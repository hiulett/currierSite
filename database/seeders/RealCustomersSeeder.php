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
            ['id' => '1021', 'name' => 'Ariel Ramos', 'email' => 'ariel.ramos@gmail.com', 'phone' => '6111-2233'],
            ['id' => '1022', 'name' => 'Yamileth Vega', 'email' => 'yamivega@hotmail.com', 'phone' => '6222-3344'],
            ['id' => '1023', 'name' => 'Elias Garcia', 'email' => 'eliasgarcia_pty@outlook.com', 'phone' => '6333-4455'],
            ['id' => '1024', 'name' => 'Sonia Perez', 'email' => 'soniaperez_log@pro.com', 'phone' => '6444-5566'],
            ['id' => '1025', 'name' => 'Mario Ortega', 'email' => 'mario.ortega@gmail.com', 'phone' => '6555-6677'],
            ['id' => '1026', 'name' => 'Karla Mendez', 'email' => 'k_mendez88@hotmail.com', 'phone' => '6666-7788'],
            ['id' => '1027', 'name' => 'Jose Manuel Castillo', 'email' => 'josem_castillo@gmail.com', 'phone' => '6777-8899'],
            ['id' => '1028', 'name' => 'Daniela Vargas', 'email' => 'dvargas_pty@outlook.com', 'phone' => '6888-9900'],
            ['id' => '1029', 'name' => 'Luis Antonio Rios', 'email' => 'luisar@gmail.com', 'phone' => '6999-0011'],
            ['id' => '1030', 'name' => 'Carmen Elena Solis', 'email' => 'carmensolis_pa@hotmail.com', 'phone' => '6000-1122'],
            ['id' => '1031', 'name' => 'Ricardo Alfredo Lopez', 'email' => 'ralfredo_l@gmail.com', 'phone' => '6111-2233'],
            ['id' => '1032', 'name' => 'Beatriz Isabel Gomez', 'email' => 'beatrizig@outlook.com', 'phone' => '6222-3344'],
            ['id' => '1033', 'name' => 'Roberto Carlos Moreno', 'email' => 'rob_moreno@pro.com', 'phone' => '6333-4455'],
            ['id' => '1034', 'name' => 'Adriana Lucia Ortiz', 'email' => 'adriana_o@gmail.com', 'phone' => '6444-5566'],
            ['id' => '1035', 'name' => 'Felipe Enrique Ramos', 'email' => 'feliper@hotmail.com', 'phone' => '6555-6677'],
            ['id' => '1036', 'name' => 'Gloria Maria Batista', 'email' => 'gloria_b@gmail.com', 'phone' => '6666-7788'],
            ['id' => '1037', 'name' => 'Victor Manuel Guerra', 'email' => 'vmanuelg@outlook.com', 'phone' => '6777-8899'],
            ['id' => '1038', 'name' => 'Martha Isabel Rivera', 'email' => 'martair@pro.com', 'phone' => '6888-9900'],
            ['id' => '1039', 'name' => 'Andres Eduardo Cano', 'email' => 'andres_cano@gmail.com', 'phone' => '6999-0011'],
            ['id' => '1040', 'name' => 'Lucia Ines Fernandez', 'email' => 'lucia_f@hotmail.com', 'phone' => '6000-1122'],
            ['id' => '1041', 'name' => 'Pedro Pablo Martinez', 'email' => 'pedrom@gmail.com', 'phone' => '6111-2233'],
            ['id' => '1042', 'name' => 'Claudia Patricia Soto', 'email' => 'claudiaps@outlook.com', 'phone' => '6222-3344'],
            ['id' => '1043', 'name' => 'Jorge Luis Herrera', 'email' => 'jorgelh@pro.com', 'phone' => '6333-4455'],
            ['id' => '1044', 'name' => 'Monica Alejandra Rojas', 'email' => 'monic_rojas@gmail.com', 'phone' => '6444-5566'],
            ['id' => '1045', 'name' => 'Francisco Javier Ortiz', 'email' => 'fs_ortiz@hotmail.com', 'phone' => '6555-6677'],
            ['id' => '1046', 'name' => 'Rosa Elvira Espino', 'email' => 'rosae_espino@gmail.com', 'phone' => '6666-7788'],
            ['id' => '1047', 'name' => 'Gabriel Alonso Solis', 'email' => 'gabriel_alonso@outlook.com', 'phone' => '6777-8899'],
            ['id' => '1048', 'name' => 'Isabel Cristina Batista', 'email' => 'isabelcb@pro.com', 'phone' => '6888-9900'],
            ['id' => '1049', 'name' => 'Daniel Antonio Mendoza', 'email' => 'danielam@gmail.com', 'phone' => '6999-0011'],
            ['id' => '1050', 'name' => 'Laura Vanessa Mendez', 'email' => 'laura_vm@hotmail.com', 'phone' => '6000-1122'],
            ['id' => '1051', 'name' => 'Oscar Roberto Jimenez', 'email' => 'oscar_jimenez@gmail.com', 'phone' => '6111-2233'],
            ['id' => '1052', 'name' => 'Patricia Elena Ruiz', 'email' => 'patriciaer@outlook.com', 'phone' => '6222-3344'],
            ['id' => '1053', 'name' => 'Sergio Andres Salazar', 'email' => 'sergioas@pro.com', 'phone' => '6333-4455'],
            ['id' => '1054', 'name' => 'Manuel Antonio Flores', 'email' => 'manuelaf@gmail.com', 'phone' => '6444-5566'],
            ['id' => '1055', 'name' => 'Yolanda Ines Ortiz', 'email' => 'yolandai_ortiz@hotmail.com', 'phone' => '6555-6677'],
            ['id' => '1056', 'name' => 'Victor Hugo Medina', 'email' => 'vmedina_h@gmail.com', 'phone' => '6666-7788'],
            ['id' => '1057', 'name' => 'Gloria Elena Castillo', 'email' => 'gloriaec@outlook.com', 'phone' => '6777-8899'],
            ['id' => '1058', 'name' => 'Daniel Eduardo Guerrero', 'email' => 'daniel_guerrero@pro.com', 'phone' => '6888-9900'],
            ['id' => '1059', 'name' => 'Francisco Jose Soto', 'email' => 'fsoto_jose@gmail.com', 'phone' => '6999-0011'],
            ['id' => '1060', 'name' => 'Elena Patricia Vasquez', 'email' => 'elenav_pty@hotmail.com', 'phone' => '6000-1122'],
            ['id' => '1061', 'name' => 'Sandra Milena Gomez', 'email' => 'sandra.gomez@gmail.com', 'phone' => '6111-4455'],
            ['id' => '1062', 'name' => 'Gustavo Adolfo Rios', 'email' => 'grios_adolfo@outlook.com', 'phone' => '6222-5566'],
            ['id' => '1063', 'name' => 'Monica del Carmen Rojas', 'email' => 'monica.rojas88@gmail.com', 'phone' => '6333-6677'],
            ['id' => '1064', 'name' => 'Jose Angel Rivera', 'email' => 'jose.rivera.pty@pro.com', 'phone' => '6444-7788'],
            ['id' => '1065', 'name' => 'Beatriz Adriana Solis', 'email' => 'beatriz.solis@hotmail.com', 'phone' => '6555-8899'],
            ['id' => '1066', 'name' => 'Luis Fernando Guerra', 'email' => 'luis.guerra.pa@gmail.com', 'phone' => '6666-9900'],
            ['id' => '1067', 'name' => 'Claudia Isabel Mendez', 'email' => 'claudia.mendez@outlook.com', 'phone' => '6777-0011'],
            ['id' => '1068', 'name' => 'Roberto Antonio Vega', 'email' => 'roberto.vega.log@gmail.com', 'phone' => '6888-1122'],
            ['id' => '1069', 'name' => 'Patricia Maria Cano', 'email' => 'patricia.cano@pro.com', 'phone' => '6999-2233'],
            ['id' => '1070', 'name' => 'Jorge Luis Batista', 'email' => 'jorge.batista@hotmail.com', 'phone' => '6000-3344'],
            ['id' => '1071', 'name' => 'Ana Victoria Ortiz', 'email' => 'ana.ortiz.pty@gmail.com', 'phone' => '6111-4455'],
            ['id' => '1072', 'name' => 'Carlos Alberto Espino', 'email' => 'carlos.espino@outlook.com', 'phone' => '6222-5566'],
            ['id' => '1073', 'name' => 'Marta Elena Soto', 'email' => 'marta.soto@gmail.com', 'phone' => '6333-6677'],
            ['id' => '1074', 'name' => 'Francisco Javier Ramos', 'email' => 'framos.log@pro.com', 'phone' => '6444-7788'],
            ['id' => '1075', 'name' => 'Laura Sofia Rojas', 'email' => 'laura.rojas@hotmail.com', 'phone' => '6555-8899'],
            ['id' => '1076', 'name' => 'Victor Manuel Rios', 'email' => 'victor.rios.pa@gmail.com', 'phone' => '6666-9900'],
            ['id' => '1077', 'name' => 'Gloria Maria Mendez', 'email' => 'gloria.mendez@outlook.com', 'phone' => '6777-0011'],
            ['id' => '1078', 'name' => 'Daniel Antonio Solis', 'email' => 'daniel.solis.log@gmail.com', 'phone' => '6888-1122'],
            ['id' => '1079', 'name' => 'Isabel Cristina Gomez', 'email' => 'isabel.gomez@pro.com', 'phone' => '6999-2233'],
            ['id' => '1080', 'name' => 'Manuel Alberto Castillo', 'email' => 'manuel.castillo@hotmail.com', 'phone' => '6000-3344'],
        ];

        foreach ($customers as $data) {
            // Create or Update User account
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
        $settings['box_number_counter'] = 1080;
        $tenant->update(['settings_json' => $settings]);
    }
}
