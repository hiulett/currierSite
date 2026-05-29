<?php

namespace App\Services\Loyalty;

use App\Models\Customer;
use App\Models\Package;
use App\Models\LoyaltyLevel;
use Illuminate\Support\Facades\Log;

class LoyaltyService
{
    /**
     * Asigna puntos a un cliente basándose en un paquete entregado.
     */
    public function awardPointsForPackage(Package $package)
    {
        $customer = $package->customer;
        if (!$customer) return;

        $tenant = $customer->tenant;
        $settings = $tenant->settings_json ?? [];

        // Regla base: 1 punto por libra (por defecto)
        $pointsPerPound = $settings['points_per_pound'] ?? 1;

        $basePoints = $package->weight * $pointsPerPound;

        // Aplicar multiplicador del nivel actual
        $multiplier = 1.0;
        if ($customer->level) {
            $multiplier = $customer->level->multiplier;
        }

        $finalPoints = ceil($basePoints * $multiplier);

        if ($finalPoints > 0) {
            $customer->increment('points', $finalPoints);

            \App\Models\LoyaltyPointsHistory::create([
                'tenant_id' => $customer->tenant_id,
                'customer_id' => $customer->id,
                'points' => $finalPoints,
                'type' => 'earn',
                'description' => "Puntos ganados por paquete {$package->tracking_number}",
                'reference_id' => $package->id,
                'reference_type' => Package::class,
            ]);

            $this->checkLevelUp($customer);

            Log::info("Puntos asignados a cliente {$customer->id}: {$finalPoints} puntos por paquete {$package->id}");
        }
    }

    /**
     * Verifica si el cliente califica para un nuevo nivel.
     */
    public function checkLevelUp(Customer $customer)
    {
        $currentPoints = $customer->points;

        $newLevel = LoyaltyLevel::where('tenant_id', $customer->tenant_id)
            ->where('min_points', '<=', $currentPoints)
            ->orderBy('min_points', 'desc')
            ->first();

        if ($newLevel && $newLevel->id !== $customer->loyalty_level_id) {
            $customer->update(['loyalty_level_id' => $newLevel->id]);
            Log::info("Cliente {$customer->id} subió al nivel: {$newLevel->name}");

            // Aquí se podría disparar una notificación de "Subiste de Nivel"
        }
    }
}
