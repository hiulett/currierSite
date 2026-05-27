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
        'package_id',
        'status',
        'scanned_at',
        'observation',
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
