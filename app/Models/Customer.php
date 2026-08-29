<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'locker_id',
        'referral_code',
        'referrer_id',
        'loyalty_level_id',
        'box_number',
        'box_number_air',
        'box_number_maritime',
        'balance',
        'points',
        'rate_type',
        'is_loyalty_eligible',
        'phone',
        'identification_number',
        'address',
        'admin_notes',
        'temporary_password',
        'password_sent_at',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'password_sent_at' => 'datetime',
        'is_loyalty_eligible' => 'boolean',
    ];

    public function level()
    {
        return $this->belongsTo(LoyaltyLevel::class, 'loyalty_level_id');
    }

    public function pointsHistory()
    {
        return $this->hasMany(LoyaltyPointsHistory::class);
    }

    public function redemptions()
    {
        return $this->hasMany(RedemptionHistory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function locker()
    {
        return $this->belongsTo(Locker::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
