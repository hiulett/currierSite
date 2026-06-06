<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];
        $tenant = null;

        // 1. [IMPERSONATION] GOD MODE priority
        if (session()->has('impersonate_tenant_id')) {
            $tenant = Tenant::find(session('impersonate_tenant_id'));
        }

        // 2. [SUBDOMAIN] Extraction (Production Wildcard)
        if (!$tenant && !in_array($subdomain, ['localhost', '127', '127.0.0.1', 'www', 'curriersite-production'])) {
            $tenant = Tenant::where('subdomain', $subdomain)->orWhere('domain', $host)->first();
        }

        // 3. [AUTHENTICATED] User identity
        if (!$tenant && Auth::check() && Auth::user()->tenant_id) {
            $tenant = Tenant::find(Auth::user()->tenant_id);
        }

        // 4. [SESSION/COOKIE] Context persistence
        if (!$tenant) {
            $tenantId = session('tenant_id') ?? $request->cookie('tenant_branding_id');
            if ($tenantId) {
                $tenant = Tenant::find($tenantId);
            }
        }

        // 5. [LOCAL FALLBACK] For development convenience
        if (!$tenant && in_array($subdomain, ['localhost', '127', '127.0.0.1'])) {
            $tenant = Tenant::first();
        }

        // 6. APPLY CONTEXT
        if ($tenant) {
            // Flatten tenant_id into session to prevent recursion in Global Scopes
            if (session('tenant_id') !== $tenant->id) {
                session(['tenant_id' => $tenant->id]);
            }

            config(['app.name' => $tenant->name]);
            app()->setLocale($tenant->locale ?? config('app.locale'));

            // Set SuperAdmin flag for scope bypass
            if (Auth::check() && !session()->has('is_superadmin')) {
                session(['is_superadmin' => Auth::user()->role === 'superadmin']);
            }

            // Dynamic Config Overrides (Mail, Payments, etc)
            $this->applyTenantConfig($tenant);

            // Suspension Lock
            if ($tenant->status === 'suspended' && session('is_superadmin') !== true) {
                return response()->view('errors.suspended', [], 403);
            }
        }

        // Update Last Seen (Bypass Scopes/Events using direct DB query)
        if (Auth::check()) {
            DB::table('users')->where('id', Auth::id())->update(['last_seen_at' => now()]);
        }

        return $next($request);
    }

    /**
     * Applies dynamic configurations from tenant settings.
     */
    protected function applyTenantConfig(Tenant $tenant): void
    {
        $settings = $tenant->settings_json ?? [];

        // SMTP Override
        if (!empty($settings['mail_host'])) {
            $driver = $settings['mail_driver'] ?? 'smtp';

            if ($driver === 'sendgrid_api' || $driver === 'sendgrid') {
                config(["mail.mailers.$driver" => [
                    'transport' => $driver,
                    'key' => $settings['mail_password'] ?? $settings['mail_username']
                ]]);
            }

            config([
                'mail.default' => $driver,
                'mail.mailers.smtp.host' => trim($settings['mail_host']),
                'mail.mailers.smtp.port' => trim($settings['mail_port'] ?? '587'),
                'mail.mailers.smtp.username' => trim($settings['mail_username']),
                'mail.mailers.smtp.password' => trim($settings['mail_password']),
                'mail.mailers.smtp.encryption' => trim($settings['mail_encryption'] ?? 'tls'),
                'mail.from.address' => trim($settings['mail_from_address'] ?? 'no-reply@logisaas.com'),
                'mail.from.name' => trim($settings['mail_from_name'] ?? $tenant->name),
            ]);
        }
    }
}
