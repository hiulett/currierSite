<?php

namespace Tests\Feature;

use App\Jobs\SendInvoiceWhatsApp;
use App\Livewire\Billing\InvoiceList;
use App\Livewire\Builder\Integrations;
use App\Livewire\Logistics\CustomerList;
use App\Models\AppSetting;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Tenant;
use App\Models\User;
use App\Services\WhatsApp\PasswordResetLink;
use App\Services\WhatsApp\WebChannel;
use App\Services\WhatsAppService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class WhatsAppChannelTest extends TestCase
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
            'name' => 'Canal Test Company',
            'subdomain' => 'canaltest',
            'domain' => 'canaltest.localhost',
            'status' => 'active',
            'settings_json' => [
                'currency' => 'USD',
                'whatsapp_enabled' => true,
                'whatsapp_phone_number_id' => '1234567890',
                'whatsapp_token' => 'EAAG-test-token',
                'whatsapp_business_number' => '+50760000000',
            ],
        ]);

        $this->admin = User::create([
            'name' => 'Admin Canal',
            'email' => 'admin@canaltest.localhost',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
            'tenant_id' => $this->tenant->id,
        ]);
        $this->admin->email_verified_at = now();
        $this->admin->save();

        $customerUser = User::create([
            'name' => 'Cliente Canal',
            'email' => 'cliente@canaltest.localhost',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'tenant_id' => $this->tenant->id,
        ]);

        $this->customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $customerUser->id,
            'box_number' => 'CH1001',
            'phone' => '60000000',
            'balance' => 0,
            'points' => 0,
        ]);

        $this->invoice = Invoice::create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'number' => 'INV-CH-001',
            'subtotal' => 100.00,
            'tax' => 7.00,
            'total' => 107.00,
            'status' => 'unpaid',
            'currency' => 'USD',
            'due_date' => now()->addDays(7)->toDateString(),
        ]);

        session(['tenant_id' => $this->tenant->id]);
    }

    protected function useWebChannel(): void
    {
        $settings = $this->tenant->settings_json;
        $settings['whatsapp_channel'] = 'web';

        $this->tenant->update(['settings_json' => $settings]);
        $this->tenant->refresh();
    }

    public function test_default_channel_is_api(): void
    {
        $this->assertSame('api', app(WhatsAppService::class)->channelName($this->tenant));
    }

    public function test_channel_web_is_resolved_from_tenant_settings(): void
    {
        $this->useWebChannel();

        $this->assertSame('web', app(WhatsAppService::class)->channelName($this->tenant));
    }

    public function test_web_channel_invoice_returns_whatsapp_web_url_with_portal_link(): void
    {
        AppSetting::set('whatsapp_pdf_enabled', '0');
        $this->useWebChannel();

        Http::fake();

        $result = app(WhatsAppService::class)->sendInvoice($this->invoice);

        $this->assertTrue($result['ok']);
        $this->assertSame('web', $result['channel']);
        $this->assertStringStartsWith('https://web.whatsapp.com/send?phone=50760000000&text=', $result['url']);

        $message = urldecode($result['url']);
        $this->assertStringContainsString('INV-CH-001', $message);
        $this->assertStringContainsString('/portal/facturas', $message);

        $this->assertNotNull($this->invoice->fresh()->whatsapp_sent_at);
        Http::assertNothingSent();
    }

    public function test_web_channel_quotation_returns_portal_quotations_link(): void
    {
        AppSetting::set('whatsapp_pdf_enabled', '0');
        $this->useWebChannel();

        $quotation = Quotation::create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'number' => 'COT-CH-001',
            'subtotal' => 50.00,
            'total' => 50.00,
            'status' => 'draft',
            'currency' => 'USD',
        ]);

        Http::fake();

        $result = app(WhatsAppService::class)->sendQuotation($quotation);

        $this->assertTrue($result['ok']);
        $this->assertStringStartsWith('https://web.whatsapp.com/send?phone=50760000000&text=', $result['url']);
        $this->assertStringContainsString('/portal/cotizaciones', urldecode($result['url']));
        Http::assertNothingSent();
    }

    public function test_web_channel_requires_customer_phone(): void
    {
        $this->useWebChannel();
        $this->customer->update(['phone' => null]);

        $result = app(WhatsAppService::class)->sendInvoice($this->invoice);

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('no tiene un teléfono', $result['error']);
    }

    public function test_invoice_list_redirects_to_whatsapp_web_instead_of_dispatching_job(): void
    {
        AppSetting::set('whatsapp_pdf_enabled', '0');
        $this->useWebChannel();
        Bus::fake();
        $this->actingAs($this->admin);

        $expected = app(WhatsAppService::class)->sendInvoice($this->invoice)['url'];
        $this->invoice->update(['whatsapp_sent_at' => null]);

        Livewire::test(InvoiceList::class)
            ->call('sendWhatsApp', $this->invoice->id)
            ->assertRedirect($expected);

        Bus::assertNotDispatched(SendInvoiceWhatsApp::class);
    }

    public function test_integrations_persists_selected_channel(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(Integrations::class)
            ->set('whatsapp_channel', 'web')
            ->call('saveWhatsapp')
            ->assertHasNoErrors();

        $this->assertSame('web', $this->tenant->fresh()->settings_json['whatsapp_channel']);
    }

    public function test_password_reset_link_shares_token_without_exposing_password(): void
    {
        $user = $this->customer->user;
        $originalHash = $user->password;

        $link = app(PasswordResetLink::class)->build($user, $this->customer->phone);

        $this->assertStringContainsString('/reset-password/', $link['url']);
        $this->assertStringContainsString(rawurlencode($user->email), $link['url']);
        $this->assertStringStartsWith('https://web.whatsapp.com/send?phone=50760000000&text=', $link['whatsapp']);
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
        $this->assertSame($originalHash, $user->fresh()->password);
        $this->assertStringContainsString($link['url'], $link['message']);
        $this->assertNull($this->customer->fresh()->temporary_password);
    }

    public function test_customer_list_can_open_password_reset_link_in_whatsapp_web(): void
    {
        $this->actingAs($this->admin);
        $originalHash = $this->customer->user->password;

        Livewire::test(CustomerList::class)
            ->call('sendPasswordResetWhatsApp', $this->customer->id)
            ->assertRedirect();

        $this->assertSame($originalHash, $this->customer->user->fresh()->password);
    }

    public function test_web_channel_url_encodes_message(): void
    {
        $url = WebChannel::url('60000000', "Hola\nmundo & más");

        $this->assertStringStartsWith('https://web.whatsapp.com/send?phone=50760000000&text=', $url);
        $this->assertStringNotContainsString(' ', $url);
        $this->assertStringNotContainsString("\n", $url);
    }
}
