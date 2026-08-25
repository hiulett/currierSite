<?php

namespace App\Livewire\Logistics;

use App\Helpers\DatabaseHelper;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Tenant;
use App\Models\Warehouse;
use Livewire\Component;

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
                $this->report_data = Warehouse::withCount(['packages' => function ($q) {
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
                $monthFormat = DatabaseHelper::formatMonth('created_at', '%Y-%m');
                $this->report_data = Package::selectRaw("$monthFormat as month, sum(weight) as total_weight, sum(volumetric_weight) as total_vlb")
                    ->groupBy('month')
                    ->orderBy('month', 'desc')
                    ->take(6)
                    ->get();
                break;
        }
    }

    public function exportReport()
    {
        if (! $this->active_report || empty($this->report_data)) {
            session()->flash('error', 'Selecciona un reporte con datos para exportar.');

            return;
        }

        $rows = $this->buildExportRows($this->active_report);
        if (empty($rows)) {
            session()->flash('error', 'No hay datos para exportar en este reporte.');

            return;
        }

        $headings = array_keys($rows[0]);
        $filename = 'Reporte_'.$this->active_report.'_'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($headings, $rows) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF"); // UTF-8 BOM para que Excel lo abra correctamente
            fputcsv($handle, $headings);
            foreach ($rows as $row) {
                fputcsv($handle, array_values($row));
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    protected function buildExportRows($slug)
    {
        $rows = [];

        foreach ($this->report_data as $item) {
            switch ($slug) {
                case 'inventory_stock':
                    $rows[] = [
                        'Bodega' => $item->name,
                        'Paquetes en stock' => $item->packages_count,
                    ];
                    break;
                case 'revenue_daily':
                    $rows[] = [
                        'Fecha' => $item->date,
                        'Total recaudado' => number_format((float) $item->total, 2),
                    ];
                    break;
                case 'customer_debt':
                    $rows[] = [
                        'Cliente' => $item->user?->name ?? '—',
                        'Casillero' => $item->box_number,
                        'Saldo pendiente' => number_format((float) $item->balance, 2),
                    ];
                    break;
                case 'package_status':
                    $dummy = new Package(['status' => $item->status]);
                    $rows[] = [
                        'Estado' => $dummy->getStatusLabel(),
                        'Cantidad' => $item->count,
                    ];
                    break;
                case 'stagnant_cargo':
                    $rows[] = [
                        'Tracking' => $item->tracking_number,
                        'Cliente' => $item->customer?->user?->name ?? '—',
                        'Bodega' => $item->warehouse?->code ?? '—',
                        'Días en stock' => now()->diffInDays($item->created_at),
                    ];
                    break;
                case 'tax_collection':
                    $rows[] = [
                        'Fecha' => $item->date,
                        'Impuestos recaudados' => number_format((float) $item->total_tax, 2),
                    ];
                    break;
                case 'volume_weight':
                    $rows[] = [
                        'Mes' => $item->month,
                        'Peso real total' => number_format((float) $item->total_weight, 2),
                        'Peso volumétrico' => number_format((float) $item->total_vlb, 2),
                    ];
                    break;
            }
        }

        return $rows;
    }

    public function render()
    {
        return view('livewire.logistics.report-center')->layout('components.layouts.app');
    }
}
