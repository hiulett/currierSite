<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Package;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use App\Traits\WithSorting;

class InvoiceList extends Component
{
    use WithPagination, WithSorting;

    public $search = '';
    public $filter_status = '';
    public $filter_date_from = '';
    public $filter_date_to = '';
    public $selected_invoices = [];
    public $selectAll = false;

    // Payment Modal State
    public $is_paying = false;
    public $payment_method = 'cash';
    public $payment_reference = '';
    public $single_invoice_id = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filter_status' => ['except' => ''],
        'filter_date_from' => ['except' => ''],
        'filter_date_to' => ['except' => ''],
    ];

    protected $listeners = ['invoice-saved' => '$refresh'];

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected_invoices = $this->getInvoicesQuery()->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selected_invoices = [];
        }
    }

    public function openPaymentModal($invoiceId = null)
    {
        $this->single_invoice_id = $invoiceId;
        if (!$invoiceId && empty($this->selected_invoices)) {
            session()->flash('error', 'Seleccione al menos una factura.');
            return;
        }
        $this->is_paying = true;
    }

    public function confirmPayment()
    {
        $ids = $this->single_invoice_id ? [$this->single_invoice_id] : $this->selected_invoices;
        $invoices = Invoice::whereIn('id', $ids)->where('status', '!=', 'paid')->get();

        foreach ($invoices as $invoice) {
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_method' => $this->payment_method,
                'payment_reference' => $this->payment_reference,
            ]);

            if ($invoice->customer) {
                $invoice->customer->decrement('balance', $invoice->total);
            }
        }

        $this->reset(['is_paying', 'selected_invoices', 'selectAll', 'single_invoice_id', 'payment_reference']);
        session()->flash('message', count($invoices) . ' factura(s) procesadas como pagadas.');
    }

    public function voidInvoice($invoiceId)
    {
        $invoice = Invoice::find($invoiceId);
        if ($invoice && $invoice->status !== 'cancelled') {
            // Restore customer balance if it was unpaid
            if ($invoice->status === 'unpaid') {
                $invoice->customer->decrement('balance', $invoice->total);
            }

            $invoice->update(['status' => 'cancelled']);
            session()->flash('message', 'Factura ' . $invoice->number . ' ha sido anulada.');
        }
    }

    public function deleteInvoice($invoiceId)
    {
        $invoice = Invoice::find($invoiceId);
        if ($invoice) {
            // Restore customer balance if it was unpaid
            if ($invoice->status === 'unpaid' && $invoice->customer) {
                $invoice->customer->decrement('balance', $invoice->total);
            }

            $invoice->delete();
            session()->flash('message', 'Factura eliminada con éxito.');
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

    /**
     * Elimina todas las facturas del tenant actual para iniciar producción.
     */
    public function purgeInvoices()
    {
        // Seguridad: Solo permitir a administradores
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'superadmin') {
            session()->flash('error', 'No tiene permisos para realizar esta acción.');
            return;
        }

        $tenantId = session('tenant_id');

        DB::transaction(function() use ($tenantId) {
            // 1. Resetear balances de clientes (opcional, pero recomendado para fresh start)
            Customer::where('tenant_id', $tenantId)->update(['balance' => 0]);

            // 2. Eliminar items y facturas
            InvoiceItem::where('tenant_id', $tenantId)->delete();
            Invoice::where('tenant_id', $tenantId)->delete();
        });

        session()->flash('message', 'Todas las facturas de prueba han sido eliminadas. El sistema está listo para producción.');
        $this->resetPage();
    }

    protected function getInvoicesQuery()
    {
        $query = Invoice::with('customer.user')
            ->where(function($query) {
                $query->where('number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('customer', function($q) {
                          $q->where('box_number', 'like', '%' . $this->search . '%')
                            ->orWhereHas('user', function($u) {
                                $u->where('name', 'like', '%' . $this->search . '%');
                            });
                      });
            });

        if ($this->filter_status === 'overdue') {
            $query->where('status', 'unpaid')
                  ->where('due_date', '<', now()->today());
        } elseif ($this->filter_status) {
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
        $invoices = $this->applySorting($this->getInvoicesQuery())->paginate(15);

        $stats = [
            'total_invoiced' => Invoice::where('status', '!=', 'cancelled')->sum('total'),
            'unpaid_amount' => Invoice::where('status', 'unpaid')->sum('total'),
            'paid_today' => Invoice::where('status', 'paid')->whereDate('paid_at', now()->today())->sum('total'),
            'pending_count' => Invoice::where('status', 'unpaid')->count(),
            'overdue_count' => Invoice::where('status', 'unpaid')->where('due_date', '<', now()->today())->count(),
            'cancelled_count' => Invoice::where('status', 'cancelled')->count(),
        ];

        $tenant = \App\Models\Tenant::find(session('tenant_id'));
        $currency = $tenant->settings_json['currency'] ?? 'USD';

        return view('livewire.billing.invoice-list', [
            'invoices' => $invoices,
            'stats' => $stats,
            'currency' => $currency,
        ])->layout('components.layouts.app');
    }
}
