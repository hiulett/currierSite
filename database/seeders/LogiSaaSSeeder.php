<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Plan;
use App\Models\Warehouse;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Invoice;
use App\Models\SubscriptionInvoice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LogiSaaSSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Global Plans
        $planEnterprise = Plan::updateOrCreate(['name' => 'Enterprise'], [
            'price' => 499.00,
            'limit_users' => 999,
            'limit_packages_month' => 99999,
            'has_website_builder' => true,
            'has_api_access' => true,
            'is_active' => true,
        ]);

        $planPro = Plan::updateOrCreate(['name' => 'Professional'], [
            'price' => 249.00,
            'limit_users' => 20,
            'limit_packages_month' => 5000,
            'has_website_builder' => true,
            'has_api_access' => true,
            'is_active' => true,
        ]);

        $planStarter = Plan::updateOrCreate(['name' => 'Starter'], [
            'price' => 99.00,
            'limit_users' => 5,
            'limit_packages_month' => 500,
            'has_website_builder' => false,
            'is_active' => true,
        ]);

        // 2. Super Admin
        User::updateOrCreate(['email' => 'superadmin@logisaas.com'], [
            'name' => 'Super Admin Global',
            'password' => Hash::make('admin123'),
            'role' => 'superadmin',
            'email_verified_at' => now(),
        ]);

        // 3. Setup Multiple Tenants
        $tenantsData = [
            [
                'name' => 'LogiExpress Panama',
                'subdomain' => 'logiexpress',
                'domain' => 'logiexpress.test',
                'plan' => $planPro,
                'color' => '#2563eb'
            ],
            [
                'name' => 'Global Cargo China',
                'subdomain' => 'globalcargo',
                'domain' => 'globalcargo.test',
                'plan' => $planEnterprise,
                'color' => '#dc2626'
            ],
            [
                'name' => 'SpeedShip USA',
                'subdomain' => 'speedship',
                'domain' => null,
                'plan' => $planStarter,
                'color' => '#16a34a'
            ]
        ];

        foreach ($tenantsData as $tData) {
            $tenant = Tenant::updateOrCreate(['subdomain' => $tData['subdomain']], [
                'uuid' => Str::uuid(),
                'name' => $tData['name'],
                'domain' => $tData['domain'],
                'status' => 'active',
                'plan_id' => $tData['plan']->id,
                'theme_config_json' => [
                    'primary' => $tData['color'],
                    'logo_url' => null // Removed external hotlinking URL
                ],
                'settings_json' => ['currency' => 'USD', 'tax_rate' => 7],
            ]);

            // 4. Staff Users per Tenant
            User::updateOrCreate(['email' => "admin@{$tData['subdomain']}.com"], [
                'tenant_id' => $tenant->id,
                'name' => "Admin {$tData['name']}",
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);

            User::updateOrCreate(['email' => "operador@{$tData['subdomain']}.com"], [
                'tenant_id' => $tenant->id,
                'name' => "Operador {$tData['name']}",
                'password' => Hash::make('password'),
                'role' => 'operator',
                'email_verified_at' => now(),
            ]);

            // 5. Warehouses per Tenant
            $miami = Warehouse::updateOrCreate(['code' => "MIA-{$tenant->id}", 'tenant_id' => $tenant->id], [
                'name' => 'Miami Hub',
                'address' => '8400 NW 25th St',
                'city' => 'Miami',
                'country' => 'USA',
            ]);

            $local = Warehouse::updateOrCreate(['code' => "LOC-{$tenant->id}", 'tenant_id' => $tenant->id], [
                'name' => 'Centro de Distribución Local',
                'address' => 'Av. Central',
                'city' => 'Ciudad',
                'country' => 'Local',
            ]);

            // 6. Customers per Tenant
            $customerNames = ['Juan Perez', 'Maria Lopez', 'Carlos Ruiz', 'Ana Smith', 'Pedro Gomez'];
            foreach ($customerNames as $index => $name) {
                $email = strtolower(str_replace(' ', '.', $name)) . "_{$tenant->id}@test.com";
                $user = User::updateOrCreate(['email' => $email], [
                    'tenant_id' => $tenant->id,
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'email_verified_at' => ($index % 2 == 0) ? now() : null, // Every second user unverified
                ]);

                $boxPrefix = strtoupper(substr($tData['subdomain'], 0, 3));
                $customer = Customer::updateOrCreate(['user_id' => $user->id], [
                    'tenant_id' => $tenant->id,
                    'box_number' => "{$boxPrefix}-" . (5000 + $index),
                    'identification_number' => "ID-" . rand(1000, 9999),
                    'phone' => '507-6000-000' . $index,
                ]);

                // 7. Random Packages per Customer (Some will have 0 for 'Inactive' scenario)
                if ($index < 3) {
                    $statuses = ['prealert', 'received', 'in_transit', 'arrived', 'delivered'];
                    for ($i = 0; $i < 3; $i++) {
                        $track = strtoupper(Str::random(12));
                        Package::updateOrCreate(['tracking_number' => $track], [
                            'tenant_id' => $tenant->id,
                            'customer_id' => $customer->id,
                            'warehouse_id' => ($i % 2 == 0) ? $miami->id : $local->id,
                            'status' => $statuses[array_rand($statuses)],
                            'description' => 'Producto de prueba ' . ($i + 1),
                            'weight' => rand(1, 20),
                            'declared_value' => rand(10, 500),
                        ]);
                    }
                }

                // 8. Random Invoices per Customer (Scenarios: Paid, Unpaid, Overdue)
                // Overdue
                Invoice::updateOrCreate(['number' => "INV-OLD-{$tenant->id}-{$index}"], [
                    'tenant_id' => $tenant->id,
                    'customer_id' => $customer->id,
                    'total' => rand(20, 100),
                    'status' => 'unpaid',
                    'due_date' => now()->subDays(rand(5, 30)),
                ]);

                // Paid
                Invoice::updateOrCreate(['number' => "INV-PAID-{$tenant->id}-{$index}"], [
                    'tenant_id' => $tenant->id,
                    'customer_id' => $customer->id,
                    'total' => rand(50, 200),
                    'status' => 'paid',
                    'paid_at' => now()->subDays(2),
                ]);
            }

            // 9. Subscription Invoices for the SaaS (Revenue for SuperAdmin)
            SubscriptionInvoice::updateOrCreate(['number' => "SAAS-{$tenant->id}-" . date('Ym')], [
                'tenant_id' => $tenant->id,
                'plan_id' => $tenant->plan_id,
                'amount' => $tData['plan']->price,
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }
    }
}
