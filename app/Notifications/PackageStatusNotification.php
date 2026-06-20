<?php

namespace App\Notifications;

use App\Models\Package;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PackageStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $package;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(Package $package, string $status)
    {
        $this->package = $package;
        $this->status = $status;
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

        $statusMessages = [
            'in_transit' => [
                'subject' => 'Paquete en Tránsito 🚢 - ' . $this->package->tracking_number,
                'title' => '¡Tu paquete viene en camino!',
                'line' => 'Te informamos que tu paquete con tracking ' . $this->package->tracking_number . ' ha sido despachado y está en tránsito hacia su destino final.'
            ],
            'arrived' => [
                'subject' => 'Llegó al País 🛬 - ' . $this->package->tracking_number,
                'title' => '¡Tu paquete ha llegado!',
                'line' => 'Buenas noticias. Tu paquete con tracking ' . $this->package->tracking_number . ' ya se encuentra en el país y está siendo procesado en nuestra bodega local.'
            ],
            'ready_for_pickup' => [
                'subject' => 'Listo para Retiro 📦 - ' . $this->package->tracking_number,
                'title' => '¡Ya puedes pasar por tu paquete!',
                'line' => 'Tu carga con tracking ' . $this->package->tracking_number . ' está lista para ser retirada en nuestra oficina. ¡Te esperamos!'
            ],
            'delivered' => [
                'subject' => 'Paquete Entregado ✅ - ' . $this->package->tracking_number,
                'title' => 'Confirmación de Entrega',
                'line' => 'Confirmamos que el paquete con tracking ' . $this->package->tracking_number . ' ha sido entregado exitosamente.'
            ],
            'out_for_delivery' => [
                'subject' => 'En Ruta de Entrega 🚚 - ' . $this->package->tracking_number,
                'title' => '¡Tu paquete va hacia tu casa!',
                'line' => 'Tu paquete con tracking ' . $this->package->tracking_number . ' ha sido asignado a un repartidor y será entregado pronto.'
            ],
        ];

        $data = $statusMessages[$this->status] ?? [
            'subject' => 'Actualización de Paquete - ' . $this->package->tracking_number,
            'title' => 'Estado actualizado: ' . str_replace('_', ' ', $this->status),
            'line' => 'Tu paquete con tracking ' . $this->package->tracking_number . ' ha cambiado su estado a ' . str_replace('_', ' ', $this->status) . '.'
        ];

        return (new MailMessage)
                    ->subject($data['subject'])
                    ->greeting("Hola, {$notifiable->name}")
                    ->line($data['title'])
                    ->line($data['line'])
                    ->line('Detalles:')
                    ->line('• Peso: ' . $this->package->weight . ' lbs')
                    ->line('• Descripción: ' . ($this->package->description ?? 'Sin descripción'))
                    ->action('Rastrear Paquete', url('/customer/packages'))
                    ->line('Gracias por elegirnos.');
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
            'status' => $this->status,
        ];
    }
}
