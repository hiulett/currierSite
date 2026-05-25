<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Package;
use App\Models\Tenant;
use Livewire\WithPagination;
use App\Traits\WithSorting;

class GlobalInventory extends Component
{
    use WithPagination, WithSorting;

    public $search = '';
    public $filter_tenant = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Package::withoutGlobalScope('tenant')
            ->with(['tenant', 'customer.user', 'warehouse']);

        if ($this->search) {
            $searchTerm = '%' . str_replace(' ', '%', trim($this->search)) . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('tracking_number', 'like', $searchTerm)
                  ->orWhereHas('customer', function($c) use ($searchTerm) {
                      $c->where('box_number', 'like', $searchTerm)
                        ->orWhereHas('user', function($u) use ($searchTerm) {
                            $u->where('name', 'like', $searchTerm);
                        });
                  });
            });
        }

        if ($this->filter_tenant) {
            $query->where('tenant_id', $this->filter_tenant);
        }

        return view('livewire.super-admin.global-inventory', [
            'packages' => $this->applySorting($query)->paginate(15),
            'tenants' => Tenant::all()
        ])->layout('components.super-admin-layout');
    }
}
