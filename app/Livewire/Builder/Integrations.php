<?php

namespace App\Livewire\Builder;

use App\Models\Tenant;
use App\Services\WhatsAppService;
use Livewire\Component;

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

    // WhatsApp Business API Settings
    public $whatsapp_enabled = false;

    public $whatsapp_phone_number_id;

    public $whatsapp_token;

    public $whatsapp_business_number;

    public $whatsapp_template_invoice;

    public $whatsapp_template_quotation;

    public $whatsapp_invoice_template;

    public $whatsapp_quotation_template;

    public $whatsapp_test_number;

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

        $this->whatsapp_enabled = (bool) ($settings['whatsapp_enabled'] ?? false);
        $this->whatsapp_phone_number_id = $settings['whatsapp_phone_number_id'] ?? '';
        $this->whatsapp_token = $settings['whatsapp_token'] ?? '';
        $this->whatsapp_business_number = $settings['whatsapp_business_number'] ?? '';
        $this->whatsapp_template_invoice = $settings['whatsapp_template_invoice'] ?? 'factura_whatsapp';
        $this->whatsapp_template_quotation = $settings['whatsapp_template_quotation'] ?? 'cotizacion_whatsapp';
        $this->whatsapp_invoice_template = $settings['whatsapp_invoice_template'] ?? "Hola {nombre_cliente},\n\nTu factura #{numero_documento} por {monto_total} está disponible.\n\nMírala o descárgala aquí: {link_documento}";
        $this->whatsapp_quotation_template = $settings['whatsapp_quotation_template'] ?? "Hola {nombre_cliente},\n\nTu cotización #{numero_documento} por {monto_total} está disponible.\n\nMírala o descárgala aquí: {link_documento}";
        $this->whatsapp_test_number = $settings['whatsapp_test_number'] ?? '';
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

    public function saveWhatsapp()
    {
        $tenant = Tenant::find($this->tenant_id);
        $settings = $tenant->settings_json ?? [];

        $settings['whatsapp_enabled'] = $this->whatsapp_enabled;
        $settings['whatsapp_phone_number_id'] = $this->whatsapp_phone_number_id;
        $settings['whatsapp_token'] = $this->whatsapp_token;
        $settings['whatsapp_business_number'] = $this->whatsapp_business_number;
        $settings['whatsapp_template_invoice'] = $this->whatsapp_template_invoice;
        $settings['whatsapp_template_quotation'] = $this->whatsapp_template_quotation;
        $settings['whatsapp_invoice_template'] = $this->whatsapp_invoice_template;
        $settings['whatsapp_quotation_template'] = $this->whatsapp_quotation_template;
        $settings['whatsapp_test_number'] = $this->whatsapp_test_number;

        $tenant->update(['settings_json' => $settings]);

        session()->flash('message', 'Configuración de WhatsApp actualizada exitosamente.');
    }

    public function testConnection()
    {
        $tenant = Tenant::find($this->tenant_id);

        // Persistir lo que hay en el formulario para que la prueba use las credenciales actuales
        $settings = $tenant->settings_json ?? [];
        $settings['whatsapp_enabled'] = $this->whatsapp_enabled;
        $settings['whatsapp_phone_number_id'] = $this->whatsapp_phone_number_id;
        $settings['whatsapp_token'] = $this->whatsapp_token;
        $settings['whatsapp_business_number'] = $this->whatsapp_business_number;
        $settings['whatsapp_template_invoice'] = $this->whatsapp_template_invoice;
        $settings['whatsapp_template_quotation'] = $this->whatsapp_template_quotation;
        $settings['whatsapp_invoice_template'] = $this->whatsapp_invoice_template;
        $settings['whatsapp_quotation_template'] = $this->whatsapp_quotation_template;
        $settings['whatsapp_test_number'] = $this->whatsapp_test_number;
        $tenant->update(['settings_json' => $settings]);

        $number = $this->whatsapp_test_number ?: $this->whatsapp_business_number;

        if (! $number) {
            session()->flash('error', 'Indica un número de prueba o el número de negocio para probar la conexión.');

            return;
        }

        $result = app(WhatsAppService::class)->sendTestMessage($tenant, $number);

        if ($result['ok']) {
            session()->flash('message', '✅ Conexión exitosa. Revisa el WhatsApp de '.$number.' (ID: '.($result['message_id'] ?? 'n/a').')');
        } else {
            session()->flash('error', 'No se pudo enviar el mensaje de prueba: '.($result['error'] ?? 'Error desconocido'));
        }
    }

    public function render()
    {
        return view('livewire.builder.integrations')->layout('components.layouts.app');
    }
}
