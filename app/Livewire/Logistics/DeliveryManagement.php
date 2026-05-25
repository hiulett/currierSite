<?php

namespace App\Livewire\Logistics;

use Livewire\Component;
use App\Models\Delivery;
use App\Models\Package;
use App\Models\User;
use Livewire\WithPagination;

class DeliveryManagement extends Component
{
    use WithPagination;

    public $route_name;
    public $driver_id;
    public $selected_packages = [];
    public $search_package = '';
    public $cod_amount = 0;
    public $filter_status = '';

    protected $queryString = [
        'filter_status' => ['except' => ''],
    ];

    public function setFilter($status)
    {
        $this->filter_status = $status;
        $this->resetPage();
    }

    public function createDelivery()
    {
        $this->validate([
            'route_name' => 'required|string',
            'driver_id' => 'required|exists:users,id',
            'selected_packages' => 'required|array|min:1',
            'cod_amount' => 'nullable|numeric|min:0',
        ]);

        $delivery = Delivery::create([
            'route_name' => $this->route_name,
            'driver_id' => $this->driver_id,
            'status' => 'pending',
            'cod_amount' => $this->cod_amount ?? 0,
        ]);

        foreach (Package::whereIn('id', $this->selected_packages)->get() as $package) {
            $package->update([
                'delivery_id' => $delivery->id,
                'status' => 'out_for_delivery'
            ]);
        }

        $this->reset(['route_name', 'driver_id', 'selected_packages']);
        session()->flash('message', 'Entrega de última milla creada y asignada.');
    }

    public function togglePackage($packageId)
    {
        if (in_array($packageId, $this->selected_packages)) {
            $this->selected_packages = array_diff($this->selected_packages, [$packageId]);
        } else {
            $this->selected_packages[] = $packageId;
        }
    }

    public function render()
    {
        $packages = Package::whereNull('delivery_id')
            ->whereIn('status', ['received', 'ready_for_pickup'])
            ->where(function($q) {
                $q->where('tracking_number', 'like', '%' . $this->search_package . '%')
                  ->orWhereHas('customer', function($sub) {
                      $sub->where('box_number', 'like', '%' . $this->search_package . '%');
                  });
            })
            ->get();

        $drivers = User::where('role', 'operator') // Simplified: using operators as drivers for now
            ->orWhere('role', 'admin')
            ->get();

        $query = Delivery::with('driver')->withCount('packages');

        if ($this->filter_status) {
            $query->where('status', $this->filter_status);
        }

        $stats = [
            'pending_deliveries' => Delivery::where('status', 'pending')->count(),
            'on_route' => Delivery::where('status', 'out_for_delivery')->count(),
            'delivered_today' => Delivery::where('status', 'delivered')->whereDate('updated_at', now()->today())->count(),
        ];

        return view('livewire.logistics.delivery-management', [
            'packages' => $packages,
            'drivers' => $drivers,
            'deliveries' => $query->latest()->paginate(5),
            'stats' => $stats
        ])->layout('components.layouts.app');
    }
}
