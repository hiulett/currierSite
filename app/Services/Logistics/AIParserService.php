<?php

namespace App\Services\Logistics;

use Smalot\PdfParser\Parser;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Facades\Log;

class AIParserService
{
    protected $pdfParser;

    public function __construct()
    {
        $this->pdfParser = new Parser();
    }

    /**
     * Procesa un archivo (PDF o Imagen) extrae items (tracking, peso, dims, precio) y nº factura.
     */
    public function parseGlobalExpressInvoice(string $filePath): array
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $fullText = "";
        $items = [];

        try {
            if ($extension === 'pdf') {
                $pdf = $this->pdfParser->parseFile($filePath);
                $pages = $pdf->getPages();

                foreach ($pages as $page) {
                    $pageText = $page->getText();

                    // 1. RECONSTRUCCIÓN: Unir trackings que el PDF corta en 2 líneas
                    $pageText = preg_replace_callback('/(\d{15,30})\s*\n\s*(\d{5,15})/', function($m) {
                        return $m[1] . $m[2];
                    }, $pageText);

                    // 2. UNIÓN DE DATOS: Si las dimensiones están en la línea de abajo, subirlas
                    $pageText = preg_replace('/\n\s*([0-9]+[\.,][0-9]{2})/', ' $1', $pageText);

                    $fullText .= $pageText . "\n";
                    $items = array_merge($items, $this->extractDataFromTable($pageText));
                }

                if (empty($items) && strlen(trim($fullText)) < 100) {
                    $fullText = $this->tryOCRFallback($filePath);
                    $items = $this->extractDataFromTable($fullText);
                }

            } else {
                $fullText = $this->tryOCRFallback($filePath);
                $items = $this->extractDataFromTable($fullText);
            }

            return [
                'items' => collect($items)->unique('tracking')->values()->toArray(),
                'invoice_number' => $this->extractInvoiceNumber($fullText),
                'raw_text' => $fullText,
                'error' => empty($items) && str_contains($fullText, 'ERROR:') ? $fullText : null
            ];

        } catch (\Exception $e) {
            Log::error("Error en AIParserService: " . $e->getMessage());
            return [
                'items' => [],
                'invoice_number' => null,
                'raw_text' => '',
                'error' => $e->getMessage()
            ];
        }
    }

    protected function extractDataFromTable(string $text): array
    {
        $keyword = 'Código';
        $tableStart = mb_stripos($text, $keyword);
        if ($tableStart === false) $tableStart = mb_stripos($text, 'Codigo');

        if ($tableStart !== false) {
            $text = mb_substr($text, $tableStart);
        }

        $lines = explode("\n", $text);
        $extracted = [];

        $patterns = [
            'UPS' => '/\b(1Z[A-Z0-9]{16})\b/i',
            'FedEx' => '/\b([0-9]{12}|[0-9]{15})\b/',
            'USPS' => '/\b(9[0-9]{19,33})\b/',
            'Internal' => '/\b(WH[0-9]{6}\s+[0-9]-[0-9])\b/i',
            'Courier' => '/\b(TBA[A-Z0-9]{10,20})\b/i',
            'Long' => '/\b(420331[0-9]{20,35})\b/'
        ];

        foreach ($lines as $line) {
            $tracking = null;
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $line, $matches)) {
                    $tracking = strtoupper(trim($matches[0]));
                    break;
                }
            }

            if ($tracking) {
                preg_match_all('/([0-9]+[\.,][0-9]{1,2})/', $line, $numbers);
                $nums = $numbers[0] ?? [];

                if (count($nums) >= 4) {
                    $extracted[] = [
                        'tracking' => $tracking,
                        'length' => (float)str_replace(',', '.', $nums[0] ?? 1),
                        'height' => (float)str_replace(',', '.', $nums[1] ?? 1),
                        'width'  => (float)str_replace(',', '.', $nums[2] ?? 1),
                        'weight' => (float)str_replace(',', '.', $nums[3] ?? 0),
                        'volume' => (float)str_replace(',', '.', $nums[4] ?? 0),
                        'price'  => (float)str_replace(',', '.', $nums[5] ?? 0),
                    ];
                }
            }
        }

        return $extracted;
    }

    protected function extractInvoiceNumber(string $text): ?string
    {
        $invoicePatterns = [
            '/N[º°º]\s*([0-9]+)/u',
            '/Factura\s*N[º°º]?\s*([0-9]+)/ui',
            '/Invoice\s*#?\s*([A-Z0-9-]{4,})/i',
            '/Bill\s*#?\s*([A-Z0-9-]{4,})/i',
            '/Reference\s*#?\s*([A-Z0-9-]{4,})/i',
            '/Order\s*#?\s*([A-Z0-9-]{4,})/i',
        ];

        foreach ($invoicePatterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $found = trim($matches[1]);
                if (strtolower($found) !== 'cliente' && strlen($found) > 2) {
                    return $found;
                }
            }
        }
        return null;
    }

    private function tryOCRFallback(string $filePath): string
    {
        if (!class_exists('thiagoalessio\TesseractOCR\TesseractOCR')) {
            return "ERROR: Tesseract no instalado localmente.";
        }
        try {
            $ocr = new TesseractOCR($filePath);
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $winPath = 'C:\Program Files\Tesseract-OCR\tesseract.exe';
                if (file_exists($winPath)) $ocr->executable($winPath);
            }
            return $ocr->lang('spa', 'eng')->run();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Exception in ' . __CLASS__ . '::' . __FUNCTION__ . ' - ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return "ERROR OCR: " . $e->getMessage();
        }
    }
}
