<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequiresTenantContext
{
    /**
     * Bloquea el acceso si no hay un tenant identificado en la sesión.
     * Los SuperAdmins están exentos de esta restricción.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // SuperAdmins pueden acceder sin tenant_id
        if (auth()->check() && auth()->user()->role === 'superadmin') {
            return $next($request);
        }

        // Si hay tenant_id en sesión, continuar normalmente
        if (session()->has('tenant_id')) {
            return $next($request);
        }

        // Intentar resolver tenant por subdominio o dominio personalizado
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];
        $excludedHosts = ['curriersite-production', 'localhost', '127', 'www'];

        if (!in_array($subdomain, $excludedHosts)) {
            $tenant = \App\Models\Tenant::where('domain', $host)
                ->orWhere('subdomain', $subdomain)
                ->first();

            if ($tenant) {
                session(['tenant_id' => $tenant->id]);
                return $next($request);
            }
        }

        // Sin tenant identificado: mostrar página de error informativa
        return response()->view('errors.no-tenant', [], 400);
    }
}
