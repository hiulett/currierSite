<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssistedPurchase extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'url',
        'description',
        'estimated_price',
        'status',
        'commission',
        'admin_notes',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
