<?php

namespace App\Livewire\Logistics;

use Livewire\Component;
use App\Models\Manifest;
use App\Models\ManifestItem;
use App\Models\Package;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;

use Livewire\WithFileUploads;
use App\Services\Logistics\ManifestParserService;

class ReceiveManifest extends Component
{
    use WithPagination, WithFileUploads;

    public $manifest_id;
    public $carrier_invoice;
    public $tracking_input; // For bulk input
    public $scanner_input;  // For single scan matching
    public $manifest_file;  // New: For PDF upload

    // Management & Search
    public $search = '';
    public $status_filter = '';

    // CRUD fields for header editing
    public $number;
    public $carrier_name;
    public $description;
    public $status;
    public $isEditModalOpen = false;

    // Pre-review management
    public $extracted_trackings = [];
    public $editing_index = null;
    public $editing_value = '';
    public $new_tracking = '';

    // Detail view item filter
    public $item_status_filter = 'all';

    public $view_mode = 'list'; // list, create, review, scanning, detail

    // New: Selected warehouse for reception
    public $warehouse_id;

    protected $rules = [
        'carrier_invoice' => 'required|string',
    ];

    protected $queryString = ['search', 'status_filter'];

    public function mount($manifest_id = null)
    {
        if ($manifest_id) {
            $this->selectManifest($manifest_id);
        }

        // Cargar bodega por defecto si existe alguna
        $defaultWarehouse = \App\Models\Warehouse::first();
        if ($defaultWarehouse) {
            $this->warehouse_id = $defaultWarehouse->id;
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    /**
     * Gestión CRUD - Abrir Modal de Edición
     */
    public function openEditModal($id)
    {
        $this->manifest_id = $id;
        $manifest = Manifest::findOrFail($id);

        $this->number = $manifest->number;
        $this->carrier_name = $manifest->carrier_name;
        $this->carrier_invoice = $manifest->carrier_invoice_number;
        $this->description = $manifest->description;
        $this->status = $manifest->status;

        $this->isEditModalOpen = true;
    }

    public function closeEditModal()
    {
        $this->isEditModalOpen = false;
    }

    public function saveManifestHeader()
    {
        $this->validate([
            'number' => 'required|unique:manifests,number,' . $this->manifest_id,
            'carrier_invoice' => 'required|string',
            'status' => 'required|in:pending,processing,reconciled,closed',
        ]);

        $manifest = Manifest::find($this->manifest_id);
        $manifest->update([
            'number' => $this->number,
            'carrier_name' => $this->carrier_name,
            'carrier_invoice_number' => $this->carrier_invoice,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        $this->isEditModalOpen = false;
        session()->flash('message', 'Encabezado de manifiesto actualizado.');
    }

    public function deleteManifest($id)
    {
        $manifest = Manifest::findOrFail($id);

        if ($manifest->file_path) {
            Storage::disk('public')->delete($manifest->file_path);
        }

        $manifest->delete();
        session()->flash('message', 'Manifiesto eliminado correctamente.');
    }

    /**
     * Al subir un archivo, intentamos extraer los trackings automáticamente (OCR Gratuito)
     */
    public function updatedManifestFile()
    {
        $this->validate([
            'manifest_file' => 'mimes:pdf|max:10240', // Max 10MB
        ]);

        $parser = new ManifestParserService();
        $path = $this->manifest_file->getRealPath();
        $extractedData = $parser->parsePdf($path);

        if (!empty($extractedData['trackings'])) {
            $this->extracted_trackings = $extractedData['trackings'];

            if ($extractedData['invoice_number']) {
                $this->carrier_invoice = $extractedData['invoice_number'];
            }

            $this->view_mode = 'review';
            session()->flash('ocr_message', "✅ Se detectaron " . count($this->extracted_trackings) . " trackings con sus dimensiones.");
        } else {
            session()->flash('ocr_error', "❌ No se pudieron detectar trackings en este PDF.");
        }
    }

    public function addManualTracking()
    {
        if (empty($this->new_tracking)) return;

        $this->extracted_trackings[] = [
            'tracking' => strtoupper(trim($this->new_tracking)),
            'weight' => 0,
            'length' => 1,
            'width' => 1,
            'height' => 1
        ];
        $this->new_tracking = '';
    }

    public function removeTracking($index)
    {
        unset($this->extracted_trackings[$index]);
        $this->extracted_trackings = array_values($this->extracted_trackings);
    }

    public function editTracking($index)
    {
        $this->editing_index = $index;
        $this->editing_value = $this->extracted_trackings[$index]['tracking'];
    }

    public function saveEdit()
    {
        if ($this->editing_index !== null) {
            $this->extracted_trackings[$this->editing_index]['tracking'] = strtoupper(trim($this->editing_value));

            $this->editing_index = null;
            $this->editing_value = '';
        }
    }

    public function confirmReview()
    {
        if (empty($this->extracted_trackings)) {
            session()->flash('review_error', "Debes tener al menos un tracking para continuar.");
            return;
        }

        $this->createManifestFromReview();
    }

    protected function createManifestFromReview()
    {
        $this->validate();

        DB::transaction(function () {
            $items = collect($this->extracted_trackings)->unique('tracking')->values();

            $filePath = null;
            if ($this->manifest_file) {
                $filePath = $this->manifest_file->store('manifests', 'public');
            }

            $manifest = Manifest::create([
                'tenant_id' => session('tenant_id'),
                'number' => 'MAN-' . date('Ymd-His'),
                'carrier_invoice_number' => $this->carrier_invoice,
                'file_path' => $filePath,
                'status' => 'processing',
                'created_by' => auth()->id(),
                'total_items_expected' => $items->count(),
            ]);

            foreach ($items as $item) {
                ManifestItem::create([
                    'manifest_id' => $manifest->id,
                    'tenant_id' => session('tenant_id'),
                    'tracking_number' => $item['tracking'],
                    'weight' => $item['weight'] ?? 0,
                    'length' => $item['length'] ?? 0,
                    'width' => $item['width'] ?? 0,
                    'height' => $item['height'] ?? 0,
                    'status' => 'expected',
                ]);
            }

            $this->manifest_id = $manifest->id;
            $this->view_mode = 'scanning';
        });

        $this->reset(['extracted_trackings', 'carrier_invoice', 'manifest_file']);
    }

    public function createManifest()
    {
        $this->validate([
            'carrier_invoice' => 'required',
            'tracking_input' => 'required',
        ]);

        $trackings = preg_split('/\r\n|\r|\n|,| /', $this->tracking_input);
        $trackings = array_filter(array_map('trim', $trackings));

        $this->extracted_trackings = [];
        foreach ($trackings as $t) {
            $this->extracted_trackings[] = [
                'tracking' => $t,
                'weight' => 0,
                'length' => 1,
                'width' => 1,
                'height' => 1
            ];
        }

        $this->createManifestFromReview();
    }

    public function selectManifest($id)
    {
        $this->manifest_id = $id;
        $manifest = Manifest::find($id);

        if ($manifest->status === 'processing' || $manifest->status === 'pending') {
            // Si estaba pendiente, lo pasamos a procesamiento al abrirlo para escanear
            if ($manifest->status === 'pending') {
                $manifest->update(['status' => 'processing']);
            }
            $this->view_mode = 'scanning';
        } else {
            $this->view_mode = 'detail';
        }
    }

    public function processScan()
    {
        if (empty($this->scanner_input)) return;

        $tracking = trim($this->scanner_input);

        $item = ManifestItem::where('manifest_id', $this->manifest_id)
            ->where('tracking_number', $tracking)
            ->first();

        if ($item) {
            if ($item->status !== 'received') {
                // 1. Update/Create the actual Package in Inventory
                $package = Package::where('tracking_number', $tracking)
                    ->where('tenant_id', session('tenant_id'))
                    ->first();

                if ($package) {
                    $package->update(['status' => 'arrived']);
                } else {
                    // Si el paquete no existe en sistema, lo creamos "Sin Dueño"
                    $package = Package::create([
                        'tenant_id' => session('tenant_id'),
                        'tracking_number' => $tracking,
                        'weight' => $item->weight ?: 0,
                        'length' => $item->length ?: 0,
                        'width' => $item->width ?: 0,
                        'height' => $item->height ?: 0,
                        'warehouse_id' => $this->warehouse_id,
                        'status' => 'arrived',
                        'description' => 'Ingresado por manifiesto ' . $tracking,
                    ]);
                }

                // 2. Link the manifest item to the package
                $item->update([
                    'status' => 'received',
                    'scanned_at' => now(),
                    'package_id' => $package->id
                ]);

                session()->flash('scan_message', "✅ Tracking $tracking recibido y agregado al inventario.");
                $this->dispatch('play-sound', type: 'success');
            } else {
                session()->flash('scan_warning', "⚠️ Tracking $tracking ya fue recibido previamente.");
                $this->dispatch('play-sound', type: 'warning');
            }
        } else {
            // Surplus detected: No estaba en factura, pero lo creamos en inventario igualmente
            $package = Package::create([
                'tenant_id' => session('tenant_id'),
                'tracking_number' => $tracking,
                'weight' => 0,
                'warehouse_id' => $this->warehouse_id,
                'status' => 'arrived',
                'description' => 'SOBRANTE: Ingresado por manifiesto',
            ]);

            ManifestItem::create([
                'manifest_id' => $this->manifest_id,
                'tenant_id' => session('tenant_id'),
                'tracking_number' => $tracking,
                'package_id' => $package->id,
                'status' => 'surplus',
                'scanned_at' => now(),
                'observation' => 'Paquete no estaba en la factura inicial.',
            ]);
            session()->flash('scan_error', "🚨 SOBRANTE: $tracking agregado como sin dueño.");
            $this->dispatch('play-sound', type: 'error');
        }

        $this->scanner_input = '';
        $this->updateManifestStats();
    }

    protected function updateManifestStats()
    {
        $manifest = Manifest::find($this->manifest_id);
        if ($manifest) {
            $receivedCount = ManifestItem::where('manifest_id', $this->manifest_id)
                ->where('status', 'received')
                ->count();
            $manifest->update(['total_items_received' => $receivedCount]);
        }
    }

    public function closeManifest()
    {
        $manifest = Manifest::find($this->manifest_id);

        // Mark remaining expected items as missing
        ManifestItem::where('manifest_id', $this->manifest_id)
            ->where('status', 'expected')
            ->update(['status' => 'missing']);

        $manifest->update(['status' => 'reconciled']);
        $this->view_mode = 'detail';
        session()->flash('message', 'Manifiesto conciliado y cerrado.');
    }

    public function reopenManifest()
    {
        $manifest = Manifest::findOrFail($this->manifest_id);

        // Regresar a procesamiento
        $manifest->update(['status' => 'processing']);

        // Los ítems que estaban como 'missing' vuelven a ser 'expected' para permitir re-escaneo
        ManifestItem::where('manifest_id', $this->manifest_id)
            ->where('status', 'missing')
            ->update(['status' => 'expected']);

        $this->view_mode = 'scanning';
        session()->flash('message', 'Manifiesto reabierto para edición y escaneo.');
    }

    public function render()
    {
        $query = Manifest::where('tenant_id', session('tenant_id'))
            ->with('creator')
            ->where(function($q) {
                $q->where('number', 'like', '%' . $this->search . '%')
                  ->orWhere('carrier_invoice_number', 'like', '%' . $this->search . '%');
            });

        if ($this->status_filter) {
            $query->where('status', $this->status_filter);
        }

        $manifests = $query->latest()->paginate(10);

        $activeManifest = $this->manifest_id ? Manifest::with(['items.package', 'creator'])->find($this->manifest_id) : null;

        $items = [];
        if ($activeManifest) {
            $itemsQuery = ManifestItem::where('manifest_id', $this->manifest_id);

            if ($this->item_status_filter === 'missing') {
                $itemsQuery->where('status', 'missing');
            } elseif ($this->item_status_filter === 'surplus') {
                $itemsQuery->where('status', 'surplus');
            }

            $items = $itemsQuery->orderBy('status', 'desc')
                ->orderBy('scanned_at', 'desc')
                ->get();
        }

        return view('livewire.logistics.receive-manifest', [
            'manifests' => $manifests,
            'activeManifest' => $activeManifest,
            'items' => $items,
        ])->layout('components.layouts.app');
    }
}
