<?php

namespace App\Livewire\Logistics;

use Livewire\Component;
use App\Models\Package;
use Livewire\WithPagination;
use App\Traits\WithSorting;

class InventoryList extends Component
{
    use WithPagination, WithSorting;

    public $search = '';

    public $filter_warehouse = '';
    public $filter_status = '';
    public $filter_delivery_type = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filter_warehouse' => ['except' => ''],
        'filter_status' => ['except' => ''],
        'filter_delivery_type' => ['except' => ''],
    ];

    // Edit Package Properties
    public $editing_package_id = null;
    public $edit_tracking_number;
    public $edit_description;
    public $edit_weight;
    public $edit_status;
    public $edit_warehouse_id;

    protected $listeners = ['package-saved' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editPackage($id)
    {
        $package = Package::findOrFail($id);
        $this->editing_package_id = $id;
        $this->edit_tracking_number = $package->tracking_number;
        $this->edit_description = $package->description;
        $this->edit_weight = $package->weight;
        $this->edit_status = $package->status;
        $this->edit_warehouse_id = $package->warehouse_id;

        $this->dispatch('open-edit-modal');
    }

    public function updatePackage()
    {
        $this->validate([
            'edit_tracking_number' => 'required|string',
            'edit_weight' => 'required|numeric|min:0',
            'edit_status' => 'required|string',
            'edit_warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $package = Package::findOrFail($this->editing_package_id);
        $package->update([
            'tracking_number' => $this->edit_tracking_number,
            'description' => $this->edit_description,
            'weight' => $this->edit_weight,
            'status' => $this->edit_status,
            'warehouse_id' => $this->edit_warehouse_id,
        ]);

        session()->flash('message', 'Paquete actualizado correctamente.');
        $this->editing_package_id = null;
        $this->dispatch('close-edit-modal');
    }

    public function render()
    {
        $query = Package::with(['customer.user', 'warehouse'])
            ->where(function($query) {
                $query->where('tracking_number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('customer', function($q) {
                          $q->where('box_number', 'like', '%' . $this->search . '%');
                      });
            });

        if ($this->filter_warehouse) {
            $query->where('warehouse_id', $this->filter_warehouse);
        }

        if ($this->filter_status) {
            $query->where('status', $this->filter_status);
        }

        if ($this->filter_delivery_type) {
            $query->where('delivery_type', $this->filter_delivery_type);
        }

        $packages = $this->applySorting($query)->paginate(10);

        // Dashboard Stats
        $stats = [
            'total_count' => Package::whereNotIn('status', ['delivered', 'cancelled'])->count(),
            'total_weight' => Package::whereNotIn('status', ['delivered', 'cancelled'])->sum('weight'),
            'by_status' => Package::whereNotIn('status', ['delivered', 'cancelled'])
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get(),
            'by_warehouse' => Package::whereNotIn('packages.status', ['delivered', 'cancelled'])
                ->join('warehouses', 'packages.warehouse_id', '=', 'warehouses.id')
                ->selectRaw('warehouses.name, count(*) as count')
                ->groupBy('warehouses.name')
                ->get()
        ];

        return view('livewire.logistics.inventory-list', [
            'packages' => $packages,
            'stats' => $stats,
            'warehouses' => \App\Models\Warehouse::all()
        ])->layout('components.layouts.app');
    }
}
