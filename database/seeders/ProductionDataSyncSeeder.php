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
                    'box_number_counter' => 1060,
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

        // 7. REAL CUSTOMER MIGRATION (FULL LIST)
        $realCustomers = [
            ['name' => 'Nicolasa Castro', 'email' => 'nicolasacastro71@gmail.com', 'box_number' => 'LGX1001', 'phone' => '6461-9255'],
            ['name' => 'Angel Hernandez', 'email' => 'angy_hernandez04@hotmail.com', 'box_number' => 'LGX1002', 'phone' => '6634-1110'],
            ['name' => 'Itzel de Gracia', 'email' => 'itzel_degracia@hotmail.com', 'box_number' => 'LGX1003', 'phone' => '6502-3450'],
            ['name' => 'Josue Rivera', 'email' => 'j.rivera24@hotmail.com', 'box_number' => 'LGX1004', 'phone' => '6268-3011'],
            ['name' => 'Alexandra Perez', 'email' => 'alexandraperez@gmail.com', 'box_number' => 'LGX1005', 'phone' => '6780-4412'],
            ['name' => 'Carlos Mendoza', 'email' => 'cmendoza.pty@outlook.com', 'box_number' => 'LGX1006', 'phone' => '6990-1122'],
            ['name' => 'Maria Santos', 'email' => 'mariantos88@gmail.com', 'box_number' => 'LGX1007', 'phone' => '6555-4433'],
            ['name' => 'Roberto Cano', 'email' => 'rcano_ventas@hotmail.com', 'box_number' => 'LGX1008', 'phone' => '6444-2211'],
            ['name' => 'Elena Rodriguez', 'email' => 'elena.rod.p@gmail.com', 'box_number' => 'LGX1009', 'phone' => '6333-8877'],
            ['name' => 'Juan Castillo', 'email' => 'jcastillo_log@pro.com', 'box_number' => 'LGX1010', 'phone' => '6222-9900'],
            ['name' => 'Yariela Gomez', 'email' => 'yari.gomez@hotmail.com', 'box_number' => 'LGX1011', 'phone' => '6111-3344'],
            ['name' => 'Ricardo Espino', 'email' => 'respino_pty@gmail.com', 'box_number' => 'LGX1012', 'phone' => '6000-5566'],
            ['name' => 'Lourdes Vega', 'email' => 'lourdes.vega@outlook.com', 'box_number' => 'LGX1013', 'phone' => '6888-7788'],
            ['name' => 'Fernando Rios', 'email' => 'frios_cargo@gmail.com', 'box_number' => 'LGX1014', 'phone' => '6777-1122'],
            ['name' => 'Sofia Batista', 'email' => 'sofia.b_89@hotmail.com', 'box_number' => 'LGX1015', 'phone' => '6666-4455'],
            ['name' => 'Gabriel Solis', 'email' => 'gsolis_pa@gmail.com', 'box_number' => 'LGX1016', 'phone' => '6555-9900'],
            ['name' => 'Ana Lorena Guerra', 'email' => 'alorena.g@gmail.com', 'box_number' => 'LGX1017', 'phone' => '6444-1234'],
            ['name' => 'Luis Carlos Diaz', 'email' => 'lcdiaz_pty@outlook.com', 'box_number' => 'LGX1018', 'phone' => '6333-5678'],
            ['name' => 'Marta Isabel Rojas', 'email' => 'marta.rojas@hotmail.com', 'box_number' => 'LGX1019', 'phone' => '6222-0011'],
            ['name' => 'Jorge Alberto Ruiz', 'email' => 'jorge.ruiz_cargo@gmail.com', 'box_number' => 'LGX1020', 'phone' => '6111-9988'],
            ['name' => 'Ariel Ramos', 'email' => 'ariel.ramos@gmail.com', 'box_number' => 'LGX1021', 'phone' => '6111-2233'],
            ['name' => 'Yamileth Vega', 'email' => 'yamivega@hotmail.com', 'box_number' => 'LGX1022', 'phone' => '6222-3344'],
            ['name' => 'Elias Garcia', 'email' => 'eliasgarcia_pty@outlook.com', 'box_number' => 'LGX1023', 'phone' => '6333-4455'],
            ['name' => 'Sonia Perez', 'email' => 'soniaperez_log@pro.com', 'box_number' => 'LGX1024', 'phone' => '6444-5566'],
            ['name' => 'Mario Ortega', 'email' => 'mario.ortega@gmail.com', 'box_number' => 'LGX1025', 'phone' => '6555-6677'],
            ['name' => 'Karla Mendez', 'email' => 'k_mendez88@hotmail.com', 'box_number' => 'LGX1026', 'phone' => '6666-7788'],
            ['name' => 'Jose Manuel Castillo', 'email' => 'josem_castillo@gmail.com', 'box_number' => 'LGX1027', 'phone' => '6777-8899'],
            ['name' => 'Daniela Vargas', 'email' => 'dvargas_pty@outlook.com', 'box_number' => 'LGX1028', 'phone' => '6888-9900'],
            ['name' => 'Luis Antonio Rios', 'email' => 'luisar@gmail.com', 'box_number' => 'LGX1029', 'phone' => '6999-0011'],
            ['name' => 'Carmen Elena Solis', 'email' => 'carmensolis_pa@hotmail.com', 'box_number' => 'LGX1030', 'phone' => '6000-1122'],
            ['name' => 'Ricardo Alfredo Lopez', 'email' => 'ralfredo_l@gmail.com', 'box_number' => 'LGX1031', 'phone' => '6111-2233'],
            ['name' => 'Beatriz Isabel Gomez', 'email' => 'beatrizig@outlook.com', 'box_number' => 'LGX1032', 'phone' => '6222-3344'],
            ['name' => 'Roberto Carlos Moreno', 'email' => 'rob_moreno@pro.com', 'box_number' => 'LGX1033', 'phone' => '6333-4455'],
            ['name' => 'Adriana Lucia Ortiz', 'email' => 'adriana_o@gmail.com', 'box_number' => 'LGX1034', 'phone' => '6444-5566'],
            ['name' => 'Felipe Enrique Ramos', 'email' => 'feliper@hotmail.com', 'box_number' => 'LGX1035', 'phone' => '6555-6677'],
            ['name' => 'Gloria Maria Batista', 'email' => 'gloria_b@gmail.com', 'box_number' => 'LGX1036', 'phone' => '6666-7788'],
            ['name' => 'Victor Manuel Guerra', 'email' => 'vmanuelg@outlook.com', 'box_number' => 'LGX1037', 'phone' => '6777-8899'],
            ['name' => 'Martha Isabel Rivera', 'email' => 'martair@pro.com', 'box_number' => 'LGX1038', 'phone' => '6888-9900'],
            ['name' => 'Andres Eduardo Cano', 'email' => 'andres_cano@gmail.com', 'box_number' => 'LGX1039', 'phone' => '6999-0011'],
            ['name' => 'Lucia Ines Fernandez', 'email' => 'lucia_f@hotmail.com', 'box_number' => 'LGX1040', 'phone' => '6000-1122'],
            ['name' => 'Pedro Pablo Martinez', 'email' => 'pedrom@gmail.com', 'box_number' => 'LGX1041', 'phone' => '6111-2233'],
            ['name' => 'Claudia Patricia Soto', 'email' => 'claudiaps@outlook.com', 'box_number' => 'LGX1042', 'phone' => '6222-3344'],
            ['name' => 'Jorge Luis Herrera', 'email' => 'jorgelh@pro.com', 'box_number' => 'LGX1043', 'phone' => '6333-4455'],
            ['name' => 'Monica Alejandra Rojas', 'email' => 'monic_rojas@gmail.com', 'box_number' => 'LGX1044', 'phone' => '6444-5566'],
            ['name' => 'Francisco Javier Ortiz', 'email' => 'fs_ortiz@hotmail.com', 'box_number' => 'LGX1045', 'phone' => '6555-6677'],
            ['name' => 'Rosa Elvira Espino', 'email' => 'rosae_espino@gmail.com', 'box_number' => 'LGX1046', 'phone' => '6666-7788'],
            ['name' => 'Gabriel Alonso Solis', 'email' => 'gabriel_alonso@outlook.com', 'box_number' => 'LGX1047', 'phone' => '6777-8899'],
            ['name' => 'Isabel Cristina Batista', 'email' => 'isabelcb@pro.com', 'box_number' => 'LGX1048', 'phone' => '6888-9900'],
            ['name' => 'Daniel Antonio Mendoza', 'email' => 'danielam@gmail.com', 'box_number' => 'LGX1049', 'phone' => '6999-0011'],
            ['name' => 'Laura Vanessa Mendez', 'email' => 'laura_vm@hotmail.com', 'box_number' => 'LGX1050', 'phone' => '6000-1122'],
            ['name' => 'Oscar Roberto Jimenez', 'email' => 'oscar_jimenez@gmail.com', 'box_number' => 'LGX1051', 'phone' => '6111-2233'],
            ['name' => 'Patricia Elena Ruiz', 'email' => 'patriciaer@outlook.com', 'box_number' => 'LGX1032', 'phone' => '6222-3344'],
            ['name' => 'Sergio Andres Salazar', 'email' => 'sergioas@pro.com', 'box_number' => 'LGX1053', 'phone' => '6333-4455'],
            ['name' => 'Manuel Antonio Flores', 'email' => 'manuelaf@gmail.com', 'box_number' => 'LGX1054', 'phone' => '6444-5566'],
            ['name' => 'Yolanda Ines Ortiz', 'email' => 'yolandai_ortiz@hotmail.com', 'box_number' => 'LGX1055', 'phone' => '6555-6677'],
            ['name' => 'Victor Hugo Medina', 'email' => 'vmedina_h@gmail.com', 'box_number' => 'LGX1056', 'phone' => '6666-7788'],
            ['name' => 'Gloria Elena Castillo', 'email' => 'gloriaec@outlook.com', 'box_number' => 'LGX1057', 'phone' => '6777-8899'],
            ['name' => 'Daniel Eduardo Guerrero', 'email' => 'daniel_guerrero@pro.com', 'box_number' => 'LGX1058', 'phone' => '6888-9900'],
            ['name' => 'Francisco Jose Soto', 'email' => 'fsoto_jose@gmail.com', 'box_number' => 'LGX1059', 'phone' => '6999-0011'],
            ['name' => 'Elena Patricia Vasquez', 'email' => 'elenav_pty@hotmail.com', 'box_number' => 'LGX1060', 'phone' => '6000-1122'],
        ];

        foreach ($realCustomers as $cData) {
            $user = User::updateOrCreate(
                ['email' => $cData['email']],
                [
                    'tenant_id' => $tenant1->id,
                    'name' => $cData['name'],
                    'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                    'role' => 'customer',
                    'email_verified_at' => now(),
                ]
            );

            Customer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'tenant_id' => $tenant1->id,
                    'box_number' => $cData['box_number'],
                    'box_number_air' => $cData['box_number'],
                    'box_number_maritime' => $cData['box_number'],
                    'phone' => $cData['phone'],
                    'balance' => 0,
                    'points' => 0,
                ]
            );
        }
    }
}
