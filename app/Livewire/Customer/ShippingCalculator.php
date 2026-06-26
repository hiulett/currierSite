<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Tenant;
use App\Models\TaxCategory;

class ShippingCalculator extends Component
{
    public $service_type = 'air';
    public $weight = 1;
    public $declared_value = 0;
    public $length = 0;
    public $width = 0;
    public $height = 0;
    public $category_id = '';

    public $result = null;

    public function calculate()
    {
        $this->length = $this->length === '' ? null : $this->length;
        $this->width = $this->width === '' ? null : $this->width;
        $this->height = $this->height === '' ? null : $this->height;
        $this->declared_value = $this->declared_value === '' ? 0 : $this->declared_value;

        $this->validate([
            'service_type' => 'required|in:air,maritime',
            'weight' => 'required|numeric|min:0.1',
            'declared_value' => 'required|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:tax_categories,id',
        ]);

        $tenant = Tenant::find(session('tenant_id'));
        $settings = $tenant->settings_json ?? [];

        // Base rate per pound (usually different for air/maritime)
        $rate = $this->service_type === 'air'
            ? ($settings['default_rate'] ?? 2.50)
            : ($settings['maritime_rate'] ?? 1.50);

        // Volumetric Weight (L * W * H / 166 or similar)
        $volumetric_weight = ($this->length * $this->width * $this->height) / 166;
        $chargeable_weight = max($this->weight, $volumetric_weight);

        $shipping_cost = $chargeable_weight * $rate;

        // Custom Duties
        $category = TaxCategory::find($this->category_id);
        $tax_percentage = $category->percentage;
        $customs_duty = $this->declared_value * ($tax_percentage / 100);

        // Fuel Surcharge (Sample logic)
        $fuel_surcharge = $shipping_cost * 0.05;

        $total = $shipping_cost + $customs_duty + $fuel_surcharge;

        $this->result = [
            'shipping_cost' => $shipping_cost,
            'customs_duty' => $customs_duty,
            'fuel_surcharge' => $fuel_surcharge,
            'total' => $total,
            'chargeable_weight' => $chargeable_weight,
            'is_volumetric' => $volumetric_weight > $this->weight,
            'rate' => $rate,
            'tax_percentage' => $tax_percentage
        ];
    }

    public function render()
    {
        $tenant = Tenant::find(session('tenant_id'));
        $airEnabled = $tenant->settings_json['service_air_enabled'] ?? true;
        $maritimeEnabled = $tenant->settings_json['service_maritime_enabled'] ?? true;

        return view('livewire.customer.shipping-calculator', [
            'categories' => TaxCategory::all(),
            'airEnabled' => $airEnabled,
            'maritimeEnabled' => $maritimeEnabled,
        ])->layout('components.customer-layout');
    }
}
