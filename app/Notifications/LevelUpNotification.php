<?php

namespace App\Notifications;

use App\Models\LoyaltyLevel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LevelUpNotification extends Notification
{
    use Queueable;

    public function __construct(public LoyaltyLevel $level) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'level' => $this->level->name,
            'free_pounds' => $this->level->free_pounds,
            'icon' => $this->level->icon,
            'color' => $this->level->color,
            'url' => route('customer.rewards'),
        ];
    }
}
