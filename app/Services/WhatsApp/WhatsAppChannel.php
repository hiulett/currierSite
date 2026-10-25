<?php

namespace App\Services\WhatsApp;

use App\Models\Tenant;

/**
 * Canal de entrega de mensajes de WhatsApp.
 *
 * Implementaciones:
 * - ApiChannel: Meta WhatsApp Cloud API (envío automático).
 * - WebChannel: WhatsApp Web (abre wa.me y el usuario confirma el envío).
 */
interface WhatsAppChannel
{
    /** Identificador persistido en settings_json.whatsapp_channel. */
    public function name(): string;

    public function isConfigured(Tenant $tenant): bool;

    public function sendText(Tenant $tenant, string $to, string $body): array;

    public function sendDocument(Tenant $tenant, string $to, string $filename, string $pdfBytes, string $caption): array;

    public function sendTemplate(Tenant $tenant, string $to, string $templateName, array $params): array;
}
