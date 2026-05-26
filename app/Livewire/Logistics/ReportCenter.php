<?php

namespace App\Livewire\Logistics;

use Livewire\Component;
use App\Models\Tenant;
use App\Models\Package;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Warehouse;

class ReportCenter extends Component
{
    public $active_report = null;
    public $available_reports = [];
    public $report_data = [];

    public function mount()
    {
        $tenant = Tenant::find(session('tenant_id'));
        $enabled = $tenant->enabled_reports_json ?? ['inventory_stock', 'revenue_daily', 'customer_debt', 'package_status'];

        $all_reports = [
            'inventory_stock' => ['name' => 'Resumen de Inventario', 'icon' => 'box', 'desc' => 'Stock actual en cada bodega.'],
            'revenue_daily' => ['name' => 'Ingresos Diarios', 'icon' => 'dollar-sign', 'desc' => 'Recaudación de facturas pagadas.'],
            'customer_debt' => ['name' => 'Ranking de Deudores', 'icon' => 'users', 'desc' => 'Clientes con saldos más altos.'],
            'package_status' => ['name' => 'Distribución de Estados', 'icon' => 'pie-chart', 'desc' => 'Conteo por cada fase logística.'],
            'volume_weight' => ['name' => 'Métricas de Crecimiento', 'icon' => 'trending-up', 'desc' => 'Comparativa mensual de carga.'],
            'stagnant_cargo' => ['name' => 'Carga Estancada', 'icon' => 'clock', 'desc' => 'Paquetes con más de 15 días en bodega.'],
            'driver_efficiency' => ['name' => 'Eficiencia de Reparto', 'icon' => 'truck', 'desc' => 'Desempeño de conductores locales.'],
            'tax_collection' => ['name' => 'Recaudación de Impuestos', 'icon' => 'percent', 'desc' => 'Total de impuestos facturados.'],
            'referral_activity' => ['name' => 'Actividad de Referidos', 'icon' => 'user-plus', 'desc' => 'Rendimiento del programa de socios.'],
            'system_audit' => ['name' => 'Auditoría de Sistema', 'icon' => 'shield', 'desc' => 'Historial de acciones críticas.'],
        ];

        // Filter only enabled ones
        foreach ($all_reports as $slug => $info) {
            if (in_array($slug, $enabled)) {
                $this->available_reports[$slug] = $info;
            }
        }
    }

    public function selectReport($slug)
    {
        $this->active_report = $slug;
        $this->loadReportData($slug);
    }

    public function loadReportData($slug)
    {
        switch ($slug) {
            case 'inventory_stock':
                $this->report_data = Warehouse::withCount(['packages' => function($q) {
                    $q->whereNotIn('status', ['delivered', 'cancelled']);
                }])->get();
                break;
            case 'revenue_daily':
                $this->report_data = Invoice::where('status', 'paid')
                    ->where('paid_at', '>=', now()->startOfWeek())
                    ->selectRaw('date(paid_at) as date, sum(total) as total')
                    ->groupBy('date')
                    ->get();
                break;
            case 'customer_debt':
                $this->report_data = Customer::with('user')
                    ->where('balance', '>', 0)
                    ->orderBy('balance', 'desc')
                    ->take(10)
                    ->get();
                break;
            case 'package_status':
                $this->report_data = Package::selectRaw('status, count(*) as count')
                    ->groupBy('status')
                    ->get();
                break;
            case 'stagnant_cargo':
                $this->report_data = Package::with(['customer.user', 'warehouse'])
                    ->whereNotIn('status', ['delivered', 'cancelled', 'consolidated'])
                    ->where('created_at', '<=', now()->subDays(15))
                    ->orderBy('created_at', 'asc')
                    ->get();
                break;
            case 'tax_collection':
                $this->report_data = Invoice::where('status', 'paid')
                    ->selectRaw('date(paid_at) as date, sum(tax) as total_tax')
                    ->groupBy('date')
                    ->orderBy('date', 'desc')
                    ->get();
                break;
            case 'volume_weight':
                $monthFormat = config('database.default') === 'sqlite' ? 'strftime("%Y-%m", created_at)' : "DATE_FORMAT(created_at, '%Y-%m')";
                $this->report_data = Package::selectRaw("$monthFormat as month, sum(weight) as total_weight, sum(volumetric_weight) as total_vlb")
                    ->groupBy('month')
                    ->orderBy('month', 'desc')
                    ->take(6)
                    ->get();
                break;
        }
    }

    public function render()
    {
        return view('livewire.logistics.report-center')->layout('components.layouts.app');
    }
}
