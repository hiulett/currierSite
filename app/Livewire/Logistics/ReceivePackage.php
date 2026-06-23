<?php

namespace App\Livewire\Logistics;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Warehouse;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Notifications\PackageReceived;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReceivePackage extends Component
{
    public $tracking_number;
    public $box_number;
    public $weight = 0;
    public $length = 0;
    public $width = 0;
    public $height = 0;
    public $volumetric_weight = 0;
    public $description;
    public $warehouse_id;
    public $last_package_id = null;
    public $auto_invoice = true;

    public $found_customer = null;
    public $customer_search = '';
    public $search_results = [];
    public $recent_customer_ids = [];

    public function mount()
    {
        $this->recent_customer_ids = session('recent_receivers', []);
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['length', 'width', 'height'])) {
            $this->calculateVolumetric();
        }
    }

    public function calculateVolumetric()
    {
        // Standard formula: (L * W * H) / 166 for lbs
        // Can be changed to / 5000 for kg/cm if needed
        $this->volumetric_weight = ($this->length * $this->width * $this->height) / 166;
        $this->volumetric_weight = round($this->volumetric_weight, 2);
    }

    public function updatedCustomerSearch($value)
    {
        if (strlen($value) < 2) {
            $this->search_results = [];
            return;
        }

        $this->search_results = Customer::with('user')
            ->where('box_number', 'like', '%' . $value . '%')
            ->orWhereHas('user', function($q) use ($value) {
                $q->where('name', 'like', '%' . $value . '%')
                  ->orWhere('email', 'like', '%' . $value . '%');
            })
            ->take(5)
            ->get();
    }

    public function selectCustomer($customerId)
    {
        $this->found_customer = Customer::with('user')->find($customerId);
        $this->box_number = $this->found_customer->box_number;
        $this->customer_search = $this->found_customer->user->name . ' (' . $this->found_customer->box_number . ')';
        $this->search_results = [];
    }

    public function save()
    {
        $this->validate([
            'tracking_number' => 'required|string',
            'box_number' => 'required|exists:customers,box_number',
            'weight' => 'required|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $package = null;

        DB::transaction(function() use (&$package) {
            $package = Package::create([
                'tenant_id' => session('tenant_id'),
                'customer_id' => $this->found_customer->id,
                'warehouse_id' => $this->warehouse_id,
                'tracking_number' => $this->tracking_number,
                'weight' => $this->weight,
                'length' => $this->length,
                'width' => $this->width,
                'height' => $this->height,
                'volumetric_weight' => $this->volumetric_weight,
                'description' => $this->description,
                'status' => 'received',
            ]);

            // Auto-invoice logic
            if ($this->auto_invoice && $this->weight > 0) {
                $tenant = \App\Models\Tenant::find(session('tenant_id'));
                $settings = $tenant->settings_json ?? [];

                $rate = $settings['default_rate'] ?? 2.50;
                $tax_percent = $settings['default_tax'] ?? 7;

                $subtotal = $this->weight * $rate;
                $tax = $subtotal * ($tax_percent / 100);
                $total = $subtotal + $tax;

                $invoice = Invoice::create([
                    'tenant_id' => session('tenant_id'),
                    'customer_id' => $this->found_customer->id,
                    'number' => 'INV-' . date('Ym') . strtoupper(Str::random(4)),
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total' => $total,
                    'status' => 'unpaid',
                    'due_date' => now()->addDays(7),
                    'notes' => 'Factura automática por recepción de paquete ' . $this->tracking_number,
                ]);

                InvoiceItem::create([
                    'tenant_id' => session('tenant_id'),
                    'invoice_id' => $invoice->id,
                    'description' => 'Flete Aéreo (Libras) - ' . $this->tracking_number,
                    'quantity' => $this->weight,
                    'unit_price' => $rate,
                    'total' => $subtotal,
                ]);

                $this->found_customer->increment('balance', $total);
            }
        });

        $this->last_package_id = $package->id;

        // Points Logic
        $customer = $this->found_customer;
        $pointsEarned = ceil($this->weight); // 1 lb = 1 point

        $customer->increment('points', $pointsEarned);

        // Referrer Points (10% of the points)
        if ($customer->referrer_id) {
            $referrer = \App\Models\Customer::find($customer->referrer_id);
            if ($referrer) {
                $referrer->increment('points', ceil($pointsEarned * 0.1));
            }
        }

        if ($this->found_customer->user) {
            try {
                $this->found_customer->user->notify(new PackageReceived($package));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error enviando notificación de paquete recibido: ' . $e->getMessage());
            }
        }

        // Add to recent receivers
        $recent = session('recent_receivers', []);
        array_unshift($recent, $customer->id);
        $recent = array_unique($recent);
        $recent = array_slice($recent, 0, 4); // Keep last 4
        session(['recent_receivers' => $recent]);
        $this->recent_customer_ids = $recent;

        $this->dispatch('package-saved');

        session()->flash('message', 'Paquete registrado exitosamente: ' . $package->tracking_number);

        $this->reset(['tracking_number', 'customer_search', 'weight', 'description', 'found_customer', 'box_number']);
    }

    public function render()
    {
        $recentCustomers = Customer::with('user')
            ->whereIn('id', $this->recent_customer_ids)
            ->get()
            ->sortBy(function($model) {
                return array_search($model->id, $this->recent_customer_ids);
            });

        $stats = [
            'received_today' => Package::whereDate('created_at', now()->today())->count(),
            'weight_today' => Package::whereDate('created_at', now()->today())->sum('weight'),
            'last_packages' => Package::with('customer.user')->latest()->take(5)->get(),
        ];

        return view('livewire.logistics.receive-package', [
            'warehouses' => Warehouse::all(),
            'recentCustomers' => $recentCustomers,
            'stats' => $stats
        ])->layout('components.layouts.app');
    }
}
