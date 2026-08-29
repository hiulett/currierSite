<?php

namespace Database\Seeders;

use App\Models\LoyaltyLevel;
use App\Models\LoyaltyRule;
use App\Models\Reward;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class LoyaltySetupSeeder extends Seeder
{
    /**
     * Siembra las reglas, niveles y catálogo de recompensas LOGYPUNTOS.
     * Idempotente: puede ejecutarse múltiples veces sin duplicar.
     */
    public function run(): void
    {
        $levels = [
            ['name' => 'Silver', 'min_points' => 300, 'free_pounds' => 3],
            ['name' => 'Gold', 'min_points' => 900, 'free_pounds' => 12],
            ['name' => 'Platinum', 'min_points' => 1500, 'free_pounds' => 20],
        ];

        $rewards = [
            ['name' => '3 lb gratis', 'points_cost' => 300, 'value' => 3],
            ['name' => '12 lb gratis', 'points_cost' => 900, 'value' => 12],
            ['name' => '20 lb gratis', 'points_cost' => 1500, 'value' => 20],
        ];

        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            $this->command?->warn('No hay tenants para sembrar LOGYPUNTOS.');
        }

        foreach ($tenants as $tenant) {
            // Reglas por tenant
            LoyaltyRule::updateOrCreate(
                ['tenant_id' => $tenant->id],
                [
                    'points_per_pound' => 3.00,
                    'enabled' => true,
                    'apply_on' => 'invoice',
                    'eligible_rate_types' => ['regular'],
                ]
            );

            // Niveles de lealtad
            foreach ($levels as $level) {
                LoyaltyLevel::updateOrCreate(
                    ['tenant_id' => $tenant->id, 'name' => $level['name']],
                    array_merge($level, [
                        'max_points' => null, // Acumulación infinita, sin tope
                        'multiplier' => 1.00,
                        'color' => match ($level['name']) {
                            'Gold' => '#f59e0b',
                            'Platinum' => '#64748b',
                            default => '#94a3b8',
                        },
                        'priority' => $level['min_points'],
                        'is_active' => true,
                    ])
                );
            }

            // Catálogo de recompensas (extensible a artículos físicos)
            foreach ($rewards as $reward) {
                Reward::updateOrCreate(
                    ['tenant_id' => $tenant->id, 'name' => $reward['name']],
                    array_merge($reward, [
                        'type' => 'free_pounds',
                        'description' => "Canjea {$reward['value']} lb gratis de envío.",
                        'is_active' => true,
                        'stock' => null,
                        'sort_order' => $reward['points_cost'],
                    ])
                );
            }

            // Sincronizar con la pantalla existente (settings_json)
            $settings = $tenant->settings_json ?? [];
            $settings['points_per_pound'] = 3.00;
            $settings['loyalty_enabled'] = true;
            $tenant->update(['settings_json' => $settings]);
        }
    }
}
