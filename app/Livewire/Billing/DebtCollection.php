<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Invoice;
use Livewire\WithPagination;

class DebtCollection extends Component
{
    use WithPagination;

    public $filter_days = 0;

    public function render()
    {
        $debtors = Customer::with('user')
            ->where('balance', '>', 0)
            ->whereHas('user', function($q) {
                $q->where('tenant_id', session('tenant_id'));
            })
            ->latest()
            ->paginate(10);

        $total_debt = Invoice::where('status', 'unpaid')->sum('total');
        $overdue_count = Invoice::where('status', 'unpaid')->where('due_date', '<', now())->count();

        return view('livewire.billing.debt-collection', [
            'debtors' => $debtors,
            'total_debt' => $total_debt,
            'overdue_count' => $overdue_count
        ])->layout('components.layouts.app');
    }
}
