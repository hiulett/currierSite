<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'client_name',
        'client_email',
        'client_phone',
        'number',
        'subtotal',
        'handling_total',
        'total',
        'status',
        'service_type',
        'notes',
        'whatsapp_sent_at',
    ];

    protected $casts = [
        'whatsapp_sent_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
