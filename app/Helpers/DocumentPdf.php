<?php

namespace App\Helpers;

use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Tenant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class DocumentPdf
{
    /**
     * Convierte el logo del tenant a base64 para renderizar en PDF de forma fiable.
     */
    public static function logoBase64(?Tenant $tenant): ?string
    {
        if (! $tenant) {
            return null;
        }

        $logoUrl = $tenant->theme_config_json['logo_url'] ?? null;
        if (! $logoUrl) {
            return null;
        }

        try {
            $logoData = null;

            if (str_contains($logoUrl, 'localhost') || str_contains($logoUrl, '127.0.0.1')) {
                $parsed = parse_url($logoUrl, PHP_URL_PATH);
                $localPath = public_path($parsed);
                if ($parsed && file_exists($localPath)) {
                    $logoData = file_get_contents($localPath);
                }
            }

            if (! $logoData) {
                $logoData = @file_get_contents($logoUrl);
            }

            if ($logoData) {
                $type = pathinfo($logoUrl, PATHINFO_EXTENSION);

                return 'data:image/'.($type ?: 'png').';base64,'.base64_encode($logoData);
            }
        } catch (\Throwable $e) {
            Log::warning('Could not convert logo to base64: '.$e->getMessage());
        }

        return null;
    }

    public static function renderInvoicePdf(Invoice $invoice)
    {
        $logoBase64 = self::logoBase64($invoice->tenant);

        return Pdf::loadView('billing.invoice-pdf', compact('invoice', 'logoBase64'));
    }

    public static function renderQuotationPdf(Quotation $quotation)
    {
        $tenant = $quotation->tenant;
        $logoBase64 = self::logoBase64($tenant);
        $currency = $tenant->settings_json['currency'] ?? 'USD';

        return Pdf::loadView('billing.quotation-pdf', compact('quotation', 'logoBase64', 'currency'));
    }
}
