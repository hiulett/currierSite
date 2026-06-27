<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CreateQuotation extends Component
{
    public $is_registered = true;
    public $customer_id;
    public $search_customer = '';
    public $customers = [];
    public $client_name = '';
    public $client_lastname = '';
    public $client_email = '';
    public $notes = '';
    public $service_type = 'air';

    public $items = [];
    public $quotation_id = null;

    protected $listeners = ['openCreateQuotationModal' => 'initForm'];

    public function mount()
    {
        $this->initForm();
    }

    public function initForm($quotationId = null)
    {
        $this->reset(['quotation_id', 'customer_id', 'search_customer', 'notes', 'customers', 'client_name', 'client_lastname', 'client_email', 'service_type']);
        $this->is_registered = true;
        
        if (is_array($quotationId) && isset($quotationId['quotationId'])) {
            $quotationId = $quotationId['quotationId'];
        }
        
        if ($quotationId) {
            $this->loadQuotation($quotationId);
        } else {
            $this->service_type = 'air';
            $this->items = [
                ['item_number' => '', 'description' => '', 'quantity' => 1, 'price' => $this->getCurrentRate(), 'handling_price' => 0, 'total' => 0]
            ];
            $this->searchCustomers();
        }
    }

    private function loadQuotation($id)
    {
        $quotation = Quotation::with('items', 'customer.user')->find($id);
        if (!$quotation) return;

        $this->quotation_id = $quotation->id;
        $this->service_type = $quotation->service_type ?? 'air';
        $this->notes = $quotation->notes;
        
        if ($quotation->customer_id) {
            $this->is_registered = true;
            $this->customer_id = $quotation->customer_id;
            $this->search_customer = $quotation->customer->user->name ?? '';
        } else {
            $this->is_registered = false;
            $parts = explode(' ', $quotation->client_name, 2);
            $this->client_name = $parts[0] ?? '';
            $this->client_lastname = $parts[1] ?? '';
            $this->client_email = $quotation->client_email;
        }

        $this->items = [];
        foreach ($quotation->items as $item) {
            $this->items[] = [
                'item_number' => $item->item_number,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'handling_price' => $item->handling_price,
                'total' => $item->total,
            ];
        }
    }

    public function updatedSearchCustomer()
    {
        $this->searchCustomers();
    }

    public function updatedIsRegistered($value)
    {
        $this->is_registered = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function searchCustomers()
    {
        $query = Customer::with('user');
        if (!empty($this->search_customer)) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search_customer . '%')
                  ->orWhere('email', 'like', '%' . $this->search_customer . '%');
            })->orWhere('box_number', 'like', '%' . $this->search_customer . '%');
        }
        $this->customers = $query->take(10)->get();
    }

    public function selectCustomer($id, $name)
    {
        $this->customer_id = $id;
        $this->search_customer = $name;
        $this->customers = [];
    }

    public function addItem()
    {
        $this->items[] = ['item_number' => '', 'description' => '', 'quantity' => 1, 'price' => $this->getCurrentRate(), 'handling_price' => 0, 'total' => 0];
        $this->calculateTotals();
    }

    public function updatedServiceType()
    {
        $rate = $this->getCurrentRate();
        foreach ($this->items as $key => $item) {
            $this->items[$key]['price'] = $rate;
        }
        $this->calculateTotals();
    }

    private function getCurrentRate()
    {
        $tenant = \App\Models\Tenant::find(session('tenant_id')) ?? \App\Models\Tenant::first();
        $settings = $tenant->settings_json ?? [];
        return $this->service_type === 'maritime' ? ($settings['maritime_rate'] ?? 1.50) : ($settings['air_rate'] ?? 2.50);
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        foreach ($this->items as $key => $item) {
            $qty = floatval($item['quantity'] ?? 0);
            $price = floatval($item['price'] ?? 0);
            $handling = floatval($item['handling_price'] ?? 0);
            $this->items[$key]['total'] = ($qty * $price) + ($qty * $handling);
        }
    }

    public function updatedItems()
    {
        $this->calculateTotals();
    }

    public function getSubtotalProperty()
    {
        return collect($this->items)->sum(function ($item) {
            return floatval($item['quantity'] ?? 0) * floatval($item['price'] ?? 0);
        });
    }

    public function getHandlingTotalProperty()
    {
        return collect($this->items)->sum(function ($item) {
            return floatval($item['quantity'] ?? 0) * floatval($item['handling_price'] ?? 0);
        });
    }

    public function getTotalProperty()
    {
        return $this->getSubtotalProperty() + $this->getHandlingTotalProperty();
    }

    public function save()
    {
        $rules = [
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.handling_price' => 'required|numeric|min:0',
        ];

        if ($this->is_registered) {
            $rules['customer_id'] = 'required|exists:customers,id';
        } else {
            $rules['client_name'] = 'required|string|max:255';
            $rules['client_email'] = 'required|email|max:255';
        }

        $this->validate($rules);

        DB::transaction(function () {
            $tenantId = session('tenant_id');
            $fullName = $this->is_registered ? null : trim($this->client_name . ' ' . $this->client_lastname);

            if ($this->quotation_id) {
                $quotation = Quotation::findOrFail($this->quotation_id);
                $quotation->update([
                    'customer_id' => $this->is_registered ? $this->customer_id : null,
                    'client_name' => $fullName,
                    'client_email' => $this->is_registered ? null : $this->client_email,
                    'subtotal' => $this->getSubtotalProperty(),
                    'handling_total' => $this->getHandlingTotalProperty(),
                    'total' => $this->getTotalProperty(),
                    'service_type' => $this->service_type,
                    'notes' => $this->notes,
                ]);
                QuotationItem::where('quotation_id', $quotation->id)->delete();
            } else {
                $prefix = 'COT-';
                $lastQuotation = Quotation::where('tenant_id', $tenantId)->latest('id')->first();
                $nextNumber = $lastQuotation ? intval(str_replace($prefix, '', $lastQuotation->number)) + 1 : 1;
                $number = $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

                $quotation = Quotation::create([
                    'tenant_id' => $tenantId,
                    'customer_id' => $this->is_registered ? $this->customer_id : null,
                    'client_name' => $fullName,
                    'client_email' => $this->is_registered ? null : $this->client_email,
                    'number' => $number,
                    'subtotal' => $this->getSubtotalProperty(),
                    'handling_total' => $this->getHandlingTotalProperty(),
                    'total' => $this->getTotalProperty(),
                    'status' => 'draft',
                    'service_type' => $this->service_type,
                    'notes' => $this->notes,
                ]);
            }

            foreach ($this->items as $item) {
                QuotationItem::create([
                    'tenant_id' => $tenantId,
                    'quotation_id' => $quotation->id,
                    'item_number' => $item['item_number'] ?? '',
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'handling_price' => $item['handling_price'],
                    'total' => $item['total'],
                ]);
            }
        });

        $this->dispatch('quotation-saved');
        session()->flash('message', $this->quotation_id ? 'Cotización actualizada con éxito.' : 'Cotización generada con éxito.');
        $this->initForm();
    }

    public function render()
    {
        $tenant = \App\Models\Tenant::find(session('tenant_id'));
        $currency = $tenant->settings_json['currency'] ?? 'USD';

        return view('livewire.billing.create-quotation', [
            'currency' => $currency,
        ]);
    }
}
