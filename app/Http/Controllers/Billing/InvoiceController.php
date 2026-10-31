<?php

namespace App\Http\Controllers\Billing;

use App\Helpers\DocumentPdf;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function download(Invoice $invoice)
    {
        // ... (existing code)
        $invoice = Invoice::withoutGlobalScope('tenant')->find($invoice->id);

        $user = auth()->user();
        if ($user->role !== 'superadmin' && $user->tenant_id !== $invoice->tenant_id) {
            abort(403);
        }

        $invoice->load(['customer.user', 'items', 'tenant']);

        $logoBase64 = DocumentPdf::logoBase64($invoice->tenant);

        $pdf = Pdf::loadView('billing.invoice-pdf', compact('invoice', 'logoBase64'));

        return $pdf->stream('Factura_'.$invoice->number.'.pdf');
    }

    public function downloadStatement(Customer $customer)
    {
        // Security check
        $user = auth()->user();
        if ($user->role !== 'superadmin' && $user->tenant_id !== $customer->tenant_id) {
            abort(403);
        }

        $customer->load(['user', 'tenant']);

        $logoBase64 = DocumentPdf::logoBase64($customer->tenant);

        $invoices = Invoice::where('customer_id', $customer->id)
            ->latest()
            ->get();

        $packages = Package::with('warehouse')
            ->where('customer_id', $customer->id)
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->latest()
            ->get();

        $pdf = Pdf::loadView('billing.statement-pdf', compact('customer', 'invoices', 'packages', 'logoBase64'));

        return $pdf->stream('Estado_Cuenta_'.$customer->box_number.'.pdf');
    }
}
