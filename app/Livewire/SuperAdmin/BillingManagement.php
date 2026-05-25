<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\SubscriptionInvoice;
use App\Models\Tenant;
use App\Models\Plan;
use Livewire\WithPagination;
use App\Traits\WithSorting;
use Illuminate\Support\Str;

class BillingManagement extends Component
{
    use WithPagination, WithSorting;

    public $search = '';
    public $filter_status = '';

    // Create Invoice Modal
    public $tenant_id, $amount, $due_date, $notes;

    protected $queryString = [
        'search' => ['except' => ''],
        'filter_status' => ['except' => ''],
    ];

    public function createInvoice()
    {
        $this->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        $tenant = Tenant::find($this->tenant_id);

        SubscriptionInvoice::create([
            'tenant_id' => $this->tenant_id,
            'plan_id' => $tenant->plan_id,
            'number' => 'SA-' . date('Ymd') . strtoupper(Str::random(4)),
            'amount' => $this->amount,
            'status' => 'unpaid',
            'due_date' => $this->due_date,
            'notes' => $this->notes,
        ]);

        $this->reset(['tenant_id', 'amount', 'due_date', 'notes']);
        $this->dispatch('close-modal');
        session()->flash('message', 'Factura generada exitosamente.');
    }

    public function markAsPaid($id)
    {
        $invoice = SubscriptionInvoice::findOrFail($id);
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
        session()->flash('message', 'Factura marcadas como pagada.');
    }

    public function render()
    {
        $query = SubscriptionInvoice::with(['tenant', 'plan'])
            ->whereHas('tenant', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });

        if ($this->filter_status) {
            $query->where('status', $this->filter_status);
        }

        $stats = [
            'total_receivable' => SubscriptionInvoice::where('status', 'unpaid')->sum('amount'),
            'paid_this_month' => SubscriptionInvoice::where('status', 'paid')->whereMonth('paid_at', now()->month)->sum('amount'),
            'overdue_count' => SubscriptionInvoice::where('status', 'unpaid')->where('due_date', '<', now()->today())->count(),
            'pending_count' => SubscriptionInvoice::where('status', 'unpaid')->count(),
        ];

        return view('livewire.super-admin.billing-management', [
            'invoices' => $this->applySorting($query)->paginate(10),
            'tenants' => Tenant::all(),
            'stats' => $stats
        ])->layout('components.super-admin-layout');
    }
}
