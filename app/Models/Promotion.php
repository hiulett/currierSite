<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'type',
        'value',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'is_active',
        'rules_json',
    ];

    protected $casts = [
        'rules_json' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function isValid()
    {
        if (!$this->is_active) return false;

        $now = now()->startOfDay();
        if ($this->start_date && $this->start_date->gt($now)) return false;
        if ($this->end_date && $this->end_date->lt($now)) return false;

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;

        return true;
    }

    public function calculateDiscount($amount)
    {
        if ($this->type === 'percentage') {
            return ($amount * $this->value) / 100;
        }

        return min($amount, $this->value);
    }
}
