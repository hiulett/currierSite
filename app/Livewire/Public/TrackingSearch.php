<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Package;

class TrackingSearch extends Component
{
    public $search_tracking;
    public $package = null;
    public $searched = false;

    public function search()
    {
        $this->validate([
            'search_tracking' => 'required|string|min:5',
        ]);

        $this->package = Package::with(['trackingEvents' => function($q) {
                $q->orderBy('created_at', 'desc');
            }])
            ->where('tracking_number', trim($this->search_tracking))
            ->orWhere('internal_tracking', trim($this->search_tracking))
            ->first();

        $this->searched = true;
    }

    public function render()
    {
        $layout = request()->query('embedded') ? 'components.embedded-layout' : 'components.public-layout';
        return view('livewire.public.tracking-search')->layout($layout);
    }
}
