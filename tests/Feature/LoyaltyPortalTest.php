<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\LoyaltyLevel;
use App\Models\LoyaltyRule;
use App\Models\Reward;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Loyalty\LoyaltyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoyaltyPortalTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $customerUser;

    protected $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Logy Portal Test',
            'subdomain' => 'logyportal',
            'domain' => 'logyportal.localhost',
            'status' => 'active',
            'settings_json' => ['currency' => 'USD'],
        ]);

        $this->customerUser = User::create([
            'name' => 'Cliente Portal',
            'email' => 'portal@logyportal.localhost',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'tenant_id' => $this->tenant->id,
        ]);
        $this->customerUser->email_verified_at = now();
        $this->customerUser->save();

        $this->customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->customerUser->id,
            'box_number' => 'LGX-PORTAL-1',
            'balance' => 0,
            'points' => 500,
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

        LoyaltyLevel::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Gold',
            'min_points' => 900,
            'max_points' => null,
            'multiplier' => 1.00,
            'free_pounds' => 12,
            'priority' => 900,
            'is_active' => true,
        ]);

        Reward::create([
            'tenant_id' => $this->tenant->id,
            'name' => '3 lb gratis',
            'type' => 'free_pounds',
            'points_cost' => 300,
            'value' => 3,
            'is_active' => true,
            'stock' => null,
            'sort_order' => 300,
        ]);

        Reward::create([
            'tenant_id' => $this->tenant->id,
            'name' => '12 lb gratis',
            'type' => 'free_pounds',
            'points_cost' => 900,
            'value' => 12,
            'is_active' => true,
            'stock' => null,
            'sort_order' => 900,
        ]);
    }

    public function test_rewards_route_loads_for_customer()
    {
        $this->actingAs($this->customerUser);

        $response = $this->get(route('customer.rewards'));

        $response->assertOk();
        $response->assertSee('Recompensas LOGYPUNTOS');
        $response->assertSee('3 lb gratis');
        $response->assertSee('Canjeable');
        $response->assertSee('LOGYPUNTOS aplica únicamente a clientes con tarifa regular');
    }

    public function test_progress_returns_balance_and_next_target()
    {
        $progress = app(LoyaltyService::class)->progress($this->customer);

        $this->assertSame(500, $progress['balance']);
        $this->assertSame('Silver', $progress['currentLevel']->name);
        $this->assertSame('Gold', $progress['nextLevel']->name);
        $this->assertSame(900, $progress['nextReward']->points_cost);
        $this->assertSame(400, $progress['pointsToNext']);
        $this->assertSame(56, $progress['percent']);
    }

    public function test_rewards_route_redirects_when_no_customer_profile()
    {
        $otherUser = User::create([
            'name' => 'Sin Perfil',
            'email' => 'sinperfil@logyportal.localhost',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'tenant_id' => $this->tenant->id,
        ]);
        $otherUser->email_verified_at = now();
        $otherUser->save();

        $this->actingAs($otherUser);

        $response = $this->get(route('customer.rewards'));

        $response->assertOk();
        $response->assertSee('Perfil no encontrado');
    }
}
