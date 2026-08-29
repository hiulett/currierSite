<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use BelongsToTenant, HasFactory;

    protected $table = 'reward_catalog';

    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'points_cost',
        'value',
        'description',
        'image_url',
        'icon',
        'is_active',
        'stock',
        'sort_order',
    ];

    protected $casts = [
        'points_cost' => 'integer',
        'value' => 'float',
        'is_active' => 'boolean',
        'stock' => 'integer',
    ];

    public function redemptions()
    {
        return $this->hasMany(RedemptionHistory::class, 'reward_id');
    }
}
