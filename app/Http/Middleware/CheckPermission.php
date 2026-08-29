<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (! $request->user() || ! $request->user()->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No tienes permisos suficientes.'], 403);
            }
            abort(403, 'No tienes permisos suficientes para acceder a esta sección.');
        }

        return $next($request);
    }
}
