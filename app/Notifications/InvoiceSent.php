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

        $template = $tenant->settings_json['invoice_email_template'] ?? "Hola {nombre_cliente},\n\nSe ha generado una nueva factura por tus servicios de logística.\n\nNúmero de Factura: #{numero_documento}\nMonto Total: {monto_total}\nFecha de Vencimiento: {fecha_vencimiento}\n\nGracias por confiar en nosotros.\n\nSaludos,\n{nombre_empresa}";
        
        $replacements = [
            '{nombre_cliente}' => $notifiable->name ?? 'Cliente',
            '{numero_documento}' => $this->invoice->number,
            '{monto_total}' => $this->invoice->currency . ' ' . number_format($this->invoice->total, 2),
            '{fecha_vencimiento}' => $this->invoice->due_date ? $this->invoice->due_date->format('d/m/Y') : 'N/A',
            '{nombre_empresa}' => $tenant->name ?? config('app.name'),
        ];
        
        $body = str_replace(array_keys($replacements), array_values($replacements), $template);

        $mailMessage = (new MailMessage)
                    ->subject("Factura #{$this->invoice->number}");
                    
        $lines = explode("\n", $body);
        foreach ($lines as $line) {
            $mailMessage->line(trim($line));
        }

        return $mailMessage->attachData($pdf->output(), 'Factura_' . $this->invoice->number . '.pdf', [
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
