<?php

namespace App\Livewire\Logistics;

use Livewire\Component;
use App\Models\Package;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Livewire\WithPagination;
use App\Traits\WithSorting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InventoryList extends Component
{
    use WithPagination, WithSorting;

    public $search = '';
    public $filter_warehouse = '';
    public $filter_status = '';
    public $filter_date = '';
    public $filter_delivery_type = '';
    public $view_tab = 'all'; // all, pending, recent

    protected $queryString = [
        'search' => ['except' => ''],
        'filter_warehouse' => ['except' => ''],
        'filter_status' => ['except' => ''],
        'filter_date' => ['except' => ''],
        'filter_delivery_type' => ['except' => ''],
        'view_tab' => ['except' => 'all'],
    ];

    // Bulk Selection
    public $selected_packages = [];
    public $selectAll = false;

    // Assignment Panel State
    public $is_assigning = false;
    public $target_customer_id;
    public $customer_search = '';
    public $customer_results = [];
    public $selected_customer = null;

    // Assignment Customizations
    public $custom_rate;
    public $extra_charge = 0;
    public $extra_charge_reason = '';
    public $shelf_location;

    // Temporary storage for notifications
    protected $invoice_to_notify;
    protected $packages_to_notify;

    // Edit Package Properties (Standard Modal)
    public $editing_package_id = null;
    public $edit_tracking_number;
    public $edit_description;
    public $edit_weight;
    public $edit_status;
    public $edit_warehouse_id;

    protected $listeners = ['package-saved' => '$refresh'];

    public function mount()
    {
        // RESET ALL FILTERS ON MOUNT TO ENSURE VISIBILITY
        $this->search = '';
        $this->filter_warehouse = '';
        $this->filter_status = '';
        $this->view_tab = 'all';
        $this->resetPage();

        $tenant = \App\Models\Tenant::find(session('tenant_id')) ?? \App\Models\Tenant::first();
        $this->custom_rate = $tenant->settings_json['default_rate'] ?? 2.50;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected_packages = $this->getPackagesQuery()->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selected_packages = [];
        }
    }

    public function updatedCustomerSearch($value)
    {
        if (strlen($value) < 2) {
            $this->customer_results = [];
            return;
        }

        $this->customer_results = Customer::with('user')
            ->where('box_number', 'like', '%' . $value . '%')
            ->orWhereHas('user', function($q) use ($value) {
                $q->where('name', 'like', '%' . $value . '%');
            })->take(5)->get();
    }

    public function selectCustomer($id)
    {
        $this->selected_customer = Customer::with('user')->find($id);
        $this->target_customer_id = $id;
        $this->customer_search = $this->selected_customer->user->name . ' (' . $this->selected_customer->box_number . ')';
        $this->customer_results = [];
    }

    public function editPackage($id)
    {
        // Si el paquete no tiene cliente, abrimos el panel de asignación para este paquete individual
        $package = Package::find($id);
        if (!$package->customer_id) {
            $this->selected_packages = [(string)$id];
            $this->openAssignment();
        } else {
            // Lógica para edición normal (opcional, si quieres mantener el modal de edición de datos básicos)
            $this->editing_package_id = $id;
            $this->edit_tracking_number = $package->tracking_number;
            $this->edit_description = $package->description;
            $this->edit_weight = $package->weight;
            $this->edit_status = $package->status;
            $this->edit_warehouse_id = $package->warehouse_id;
            $this->dispatch('open-edit-modal');
        }
    }

    public function updatePackage()
    {
        $this->validate([
            'edit_tracking_number' => 'required|string',
            'edit_weight' => 'required|numeric|min:0',
            'edit_status' => 'required|string',
            'edit_warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $package = Package::findOrFail($this->editing_package_id);
        $package->update([
            'tracking_number' => $this->edit_tracking_number,
            'description' => $this->edit_description,
            'weight' => $this->edit_weight,
            'status' => $this->edit_status,
            'warehouse_id' => $this->edit_warehouse_id,
        ]);

        session()->flash('message', 'Paquete actualizado correctamente.');
        $this->editing_package_id = null;
        $this->dispatch('close-edit-modal');
    }

    public function openAssignment()
    {
        if (empty($this->selected_packages)) {
            session()->flash('error', 'Seleccione al menos un paquete.');
            return;
        }
        $this->is_assigning = true;
    }

    public function unassignPackage($id)
    {
        $package = Package::findOrFail($id);
        $customer = $package->customer;

        if ($customer) {
            // Decrement points
            $customer->decrement('points', ceil($package->weight));

            // We don't touch the balance automatically because the invoice might have other items.
            // We just warn the user or let them handle the invoice manually.
        }

        $package->update([
            'customer_id' => null,
            'status' => 'received', // Reset to received status
            'shelf_location' => null
        ]);

        session()->flash('message', 'Paquete desasociado del cliente. Recuerde anular o editar la factura manualmente si es necesario.');
    }

    public function bulkUnassign()
    {
        if (empty($this->selected_packages)) return;

        $packages = Package::whereIn('id', $this->selected_packages)->get();

        foreach ($packages as $pkg) {
            if ($pkg->customer) {
                $pkg->customer->decrement('points', ceil($pkg->weight));
            }
            $pkg->update([
                'customer_id' => null,
                'status' => 'received',
                'shelf_location' => null
            ]);
        }

        $this->selected_packages = [];
        $this->selectAll = false;
        session()->flash('message', count($packages) . ' paquetes desasociados masivamente.');
    }

    public function cancelAssignment()
    {
        $this->is_assigning = false;
        $this->reset(['target_customer_id', 'customer_search', 'selected_customer', 'extra_charge', 'extra_charge_reason', 'shelf_location']);
    }

    public function confirmAssignment()
    {
        if (!$this->target_customer_id) {
            session()->flash('assign_error', 'Debe seleccionar un cliente.');
            return;
        }

        $packages = Package::whereIn('id', $this->selected_packages)->get();

        DB::transaction(function() use ($packages) {
            $total_weight = $packages->sum('weight');
            $subtotal = ($total_weight * $this->custom_rate) + (float)$this->extra_charge;

            $tenant = \App\Models\Tenant::current();
            $tax_percent = $tenant->settings_json['default_tax'] ?? ($tenant->settings_json['tax_rate'] ?? 0);
            $tax = $subtotal * ($tax_percent / 100);
            $total = $subtotal + $tax;

            // 1. Create Consolidated Invoice
            $invoice = Invoice::create([
                'tenant_id' => session('tenant_id'),
                'customer_id' => $this->target_customer_id,
                'number' => 'INV-' . date('Ymd') . strtoupper(Str::random(4)),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'status' => 'unpaid',
                'due_date' => now()->addDays(7),
                'notes' => 'Factura generada por asignación masiva de inventario.',
            ]);

            // 2. Create Invoice Items
            foreach ($packages as $pkg) {
                InvoiceItem::create([
                    'tenant_id' => session('tenant_id'),
                    'invoice_id' => $invoice->id,
                    'description' => "Flete: " . $pkg->tracking_number . " (" . $pkg->weight . " lbs)",
                    'quantity' => $pkg->weight,
                    'unit_price' => $this->custom_rate,
                    'total' => $pkg->weight * $this->custom_rate,
                ]);

                // 3. Update Packages
                $pkg->update([
                    'customer_id' => $this->target_customer_id,
                    'shelf_location' => $this->shelf_location,
                    'status' => 'arrived', // Mark as arrived in destination
                ]);
            }

            if ($this->extra_charge > 0) {
                InvoiceItem::create([
                    'tenant_id' => session('tenant_id'),
                    'invoice_id' => $invoice->id,
                    'description' => $this->extra_charge_reason ?: 'Cargo adicional administrativo',
                    'quantity' => 1,
                    'unit_price' => $this->extra_charge,
                    'total' => $this->extra_charge,
                ]);
            }

            // Update customer balance
            $this->selected_customer->increment('balance', $total);
            $this->selected_customer->increment('points', ceil($total_weight));

            $this->invoice_to_notify = $invoice;
            $this->packages_to_notify = $packages;
        });

        // 4. Notify Customer (Outside transaction to prevent rollback on mail failure)
        if ($this->selected_customer->user) {
            try {
                $this->selected_customer->user->notify(new \App\Notifications\PackagesArrivedNotification($this->invoice_to_notify, $this->packages_to_notify));
                session()->flash('message', 'Asignación completada y factura generada con éxito. El cliente ha sido notificado vía correo.');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error enviando notificación de paquetes: ' . $e->getMessage());
                session()->flash('message', 'Asignación completada con éxito, pero no se pudo enviar el correo de notificación. Por favor, verifique la configuración de correo.');
            }
        } else {
            session()->flash('message', 'Asignación completada con éxito.');
        }

        $this->selected_packages = [];
        $this->selectAll = false;
        $this->cancelAssignment();
    }

    protected function getPackagesQuery()
    {
        // Multi-tenant security enforced by BelongsToTenant scope
        $query = Package::with(['customer.user', 'warehouse']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('tracking_number', 'like', '%' . trim($this->search) . '%')
                  ->orWhere('description', 'like', '%' . trim($this->search) . '%');
            });
        }

        // Functional filters (Non-date based)
        if ($this->view_tab === 'pending') {
            $query->whereNull('customer_id');
        } elseif ($this->view_tab === 'assigned') {
            $query->whereNotNull('customer_id');
        }

        // Property filters
        if (!empty($this->filter_warehouse)) {
            $query->where('warehouse_id', $this->filter_warehouse);
        }

        if (!empty($this->filter_status)) {
            $query->where('status', $this->filter_status);
        }

        if (!empty($this->filter_date)) {
            $query->whereDate('created_at', $this->filter_date);
        }

        return $query;
    }

    public function forceRepair()
    {
        try {
            // Respecting tenant isolation during repair
            $tenant = \App\Models\Tenant::find(session('tenant_id')) ?? \App\Models\Tenant::first();
            $warehouse = \App\Models\Warehouse::where('tenant_id', $tenant->id)->first();
            $customers = \App\Models\Customer::where('tenant_id', $tenant->id)->pluck('id')->toArray();

            Package::where('tenant_id', $tenant->id)->delete();

            for($i=1; $i<=10; $i++) {
                Package::create([
                    'tenant_id' => $tenant->id,
                    'warehouse_id' => $warehouse->id,
                    'customer_id' => !empty($customers) ? $customers[array_rand($customers)] : null,
                    'tracking_number' => 'REPAIR-' . strtoupper(Str::random(8)),
                    'description' => 'Paquete de Reparación ' . $i,
                    'weight' => rand(1, 10),
                    'status' => 'arrived',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            session()->flash('message', '¡Base de datos del tenant reparada! 10 paquetes generados.');
            return redirect()->route('logistics.inventory');
        } catch (\Exception $e) {
            session()->flash('error', 'Error en reparación: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $packages = $this->applySorting($this->getPackagesQuery())->paginate(15);

        $stats = [
            'total_count' => Package::whereNotIn('status', ['delivered', 'cancelled'])->count(),
            'total_weight' => Package::whereNotIn('status', ['delivered', 'cancelled'])->sum('weight'),
            'pending_assignment' => Package::whereNull('customer_id')->count(),
            'by_status' => Package::whereNotIn('status', ['delivered', 'cancelled'])
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get()
        ];

        return view('livewire.logistics.inventory-list', [
            'packages' => $packages,
            'stats' => $stats,
            'warehouses' => Warehouse::all()
        ])->layout('components.layouts.app');
    }
}
