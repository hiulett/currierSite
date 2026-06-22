<?php

namespace Tests\Feature;

use App\Models\DriverTrip;
use App\Models\Tenant;
use App\Models\User;
use App\Livewire\Billing\DriverTripList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class DriverTripTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Tenant
        $this->tenant = Tenant::create([
            'uuid' => Str::uuid(),
            'name' => 'Test Tenant',
            'subdomain' => 'test',
            'domain' => 'test.localhost',
            'status' => 'active',
            'settings_json' => ['currency' => 'USD'],
        ]);

        // Create an Admin user
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.localhost',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
            'tenant_id' => $this->tenant->id,
        ]);
        $this->admin->email_verified_at = now();
        $this->admin->save();

        // Set session tenant_id
        session(['tenant_id' => $this->tenant->id]);
    }

    public function test_driver_trip_route_renders_successfully()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('billing.driver-trips.index'));
        $response->assertStatus(200);
        $response->assertSeeLivewire(DriverTripList::class);
    }

    public function test_automatic_revenue_calculation_on_saving()
    {
        $trip = DriverTrip::create([
            'tenant_id' => $this->tenant->id,
            'date' => '2026-06-22',
            'driver_name' => 'TGR',
            'company_name' => 'GLC PANAMA',
            'description' => 'TOCUMEN A HOWARD',
            'outsourcing_cost' => 150.00,
            'final_client_price' => 200.00,
            'invoice_number' => '505',
            'invoice_status' => 'PAGADA',
            'driver_payment_status' => 'PAGADA',
        ]);

        // Assert revenue is auto-calculated: 200.00 - 150.00 = 50.00
        $this->assertEquals(50.00, floatval($trip->fresh()->revenue));
    }

    public function test_can_create_driver_trip_via_livewire()
    {
        $this->actingAs($this->admin);

        Livewire::test(DriverTripList::class)
            ->call('initForm')
            ->set('date', '2026-06-22')
            ->set('driver_name', 'TGR')
            ->set('company_name', 'LOGY FREIGHT')
            ->set('description', 'PANALPHINA A LLANO BONITO')
            ->set('outsourcing_cost', 120)
            ->set('final_client_price', 100)
            ->set('invoice_status', 'PENDIENTE')
            ->set('driver_payment_status', 'PAGADA')
            ->call('saveTrip');

        $this->assertDatabaseHas('driver_trips', [
            'tenant_id' => $this->tenant->id,
            'driver_name' => 'TGR',
            'company_name' => 'LOGY FREIGHT',
            'outsourcing_cost' => 120,
            'final_client_price' => 100,
            'revenue' => -20, // Negative utility is allowed/calculated correctly
        ]);
    }

    public function test_can_edit_driver_trip_via_livewire()
    {
        $this->actingAs($this->admin);

        $trip = DriverTrip::create([
            'tenant_id' => $this->tenant->id,
            'date' => '2026-06-22',
            'driver_name' => 'TGR',
            'company_name' => 'GLC PANAMA',
            'description' => 'AMSA A HOWARD',
            'outsourcing_cost' => 120.00,
            'final_client_price' => 185.00,
            'invoice_number' => '505',
            'invoice_status' => 'PENDIENTE',
            'driver_payment_status' => 'PENDIENTE',
        ]);

        Livewire::test(DriverTripList::class)
            ->call('editTrip', $trip->id)
            ->assertSet('driver_name', 'TGR')
            ->set('outsourcing_cost', 100.00)
            ->set('invoice_status', 'PAGADA')
            ->call('saveTrip');

        $updatedTrip = $trip->fresh();
        $this->assertEquals(100.00, floatval($updatedTrip->outsourcing_cost));
        $this->assertEquals('PAGADA', $updatedTrip->invoice_status);
        // Utility should recalculate: 185.00 - 100.00 = 85.00
        $this->assertEquals(85.00, floatval($updatedTrip->revenue));
    }

    public function test_can_delete_driver_trip_via_livewire()
    {
        $this->actingAs($this->admin);

        $trip = DriverTrip::create([
            'tenant_id' => $this->tenant->id,
            'date' => '2026-06-22',
            'driver_name' => 'TGR',
            'company_name' => 'UNLIMITED TRANSPORT',
            'description' => 'TOCUMEN A PANAPARK PANEL',
            'outsourcing_cost' => 90.00,
            'final_client_price' => 150.00,
            'invoice_status' => 'PENDIENTE',
            'driver_payment_status' => 'PENDIENTE',
        ]);

        $this->assertDatabaseHas('driver_trips', ['id' => $trip->id]);

        Livewire::test(DriverTripList::class)
            ->call('deleteTrip', $trip->id);

        $this->assertDatabaseMissing('driver_trips', ['id' => $trip->id]);
    }

    public function test_driver_trip_validation_rules()
    {
        $this->actingAs($this->admin);

        Livewire::test(DriverTripList::class)
            ->call('initForm')
            ->set('date', '')
            ->set('driver_name', '')
            ->set('company_name', '')
            ->set('description', '')
            ->set('outsourcing_cost', -5)
            ->set('final_client_price', -10)
            ->call('saveTrip')
            ->assertHasErrors([
                'date' => 'required',
                'driver_name' => 'required',
                'company_name' => 'required',
                'description' => 'required',
                'outsourcing_cost' => 'min',
                'final_client_price' => 'min',
            ]);
    }
}
