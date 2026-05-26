<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Tenant;
use App\Models\Package;
use App\Models\Customer;
use App\Models\Invoice;

class Dashboard extends Component
{
    public function render()
    {
        $tenants_stats = [
            'active' => Tenant::where('status', 'active')->count(),
            'suspended' => Tenant::where('status', 'suspended')->count(),
            'total' => Tenant::count(),
        ];

        // Package growth (last 6 months)
        $monthFormat = \App\Helpers\DatabaseHelper::formatMonth('created_at', '%m');

        $package_growth = Package::withoutGlobalScope('tenant')
            ->selectRaw("$monthFormat as month, count(*) as count")
            ->where('created_at', '>=', now()->subMonths(6))
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

        return view('livewire.super-admin.dashboard', [
            'total_tenants' => $tenants_stats['total'],
            'active_tenants' => $tenants_stats['active'],
            'suspended_tenants' => $tenants_stats['suspended'],
            'total_packages' => Package::withoutGlobalScope('tenant')->count(),
            'total_customers' => Customer::withoutGlobalScope('tenant')->count(),
            'total_mrr' => Invoice::withoutGlobalScope('tenant')->where('status', 'paid')->sum('total'),
            'recent_tenants' => Tenant::latest()->take(5)->get(),
            'package_growth' => $package_growth,
            'top_tenants' => $top_tenants,
            'total_weight' => Package::withoutGlobalScope('tenant')->sum('weight'),
        ])->layout('components.super-admin-layout');
    }
}
