<?php

namespace App\Services\WhatsApp;

use App\Jobs\SendInvoiceWhatsApp;
use App\Jobs\SendQuotationWhatsApp;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Tenant;
use App\Services\WhatsAppService;

/**
 * Punto único que usan los componentes Livewire para enviar documentos.
 *
 * - Canal API: encola el Job existente (envío automático en background).
 * - Canal Web: devuelve la URL wa.me para que el componente redirija al usuario.
 */
class DocumentSender
{
    public function __construct(protected WhatsAppService $whatsapp) {}

    /**
     * @return array{mode: string, url: ?string, error: ?string}
     */
    public function invoice(Invoice $invoice): array
    {
        $tenant = Tenant::find($invoice->tenant_id);

        if (! $tenant) {
            return $this->error('Tenant no encontrado.');
        }

        $customer = $invoice->customer_id ? Customer::find($invoice->customer_id) : null;

        if ($this->whatsapp->channelName($tenant) === 'api') {
            if (! $this->whatsapp->isConfigured($tenant)) {
                return $this->error('WhatsApp no está configurado. Configúralo en Configuración → Pagos e Integraciones.');
            }

            if (! $customer?->phone) {
                return $this->error('El cliente no tiene un teléfono registrado.');
            }            SendInvoiceWhatsApp::dispatch($invoice);

            return ['mode' => 'api', 'url' => null, 'error' => null];
        }

        $result = $this->whatsapp->sendInvoice($invoice);

        return [
            'mode' => 'web',
            'url' => $result['url'] ?? null,
            'error' => $result['ok'] ? null : ($result['error'] ?? 'No se pudo preparar el envío por WhatsApp Web.'),
        ];
    }

    /**
     * @return array{mode: string, url: ?string, error: ?string}
     */
    public function quotation(Quotation $quotation): array
    {
        $tenant = Tenant::find($quotation->tenant_id);

        if (! $tenant) {
            return $this->error('Tenant no encontrado.');
        }

        $customerPhone = $quotation->customer_id
            ? Customer::query()->whereKey($quotation->customer_id)->value('phone')
            : null;
        $phone = $customerPhone ?: $quotation->client_phone;
        if ($this->whatsapp->channelName($tenant) === 'api') {
            if (! $this->whatsapp->isConfigured($tenant)) {
                return $this->error('WhatsApp no está configurado. Configúralo en Configuración → Pagos e Integraciones.');
            }

            if (! $phone) {
                return $this->error('El cliente no tiene un teléfono registrado.');
            }

            SendQuotationWhatsApp::dispatch($quotation);

            return ['mode' => 'api', 'url' => null, 'error' => null];
        }

        $result = $this->whatsapp->sendQuotation($quotation);

        return [
            'mode' => 'web',
            'url' => $result['url'] ?? null,
            'error' => $result['ok'] ? null : ($result['error'] ?? 'No se pudo preparar el envío por WhatsApp Web.'),
        ];
    }

    /**
     * @return array{mode: string, url: ?string, error: ?string}
     */
    protected function error(string $message): array
    {
        return ['mode' => 'error', 'url' => null, 'error' => $message];
    }
}
