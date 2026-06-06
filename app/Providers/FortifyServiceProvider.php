<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LogoutResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                return redirect('/login');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::loginView(function () {
            // Si no hay tenant en sesión y no es superadmin, mostrar pantalla de error
            if (!session()->has('tenant_id')) {
                // Intentar resolver tenant por subdominio (para setups con subdominio propio)
                $host = request()->getHost();
                $subdomain = explode('.', $host)[0];
                $tenant = null;
                if (!in_array($subdomain, ['curriersite-production', 'localhost', '127', 'www'])) {
                    $tenant = \App\Models\Tenant::where('domain', $host)
                        ->orWhere('subdomain', $subdomain)
                        ->first();
                    if ($tenant) {
                        session(['tenant_id' => $tenant->id]);
                    }
                }
            }
            return view('auth.login');
        });

        Fortify::registerView(function () {
            // Si no hay tenant en sesión, no se puede registrar
            if (!session()->has('tenant_id')) {
                $host = request()->getHost();
                $subdomain = explode('.', $host)[0];
                if (!in_array($subdomain, ['curriersite-production', 'localhost', '127', 'www'])) {
                    $tenant = \App\Models\Tenant::where('domain', $host)
                        ->orWhere('subdomain', $subdomain)
                        ->first();
                    if ($tenant) {
                        session(['tenant_id' => $tenant->id]);
                    }
                }
            }
            return view('auth.register');
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', ['request' => $request]);
        });

        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password');
        });

        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge');
        });

        Fortify::authenticateUsing(function (Request $request) {
            $email = trim($request->email);
            $sessionTenantId = session('tenant_id');

            // 1. Obtener todos los usuarios con ese email (sin importar el tenant)
            $users = \App\Models\User::withoutGlobalScope('tenant')
                ->where('email', $email)
                ->get();

            foreach ($users as $user) {
                // 2. Verificar contraseña
                if (\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {

                    // 3. Si entramos por un link de agencia específico, validar integridad
                    if ($sessionTenantId && $user->tenant_id && (int)$user->tenant_id !== (int)$sessionTenantId) {
                        continue; // Podría haber otro usuario con el mismo email en esta agencia
                    }

                    // 4. Login exitoso: Inyectar el Tenant en la sesión para activar los filtros
                    if ($user->tenant_id) {
                        session(['tenant_id' => $user->tenant_id]);
                    } else {
                        session()->forget('tenant_id'); // Es SuperAdmin
                    }

                    // Bandera de superadmin para optimizar Global Scopes
                    session(['is_superadmin' => ($user->role === 'superadmin')]);

                    return $user;
                }
            }

            return null;
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('passkeys', function (Request $request) {
            $credentialId = $request->input('credential.id');

            return Limit::perMinute(10)->by(
                ($credentialId ?: $request->session()->getId()).'|'.$request->ip()
            );
        });
    }
}
