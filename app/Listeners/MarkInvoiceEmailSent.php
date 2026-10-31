<?php

namespace App\Listeners;

use App\Notifications\InvoiceSent;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Log;

/**
 * Marca la factura como enviada solo cuando el canal de correo confirmó
 * el envío. Antes se marcaba al encolar, por lo que la UI mostraba
 * "Enviado" aunque el job fallara.
 */
class MarkInvoiceEmailSent
{
    public function handle(NotificationSent $event): void
    {
        if ($event->channel !== 'mail' || ! $event->notification instanceof InvoiceSent) {
            return;
        }

        try {
            $invoice = $event->notification->invoice();
            $invoice->forceFill([
                'email_sent_at' => now(),
                'email_error' => null,
            ])->save();
        } catch (\Throwable $e) {
            Log::error('No se pudo marcar la factura como enviada: '.$e->getMessage());
        }
    }
}
