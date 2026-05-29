<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use App\Models\PaymentProof;
use App\Models\Invoice;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class PaymentApprovals extends Component
{
    use WithPagination;

    public $selected_proof;
    public $rejection_reason;

    public function selectProof($id)
    {
        $this->selected_proof = PaymentProof::with('invoice.customer.user')->find($id);
    }

    public function approve($id)
    {
        $proof = PaymentProof::with('invoice.customer')->find($id);

        DB::transaction(function () use ($proof) {
            $proof->update(['status' => 'approved']);

            $invoice = $proof->invoice;
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            $invoice->customer->decrement('balance', $invoice->total);
        });

        $this->selected_proof = null;
        session()->flash('message', 'Pago aprobado correctamente.');
    }

    public function reject($id)
    {
        $this->validate(['rejection_reason' => 'required|string|min:5']);

        $proof = PaymentProof::find($id);
        $proof->update([
            'status' => 'rejected',
            'rejection_reason' => $this->rejection_reason
        ]);

        $proof->invoice->update(['status' => 'unpaid']);

        $this->selected_proof = null;
        $this->rejection_reason = '';
        session()->flash('message', 'Pago rechazado.');
    }

    public function render()
    {
        return view('livewire.billing.payment-approvals', [
            'proofs' => PaymentProof::with(['invoice.customer.user'])
                ->where('status', 'pending')
                ->latest()
                ->paginate(10)
        ])->layout('components.layouts.app');
    }
}
