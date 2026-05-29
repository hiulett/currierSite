<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProof extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'invoice_id',
        'file_path',
        'method',
        'status',
        'rejection_reason',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
