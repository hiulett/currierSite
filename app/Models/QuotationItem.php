<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'quotation_id',
        'item_number',
        'description',
        'quantity',
        'price',
        'handling_price',
        'total',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}
