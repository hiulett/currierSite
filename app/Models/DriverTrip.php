<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverTrip extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'date',
        'driver_name',
        'company_name',
        'description',
        'outsourcing_cost',
        'final_client_price',
        'revenue',
        'invoice_number',
        'invoice_status',
        'driver_payment_status',
    ];

    protected $casts = [
        'date' => 'date',
        'outsourcing_cost' => 'decimal:2',
        'final_client_price' => 'decimal:2',
        'revenue' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saving(function ($trip) {
            $trip->revenue = floatval($trip->final_client_price ?? 0) - floatval($trip->outsourcing_cost ?? 0);
        });
    }
}
