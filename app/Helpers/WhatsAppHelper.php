<?php

namespace App\Helpers;

class WhatsAppHelper
{
    /**
     * Genera un enlace de WhatsApp (wa.me) con un mensaje pre-formateado.
     */
    public static function getLink($phone, $message)
    {
        // Limpiar el teléfono (dejar solo números)
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Codificar el mensaje para URL
        $encodedMessage = urlencode($message);

        return "https://wa.me/{$phone}?text={$encodedMessage}";
    }

    /**
     * Mensaje pre-formateado para consulta de paquete.
     */
    public static function getPackageQueryMessage($tracking)
    {
        return "Hola, quisiera consultar el estado de mi paquete con tracking: {$tracking}";
    }

    /**
     * Mensaje pre-formateado para soporte de pago rechazado.
     */
    public static function getPaymentSupportMessage($invoiceNumber)
    {
        return "Hola, recibí una notificación de pago rechazado para la factura {$invoiceNumber}. Quisiera más información.";
    }
}
