<?php

namespace App\Services;

use App\Models\Tenant;

/**
 * Aplica la configuración de correo del tenant a Laravel.
 *
 * Es la única fuente de verdad: la usan tanto el middleware IdentifyTenant
 * (peticiones HTTP) como Tenant::setMailConfig() (worker de colas y envíos
 * puntuales). Antes el worker no fijaba mail.default y los correos salían
 * por el mailer global (p. ej. "log") en lugar del SMTP del tenant.
 */
class TenantMailConfigurator
{
    public function apply(Tenant $tenant): void
    {
        $settings = $tenant->settings_json ?? [];

        config([
            'app.name' => $tenant->name,
            // Logo del tenant para el encabezado de los correos (vendor/mail/html/header).
            'app.tenant_logo_url' => $tenant->getLogoUrl(),
        ]);

        $host = trim((string) ($settings['mail_host'] ?? ''));

        if ($host === '') {
            return;
        }

        config([
            // El canal "mail" de Laravel siempre sale por SMTP con las
            // credenciales del tenant. La API de SendGrid se usa solo en el
            // botón de prueba (HTTP directo).
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $host,
            'mail.mailers.smtp.port' => trim((string) ($settings['mail_port'] ?? '587')),
            'mail.mailers.smtp.username' => trim((string) ($settings['mail_username'] ?? '')),
            'mail.mailers.smtp.password' => (string) ($settings['mail_password'] ?? ''),
            'mail.mailers.smtp.encryption' => trim((string) ($settings['mail_encryption'] ?? 'tls')),
            'mail.mailers.smtp.timeout' => 15,
            'mail.from.address' => trim((string) ($settings['mail_from_address'] ?? 'no-reply@logisaas.com')),
            'mail.from.name' => trim((string) ($settings['mail_from_name'] ?? $tenant->name)),
        ]);

        if (app()->bound('mail.manager')) {
            app('mail.manager')->forgetMailers();
        }
    }
}
