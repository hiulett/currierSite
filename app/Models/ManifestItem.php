<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManifestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'manifest_id',
        'tenant_id',
        'tracking_number',
        'weight',
        'length',
        'width',
        'height',
        'package_id',
        'status',
        'scanned_at',
        'observation',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
    ];

    public function manifest()
    {
        return $this->belongsTo(Manifest::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
