<?php

namespace App\Notifications;

use App\Models\Package;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PackageReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $package;

    /**
     * Create a new notification instance.
     */
    public function __construct(Package $package)
    {
        $this->package = $package;
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
        if ($this->package->tenant) {
            $this->package->tenant->setMailConfig();
        }
        $tenantName = $this->package->tenant?->name ?? config('app.name');

        return (new MailMessage)
                    ->subject('¡Paquete Recibido! - ' . $tenantName)
                    ->greeting('Hola ' . $notifiable->name . ',')
                    ->line('Hemos recibido un nuevo paquete en nuestra bodega con el siguiente detalle:')
                    ->line('**Tracking:** ' . $this->package->tracking_number)
                    ->line('**Descripción:** ' . ($this->package->description ?? 'N/A'))
                    ->line('**Peso:** ' . $this->package->weight . ' lbs')
                    ->action('Ver mi casillero', route('customer.dashboard'))
                    ->line('Gracias por utilizar nuestros servicios.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'package_id' => $this->package->id,
            'tracking_number' => $this->package->tracking_number,
            'message' => 'Paquete ' . $this->package->tracking_number . ' recibido en bodega.',
            'type' => 'package_received'
        ];
    }
}

