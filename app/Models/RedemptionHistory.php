<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedemptionHistory extends Model
{
    use BelongsToTenant, HasFactory;

    protected $table = 'redemption_history';

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'reward_id',
        'points_spent',
        'reward_name',
        'free_pounds_granted',
        'status',
        'redeemed_at',
        'redeemed_by_user_id',
        'notes',
    ];

    protected $casts = [
        'points_spent' => 'integer',
        'free_pounds_granted' => 'float',
        'redeemed_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class, 'reward_id');
    }
}
