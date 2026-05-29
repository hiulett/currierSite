<?php

namespace App\Livewire\Logistics;

use Livewire\Component;
use App\Models\Delivery;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class DeliveryManifest extends Component
{
    use WithFileUploads;

    public $deliveryId;
    public $delivery;
    public $photo;
    public $signature;
    public $notes;
    public $latitude;
    public $longitude;
    public $cod_collected = 0;
    public $payment_method_collected = 'cash'; // cash, yappy

    public function mount($id)
    {
        $this->deliveryId = $id;
        $this->loadDelivery();
        $this->cod_collected = $this->delivery->cod_amount;
    }

    public function loadDelivery()
    {
        $this->delivery = Delivery::with('packages.customer.user', 'driver')->findOrFail($this->deliveryId);
        $this->notes = $this->delivery->notes;
    }

    public function setLocation($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
    }

    public function completeDelivery()
    {
        $this->validate([
            'photo' => 'nullable|image|max:2048',
            'notes' => 'nullable|string',
            'cod_collected' => 'required|numeric|min:0',
        ]);

        $updateData = [
            'status' => 'delivered',
            'completed_at' => now(),
            'notes' => $this->notes,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'cod_collected' => $this->cod_collected,
        ];

        if ($this->photo) {
            $path = $this->photo->store('delivery_photos', 'public');
            $updateData['photo_path'] = Storage::url($path);
        }

        $this->delivery->update($updateData);

        foreach ($this->delivery->packages as $package) {
            $package->update(['status' => 'delivered']);
        }

        session()->flash('message', 'Entrega finalizada con éxito.');
        return redirect()->route('logistics.delivery');
    }

    public function render()
    {
        return view('livewire.logistics.delivery-manifest')->layout('components.layouts.app');
    }
}
