<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InitialSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Plan::create([
            'name' => 'Starter',
            'price' => 99.00,
            'limit_users' => 5,
            'limit_packages_month' => 500,
        ]);

        \App\Models\Plan::create([
            'name' => 'Professional',
            'price' => 249.00,
            'limit_users' => 20,
            'limit_packages_month' => 5000,
            'has_website_builder' => true,
        ]);

        $tenant = Tenant::create([
            'uuid' => Str::uuid(),
            'name' => 'Empresa Test 1',
            'subdomain' => 'empresa1',
            'status' => 'active',
        ]);

        // Temporarily put tenant_id in session for the trait to pick it up if needed,
        // but here we can just pass it to create()
        session(['tenant_id' => $tenant->id]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Admin Empresa 1',
            'email' => 'admin@empresa1.com',
            'password' => bcrypt('password'),
        ]);

        $warehouse = \App\Models\Warehouse::create([
            'tenant_id' => $tenant->id,
            'name' => 'Miami Air Hub',
            'code' => 'MIA-AIR',
            'address' => '12345 NW 115th Ave',
            'city' => 'Miami',
            'state' => 'FL',
            'zip_code' => '33178',
            'country' => 'USA',
            'service_type' => 'air',
        ]);

        \App\Models\Warehouse::create([
            'tenant_id' => $tenant->id,
            'name' => 'Miami Sea Port',
            'code' => 'MIA-SEA',
            'address' => '2250 NW 114th Ave',
            'city' => 'Miami',
            'state' => 'FL',
            'zip_code' => '33192',
            'country' => 'USA',
            'service_type' => 'maritime',
        ]);

        $customer = \App\Models\Customer::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'box_number' => 'PTY-1001',
            'phone' => '507-6666-6666',
        ]);

        \App\Models\Package::create([
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'warehouse_id' => $warehouse->id,
            'tracking_number' => '1Z9999999999999999',
            'description' => 'Laptop Dell XPS',
            'weight' => 5.5,
            'status' => 'received',
        ]);

        session()->forget('tenant_id');
    }
}
