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
    use WithPagination, WithSorting, \Livewire\WithFileUploads;

    public $search = '';
    public $filter = '';
    public $filter_level = '';
    public $name, $email, $phone, $box_number, $locker_id, $identification_number, $address, $loyalty_level_id, $admin_notes;
    public $box_number_air, $box_number_maritime;

    public $is_editing = false;
    public $customer_id;

    // CSV Import
    public $csv_file;
    public $is_importing = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'filter' => ['except' => ''],
        'filter_level' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
        // If searching, we usually want to search among all customers, not just the filtered ones
        if ($this->search !== '') {
            $this->filter = '';
            $this->filter_level = '';
        }
    }

    public function openPasswordModal($customerId)
    {
        $this->selected_customer_id = $customerId;
        $this->new_password = \Illuminate\Support\Str::random(8); // Generar una por defecto
        $this->dispatch('open-password-modal');
    }

    public function generateRandomPassword()
    {
        $this->new_password = \Illuminate\Support\Str::random(8);
    }

    public function resetPassword()
    {
        $this->validate([
            'new_password' => 'required|string|min:6',
        ]);

        $customer = Customer::find($this->selected_customer_id);
        if ($customer && $customer->user) {
            $customer->user->update([
                'password' => Hash::make($this->new_password)
            ]);

            // Guardar en texto plano para el admin (bajo responsabilidad del admin)
            $customer->update([
                'temporary_password' => $this->new_password
            ]);

            // Enviar notificación al correo
            $customer->user->notify(new \App\Notifications\TemporaryPasswordNotification($this->new_password, $customer->user->name));

            session()->flash('message', 'Contraseña actualizada y enviada por correo a: ' . $customer->user->name);
        }

        $this->dispatch('close-password-modal');
        $this->reset(['selected_customer_id', 'new_password']);
    }

    public function sendBulkPasswords()
    {
        $customers = Customer::where('tenant_id', session('tenant_id'))->get();
        $count = 0;

        foreach ($customers as $customer) {
            if (!$customer->user) continue;

            // Generar clave aleatoria de 8 caracteres (alfanumérico)
            $plainPassword = Str::random(8);

            // Actualizar cuenta de usuario
            $customer->user->update([
                'password' => Hash::make($plainPassword)
            ]);

            // Guardar para vista del administrador
            $customer->update([
                'temporary_password' => $plainPassword
            ]);

            // Enviar notificación
            $customer->user->notify(new \App\Notifications\TemporaryPasswordNotification($plainPassword, $customer->user->name));

            $count++;
        }

        session()->flash('message', "Operación masiva completada. Se enviaron $count correos con nuevas contraseñas.");
    }

    public function sendPasswordEmail($customerId)
    {
        $customer = Customer::findOrFail($customerId);

        // If no temporary password exists, generate one now
        if (!$customer->temporary_password) {
            $newPass = \Illuminate\Support\Str::random(8);
            $customer->update(['temporary_password' => $newPass]);
            if ($customer->user) {
                $customer->user->update(['password' => Hash::make($newPass)]);
            }
        }

        if ($customer->user) {
            try {
                $customer->user->notify(new \App\Notifications\TemporaryPasswordNotification($customer->temporary_password, $customer->user->name));
                session()->flash('message', 'Credenciales enviadas correctamente a: ' . $customer->user->email);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error enviando correo de contraseña: ' . $e->getMessage());
                session()->flash('message', 'La contraseña se actualizó en el sistema, pero no se pudo enviar el correo. Por favor, proporcione la clave manualmente: ' . $customer->temporary_password);
            }
        }
    }

    public function resetFields()
    {
        $this->reset(['name', 'email', 'phone', 'box_number', 'locker_id', 'loyalty_level_id', 'identification_number', 'address', 'admin_notes', 'box_number_air', 'box_number_maritime', 'is_editing', 'customer_id']);
    }

    public function openCreateModal()
    {
        $this->resetFields();
        $this->dispatch('open-customer-modal');
    }

    public function openEditModal(Customer $customer)
    {
        $this->resetFields();
        $this->customer_id = $customer->id;
        $this->name = $customer->user->name;
        $this->email = $customer->user->email;
        $this->phone = $customer->phone;
        $this->box_number = $customer->box_number;
        $this->box_number_air = $customer->box_number_air;
        $this->box_number_maritime = $customer->box_number_maritime;
        $this->locker_id = $customer->locker_id;
        $this->loyalty_level_id = $customer->loyalty_level_id;
        $this->identification_number = $customer->identification_number;
        $this->address = $customer->address;
        $this->admin_notes = $customer->admin_notes;
        $this->is_editing = true;

        $this->dispatch('open-customer-modal');
    }

    public function saveCustomer()
    {
        $targetUserId = $this->is_editing ? Customer::find($this->customer_id)->user_id : 'NULL';

        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'email',
                'unique:users,email,' . $targetUserId
            ],
            'box_number' => 'required|unique:customers,box_number,' . ($this->customer_id ?: 'NULL'),
            'locker_id' => 'nullable|exists:lockers,id',
            'loyalty_level_id' => 'nullable|exists:loyalty_levels,id',
            'phone' => 'nullable|string|max:20',
            'identification_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ];

        $this->validate($rules);

        if ($this->is_editing) {
            $customer = Customer::findOrFail($this->customer_id);
            $customer->user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            $customer->update([
                'box_number' => $this->box_number,
                'box_number_air' => $this->box_number_air,
                'box_number_maritime' => $this->box_number_maritime,
                'phone' => $this->phone,
                'locker_id' => $this->locker_id,
                'loyalty_level_id' => $this->loyalty_level_id,
                'identification_number' => $this->identification_number,
                'address' => $this->address,
                'admin_notes' => $this->admin_notes,
            ]);

            session()->flash('message', 'Cliente actualizado exitosamente.');
        } else {
            $user = User::create([
                'tenant_id' => session('tenant_id'),
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make('password123'),
                'role' => 'customer'
            ]);

            Customer::create([
                'tenant_id' => session('tenant_id'),
                'user_id' => $user->id,
                'box_number' => $this->box_number,
                'box_number_air' => $this->box_number_air,
                'box_number_maritime' => $this->box_number_maritime,
                'phone' => $this->phone,
                'locker_id' => $this->locker_id,
                'loyalty_level_id' => $this->loyalty_level_id,
                'identification_number' => $this->identification_number,
                'address' => $this->address,
                'admin_notes' => $this->admin_notes,
                'balance' => 0,
                'points' => 0,
            ]);

            session()->flash('message', 'Cliente registrado exitosamente.');
        }

        if ($this->locker_id) {
            Locker::where('id', $this->locker_id)->update(['status' => 'occupied']);
        }

        $this->resetFields();
        $this->dispatch('customer-saved');
    }

    public function deleteCustomer($id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->packages()->count() > 0) {
            session()->flash('error', 'No se puede eliminar el cliente porque tiene paquetes registrados.');
            return;
        }

        if ($customer->user) {
            $customer->user->delete();
        }

        $customer->delete();
        session()->flash('message', 'Cliente y su cuenta de usuario eliminados.');
    }

    public function importCSV()
    {
        $this->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $path = $this->csv_file->getRealPath();

        // Auto-detect delimiter
        $fileContent = file_get_contents($path);
        $delimiters = [",", ";", "\t"];
        $delimiter = ",";
        foreach ($delimiters as $d) {
            if (strpos($fileContent, $d) !== false) {
                $delimiter = $d;
                break;
            }
        }

        $file = fopen($path, 'r');
        ini_set('auto_detect_line_endings', true);

        // Skip header
        fgetcsv($file, 0, $delimiter);

        $count = 0;
        $skipped = 0;
        $tenantId = session('tenant_id') ?? 1;

        while (($row = fgetcsv($file, 0, $delimiter)) !== FALSE) {
            // Basic sanity check: row must have at least name and email
            if (count($row) < 2 || empty($row[1])) {
                $skipped++;
                continue;
            }

            $name = trim($row[0]);
            $email = trim($row[1]);
            $phone = isset($row[2]) ? trim($row[2]) : '';
            $id_num = isset($row[3]) ? trim($row[3]) : '';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped++;
                continue;
            }

            try {
                DB::transaction(function() use ($email, $tenantId, $name, $phone, $id_num, &$count) {
                    // 1. Create/Update User
                    $user = User::updateOrCreate(
                        ['email' => $email],
                        [
                            'tenant_id' => $tenantId,
                            'name' => $name,
                            'password' => Hash::make('password123'),
                            'role' => 'customer',
                            'email_verified_at' => now(),
                        ]
                    );

                    // 2. Determine Box Number
                    $customer = Customer::where('user_id', $user->id)->first();
                    if (!$customer) {
                        $tenant = \App\Models\Tenant::find($tenantId);
                        $settings = $tenant->settings_json;
                        $nextId = ($settings['box_number_counter'] ?? 1000) + 1;

                        $boxNumber = 'LGX' . $nextId;

                        Customer::create([
                            'tenant_id' => $tenantId,
                            'user_id' => $user->id,
                            'box_number' => $boxNumber,
                            'box_number_air' => $boxNumber,
                            'box_number_maritime' => $boxNumber . 'M',
                            'phone' => $phone,
                            'identification_number' => $id_num,
                            'balance' => 0,
                            'points' => 0,
                        ]);

                        // Update counter
                        $settings['box_number_counter'] = $nextId;
                        $tenant->update(['settings_json' => $settings]);
                    } else {
                        $customer->update([
                            'phone' => $phone,
                            'identification_number' => $id_num,
                        ]);
                    }
                    $count++;
                });
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error importing CSV row for $email: " . $e->getMessage());
                $skipped++;
            }
        }

        fclose($file);
        $this->reset(['csv_file', 'is_importing']);

        if ($count > 0) {
            session()->flash('message', "Importación finalizada: $count clientes procesados." . ($skipped > 0 ? " ($skipped filas omitidas por formato incorrecto)" : ""));
        } else {
            session()->flash('error', "No se pudo cargar ningún cliente. Verifique que el archivo use comas (,) o puntos y comas (;) y que la segunda columna sea un email válido.");
        }
    }

    public function render()
    {
        $query = Customer::with(['user', 'locker', 'level'])
            ->withCount(['packages', 'invoices'])
            ->withSum('invoices', 'total');

        if ($this->filter === 'new') {
            $query->where('customers.created_at', '>=', now()->subHours(48));
        } elseif ($this->filter === 'unverified') {
            $query->whereHas('user', function($q) {
                $q->whereNull('email_verified_at');
            });
        } elseif ($this->filter === 'inactive') {
            $query->whereDoesntHave('packages')
                  ->where('customers.created_at', '<=', now()->subDays(7));
        }

        if ($this->filter_level) {
            $query->where('loyalty_level_id', $this->filter_level);
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
            'unverified_emails' => User::where('role', 'customer')->whereNull('email_verified_at')->count(),
            'inactive_users' => Customer::whereDoesntHave('packages')->where('created_at', '<=', now()->subDays(7))->count(),
            'active_lockers' => Customer::whereNotNull('locker_id')->count(),
        ];

        $tenant = \App\Models\Tenant::find(session('tenant_id')) ?? \App\Models\Tenant::first();
        $settings = $tenant->settings_json ?? [];
        $airEnabled = $settings['service_air_enabled'] ?? true;
        $maritimeEnabled = $settings['service_maritime_enabled'] ?? true;

        return view('livewire.logistics.customer-list', [
            'customers' => $customers,
            'availableLockers' => $availableLockers,
            'loyaltyLevels' => \App\Models\LoyaltyLevel::all(),
            'stats' => $stats,
            'airEnabled' => $airEnabled,
            'maritimeEnabled' => $maritimeEnabled,
        ])->layout('components.layouts.app');
    }
}
