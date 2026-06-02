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
        $subdomain = explode('.', $host)[0];

        // 1. Try to find tenant by domain or subdomain
        $tenant = null;

        // AUTO-IDENTIFY ON LOCALHOST
        if (in_array($subdomain, ['localhost', '127'])) {
            $tenant = Tenant::first();
        } elseif ($subdomain !== 'curriersite-production') {
            $tenant = Tenant::where('domain', $host)
                ->orWhere('subdomain', $subdomain)
                ->first();
        }

        // [IMPERSONATION OVERRIDE]
        if (session()->has('impersonate_tenant_id')) {
            $tenant = Tenant::find(session('impersonate_tenant_id'));
        }

        // 2. Local/Session fallbacks (ONLY for authenticated routes or specific contexts)
        if (!$tenant && !in_array($request->route()->getName(), ['login', 'register'])) {
            if (session()->has('tenant_id')) {
                $tenant = Tenant::find(session('tenant_id'));
            } elseif (auth()->check() && auth()->user()->tenant_id) {
                $tenant = Tenant::find(auth()->user()->tenant_id);
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
                    'mail.default' => 'smtp',
                    'mail.mailers.smtp.host' => trim($settings['mail_host']),
                    'mail.mailers.smtp.port' => trim($settings['mail_port'] ?? '587'),
                    'mail.mailers.smtp.username' => trim($settings['mail_username']),
                    'mail.mailers.smtp.password' => trim($settings['mail_password']),
                    'mail.mailers.smtp.encryption' => trim($settings['mail_encryption'] ?? 'tls'),
                    'mail.from.address' => trim($settings['mail_from_address'] ?? 'no-reply@logisaas.com'),
                    'mail.from.name' => trim($settings['mail_from_name'] ?? $tenant->name),
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

            // 6. Suspension Check (Hard Lock)
            $isSuperAdmin = auth()->check() && auth()->user()->role === 'superadmin';
            if ($tenant->status === 'suspended' && !$isSuperAdmin) {
                return response()->view('errors.suspended', [], 403);
            }
        } else {
            // Safety fallback if no tenant identified
            session()->forget('tenant_id');
            config(['app.name' => 'LogiSaaS']);
        }

        // Update Last Seen for authenticated users
        if (auth()->check()) {
            // We use DB::table to avoid triggering observers or updated_at timestamps
            \Illuminate\Support\Facades\DB::table('users')
                ->where('id', auth()->id())
                ->update(['last_seen_at' => now()]);
        }

        return $next($request);
    }
}
