<?php

namespace App\Livewire\Builder;

use Livewire\Component;
use App\Models\Role;
use App\Models\Permission;
use Livewire\WithPagination;
use App\Traits\WithSorting;

class RoleManagement extends Component
{
    use WithPagination, WithSorting;

    public $name, $description, $selected_role_id;
    public $selected_permissions = [];
    public $is_editing = false;

    public function resetFields()
    {
        $this->reset(['name', 'description', 'selected_role_id', 'selected_permissions', 'is_editing']);
    }

    public function createRole()
    {
        $this->resetFields();
        $this->dispatch('open-role-modal');
    }

    public function editRole(Role $role)
    {
        $this->selected_role_id = $role->id;
        $this->name = $role->name;
        $this->description = $role->description;
        $this->selected_permissions = $role->permissions->pluck('id')->toArray();
        $this->is_editing = true;
        $this->dispatch('open-role-modal');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        if ($this->is_editing) {
            $role = Role::find($this->selected_role_id);
            $role->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            $role->permissions()->sync($this->selected_permissions);
            session()->flash('message', 'Rol actualizado con éxito.');
        } else {
            $role = Role::create([
                'tenant_id' => session('tenant_id'),
                'name' => $this->name,
                'description' => $this->description,
            ]);
            $role->permissions()->sync($this->selected_permissions);
            session()->flash('message', 'Rol creado con éxito.');
        }

        $this->resetFields();
        $this->dispatch('close-role-modal');
    }

    public function deleteRole($id)
    {
        $role = Role::find($id);
        if ($role && !$role->is_system) {
            $role->delete();
            session()->flash('message', 'Rol eliminado.');
        }
    }

    public function render()
    {
        return view('livewire.builder.role-management', [
            'roles' => $this->applySorting(Role::query())->paginate(10),
            'all_permissions' => Permission::all()->groupBy('group')
        ])->layout('components.layouts.app');
    }
}
