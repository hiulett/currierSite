<?php

namespace App\Livewire\Customer;

use App\Notifications\LevelUpNotification;
use App\Notifications\RewardUnlockedNotification;
use Livewire\Component;

class LoyaltyAlerts extends Component
{
    public $alerts = [];

    public function mount()
    {
        $user = auth()->user();
        if (! $user) {
            return;
        }

        $this->alerts = $user->notifications()
            ->whereIn('type', [LevelUpNotification::class, RewardUnlockedNotification::class])
            ->whereNull('read_at')
            ->latest()
            ->take(2)
            ->get()
            ->values()
            ->all();
    }

    public function markRead($id)
    {
        auth()->user()->notifications()->where('id', $id)->update(['read_at' => now()]);

        $this->alerts = collect($this->alerts)
            ->reject(fn ($alert) => $alert->id === $id)
            ->values()
            ->all();
    }

    public function render()
    {
        return view('livewire.customer.loyalty-alerts');
    }
}
