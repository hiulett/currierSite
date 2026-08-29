<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class BladeComponentsTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Logy Components Test',
            'subdomain' => 'logycomponents',
            'domain' => 'logycomponents.localhost',
            'status' => 'active',
            'settings_json' => ['currency' => 'USD', 'points_per_pound' => 3],
        ]);

        $this->admin = User::create([
            'name' => 'Admin Componentes',
            'email' => 'admincomponents@logycomponents.localhost',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
            'tenant_id' => $this->tenant->id,
        ]);
        $this->admin->email_verified_at = now();
        $this->admin->save();

        session(['tenant_id' => $this->tenant->id]);
    }

    public function test_loyalty_settings_uses_page_header_component()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('builder.loyalty'));

        $response->assertOk();
        $response->assertSee('Configuración de Fidelización');
        $response->assertSee('Reglas Generales');
    }

    public function test_admin_layout_renders_navigate_links()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('builder.rewards'));

        $response->assertOk();
        $response->assertSee('wire:navigate', false);
    }

    public function test_rewards_settings_uses_card_components()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('builder.rewards'));

        $response->assertOk();
        $response->assertSee('Catálogo de Recompensas');
        $response->assertSee('Recompensas Configuradas');
    }

    public function test_customer_list_modal_uses_x_modal_component()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('logistics.customers'));

        $response->assertOk();
        $response->assertSee('customerModal');
        $response->assertSee('Nuevo Cliente');
    }
}
