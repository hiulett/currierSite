<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuotationSent extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $quotation;

    /**
     * Create a new message instance.
     */
    public function __construct(\App\Models\Quotation $quotation)
    {
        $this->quotation = $quotation;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $tenant = $this->quotation->tenant;
        if ($tenant) {
            $tenant->setMailConfig();
        }
        return new Envelope(
            subject: 'Cotización #' . $this->quotation->number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $tenant = $this->quotation->tenant;
        if ($tenant) {
            $tenant->setMailConfig();
        }
        $template = $tenant->settings_json['quotation_email_template'] ?? "Hola {nombre_cliente},\n\nLe hemos generado la cotización #{numero_documento}.\n\nAdjunto a este correo encontrará el documento en formato PDF con todos los detalles y condiciones de los servicios cotizados.\n\nMonto Total: {monto_total}\n\nSi tiene alguna duda o requiere asistencia adicional, no dude en contactarnos.\n\nGracias por su preferencia,\n{nombre_empresa}";
        
        $replacements = [
            '{nombre_cliente}' => $this->quotation->customer->user->name ?? 'Cliente',
            '{numero_documento}' => $this->quotation->number,
            '{monto_total}' => ($tenant->settings_json['currency'] ?? 'USD') . ' ' . number_format($this->quotation->total, 2),
            '{fecha_vencimiento}' => '',
            '{nombre_empresa}' => $tenant->name ?? config('app.name'),
        ];
        
        $body = str_replace(array_keys($replacements), array_values($replacements), $template);

        return new Content(
            markdown: 'emails.quotation',
            with: [
                'body' => $body,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $tenant = $this->quotation->tenant;
        if ($tenant) {
            $tenant->setMailConfig();
        }
        $logoBase64 = null;
        try {
            $logoUrl = $tenant->theme_config_json['logo_url'] ?? null;
            if ($logoUrl) {
                $logoData = file_get_contents($logoUrl);
                if ($logoData) {
                    $type = pathinfo($logoUrl, PATHINFO_EXTENSION);
                    $logoBase64 = 'data:image/' . ($type ?: 'png') . ';base64,' . base64_encode($logoData);
                }
            }
        } catch (\Exception $e) {}

        $currency = $tenant->settings_json['currency'] ?? 'USD';
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('billing.quotation-pdf', [
            'quotation' => $this->quotation,
            'logoBase64' => $logoBase64,
            'currency' => $currency
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Cotizacion_' . $this->quotation->number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
