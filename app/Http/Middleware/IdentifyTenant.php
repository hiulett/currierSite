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
        }

        return $next($request);
    }
}
