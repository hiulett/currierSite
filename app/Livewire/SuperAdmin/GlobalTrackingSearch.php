<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Package;
use Livewire\WithPagination;

class GlobalTrackingSearch extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $results = [];
        if (strlen($this->search) >= 3) {
            $results = Package::withoutGlobalScope('tenant')
                ->with(['tenant', 'customer.user'])
                ->where('tracking_number', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%')
                ->paginate(20);
        }

        return view('livewire.super-admin.global-tracking-search', [
            'packages' => $results
        ])->layout('components.super-admin-layout');
    }
}
