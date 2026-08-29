<?php

namespace App\Notifications;

use App\Models\Customer;
use App\Models\RedemptionHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RedemptionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Customer $customer,
        public RedemptionHistory $redemption,
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return app()->environment('testing') ? ['database'] : ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Canje de LOGYPUNTOS ejecutado')
            ->greeting('Hola '.$notifiable->name)
            ->line('El cliente '.($this->customer->user?->name ?? $this->customer->box_number).' ('.$this->customer->box_number.') canjeó "'.$this->redemption->reward_name.'" por '.$this->redemption->points_spent.' puntos.')
            ->line('Libras gratis aplicadas: '.number_format($this->redemption->free_pounds_granted, 0))
            ->action('Ver canjes', route('billing.redemptions'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'customer_name' => $this->customer->user?->name,
            'box_number' => $this->customer->box_number,
            'reward_name' => $this->redemption->reward_name,
            'points_spent' => $this->redemption->points_spent,
            'free_pounds_granted' => $this->redemption->free_pounds_granted,
            'redeemed_at' => $this->redemption->redeemed_at?->toDateTimeString(),
            'url' => route('billing.redemptions'),
        ];
    }
}
