<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileSettings extends Component
{
    public $name;
    public $email;
    public $phone;
    public $identification_number;
    public $address;
    public $latitude;
    public $longitude;

    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $user = auth()->user();
        $customer = $user->customer;

        $this->name = $user->name;
        $this->email = $user->email;

        if ($customer) {
            $this->phone = $customer->phone;
            $this->identification_number = $customer->identification_number;
            $this->address = $customer->address;
            $this->latitude = $customer->latitude;
            $this->longitude = $customer->longitude;
        }
    }

    public function updateProfile()
    {
        $user = auth()->user();

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)->where(function ($query) use ($user) {
                    return $query->where('tenant_id', $user->tenant_id);
                }),
            ],
            'phone' => 'nullable|string|max:20',
            'identification_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $customer = $user->customer;
        if ($customer) {
            $customer->update([
                'phone' => $this->phone,
                'identification_number' => $this->identification_number,
                'address' => $this->address,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ]);
        }

        session()->flash('profile_message', 'Perfil actualizado exitosamente.');
    }

    public function updatePassword()
    {
        $user = auth()->user();

        $this->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($this->new_password),
            'must_change_password' => false
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('password_message', 'Contraseña actualizada correctamente.');

        if (!$user->must_change_password) {
            return redirect()->route('customer.dashboard');
        }
    }

    public function render()
    {
        return view('livewire.customer.profile-settings')->layout('components.customer-layout');
    }
}
