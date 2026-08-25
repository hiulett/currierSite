<?php

namespace App\Livewire\Logistics;

use App\Jobs\SendInvoiceWhatsApp;
use App\Models\Customer;
use App\Models\Locker;
use App\Models\LoyaltyLevel;
use App\Models\Tenant;
use App\Models\Ticket;
use App\Notifications\TemporaryPasswordNotification;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class CustomerDetail extends Component
{
    public Customer $customer;

    public $tab = 'packages';

    // Edit form
    public $name;

    public $email;

    public $phone;

    public $identification_number;

    public $address;

    public $admin_notes;

    public $box_number;

    public $box_number_air;

    public $box_number_maritime;

    public $locker_id;

    public $loyalty_level_id;

    // Credentials (shared partial customer-credential-modals)
    public $selected_customer_id = null;

    public $new_password = '';

    public function mount(Customer $customer)
    {
        $this->customer = $customer->load(['user', 'locker', 'level']);
    }

    public function setTab($tab)
    {
        $this->tab = in_array($tab, ['packages', 'invoices', 'tickets', 'activity', 'details']) ? $tab : 'packages';
    }

    // ---- Edit ----

    public function openEditModal()
    {
        $c = $this->customer;
        $this->name = $c->user->name;
        $this->email = $c->user->email;
        $this->phone = $c->phone;
        $this->identification_number = $c->identification_number;
        $this->address = $c->address;
        $this->admin_notes = $c->admin_notes;
        $this->box_number = $c->box_number;
        $this->box_number_air = $c->box_number_air;
        $this->box_number_maritime = $c->box_number_maritime;
        $this->locker_id = $c->locker_id;
        $this->loyalty_level_id = $c->loyalty_level_id;

        $this->dispatch('open-customer-modal');
    }

    public function saveCustomer()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'email',
                'unique:users,email,'.$this->customer->user_id,
            ],
            'locker_id' => 'nullable|exists:lockers,id',
            'loyalty_level_id' => 'nullable|exists:loyalty_levels,id',
            'phone' => 'required|string|max:20',
            'identification_number' => 'required|string|max:50',
            'address' => 'nullable|string|max:500',
            'box_number' => 'required',
        ]);

        $customer = $this->customer;
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

        if ($this->locker_id) {
            Locker::where('id', $this->locker_id)->update(['status' => 'occupied']);
        }

        $this->customer->refresh();
        $this->dispatch('customer-saved');
        session()->flash('message', 'Cliente actualizado exitosamente.');
    }

    // ---- Credentials (mirrors CustomerList) ----

    public function sendWhatsApp($invoiceId)
    {
        $invoice = $this->customer->invoices()->find($invoiceId);
        if (! $invoice) {
            session()->flash('error', 'Factura no encontrada.');

            return;
        }

        $tenant = $invoice->tenant;
        if (! $tenant || ! app(WhatsAppService::class)->isConfigured($tenant)) {
            session()->flash('error', 'WhatsApp no está configurado. Configúralo en Configuración → Pagos e Integraciones.');

            return;
        }

        if (! $this->customer->phone) {
            session()->flash('error', 'El cliente no tiene un teléfono registrado.');

            return;
        }

        SendInvoiceWhatsApp::dispatch($invoice);
        session()->flash('message', 'Envío de la factura #'.$invoice->number.' por WhatsApp encolado.');
    }

    public function openPasswordModal()
    {
        $this->selected_customer_id = $this->customer->id;
        $this->new_password = Str::random(8);
        $this->dispatch('open-password-modal');
    }

    public function generateRandomPassword()
    {
        $this->new_password = Str::random(8);
    }

    public function resetPassword()
    {
        $this->validate([
            'new_password' => 'required|string|min:6',
        ]);

        $customer = $this->customer;
        if ($customer && $customer->user) {
            $tenant = Tenant::find(session('tenant_id'));
            $mustChange = $tenant->settings_json['force_password_change'] ?? false;

            $customer->user->update([
                'password' => Hash::make($this->new_password),
                'must_change_password' => $mustChange,
            ]);

            $customer->update([
                'temporary_password' => $this->new_password,
            ]);

            try {
                $customer->user->notify(new TemporaryPasswordNotification($this->new_password, $customer->user->name, $tenant));
                $customer->update(['password_sent_at' => now()]);
                session()->flash('message', 'Contraseña actualizada y enviada por correo a: '.$customer->user->name);
            } catch (\Exception $e) {
                Log::error('Error enviando correo de reset de contraseña: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
                session()->flash('error', 'Contraseña actualizada, pero NO se pudo enviar el correo. Error interno: '.$e->getMessage());
            }
        }

        $this->dispatch('close-password-modal');
        $this->reset(['selected_customer_id', 'new_password']);
    }

    public function confirmSendPassword()
    {
        $this->selected_customer_id = $this->customer->id;
        $this->dispatch('open-confirm-password-modal');
    }

    public function sendPasswordEmail()
    {
        $customer = $this->customer;

        try {
            if (! $customer->temporary_password) {
                $newPass = Str::random(8);
                $tenant = Tenant::find(session('tenant_id'));
                $mustChange = $tenant->settings_json['force_password_change'] ?? false;

                $customer->update(['temporary_password' => $newPass]);
                if ($customer->user) {
                    $customer->user->update([
                        'password' => Hash::make($newPass),
                        'must_change_password' => $mustChange,
                    ]);
                }
            }

            if ($customer->user) {
                $notifTenant = Tenant::find(session('tenant_id'));
                $customer->user->notify(new TemporaryPasswordNotification($customer->temporary_password, $customer->user->name, $notifTenant));
                $customer->update(['password_sent_at' => now()]);
                session()->flash('message', 'Credenciales enviadas correctamente a: '.$customer->user->email);
            } else {
                session()->flash('error', 'El cliente no tiene un usuario asociado.');
            }
        } catch (\Exception $e) {
            Log::error('Error enviando correo de contraseña: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            session()->flash('error', 'No se pudo enviar el correo. Error interno: '.$e->getMessage());
        }

        $this->dispatch('close-confirm-password-modal');
    }

    // ---- Data ----

    public function render()
    {
        $customer = $this->customer;

        $packages = $customer->packages()->with(['warehouse'])->latest()->take(100)->get();
        $invoices = $customer->invoices()->latest()->take(100)->get();
        $tickets = Ticket::where('customer_id', $customer->id)->with('messages')->latest()->take(50)->get();

        // Activity timeline
        $activity = collect();
        foreach ($customer->packages()->latest()->take(20)->get() as $pkg) {
            $activity->push([
                'at' => $pkg->created_at,
                'icon' => 'package',
                'color' => 'primary',
                'text' => 'Pre-alerta / recepción del paquete '.$pkg->tracking_number,
            ]);
            foreach ($pkg->trackingEvents()->latest()->take(5)->get() as $event) {
                $activity->push([
                    'at' => $event->created_at,
                    'icon' => 'map-pin',
                    'color' => 'info',
                    'text' => $pkg->tracking_number.': '.$event->status.($event->location ? ' ('.$event->location.')' : ''),
                ]);
            }
        }
        foreach ($invoices->where('status', 'paid') as $invoice) {
            $activity->push([
                'at' => $invoice->paid_at ?? $invoice->updated_at,
                'icon' => 'dollar-sign',
                'color' => 'success',
                'text' => 'Pago de factura '.$invoice->number.' por '.$invoice->total,
            ]);
        }
        if ($customer->password_sent_at) {
            $activity->push([
                'at' => $customer->password_sent_at,
                'icon' => 'mail',
                'color' => 'warning',
                'text' => 'Credenciales enviadas al cliente',
            ]);
        }
        $activity->push([
            'at' => $customer->created_at,
            'icon' => 'user-plus',
            'color' => 'primary',
            'text' => 'Cliente creado',
        ]);
        $activity = $activity->sortByDesc('at')->values();

        $stats = [
            'total_packages' => $customer->packages()->count(),
            'active_packages' => $customer->packages()->whereNotIn('status', ['delivered', 'cancelled'])->count(),
            'invoices_count' => $customer->invoices()->count(),
            'total_billed' => (float) $customer->invoices()->sum('total'),
            'balance' => (float) $customer->balance,
            'points' => (int) $customer->points,
            'open_tickets' => Ticket::where('customer_id', $customer->id)->where('status', '!=', 'closed')->count(),
        ];

        $tenant = Tenant::find(session('tenant_id')) ?? Tenant::first();
        $settings = $tenant->settings_json ?? [];
        $airEnabled = $settings['service_air_enabled'] ?? true;
        $maritimeEnabled = $settings['service_maritime_enabled'] ?? true;

        return view('livewire.logistics.customer-detail', [
            'packages' => $packages,
            'invoices' => $invoices,
            'tickets' => $tickets,
            'activity' => $activity,
            'stats' => $stats,
            'availableLockers' => Locker::where('status', 'available')->get(),
            'loyaltyLevels' => LoyaltyLevel::all(),
            'airEnabled' => $airEnabled,
            'maritimeEnabled' => $maritimeEnabled,
            'currency' => $settings['currency'] ?? 'USD',
        ])->layout('components.layouts.app');
    }
}
