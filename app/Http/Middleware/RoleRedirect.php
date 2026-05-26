<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // SuperAdmin bypass
        if ($user->role === 'superadmin') {
            return $next($request);
        }

        if ($user->role !== $role) {
            if ($user->role === 'customer') {
                return redirect()->route('customer.dashboard');
            }
            return redirect()->route('dashboard'); // Admin/Staff dashboard
        }

        return $next($request);
    }
}
