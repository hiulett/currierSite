<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use App\Models\LoyaltyLevel;
use App\Models\Reward;
use App\Services\Loyalty\LoyaltyService;
use Livewire\Component;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Rewards extends Component
{
    public $customer;

    public $confirming_reward_id = null;

    public $confirming_reward = null;

    public function mount()
    {
        $user = auth()->user();
        $this->customer = $user->customer
            ?? Customer::where('user_id', $user->id)->first();
    }

    public function confirmRedeem($rewardId)
    {
        $reward = Reward::where('tenant_id', $this->customer->tenant_id)
            ->where('is_active', true)
            ->find($rewardId);

        if (! $reward) {
            return;
        }

        $this->confirming_reward_id = $reward->id;
        $this->confirming_reward = $reward;
    }

    public function cancelRedeem()
    {
        $this->confirming_reward_id = null;
        $this->confirming_reward = null;
    }

    public function redeem()
    {
        if (! $this->confirming_reward) {
            return;
        }

        try {
            $redemption = app(LoyaltyService::class)->redeem($this->customer, $this->confirming_reward);
            $this->customer->refresh();
            $this->cancelRedeem();

            session()->flash('message', '¡Canje exitoso! Canjeaste "'.$redemption->reward_name.'" y tu saldo quedó en '.number_format($this->customer->points).' puntos.');
        } catch (HttpException $e) {
            $this->cancelRedeem();
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        if (! $this->customer) {
            return view('livewire.customer.dashboard-error', [
                'title' => 'Perfil no encontrado',
                'message' => 'Tu usuario no tiene un perfil de cliente asociado.',
            ])->layout('components.customer-layout');
        }

        $service = app(LoyaltyService::class);
        $progress = $service->progress($this->customer);

        $rewards = Reward::where('tenant_id', $this->customer->tenant_id)
            ->where('is_active', true)
            ->orderBy('points_cost', 'asc')
            ->get()
            ->map(function (Reward $reward) {
                $reward->is_claimable = $this->customer->points >= $reward->points_cost
                    && ($reward->stock === null || $reward->stock > 0);

                return $reward;
            });

        return view('livewire.customer.rewards', [
            'progress' => $progress,
            'rewards' => $rewards,
            'levels' => LoyaltyLevel::where('tenant_id', $this->customer->tenant_id)
                ->where('is_active', true)
                ->orderBy('min_points', 'asc')
                ->get(),
            'pointsHistory' => $this->customer->pointsHistory()->latest()->take(15)->get(),
            'redemptions' => $this->customer->redemptions()->latest()->take(15)->get(),
        ])->layout('components.customer-layout');
    }
}
