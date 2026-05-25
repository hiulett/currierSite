<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Tenant;

class ReportConfig extends Component
{
    public $selected_tenant_id;
    public $enabled_reports = [];

    public $all_reports = [
        'inventory_stock' => 'Resumen de Inventario',
        'revenue_daily' => 'Ingresos Diarios',
        'customer_debt' => 'Ranking de Deudores',
        'package_status' => 'Distribución de Estados',
        'volume_weight' => 'Métricas de Crecimiento',
        'stagnant_cargo' => 'Carga Estancada',
        'driver_efficiency' => 'Eficiencia de Reparto',
        'tax_collection' => 'Recaudación de Impuestos',
        'referral_activity' => 'Actividad de Referidos',
        'system_audit' => 'Log de Auditoría',
    ];

    public function mount($tenantId)
    {
        $this->selected_tenant_id = $tenantId;
        $tenant = Tenant::findOrFail($tenantId);
        $this->enabled_reports = $tenant->enabled_reports_json ?? [];
    }

    public function toggleReport($slug)
    {
        if (in_array($slug, $this->enabled_reports)) {
            $this->enabled_reports = array_diff($this->enabled_reports, [$slug]);
        } else {
            $this->enabled_reports[] = $slug;
        }

        $tenant = Tenant::findOrFail($this->selected_tenant_id);
        $tenant->update(['enabled_reports_json' => array_values($this->enabled_reports)]);

        $this->dispatch('report-config-updated');
    }

    public function render()
    {
        return view('livewire.super-admin.report-config');
    }
}
