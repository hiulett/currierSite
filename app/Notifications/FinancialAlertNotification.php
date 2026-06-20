<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FinancialAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $type;
    public $message;
    public $package_id;
    public $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($type, $message, $package_id = null, $data = [])
    {
        $this->type = $type; // 'leak', 'price_hike', 'discrepancy'
        $this->message = $message;
        $this->package_id = $package_id;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Keeping it simple and cost-free
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'message' => $this->message,
            'package_id' => $this->package_id,
            'data' => $this->data,
            'icon' => $this->getIcon(),
            'color' => $this->getColor(),
        ];
    }

    private function getIcon()
    {
        return match($this->type) {
            'leak' => 'trending-down',
            'price_hike' => 'alert-triangle',
            'discrepancy' => 'file-text',
            default => 'bell',
        };
    }

    private function getColor()
    {
        return match($this->type) {
            'leak' => 'danger',
            'price_hike' => 'warning',
            'discrepancy' => 'info',
            default => 'primary',
        };
    }
}
