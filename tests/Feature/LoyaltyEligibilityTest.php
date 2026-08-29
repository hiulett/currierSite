<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\LoyaltyLevel;
use App\Models\LoyaltyPointsHistory;
use App\Models\LoyaltyRule;
use App\Models\Reward;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\LoyaltySetupSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoyaltyEligibilityTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Logy Loyalty Test',
            'subdomain' => 'logyloyalty',
            'domain' => 'logyloyalty.localhost',
            'status' => 'active',
            'settings_json' => ['currency' => 'USD', 'points_per_pound' => 1],
        ]);

        $this->user = User::create([
            'name' => 'Cliente Test',
            'email' => 'cliente@logyloyalty.localhost',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'tenant_id' => $this->tenant->id,
        ]);
        $this->user->email_verified_at = now();
        $this->user->save();

        session(['tenant_id' => $this->tenant->id]);
    }

    protected function makeCustomer(array $overrides = []): Customer
    {
        return Customer::create(array_merge([
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->user->id,
            'box_number' => 'LGX-TEST-'.random_int(1000, 9999),
            'balance' => 0,
            'points' => 0,
            'rate_type' => 'regular',
            'is_loyalty_eligible' => true,
        ], $overrides));
    }

    public function test_customer_has_loyalty_eligibility_defaults()
    {
        $customer = $this->makeCustomer();

        $this->assertSame('regular', $customer->rate_type);
        $this->assertTrue($customer->is_loyalty_eligible);
    }

    public function test_customer_relation_with_ledger_and_redemptions()
    {
        $customer = $this->makeCustomer();

        $reward = Reward::create([
            'tenant_id' => $this->tenant->id,
            'name' => '3 lb gratis',
            'type' => 'free_pounds',
            'points_cost' => 300,
            'value' => 3,
        ]);

        $customer->pointsHistory()->create([
            'tenant_id' => $this->tenant->id,
            'points' => 30,
            'type' => 'earn',
            'description' => 'Puntos de prueba',
        ]);

        $customer->redemptions()->create([
            'tenant_id' => $this->tenant->id,
            'reward_id' => $reward->id,
            'points_spent' => 300,
            'reward_name' => '3 lb gratis',
            'free_pounds_granted' => 3,
            'status' => 'completed',
            'redeemed_at' => now(),
        ]);

        $this->assertSame(1, $customer->pointsHistory()->count());
        $this->assertSame(1, $customer->redemptions()->count());
        $this->assertSame('3 lb gratis', $customer->redemptions()->first()->reward_name);
    }

    public function test_loyalty_rule_defaults_to_three_points_per_pound()
    {
        LoyaltyRule::create([
            'tenant_id' => $this->tenant->id,
            'points_per_pound' => 3.00,
            'apply_on' => 'invoice',
            'eligible_rate_types' => ['regular'],
        ]);

        $rule = LoyaltyRule::where('tenant_id', $this->tenant->id)->first();

        $this->assertSame(3.0, (float) $rule->points_per_pound);
        $this->assertTrue($rule->enabled);
        $this->assertSame(['regular'], $rule->eligible_rate_types);
    }

    public function test_loyalty_setup_seeder_is_idempotent()
    {
        (new LoyaltySetupSeeder)->run();
        (new LoyaltySetupSeeder)->run();

        $this->assertSame(3, LoyaltyLevel::where('tenant_id', $this->tenant->id)->count());
        $this->assertSame(3, Reward::where('tenant_id', $this->tenant->id)->count());
        $this->assertSame(1, LoyaltyRule::where('tenant_id', $this->tenant->id)->count());
        $this->assertSame(0, LoyaltyPointsHistory::count());
    }

    public function test_seeder_creates_expected_tiers_and_rewards()
    {
        (new LoyaltySetupSeeder)->run();

        $silver = LoyaltyLevel::where('tenant_id', $this->tenant->id)->where('name', 'Silver')->first();
        $gold = LoyaltyLevel::where('tenant_id', $this->tenant->id)->where('name', 'Gold')->first();
        $platinum = LoyaltyLevel::where('tenant_id', $this->tenant->id)->where('name', 'Platinum')->first();

        $this->assertSame(300, $silver->min_points);
        $this->assertSame(3, $silver->free_pounds);
        $this->assertSame(900, $gold->min_points);
        $this->assertSame(12, $gold->free_pounds);
        $this->assertSame(1500, $platinum->min_points);
        $this->assertSame(20, $platinum->free_pounds);
        $this->assertNull($platinum->max_points);

        $this->assertSame(3, Reward::where('tenant_id', $this->tenant->id)->where('type', 'free_pounds')->count());
        $this->assertSame(3.0, (float) LoyaltyRule::where('tenant_id', $this->tenant->id)->value('points_per_pound'));

        $settings = $this->tenant->fresh()->settings_json;
        $this->assertSame(3.0, (float) ($settings['points_per_pound'] ?? 0));
        $this->assertTrue($settings['loyalty_enabled']);
    }
}
