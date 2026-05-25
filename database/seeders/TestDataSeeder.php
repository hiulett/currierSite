<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Plan;
use App\Models\Warehouse;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\TaxCategory;
use App\Models\Locker;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Clear existing plans
        DB::statement('DELETE FROM plans');

        // Feature set common to all current plans
        $currentFeatures = [
            'Logística PRO (Recepción, Scanner, Etiquetas)',
            'Inventario Avanzado (Búsqueda por Cédula/Tracking)',
            'Módulo de Reempaque y Consolidación',
            'Gestión de Embarques y Manifiestos',
            'Última Milla (POD, Fotos y Geofencing GPS)',
            'Despacho en Oficina con Alerta de Deuda',
            'Facturación Automática (Stripe & PayPal)',
            'Portal del Cliente White Label',
            'WhatsApp IA Agent & Tickets de Soporte',
            'Identidad Visual (Logo y Colores Propios)',
            'Widgets de Rastreo para sitios externos',
            'Soporte Multilenguaje (i18n)',
        ];

        // 1. Create 3 Simplified Plans based on Payment Ease
        $flexPlan = Plan::create([
            'name' => 'Plan Flex (Mensual)',
            'slug' => 'flex',
            'description' => 'Pago recurrente mes a mes sin compromisos. Ideal para flujo de caja variable.',
            'price' => 990.00,
            'price_annual' => null,
            'price_5year' => null,
            'limit_users' => 999,
            'limit_packages_month' => 99999,
            'features_json' => $currentFeatures,
            'is_active' => true,
            'is_featured' => false,
        ]);

        $proPlan = Plan::create([
            'name' => 'Plan Pro (2 Pagos)',
            'slug' => 'pro',
            'description' => 'Divida su inversión en solo 2 cuotas semestrales. Ahorro inmediato de $1,092 anuales.',
            'price' => 899.00,
            'price_annual' => 10788.00,
            'price_5year' => null,
            'limit_users' => 999,
            'limit_packages_month' => 99999,
            'features_json' => $currentFeatures,
            'is_active' => true,
            'is_featured' => true,
        ]);

        $visionaryPlan = Plan::create([
            'name' => 'Plan Visionario (5 Años)',
            'slug' => 'visionary',
            'description' => 'Asegure la tecnología de su empresa por media década con la tarifa más baja garantizada.',
            'price' => 599.00,
            'price_annual' => null,
            'price_5year' => 35940.00,
            'limit_users' => 999,
            'limit_packages_month' => 99999,
            'features_json' => $currentFeatures,
            'is_active' => true,
            'is_featured' => false,
        ]);

        // 2. Create SuperAdmin
        User::updateOrCreate(['email' => 'superadmin@logisaas.com'], [
            'name' => 'Super Admin Global',
            'password' => Hash::make('admin123'),
            'role' => 'superadmin',
            'tenant_id' => null,
        ]);

        // 3. Create Tenant (Assigned to the most popular plan)
        $tenant = Tenant::updateOrCreate(['subdomain' => 'logiexpress'], [
            'uuid' => Str::uuid(),
            'name' => 'LogiExpress Panama',
            'domain' => 'logiexpress.test',
            'status' => 'active',
            'plan_id' => $proPlan->id,
            'settings_json' => ['currency' => 'USD'],
            'theme_config_json' => ['primary_color' => '#3b7ddd'],
            'enabled_reports_json' => [
                'inventory_stock', 'revenue_daily', 'customer_debt', 'package_status',
                'volume_weight', 'stagnant_cargo', 'driver_efficiency', 'tax_collection'
            ]
        ]);

        // 3.1 Create Roles for the Tenant
        $this->call(PermissionSeeder::class);

        $adminRole = Role::updateOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'Administrador'],
            ['description' => 'Acceso total a la configuración y gestión del tenant.', 'is_system' => true]
        );
        $adminRole->permissions()->sync(Permission::all());

        $operatorRole = Role::updateOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'Operador'],
            ['description' => 'Gestión operativa de paquetes y entregas.', 'is_system' => true]
        );
        $operatorRole->permissions()->sync(Permission::where('group', 'Logística')->get());

        // 4. Admin and Operators
        User::updateOrCreate(['email' => 'admin@logiexpress.com'], [
            'tenant_id' => $tenant->id,
            'name' => 'Admin LogiExpress',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'role_id' => $adminRole->id,
        ]);
        User::updateOrCreate(['email' => 'operador@logiexpress.com'], [
            'tenant_id' => $tenant->id,
            'name' => 'Operador Bodega',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'role_id' => $operatorRole->id,
        ]);

        // 5. Warehouses
        $miami = Warehouse::updateOrCreate(['code' => 'MIA-01', 'tenant_id' => $tenant->id], [
            'name' => 'Miami Main Hub', 'address' => '8400 NW 25th St', 'city' => 'Miami', 'country' => 'USA',
        ]);
        $panama = Warehouse::updateOrCreate(['code' => 'PTY-01', 'tenant_id' => $tenant->id], [
            'name' => 'Panama Center', 'address' => 'Calle 50', 'city' => 'Panama', 'country' => 'Panama',
        ]);

        // 6. Lockers
        for ($i = 1; $i <= 5; $i++) {
            Locker::updateOrCreate(['code' => "SEC-A-0$i", 'tenant_id' => $tenant->id], [
                'status' => 'available', 'max_weight' => 50.00
            ]);
        }

        // 7. Diverse Customers with Identification Numbers (Cedulas)
        $customersData = [
            ['name' => 'Juan Perez', 'email' => 'cliente@gmail.com', 'box' => 'LEX-5501', 'id' => '8-765-4321'],
            ['name' => 'Maria Lopez', 'email' => 'mlopez@test.com', 'box' => 'LEX-5502', 'id' => 'PE-123-456'],
            ['name' => 'Carlos Ruiz', 'email' => 'cruiz@test.com', 'box' => 'LEX-5503', 'id' => '4-111-2222'],
            ['name' => 'Ana Smith', 'email' => 'asmith@test.com', 'box' => 'LEX-5504', 'id' => 'N-20-3333'],
        ];

        foreach ($customersData as $data) {
            $user = User::updateOrCreate(['email' => $data['email']], [
                'tenant_id' => $tenant->id, 'name' => $data['name'], 'password' => Hash::make('password'), 'role' => 'customer',
            ]);
            Customer::updateOrCreate(['user_id' => $user->id], [
                'tenant_id' => $tenant->id,
                'box_number' => $data['box'],
                'phone' => '6000-0000',
                'balance' => rand(0, 50),
                'identification_number' => $data['id']
            ]);
        }

        $juan = Customer::where('box_number', 'LEX-5501')->first();
        $maria = Customer::where('box_number', 'LEX-5502')->first();
        $carlos = Customer::where('box_number', 'LEX-5503')->first();

        // 8. Packages in different states
        $packages = [
            ['track' => 'UPS1001', 'cust' => $juan->id, 'wh' => $miami->id, 'status' => 'received', 'desc' => 'Zapatillas Nike'],
            ['track' => 'FEDEX2002', 'cust' => $juan->id, 'wh' => $miami->id, 'status' => 'in_transit', 'desc' => 'iPhone Case'],
            ['track' => 'DHL3003', 'cust' => $maria->id, 'wh' => $miami->id, 'status' => 'arrived', 'desc' => 'Macbook Pro'],
            ['track' => 'AMZ4004', 'cust' => $maria->id, 'wh' => $panama->id, 'status' => 'ready_for_pickup', 'desc' => 'Libros varios'],
            ['track' => 'USPS5005', 'cust' => $carlos->id, 'wh' => $miami->id, 'status' => 'prealert', 'desc' => 'Ropa GAP'],
            ['track' => 'UPS6006', 'cust' => $carlos->id, 'wh' => $panama->id, 'status' => 'delivered', 'desc' => 'Monitor Gamer'],
            ['track' => 'PTY7007', 'cust' => $juan->id, 'wh' => $panama->id, 'status' => 'out_for_delivery', 'desc' => 'Suplementos'],
        ];

        foreach ($packages as $p) {
            Package::updateOrCreate(['tracking_number' => $p['track']], [
                'tenant_id' => $tenant->id, 'customer_id' => $p['cust'], 'warehouse_id' => $p['wh'],
                'status' => $p['status'], 'description' => $p['desc'],
                'weight' => rand(1, 10)
            ]);
        }

        // 9. Diverse Invoices
        Invoice::updateOrCreate(['number' => 'INV-001'], [
            'tenant_id' => $tenant->id, 'customer_id' => $juan->id, 'subtotal' => 20, 'tax' => 5.50, 'total' => 25.50, 'status' => 'unpaid', 'due_date' => now()->addDays(5)
        ]);
        Invoice::updateOrCreate(['number' => 'INV-002'], [
            'tenant_id' => $tenant->id, 'customer_id' => $maria->id, 'subtotal' => 100, 'tax' => 10, 'total' => 110.00, 'status' => 'paid', 'paid_at' => now()->subDay()
        ]);
        Invoice::updateOrCreate(['number' => 'INV-003'], [
            'tenant_id' => $tenant->id, 'customer_id' => $carlos->id, 'subtotal' => 40, 'tax' => 5, 'total' => 45.00, 'status' => 'unpaid', 'due_date' => now()->subDays(2)
        ]);
    }
}
