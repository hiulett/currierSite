<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $invoice;
    protected $type; // 'before', 'on_due', 'after_7', 'after_30'

    /**
     * Create a new notification instance.
     */
    public function __construct(Invoice $invoice, string $type)
    {
        $this->invoice = $invoice;
        $this->type = $type;
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
        $subject = match($this->type) {
            'before' => "Recordatorio de Pago: Factura #{$this->invoice->number} próxima a vencer",
            'on_due' => "Aviso de Vencimiento: Factura #{$this->invoice->number} vence hoy",
            'after_7' => "URGENTE: Factura #{$this->invoice->number} con 7 días de retraso",
            'after_30' => "AVISO FINAL: Factura #{$this->invoice->number} con 30 días de retraso",
            default => "Recordatorio de Pago: Factura #{$this->invoice->number}"
        };

        $message = (new MailMessage)
                    ->subject($subject)
                    ->greeting("Hola, {$notifiable->name}")
                    ->line("Te escribimos para recordarte sobre el pago de tu factura #{$this->invoice->number}.");

        if ($this->type === 'before') {
            $message->line("Esta factura vencerá el próximo " . ($this->invoice->due_date ? $this->invoice->due_date->format('d/m/Y') : 'N/A') . ".");
        } elseif ($this->type === 'on_due') {
            $message->line("Esta factura vence el día de hoy.");
        } else {
            $message->line("Esta factura se encuentra vencida desde el " . ($this->invoice->due_date ? $this->invoice->due_date->format('d/m/Y') : 'N/A') . ".");
        }

        return $message
                    ->line("Monto total: {$this->invoice->currency} {$this->invoice->total}")
                    ->action('Ver Factura y Pagar', url("/invoices/{$this->invoice->id}"))
                    ->line('Si ya realizaste el pago, por favor ignora este mensaje.');
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
            'type' => $this->type,
        ];
    }
}
