<?php

namespace App\Livewire\Billing;

use App\Exports\DriverTripExport;
use App\Models\DriverTrip;
use App\Models\Tenant;
use App\Traits\WithSorting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class DriverTripList extends Component
{
    use WithPagination, WithSorting;

    public $search = '';

    public $filter_date_from = '';

    public $filter_date_to = '';

    public $filter_invoice_status = '';

    public $filter_driver_payment_status = '';

    // Modal / Form fields
    public $trip_id;

    public $date;

    public $driver_name = '';

    public $company_name = '';

    public $description = '';

    public $outsourcing_cost = 0;

    public $final_client_price = 0;

    public $invoice_number = '';

    public $invoice_status = 'PENDIENTE';

    public $driver_payment_status = 'PENDIENTE';

    public $isEditMode = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'filter_date_from' => ['except' => ''],
        'filter_date_to' => ['except' => ''],
        'filter_invoice_status' => ['except' => ''],
        'filter_driver_payment_status' => ['except' => ''],
    ];

    protected $rules = [
        'date' => 'required|date',
        'driver_name' => 'required|string|max:100',
        'company_name' => 'required|string|max:100',
        'description' => 'required|string',
        'outsourcing_cost' => 'required|numeric|min:0',
        'final_client_price' => 'required|numeric|min:0',
        'invoice_number' => 'nullable|string|max:100',
        'invoice_status' => 'required|string|in:PENDIENTE,PAGADA,ABONO',
        'driver_payment_status' => 'required|string|in:PENDIENTE,PAGADA',
    ];

    public function mount()
    {
        $this->sortField = 'date';
        $this->sortDirection = 'desc';

        // Default: filtrar por mes corriente si no hay filtros en query string
        if (empty($this->filter_date_from) && empty($this->filter_date_to)) {
            $this->filter_date_from = now()->startOfMonth()->format('Y-m-d');
            $this->filter_date_to = now()->endOfMonth()->format('Y-m-d');
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filter_invoice_status = '';
        $this->filter_driver_payment_status = '';
        $this->filter_date_from = now()->startOfMonth()->format('Y-m-d');
        $this->filter_date_to = now()->endOfMonth()->format('Y-m-d');
        $this->resetPage();
    }

    public function exportExcel()
    {
        $query = $this->getFilteredQuery();
        $trips = $this->applySorting($query)->get();
        $tenant = Tenant::find(session('tenant_id'));
        $currency = $tenant->settings_json['currency'] ?? 'USD';

        return Excel::download(
            new DriverTripExport($trips, $currency),
            'Fletes_'.now()->format('Y-m-d').'.xlsx'
        );
    }

    public function exportPdf()
    {
        $query = $this->getFilteredQuery();
        $trips = $this->applySorting($query)->get();
        $tenant = Tenant::find(session('tenant_id'));
        $currency = $tenant->settings_json['currency'] ?? 'USD';

        $stats = [
            'total_outsourcing' => (float) $trips->sum('outsourcing_cost'),
            'total_client' => (float) $trips->sum('final_client_price'),
            'total_revenue' => (float) $trips->sum('revenue'),
        ];

        $dateRange = ($this->filter_date_from && $this->filter_date_to)
            ? Carbon::parse($this->filter_date_from)->format('d/m/Y').' - '.Carbon::parse($this->filter_date_to)->format('d/m/Y')
            : 'Todos los períodos';

        // Logo en base64 para DomPDF
        $logoBase64 = null;
        try {
            $logoUrl = $tenant->theme_config_json['logo_url'] ?? null;
            if ($logoUrl) {
                $logoData = @file_get_contents($logoUrl);
                if ($logoData) {
                    $type = pathinfo($logoUrl, PATHINFO_EXTENSION);
                    $logoBase64 = 'data:image/'.($type ?: 'png').';base64,'.base64_encode($logoData);
                }
            }
        } catch (\Exception $e) {
            // Logo opcional, continuar sin él
        }

        $pdf = Pdf::loadView('billing.driver-trip-report-pdf', compact(
            'trips', 'stats', 'currency', 'dateRange', 'tenant', 'logoBase64'
        ));
        $pdf->setPaper('letter', 'landscape');

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'Reporte_Fletes_'.now()->format('Y-m-d').'.pdf'
        );
    }

    public function initForm()
    {
        $this->resetValidation();
        $this->trip_id = null;
        $this->date = now()->format('Y-m-d');
        $this->driver_name = '';
        $this->company_name = '';
        $this->description = '';
        $this->outsourcing_cost = 0;
        $this->final_client_price = 0;
        $this->invoice_number = '';
        $this->invoice_status = 'PENDIENTE';
        $this->driver_payment_status = 'PENDIENTE';
        $this->isEditMode = false;
    }

    public function openCreateModal()
    {
        $this->initForm();
        $this->dispatch('open-modal', 'modalDriverTripForm');
    }

    public function editTrip($id)
    {
        $this->resetValidation();
        $trip = DriverTrip::findOrFail($id);
        $this->trip_id = $trip->id;
        $this->date = $trip->date->format('Y-m-d');
        $this->driver_name = $trip->driver_name;
        $this->company_name = $trip->company_name;
        $this->description = $trip->description;
        $this->outsourcing_cost = $trip->outsourcing_cost;
        $this->final_client_price = $trip->final_client_price;
        $this->invoice_number = $trip->invoice_number;
        $this->invoice_status = $trip->invoice_status;
        $this->driver_payment_status = $trip->driver_payment_status;
        $this->isEditMode = true;

        $this->dispatch('open-modal', 'modalDriverTripForm');
    }

    public function saveTrip()
    {
        $this->validate();

        $data = [
            'tenant_id' => session('tenant_id'),
            'date' => $this->date,
            'driver_name' => $this->driver_name,
            'company_name' => $this->company_name,
            'description' => $this->description,
            'outsourcing_cost' => $this->outsourcing_cost,
            'final_client_price' => $this->final_client_price,
            'invoice_number' => $this->invoice_number,
            'invoice_status' => $this->invoice_status,
            'driver_payment_status' => $this->driver_payment_status,
        ];

        if ($this->isEditMode && $this->trip_id) {
            $trip = DriverTrip::findOrFail($this->trip_id);
            $trip->update($data);
            session()->flash('message', 'Flete/Viaje actualizado con éxito.');
        } else {
            DriverTrip::create($data);
            session()->flash('message', 'Flete/Viaje registrado con éxito.');
        }

        $this->dispatch('close-modal', 'modalDriverTripForm');
        $this->initForm();
    }

    public function deleteTrip($id)
    {
        $trip = DriverTrip::findOrFail($id);
        $trip->delete();
        session()->flash('message', 'Registro de Flete/Viaje eliminado con éxito.');
    }

    public function getFilteredQuery()
    {
        $query = DriverTrip::query();

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('driver_name', 'like', '%'.$this->search.'%')
                    ->orWhere('company_name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%')
                    ->orWhere('invoice_number', 'like', '%'.$this->search.'%');
            });
        }

        if (! empty($this->filter_date_from)) {
            $query->whereDate('date', '>=', $this->filter_date_from);
        }

        if (! empty($this->filter_date_to)) {
            $query->whereDate('date', '<=', $this->filter_date_to);
        }

        if (! empty($this->filter_invoice_status)) {
            $query->where('invoice_status', $this->filter_invoice_status);
        }

        if (! empty($this->filter_driver_payment_status)) {
            $query->where('driver_payment_status', $this->filter_driver_payment_status);
        }

        return $query;
    }

    public function render()
    {
        $baseQuery = $this->getFilteredQuery();

        // Calculate Stats based on CURRENT filters (dynamic and useful)
        $stats = [
            'total_outsourcing' => (float) $baseQuery->sum('outsourcing_cost'),
            'total_client' => (float) $baseQuery->sum('final_client_price'),
            'total_revenue' => (float) $baseQuery->sum('revenue'),
        ];

        $trips = $this->applySorting($baseQuery)->paginate(15);

        $tenant = Tenant::find(session('tenant_id'));
        $currency = $tenant->settings_json['currency'] ?? 'USD';

        // Etiqueta del período activo para mostrar en la UI
        $isCurrentMonth = ($this->filter_date_from === now()->startOfMonth()->format('Y-m-d')
            && $this->filter_date_to === now()->endOfMonth()->format('Y-m-d'));

        if ($isCurrentMonth) {
            $dateRangeLabel = ucfirst(now()->translatedFormat('F Y'));
        } elseif ($this->filter_date_from && $this->filter_date_to) {
            $dateRangeLabel = Carbon::parse($this->filter_date_from)->format('d/m/Y').' — '.Carbon::parse($this->filter_date_to)->format('d/m/Y');
        } elseif ($this->filter_date_from) {
            $dateRangeLabel = 'Desde '.Carbon::parse($this->filter_date_from)->format('d/m/Y');
        } elseif ($this->filter_date_to) {
            $dateRangeLabel = 'Hasta '.Carbon::parse($this->filter_date_to)->format('d/m/Y');
        } else {
            $dateRangeLabel = 'Todos los períodos';
        }

        return view('livewire.billing.driver-trip-list', [
            'trips' => $trips,
            'stats' => $stats,
            'currency' => $currency,
            'dateRangeLabel' => $dateRangeLabel,
            'isCurrentMonth' => $isCurrentMonth,
        ])->layout('components.layouts.app');
    }
}
