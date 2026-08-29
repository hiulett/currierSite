<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\PaymentProof;
use App\Models\Permission;
use App\Models\Tenant;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('RAILWAY_ENVIRONMENT')) {
            config(['app.env' => 'production']);
            config(['session.secure' => true]); // Crítico para evitar error 419 en Railway
            config(['session.same_site' => 'lax']);
            URL::forceScheme('https');
        } elseif (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Evita el warning "preloaded but not used" de Vite + Livewire navigate:
        // se omite el <link rel="preload"> de CSS (el <link rel="stylesheet"> ya lo carga),
        // conservando el modulepreload de los chunks JS.
        app(Vite::class)->usePreloadTagAttributes(function ($src, $url, $chunk, $manifest) {
            return str_ends_with($url, '.css') ? false : [];
        });

        // ---------------------------------------------------------------
        // View Composer: Inyecta variables de tema del tenant en vistas auth
        // ---------------------------------------------------------------
        View::composer(['auth.login', 'auth.register', 'auth.forgot-password', 'auth.reset-password'], function ($view) {
            $tenantId = session('tenant_id');
            $tenant = null;

            if (auth()->check() && auth()->user()->tenant_id) {
                $tenant = Tenant::find(auth()->user()->tenant_id);
            } elseif ($tenantId) {
                $tenant = Tenant::find($tenantId);
            }

            $theme = $tenant?->theme_config_json ?? [];

            $view->with([
                'tenant' => $tenant,
                'tenantName' => $tenant?->name ?? config('app.name'),
                'logoUrl' => $tenant?->getLogoUrl(),
                'primaryColor' => $theme['primary_color'] ?? '#3B82F6',
                'primaryDark' => $theme['primary_dark_color'] ?? '#1D4ED8',
                'bgColor' => $theme['login_bg_color'] ?? '#F8FAFC',
                'bgImageUrl' => $theme['login_bg_image_url'] ?? null,
                'welcomeSubtitle' => $theme['login_welcome_subtitle'] ?? 'Acceso al Portal',
                'showRegisterLink' => $theme['show_register_link'] ?? true,
                'customCss' => $theme['custom_css'] ?? '',
            ]);
        });

        // Implicitly grant "Super Admin" role all permissions
        // This works even if permissions table is empty
        Gate::before(function (User $user, $ability) {
            return $user->role === 'superadmin' ? true : null;
        });

        Gate::define('access-superadmin', function (User $user) {
            return $user->role === 'superadmin';
        });

        Gate::define('access-admin', function (User $user) {
            return in_array($user->role, ['superadmin', 'admin', 'operator']);
        });

        Gate::define('access-customer', function (User $user) {
            return $user->role === 'customer';
        });

        // Register dynamic permissions from database
        try {
            if (Schema::hasTable('permissions')) {
                $permissions = Permission::all();

                // If table exists but is empty, define core gates for Admin fallback
                if ($permissions->isEmpty()) {
                    $corePermissions = [
                        'logistics.receive', 'logistics.inventory', 'logistics.repack',
                        'logistics.shipments', 'logistics.delivery', 'logistics.counter',
                        'logistics.reports', 'customers.view', 'tickets.manage',
                        'billing.view', 'billing.manage', 'settings.general',
                        'settings.brand', 'settings.users',
                    ];
                    foreach ($corePermissions as $perm) {
                        Gate::define($perm, function (User $user) {
                            return in_array($user->role, ['admin', 'superadmin']);
                        });
                    }
                } else {
                    $permissions->each(function ($permission) {
                        Gate::define($permission->name, function (User $user) use ($permission) {
                            return $user->hasPermission($permission->name);
                        });
                    });
                }
            }
        } catch (\Exception $e) {
            Log::error('Exception in '.__CLASS__.'::'.__FUNCTION__.' - '.$e->getMessage()."\n".$e->getTraceAsString());
            // Silence errors during migration/seeding
        }

        // Shared data for sidebar and navbar notifications
        View::composer('components.layouts.app', function ($view) {
            if (auth()->check()) {
                try {
                    $alerts = [];

                    // 1. Overdue Invoices
                    $overdue = Invoice::where('status', 'unpaid')->where('due_date', '<', now()->today())->count();
                    if ($overdue > 0) {
                        $alerts['overdue'] = $overdue;
                    }

                    // 2. Open Tickets
                    $tickets = Ticket::where('status', 'open')->count();
                    if ($tickets > 0) {
                        $alerts['tickets'] = $tickets;
                    }

                    // 3. New Pre-alerts
                    $prealerts = Package::where('status', 'prealert')->count();
                    if ($prealerts > 0) {
                        $alerts['prealerts'] = $prealerts;
                    }

                    // 4. New Customers (Last 48 hours)
                    $newCustomers = Customer::where('created_at', '>=', now()->subHours(48))->count();
                    if ($newCustomers > 0) {
                        $alerts['new_customers'] = $newCustomers;
                    }

                    // 5. Pending Payments
                    if (Schema::hasTable('payment_proofs')) {
                        $pendingPayments = PaymentProof::where('status', 'pending')->count();
                        if ($pendingPayments > 0) {
                            $alerts['pending_payments'] = $pendingPayments;
                        }
                    }

                    // 6. DB Notifications (Financial Alerts, etc)
                    $dbNotificationsCount = auth()->user()->unreadNotifications->count();

                    // 7. Tenant Billing Alert
                    $billingAlert = false;
                    if (session()->has('tenant_id')) {
                        $tenant = Tenant::find(session('tenant_id'));
                        if ($tenant) {
                            $isOverdue = $tenant->next_billing_at && $tenant->next_billing_at->isPast();
                            $billingAlert = $tenant->payment_warning_active || $isOverdue;
                        }
                    }

                    $view->with('navAlerts', $alerts);
                    $view->with('totalNavAlerts', array_sum($alerts) + $dbNotificationsCount);
                    $view->with('showBillingAlert', $billingAlert);
                } catch (\Exception $e) {
                    Log::error('Exception in '.__CLASS__.'::'.__FUNCTION__.' - '.$e->getMessage()."\n".$e->getTraceAsString());
                    $view->with('navAlerts', []);
                    $view->with('totalNavAlerts', 0);
                }
            }
        });

        // Register custom tenant feature Blade directives
        Blade::if('tenantFeatureNotHidden', function (string $feature) {
            $tenant = Tenant::current();

            return $tenant ? $tenant->getFeatureStatus($feature) !== 'hidden' : true;
        });

        Blade::if('tenantFeatureDisabled', function (string $feature) {
            $tenant = Tenant::current();

            return $tenant ? $tenant->getFeatureStatus($feature) === 'disabled' : false;
        });

        Blade::if('tenantSubFeature', function (string $subFeature) {
            $tenant = Tenant::current();

            return $tenant ? $tenant->hasSubFeature($subFeature) : true;
        });
    }
}
