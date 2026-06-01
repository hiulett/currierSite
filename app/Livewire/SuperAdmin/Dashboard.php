<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Tenant;
use App\Models\Package;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Plan;
use App\Models\SubscriptionInvoice;

class Dashboard extends Component
{
    public function render()
    {
        $tenants_stats = [
            'active' => Tenant::where('status', 'active')->count(),
            'suspended' => Tenant::where('status', 'suspended')->count(),
            'total' => Tenant::count(),
        ];

        // 1. Carga Global Metrics
        $total_packages = Package::withoutGlobalScope('tenant')->count();
        $total_weight = Package::withoutGlobalScope('tenant')->sum('weight');

        $packages_by_status = Package::withoutGlobalScope('tenant')
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // 2. Analítica de Usuarios
        $total_users = User::withoutGlobalScope('tenant')->count();
        $total_customers = Customer::withoutGlobalScope('tenant')->count();

        // Active in last 7 days (Using real last_seen_at field)
        $active_users_7d = User::withoutGlobalScope('tenant')
            ->where('last_seen_at', '>=', now()->subDays(7))
            ->count();

        // Online Users (Activity in last 15 mins from sessions table)
        $online_users = \Illuminate\Support\Facades\DB::table('sessions')
            ->where('last_activity', '>=', now()->subMinutes(15)->getTimestamp())
            ->whereNotNull('user_id')
            ->count();

        // 3. Finanzas Consolidadas
        $total_revenue = Invoice::withoutGlobalScope('tenant')->where('status', 'paid')->sum('total');
        $pending_collection = Invoice::withoutGlobalScope('tenant')->where('status', 'unpaid')->sum('total');

        // 3b. SaaS Health (Your own revenue from tenants)
        $saas_revenue_month = SubscriptionInvoice::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->sum('amount');

        $saas_pending = SubscriptionInvoice::where('status', 'unpaid')->sum('amount');
        $saas_overdue_count = SubscriptionInvoice::where('status', 'unpaid')
            ->where('due_date', '<', now()->today())
            ->count();

        // Package growth (last 12 months)
        $monthFormat = \App\Helpers\DatabaseHelper::formatMonth('created_at', '%m');
        $package_growth = Package::withoutGlobalScope('tenant')
            ->selectRaw("$monthFormat as month, count(*) as count")
            ->where('created_at', '>=', now()->startOfYear())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Top 5 Tenants by package volume
        $top_tenants = Tenant::withCount(['packages' => function($query) {
                $query->withoutGlobalScope('tenant');
            }])
            ->orderBy('packages_count', 'desc')
            ->take(5)
            ->get();

        // 4. Breakdown of Contracted Plans
        $plans_usage = Plan::withCount('tenants')
            ->where('is_active', true)
            ->get()
            ->pluck('tenants_count', 'name')
            ->toArray();

        return view('livewire.super-admin.dashboard', [
            'total_tenants' => $tenants_stats['total'],
            'active_tenants' => $tenants_stats['active'],
            'suspended_tenants' => $tenants_stats['suspended'],
            'total_packages' => $total_packages,
            'total_customers' => $total_customers,
            'total_users' => $total_users,
            'active_users_7d' => $active_users_7d,
            'online_users' => $online_users,
            'total_revenue' => $total_revenue,
            'pending_collection' => $pending_collection,
            'saas_revenue_month' => $saas_revenue_month,
            'saas_pending' => $saas_pending,
            'saas_overdue_count' => $saas_overdue_count,
            'recent_tenants' => Tenant::with('plan')->latest()->take(5)->get(),
            'package_growth' => $package_growth,
            'top_tenants' => $top_tenants,
            'total_weight' => $total_weight,
            'packages_by_status' => $packages_by_status,
            'plans_usage' => $plans_usage,
        ])->layout('components.super-admin-layout');
    }
}
