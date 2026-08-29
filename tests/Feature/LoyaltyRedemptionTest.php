<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\LoyaltyLevel;
use App\Models\LoyaltyRule;
use App\Models\RedemptionHistory;
use App\Models\Reward;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\LevelUpNotification;
use App\Notifications\RedemptionNotification;
use App\Notifications\RewardUnlockedNotification;
use App\Services\Loyalty\LoyaltyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class LoyaltyRedemptionTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $customerUser;

    protected $admin;

    protected $customer;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Logy Redeem Test',
            'subdomain' => 'logyredeem',
            'domain' => 'logyredeem.localhost',
            'status' => 'active',
            'settings_json' => ['currency' => 'USD'],
        ]);

        $this->customerUser = User::create([
            'name' => 'Cliente Canje',
            'email' => 'canje@logyredeem.localhost',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'tenant_id' => $this->tenant->id,
        ]);
        $this->customerUser->email_verified_at = now();
        $this->customerUser->save();

        $this->admin = User::create([
            'name' => 'Admin Canjes',
            'email' => 'admin@logyredeem.localhost',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'tenant_id' => $this->tenant->id,
        ]);
        $this->admin->email_verified_at = now();
        $this->admin->save();

        $this->customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->customerUser->id,
            'box_number' => 'LGX-REDEEM-1',
            'balance' => 0,
            'points' => 1000,
            'rate_type' => 'regular',
            'is_loyalty_eligible' => true,
        ]);

        session(['tenant_id' => $this->tenant->id]);

        LoyaltyRule::create([
            'tenant_id' => $this->tenant->id,
            'points_per_pound' => 3.00,
            'enabled' => true,
            'apply_on' => 'invoice',
            'eligible_rate_types' => ['regular'],
        ]);

        $this->service = app(LoyaltyService::class);
    }

    protected function makeReward(array $overrides = []): Reward
    {
        return Reward::create(array_merge([
            'tenant_id' => $this->tenant->id,
            'name' => '12 lb gratis',
            'type' => 'free_pounds',
            'points_cost' => 900,
            'value' => 12,
            'is_active' => true,
            'stock' => null,
            'sort_order' => 900,
        ], $overrides));
    }

    public function test_redeem_deducts_points_and_registers_audit()
    {
        $reward = $this->makeReward();

        $redemption = $this->service->redeem($this->customer, $reward);

        $this->assertSame('12 lb gratis', $redemption->reward_name);
        $this->assertSame(900, $redemption->points_spent);
        $this->assertSame(100, $this->customer->fresh()->points);
        $this->assertSame(1, RedemptionHistory::where('customer_id', $this->customer->id)->count());

        $this->assertDatabaseHas('loyalty_points_history', [
            'customer_id' => $this->customer->id,
            'type' => 'spend',
            'points' => -900,
            'reference_type' => Reward::class,
        ]);
    }

    public function test_redeem_rejects_insufficient_points()
    {
        $reward = $this->makeReward(['points_cost' => 1500]);
        $this->customer->update(['points' => 500]);

        try {
            $this->service->redeem($this->customer, $reward);
            $this->fail('Debió lanzarse una excepción por puntos insuficientes.');
        } catch (HttpException $e) {
            $this->assertSame(422, $e->getStatusCode());
        }

        $this->assertSame(500, $this->customer->fresh()->points);
        $this->assertSame(0, RedemptionHistory::count());
    }

    public function test_redeem_rejects_non_eligible_customer()
    {
        $reward = $this->makeReward();
        $this->customer->update(['rate_type' => 'reseller']);

        try {
            $this->service->redeem($this->customer, $reward);
            $this->fail('Debió lanzarse una excepción por cliente no elegible.');
        } catch (HttpException $e) {
            $this->assertSame(403, $e->getStatusCode());
        }

        $this->assertSame(1000, $this->customer->fresh()->points);
        $this->assertSame(0, RedemptionHistory::count());
    }

    public function test_redeem_decrements_stock_for_physical_rewards()
    {
        $reward = $this->makeReward(['type' => 'physical', 'stock' => 5]);

        $this->service->redeem($this->customer, $reward);

        $this->assertSame(4, $reward->fresh()->stock);
    }

    public function test_redeem_notifies_admin()
    {
        $reward = $this->makeReward();

        $this->service->redeem($this->customer, $reward);

        $this->assertSame(1, $this->admin->notifications()
            ->where('type', RedemptionNotification::class)
            ->count());
    }

    public function test_accrual_fires_level_up_and_reward_unlocked_notifications()
    {
        LoyaltyLevel::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Silver',
            'min_points' => 300,
            'max_points' => null,
            'multiplier' => 1.00,
            'free_pounds' => 3,
            'priority' => 300,
            'is_active' => true,
        ]);

        $reward = $this->makeReward(['points_cost' => 900]);
        $this->customer->update(['points' => 290]);

        $invoice = Invoice::create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'number' => 'INV-REDEEM-1',
            'subtotal' => 25,
            'tax' => 0,
            'total' => 25,
            'status' => 'unpaid',
            'due_date' => now()->addDays(7),
        ]);
        InvoiceItem::create([
            'tenant_id' => $this->tenant->id,
            'invoice_id' => $invoice->id,
            'description' => 'Flete Aéreo - TEST',
            'quantity' => 10,
            'unit_price' => 2.5,
            'total' => 25,
        ]);

        $this->service->awardPointsForInvoice($invoice);

        // 290 + 30 = 320 → cruza Silver (300) pero no la recompensa (900)
        $this->assertSame(1, $this->customerUser->notifications()
            ->where('type', LevelUpNotification::class)
            ->count());
        $this->assertSame(0, $this->customerUser->notifications()
            ->where('type', RewardUnlockedNotification::class)
            ->count());

        // Ahora acumular hasta cruzar la recompensa
        $invoice2 = Invoice::create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'number' => 'INV-REDEEM-2',
            'subtotal' => 500,
            'tax' => 0,
            'total' => 500,
            'status' => 'unpaid',
            'due_date' => now()->addDays(7),
        ]);
        InvoiceItem::create([
            'tenant_id' => $this->tenant->id,
            'invoice_id' => $invoice2->id,
            'description' => 'Flete Aéreo - TEST',
            'quantity' => 200,
            'unit_price' => 2.5,
            'total' => 500,
        ]);

        $this->service->awardPointsForInvoice($invoice2);

        $this->assertSame(1, $this->customerUser->notifications()
            ->where('type', RewardUnlockedNotification::class)
            ->count());
    }
}
