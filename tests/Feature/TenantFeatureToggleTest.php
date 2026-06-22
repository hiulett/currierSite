<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantFeatureToggleTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'Logy Test Company',
            'subdomain' => 'logytest',
            'domain' => 'logytest.localhost',
            'status' => 'active',
            'settings_json' => ['currency' => 'USD'],
            'features_json' => [
                'modules' => [
                    'expenses' => 'disabled',
                    'recepcion_carga' => 'hidden',
                    'dashboard' => 'active'
                ],
                'sub_features' => [
                    'download_reports' => false,
                    'change_company_name' => true
                ]
            ]
        ]);

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@logytest.localhost',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
            'tenant_id' => $this->tenant->id,
        ]);
        $this->admin->email_verified_at = now();
        $this->admin->save();

        session(['tenant_id' => $this->tenant->id]);
    }

    public function test_hidden_feature_returns_404()
    {
        $this->actingAs($this->admin);

        // Accessing 'recepcion_carga' (e.g. /recepcion)
        // Let's check routes to find a route protected by the middleware
        $response = $this->get('/recepcion');
        $response->assertStatus(404);
    }

    public function test_disabled_feature_redirects_to_dashboard_with_warning()
    {
        $this->actingAs($this->admin);

        // Accessing 'expenses' (e.g. /egresos) which is configured as 'disabled'
        $response = $this->get('/egresos');
        
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('warning', 'Esta funcionalidad está bloqueada bajo la configuración de su plan.');
    }

    public function test_active_feature_allows_access()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_tenant_subfeature_helpers()
    {
        $this->assertFalse($this->tenant->hasSubFeature('download_reports'));
        $this->assertTrue($this->tenant->hasSubFeature('change_company_name'));
        
        // Default fallback is true
        $this->assertTrue($this->tenant->hasSubFeature('non_existent'));
    }
}
