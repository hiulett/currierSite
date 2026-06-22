<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Package;

class PackageReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $package;

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
        return ['mail'];
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
            ->subject('Paquete Recibido - ' . $this->package->tracking_number)
            ->greeting('¡Hola, ' . $notifiable->name . '!')
            ->line('Hemos recibido un nuevo paquete para ti en nuestra bodega.')
            ->line('Tracking: ' . $this->package->tracking_number)
            ->line('Peso: ' . $this->package->weight . ' kg')
            ->action('Ver mi Casillero', url('/portal/paquetes'))
            ->line('Gracias por confiar en ' . $tenantName . '.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
