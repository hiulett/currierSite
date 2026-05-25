<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Package;
use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\Customer;

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
            if (\Illuminate\Support\Facades\Schema::hasTable('permissions')) {
                \App\Models\Permission::all()->each(function ($permission) {
                    Gate::define($permission->name, function (User $user) use ($permission) {
                        return $user->hasPermission($permission->name);
                    });
                });
            }
        } catch (\Exception $e) {
            // Silence errors during migration/seeding
        }

        // Shared data for sidebar and navbar notifications
        View::composer('components.layouts.app', function ($view) {
            if (auth()->check()) {
                $alerts = [];

                // 1. Overdue Invoices
                $overdue = Invoice::where('status', 'unpaid')->where('due_date', '<', now()->today())->count();
                if ($overdue > 0) $alerts['overdue'] = $overdue;

                // 2. Open Tickets
                $tickets = Ticket::where('status', 'open')->count();
                if ($tickets > 0) $alerts['tickets'] = $tickets;

                // 3. New Pre-alerts
                $prealerts = Package::where('status', 'prealert')->count();
                if ($prealerts > 0) $alerts['prealerts'] = $prealerts;

                // 4. New Customers (Last 48 hours)
                $newCustomers = Customer::where('created_at', '>=', now()->subHours(48))->count();
                if ($newCustomers > 0) $alerts['new_customers'] = $newCustomers;

                $view->with('navAlerts', $alerts);
                $view->with('totalNavAlerts', array_sum($alerts));
            }
        });
    }
}
