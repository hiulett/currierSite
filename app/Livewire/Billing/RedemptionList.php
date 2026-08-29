<?php

namespace App\Livewire\Billing;

use App\Models\RedemptionHistory;
use Livewire\Component;
use Livewire\WithPagination;

class RedemptionList extends Component
{
    use WithPagination;

    public $search = '';

    public $filter_status = '';

    public function render()
    {
        $query = RedemptionHistory::with('customer.user', 'reward')->latest('redeemed_at');

        if ($this->filter_status !== '') {
            $query->where('status', $this->filter_status);
        }

        if (strlen($this->search) >= 2) {
            $query->whereHas('customer.user', function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        return view('livewire.billing.redemption-list', [
            'redemptions' => $query->paginate(20),
        ])->layout('components.layouts.app');
    }
}
