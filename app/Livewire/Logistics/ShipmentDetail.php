<?php

namespace App\Livewire\Logistics;

use Livewire\Component;
use App\Models\Shipment;
use App\Models\Package;

class ShipmentDetail extends Component
{
    public Shipment $shipment;
    public $search_package = '';

    public function mount(Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    public function addPackage($packageId)
    {
        $package = Package::find($packageId);
        if ($package && !$package->shipment_id) {
            $package->update(['shipment_id' => $this->shipment->id]);
            $this->shipment->load('packages');
        }
    }

    public function removePackage($packageId)
    {
        $package = Package::find($packageId);
        if ($package && $package->shipment_id == $this->shipment->id) {
            $package->update(['shipment_id' => null]);
            $this->shipment->load('packages');
        }
    }

    public function updateStatus($status)
    {
        $this->shipment->update(['status' => $status]);

        $packageStatus = match ($status) {
            'in_transit' => 'in_transit',
            'arrived' => 'ready_for_pickup',
            default => null,
        };

        if ($packageStatus) {
            foreach ($this->shipment->packages as $package) {
                $package->update(['status' => $packageStatus]);
            }
        }
    }

    public function render()
    {
        $availablePackages = Package::whereNull('shipment_id')
            ->whereIn('status', ['received', 'prealert'])
            ->where(function($q) {
                $q->where('tracking_number', 'like', '%' . $this->search_package . '%')
                  ->orWhereHas('customer', function($sub) {
                      $sub->where('box_number', 'like', '%' . $this->search_package . '%');
                  });
            })
            ->take(5)
            ->get();

        return view('livewire.logistics.shipment-detail', [
            'availablePackages' => $availablePackages
        ])->layout('components.layouts.app');
    }
}
