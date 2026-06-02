<?php

namespace Tests\Feature;

use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Tenant;
use App\Models\User;
use App\Livewire\Billing\InvoiceList;
use App\Livewire\Billing\CreateInvoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BillingTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $admin;
    protected $customer;

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
            'settings_json' => ['currency' => 'USD', 'default_tax' => 7],
        ]);

        // Create an Admin user
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.localhost',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'tenant_id' => $this->tenant->id,
        ]);

        // Set session tenant_id
        session(['tenant_id' => $this->tenant->id]);

        // Create a Customer
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
            'balance' => 100.00,
        ]);
    }

    public function test_can_delete_invoice_and_restore_customer_balance()
    {
        $this->actingAs($this->admin);

        // Create an unpaid invoice
        $invoice = Invoice::create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'number' => 'INV-001',
            'subtotal' => 50.00,
            'tax' => 3.50,
            'total' => 53.50,
            'status' => 'unpaid',
            'due_date' => now()->addDays(7),
        ]);

        // Prior balance is 100.00
        $this->assertEquals(100.00, $this->customer->fresh()->balance);

        // Run Livewire component and delete invoice
        Livewire::test(InvoiceList::class)
            ->call('deleteInvoice', $invoice->id);

        // Assert invoice is deleted from database
        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);

        // Assert customer balance is decremented (restored) by the invoice total (53.50)
        // 100.00 - 53.50 = 46.50
        $this->assertEquals(46.50, $this->customer->fresh()->balance);
    }

    public function test_can_search_and_select_customer_in_create_invoice()
    {
        $this->actingAs($this->admin);

        // Test Livewire customer search
        Livewire::test(CreateInvoice::class)
            ->set('customer_search', 'Customer')
            ->assertSet('customer_results', function($results) {
                return count($results) === 1 && $results[0]['id'] === $this->customer->id;
            })
            ->call('selectCustomer', $this->customer->id)
            ->assertSet('box_number', 'PTY-12345')
            ->assertSet('found_customer.id', $this->customer->id)
            ->assertSet('customer_search', '')
            ->assertSet('customer_results', []);
    }
}
