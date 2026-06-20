@component('mail::message')
# Cotización de Servicios Logísticos

Hola **{{ $quotation->customer->user->name }}**,

Le hemos generado la cotización **#{{ $quotation->number }}**.

Adjunto a este correo encontrará el documento en formato PDF con todos los detalles y condiciones de los servicios cotizados.

**Resumen:**
- **Cotización #:** {{ $quotation->number }}
- **Fecha:** {{ $quotation->created_at->format('d/m/Y') }}
- **Monto Total:** {{ $quotation->tenant->settings_json['currency'] ?? 'USD' }} {{ number_format($quotation->total, 2) }}

Si tiene alguna duda o requiere asistencia adicional, no dude en contactarnos.

Gracias por su preferencia,<br>
{{ $quotation->tenant->name ?? config('app.name') }}
@endcomponent
