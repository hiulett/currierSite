<?php

namespace App\Livewire\Logistics;

use Livewire\Component;
use App\Models\Manifest;
use App\Models\ManifestItem;
use App\Models\Package;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class ReceiveManifest extends Component
{
    use WithPagination;

    public $manifest_id;
    public $carrier_invoice;
    public $tracking_input; // For bulk input
    public $scanner_input;  // For single scan matching

    public $view_mode = 'list'; // list, create, scanning, detail

    protected $rules = [
        'carrier_invoice' => 'required|string',
        'tracking_input' => 'required|string',
    ];

    public function createManifest()
    {
        $this->validate();

        DB::transaction(function () {
            $trackings = preg_split('/\r\n|\r|\n|,| /', $this->tracking_input);
            $trackings = array_filter(array_map('trim', $trackings));
            $trackings = array_unique($trackings);

            $manifest = Manifest::create([
                'tenant_id' => session('tenant_id'),
                'number' => 'MAN-' . date('Ymd-His'),
                'carrier_invoice_number' => $this->carrier_invoice,
                'status' => 'processing',
                'created_by' => auth()->id(),
                'total_items_expected' => count($trackings),
            ]);

            foreach ($trackings as $tracking) {
                ManifestItem::create([
                    'manifest_id' => $manifest->id,
                    'tenant_id' => session('tenant_id'),
                    'tracking_number' => $tracking,
                    'status' => 'expected',
                ]);
            }

            $this->manifest_id = $manifest->id;
            $this->view_mode = 'scanning';
        });

        $this->reset(['tracking_input', 'carrier_invoice']);
    }

    public function selectManifest($id)
    {
        $this->manifest_id = $id;
        $manifest = Manifest::find($id);

        if ($manifest->status === 'processing') {
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
                $item->update([
                    'status' => 'received',
                    'scanned_at' => now(),
                ]);

                // Update linked package if it exists in system
                $package = Package::where('tracking_number', $tracking)
                    ->where('tenant_id', session('tenant_id'))
                    ->first();

                if ($package) {
                    $package->update(['status' => 'arrived']);
                    $item->update(['package_id' => $package->id]);
                }

                session()->flash('scan_message', "✅ Tracking $tracking recibido.");
            } else {
                session()->flash('scan_warning', "⚠️ Tracking $tracking ya fue recibido previamente.");
            }
        } else {
            // Surplus detected
            ManifestItem::create([
                'manifest_id' => $this->manifest_id,
                'tenant_id' => session('tenant_id'),
                'tracking_number' => $tracking,
                'status' => 'surplus',
                'scanned_at' => now(),
                'observation' => 'Paquete no estaba en la factura inicial.',
            ]);
            session()->flash('scan_error', "🚨 SOBRANTE: $tracking no está en la factura.");
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

    public function render()
    {
        $manifests = Manifest::where('tenant_id', session('tenant_id'))
            ->with('creator')
            ->latest()
            ->paginate(10);

        $activeManifest = $this->manifest_id ? Manifest::with(['items.package', 'creator'])->find($this->manifest_id) : null;

        $items = [];
        if ($activeManifest) {
            $items = ManifestItem::where('manifest_id', $this->manifest_id)
                ->orderBy('status', 'desc')
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
