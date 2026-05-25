<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Plan;
use App\Models\Warehouse;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Invoice;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LogiSaaSSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Global Plans
        $planPro = Plan::updateOrCreate(['name' => 'Professional'], [
            'price' => 249.00,
            'limit_users' => 20,
            'limit_packages_month' => 5000,
            'has_website_builder' => true,
            'has_api_access' => true,
        ]);

        Plan::updateOrCreate(['name' => 'Starter'], [
            'price' => 99.00,
            'limit_users' => 5,
            'limit_packages_month' => 500,
            'has_website_builder' => false,
        ]);

        // 2. Super Admin
        User::updateOrCreate(['email' => 'superadmin@logisaas.com'], [
            'name' => 'Super Admin',
            'password' => Hash::make('admin123'),
        ]);

        // 3. Tenant: LogiExpress
        $tenant = Tenant::updateOrCreate(['subdomain' => 'logiexpress'], [
            'uuid' => Str::uuid(),
            'name' => 'LogiExpress Panama',
            'domain' => 'logiexpress.test',
            'status' => 'active',
            'plan_id' => $planPro->id,
            'theme_config_json' => ['primary' => '#2563eb'],
        ]);

        // 4. Staff Users
        User::updateOrCreate(['email' => 'admin@logiexpress.com'], [
            'tenant_id' => $tenant->id,
            'name' => 'Admin LogiExpress',
            'password' => Hash::make('password'),
        ]);

        // 5. Warehouses
        $whMiami = Warehouse::updateOrCreate(['code' => 'MIA-01', 'tenant_id' => $tenant->id], [
            'name' => 'Miami Main Hub',
            'address' => '12345 NW 115th Ave',
            'city' => 'Miami',
            'state' => 'FL',
            'zip_code' => '33178',
            'country' => 'USA',
        ]);

        $whPanama = Warehouse::updateOrCreate(['code' => 'PTY-01', 'tenant_id' => $tenant->id], [
            'name' => 'Panama Center',
            'address' => 'Calle 50, Edificio Logi',
            'city' => 'Panama',
            'country' => 'Panama',
        ]);

        // 6. Customers
        $customers = [
            ['name' => 'Juan Perez', 'email' => 'cliente@gmail.com', 'box' => 'LEX-5501', 'id' => '8-765-4321'],
            ['name' => 'Maria Lopez', 'email' => 'mlopez@test.com', 'box' => 'LEX-5502', 'id' => 'PE-123-456'],
            ['name' => 'Carlos Ruiz', 'email' => 'cruiz@test.com', 'box' => 'LEX-5503', 'id' => '4-111-2222'],
            ['name' => 'Ana Smith', 'email' => 'asmith@test.com', 'box' => 'LEX-5504', 'id' => 'N-20-3333'],
        ];

        foreach ($customers as $c) {
            $user = User::updateOrCreate(['email' => $c['email']], [
                'tenant_id' => $tenant->id,
                'name' => $c['name'],
                'password' => Hash::make('password'),
            ]);

            Customer::updateOrCreate(['user_id' => $user->id], [
                'tenant_id' => $tenant->id,
                'box_number' => $c['box'],
                'identification_number' => $c['id'],
                'phone' => '507-6000-0000',
            ]);
        }

        $juan = Customer::where('box_number', 'LEX-5501')->first();
        $maria = Customer::where('box_number', 'LEX-5502')->first();
        $carlos = Customer::where('box_number', 'LEX-5503')->first();

        // 7. Packages
        $packagesData = [
            ['track' => 'UPS1001', 'cust' => $juan->id, 'wh' => $whMiami->id, 'status' => 'received', 'desc' => 'Zapatillas Nike'],
            ['track' => 'FEDEX2002', 'cust' => $juan->id, 'wh' => $whMiami->id, 'status' => 'in_transit', 'desc' => 'iPhone 15 Case'],
            ['track' => 'DHL3003', 'cust' => $maria->id, 'wh' => $whPanama->id, 'status' => 'received', 'desc' => 'Macbook Pro'],
            ['track' => 'USPS5005', 'cust' => $carlos->id, 'wh' => $whMiami->id, 'status' => 'prealert', 'desc' => 'Ropa GAP'],
            ['track' => 'PTY7007', 'cust' => $juan->id, 'wh' => $whPanama->id, 'status' => 'delivered', 'desc' => 'Suplementos'],
        ];

        foreach ($packagesData as $p) {
            Package::updateOrCreate(['tracking_number' => $p['track']], [
                'tenant_id' => $tenant->id,
                'customer_id' => $p['cust'],
                'warehouse_id' => $p['wh'],
                'status' => $p['status'],
                'description' => $p['desc'],
                'weight' => rand(1, 15),
            ]);
        }

        // 8. Invoices
        Invoice::updateOrCreate(['number' => 'INV-001'], [
            'tenant_id' => $tenant->id,
            'customer_id' => $juan->id,
            'total' => 25.50,
            'status' => 'unpaid',
            'due_date' => now()->addDays(5),
        ]);

        Invoice::updateOrCreate(['number' => 'INV-002'], [
            'tenant_id' => $tenant->id,
            'customer_id' => $maria->id,
            'total' => 110.00,
            'status' => 'paid',
            'paid_at' => now()->subDay(),
        ]);

        Invoice::updateOrCreate(['number' => 'INV-003'], [
            'tenant_id' => $tenant->id,
            'customer_id' => $carlos->id,
            'total' => 45.00,
            'status' => 'unpaid',
            'due_date' => now()->subDays(2), // Overdue
        ]);

        // 9. Website Builder Initial Data
        $homePage = Page::updateOrCreate(['tenant_id' => $tenant->id, 'slug' => 'inicio'], [
            'title' => 'Bienvenidos a LogiExpress',
            'is_home' => true,
            'is_published' => true,
        ]);

        Section::updateOrCreate(['page_id' => $homePage->id, 'type' => 'hero'], [
            'tenant_id' => $tenant->id,
            'sort_order' => 0,
            'content_json' => [
                'title' => 'Tu Carga en Buenas Manos',
                'subtitle' => 'Compras en USA y recibes en Panamá de forma rápida y segura.',
                'cta_text' => 'Abre tu Casillero Gratis',
                'cta_link' => '/register'
            ]
        ]);
    }
}
