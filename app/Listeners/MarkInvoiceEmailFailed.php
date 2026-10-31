<?php

namespace App\Listeners;

use App\Notifications\InvoiceSent;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Guarda el motivo del fallo del correo de la factura para que el operador
 * pueda verlo en el listado (badge "✉ Error") en lugar de que el envío
 * falle en silencio dentro de la cola.
 */
class MarkInvoiceEmailFailed
{
    public function handle(NotificationFailed $event): void
    {
        if ($event->channel !== 'mail' || ! $event->notification instanceof InvoiceSent) {
            return;
        }

        try {
            $invoice = $event->notification->invoice();

            $exception = $event->data['exception'] ?? null;
            $message = $exception instanceof \Throwable
                ? $exception->getMessage()
                : 'Error desconocido al enviar el correo.';

            $invoice->forceFill([
                'email_error' => Str::limit($message, 500),
            ])->save();

            Log::error('Fallo el envío del correo de la factura '.$invoice->number, [
                'tenant_id' => $invoice->tenant_id,
                'invoice_id' => $invoice->id,
                'error' => $message,
            ]);
        } catch (\Throwable $e) {
            Log::error('No se pudo registrar el fallo del correo de la factura: '.$e->getMessage());
        }
    }
}
