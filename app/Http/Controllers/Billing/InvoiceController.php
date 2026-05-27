<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Package;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

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

        // Base64 logo for PDF rendering reliability
        $logoBase64 = null;
        try {
            $logoUrl = $invoice->tenant->theme_config_json['logo_url'] ?? null;
            if ($logoUrl) {
                $logoData = file_get_contents($logoUrl);
                if ($logoData) {
                    $type = pathinfo($logoUrl, PATHINFO_EXTENSION);
                    $logoBase64 = 'data:image/' . ($type ?: 'png') . ';base64,' . base64_encode($logoData);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Could not convert logo to base64: " . $e->getMessage());
        }

        $pdf = Pdf::loadView('billing.invoice-pdf', compact('invoice', 'logoBase64'));
        return $pdf->stream('Factura_' . $invoice->number . '.pdf');
    }

    public function downloadStatement(Customer $customer)
    {
        // Security check
        $user = auth()->user();
        if ($user->role !== 'superadmin' && $user->tenant_id !== $customer->tenant_id) {
            abort(403);
        }

        $customer->load(['user', 'tenant']);

        // Base64 logo for PDF rendering reliability
        $logoBase64 = null;
        try {
            $logoUrl = $customer->tenant->theme_config_json['logo_url'] ?? null;
            if ($logoUrl) {
                $logoData = file_get_contents($logoUrl);
                if ($logoData) {
                    $type = pathinfo($logoUrl, PATHINFO_EXTENSION);
                    $logoBase64 = 'data:image/' . ($type ?: 'png') . ';base64,' . base64_encode($logoData);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Could not convert logo to base64: " . $e->getMessage());
        }

        $invoices = Invoice::where('customer_id', $customer->id)
            ->latest()
            ->get();

        $packages = Package::with('warehouse')
            ->where('customer_id', $customer->id)
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->latest()
            ->get();

        $pdf = Pdf::loadView('billing.statement-pdf', compact('customer', 'invoices', 'packages', 'logoBase64'));

        return $pdf->stream('Estado_Cuenta_' . $customer->box_number . '.pdf');
    }
}
