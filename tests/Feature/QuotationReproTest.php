<?php

namespace Tests\Feature;

use App\Livewire\Billing\CreateQuotation;
use App\Livewire\Billing\QuotationList;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class QuotationReproTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $admin;

    protected $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'uuid' => Str::uuid(),
            'name' => 'Test Tenant',
            'subdomain' => 'test',
            'domain' => 'test.localhost',
            'status' => 'active',
            'settings_json' => ['currency' => 'USD'],
        ]);

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.localhost',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'tenant_id' => $this->tenant->id,
        ]);
        $this->admin->forceFill(['email_verified_at' => now()])->save();

        session(['tenant_id' => $this->tenant->id]);

        $customerUser = User::create([
            'name' => 'Customer User',
            'email' => 'customer@test.localhost',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'tenant_id' => $this->tenant->id,
        ]);

        $this->customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $customerUser->id,
            'box_number' => 'PTY-12345',
            'balance' => 0,
        ]);
    }

    public function test_standalone_save_registered_customer()
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateQuotation::class)
            ->set('is_registered', true)
            ->set('customer_id', $this->customer->id)
            ->set('items', [
                ['item_number' => 'TRK1', 'description' => 'Laptop', 'quantity' => 2, 'price' => 2.50, 'handling_price' => 1, 'total' => 7.00],
            ])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('quotations', [
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
        ]);
        $this->assertEquals(1, Quotation::count());
    }

    public function test_quotation_list_shows_new_quotation_after_save()
    {
        $this->actingAs($this->admin);

        // Save directly first
        Livewire::test(CreateQuotation::class)
            ->set('is_registered', true)
            ->set('customer_id', $this->customer->id)
            ->set('items', [
                ['item_number' => 'TRK1', 'description' => 'Laptop', 'quantity' => 2, 'price' => 2.50, 'handling_price' => 1, 'total' => 7.00],
            ])
            ->call('save')
            ->assertHasNoErrors();

        // Now the list should show it
        Livewire::test(QuotationList::class)
            ->assertSee('COT-00001')
            ->assertSee('Laptop');

        // Simulate the event the nested create component dispatches after saving:
        // the parent list listens to "quotation-saved" and must refresh with the new row.
        Livewire::test(QuotationList::class)
            ->dispatch('quotation-saved')
            ->assertSee('COT-00001');
    }

    public function test_nested_component_in_modal_can_save()
    {
        $this->actingAs($this->admin);

        // Simulate the modal: render the list (which embeds the create component)
        // and interact with the nested create-quotation component directly.
        $list = Livewire::test(QuotationList::class)
            ->assertSeeLivewire('billing.create-quotation');

        $this->assertStringContainsString('billing.create-quotation', $list->html());

        // Saving via the same logic the nested component uses must persist + appear in the grid
        Livewire::test(CreateQuotation::class)
            ->set('is_registered', true)
            ->set('customer_id', $this->customer->id)
            ->set('items', [
                ['item_number' => 'TRK1', 'description' => 'Laptop', 'quantity' => 2, 'price' => 2.50, 'handling_price' => 1, 'total' => 7.00],
            ])
            ->call('save')
            ->assertHasNoErrors();

        Livewire::test(QuotationList::class)
            ->assertSee('COT-00001');

        $this->assertDatabaseHas('quotations', [
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
        ]);
    }

    public function test_quotation_page_route_loads()
    {
        $this->actingAs($this->admin);

        // The billing.view gate is only registered from the DB during app boot,
        // which happens before RefreshDatabase migrates in tests. Define it explicitly.
        Gate::define('billing.view', fn ($user) => true);

        $response = $this->get(route('billing.quotations.index'));
        $response->assertOk();
        $response->assertSee('Gestión de Cotizaciones');
        $response->assertSee('Nueva Cotización');
    }
}
