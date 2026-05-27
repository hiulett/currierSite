<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Package;
use App\Services\ExternalTrackingService;
use Illuminate\Support\Facades\Auth;

class Tracking extends Component
{
    public $search_tracking = '';
    public $package = null;
    public $external_data = null;
    public $searched = false;

    protected $queryString = ['search_tracking' => ['except' => '']];

    public function mount()
    {
        if ($this->search_tracking) {
            $this->search(new ExternalTrackingService());
        }
    }

    public function search(ExternalTrackingService $trackingService)
    {
        $this->validate([
            'search_tracking' => 'required|string|min:5',
        ]);

        $this->package = null;
        $this->external_data = null;

        // 1. Local Search (Our DB) - Scoped to the customer
        $this->package = Package::with(['trackingEvents' => function($q) {
                $q->orderBy('created_at', 'desc');
            }])
            ->where('customer_id', Auth::user()->customer?->id)
            ->where(function($q) {
                $q->where('tracking_number', trim($this->search_tracking))
                  ->orWhere('internal_tracking', trim($this->search_tracking));
            })
            ->first();

        // 2. External Intelligence Search (Fallback/Live Data)
        // For customers, we show live data from outside if it's not delivered yet or not in our DB
        $international = $trackingService->trackInstantParcels($this->search_tracking);
        $localPanama = $trackingService->trackFuzionCargo($this->search_tracking);

        if ($international || $localPanama) {
            $this->external_data = [
                'tracking' => $this->search_tracking,
                'carrier' => $international['carrier'] ?? 'Detected Carrier',
                'status' => $localPanama['status'] ?? ($international['status'] ?? 'IN TRANSIT'),
                'origin' => $international['origin'] ?? 'USA Hub',
                'destination' => $international['destination'] ?? 'Panama',
                'history' => array_merge($localPanama['history'] ?? [], $international['history'] ?? [])
            ];

            // Sort merged history by date descending
            usort($this->external_data['history'], function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }

        $this->searched = true;
    }

    public function render()
    {
        return view('livewire.customer.tracking')->layout('components.customer-layout');
    }
}
