<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'manifest_number',
        'carrier_name',
        'master_tracking',
        'status',
        'shipped_at',
        'estimated_arrival',
        'notes',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'estimated_arrival' => 'datetime',
    ];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}
