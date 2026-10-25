<?php

namespace App\Services;

use App\Helpers\DocumentPdf;
use App\Models\AppSetting;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Tenant;
use App\Services\WhatsApp\ApiChannel;
use App\Services\WhatsApp\WebChannel;
use App\Services\WhatsApp\WhatsAppChannel;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Resuelve el canal configurado por el tenant (settings_json.whatsapp_channel).
     */
    public function channel(Tenant $tenant): WhatsAppChannel
    {
        $configured = $tenant->settings_json['whatsapp_channel'] ?? 'api';

        return $configured === 'web'
            ? app(WebChannel::class)
            : app(ApiChannel::class);
    }

    public function channelName(Tenant $tenant): string
    {
        return $this->channel($tenant)->name();
    }

    public function isConfigured(Tenant $tenant): bool
    {
        return $this->channel($tenant)->isConfigured($tenant);
    }

    protected function config(Tenant $tenant): array
    {
        $s = $tenant->settings_json ?? [];

        return [
            'business_number' => $s['whatsapp_business_number'] ?? '',
            'template_invoice' => $s['whatsapp_template_invoice'] ?? 'factura_whatsapp',
            'template_quotation' => $s['whatsapp_template_quotation'] ?? 'cotizacion_whatsapp',
            'invoice_template' => $s['whatsapp_invoice_template'] ?? "Hola {nombre_cliente},\n\nTu factura #{numero_documento} por {monto_total} está disponible.\n\nMírala o descárgala aquí: {link_documento}",
            'quotation_template' => $s['whatsapp_quotation_template'] ?? "Hola {nombre_cliente},\n\nTu cotización #{numero_documento} por {monto_total} está disponible.\n\nMírala o descárgala aquí: {link_documento}",
        ];
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

    /**
     * Aplica placeholders sobre la plantilla de texto configurada.
     */
    protected function renderBody(string $template, array $replacements): string
    {
        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }

    /**
     * Asegura que el mensaje incluya el link del portal cuando el canal no
     * puede adjuntar el PDF (WhatsApp Web).
     */
    protected function withPortalLink(string $body, string $link): string
    {
        if ($link === '' || str_contains($body, $link)) {
            return $body;
        }

        return rtrim($body)."\n\n".$link;
    }

    /**
     * Envía un mensaje de prueba para validar la configuración desde Integraciones.
     */
    public function sendTestMessage(Tenant $tenant, string $to): array
    {
        $channel = $this->channel($tenant);

        if (! $channel->isConfigured($tenant)) {
            return ['ok' => false, 'error' => 'WhatsApp no está configurado.'];
        }

        $normalized = self::normalizePhone($to);

        if (! $normalized) {
            return ['ok' => false, 'error' => 'Número de prueba inválido.'];
        }

        return $channel->sendText($tenant, $normalized, 'Prueba de conexión de WhatsApp. Si recibes este mensaje, la configuración es correcta.');
    }

    public function sendInvoice(Invoice $invoice): array
    {
        $invoice->loadMissing(['customer.user', 'tenant', 'items']);
        $tenant = $invoice->tenant;

        if (! $tenant) {
            return ['ok' => false, 'error' => 'Tenant no encontrado.'];
        }

        $channel = $this->channel($tenant);

        if (! $channel->isConfigured($tenant)) {
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
        $link = route('customer.invoices');

        $body = $this->renderBody($c['invoice_template'], [
            '{nombre_cliente}' => $customerName,
            '{numero_documento}' => $invoice->number,
            '{monto_total}' => $amount,
            '{fecha_vencimiento}' => $dueDate,
            '{nombre_empresa}' => $tenant->name ?? config('app.name'),
            '{link_documento}' => $link,
        ]);

        if ($channel->name() === 'web') {
            $body = $this->withPortalLink($body, $link);
        }

        $result = $this->deliverWithPdf(
            $channel,
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

        $channel = $this->channel($tenant);

        if (! $channel->isConfigured($tenant)) {
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
        $link = $quotation->customer_id ? route('customer.quotations') : '';

        $body = $this->renderBody($c['quotation_template'], [
            '{nombre_cliente}' => $customerName,
            '{numero_documento}' => $quotation->number,
            '{monto_total}' => $amount,
            '{fecha_vencimiento}' => '',
            '{nombre_empresa}' => $tenant->name ?? config('app.name'),
            '{link_documento}' => $link,
        ]);

        if ($channel->name() === 'web') {
            $body = $this->withPortalLink($body, $link);
        }

        $result = $this->deliverWithPdf(
            $channel,
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
     * Entrega el mensaje según el canal:
     * - WhatsApp Web: devuelve la URL wa.me para que el usuario confirme.
     * - Cloud API: adjunta PDF si whatsapp_pdf_enabled, con respaldo a plantilla.
     */
    protected function deliverWithPdf(
        WhatsAppChannel $channel,
        Tenant $tenant,
        string $to,
        string $filename,
        string $body,
        string $templateName,
        \Closure $pdfGenerator,
        array $templateParams
    ): array {
        if ($channel->name() === 'web') {
            return $channel->sendDocument($tenant, $to, $filename, '', $body);
        }

        $pdfEnabled = (bool) AppSetting::get('whatsapp_pdf_enabled', false);

        if ($pdfEnabled) {
            $result = $channel->sendDocument($tenant, $to, $filename, $pdfGenerator(), $body);
        } else {
            $result = $channel->sendText($tenant, $to, $body);
        }

        if ($result['ok'] || ! ($result['out_of_window'] ?? false)) {
            return $result;
        }

        Log::info('WhatsApp window closed, falling back to template', ['tenant_id' => $tenant->id, 'template' => $templateName]);

        return $channel->sendTemplate($tenant, $to, $templateName, $templateParams);
    }
}
