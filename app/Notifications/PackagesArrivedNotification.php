<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;

class PackagesArrivedNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $invoice;
    protected $packages;

    /**
     * Create a new notification instance.
     *
     * @param Invoice $invoice
     * @param Collection $packages
     */
    public function __construct(Invoice $invoice, Collection $packages)
    {
        $this->invoice = $invoice;
        $this->packages = $packages;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        if ($this->invoice->tenant) {
            $this->invoice->tenant->setMailConfig();
        }

        $this->invoice->load(['customer.user', 'items', 'tenant']);
        $tenantName = $this->invoice->tenant?->name ?? config('app.name');

        // Prepare logo for PDF
        $logoBase64 = null;
        try {
            $logoUrl = $this->invoice->tenant->theme_config_json['logo_url'] ?? null;
            if ($logoUrl) {
                $logoData = file_get_contents($logoUrl);
                if ($logoData) {
                    $type = pathinfo($logoUrl, PATHINFO_EXTENSION);
                    $logoBase64 = 'data:image/' . ($type ?: 'png') . ';base64,' . base64_encode($logoData);
                }
            }
        } catch (\Exception $e) { }
            \Illuminate\Support\Facades\Log::error('Exception in ' . __CLASS__ . '::' . __FUNCTION__ . ' - ' . $e->getMessage() . "\n" . $e->getTraceAsString());

        $pdf = Pdf::loadView('billing.invoice-pdf', [
            'invoice' => $this->invoice,
            'logoBase64' => $logoBase64
        ]);

        $mail = (new MailMessage)
                    ->subject("¡Tus paquetes han llegado! - Factura #{$this->invoice->number}")
                    ->greeting("Hola, {$notifiable->name}")
                    ->line("Te informamos que nuevos paquetes han sido procesados y ya se encuentran disponibles en tu cuenta.")
                    ->line("A continuación, el detalle de la carga recibida:");

        foreach ($this->packages as $pkg) {
            $mail->line("- Tracking: **{$pkg->tracking_number}** | Peso: {$pkg->weight} lbs");
        }

        return $mail->line("Se ha generado la factura **#{$this->invoice->number}** por un total de **$" . number_format($this->invoice->total, 2) . "**.")
                    ->line("Hemos adjuntado tu factura a este correo para tu conveniencia.")
                    ->line("Fecha de vencimiento: " . ($this->invoice->due_date ? $this->invoice->due_date->format('d/m/Y') : 'N/A'))
                    ->action('Ver mi Portal y Pagar', route('customer.invoices'))
                    ->attachData($pdf->output(), "Factura_{$this->invoice->number}.pdf", [
                        'mime' => 'application/pdf',
                    ])
                    ->line('¡Gracias por preferir los servicios de ' . $tenantName . '!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'packages_arrived',
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->number,
            'amount' => $this->invoice->total,
            'package_count' => $this->packages->count(),
            'message' => "Han llegado " . $this->packages->count() . " paquetes nuevos."
        ];
    }
}

