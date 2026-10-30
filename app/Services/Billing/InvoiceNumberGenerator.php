<?php

namespace App\Services\Billing;

use App\Models\Invoice;
use Carbon\CarbonInterface;
use Illuminate\Database\QueryException;
use InvalidArgumentException;

/**
 * Genera números de factura únicos por tenant con secuencia diaria.
 *
 * Formato: INV-{Ymd}-{0001}. El número deja de depender de rand(), que
 * provocaba colisiones con el índice único de la tabla invoices.
 */
class InvoiceNumberGenerator
{
    private const MAX_ATTEMPTS = 5;

    private const SEQUENCE_PADDING = 4;

    /**
     * Crea una factura asignando un número único, reintentando si otro
     * proceso insertó el mismo número entre la lectura y el insert.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes, ?CarbonInterface $date = null): Invoice
    {
        $tenantId = (int) ($attributes['tenant_id'] ?? 0);

        if ($tenantId <= 0) {
            throw new InvalidArgumentException('Se requiere tenant_id para generar el número de factura.');
        }

        for ($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {
            $attributes['number'] = $this->next($tenantId, $date);

            try {
                return Invoice::create($attributes);
            } catch (QueryException $e) {
                if ($attempt === self::MAX_ATTEMPTS || ! $this->isDuplicateNumber($e)) {
                    throw $e;
                }
            }
        }

        // Inalcanzable: el bucle lanza o retorna en cada iteración.
        throw new \RuntimeException('No se pudo generar un número de factura único.');
    }

    /**
     * Devuelve el siguiente número disponible para el tenant en la fecha dada.
     */
    public function next(int $tenantId, ?CarbonInterface $date = null): string
    {
        $prefix = 'INV-'.($date ?? now())->format('Ymd').'-';

        $lastNumber = Invoice::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('number', 'like', $prefix.'%')
            ->orderByDesc('number')
            ->value('number');

        $sequence = 1;

        if (is_string($lastNumber) && str_starts_with($lastNumber, $prefix)) {
            $sequence = ((int) substr($lastNumber, strlen($prefix))) + 1;
        }

        return $prefix.str_pad((string) $sequence, self::SEQUENCE_PADDING, '0', STR_PAD_LEFT);
    }

    private function isDuplicateNumber(QueryException $e): bool
    {
        $message = strtolower($e->getMessage());

        return (string) $e->getCode() === '23000'
            && str_contains($message, 'number')
            && (str_contains($message, 'unique') || str_contains($message, 'duplicate'));
    }
}
