<?php

namespace App\Services\WhatsApp;

use App\Models\Tenant;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Envío automático vía Meta WhatsApp Cloud API.
 */
class ApiChannel implements WhatsAppChannel
{
    protected string $version = 'v22.0';

    /**
     * Códigos de error de Meta que indican que la ventana de 24h está cerrada
     * y se requiere una plantilla aprobada.
     */
    protected array $outOfWindowCodes = [131026, 131042, 131047, 131051, 131056, 132012, 133010];

    public function name(): string
    {
        return 'api';
    }

    public function credentials(Tenant $tenant): array
    {
        $s = $tenant->settings_json ?? [];

        return [
            'enabled' => (bool) ($s['whatsapp_enabled'] ?? false),
            'phone_number_id' => $s['whatsapp_phone_number_id'] ?? '',
            'token' => $s['whatsapp_token'] ?? '',
        ];
    }

    public function isConfigured(Tenant $tenant): bool
    {
        $c = $this->credentials($tenant);

        return $c['enabled'] && $c['phone_number_id'] !== '' && $c['token'] !== '';
    }

    protected function endpoint(string $phoneNumberId): string
    {
        return "https://graph.facebook.com/{$this->version}/{$phoneNumberId}/messages";
    }

    protected function send(Tenant $tenant, string $to, array $payload): array
    {
        $c = $this->credentials($tenant);

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
                'channel' => 'api',
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
            'channel' => 'api',
            'out_of_window' => $outOfWindow,
            'error' => $message,
            'code' => $code,
        ];
    }

    public function sendText(Tenant $tenant, string $to, string $body): array
    {
        return $this->send($tenant, $to, [
            'type' => 'text',
            'text' => ['preview_url' => true, 'body' => $body],
        ]);
    }

    public function sendDocument(Tenant $tenant, string $to, string $filename, string $pdfBytes, string $caption): array
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

    public function sendTemplate(Tenant $tenant, string $to, string $templateName, array $params): array
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
}
