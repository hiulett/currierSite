<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'driver_id',
        'route_name',
        'status',
        'started_at',
        'completed_at',
        'signature_path',
        'photo_path',
        'latitude',
        'longitude',
        'cod_amount',
        'cod_collected',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}
