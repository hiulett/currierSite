<?php

namespace Tests\Feature;

use App\Livewire\Billing\InvoiceList;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class WhatsAppWebRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;

    protected User $admin;

    protected Customer $customer;

    protected Invoice $invoice;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Web Routes Company',
            'subdomain' => 'webroutes',
            'domain' => 'webroutes.localhost',
            'status' => 'active',
            'settings_json' => [
                'currency' => 'USD',
                'whatsapp_enabled' => true,
                'whatsapp_channel' => 'web',
                'whatsapp_phone_number_id' => '1234567890',
                'whatsapp_token' => 'EAAG-test-token',
                'whatsapp_business_number' => '+50760000000',
            ],
        ]);

        $this->admin = User::create([
            'name' => 'Admin Web',
            'email' => 'admin@webroutes.localhost',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
            'tenant_id' => $this->tenant->id,
        ]);
        $this->admin->email_verified_at = now();
        $this->admin->save();

        $customerUser = User::create([
            'name' => 'Cliente Web',
            'email' => 'cliente@webroutes.localhost',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'tenant_id' => $this->tenant->id,
        ]);

        $this->customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $customerUser->id,
            'box_number' => 'WR1001',
            'phone' => '60000000',
            'balance' => 0,
            'points' => 0,
        ]);

        $this->invoice = Invoice::create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'number' => 'INV-WR-001',
            'subtotal' => 100.00,
            'tax' => 7.00,
            'total' => 107.00,
            'status' => 'unpaid',
            'currency' => 'USD',
            'due_date' => now()->addDays(7)->toDateString(),
        ]);

        session(['tenant_id' => $this->tenant->id]);
    }

    protected function location($response): string
    {
        return (string) $response->headers->get('Location');
    }

    public function test_invoice_route_redirects_to_logged_in_whatsapp_web(): void
    {
        $response = $this->actingAs($this->admin)->get(route('whatsapp.web.invoice', $this->invoice));

        $response->assertRedirect();

        $location = $this->location($response);
        $this->assertStringStartsWith('https://web.whatsapp.com/send?phone=50760000000&text=', $location);
        $this->assertStringContainsString('/portal/facturas', urldecode($location));
        $this->assertStringContainsString('INV-WR-001', urldecode($location));
        $this->assertNotNull($this->invoice->fresh()->whatsapp_sent_at);
    }

    public function test_invoice_route_is_blocked_when_channel_is_api(): void
    {
        $settings = $this->tenant->settings_json;
        $settings['whatsapp_channel'] = 'api';
        $this->tenant->update(['settings_json' => $settings]);

        $response = $this->actingAs($this->admin)->get(route('whatsapp.web.invoice', $this->invoice));

        $response->assertSessionHas('error');
        $this->assertStringNotContainsString('web.whatsapp.com', $this->location($response));
    }

    public function test_password_reset_route_redirects_to_whatsapp_web_and_creates_token(): void
    {
        $response = $this->actingAs($this->admin)->get(route('whatsapp.web.reset', $this->customer));

        $response->assertRedirect();

        $location = $this->location($response);
        $this->assertStringStartsWith('https://web.whatsapp.com/send?phone=50760000000&text=', $location);
        $this->assertStringContainsString('reset-password', urldecode($location));
        $this->assertDatabaseHas('password_reset_tokens', ['email' => 'cliente@webroutes.localhost']);
    }

    public function test_test_route_opens_whatsapp_web_with_the_test_number(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('whatsapp.web.test', ['numero' => '+50761598812']));

        $response->assertRedirect();

        $this->assertStringStartsWith(
            'https://web.whatsapp.com/send?phone=50761598812&text=',
            $this->location($response)
        );
    }

    public function test_invoice_list_renders_new_tab_link_when_channel_is_web(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(InvoiceList::class)
            ->assertSee('target="_blank"', false)
            ->assertSee(route('whatsapp.web.invoice', $this->invoice), false);
    }

    public function test_invoice_list_keeps_queued_button_when_channel_is_api(): void
    {
        $settings = $this->tenant->settings_json;
        $settings['whatsapp_channel'] = 'api';
        $this->tenant->update(['settings_json' => $settings]);

        $this->actingAs($this->admin);

        Livewire::test(InvoiceList::class)
            ->assertDontSee(route('whatsapp.web.invoice', $this->invoice), false)
            ->assertSee('sendWhatsApp', false);
    }
}
