<?php

namespace App\Notifications;

use App\Models\Reward;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RewardUnlockedNotification extends Notification
{
    use Queueable;

    public function __construct(public Reward $reward) {}

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
            'reward' => $this->reward->name,
            'points_cost' => $this->reward->points_cost,
            'url' => route('customer.rewards'),
        ];
    }
}
