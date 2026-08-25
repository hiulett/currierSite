<?php

namespace App\Services;

use App\Helpers\DocumentPdf;
use App\Models\AppSetting;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Tenant;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $version = 'v22.0';

    /**
     * Códigos de error de Meta que indican que la ventana de 24h está cerrada
     * y se requiere una plantilla aprobada.
     */
    protected array $outOfWindowCodes = [131026, 131042, 131047, 131051, 131056, 132012, 133010];

    protected function config(Tenant $tenant): array
    {
        $s = $tenant->settings_json ?? [];

        return [
            'enabled' => (bool) ($s['whatsapp_enabled'] ?? false),
            'phone_number_id' => $s['whatsapp_phone_number_id'] ?? '',
            'token' => $s['whatsapp_token'] ?? '',
            'business_number' => $s['whatsapp_business_number'] ?? '',
            'template_invoice' => $s['whatsapp_template_invoice'] ?? 'factura_whatsapp',
            'template_quotation' => $s['whatsapp_template_quotation'] ?? 'cotizacion_whatsapp',
            'invoice_template' => $s['whatsapp_invoice_template'] ?? "Hola {nombre_cliente},\n\nTu factura #{numero_documento} por {monto_total} está disponible.\n\nMírala o descárgala aquí: {link_documento}",
            'quotation_template' => $s['whatsapp_quotation_template'] ?? "Hola {nombre_cliente},\n\nTu cotización #{numero_documento} por {monto_total} está disponible.\n\nMírala o descárgala aquí: {link_documento}",
        ];
    }

    public function isConfigured(Tenant $tenant): bool
    {
        $c = $this->config($tenant);

        return $c['enabled'] && $c['phone_number_id'] !== '' && $c['token'] !== '';
    }

    /**
     * Normaliza un teléfono a formato E.164 (+507...).
     * Si el número lleva "+" explícito, se respeta el código de país indicado.
     * Los números locales (sin "+") asumen Panamá (+507).
     */
    public static function normalizePhone(?string $phone): ?string
    {
        if (! $phone) {
            return null;
        }

        $raw = trim($phone);

        // Número con código de país explícito (+507, +1, +34...)
        if (str_starts_with($raw, '+')) {
            $digits = preg_replace('/[^0-9]/', '', $raw);

            return $digits === '' ? null : '+'.$digits;
        }

        $digits = preg_replace('/[^0-9]/', '', $raw);

        if ($digits === '') {
            return null;
        }

        if (! str_starts_with($digits, '507')) {
            $digits = '507'.$digits;
        }

        return '+'.$digits;
    }

    protected function endpoint(string $phoneNumberId): string
    {
        return "https://graph.facebook.com/{$this->version}/{$phoneNumberId}/messages";
    }

    protected function send(Tenant $tenant, string $to, array $payload): array
    {
        $c = $this->config($tenant);

        $payload['to'] = $to;
        $payload['messaging_product'] = 'whatsapp';

        try {
            $response = Http::withToken($c['token'])
                ->acceptJson()
                ->post($this->endpoint($c['phone_number_id']), $payload);
        } catch (\Throwable $e) {
            Log::error('WhatsApp request exception', ['tenant_id' => $tenant->id, 'error' => $e->getMessage()]);

            return ['ok' => false, 'out_of_window' => false, 'error' => $e->getMessage()];
        }

        $body = $response->json() ?? [];

        if ($response->successful()) {
            return [
                'ok' => true,
                'message_id' => $body['messages'][0]['id'] ?? null,
            ];
        }

        $error = $body['error'] ?? [];
        $code = $error['code'] ?? null;
        $subcode = $error['error_subcode'] ?? null;
        $message = $error['message'] ?? $response->body();

        Log::warning('WhatsApp send failed', [
            'tenant_id' => $tenant->id,
            'code' => $code,
            'error_subcode' => $subcode,
            'message' => $message,
        ]);

        $outOfWindow = $code === 429 || in_array($code, $this->outOfWindowCodes);

        return [
            'ok' => false,
            'out_of_window' => $outOfWindow,
            'error' => $message,
            'code' => $code,
        ];
    }

    protected function sendText(Tenant $tenant, string $to, string $body): array
    {
        return $this->send($tenant, $to, [
            'type' => 'text',
            'text' => ['preview_url' => true, 'body' => $body],
        ]);
    }

    protected function sendDocument(Tenant $tenant, string $to, string $filename, string $pdfBytes, string $caption): array
    {
        return $this->send($tenant, $to, [
            'type' => 'document',
            'document' => [
                'filename' => $filename,
                'caption' => $caption,
                'data' => base64_encode($pdfBytes),
            ],
        ]);
    }

    protected function sendTemplate(Tenant $tenant, string $to, string $templateName, array $params): array
    {
        return $this->send($tenant, $to, [
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => ['code' => 'es'],
                'components' => [
                    ['type' => 'body', 'parameters' => array_map(fn ($v) => ['text' => (string) $v], $params)],
                ],
            ],
        ]);
    }

    /**
     * Aplica placeholders sobre la plantilla de texto configurada.
     */
    protected function renderBody(string $template, array $replacements): string
    {
        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }

    /**
     * Envía un mensaje de prueba para validar credenciales desde Integraciones.
     */
    public function sendTestMessage(Tenant $tenant, string $to): array
    {
        if (! $this->isConfigured($tenant)) {
            return ['ok' => false, 'error' => 'WhatsApp no está configurado.'];
        }

        $normalized = self::normalizePhone($to);

        if (! $normalized) {
            return ['ok' => false, 'error' => 'Número de prueba inválido.'];
        }

        return $this->sendText($tenant, $normalized, 'Prueba de conexión de WhatsApp. Si recibes este mensaje, la configuración es correcta.');
    }

    public function sendInvoice(Invoice $invoice): array
    {
        $invoice->loadMissing(['customer.user', 'tenant', 'items']);
        $tenant = $invoice->tenant;

        if (! $tenant) {
            return ['ok' => false, 'error' => 'Tenant no encontrado.'];
        }
        if (! $this->isConfigured($tenant)) {
            return ['ok' => false, 'error' => 'WhatsApp no está configurado en Integraciones.'];
        }

        $to = self::normalizePhone($invoice->customer?->phone);
        if (! $to) {
            return ['ok' => false, 'error' => 'El cliente no tiene un teléfono registrado.'];
        }

        $c = $this->config($tenant);
        $currency = $tenant->settings_json['currency'] ?? 'USD';
        $customerName = $invoice->customer?->user?->name ?? 'Cliente';
        $amount = $currency.' '.number_format($invoice->total, 2);
        $dueDate = $invoice->due_date ? $invoice->due_date->format('d/m/Y') : 'N/A';
        $link = url('/portal/facturas');

        $body = $this->renderBody($c['invoice_template'], [
            '{nombre_cliente}' => $customerName,
            '{numero_documento}' => $invoice->number,
            '{monto_total}' => $amount,
            '{fecha_vencimiento}' => $dueDate,
            '{nombre_empresa}' => $tenant->name ?? config('app.name'),
            '{link_documento}' => $link,
        ]);

        $result = $this->deliverWithPdf(
            $tenant,
            $to,
            'Factura_'.$invoice->number.'.pdf',
            $body,
            $c['template_invoice'],
            fn () => DocumentPdf::renderInvoicePdf($invoice)->output(),
            [$customerName, $invoice->number, $amount, $dueDate]
        );

        if ($result['ok']) {
            $invoice->update(['whatsapp_sent_at' => now()]);
        }

        return $result;
    }

    public function sendQuotation(Quotation $quotation): array
    {
        $quotation->loadMissing(['customer.user', 'tenant', 'items']);
        $tenant = $quotation->tenant;

        if (! $tenant) {
            return ['ok' => false, 'error' => 'Tenant no encontrado.'];
        }
        if (! $this->isConfigured($tenant)) {
            return ['ok' => false, 'error' => 'WhatsApp no está configurado en Integraciones.'];
        }

        $to = self::normalizePhone($quotation->customer?->phone ?? $quotation->client_phone);
        if (! $to) {
            return ['ok' => false, 'error' => 'El cliente no tiene un teléfono registrado.'];
        }

        $c = $this->config($tenant);
        $currency = $tenant->settings_json['currency'] ?? 'USD';
        $customerName = $quotation->customer?->user?->name ?? $quotation->client_name ?? 'Cliente';
        $amount = $currency.' '.number_format($quotation->total, 2);
        // El link al portal solo aplica a clientes con cuenta
        $link = $quotation->customer_id ? url('/portal/cotizaciones') : '';

        $body = $this->renderBody($c['quotation_template'], [
            '{nombre_cliente}' => $customerName,
            '{numero_documento}' => $quotation->number,
            '{monto_total}' => $amount,
            '{fecha_vencimiento}' => '',
            '{nombre_empresa}' => $tenant->name ?? config('app.name'),
            '{link_documento}' => $link,
        ]);

        $result = $this->deliverWithPdf(
            $tenant,
            $to,
            'Cotizacion_'.$quotation->number.'.pdf',
            $body,
            $c['template_quotation'],
            fn () => DocumentPdf::renderQuotationPdf($quotation)->output(),
            [$customerName, $quotation->number, $amount, $link]
        );

        if ($result['ok']) {
            $quotation->update(['whatsapp_sent_at' => now()]);
        }

        return $result;
    }

    /**
     * Decide la estrategia de entrega según el flag global del superadmin:
     * - whatsapp_pdf_enabled=false  → texto + link
     * - whatsapp_pdf_enabled=true   → documento PDF (+ caption)
     * En ambos casos, si la ventana de 24h está cerrada, cae a plantilla aprobada.
     */
    protected function deliverWithPdf(
        Tenant $tenant,
        string $to,
        string $filename,
        string $body,
        string $templateName,
        \Closure $pdfGenerator,
        array $templateParams
    ): array {
        $pdfEnabled = (bool) AppSetting::get('whatsapp_pdf_enabled', false);

        if ($pdfEnabled) {
            $result = $this->sendDocument($tenant, $to, $filename, $pdfGenerator(), $body);
        } else {
            $result = $this->sendText($tenant, $to, $body);
        }

        if ($result['ok'] || ! $result['out_of_window']) {
            return $result;
        }

        Log::info('WhatsApp window closed, falling back to template', ['tenant_id' => $tenant->id, 'template' => $templateName]);

        return $this->sendTemplate($tenant, $to, $templateName, $templateParams);
    }
}
