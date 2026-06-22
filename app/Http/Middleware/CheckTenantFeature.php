<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantFeature
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $tenant = Tenant::current();

        if ($tenant) {
            $status = $tenant->getFeatureStatus($module);

            if ($status === 'hidden') {
                abort(404, 'Módulo no encontrado.');
            }

            if ($status === 'disabled') {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Esta funcionalidad está bloqueada bajo la configuración de su plan.'], 403);
                }

                return redirect()->route('dashboard')->with('warning', 'Esta funcionalidad está bloqueada bajo la configuración de su plan.');
            }
        }

        return $next($request);
    }
}
