<?php

namespace App\Livewire\Builder;

use Livewire\Component;
use App\Models\Tenant;

class Integrations extends Component
{
    public $tenant_id;
    public $baseUrl;

    // Payment Gateway Settings
    public $stripe_key;
    public $stripe_secret;
    public $paypal_mode = 'sandbox';
    public $paypal_sandbox_client_id;
    public $paypal_sandbox_client_secret;
    public $paypal_live_client_id;
    public $paypal_live_client_secret;

    public function mount()
    {
        $this->tenant_id = session('tenant_id');
        $this->baseUrl = url('/');

        $tenant = Tenant::find($this->tenant_id);
        $settings = $tenant->settings_json ?? [];

        $this->stripe_key = $settings['stripe_key'] ?? '';
        $this->stripe_secret = $settings['stripe_secret'] ?? '';
        $this->paypal_mode = $settings['paypal_mode'] ?? 'sandbox';
        $this->paypal_sandbox_client_id = $settings['paypal_sandbox_client_id'] ?? '';
        $this->paypal_sandbox_client_secret = $settings['paypal_sandbox_client_secret'] ?? '';
        $this->paypal_live_client_id = $settings['paypal_live_client_id'] ?? '';
        $this->paypal_live_client_secret = $settings['paypal_live_client_secret'] ?? '';
    }

    public function savePayments()
    {
        $tenant = Tenant::find($this->tenant_id);
        $settings = $tenant->settings_json ?? [];

        $settings['stripe_key'] = $this->stripe_key;
        $settings['stripe_secret'] = $this->stripe_secret;
        $settings['paypal_mode'] = $this->paypal_mode;
        $settings['paypal_sandbox_client_id'] = $this->paypal_sandbox_client_id;
        $settings['paypal_sandbox_client_secret'] = $this->paypal_sandbox_client_secret;
        $settings['paypal_live_client_id'] = $this->paypal_live_client_id;
        $settings['paypal_live_client_secret'] = $this->paypal_live_client_secret;

        $tenant->update(['settings_json' => $settings]);

        session()->flash('message', 'Configuración de pagos actualizada exitosamente.');
    }

    public function render()
    {
        return view('livewire.builder.integrations')->layout('components.layouts.app');
    }
}
