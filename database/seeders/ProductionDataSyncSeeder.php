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
    }
}
