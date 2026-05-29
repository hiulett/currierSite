<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Customer;
use App\Models\LoyaltyLevel;
use App\Models\Invoice;
use App\Models\PaymentProof;
use App\Models\Manifest;
use App\Models\ManifestItem;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ImplementationDataSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('subdomain', 'logiexpress')->first() ?? Tenant::first();
        if (!$tenant) return;

        // 1. Loyalty Levels
        $bronze = LoyaltyLevel::updateOrCreate(['tenant_id' => $tenant->id, 'name' => 'Bronze'], [
            'min_points' => 0,
            'multiplier' => 1.00,
            'color' => '#cd7f32',
            'icon' => 'award',
            'priority' => 1
        ]);

        $silver = LoyaltyLevel::updateOrCreate(['tenant_id' => $tenant->id, 'name' => 'Silver'], [
            'min_points' => 500,
            'multiplier' => 1.10,
            'color' => '#c0c0c0',
            'icon' => 'zap',
            'priority' => 2
        ]);

        $gold = LoyaltyLevel::updateOrCreate(['tenant_id' => $tenant->id, 'name' => 'Gold'], [
            'min_points' => 2000,
            'multiplier' => 1.25,
            'color' => '#ffd700',
            'icon' => 'star',
            'priority' => 3
        ]);

        // 2. Assign Points to Customers and update their levels
        $customers = Customer::where('tenant_id', $tenant->id)->get();
        foreach ($customers as $index => $customer) {
            $points = [100, 650, 2500, 0][$index % 4];
            $customer->update(['points' => $points]);

            $level = LoyaltyLevel::where('tenant_id', $tenant->id)
                ->where('min_points', '<=', $points)
                ->orderBy('min_points', 'desc')
                ->first();

            if ($level) {
                $customer->update(['loyalty_level_id' => $level->id]);
            }
        }

        // 3. Payment Proofs (Simulation of pending validations)
        $unpaidInvoices = Invoice::where('tenant_id', $tenant->id)->where('status', 'unpaid')->take(2)->get();
        foreach ($unpaidInvoices as $index => $invoice) {
            PaymentProof::updateOrCreate(['invoice_id' => $invoice->id], [
                'tenant_id' => $tenant->id,
                'file_path' => 'payment_proofs/demo_proof.jpg', // Dummy path
                'method' => $index % 2 == 0 ? 'yappy' : 'ach',
                'status' => 'pending',
            ]);

            // Mark invoice as pending validation
            $invoice->update(['status' => 'pending']);
        }

        // 4. Promotions
        Promotion::updateOrCreate(['code' => 'BIENVENIDA2026'], [
            'tenant_id' => $tenant->id,
            'name' => 'Cupón de Bienvenida',
            'type' => 'percentage',
            'value' => 10,
            'is_active' => true,
        ]);

        Promotion::updateOrCreate(['code' => 'VERANOPTY'], [
            'tenant_id' => $tenant->id,
            'name' => 'Promo Verano',
            'type' => 'fixed',
            'value' => 5.00,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'is_active' => true,
        ]);

        // 5. Manifest (Simulation of OCR result)
        $admin = User::where('tenant_id', $tenant->id)->where('role', 'admin')->first();
        $manifest = Manifest::updateOrCreate(['number' => 'MAN-DEMO-2026'], [
            'tenant_id' => $tenant->id,
            'carrier_name' => 'DHL Global Forwarding',
            'carrier_invoice_number' => 'INV-DHL-9988',
            'status' => 'processing',
            'created_by' => $admin?->id,
            'total_items_expected' => 5,
        ]);

        $trackings = ['TRACK001', 'TRACK002', 'TRACK003', 'TRACK004', 'TRACK005'];
        foreach ($trackings as $index => $t) {
            ManifestItem::updateOrCreate(['manifest_id' => $manifest->id, 'tracking_number' => $t], [
                'tenant_id' => $tenant->id,
                'status' => $index < 2 ? 'received' : 'expected',
                'scanned_at' => $index < 2 ? now() : null,
            ]);
        }

        $manifest->update(['total_items_received' => 2]);
    }
}
