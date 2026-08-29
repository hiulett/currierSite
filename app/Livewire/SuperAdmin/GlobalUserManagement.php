<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Tenant;
use App\Models\User;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;

class GlobalUserManagement extends Component
{
    use WithPagination, WithSorting;

    public $search = '';

    public $filter_tenant = '';

    public $filter_role = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::withoutGlobalScope('tenant')->with('tenant');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->filter_tenant) {
            $query->where('tenant_id', $this->filter_tenant);
        }

        if ($this->filter_role) {
            $query->where('role', $this->filter_role);
        }

        return view('livewire.super-admin.global-user-management', [
            'users' => $this->applySorting($query)->paginate(15),
            'tenants' => Tenant::all(),
        ])->layout('components.super-admin-layout');
    }
}
