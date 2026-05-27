<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manifest extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'number',
        'carrier_name',
        'carrier_invoice_number',
        'description',
        'status',
        'created_by',
        'received_at',
        'total_items_expected',
        'total_items_received',
    ];

    public function items()
    {
        return $this->hasMany(ManifestItem::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
