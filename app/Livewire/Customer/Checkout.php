<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\PaymentProof;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class Checkout extends Component
{
    use WithFileUploads;

    public $invoice;
    public $delivery_method = 'pickup';
    public $payment_method = 'card';
    public $payment_proof;
    public $notes;

    public function mount($invoice_id)
    {
        $this->invoice = Invoice::with('items.package')->findOrFail($invoice_id);

        if ($this->invoice->customer_id !== auth()->user()->customer->id) {
            abort(403);
        }

        $firstItem = $this->invoice->items->first();
        if ($firstItem && $firstItem->package) {
            $this->delivery_method = $firstItem->package->delivery_type ?: 'pickup';
        }
    }

    public function processCheckout()
    {
        if (in_array($this->payment_method, ['yappy', 'ach'])) {
            $this->validate([
                'payment_proof' => 'required|image|max:5120',
            ]);

            DB::transaction(function () {
                $path = $this->payment_proof->store('payment_proofs', 'public');

                $this->invoice->update([
                    'status' => 'pending',
                ]);

                PaymentProof::create([
                    'tenant_id' => $this->invoice->tenant_id,
                    'invoice_id' => $this->invoice->id,
                    'file_path' => $path,
                    'method' => $this->payment_method,
                    'status' => 'pending',
                ]);

                $this->updatePackagesDeliveryMethod();
            });

            session()->flash('message', 'Comprobante subido. Tu pago está en revisión.');
            return redirect()->route('customer.invoices');

        } elseif ($this->payment_method === 'cod') {
            DB::transaction(function () {
                $this->invoice->update(['status' => 'unpaid']);
                $this->delivery_method = 'home_delivery'; // Force home delivery for COD
                $this->updatePackagesDeliveryMethod();
            });

            session()->flash('message', 'Has seleccionado Pago Contra Entrega. Tu paquete será despachado.');
            return redirect()->route('customer.packages');

        } elseif ($this->payment_method === 'card') {
            $this->updatePackagesDeliveryMethod();
            return redirect()->route('payment.checkout', $this->invoice);
        } elseif ($this->payment_method === 'paypal') {
            $this->updatePackagesDeliveryMethod();
            return redirect()->route('payment.paypal', $this->invoice);
        }
    }

    protected function updatePackagesDeliveryMethod()
    {
        foreach ($this->invoice->items as $item) {
            if ($item->package) {
                $item->package->update(['delivery_type' => $this->delivery_method]);
            }
        }
    }

    public function render()
    {
        return view('livewire.customer.checkout')->layout('components.customer-layout');
    }
}
