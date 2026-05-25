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
    public $tax_percent = 7; // Default tax (e.g. ITBMS Panama)

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
                    'invoice_id' => $invoice->id,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['total'],
                ]);
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
