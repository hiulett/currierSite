<?php

namespace App\Livewire\Logistics;

use Livewire\Component;
use App\Models\Customer;
use App\Models\User;
use App\Models\Locker;
use Livewire\WithPagination;
use App\Traits\WithSorting;
use Illuminate\Support\Facades\Hash;

class CustomerList extends Component
{
    use WithPagination, WithSorting;

    public $search = '';
    public $filter = '';
    public $name, $email, $phone, $box_number, $locker_id, $identification_number;

    protected $queryString = [
        'search' => ['except' => ''],
        'filter' => ['except' => ''],
    ];

    // Password Management
    public $selected_customer_id;
    public $new_password;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openPasswordModal($customerId)
    {
        $this->selected_customer_id = $customerId;
        $this->new_password = '';
        $this->dispatch('open-password-modal');
    }

    public function resetPassword()
    {
        $this->validate([
            'new_password' => 'required|string|min:8',
        ]);

        $customer = Customer::find($this->selected_customer_id);
        if ($customer && $customer->user) {
            $customer->user->update([
                'password' => Hash::make($this->new_password)
            ]);

            session()->flash('message', 'Contraseña actualizada para el cliente: ' . $customer->user->name);
        }

        $this->dispatch('close-password-modal');
        $this->reset(['selected_customer_id', 'new_password']);
    }

    public function createCustomer()
    {
        $this->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'box_number' => 'required|unique:customers,box_number',
            'locker_id' => 'nullable|exists:lockers,id',
            'identification_number' => 'nullable|string',
        ]);

        $user = User::create([
            'tenant_id' => session('tenant_id'),
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make('password123'),
            'role' => 'customer'
        ]);

        $customer = Customer::create([
            'tenant_id' => session('tenant_id'),
            'user_id' => $user->id,
            'box_number' => $this->box_number,
            'phone' => $this->phone,
            'locker_id' => $this->locker_id,
            'identification_number' => $this->identification_number,
        ]);

        if ($this->locker_id) {
            Locker::where('id', $this->locker_id)->update(['status' => 'occupied']);
        }

        $this->reset(['name', 'email', 'phone', 'box_number', 'locker_id', 'identification_number']);
        $this->dispatch('customer-saved');
        session()->flash('message', 'Cliente registrado y casillero asignado.');
    }

    public function render()
    {
        $query = Customer::with(['user', 'locker']);

        if ($this->filter === 'new') {
            $query->where('customers.created_at', '>=', now()->subHours(48));
        }

        if (!empty(trim($this->search))) {
            $searchTerm = '%' . str_replace(' ', '%', trim($this->search)) . '%';

            $query->where(function($q) use ($searchTerm) {
                $q->where('box_number', 'like', $searchTerm)
                  ->orWhere('identification_number', 'like', $searchTerm)
                  ->orWhere('phone', 'like', $searchTerm)
                  ->orWhereHas('user', function($u) use ($searchTerm) {
                      $u->where(function($sub) use ($searchTerm) {
                          $sub->where('name', 'like', $searchTerm)
                              ->orWhere('email', 'like', $searchTerm);
                      });
                  });
            });
        }

        $customers = $this->applySorting($query)->paginate(10);

        $availableLockers = Locker::where('status', 'available')->get();

        $stats = [
            'total_customers' => Customer::count(),
            'total_balance' => Customer::sum('balance'),
            'new_this_month' => Customer::whereMonth('created_at', now()->month)->count(),
            'active_lockers' => Customer::whereNotNull('locker_id')->count(),
        ];

        return view('livewire.logistics.customer-list', [
            'customers' => $customers,
            'availableLockers' => $availableLockers,
            'stats' => $stats
        ])->layout('components.layouts.app');
    }
}
