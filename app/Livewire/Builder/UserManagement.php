<?php

namespace App\Livewire\Builder;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use Livewire\WithPagination;
use App\Traits\WithSorting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserManagement extends Component
{
    use WithPagination, WithSorting;

    public $name, $email, $password, $role_id, $selected_user_id;
    public $must_change_password = false;
    public $is_active = true;
    
    // UI Helpers
    public $auto_generate_password = true;
    public $send_credentials_email = true;
    public $confirming_delete_user_id = null;
    
    public $search = '';
    public $is_editing = false;

    public function resetFields()
    {
        $this->reset([
            'name', 'email', 'password', 'role_id', 'selected_user_id', 
            'is_editing', 'must_change_password', 'is_active', 
            'auto_generate_password', 'send_credentials_email', 'confirming_delete_user_id'
        ]);
        $this->resetErrorBag();
    }

    public function createUser()
    {
        $this->resetFields();
        $this->auto_generate_password = true;
        $this->send_credentials_email = true;
        $this->must_change_password = true;
        $this->is_active = true;
        $this->dispatch('open-user-modal');
    }

    public function editUser(User $user)
    {
        $this->resetFields();
        $this->selected_user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->must_change_password = (bool)$user->must_change_password;
        $this->is_active = (bool)$user->is_active;
        
        $this->auto_generate_password = false;
        $this->send_credentials_email = false;
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
            if (!$this->auto_generate_password) {
                $rules['password'] = 'required|min:8';
            }
        } else {
            if ($this->password) {
                $rules['password'] = 'min:8';
            }
        }

        $this->validate($rules);

        $plainPassword = null;

        if ($this->is_editing) {
            $user = User::findOrFail($this->selected_user_id);
            
            // Check that we aren't deactivating ourselves
            if (!$this->is_active && auth()->id() === $user->id) {
                $this->is_active = true;
                session()->flash('error', 'No puedes desactivar tu propio usuario.');
                return;
            }

            $updateData = [
                'name' => $this->name,
                'email' => $this->email,
                'role_id' => $this->role_id,
                'is_active' => $this->is_active,
                'must_change_password' => $this->must_change_password,
            ];
            
            if ($this->password) {
                $updateData['password'] = Hash::make($this->password);
                $plainPassword = $this->password;
            }
            
            $user->update($updateData);

            if ($this->password && $this->send_credentials_email) {
                $tenant = session('tenant_id') ? \App\Models\Tenant::find(session('tenant_id')) : null;
                $user->notify(new \App\Notifications\TemporaryPasswordNotification($plainPassword, $user->name, $tenant));
                session()->flash('message', 'Usuario actualizado y nueva contraseña enviada por correo.');
            } else {
                session()->flash('message', 'Usuario actualizado.');
            }
        } else {
            if ($this->auto_generate_password) {
                $plainPassword = Str::random(10);
                $hashedPassword = Hash::make($plainPassword);
            } else {
                $plainPassword = $this->password;
                $hashedPassword = Hash::make($this->password);
            }

            $user = User::create([
                'tenant_id' => session('tenant_id'),
                'name' => $this->name,
                'email' => $this->email,
                'password' => $hashedPassword,
                'role_id' => $this->role_id,
                'role' => 'admin', // Internal system identifier
                'is_active' => $this->is_active,
                'must_change_password' => $this->must_change_password,
            ]);

            if ($this->send_credentials_email) {
                $tenant = session('tenant_id') ? \App\Models\Tenant::find(session('tenant_id')) : null;
                $user->notify(new \App\Notifications\TemporaryPasswordNotification($plainPassword, $user->name, $tenant));
                session()->flash('message', 'Usuario creado y credenciales enviadas por correo.');
            } else {
                session()->flash('message', 'Usuario creado.');
            }
        }

        $this->resetFields();
        $this->dispatch('close-user-modal');
    }

    public function toggleActive(User $user)
    {
        if (auth()->id() === $user->id) {
            session()->flash('error', 'No puedes desactivar tu propio usuario.');
            return;
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        session()->flash('message', 'Estado del colaborador ' . $user->name . ' actualizado correctamente.');
    }

    public function resetAndSendPassword(User $user)
    {
        $newPassword = Str::random(10);
        
        $user->update([
            'password' => Hash::make($newPassword),
            'must_change_password' => true
        ]);

        $tenant = session('tenant_id') ? \App\Models\Tenant::find(session('tenant_id')) : null;
        $user->notify(new \App\Notifications\TemporaryPasswordNotification($newPassword, $user->name, $tenant));

        session()->flash('message', 'Contraseña restablecida y enviada a: ' . $user->email);
    }

    public function confirmDeleteUser($userId)
    {
        if (auth()->id() === (int)$userId) {
            session()->flash('error', 'No puedes eliminar tu propio usuario.');
            return;
        }

        $this->confirming_delete_user_id = $userId;
        $this->dispatch('open-delete-modal');
    }

    public function deleteUser()
    {
        if (!$this->confirming_delete_user_id) return;

        if (auth()->id() === (int)$this->confirming_delete_user_id) {
            session()->flash('error', 'No puedes eliminar tu propio usuario.');
            $this->confirming_delete_user_id = null;
            $this->dispatch('close-delete-modal');
            return;
        }

        $user = User::findOrFail($this->confirming_delete_user_id);
        $user->delete();

        session()->flash('message', 'Usuario eliminado correctamente.');
        $this->confirming_delete_user_id = null;
        $this->dispatch('close-delete-modal');
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
