<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class CreateInvoice extends Component
{
    public $box_number;
    public $found_customer = null;
    public $items = [];
    public $notes;
    public $tax_percent = 7;
    public $selectedPackages = [];
    public $availablePackages = [];

    public function mount()
    {
        $this->addItem();

        $tenant = \App\Models\Tenant::find(session('tenant_id'));
        if ($tenant) {
            $this->tax_percent = $tenant->settings_json['default_tax'] ?? 7;
        }

        if (request()->has('customer')) {
            $customer = Customer::find(request('customer'));
            if ($customer) {
                $this->found_customer = $customer;
                $this->box_number = $customer->box_number;
            }
        }
    }

    public function updatedBoxNumber($value)
    {
        $this->found_customer = Customer::where('box_number', $value)->first();
        if ($this->found_customer) {
            $this->loadAvailablePackages();
        } else {
            $this->availablePackages = [];
        }
    }

    public function loadAvailablePackages()
    {
        $this->availablePackages = \App\Models\Package::where('customer_id', $this->found_customer->id)
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->get();
    }

    public function togglePackage($packageId)
    {
        $package = \App\Models\Package::find($packageId);
        if (!$package) return;

        if (in_array($packageId, $this->selectedPackages)) {
            // Remove from selected and items
            $this->selectedPackages = array_diff($this->selectedPackages, [$packageId]);
            $this->items = array_filter($this->items, fn($item) => ($item['package_id'] ?? null) !== $packageId);
            $this->items = array_values($this->items);
        } else {
            // Add to selected and items
            $this->selectedPackages[] = $packageId;

            $tenant = \App\Models\Tenant::find(session('tenant_id'));
            $rate = $tenant->settings_json['default_rate'] ?? 2.50;

            $this->items[] = [
                'package_id' => $package->id,
                'description' => 'Flete Aéreo - ' . $package->tracking_number,
                'quantity' => $package->weight,
                'unit_price' => $rate,
                'total' => $package->weight * $rate,
                'provider_cost' => $package->provider_cost ?? 0
            ];
        }
    }

    public function getEstimatedProfitProperty()
    {
        $subtotal = collect($this->items)->sum('total');
        $totalCost = collect($this->items)->sum(function($item) {
            return $item['provider_cost'] ?? 0;
        });

        return $subtotal - $totalCost;
    }

    public function addItem()
    {
        $this->items[] = [
            'description' => '',
            'quantity' => 1,
            'unit_price' => 0.00,
            'total' => 0.00
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updateItemTotal($index)
    {
        $this->items[$index]['total'] = $this->items[$index]['quantity'] * $this->items[$index]['unit_price'];
    }

    public function save()
    {
        $this->validate([
            'box_number' => 'required|exists:customers,box_number',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function() {
            $subtotal = collect($this->items)->sum('total');
            $tax = $subtotal * ($this->tax_percent / 100);
            $total = $subtotal + $tax;

            $invoice = Invoice::create([
                'customer_id' => $this->found_customer->id,
                'number' => 'INV-' . date('Ymd') . '-' . rand(100, 999),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'status' => 'unpaid',
                'due_date' => now()->addDays(7),
                'notes' => $this->notes,
            ]);

            foreach ($this->items as $item) {
                InvoiceItem::create([
                    'tenant_id' => session('tenant_id'),
                    'invoice_id' => $invoice->id,
                    'package_id' => $item['package_id'] ?? null,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['total'],
                ]);

                if (isset($item['package_id'])) {
                    \App\Models\Package::where('id', $item['package_id'])->update([
                        'client_total_billed' => $item['total']
                    ]);
                }
            }
        });

        $this->dispatch('invoice-saved');

        session()->flash('message', 'Factura generada exitosamente.');
        // return redirect()->route('billing.index'); // We don't want to redirect anymore
        $this->reset(['box_number', 'found_customer', 'items', 'notes']);
        $this->addItem();
    }

    public function render()
    {
        return view('livewire.billing.create-invoice')->layout('components.layouts.app');
    }
}
