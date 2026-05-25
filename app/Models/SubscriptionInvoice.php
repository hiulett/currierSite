<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionInvoice extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriptionInvoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'number',
        'amount',
        'status',
        'due_date',
        'paid_at',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
