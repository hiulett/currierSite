<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyLevel extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'min_points',
        'max_points',
        'multiplier',
        'free_pounds',
        'icon',
        'color',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'free_pounds' => 'integer',
        'is_active' => 'boolean',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
