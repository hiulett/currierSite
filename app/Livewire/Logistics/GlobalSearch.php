<?php

namespace App\Livewire\Logistics;

use App\Models\Customer;
use App\Models\Package;
use Livewire\Component;

class GlobalSearch extends Component
{
    public $show = false;

    public $query = '';

    protected $listeners = ['global-search-toggle' => 'toggle'];

    public function toggle()
    {
        $this->show = ! $this->show;

        if (! $this->show) {
            $this->query = '';
        }
    }

    public function close()
    {
        $this->show = false;
        $this->query = '';
    }

    public function render()
    {
        $query = trim($this->query);

        $customers = collect();
        $packages = collect();

        if ($query !== '') {
            $term = '%'.$query.'%';

            $customers = Customer::with('user')
                ->where(function ($q) use ($term) {
                    $q->where('box_number', 'like', $term)
                        ->orWhere('identification_number', 'like', $term)
                        ->orWhereHas('user', function ($u) use ($term) {
                            $u->where('name', 'like', $term)->orWhere('email', 'like', $term);
                        });
                })
                ->take(6)
                ->get();

            $packages = Package::with('customer.user')
                ->where('tracking_number', 'like', $term)
                ->take(6)
                ->get();
        }

        $allModules = [
            ['name' => 'Dashboard', 'url' => route('dashboard'), 'icon' => 'sliders', 'perm' => null],
            ['name' => 'Base de Clientes', 'url' => route('logistics.customers'), 'icon' => 'users', 'perm' => 'customers.view'],
            ['name' => 'Recepción de Paquetes', 'url' => route('logistics.smart-reception'), 'icon' => 'zap', 'perm' => 'logistics.receive'],
            ['name' => 'Control Manifiestos', 'url' => route('logistics.receive-manifest'), 'icon' => 'file-text', 'perm' => 'logistics.receive'],
            ['name' => 'Inventario Activo', 'url' => route('logistics.inventory'), 'icon' => 'box', 'perm' => 'logistics.inventory'],
            ['name' => 'Casilleros Físicos', 'url' => route('logistics.lockers'), 'icon' => 'grid', 'perm' => 'logistics.inventory'],
            ['name' => 'Reempaque / Consolidación', 'url' => route('logistics.repack'), 'icon' => 'package', 'perm' => 'logistics.repack'],
            ['name' => 'Embarques (Outbound)', 'url' => route('logistics.shipments.index'), 'icon' => 'truck', 'perm' => 'logistics.shipments'],
            ['name' => 'Última Milla (Delivery)', 'url' => route('logistics.delivery'), 'icon' => 'map-pin', 'perm' => 'logistics.delivery'],
            ['name' => 'Entrega en Counter', 'url' => route('logistics.counter'), 'icon' => 'box', 'perm' => 'logistics.counter'],
            ['name' => 'Rastreo Global', 'url' => route('logistics.tracking'), 'icon' => 'search', 'perm' => null],
            ['name' => 'Reportes de Negocio', 'url' => route('logistics.reports'), 'icon' => 'bar-chart-2', 'perm' => 'logistics.reports'],
            ['name' => 'Soporte (Tickets)', 'url' => route('logistics.tickets'), 'icon' => 'message-square', 'perm' => 'tickets.manage'],
            ['name' => 'Facturación', 'url' => route('billing.index'), 'icon' => 'credit-card', 'perm' => 'billing.view'],
            ['name' => 'Cotizaciones', 'url' => route('billing.quotations.index'), 'icon' => 'file-text', 'perm' => 'billing.view'],
            ['name' => 'Control de Fletes', 'url' => route('billing.driver-trips.index'), 'icon' => 'truck', 'perm' => 'billing.view'],
            ['name' => 'Estados de Cuenta', 'url' => route('billing.statement'), 'icon' => 'file-text', 'perm' => 'billing.view'],
            ['name' => 'Egresos', 'url' => route('billing.expenses.index'), 'icon' => 'dollar-sign', 'perm' => 'billing.view'],
            ['name' => 'Identidad Visual', 'url' => route('builder.brand'), 'icon' => 'layout', 'perm' => 'settings.brand'],
            ['name' => 'Ajustes Generales', 'url' => route('builder.general'), 'icon' => 'settings', 'perm' => 'settings.general'],
            ['name' => 'Colaboradores', 'url' => route('builder.users'), 'icon' => 'users', 'perm' => 'settings.users'],
            ['name' => 'Roles y Permisos', 'url' => route('builder.roles'), 'icon' => 'shield', 'perm' => 'settings.users'],
        ];

        $modules = [];
        foreach ($allModules as $module) {
            if ($module['perm'] !== null && ! auth()->user()?->can($module['perm'])) {
                continue;
            }
            if ($query !== '' && stripos($module['name'], $query) === false) {
                continue;
            }
            $modules[] = $module;
        }

        return view('livewire.logistics.global-search', [
            'modules' => $modules,
            'customers' => $customers,
            'packages' => $packages,
        ]);
    }
}
