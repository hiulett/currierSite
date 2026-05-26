<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Package;
use Livewire\WithPagination;
use App\Traits\WithSorting;

class InvoiceList extends Component
{
    use WithPagination, WithSorting;

    public $search = '';
    public $filter_status = '';

    protected $queryString = ['search', 'filter_status'];

    protected $listeners = ['invoice-saved' => '$refresh'];

    public function markAsPaid($invoiceId)
    {
        $invoice = Invoice::find($invoiceId);
        if ($invoice && $invoice->status !== 'paid') {
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // Update customer balance if necessary
            $invoice->customer->decrement('balance', $invoice->total);

            session()->flash('message', 'Factura ' . $invoice->number . ' marcada como pagada.');
        }
    }

    public function sendEmail($invoiceId)
    {
        $invoice = Invoice::with('customer.user')->find($invoiceId);
        if ($invoice && $invoice->customer && $invoice->customer->user) {
            $invoice->customer->user->notify(new \App\Notifications\InvoiceSent($invoice));
            session()->flash('message', 'Factura enviada por correo a ' . $invoice->customer->user->email);
        }
    }

    public function render()
    {
        $query = Invoice::with('customer.user')
            ->where(function($query) {
                $query->where('number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('customer', function($q) {
                          $q->where('box_number', 'like', '%' . $this->search . '%');
                      });
            });

        if ($this->filter_status === 'overdue') {
            $query->where('status', 'unpaid')
                  ->where('due_date', '<', now()->today());
        } elseif ($this->filter_status) {
            $query->where('status', $this->filter_status);
        }

        $invoices = $this->applySorting($query)
            ->paginate(10);

        $monthFormat = config('database.default') === 'sqlite' ? 'strftime("%m", created_at)' : "DATE_FORMAT(created_at, '%m')";

        // Prepare Revenue Chart Data for Billing Screen
        $monthlyRevenue = Invoice::selectRaw("$monthFormat as month, sum(total) as total")
            ->where('created_at', '>=', now()->startOfYear())
            ->where('status', '!=', 'cancelled')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $revenueData = array_fill(1, 12, 0);
        $projectionData = array_fill(1, 12, 0);
        $totalMonthsWithData = 0;
        $sumRevenue = 0;

        foreach ($monthlyRevenue as $month => $total) {
            $monthIdx = (int)$month;
            $revenueData[$monthIdx] = $total;
            $sumRevenue += $total;
            if ($total > 0) $totalMonthsWithData++;
        }

        // Advanced Prediction Model
        $avgRevenue = $totalMonthsWithData > 0 ? ($sumRevenue / $totalMonthsWithData) : 100;
        $newCustomersWeight = Customer::where('created_at', '>=', now()->subDays(30))->count();
        $packageVolume = Package::where('created_at', '>=', now()->subDays(30))->count();

        $momentumFactor = 1 + (($newCustomersWeight * 0.05) + ($packageVolume * 0.001));
        $currentMonth = (int)date('m');

        for ($i = 1; $i <= 12; $i++) {
            if ($i < $currentMonth) {
                $projectionData[$i] = round($avgRevenue, 2);
            } else {
                $projectionData[$i] = round($avgRevenue * pow($momentumFactor, ($i - $currentMonth + 1)), 2);
            }
        }

        $stats = [
            'total_invoiced' => Invoice::where('status', '!=', 'cancelled')->sum('total'),
            'unpaid_amount' => Invoice::where('status', 'unpaid')->sum('total'),
            'paid_today' => Invoice::where('status', 'paid')->whereDate('paid_at', now()->today())->sum('total'),
            'pending_count' => Invoice::where('status', 'unpaid')->count(),
            'overdue_count' => Invoice::where('status', 'unpaid')->where('due_date', '<', now()->today())->count(),
        ];

        $tenant = \App\Models\Tenant::find(session('tenant_id'));
        $currency = $tenant->settings_json['currency'] ?? 'USD';

        return view('livewire.billing.invoice-list', [
            'invoices' => $invoices,
            'stats' => $stats,
            'currency' => $currency,
            'revenueData' => array_values($revenueData),
            'projectionData' => array_values($projectionData),
        ])->layout('components.layouts.app');
    }
}
