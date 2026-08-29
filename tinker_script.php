<?php

use App\Models\Customer;
use App\Models\Tenant;
use App\Models\User;

$user = User::where('email', 'test@example.com')->first();
if (! $user) {
    $user = User::create(['name' => 'Test User', 'email' => 'test@example.com', 'password' => bcrypt('password123'), 'role' => 'customer']);
    $tenant = Tenant::first();
    Customer::create(['user_id' => $user->id, 'tenant_id' => $tenant->id, 'box_number' => 'TST-001', 'phone' => '123456789']);
} else {
    $user->update(['password' => bcrypt('password123')]);
} echo 'Email: '.$user->email."\n";
echo "Password: password123\n";
