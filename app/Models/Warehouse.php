<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'is_active',
    ];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}
