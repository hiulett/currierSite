<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceSent extends Notification
{
    use Queueable;

    protected $invoice;

    /**
     * Create a new notification instance.
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $tenant = $this->invoice->tenant;
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

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('billing.invoice-pdf', [
            'invoice' => $this->invoice,
            'logoBase64' => $logoBase64,
        ]);

        return (new MailMessage)
                    ->subject("Nueva Factura Generada: #{$this->invoice->number}")
                    ->greeting("Hola, {$notifiable->name}")
                    ->line("Se ha generado una nueva factura por tus servicios de logística.")
                    ->line("Número de Factura: #{$this->invoice->number}")
                    ->line("Monto Total: {$this->invoice->currency} " . number_format($this->invoice->total, 2))
                    ->line("Fecha de Vencimiento: " . ($this->invoice->due_date ? $this->invoice->due_date->format('d/m/Y') : 'N/A'))
                    ->action('Ver Factura Online', url("/customer/invoices")) // Assuming this route exists
                    ->line('Gracias por confiar en nosotros.')
                    ->attachData($pdf->output(), 'Factura_' . $this->invoice->number . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->number,
        ];
    }
}
