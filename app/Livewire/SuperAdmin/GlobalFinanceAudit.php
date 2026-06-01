<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\Tenant;
use Livewire\WithPagination;
use App\Traits\WithSorting;

class GlobalFinanceAudit extends Component
{
    use WithPagination, WithSorting;

    public $search = '';
    public $filter_tenant = '';
    public $filter_status = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Invoice::withoutGlobalScope('tenant')->with(['tenant', 'customer.user']);

        if ($this->search) {
            $query->where('number', 'like', '%' . $this->search . '%');
        }

        if ($this->filter_tenant) {
            $query->where('tenant_id', $this->filter_tenant);
        }

        if ($this->filter_status) {
            $query->where('status', $this->filter_status);
        }

        return view('livewire.super-admin.global-finance-audit', [
            'invoices' => $this->applySorting($query)->paginate(15),
            'tenants' => Tenant::all()
        ])->layout('components.super-admin-layout');
    }
}
