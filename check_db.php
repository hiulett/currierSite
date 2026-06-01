<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;

$t = Tenant::find(1);
if ($t) {
    echo "ID: " . $t->id . "\n";
    echo "Name: " . $t->name . "\n";
    echo "Subdomain: " . $t->subdomain . "\n";
    echo "Domain: " . ($t->domain ?? 'NULL') . "\n";
} else {
    echo "No tenant found with ID 1\n";
}
