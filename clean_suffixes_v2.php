<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Customer;

echo "Eliminando sufijos numericos (-1, -2, etc) de los casilleros...\n";

$customers = Customer::where('box_number', 'like', '%-%')->get();
$count = 0;

foreach ($customers as $c) {
    // Si contiene un guión, removemos la parte del guión en adelante
    if (str_contains($c->box_number, '-')) {
        $parts = explode('-', $c->box_number);
        $cleanId = $parts[0];

        $c->update([
            'box_number' => $cleanId,
            'box_number_air' => $cleanId,
            'box_number_maritime' => $cleanId
        ]);
        $count++;
    }
}

echo "Limpieza completada. Se limpiaron $count registros.\n";
