<?php

namespace App\Livewire\Billing;

use App\Helpers\DatabaseHelper;
use App\Jobs\SendQuotationWhatsApp;
use App\Mail\QuotationSent;
use App\Models\Quotation;
use App\Models\Tenant;
use App\Services\WhatsAppService;
use App\Traits\WithSorting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class QuotationList extends Component
{
    use WithPagination, WithSorting;

    public $search = '';

    public $filter_status = '';

    public $filter_date_from = '';

    public $filter_date_to = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filter_status' => ['except' => ''],
        'filter_date_from' => ['except' => ''],
        'filter_date_to' => ['except' => ''],
    ];

    protected $listeners = ['quotation-saved' => '$refresh'];

    public function deleteQuotation($quotationId)
    {
        $quotation = Quotation::find($quotationId);
        if ($quotation) {
            $quotation->delete();
            session()->flash('message', 'Cotización eliminada con éxito.');
        }
    }

    public function markAsStatus($quotationId, $status)
    {
        $quotation = Quotation::find($quotationId);
        if ($quotation && in_array($status, ['accepted', 'rejected', 'sent', 'email_sent', 'invoiced', 'draft'])) {
            $quotation->update(['status' => $status]);
            session()->flash('message', 'Estado de la cotización actualizado a '.$status.'.');
        }
    }

    public function sendEmail($quotationId)
    {
        $quotation = Quotation::with(['customer.user', 'tenant', 'items'])->find($quotationId);
        if (! $quotation) {
            session()->flash('error', 'Cotización no encontrada.');

            return;
        }

        $email = $quotation->customer?->user?->email ?? $quotation->client_email;

        if ($email) {
            try {
                Mail::to($email)
                    ->send(new QuotationSent($quotation));
                $quotation->update(['status' => 'email_sent']);
                session()->flash('message', '✉️ Correo de cotización enviado con éxito a '.$email);
            } catch (\Exception $e) {
                Log::error('Error dispatching quotation email: '.$e->getMessage());
                session()->flash('error', 'Error al enviar el correo. Verifique la configuración SMTP. Detalle: '.$e->getMessage());
            }
        } else {
            session()->flash('error', 'El cliente no tiene un correo electrónico asociado.');
        }
    }

    public function sendWhatsApp($quotationId)
    {
        $quotation = Quotation::with(['customer.user', 'tenant'])->find($quotationId);
        if (! $quotation) {
            session()->flash('error', 'Cotización no encontrada.');

            return;
        }

        $tenant = $quotation->tenant;
        if (! $tenant || ! app(WhatsAppService::class)->isConfigured($tenant)) {
            session()->flash('error', 'WhatsApp no está configurado. Configúralo en Configuración → Pagos e Integraciones.');

            return;
        }

        $phone = $quotation->customer?->phone ?? $quotation->client_phone;
        if (! $phone) {
            session()->flash('error', 'El cliente no tiene un teléfono registrado.');

            return;
        }

        SendQuotationWhatsApp::dispatch($quotation);
        session()->flash('message', 'Envío de la cotización #'.$quotation->number.' por WhatsApp encolado.');
    }

    protected function getQuotationsQuery()
    {
        $query = Quotation::with('customer.user')
            ->where(function ($query) {
                $query->where('number', 'like', '%'.$this->search.'%')
                    ->orWhere('client_name', 'like', '%'.$this->search.'%')
                    ->orWhere('client_email', 'like', '%'.$this->search.'%')
                    ->orWhereHas('customer', function ($q) {
                        $q->whereHas('user', function ($u) {
                            $u->where('name', 'like', '%'.$this->search.'%');
                        });
                    });
            });

        if ($this->filter_status) {
            $query->where('status', $this->filter_status);
        }

        if ($this->filter_date_from) {
            $query->whereDate('created_at', '>=', $this->filter_date_from);
        }

        if ($this->filter_date_to) {
            $query->whereDate('created_at', '<=', $this->filter_date_to);
        }

        return $query;
    }

    public function render()
    {
        $quotations = $this->applySorting($this->getQuotationsQuery(), 'created_at', 'desc')->paginate(15);

        // Dashboard Metrics
        $stats = [
            'total_pipeline' => Quotation::whereIn('status', ['draft', 'sent'])->sum('total'),
            'total_won' => Quotation::whereIn('status', ['accepted', 'invoiced'])->sum('total'),
            'draft_count' => Quotation::where('status', 'draft')->count(),
            'sent_count' => Quotation::where('status', 'sent')->count(),
            'accepted_count' => Quotation::where('status', 'accepted')->count(),
            'rejected_count' => Quotation::where('status', 'rejected')->count(),
        ];

        // Conversion Rate
        $closed_total = $stats['accepted_count'] + $stats['rejected_count'];
        $stats['conversion_rate'] = $closed_total > 0 ? round(($stats['accepted_count'] / $closed_total) * 100, 1) : 0;

        $tenant = Tenant::find(session('tenant_id'));
        $currency = $tenant->settings_json['currency'] ?? 'USD';

        // Prepare monthly data for Bar Chart (Last 6 months)
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $monthFormat = DatabaseHelper::formatMonth('created_at', '%Y-%m');
        $monthlyData = Quotation::where('created_at', '>=', $sixMonthsAgo)
            ->selectRaw("$monthFormat as month, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')->toArray();

        $chartLabels = [];
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $chartLabels[] = $date->translatedFormat('M Y');
            $chartData[] = $monthlyData[$monthKey] ?? 0;
        }

        $pieChartData = [
            $stats['draft_count'],
            $stats['sent_count'],
            $stats['accepted_count'],
            $stats['rejected_count'],
        ];

        $this->dispatch('updateQuotationCharts',
            chartLabels: array_values($chartLabels),
            chartData: array_values($chartData),
            pieChartData: array_values($pieChartData)
        );

        return view('livewire.billing.quotation-list', [
            'quotations' => $quotations,
            'stats' => $stats,
            'currency' => $currency,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'pieChartData' => $pieChartData,
        ])->layout('components.layouts.app');
    }
}
