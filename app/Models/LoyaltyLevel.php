<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyLevel extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'min_points',
        'max_points',
        'multiplier',
        'icon',
        'color',
        'priority',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
