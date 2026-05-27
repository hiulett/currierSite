<?php

namespace Database\Seeders;

use App\Models\Manifest;
use App\Models\ManifestItem;
use App\Models\Package;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ManifestMockSeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = 1; // LogiExpress
        $admin = User::where('tenant_id', $tenantId)->where('role', 'admin')->first();

        // Scenario 1: Fully Reconciled Manifest (Perfect Match)
        $m1 = Manifest::create([
            'tenant_id' => $tenantId,
            'number' => 'MAN-20240527-001',
            'carrier_name' => 'DHL Global',
            'carrier_invoice_number' => 'DHL-998877',
            'status' => 'reconciled',
            'created_by' => $admin->id,
            'total_items_expected' => 5,
            'total_items_received' => 5,
            'received_at' => now()->subDays(2),
        ]);

        // Get 5 existing packages for this tenant to create a "Match"
        $packages = Package::where('tenant_id', $tenantId)->take(5)->get();
        foreach ($packages as $pkg) {
            ManifestItem::create([
                'manifest_id' => $m1->id,
                'tenant_id' => $tenantId,
                'tracking_number' => $pkg->tracking_number,
                'package_id' => $pkg->id,
                'status' => 'received',
                'scanned_at' => now()->subDays(2)->addMinutes(rand(1, 60)),
            ]);
            $pkg->update(['status' => 'arrived']);
        }

        // Scenario 2: Reconciled with Discrepancies (Missing and Surplus)
        $m2 = Manifest::create([
            'tenant_id' => $tenantId,
            'number' => 'MAN-20240527-002',
            'carrier_name' => 'FedEx Cargo',
            'carrier_invoice_number' => 'FX-554433',
            'status' => 'reconciled',
            'created_by' => $admin->id,
            'total_items_expected' => 10,
            'total_items_received' => 8,
            'received_at' => now()->subDay(),
        ]);

        // 7 Matches
        $p2 = Package::where('tenant_id', $tenantId)->skip(5)->take(7)->get();
        foreach ($p2 as $pkg) {
            ManifestItem::create([
                'manifest_id' => $m2->id,
                'tenant_id' => $tenantId,
                'tracking_number' => $pkg->tracking_number,
                'package_id' => $pkg->id,
                'status' => 'received',
                'scanned_at' => now()->subDay()->addMinutes(rand(1, 30)),
            ]);
        }

        // 3 Missing (In Invoice but not physically present)
        for ($i = 0; $i < 3; $i++) {
            ManifestItem::create([
                'manifest_id' => $m2->id,
                'tenant_id' => $tenantId,
                'tracking_number' => 'MISSING-' . Str::random(8),
                'status' => 'missing',
                'observation' => 'Nunca llegó en el camión.',
            ]);
        }

        // 2 Surplus (Not in Invoice but physically present)
        for ($i = 0; $i < 2; $i++) {
            ManifestItem::create([
                'manifest_id' => $m2->id,
                'tenant_id' => $tenantId,
                'tracking_number' => 'SURPLUS-' . Str::random(8),
                'status' => 'surplus',
                'scanned_at' => now()->subDay()->addMinutes(45),
                'observation' => 'Caja extra no declarada.',
            ]);
        }

        // Scenario 3: Currently Processing Manifest (Live view)
        $m3 = Manifest::create([
            'tenant_id' => $tenantId,
            'number' => 'MAN-20240527-003',
            'carrier_name' => 'Local Trans',
            'carrier_invoice_number' => 'LT-112233',
            'status' => 'processing',
            'created_by' => $admin->id,
            'total_items_expected' => 20,
            'total_items_received' => 5,
        ]);

        // Seed 20 items as expected
        for ($i = 0; $i < 20; $i++) {
            $status = ($i < 5) ? 'received' : 'expected';
            ManifestItem::create([
                'manifest_id' => $m3->id,
                'tenant_id' => $tenantId,
                'tracking_number' => 'EXP-' . (1000 + $i),
                'status' => $status,
                'scanned_at' => ($status === 'received') ? now()->subMinutes(rand(5, 20)) : null,
            ]);
        }
    }
}
