<?php

namespace App\Services\WhatsApp;

use App\Models\Tenant;
use App\Services\WhatsAppService;

/**
 * Envío manual vía WhatsApp Web.
 *
 * El backend no puede publicar en web.whatsapp.com: genera la URL wa.me con el
 * mensaje precargado y el usuario confirma el envío desde su navegador (donde
 * ya tiene la sesión de WhatsApp Web iniciada).
 *
 * Los archivos adjuntos no se pueden precargar por URL, por eso el mensaje
 * siempre incluye el link al portal del cliente, donde descarga el PDF.
 */
class WebChannel implements WhatsAppChannel
{
    public function name(): string
    {
        return 'web';
    }

    /**
     * WhatsApp Web no requiere credenciales de Meta.
     */
    public function isConfigured(Tenant $tenant): bool
    {
        return true;
    }

    public function sendText(Tenant $tenant, string $to, string $body): array
    {
        $url = self::url($to, $body);

        if (! $url) {
            return ['ok' => false, 'channel' => 'web', 'error' => 'El cliente no tiene un teléfono registrado.'];
        }

        return ['ok' => true, 'channel' => 'web', 'url' => $url, 'manual' => true];
    }

    /**
     * WhatsApp Web no permite adjuntar archivos desde una URL: el caption ya
     * contiene el link de descarga del PDF en el portal.
     */
    public function sendDocument(Tenant $tenant, string $to, string $filename, string $pdfBytes, string $caption): array
    {
        return $this->sendText($tenant, $to, $caption);
    }

    /**
     * Las plantillas aprobadas solo existen para la Cloud API.
     */
    public function sendTemplate(Tenant $tenant, string $to, string $templateName, array $params): array
    {
        return [
            'ok' => false,
            'channel' => 'web',
            'out_of_window' => false,
            'error' => 'El canal WhatsApp Web no usa plantillas aprobadas.',
        ];
    }

    /**
     * Construye el enlace de WhatsApp Web con el mensaje precargado.
     *
     * Se usa web.whatsapp.com (no wa.me) para reutilizar la sesión ya
     * autenticada del navegador y evitar el salto a la app de escritorio.
     */
    public static function url(?string $phone, string $body): ?string
    {
        $normalized = WhatsAppService::normalizePhone($phone);
        $digits = preg_replace('/\D/', '', (string) $normalized);

        if ($digits === '') {
            return null;
        }

        return 'https://web.whatsapp.com/send?phone='.$digits.'&text='.rawurlencode($body);
    }
}
