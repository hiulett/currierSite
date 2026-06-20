<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function download(Quotation $quotation)
    {
        $quotation = Quotation::withoutGlobalScope('tenant')->find($quotation->id);

        $user = auth()->user();
        if ($user->role !== 'superadmin' && $user->tenant_id !== $quotation->tenant_id) {
            abort(403);
        }

        $quotation->load(['customer.user', 'items', 'tenant']);

        // Base64 logo for PDF rendering reliability
        $logoBase64 = null;
        try {
            $logoUrl = $quotation->tenant->theme_config_json['logo_url'] ?? null;
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

        // Get Currency
        $currency = $quotation->tenant->settings_json['currency'] ?? 'USD';

        $pdf = Pdf::loadView('billing.quotation-pdf', compact('quotation', 'logoBase64', 'currency'));
        return $pdf->stream('Cotizacion_' . $quotation->number . '.pdf');
    }
}
