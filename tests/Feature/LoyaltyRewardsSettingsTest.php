<?php

namespace Tests\Feature;

use App\Livewire\Builder\RewardsSettings;
use App\Models\Reward;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class LoyaltyRewardsSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Logy Rewards Test',
            'subdomain' => 'logyrewards',
            'domain' => 'logyrewards.localhost',
            'status' => 'active',
            'settings_json' => ['currency' => 'USD'],
        ]);

        $this->admin = User::create([
            'name' => 'Admin Recompensas',
            'email' => 'adminrewards@logyrewards.localhost',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
            'tenant_id' => $this->tenant->id,
        ]);
        $this->admin->email_verified_at = now();
        $this->admin->save();

        session(['tenant_id' => $this->tenant->id]);
    }

    public function test_rewards_settings_route_loads()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('builder.rewards'));
        $response->assertOk();
        $response->assertSee('Catálogo de Recompensas');
    }

    public function test_can_create_reward_via_component()
    {
        $this->actingAs($this->admin);

        Livewire::test(RewardsSettings::class)
            ->set('name', '20 lb gratis')
            ->set('type', 'free_pounds')
            ->set('points_cost', 1500)
            ->set('value', 20)
            ->set('is_active', true)
            ->call('saveReward');

        $this->assertDatabaseHas('reward_catalog', [
            'tenant_id' => $this->tenant->id,
            'name' => '20 lb gratis',
            'type' => 'free_pounds',
            'points_cost' => 1500,
            'value' => 20,
        ]);
    }

    public function test_can_edit_reward_via_component()
    {
        $this->actingAs($this->admin);

        $reward = Reward::create([
            'tenant_id' => $this->tenant->id,
            'name' => '3 lb gratis',
            'type' => 'free_pounds',
            'points_cost' => 300,
            'value' => 3,
            'is_active' => true,
            'sort_order' => 300,
        ]);

        Livewire::test(RewardsSettings::class)
            ->call('editReward', $reward->id)
            ->set('points_cost', 350)
            ->call('saveReward');

        $this->assertSame(350, $reward->fresh()->points_cost);
    }

    public function test_can_toggle_active_and_delete_reward()
    {
        $this->actingAs($this->admin);

        $reward = Reward::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Gorra LOGY',
            'type' => 'physical',
            'points_cost' => 500,
            'value' => 1,
            'is_active' => true,
            'stock' => 10,
            'sort_order' => 500,
        ]);

        Livewire::test(RewardsSettings::class)
            ->call('toggleActive', $reward->id);

        $this->assertFalse($reward->fresh()->is_active);

        Livewire::test(RewardsSettings::class)
            ->call('deleteReward', $reward->id);

        $this->assertDatabaseMissing('reward_catalog', ['id' => $reward->id]);
    }

    public function test_rewards_settings_route_requires_admin()
    {
        $response = $this->get(route('builder.rewards'));
        $response->assertRedirect(route('login'));
    }
}
