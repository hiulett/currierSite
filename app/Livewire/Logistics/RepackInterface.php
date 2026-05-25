<?php

namespace App\Livewire\Logistics;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class RepackInterface extends Component
{
    public $box_number;
    public $found_customer = null;
    public $selected_packages = [];

    // New box info
    public $new_weight;
    public $new_length;
    public $new_width;
    public $new_height;
    public $new_description = 'CONSOLIDADO';
    public $warehouse_id;

    public function updatedBoxNumber($value)
    {
        $this->found_customer = Customer::where('box_number', $value)->first();
        $this->selected_packages = [];
    }

    public function togglePackage($packageId)
    {
        if (in_array($packageId, $this->selected_packages)) {
            $this->selected_packages = array_diff($this->selected_packages, [$packageId]);
        } else {
            $this->selected_packages[] = $packageId;
        }
    }

    public function processRepack()
    {
        $this->validate([
            'selected_packages' => 'required|array|min:1',
            'new_weight' => 'required|numeric|min:0.1',
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        DB::transaction(function() {
            // 1. Create the Master Package
            $masterPackage = Package::create([
                'tenant_id' => $this->found_customer->tenant_id,
                'customer_id' => $this->found_customer->id,
                'warehouse_id' => $this->warehouse_id,
                'tracking_number' => 'REPACK-' . strtoupper(bin2hex(random_bytes(4))),
                'internal_tracking' => 'RE-' . date('YmdHis'),
                'description' => $this->new_description,
                'weight' => $this->new_weight,
                'length' => $this->new_length,
                'width' => $this->new_width,
                'height' => $this->new_height,
                'volumetric_weight' => ($this->new_length * $this->new_width * $this->new_height) / 166,
                'status' => 'received',
                'is_repacked' => true,
            ]);

            // 2. Link children and mark them as consolidated
            Package::whereIn('id', $this->selected_packages)->update([
                'parent_id' => $masterPackage->id,
                'status' => 'consolidated'
            ]);
        });

        session()->flash('message', '¡Consolidación exitosa!');
        $this->reset(['selected_packages', 'new_weight', 'new_length', 'new_width', 'new_height', 'new_description']);
        $this->updatedBoxNumber($this->box_number); // Refresh list
    }

    public function render()
    {
        $packages = [];
        if ($this->found_customer) {
            $packages = Package::where('customer_id', $this->found_customer->id)
                ->where('status', 'received')
                ->whereNull('parent_id')
                ->get();
        }

        $stats = [
            'waiting_repack' => Package::where('status', 'received')->whereNull('parent_id')->count(),
            'total_weight_pending' => Package::where('status', 'received')->whereNull('parent_id')->sum('weight'),
            'completed_today' => Package::where('is_repacked', true)->whereDate('created_at', now()->today())->count(),
        ];

        return view('livewire.logistics.repack-interface', [
            'packages' => $packages,
            'warehouses' => Warehouse::all(),
            'stats' => $stats
        ])->layout('components.layouts.app');
    }
}
