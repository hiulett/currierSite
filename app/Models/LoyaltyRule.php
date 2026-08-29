<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyRule extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'points_per_pound',
        'enabled',
        'apply_on',
        'eligible_rate_types',
    ];

    protected $casts = [
        'points_per_pound' => 'float',
        'enabled' => 'boolean',
        'eligible_rate_types' => 'array',
    ];
}
