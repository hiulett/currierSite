<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Package;
use App\Services\ExternalTrackingService;

class TrackingSearch extends Component
{
    public $search_tracking;
    public $package = null;
    public $external_data = null;
    public $searched = false;

    public function search(ExternalTrackingService $trackingService)
    {
        $this->validate([
            'search_tracking' => 'required|string|min:5',
        ]);

        $this->package = null;
        $this->external_data = null;

        // 1. Local Search
        $this->package = Package::with(['trackingEvents' => function($q) {
                $q->orderBy('created_at', 'desc');
            }])
            ->where('tracking_number', trim($this->search_tracking))
            ->orWhere('internal_tracking', trim($this->search_tracking))
            ->first();

        // 2. External Search (if not found locally)
        if (!$this->package) {
            $this->external_data = $trackingService->trackFuzionCargo($this->search_tracking);
        }

        $this->searched = true;
    }

    public function render()
    {
        $layout = request()->query('embedded') ? 'components.embedded-layout' : 'components.public-layout';
        return view('livewire.public.tracking-search')->layout($layout);
    }
}
