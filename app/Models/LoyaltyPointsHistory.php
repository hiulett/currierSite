<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyPointsHistory extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'loyalty_points_history';

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'points',
        'type',
        'description',
        'reference_id',
        'reference_type',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
