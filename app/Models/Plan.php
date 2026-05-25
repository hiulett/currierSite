<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'price_annual',
        'price_5year',
        'limit_users',
        'limit_packages_month',
        'has_website_builder',
        'has_api_access',
        'features_json',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'features_json' => 'array',
        'has_website_builder' => 'boolean',
        'has_api_access' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'price_annual' => 'decimal:2',
        'price_5year' => 'decimal:2',
    ];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
