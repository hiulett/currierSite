<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'number',
        'subtotal',
        'tax',
        'discount',
        'total',
        'status',
        'currency',
        'due_date',
        'paid_at',
        'payment_method',
        'payment_reference',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
