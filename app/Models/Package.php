<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'parent_id',
        'customer_id',
        'warehouse_id',
        'shipment_id',
        'delivery_id',
        'tracking_number',
        'internal_tracking',
        'description',
        'weight',
        'length',
        'width',
        'height',
        'volumetric_weight',
        'declared_value',
        'status',
        'delivery_type',
        'delivered_at',
        'delivered_to',
        'is_repacked',
        'images_json',
        'invoice_url',
    ];

    protected $casts = [
        'images_json' => 'array',
        'is_repacked' => 'boolean',
        'delivered_at' => 'datetime',
    ];

    public function parent()
    {
        return $this->belongsTo(Package::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Package::class, 'parent_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function trackingEvents()
    {
        return $this->hasMany(TrackingEvent::class);
    }

    public function getStatusAttribute($value)
    {
        return $value;
    }

    protected static $cached_statuses = null;

    public function getStatusLabel()
    {
        if (self::$cached_statuses === null) {
            self::$cached_statuses = PackageStatus::all()->keyBy('name');
        }

        if (isset(self::$cached_statuses[$this->status])) {
            return self::$cached_statuses[$this->status]->label;
        }

        $defaults = PackageStatus::getDefaults();
        return $defaults[$this->status]['label'] ?? ucfirst($this->status);
    }

    public function getStatusColor()
    {
        if (self::$cached_statuses === null) {
            self::$cached_statuses = PackageStatus::all()->keyBy('name');
        }

        if (isset(self::$cached_statuses[$this->status])) {
            return self::$cached_statuses[$this->status]->color;
        }

        $defaults = PackageStatus::getDefaults();
        return $defaults[$this->status]['color'] ?? '#6c757d';
    }

    public function getStatusIcon()
    {
        if (self::$cached_statuses === null) {
            self::$cached_statuses = PackageStatus::all()->keyBy('name');
        }

        if (isset(self::$cached_statuses[$this->status])) {
            return self::$cached_statuses[$this->status]->icon;
        }

        $defaults = PackageStatus::getDefaults();
        return $defaults[$this->status]['icon'] ?? 'package';
    }

    protected static function booted()
    {
        static::updated(function ($package) {
            if ($package->isDirty('status')) {
                // 1. Create Tracking Event
                $package->trackingEvents()->create([
                    'tenant_id' => $package->tenant_id,
                    'status' => $package->status,
                    'user_id' => auth()->id(),
                    'location' => $package->warehouse ? $package->warehouse->name : null,
                ]);

                // 2. Notify Customer (exclude initial received/prealert as they are handled elsewhere)
                $notifiableStatuses = ['in_transit', 'arrived', 'ready_for_pickup', 'out_for_delivery', 'delivered'];
                if (in_array($package->status, $notifiableStatuses) && $package->customer && $package->customer->user) {
                    $package->customer->user->notify(new \App\Notifications\PackageStatusNotification($package, $package->status));
                }
            }
        });

        static::created(function ($package) {
            $package->trackingEvents()->create([
                'tenant_id' => $package->tenant_id,
                'status' => $package->status,
                'user_id' => auth()->id(),
                'location' => $package->warehouse ? $package->warehouse->name : null,
                'notes' => 'Registro inicial del paquete',
            ]);
        });
    }
}
