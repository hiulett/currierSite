<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class DashboardRenderTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Logy Dashboard Test',
            'subdomain' => 'logydashboard',
            'domain' => 'logydashboard.localhost',
            'status' => 'active',
            'settings_json' => ['currency' => 'USD'],
        ]);

        $this->admin = User::create([
            'name' => 'Admin Dashboard',
            'email' => 'admindash@logydashboard.localhost',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
            'tenant_id' => $this->tenant->id,
        ]);
        $this->admin->email_verified_at = now();
        $this->admin->save();

        session(['tenant_id' => $this->tenant->id]);
    }

    public function test_dashboard_renders_charts_chunk_and_tailwind_stats()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('dashboard'));

        $response->assertOk();
        // El chunk de Chart.js (code-split) debe inyectarse en la página
        $response->assertSee('chartjs-movement');
        $response->assertSee('/build/assets/charts-', false);
        // KPIs migrados a Tailwind
        $response->assertSee('bg-emerald-500', false);
        $response->assertSee('bg-sky-500', false);
        $response->assertSee('Paquetes Totales');
    }
}
