<?php

namespace Tests\Feature;

use App\Livewire\Billing\CreateInvoice;
use App\Models\Customer;
use App\Models\LoyaltyPointsHistory;
use App\Models\LoyaltyRule;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class BillingFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Logy Flow Test',
            'subdomain' => 'logyflow',
            'domain' => 'logyflow.localhost',
            'status' => 'active',
            'settings_json' => ['currency' => 'USD', 'points_per_pound' => 3],
        ]);

        $this->admin = User::create([
            'name' => 'Admin Flow',
            'email' => 'adminflow@logyflow.localhost',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
            'tenant_id' => $this->tenant->id,
        ]);
        $this->admin->email_verified_at = now();
        $this->admin->save();

        session(['tenant_id' => $this->tenant->id]);

        LoyaltyRule::create([
            'tenant_id' => $this->tenant->id,
            'points_per_pound' => 3.00,
            'enabled' => true,
            'apply_on' => 'invoice',
            'eligible_rate_types' => ['regular'],
        ]);
    }

    protected function makeCustomer(string $box, string $rateType = 'regular'): Customer
    {
        $user = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Cliente '.$box,
            'email' => strtolower($box).'@logyflow.localhost',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);
        $user->email_verified_at = now();
        $user->save();

        return Customer::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $user->id,
            'box_number' => $box,
            'balance' => 0,
            'points' => 0,
            'rate_type' => $rateType,
            'is_loyalty_eligible' => true,
        ]);
    }

    public function test_create_invoice_awards_points_to_eligible_customer()
    {
        $this->actingAs($this->admin);

        $customer = $this->makeCustomer('FLOW-REG');

        Livewire::test(CreateInvoice::class)
            ->set('box_number', $customer->box_number)
            ->set('items', [[
                'description' => 'Flete Aéreo - TEST',
                'quantity' => 10,
                'unit_price' => 2.50,
                'total' => 25.00,
            ]])
            ->call('save');

        $fresh = $customer->fresh();
        $this->assertEquals(25.00, $fresh->balance);
        $this->assertSame(30, $fresh->points);
        $this->assertSame(1, LoyaltyPointsHistory::where('customer_id', $customer->id)
            ->where('type', 'earn')
            ->count());
    }

    public function test_create_invoice_does_not_award_points_to_reseller()
    {
        $this->actingAs($this->admin);

        $customer = $this->makeCustomer('FLOW-RES', 'reseller');

        Livewire::test(CreateInvoice::class)
            ->set('box_number', $customer->box_number)
            ->set('items', [[
                'description' => 'Flete Aéreo - TEST',
                'quantity' => 10,
                'unit_price' => 2.50,
                'total' => 25.00,
            ]])
            ->call('save');

        $fresh = $customer->fresh();
        $this->assertEquals(25.00, $fresh->balance);
        $this->assertSame(0, $fresh->points);
        $this->assertSame(0, LoyaltyPointsHistory::where('customer_id', $customer->id)->count());
    }
}
