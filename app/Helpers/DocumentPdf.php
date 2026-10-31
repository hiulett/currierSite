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
     *
     * Prioriza el archivo local del tenant (public/logos/{subdominio}.*) sobre
     * la URL de theme_config_json, para que el logo del documento sea el mismo
     * que el del resto de la aplicación.
     */
    public static function logoBase64(?Tenant $tenant): ?string
    {
        if (! $tenant) {
            return null;
        }

        $logoUrl = $tenant->getLogoUrl();
        if (! $logoUrl) {
            return null;
        }

        try {
            $path = parse_url($logoUrl, PHP_URL_PATH);
            $localPath = $path ? public_path(ltrim($path, '/')) : null;

            $logoData = null;
            if ($localPath && is_file($localPath)) {
                $logoData = file_get_contents($localPath);
            }

            if (! $logoData) {
                $logoData = @file_get_contents($logoUrl);
            }

            if (! $logoData) {
                return null;
            }

            $extension = strtolower(pathinfo($localPath ?: (string) $path, PATHINFO_EXTENSION));

            $mime = match ($extension) {
                'jpg', 'jpeg' => 'jpeg',
                'svg' => 'svg+xml',
                'webp' => 'webp',
                'gif' => 'gif',
                default => 'png',
            };

            return 'data:image/'.$mime.';base64,'.base64_encode($logoData);
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
