<?php

namespace App\Livewire\Builder;

use Livewire\Component;
use App\Models\Warehouse;
use Livewire\WithPagination;

class WarehouseSettings extends Component
{
    use WithPagination;

    public $search = '';

    // Form fields
    public $warehouse_id;
    public $name;
    public $code;
    public $address;
    public $city;
    public $state;
    public $zip_code;
    public $country = 'USA';
    public $service_type = 'both';
    public $is_active = true;

    public $is_editing = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:10',
        'address' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'zip_code' => 'required|string|max:20',
        'country' => 'required|string|max:100',
        'service_type' => 'required|in:air,maritime,both',
        'is_active' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetFields()
    {
        $this->reset(['warehouse_id', 'name', 'code', 'address', 'city', 'state', 'zip_code', 'country', 'service_type', 'is_active', 'is_editing']);
    }

    public function createWarehouse()
    {
        $this->resetFields();
        $this->dispatch('open-warehouse-modal');
    }

    public function editWarehouse(Warehouse $warehouse)
    {
        $this->warehouse_id = $warehouse->id;
        $this->name = $warehouse->name;
        $this->code = $warehouse->code;
        $this->address = $warehouse->address;
        $this->city = $warehouse->city;
        $this->state = $warehouse->state;
        $this->zip_code = $warehouse->zip_code;
        $this->country = $warehouse->country;
        $this->service_type = $warehouse->service_type;
        $this->is_active = (bool)$warehouse->is_active;
        $this->is_editing = true;

        $this->dispatch('open-warehouse-modal');
    }

    public function save()
    {
        $this->validate();

        if ($this->is_editing) {
            $warehouse = Warehouse::find($this->warehouse_id);
            $warehouse->update([
                'name' => $this->name,
                'code' => strtoupper($this->code),
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'zip_code' => $this->zip_code,
                'country' => $this->country,
                'service_type' => $this->service_type,
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Bodega actualizada exitosamente.');
        } else {
            Warehouse::create([
                'tenant_id' => session('tenant_id'),
                'name' => $this->name,
                'code' => strtoupper($this->code),
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'zip_code' => $this->zip_code,
                'country' => $this->country,
                'service_type' => $this->service_type,
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Bodega creada exitosamente.');
        }

        $this->resetFields();
        $this->dispatch('close-warehouse-modal');
    }

    public function toggleStatus(Warehouse $warehouse)
    {
        $warehouse->update(['is_active' => !$warehouse->is_active]);
    }

    public function render()
    {
        $warehouses = Warehouse::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.builder.warehouse-settings', [
            'warehouses' => $warehouses
        ])->layout('components.layouts.app');
    }
}
