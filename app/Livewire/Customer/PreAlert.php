<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Package;
use App\Models\Customer;
use App\Models\Warehouse;
use Livewire\WithFileUploads;

class PreAlert extends Component
{
    use WithFileUploads;

    public $tracking_number;
    public $warehouse_id;
    public $description;
    public $declared_value;
    public $invoice_file;
    public $is_scanning = false;

    public function scanInvoice()
    {
        if (!$this->invoice_file) {
            session()->flash('error', 'Por favor, sube una factura primero.');
            return;
        }

        $this->is_scanning = true;

        // Simulate AI Processing time
        sleep(2);

        // Mocked IA Logic (In production, here you call Google Vision or AWS Textract)
        // We'll simulate finding a value between $10 and $500
        $mockedValue = rand(45, 350) + (rand(0, 99) / 100);
        $this->declared_value = $mockedValue;

        $stores = ['Amazon', 'eBay', 'Walmart', 'Apple Store'];
        $this->description = "Compra en " . $stores[array_rand($stores)] . " (Detectado por IA)";

        $this->is_scanning = false;
        session()->flash('message', '¡Escaneo exitoso! Hemos detectado el valor y la tienda.');
    }

    public function save()
    {
        $this->validate([
            'tracking_number' => 'required|string|unique:packages,tracking_number',
            'warehouse_id' => 'required|exists:warehouses,id',
            'description' => 'required|string',
            'declared_value' => 'nullable|numeric|min:0',
            'invoice_file' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:4096',
        ]);

        $customer = auth()->user()->customer;

        if (!$customer) {
            session()->flash('error', 'No tienes un perfil de cliente asociado.');
            return;
        }

        $invoiceUrl = null;
        if ($this->invoice_file) {
            $path = $this->invoice_file->store('invoices/' . $customer->tenant_id, 'public');
            $invoiceUrl = asset('storage/' . $path);
        }

        Package::create([
            'tenant_id' => $customer->tenant_id,
            'customer_id' => $customer->id,
            'warehouse_id' => $this->warehouse_id,
            'tracking_number' => $this->tracking_number,
            'description' => $this->description,
            'declared_value' => $this->declared_value ?? 0,
            'invoice_url' => $invoiceUrl,
            'status' => 'prealert',
        ]);

        session()->flash('message', 'Pre-alerta registrada correctamente. Estaremos atentos a la llegada de tu paquete.');
        return redirect()->route('customer.dashboard');
    }

    public function render()
    {
        return view('livewire.customer.pre-alert', [
            'warehouses' => Warehouse::all()
        ])->layout('components.customer-layout');
    }
}
