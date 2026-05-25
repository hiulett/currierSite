<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locker extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'code',
        'status',
        'length',
        'width',
        'height',
        'max_weight',
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
}
