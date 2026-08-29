<?php

namespace App\Services\Loyalty;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\LoyaltyLevel;
use App\Models\LoyaltyPointsHistory;
use App\Models\LoyaltyRule;
use App\Models\RedemptionHistory;
use App\Models\Reward;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\LevelUpNotification;
use App\Notifications\RedemptionNotification;
use App\Notifications\RewardUnlockedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoyaltyService
{
    /**
     * Reglas de LOGYPUNTOS para un tenant.
     *
     * @return array{enabled: bool, points_per_pound: float, apply_on: string, eligible_rate_types: array}
     */
    protected function rulesFor(int $tenantId): array
    {
        $rule = LoyaltyRule::withoutGlobalScopes()->where('tenant_id', $tenantId)->first();

        if ($rule) {
            return [
                'enabled' => (bool) $rule->enabled,
                'points_per_pound' => (float) $rule->points_per_pound,
                'apply_on' => $rule->apply_on ?? 'invoice',
                'eligible_rate_types' => $rule->eligible_rate_types ?? ['regular'],
            ];
        }

        $tenant = Tenant::find($tenantId);
        $settings = $tenant->settings_json ?? [];

        return [
            'enabled' => (bool) ($settings['loyalty_enabled'] ?? true),
            'points_per_pound' => (float) ($settings['points_per_pound'] ?? 3),
            'apply_on' => 'invoice',
            'eligible_rate_types' => ['regular'],
        ];
    }

    /**
     * Determina si el cliente es elegible para acumular LOGYPUNTOS.
     * Aplica únicamente a tarifas regulares con el flag habilitado.
     */
    public function isEligible(Customer $customer): bool
    {
        if (! $customer->is_loyalty_eligible) {
            return false;
        }

        $rules = $this->rulesFor($customer->tenant_id);

        return $rules['enabled']
            && in_array($customer->rate_type, $rules['eligible_rate_types'], true);
    }

    /**
     * Otorga puntos por libras facturadas al emitir una factura.
     * Solo clientes elegibles. Idempotente por factura.
     *
     * @return int|null Puntos otorgados, o null si no aplica.
     */
    public function awardPointsForInvoice(Invoice $invoice): ?int
    {
        $customer = $invoice->customer;
        if (! $customer) {
            return null;
        }

        if (! $this->isEligible($customer)) {
            return null;
        }

        $rules = $this->rulesFor($invoice->tenant_id);
        if (($rules['apply_on'] ?? 'invoice') !== 'invoice') {
            return null;
        }

        $lbs = (float) $invoice->items()->sum('quantity');
        if ($lbs <= 0) {
            return null;
        }

        $points = (int) ceil($lbs * $rules['points_per_pound']);

        $alreadyAwarded = LoyaltyPointsHistory::withoutGlobalScopes()
            ->where('customer_id', $customer->id)
            ->where('type', 'earn')
            ->where('reference_id', $invoice->id)
            ->where('reference_type', Invoice::class)
            ->exists();

        if ($alreadyAwarded) {
            return null;
        }

        $oldPoints = (int) $customer->points;

        DB::transaction(function () use ($customer, $invoice, $points, $oldPoints) {
            // Acumulación infinita: sin tope ni reinicio al superar el nivel máximo.
            $customer->increment('points', $points);

            LoyaltyPointsHistory::create([
                'tenant_id' => $invoice->tenant_id,
                'customer_id' => $customer->id,
                'points' => $points,
                'type' => 'earn',
                'description' => "Puntos ganados por factura {$invoice->number}",
                'reference_id' => $invoice->id,
                'reference_type' => Invoice::class,
            ]);

            $newLevel = $this->checkLevelUp($customer);

            $this->notifyCustomerProgress($customer, $oldPoints, $oldPoints + $points, $newLevel);

            $this->awardReferrerBonus($customer, $points, $invoice);
        });

        Log::info("LOGYPUNTOS: {$points} pts a cliente {$customer->id} por factura {$invoice->id}");

        return $points;
    }

    /**
     * Ejecuta el canje de una recompensa por el cliente.
     */
    public function redeem(Customer $customer, Reward $reward): RedemptionHistory
    {
        abort_unless($this->isEligible($customer), 403, 'No elegible para LOGYPUNTOS.');
        abort_unless((int) $customer->points >= $reward->points_cost, 422, 'Puntos insuficientes para esta recompensa.');
        abort_unless($reward->is_active && ($reward->stock === null || $reward->stock > 0), 422, 'Recompensa no disponible.');

        return DB::transaction(function () use ($customer, $reward) {
            $customer->decrement('points', $reward->points_cost);

            LoyaltyPointsHistory::create([
                'tenant_id' => $customer->tenant_id,
                'customer_id' => $customer->id,
                'points' => -$reward->points_cost,
                'type' => 'spend',
                'description' => "Canje: {$reward->name}",
                'reference_id' => $reward->id,
                'reference_type' => Reward::class,
            ]);

            $redemption = RedemptionHistory::create([
                'tenant_id' => $customer->tenant_id,
                'customer_id' => $customer->id,
                'reward_id' => $reward->id,
                'points_spent' => $reward->points_cost,
                'reward_name' => $reward->name,
                'free_pounds_granted' => $reward->type === 'free_pounds' ? (float) $reward->value : 0,
                'status' => 'completed',
                'redeemed_at' => now(),
            ]);

            if ($reward->stock !== null) {
                $reward->decrement('stock');
            }

            $this->notifyAdminRedemption($customer, $redemption);

            return $redemption;
        });
    }

    /**
     * Otorga el bono de referido (10% de los puntos ganados) al cliente referidor.
     * Se ejecuta dentro de la transacción de acumulación de la factura.
     */
    protected function awardReferrerBonus(Customer $customer, int $points, Invoice $invoice): void
    {
        if (! $customer->referrer_id) {
            return;
        }

        $referrer = Customer::withoutGlobalScopes()->find($customer->referrer_id);
        if (! $referrer || ! $this->isEligible($referrer)) {
            return;
        }

        $bonus = max(1, (int) ceil($points * 0.10));

        $referrer->increment('points', $bonus);

        LoyaltyPointsHistory::create([
            'tenant_id' => $invoice->tenant_id,
            'customer_id' => $referrer->id,
            'points' => $bonus,
            'type' => 'earn',
            'description' => "Bono por referido en factura {$invoice->number}",
            'reference_id' => $invoice->id,
            'reference_type' => Invoice::class,
        ]);

        $this->checkLevelUp($referrer);

        Log::info("LOGYPUNTOS: Bono de {$bonus} pts por referido a cliente {$referrer->id}");
    }

    /**
     * Notifica in-app al cliente por subida de nivel o recompensa desbloqueada.
     */
    protected function notifyCustomerProgress(Customer $customer, int $oldPoints, int $newPoints, ?LoyaltyLevel $newLevel): void
    {
        if (! $customer->user) {
            return;
        }

        if ($newLevel) {
            try {
                $customer->user->notify(new LevelUpNotification($newLevel));
            } catch (\Exception $e) {
                Log::error("LOGYPUNTOS: Error notificando subida de nivel a cliente {$customer->id}: ".$e->getMessage());
            }
        }

        $unlockedRewards = Reward::withoutGlobalScopes()
            ->where('tenant_id', $customer->tenant_id)
            ->where('is_active', true)
            ->where('points_cost', '>', $oldPoints)
            ->where('points_cost', '<=', $newPoints)
            ->get();

        foreach ($unlockedRewards as $reward) {
            try {
                $customer->user->notify(new RewardUnlockedNotification($reward));
            } catch (\Exception $e) {
                Log::error("LOGYPUNTOS: Error notificando recompensa desbloqueada a cliente {$customer->id}: ".$e->getMessage());
            }
        }
    }

    /**
     * Alerta a los administradores del tenant sobre un canje ejecutado.
     */
    protected function notifyAdminRedemption(Customer $customer, RedemptionHistory $redemption): void
    {
        $admins = User::withoutGlobalScopes()
            ->where('tenant_id', $customer->tenant_id)
            ->whereIn('role', ['admin', 'superadmin'])
            ->get();

        foreach ($admins as $admin) {
            try {
                $admin->notify(new RedemptionNotification($customer, $redemption));
            } catch (\Exception $e) {
                Log::error("LOGYPUNTOS: Error notificando canje a admin {$admin->id}: ".$e->getMessage());
            }
        }
    }

    /**
     * Progreso del cliente hacia la siguiente recompensa/nivel.
     *
     * @return array{balance: int, currentLevel: ?LoyaltyLevel, nextLevel: ?LoyaltyLevel, nextReward: ?Reward, pointsToNext: ?int, percent: int}
     */
    public function progress(Customer $customer): array
    {
        $currentLevel = LoyaltyLevel::where('tenant_id', $customer->tenant_id)
            ->where('is_active', true)
            ->where('min_points', '<=', $customer->points)
            ->orderBy('min_points', 'desc')
            ->first() ?? $customer->level;

        $nextLevel = LoyaltyLevel::where('tenant_id', $customer->tenant_id)
            ->where('is_active', true)
            ->where('min_points', '>', $customer->points)
            ->orderBy('min_points', 'asc')
            ->first();

        $nextReward = Reward::where('tenant_id', $customer->tenant_id)
            ->where('is_active', true)
            ->where('points_cost', '>', $customer->points)
            ->orderBy('points_cost', 'asc')
            ->first();

        $target = collect([$nextLevel?->min_points, $nextReward?->points_cost])
            ->filter()
            ->min();

        return [
            'balance' => (int) $customer->points,
            'currentLevel' => $currentLevel,
            'nextLevel' => $nextLevel,
            'nextReward' => $nextReward,
            'pointsToNext' => $target !== null ? max(0, $target - (int) $customer->points) : null,
            'percent' => $target !== null ? min(100, (int) round($customer->points / $target * 100)) : 100,
        ];
    }

    /**
     * Verifica y asigna el nuevo nivel según el saldo acumulado.
     */
    public function checkLevelUp(Customer $customer): ?LoyaltyLevel
    {
        $newLevel = LoyaltyLevel::where('tenant_id', $customer->tenant_id)
            ->where('is_active', true)
            ->where('min_points', '<=', $customer->points)
            ->orderBy('min_points', 'desc')
            ->first();

        if ($newLevel && $newLevel->id !== $customer->loyalty_level_id) {
            $customer->update(['loyalty_level_id' => $newLevel->id]);
            Log::info("LOGYPUNTOS: Cliente {$customer->id} subió al nivel {$newLevel->name}");

            return $newLevel;
        }

        return null;
    }
}
