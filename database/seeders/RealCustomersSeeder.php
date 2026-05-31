<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RealCustomersSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('subdomain', 'logiexpress')->first();
        if (!$tenant) return;

        $customers = [
            ['id' => '1001', 'name' => 'Nicolasa Castro', 'email' => 'nicolasacastro71@gmail.com', 'phone' => '6461-9255'],
            ['id' => '1002', 'name' => 'Angel Hernandez', 'email' => 'angy_hernandez04@hotmail.com', 'phone' => '6634-1110'],
            ['id' => '1003', 'name' => 'Itzel de Gracia', 'email' => 'itzel_degracia@hotmail.com', 'phone' => '6502-3450'],
            ['id' => '1004', 'name' => 'Josue Rivera', 'email' => 'j.rivera24@hotmail.com', 'phone' => '6268-3011'],
            ['id' => '1005', 'name' => 'Alexandra Perez', 'email' => 'alexandraperez@gmail.com', 'phone' => '6780-4412'],
            ['id' => '1006', 'name' => 'Carlos Mendoza', 'email' => 'cmendoza.pty@outlook.com', 'phone' => '6990-1122'],
            ['id' => '1007', 'name' => 'Maria Santos', 'email' => 'mariantos88@gmail.com', 'phone' => '6555-4433'],
            ['id' => '1008', 'name' => 'Roberto Cano', 'email' => 'rcano_ventas@hotmail.com', 'phone' => '6444-2211'],
            ['id' => '1009', 'name' => 'Elena Rodriguez', 'email' => 'elena.rod.p@gmail.com', 'phone' => '6333-8877'],
            ['id' => '1010', 'name' => 'Juan Castillo', 'email' => 'jcastillo_log@pro.com', 'phone' => '6222-9900'],
            ['id' => '1011', 'name' => 'Yariela Gomez', 'email' => 'yari.gomez@hotmail.com', 'phone' => '6111-3344'],
            ['id' => '1012', 'name' => 'Ricardo Espino', 'email' => 'respino_pty@gmail.com', 'phone' => '6000-5566'],
            ['id' => '1013', 'name' => 'Lourdes Vega', 'email' => 'lourdes.vega@outlook.com', 'phone' => '6888-7788'],
            ['id' => '1014', 'name' => 'Fernando Rios', 'email' => 'frios_cargo@gmail.com', 'phone' => '6777-1122'],
            ['id' => '1015', 'name' => 'Sofia Batista', 'email' => 'sofia.b_89@hotmail.com', 'phone' => '6666-4455'],
            ['id' => '1016', 'name' => 'Gabriel Solis', 'email' => 'gsolis_pa@gmail.com', 'phone' => '6555-9900'],
            ['id' => '1017', 'name' => 'Ana Lorena Guerra', 'email' => 'alorena.g@gmail.com', 'phone' => '6444-1234'],
            ['id' => '1018', 'name' => 'Luis Carlos Diaz', 'email' => 'lcdiaz_pty@outlook.com', 'phone' => '6333-5678'],
            ['id' => '1019', 'name' => 'Marta Isabel Rojas', 'email' => 'marta.rojas@hotmail.com', 'phone' => '6222-0011'],
            ['id' => '1020', 'name' => 'Jorge Alberto Ruiz', 'email' => 'jorge.ruiz_cargo@gmail.com', 'phone' => '6111-9988'],
        ];

        foreach ($customers as $data) {
            // Create User account
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'tenant_id' => $tenant->id,
                    'name' => $data['name'],
                    'password' => Hash::make('password123'), // Default password
                    'role' => 'customer',
                    'email_verified_at' => now(),
                ]
            );

            // Create or Update Customer Profile
            Customer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'tenant_id' => $tenant->id,
                    'box_number' => 'LGX' . $data['id'],
                    'box_number_air' => 'LGX' . $data['id'],
                    'box_number_maritime' => 'LGX' . $data['id'] . 'M',
                    'phone' => $data['phone'],
                    'balance' => 0,
                    'points' => 0,
                ]
            );
        }

        // Update Tenant Counter to avoid ID collision
        $settings = $tenant->settings_json;
        $settings['box_number_counter'] = 1020;
        $tenant->update(['settings_json' => $settings]);
    }
}
