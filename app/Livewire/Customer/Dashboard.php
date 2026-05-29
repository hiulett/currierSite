<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Package;
use App\Models\Warehouse;
use App\Models\Customer;
use App\Models\Invoice;

class Dashboard extends Component
{
    public $customer;

    public function mount()
    {
        $user = auth()->user();
        $this->customer = $user->customer;

        if (!$this->customer) {
            // Re-attempting to find customer profile just in case relationship didn't load
            $this->customer = \App\Models\Customer::where('user_id', $user->id)->first();
        }
    }

    public function render()
    {
        if (!$this->customer) {
            return view('livewire.customer.dashboard-error', [
                'title' => 'Perfil no encontrado',
                'message' => 'Tu usuario no tiene un perfil de cliente asociado.'
            ])->layout('components.customer-layout');
        }

        $currentLevel = $this->customer->level;
        $nextLevel = \App\Models\LoyaltyLevel::where('tenant_id', $this->customer->tenant_id)
            ->where('min_points', '>', $this->customer->points)
            ->orderBy('min_points', 'asc')
            ->first();

        return view('livewire.customer.dashboard', [
            'warehouses' => Warehouse::where('is_active', true)->get(),
            'recent_packages' => Package::where('customer_id', $this->customer->id)->latest()->take(5)->get(),
            'unpaid_invoices_count' => Invoice::where('customer_id', $this->customer->id)->where('status', 'unpaid')->count(),
            'packages_in_transit_count' => Package::where('customer_id', $this->customer->id)->where('status', 'in_transit')->count(),
            'currentLevel' => $currentLevel,
            'nextLevel' => $nextLevel,
        ])->layout('components.customer-layout');
    }
}
