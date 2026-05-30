<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Warehouse;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;

class ProductionDataSyncSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Sync Roles and Permissions (Global/System)
        $this->call(PermissionSeeder::class);

        // 2. Main Tenant: Logy Express
        $tenant1 = Tenant::updateOrCreate(
            ['subdomain' => 'logiexpress'],
            [
                'uuid' => 'b2f91dec-c418-4d6b-ad46-73038127ba12',
                'name' => 'Logy Express',
                'status' => 'active',
                'plan_id' => 5,
                'locale' => 'es',
                'settings_json' => [
                    'currency' => 'USD',
                    'points_per_pound' => 5,
                    'loyalty_enabled' => true,
                    'box_number_prefix_air' => 'LGX',
                    'box_number_template_air' => '{PREFIX}{ID} {NAME}',
                    'service_air_enabled' => true,
                    'air_address' => '2610 NW 89th CT',
                    'air_city' => 'Doral',
                    'air_state' => 'Florida',
                    'air_zip_code' => '33172-1615',
                    'air_phone' => '+1 (305) 848-1127',
                    'service_maritime_enabled' => true,
                    'maritime_address' => '2610 NW 89th CT',
                    'maritime_city' => 'Doral',
                    'maritime_state' => 'Florida',
                    'maritime_zip_code' => '33172-1615',
                    'maritime_phone' => '+1 (305) 848-1127',
                    'box_number_counter' => 1000,
                ],
                'theme_config_json' => [
                    'primary_color' => '#3b7ddd',
                    'theme_mode' => 'light',
                ],
                'enabled_reports_json' => ['inventory_stock', 'revenue_daily', 'customer_debt', 'package_status', 'volume_weight', 'stagnant_cargo', 'driver_efficiency', 'tax_collection'],
            ]
        );

        // 3. Main Admin User
        User::updateOrCreate(
            ['email' => 'admin@empresa1.com'],
            [
                'tenant_id' => $tenant1->id,
                'name' => 'Admin Logy Express',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // 4. Warehouses for Logy Express
        Warehouse::updateOrCreate(
            ['code' => 'MIA-01', 'tenant_id' => $tenant1->id],
            [
                'name' => 'Miami Main Hub',
                'address' => '8400 NW 25th St',
                'city' => 'Miami',
                'state' => 'FL',
                'zip_code' => '33178',
                'country' => 'USA',
                'service_type' => 'both',
                'is_active' => true,
            ]
        );

        Warehouse::updateOrCreate(
            ['code' => 'PTY-01', 'tenant_id' => $tenant1->id],
            [
                'name' => 'Panama Center',
                'address' => 'Calle 50',
                'city' => 'Panama',
                'country' => 'Panama',
                'service_type' => 'both',
                'is_active' => true,
            ]
        );

        // 5. Other Tenants
        Tenant::updateOrCreate(
            ['subdomain' => 'globalcargo'],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Global Cargo China',
                'status' => 'active',
                'plan_id' => 1,
                'settings_json' => ['currency' => 'USD', 'tax_rate' => 7],
            ]
        );

        // 6. Loyalty Levels for Logy Express
        \App\Models\LoyaltyLevel::updateOrCreate(
            ['tenant_id' => $tenant1->id, 'name' => 'Bronce'],
            ['min_points' => 0, 'multiplier' => 1.0, 'color' => '#cd7f32', 'icon' => 'award']
        );
        \App\Models\LoyaltyLevel::updateOrCreate(
            ['tenant_id' => $tenant1->id, 'name' => 'Plata'],
            ['min_points' => 500, 'multiplier' => 1.1, 'color' => '#c0c0c0', 'icon' => 'award']
        );
        \App\Models\LoyaltyLevel::updateOrCreate(
            ['tenant_id' => $tenant1->id, 'name' => 'Oro'],
            ['min_points' => 1500, 'multiplier' => 1.25, 'color' => '#ffd700', 'icon' => 'award']
        );
    }
}
