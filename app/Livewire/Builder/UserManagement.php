<?php

namespace App\Livewire\Builder;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use Livewire\WithPagination;
use App\Traits\WithSorting;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    use WithPagination, WithSorting;

    public $name, $email, $password, $role_id, $selected_user_id;
    public $search = '';
    public $is_editing = false;

    public function resetFields()
    {
        $this->reset(['name', 'email', 'password', 'role_id', 'selected_user_id', 'is_editing']);
    }

    public function createUser()
    {
        $this->resetFields();
        $this->dispatch('open-user-modal');
    }

    public function editUser(User $user)
    {
        $this->selected_user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->is_editing = true;
        $this->dispatch('open-user-modal');
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'email',
                Rule::unique('users')->ignore($this->selected_user_id)->where('tenant_id', session('tenant_id'))
            ],
            'role_id' => 'required|exists:roles,id',
        ];

        if (!$this->is_editing) {
            $rules['password'] = 'required|min:8';
        }

        $this->validate($rules);

        if ($this->is_editing) {
            $user = User::find($this->selected_user_id);
            $updateData = [
                'name' => $this->name,
                'email' => $this->email,
                'role_id' => $this->role_id,
            ];
            if ($this->password) {
                $updateData['password'] = Hash::make($this->password);
            }
            $user->update($updateData);
            session()->flash('message', 'Usuario actualizado.');
        } else {
            User::create([
                'tenant_id' => session('tenant_id'),
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role_id' => $this->role_id,
                'role' => 'admin', // Internal system identifier
            ]);
            session()->flash('message', 'Usuario creado.');
        }

        $this->resetFields();
        $this->dispatch('close-user-modal');
    }

    public function render()
    {
        $query = User::where('role', '!=', 'customer')
            ->where('name', 'like', '%' . $this->search . '%');

        return view('livewire.builder.user-management', [
            'users' => $this->applySorting($query)->paginate(10),
            'roles' => Role::all()
        ])->layout('components.layouts.app');
    }
}
