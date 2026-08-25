<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'number',
        'subtotal',
        'tax',
        'discount',
        'total',
        'status',
        'service_type',
        'currency',
        'due_date',
        'paid_at',
        'payment_method',
        'payment_reference',
        'notes',
        'email_sent_at',
        'whatsapp_sent_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'whatsapp_sent_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function isOverdue()
    {
        return $this->status === 'unpaid' && $this->due_date && $this->due_date->lt(now()->today());
    }

    public function getStatusLabel()
    {
        return match ($this->status) {
            'paid' => 'Pagada',
            'unpaid' => $this->isOverdue() ? 'Vencida' : 'Pendiente',
            'cancelled' => 'Anulada',
            default => ucfirst((string) $this->status),
        };
    }

    public function getStatusColor()
    {
        return match ($this->status) {
            'paid' => '#1cbb8c',
            'unpaid' => $this->isOverdue() ? '#dc3545' : '#fcb92c',
            'cancelled' => '#6c757d',
            default => '#6c757d',
        };
    }

    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            'paid' => 'bg-success',
            'unpaid' => $this->isOverdue() ? 'bg-danger' : 'bg-warning text-dark',
            'cancelled' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }
}
