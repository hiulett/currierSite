<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Package;
use App\Models\Customer;
use Livewire\WithPagination;
use App\Traits\WithSorting;

class PackageList extends Component
{
    use WithPagination, WithSorting;

    public $status = 'all';
    public $search = '';

    protected $queryString = ['status', 'search'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function setDeliveryType($packageId, $type)
    {
        $package = Package::where('customer_id', auth()->user()->customer->id)->findOrFail($packageId);

        if (!in_array($package->status, ['delivered', 'cancelled'])) {
            $package->update(['delivery_type' => $type]);
            session()->flash('message', 'Preferencia de entrega actualizada.');
        } else {
            session()->flash('error', 'No se puede cambiar el tipo de entrega de un paquete entregado o cancelado.');
        }
    }

    public function render()
    {
        $customer = auth()->user()->customer;

        if (!$customer) {
            return view('livewire.customer.dashboard-error', [
                'title' => 'Perfil no encontrado',
                'message' => 'No tienes un perfil de cliente asociado para ver paquetes.'
            ])->layout('components.customer-layout');
        }

        // Calculate Stats
        $stats = [
            'in_warehouse' => Package::where('customer_id', $customer->id)->where('status', 'received')->count(),
            'in_transit' => Package::where('customer_id', $customer->id)->where('status', 'in_transit')->count(),
            'ready' => Package::where('customer_id', $customer->id)->where('status', 'ready_for_pickup')->count(),
            'delivered' => Package::where('customer_id', $customer->id)->where('status', 'delivered')->count(),
        ];

        $query = Package::where('customer_id', $customer->id)
            ->with(['trackingEvents' => function($q) {
                $q->latest();
            }, 'warehouse']);

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('tracking_number', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.customer.package-list', [
            'packages' => $this->applySorting($query)->paginate(10),
            'stats' => $stats
        ])->layout('components.customer-layout');
    }
}
