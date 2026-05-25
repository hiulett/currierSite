<?php

namespace App\Livewire\Logistics;

use Livewire\Component;
use App\Models\Shipment;
use Livewire\WithPagination;
use App\Traits\WithSorting;
use Illuminate\Support\Str;

class ShipmentList extends Component
{
    use WithPagination, WithSorting;

    public $manifest_number;
    public $carrier_name;
    public $filter_status = '';

    protected $queryString = [
        'filter_status' => ['except' => ''],
    ];

    public function createShipment()
    {
        $this->validate([
            'manifest_number' => 'required|string|unique:shipments,manifest_number',
        ]);

        $shipment = Shipment::create([
            'manifest_number' => $this->manifest_number,
            'carrier_name' => $this->carrier_name,
            'status' => 'draft',
        ]);

        $this->reset(['manifest_number', 'carrier_name']);
        return redirect()->route('logistics.shipments.detail', $shipment->id);
    }

    public function render()
    {
        $query = Shipment::withCount('packages');

        if ($this->filter_status) {
            $query->where('status', $this->filter_status);
        }

        $stats = [
            'preparing' => Shipment::where('status', 'draft')->count(),
            'in_transit' => Shipment::where('status', 'in_transit')->count(),
            'arrived_recently' => Shipment::where('status', 'arrived')->whereDate('updated_at', now()->today())->count(),
        ];

        return view('livewire.logistics.shipment-list', [
            'shipments' => $this->applySorting($query)->paginate(10),
            'stats' => $stats
        ])->layout('components.layouts.app');
    }
}
