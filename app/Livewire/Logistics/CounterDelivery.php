<?php

namespace App\Livewire\Logistics;

use Livewire\Component;
use App\Models\Package;
use App\Models\Customer;
use App\Models\Invoice;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class CounterDelivery extends Component
{
    use WithPagination;

    public $search = '';
    public $filter_status = '';
    public $selected_customer_id = null;
    public $selected_packages = [];
    public $receiver_name = '';
    public $pay_pending_invoices = false;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function setFilter($status)
    {
        $this->filter_status = $status;
        $this->resetPage();
    }

    public function selectCustomer($customerId)
    {
        $this->selected_customer_id = $customerId;
        $this->selected_packages = Package::where('customer_id', $customerId)
            ->whereIn('status', ['received', 'arrived', 'ready_for_pickup'])
            ->pluck('id')
            ->toArray();

        $customer = Customer::with('user')->find($customerId);
        $this->receiver_name = $customer->user->name;
        $this->pay_pending_invoices = false;
    }

    public function togglePackage($packageId)
    {
        if (in_array($packageId, $this->selected_packages)) {
            $this->selected_packages = array_diff($this->selected_packages, [$packageId]);
        } else {
            $this->selected_packages[] = $packageId;
        }
    }

    public function deliverPackages()
    {
        $this->validate([
            'selected_packages' => 'required|array|min:1',
            'receiver_name' => 'required|string',
        ]);

        DB::transaction(function() {
            // 1. Mark packages as delivered
            $packages = Package::whereIn('id', $this->selected_packages)->get();
            foreach ($packages as $pkg) {
                $pkg->update([
                    'status' => 'delivered',
                    'delivered_at' => now(),
                    'delivered_to' => $this->receiver_name,
                ]);
            }

            // 2. Process payment if selected
            if ($this->pay_pending_invoices) {
                $unpaidInvoices = Invoice::where('customer_id', $this->selected_customer_id)
                    ->where('status', 'unpaid')
                    ->get();

                foreach ($unpaidInvoices as $invoice) {
                    $invoice->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                    ]);
                    // Update balance
                    $invoice->customer->decrement('balance', $invoice->total);
                }
            }
        });

        $message = 'Entrega procesada con éxito.';
        if ($this->pay_pending_invoices) {
            $message = 'Entrega procesada y facturas marcadas como pagadas.';
        }

        session()->flash('message', $message);
        $this->reset(['selected_customer_id', 'selected_packages', 'receiver_name', 'search', 'pay_pending_invoices']);
    }

    public function render()
    {
        $search_results = [];
        if (strlen($this->search) >= 3) {
            $searchTerm = '%' . str_replace(' ', '%', trim($this->search)) . '%';

            // 1. Buscar directamente por cliente (nombre o casillero)
            $customer_results = Customer::with('user')
                ->where(function($q) use ($searchTerm) {
                    $q->where('box_number', 'like', $searchTerm)
                      ->orWhereHas('user', function($u) use ($searchTerm) {
                          $u->where('name', 'like', $searchTerm);
                      });
                })
                ->take(5)
                ->get();

            // 2. Buscar por número de tracking para encontrar al cliente
            $package_customer_ids = Package::where('tracking_number', 'like', $searchTerm)
                ->pluck('customer_id')
                ->unique();

            if ($package_customer_ids->isNotEmpty()) {
                $tracking_customers = Customer::with('user')
                    ->whereIn('id', $package_customer_ids)
                    ->get();

                $search_results = $customer_results->merge($tracking_customers)->unique('id')->take(5);
            } else {
                $search_results = $customer_results;
            }
        }

        $customer = null;
        $customer_packages = [];
        $unpaid_invoices_count = 0;

        if ($this->selected_customer_id) {
            $customer = Customer::with('user')->find($this->selected_customer_id);
            $customer_packages = Package::where('customer_id', $this->selected_customer_id)
                ->whereIn('status', ['received', 'arrived', 'ready_for_pickup'])
                ->get();

            $unpaid_invoices_count = Invoice::where('customer_id', $this->selected_customer_id)
                ->where('status', 'unpaid')
                ->count();
        }

        $stats = [
            'waiting_pickup' => Package::whereIn('status', ['received', 'arrived', 'ready_for_pickup'])->count(),
            'delivered_today' => Package::where('status', 'delivered')->whereDate('delivered_at', now()->today())->count(),
            'weight_delivered_today' => Package::where('status', 'delivered')->whereDate('delivered_at', now()->today())->sum('weight'),
            'customers_with_debt' => Customer::whereHas('invoices', function($q) {
                $q->where('status', 'unpaid');
            })->count(),
        ];

        // Apply filter to search results if searching for customers with debt
        if ($this->filter_status === 'debt' && empty($this->search)) {
            $search_results = Customer::with('user')->whereHas('invoices', function($q) {
                $q->where('status', 'unpaid');
            })->take(10)->get();
        }

        return view('livewire.logistics.counter-delivery', [
            'search_results' => $search_results,
            'customer' => $customer,
            'customer_packages' => $customer_packages,
            'unpaid_invoices_count' => $unpaid_invoices_count,
            'stats' => $stats
        ])->layout('components.layouts.app');
    }
}
