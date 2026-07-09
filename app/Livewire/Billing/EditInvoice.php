<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class EditInvoice extends Component
{
    public Invoice $invoice;
    public $box_number;
    public $found_customer = null;
    public $items = [];
    public $notes;
    public $tax_percent = 0;
    public $selectedPackages = [];
    public $availablePackages = [];
    public $customer_search = '';
    public $customer_results = [];

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->found_customer = $invoice->customer;
        $this->box_number = $this->found_customer ? $this->found_customer->box_number : '';
        $this->notes = $invoice->notes;
        
        // Calculate tax percent based on existing values or fallback to default settings
        if ($invoice->subtotal > 0) {
            $this->tax_percent = round(($invoice->tax / $invoice->subtotal) * 100, 2);
        } else {
            $tenant = \App\Models\Tenant::current();
            if ($tenant) {
                $this->tax_percent = $tenant->settings_json['default_tax'] ?? ($tenant->settings_json['tax_rate'] ?? 0);
            }
        }

        // Load items into array format
        $this->items = $invoice->items->map(fn($item) => [
            'id' => $item->id,
            'package_id' => $item->package_id,
            'description' => $item->description,
            'quantity' => $item->quantity,
            'unit_price' => $item->unit_price,
            'total' => $item->total,
            'provider_cost' => $item->package?->provider_cost ?? 0
        ])->toArray();

        if (empty($this->items)) {
            $this->addItem();
        }

        // Set selected packages
        $this->selectedPackages = collect($this->items)
            ->pluck('package_id')
            ->filter()
            ->toArray();

        $this->loadAvailablePackages();
    }

    public function updatedBoxNumber($value)
    {
        $this->found_customer = Customer::where('box_number', $value)->first();
        if ($this->found_customer) {
            $this->items = [];
            $this->addItem();
            $this->selectedPackages = [];
            $this->loadAvailablePackages();
        } else {
            $this->availablePackages = [];
        }
    }

    public function updatedCustomerSearch($value)
    {
        if (strlen($value) < 2) {
            $this->customer_results = [];
            return;
        }

        $this->customer_results = Customer::with('user')
            ->where('box_number', 'like', '%' . $value . '%')
            ->orWhereHas('user', function($q) use ($value) {
                $q->where('name', 'like', '%' . $value . '%');
            })->take(5)->get();
    }

    public function selectCustomer($id)
    {
        $this->found_customer = Customer::with('user')->find($id);
        if ($this->found_customer) {
            $this->box_number = $this->found_customer->box_number;
            $this->customer_search = '';
            $this->customer_results = [];
            $this->items = [];
            $this->addItem();
            $this->selectedPackages = [];
            $this->loadAvailablePackages();
        }
    }

    public function loadAvailablePackages()
    {
        if (!$this->found_customer) {
            $this->availablePackages = [];
            return;
        }

        $currentPackageIds = collect($this->items)
            ->pluck('package_id')
            ->filter()
            ->toArray();

        // Get customer packages that are either not delivered/cancelled OR already linked to this invoice
        $this->availablePackages = \App\Models\Package::where('customer_id', $this->found_customer->id)
            ->where(function($query) use ($currentPackageIds) {
                $query->whereNotIn('status', ['delivered', 'cancelled'])
                      ->orWhereIn('id', $currentPackageIds);
            })
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
            $settings = $tenant->settings_json;
            $rate = $package->service_type === 'maritime' 
                ? ($settings['maritime_rate'] ?? 1.50) 
                : ($settings['air_rate'] ?? 2.50);

            $serviceLabel = $package->service_type === 'maritime' ? 'Marítimo' : 'Aéreo';

            $this->items[] = [
                'package_id' => $package->id,
                'description' => 'Flete ' . $serviceLabel . ' - ' . $package->tracking_number,
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
            // Keep old values to adjust customer balance
            $oldCustomerId = $this->invoice->customer_id;
            $oldTotal = $this->invoice->total;
            $oldStatus = $this->invoice->status;
            $oldCustomer = $this->invoice->customer;

            $subtotal = collect($this->items)->sum('total');
            $tax = $subtotal * ($this->tax_percent / 100);
            $total = $subtotal + $tax;

            // Determine invoice service_type from items
            $types = [];
            foreach ($this->items as $item) {
                if (isset($item['package_id'])) {
                    $pkg = \App\Models\Package::find($item['package_id']);
                    if ($pkg && $pkg->service_type) {
                        $types[] = $pkg->service_type;
                    }
                }
            }
            $types = array_unique($types);
            $serviceType = count($types) === 1 ? $types[0] : (count($types) > 1 ? 'mixed' : 'air');

            // Reset client_total_billed for old packages attached to this invoice
            $oldPackageIds = $this->invoice->items()->whereNotNull('package_id')->pluck('package_id')->toArray();
            \App\Models\Package::whereIn('id', $oldPackageIds)->update(['client_total_billed' => 0]);

            // Clear previous items
            $this->invoice->items()->delete();

            // Update invoice fields
            $this->invoice->update([
                'customer_id' => $this->found_customer->id,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'service_type' => $serviceType,
                'notes' => $this->notes,
            ]);

            // Create new items and set package billed totals
            foreach ($this->items as $item) {
                InvoiceItem::create([
                    'tenant_id' => session('tenant_id'),
                    'invoice_id' => $this->invoice->id,
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

            // Adjust Customer Balance if invoice is unpaid
            if ($oldStatus === 'unpaid') {
                if ($oldCustomerId != $this->found_customer->id) {
                    if ($oldCustomer) {
                        $oldCustomer->decrement('balance', $oldTotal);
                    }
                    if ($this->found_customer) {
                        $this->found_customer->increment('balance', $total);
                    }
                } else {
                    if ($this->found_customer) {
                        $this->found_customer->increment('balance', $total - $oldTotal);
                    }
                }
            }
        });

        session()->flash('message', 'Factura ' . $this->invoice->number . ' actualizada exitosamente.');
        return redirect()->route('billing.index');
    }

    public function render()
    {
        return view('livewire.billing.edit-invoice')->layout('components.layouts.app');
    }
}
