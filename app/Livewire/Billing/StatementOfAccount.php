<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use Livewire\WithPagination;

class StatementOfAccount extends Component
{
    use WithPagination;

    public $search = '';
    public $selected_customer_id = null;
    public $selected_package_id = null;
    public $filter_status = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'selected_customer_id' => ['as' => 'c'],
        'selected_package_id' => ['as' => 'p'],
        'filter_status' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage('customersPage');
    }

    public function setFilter($status)
    {
        $this->filter_status = $status;
        $this->resetPage('customersPage');
    }

    public function selectCustomer($id)
    {
        $this->selected_customer_id = $id;
        $this->selected_package_id = null;
        $this->resetPage('invoicesPage');
    }

    public function clearSelection()
    {
        $this->selected_customer_id = null;
        $this->selected_package_id = null;
    }

    public function viewPackage($id)
    {
        $this->selected_package_id = $id;
    }

    public function closePackage()
    {
        $this->selected_package_id = null;
    }

    public function render()
    {
        $customer = null;
        $invoices = collect();
        $packages = collect();
        $package_detail = null;

        if ($this->selected_customer_id) {
            $customer = Customer::with('user')->findOrFail($this->selected_customer_id);

            $invoices = Invoice::where('customer_id', $this->selected_customer_id)
                ->latest()
                ->paginate(8, ['*'], 'invoicesPage');

            $packages = Package::where('customer_id', $this->selected_customer_id)
                ->whereNotIn('status', ['delivered', 'cancelled'])
                ->latest()
                ->get();

            if ($this->selected_package_id) {
                $package_detail = Package::with(['trackingEvents' => function($q) {
                    $q->orderBy('created_at', 'desc');
                }, 'warehouse'])->find($this->selected_package_id);
            }
        }

        $query = Customer::with('user')
            ->where(function($q) {
                $q->where('box_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($u) {
                      $u->where('name', 'like', '%' . $this->search . '%');
                  });
            });

        if ($this->filter_status === 'debt') {
            $query->where('balance', '>', 0);
        }

        $customers = $query->latest()
            ->paginate(12, ['*'], 'customersPage');

        $stats = [
            'total_debt' => Customer::sum('balance'),
            'total_customers' => Customer::count(),
            'total_points' => Customer::sum('points'),
            'active_packages' => Package::whereNotIn('status', ['delivered', 'cancelled'])->count(),
        ];

        $tenant = \App\Models\Tenant::find(session('tenant_id'));
        $currency = $tenant->settings_json['currency'] ?? 'USD';

        return view('livewire.billing.statement-of-account', [
            'customers' => $customers,
            'customer' => $customer,
            'invoices' => $invoices,
            'packages' => $packages,
            'package_detail' => $package_detail,
            'stats' => $stats,
            'currency' => $currency,
        ])->layout('components.layouts.app');
    }
}
