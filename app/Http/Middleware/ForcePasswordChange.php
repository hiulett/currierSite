<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->must_change_password) {
            // Allow access to the profile route itself, logout and any Livewire updates
            if (!$request->routeIs('customer.profile') &&
                !$request->routeIs('logout') &&
                !$request->routeIs('livewire.*')) {

                return redirect()->route('customer.profile')
                    ->with('warning', 'Por seguridad, debes cambiar tu contraseña temporal antes de continuar.');
            }
        }

        return $next($request);
    }
}
