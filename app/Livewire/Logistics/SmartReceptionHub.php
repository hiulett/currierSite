<?php

namespace App\Livewire\Logistics;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Warehouse;
use App\Models\Manifest;
use App\Models\ManifestItem;
use App\Services\Logistics\AIParserService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SmartReceptionHub extends Component
{
    use WithFileUploads;

    // OCR / File Upload
    public $invoiceFile;
    public $ocrResults = [];
    public $isProcessing = false;
    public $invoiceNumber;

    // Manual Reception Fields (Keep for individual registration)
    public $tracking_number;
    public $box_number;
    public $weight = 0;
    public $warehouse_id;
    public $found_customer = null;
    public $customer_search = '';
    public $search_results = [];

    // Dashboard State
    public $mode = 'manual'; // 'manual', 'ocr'
    public $auto_invoice = true;

    public function mount()
    {
        $this->warehouse_id = Warehouse::first()->id ?? null;
    }

    public function updatedInvoiceFile()
    {
        $this->validate([
            'invoiceFile' => 'mimes:jpeg,png,jpg,pdf|max:10240',
        ]);

        $this->processOCR();
    }

    public function processOCR()
    {
        $this->isProcessing = true;

        try {
            $fullPath = $this->invoiceFile->getRealPath();
            $parser = new AIParserService();
            $result = $parser->parseGlobalExpressInvoice($fullPath);

            $this->ocrResults = $result['items'];
            $this->invoiceNumber = $result['invoice_number'] ?? 'FAC-' . date('His');
            $this->mode = 'ocr';

            session()->flash('message', 'Factura analizada. Se detectaron ' . count($this->ocrResults) . ' ítems de carga esperada.');
        } catch (\Exception $e) {
            Log::error("Error en SmartReception OCR: " . $e->getMessage());
            session()->flash('error', 'Error al procesar la factura: ' . $e->getMessage());
        }

        $this->isProcessing = false;
    }

    public function saveAllOCRItems()
    {
        if (empty($this->ocrResults)) return;

        $count = count($this->ocrResults);

        try {
            DB::transaction(function() use ($count) {
                // 1. Crear Manifiesto de Carga Esperada (Sin afectar inventario)
                $manifest = Manifest::create([
                    'tenant_id' => session('tenant_id') ?? auth()->user()->tenant_id ?? 1,
                    'number' => 'MAN-' . date('Ymd-His'),
                    'carrier_invoice_number' => $this->invoiceNumber,
                    'status' => 'pending',
                    'created_by' => auth()->id(),
                    'total_items_expected' => $count,
                ]);

                // 2. Registrar los ítems en estado "esperado"
                foreach ($this->ocrResults as $item) {
                    ManifestItem::create([
                        'manifest_id' => $manifest->id,
                        'tenant_id' => $manifest->tenant_id,
                        'tracking_number' => strtoupper(trim($item['tracking'])),
                        'weight' => $item['weight'] ?? 0,
                        'length' => $item['length'] ?? 1,
                        'width' => $item['width'] ?? 1,
                        'height' => $item['height'] ?? 1,
                        'status' => 'expected',
                    ]);
                }
            });

            $this->ocrResults = [];
            session()->flash('message', "¡Éxito! Se ha generado el Manifiesto con $count ítems. La carga ya figura como 'Esperada' y podrá ser recibida físicamente al llegar a bodega.");

        } catch (\Exception $e) {
            Log::error("Error al confirmar lote OCR: " . $e->getMessage());
            session()->flash('error', 'Error al guardar el manifiesto: ' . $e->getMessage());
        }
    }

    // Individual registration logic remains for manual mode
    public function selectCustomer($customerId)
    {
        $this->found_customer = Customer::with('user')->find($customerId);
        $this->box_number = $this->found_customer->box_number;
        $this->customer_search = $this->found_customer->user->name . ' (' . $this->found_customer->box_number . ')';
        $this->search_results = [];
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
                $q->where('name', 'like', '%' . $value . '%');
            })->take(5)->get();
    }

    public function saveManual()
    {
        $this->validate([
            'tracking_number' => 'required|string|unique:packages,tracking_number',
            'box_number' => 'required|exists:customers,box_number',
            'weight' => 'required|numeric|min:0.01',
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        try {
            DB::transaction(function() {
                Package::create([
                    'tenant_id' => session('tenant_id') ?? auth()->user()->tenant_id ?? 1,
                    'customer_id' => $this->found_customer->id,
                    'warehouse_id' => $this->warehouse_id,
                    'tracking_number' => strtoupper(trim($this->tracking_number)),
                    'weight' => $this->weight,
                    'status' => 'received',
                ]);
                $this->found_customer->increment('points', ceil($this->weight));
            });

            session()->flash('message', 'Paquete registrado exitosamente.');
            $this->reset(['tracking_number', 'customer_search', 'weight', 'found_customer', 'box_number']);
            $this->dispatch('package-saved');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar el paquete.');
        }
    }

    public function render()
    {
        return view('livewire.logistics.smart-reception-hub', [
            'warehouses' => Warehouse::all(),
            'lastPackages' => Package::with('customer.user')->latest()->take(10)->get(),
        ])->layout('components.layouts.app');
    }
}
