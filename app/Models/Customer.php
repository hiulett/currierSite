<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'locker_id',
        'referrer_id',
        'loyalty_level_id',
        'box_number',
        'box_number_air',
        'box_number_maritime',
        'balance',
        'points',
        'phone',
        'identification_number',
        'address',
        'admin_notes',
        'temporary_password',
        'latitude',
        'longitude',
    ];

    public function level()
    {
        return $this->belongsTo(LoyaltyLevel::class, 'loyalty_level_id');
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
