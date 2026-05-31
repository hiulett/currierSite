<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $isGlobalDomain = in_array($host, ['localhost', '127.0.0.1', 'logisaas.test']);

        // 1. Try to find tenant by domain or subdomain
        $tenant = Tenant::where('domain', $host)
            ->orWhere('subdomain', explode('.', $host)[0])
            ->first();

        // [IMPERSONATION OVERRIDE]
        if (session()->has('impersonate_tenant_id')) {
            $tenant = Tenant::find(session('impersonate_tenant_id'));
        }

        // 2. Local/Session/Production fallbacks
        if (!$tenant) {
            if (session()->has('tenant_id')) {
                $tenant = Tenant::find(session('tenant_id'));
            } elseif (auth()->check() && auth()->user()->tenant_id) {
                $tenant = Tenant::find(auth()->user()->tenant_id);
            } else {
                // If on Railway/Cloud and no tenant identified, use the first one as a safety fallback
                $tenant = Tenant::first();
            }
        }

        // 3. Apply Tenant Context
        if ($tenant) {
            session(['tenant_id' => $tenant->id]);
            config(['app.name' => $tenant->name]);

            // Critical: Set the locale globally
            $locale = $tenant->locale ?? config('app.locale');
            app()->setLocale($locale);

            // Also ensure it's in session for Livewire or other parts
            session(['locale' => $locale]);

            // 4. Dynamic Mail Configuration (SMTP Override)
            $settings = $tenant->settings_json ?? [];
            if (!empty($settings['mail_host'])) {
                config([
                    'mail.mailers.smtp.host' => $settings['mail_host'],
                    'mail.mailers.smtp.port' => $settings['mail_port'] ?? '587',
                    'mail.mailers.smtp.username' => $settings['mail_username'],
                    'mail.mailers.smtp.password' => $settings['mail_password'],
                    'mail.mailers.smtp.encryption' => $settings['mail_encryption'] ?? 'tls',
                    'mail.from.address' => $settings['mail_from_address'] ?? 'no-reply@logisaas.com',
                    'mail.from.name' => $settings['mail_from_name'] ?? $tenant->name,
                ]);
            }

            // 5. Dynamic Payment Configuration (Stripe/PayPal Override)
            if (!empty($settings['stripe_secret'])) {
                config([
                    'services.stripe.key' => $settings['stripe_key'],
                    'services.stripe.secret' => $settings['stripe_secret'],
                ]);
            }
            if (!empty($settings['paypal_mode'])) {
                config([
                    'paypal.mode' => $settings['paypal_mode'],
                    'paypal.sandbox.client_id' => $settings['paypal_sandbox_client_id'] ?? '',
                    'paypal.sandbox.client_secret' => $settings['paypal_sandbox_client_secret'] ?? '',
                    'paypal.live.client_id' => $settings['paypal_live_client_id'] ?? '',
                    'paypal.live.client_secret' => $settings['paypal_live_client_secret'] ?? '',
                ]);
            }
        }

        return $next($request);
    }
}
