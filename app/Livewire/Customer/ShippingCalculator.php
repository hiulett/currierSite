<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Tenant;

class ShippingCalculator extends Component
{
    public $service_type = 'air';
    public $weight = 1;
    public $declared_value = 0;
    public $length = 0;
    public $width = 0;
    public $height = 0;

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
        ]);

        $tenant = Tenant::find(session('tenant_id'));
        $settings = $tenant->settings_json ?? [];

        // Base rate per pound (usually different for air/maritime)
        $rate = $this->service_type === 'air'
            ? ($settings['air_rate'] ?? 2.50)
            : ($settings['maritime_rate'] ?? 1.50);

        // Volumetric Weight (L * W * H / 166 or similar)
        $volumetric_weight = ($this->length * $this->width * $this->height) / 166;
        $chargeable_weight = max($this->weight, $volumetric_weight);

        $shipping_cost = $chargeable_weight * $rate;

        // Custom Duties removed
        $customs_duty = 0;
        $tax_percentage = 0;

        $other_charges_settings = $settings['other_charges'] ?? [];
        $applied_charges = [];
        $total_other_charges = 0;

        foreach ($other_charges_settings as $charge) {
            $value = floatval($charge['value'] ?? 0);
            if ($charge['type'] === 'percentage') {
                $amount = $shipping_cost * ($value / 100);
            } else {
                $amount = $value;
            }
            
            if ($amount > 0) {
                $applied_charges[] = [
                    'name' => $charge['name'] ?? 'Cargo Extra',
                    'amount' => $amount
                ];
                $total_other_charges += $amount;
            }
        }

        $total = $shipping_cost + $customs_duty + $total_other_charges;

        $this->result = [
            'shipping_cost' => $shipping_cost,
            'customs_duty' => $customs_duty,
            'applied_charges' => $applied_charges,
            'total' => $total,
            'chargeable_weight' => $chargeable_weight,
            'is_volumetric' => $volumetric_weight > $this->weight,
            'rate' => $rate,
            'tax_percentage' => $tax_percentage
        ];
    }

    public function render()
    {
        $tenant = Tenant::find(session('tenant_id')) ?? Tenant::first();
        $this->airRate = $tenant->settings_json['air_rate'] ?? 2.50;
        $airEnabled = $tenant->settings_json['service_air_enabled'] ?? true;
        $maritimeEnabled = $tenant->settings_json['service_maritime_enabled'] ?? true;

        return view('livewire.customer.shipping-calculator', [
            'airEnabled' => $airEnabled,
            'maritimeEnabled' => $maritimeEnabled,
        ])->layout('components.customer-layout');
    }
}
