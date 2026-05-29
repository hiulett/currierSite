<?php

namespace App\Services\Logistics;

use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Log;

class ManifestParserService
{
    protected $parser;

    public function __construct()
    {
        $this->parser = new Parser();
    }

    /**
     * Procesa un PDF y extrae los Tracking IDs, peso, dimensiones y número de factura detectados.
     */
    public function parsePdf(string $filePath): array
    {
        try {
            $pdf = $this->parser->parseFile($filePath);
            $pages = $pdf->getPages();
            $fullText = "";
            $allData = [];

            foreach ($pages as $page) {
                $text = $page->getText();
                $fullText .= $text . "\n";

                // Procesar página por página para mantener contexto de tablas
                $pageData = $this->extractDataFromTable($text);
                $allData = array_merge($allData, $pageData);
            }

            return [
                'trackings' => collect($allData)->unique('tracking')->values()->toArray(),
                'invoice_number' => $this->extractInvoiceNumber($fullText)
            ];
        } catch (\Exception $e) {
            Log::error("Error al procesar PDF de manifiesto: " . $e->getMessage());
            return [
                'trackings' => [],
                'invoice_number' => null
            ];
        }
    }

    /**
     * Extrae información estructurada de la tabla (Tracking, Peso, Dims)
     */
    protected function extractDataFromTable(string $text): array
    {
        // Limpiamos la cabecera de la página si existe
        $tableStart = stripos($text, 'Código');
        if ($tableStart !== false) {
            $text = substr($text, $tableStart);
        }

        // Dividir por líneas para procesar registros
        $lines = explode("\n", $text);
        $extracted = [];

        foreach ($lines as $line) {
            // Buscamos patrones de tracking conocidos en la línea
            $tracking = $this->findTrackingInLine($line);

            if ($tracking) {
                // Una vez encontrado el tracking, buscamos los números que le siguen (Largo, Alto, Ancho, Peso)
                // Buscamos secuencias de decimales (ej: 1.00 1.00 1.00 13.00)
                preg_match_all('/([0-9]+\.[0-9]{2})/', $line, $numbers);

                $nums = $numbers[0] ?? [];

                // Según la factura: Largo (0), Alto (1), Ancho (2), Peso (3)
                $extracted[] = [
                    'tracking' => $tracking,
                    'length' => $nums[0] ?? 0,
                    'height' => $nums[1] ?? 0,
                    'width' => $nums[2] ?? 0,
                    'weight' => $nums[3] ?? 0,
                    'carrier_guess' => 'Auto'
                ];
            }
        }

        return $extracted;
    }

    protected function findTrackingInLine(string $line): ?string
    {
        $patterns = [
            'UPS' => '/\b(1Z[A-Z0-9]{16})\b/i',
            'FedEx' => '/\b([0-9]{12}|[0-9]{15})\b/',
            'USPS' => '/\b(9[0-9]{19,33})\b/',
            'Internal' => '/\b(WH[0-9]{6}\s+[0-9]-[0-9])\b/i',
            'Courier' => '/\b(TBA[A-Z0-9]{10,20})\b/i',
            'Long' => '/\b(420331[0-9]{20,30})\b/'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $line, $matches)) {
                return strtoupper(trim($matches[0]));
            }
        }

        return null;
    }

    /**
     * Intenta extraer un número de factura basado en palabras clave.
     */
    protected function extractInvoiceNumber(string $text): ?string
    {
        // Limpiar el texto un poco para manejar caracteres especiales como Nº
        $invoicePatterns = [
            '/N[º°º]\s*([0-9]+)/u',               // Específico para Nº 123456 (como en la imagen)
            '/Factura\s*N[º°º]?\s*([0-9]+)/ui',    // Factura Nº 123456
            '/Invoice\s*#?\s*([A-Z0-9-]{4,})/i',   // Invoice #ABC-123
            '/Bill\s*#?\s*([A-Z0-9-]{4,})/i',
            '/Reference\s*#?\s*([A-Z0-9-]{4,})/i',
            '/Order\s*#?\s*([A-Z0-9-]{4,})/i',
        ];

        foreach ($invoicePatterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $found = trim($matches[1]);
                // Evitar capturar etiquetas comunes como "Cliente"
                if (strtolower($found) !== 'cliente' && strlen($found) > 2) {
                    return $found;
                }
            }
        }

        return null;
    }
}
