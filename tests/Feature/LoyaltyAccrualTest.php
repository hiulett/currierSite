<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\LoyaltyLevel;
use App\Models\LoyaltyPointsHistory;
use App\Models\LoyaltyRule;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Loyalty\LoyaltyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoyaltyAccrualTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $user;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Logy Accrual Test',
            'subdomain' => 'logyaccrual',
            'domain' => 'logyaccrual.localhost',
            'status' => 'active',
            'settings_json' => ['currency' => 'USD'],
        ]);

        $this->user = User::create([
            'name' => 'Cliente Accrual',
            'email' => 'accrual@logyaccrual.localhost',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'tenant_id' => $this->tenant->id,
        ]);
        $this->user->email_verified_at = now();
        $this->user->save();

        session(['tenant_id' => $this->tenant->id]);

        LoyaltyRule::create([
            'tenant_id' => $this->tenant->id,
            'points_per_pound' => 3.00,
            'enabled' => true,
            'apply_on' => 'invoice',
            'eligible_rate_types' => ['regular'],
        ]);

        $this->service = app(LoyaltyService::class);
    }

    protected function makeCustomer(array $overrides = []): Customer
    {
        return Customer::create(array_merge([
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->user->id,
            'box_number' => 'LGX-ACC-'.random_int(1000, 9999),
            'balance' => 0,
            'points' => 0,
            'rate_type' => 'regular',
            'is_loyalty_eligible' => true,
        ], $overrides));
    }

    protected function makeInvoice(Customer $customer, float $lbs): Invoice
    {
        $invoice = Invoice::create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $customer->id,
            'number' => 'INV-ACC-'.random_int(100, 999),
            'subtotal' => $lbs * 2.5,
            'tax' => 0,
            'total' => $lbs * 2.5,
            'status' => 'unpaid',
            'due_date' => now()->addDays(7),
        ]);

        InvoiceItem::create([
            'tenant_id' => $this->tenant->id,
            'invoice_id' => $invoice->id,
            'description' => 'Flete Aéreo - TEST',
            'quantity' => $lbs,
            'unit_price' => 2.5,
            'total' => $lbs * 2.5,
        ]);

        return $invoice;
    }

    public function test_regular_eligible_customer_earns_three_points_per_pound()
    {
        $customer = $this->makeCustomer();
        $invoice = $this->makeInvoice($customer, 10);

        $earned = $this->service->awardPointsForInvoice($invoice);

        $this->assertSame(30, $earned);
        $this->assertSame(30, $customer->fresh()->points);
        $this->assertSame(1, LoyaltyPointsHistory::where('customer_id', $customer->id)->count());
        $this->assertDatabaseHas('loyalty_points_history', [
            'customer_id' => $customer->id,
            'type' => 'earn',
            'points' => 30,
            'reference_type' => Invoice::class,
        ]);
    }

    public function test_award_is_idempotent_per_invoice()
    {
        $customer = $this->makeCustomer();
        $invoice = $this->makeInvoice($customer, 10);

        $this->service->awardPointsForInvoice($invoice);
        $again = $this->service->awardPointsForInvoice($invoice);

        $this->assertNull($again);
        $this->assertSame(30, $customer->fresh()->points);
        $this->assertSame(1, LoyaltyPointsHistory::where('customer_id', $customer->id)->count());
    }

    public function test_reseller_does_not_earn_points()
    {
        $customer = $this->makeCustomer(['rate_type' => 'reseller']);
        $invoice = $this->makeInvoice($customer, 10);

        $earned = $this->service->awardPointsForInvoice($invoice);

        $this->assertNull($earned);
        $this->assertSame(0, $customer->fresh()->points);
        $this->assertSame(0, LoyaltyPointsHistory::where('customer_id', $customer->id)->count());
    }

    public function test_customer_with_flag_disabled_does_not_earn_points()
    {
        $customer = $this->makeCustomer(['is_loyalty_eligible' => false]);
        $invoice = $this->makeInvoice($customer, 10);

        $earned = $this->service->awardPointsForInvoice($invoice);

        $this->assertNull($earned);
        $this->assertSame(0, $customer->fresh()->points);
    }

    public function test_accumulation_continues_beyond_1500_points()
    {
        $customer = $this->makeCustomer(['points' => 1500]);
        $invoice = $this->makeInvoice($customer, 30);

        $this->service->awardPointsForInvoice($invoice);

        $this->assertSame(1590, $customer->fresh()->points);
    }

    public function test_referrer_earns_ten_percent_bonus()
    {
        $referrerUser = User::create([
            'name' => 'Referidor',
            'email' => 'referidor@logyaccrual.localhost',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'tenant_id' => $this->tenant->id,
        ]);
        $referrerUser->email_verified_at = now();
        $referrerUser->save();

        $referrer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $referrerUser->id,
            'box_number' => 'LGX-REF-'.random_int(1000, 9999),
            'balance' => 0,
            'points' => 0,
            'rate_type' => 'regular',
            'is_loyalty_eligible' => true,
        ]);

        $customer = $this->makeCustomer(['referrer_id' => $referrer->id]);
        $invoice = $this->makeInvoice($customer, 10);

        $this->service->awardPointsForInvoice($invoice);

        // 30 pts → bono 10% = 3 pts
        $this->assertSame(3, $referrer->fresh()->points);
        $this->assertDatabaseHas('loyalty_points_history', [
            'customer_id' => $referrer->id,
            'type' => 'earn',
            'points' => 3,
            'reference_type' => Invoice::class,
        ]);
    }

    public function test_referrer_bonus_is_idempotent()
    {
        $referrerUser = User::create([
            'name' => 'Referidor Idem',
            'email' => 'referidoridem@logyaccrual.localhost',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'tenant_id' => $this->tenant->id,
        ]);
        $referrerUser->email_verified_at = now();
        $referrerUser->save();

        $referrer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $referrerUser->id,
            'box_number' => 'LGX-REFIDEM-'.random_int(1000, 9999),
            'balance' => 0,
            'points' => 0,
            'rate_type' => 'regular',
            'is_loyalty_eligible' => true,
        ]);

        $customer = $this->makeCustomer(['referrer_id' => $referrer->id]);
        $invoice = $this->makeInvoice($customer, 10);

        $this->service->awardPointsForInvoice($invoice);
        $this->service->awardPointsForInvoice($invoice);

        $this->assertSame(3, $referrer->fresh()->points);
        $this->assertSame(1, LoyaltyPointsHistory::where('customer_id', $referrer->id)->count());
    }

    public function test_referrer_bonus_not_awarded_to_non_eligible_referrer()
    {
        $referrerUser = User::create([
            'name' => 'Referidor Revendedor',
            'email' => 'referidorreseller@logyaccrual.localhost',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'tenant_id' => $this->tenant->id,
        ]);
        $referrerUser->email_verified_at = now();
        $referrerUser->save();

        $referrer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $referrerUser->id,
            'box_number' => 'LGX-REFRES-'.random_int(1000, 9999),
            'balance' => 0,
            'points' => 0,
            'rate_type' => 'reseller',
            'is_loyalty_eligible' => true,
        ]);

        $customer = $this->makeCustomer(['referrer_id' => $referrer->id]);
        $invoice = $this->makeInvoice($customer, 10);

        $this->service->awardPointsForInvoice($invoice);

        $this->assertSame(0, $referrer->fresh()->points);
        $this->assertSame(0, LoyaltyPointsHistory::where('customer_id', $referrer->id)->count());
    }

    public function test_level_up_updates_customer_tier()
    {
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

        $customer = $this->makeCustomer(['points' => 290]);
        $invoice = $this->makeInvoice($customer, 10);

        $this->service->awardPointsForInvoice($invoice);

        $fresh = $customer->fresh();
        $this->assertSame(320, $fresh->points);
        $this->assertNotNull($fresh->loyalty_level_id);
        $this->assertSame('Silver', $fresh->level->name);
    }
}
