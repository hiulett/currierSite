<?php

namespace Tests\Feature;

use App\Jobs\SendInvoiceWhatsApp;
use App\Livewire\Billing\InvoiceList;
use App\Models\AppSetting;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Tenant;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class WhatsAppTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $admin;

    protected $customer;

    protected $invoice;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Logy Test Company',
            'subdomain' => 'logywa',
            'domain' => 'logywa.localhost',
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
            'name' => 'Admin Test',
            'email' => 'admin@logywa.localhost',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
            'tenant_id' => $this->tenant->id,
        ]);
        $this->admin->email_verified_at = now();
        $this->admin->save();

        $customerUser = User::create([
            'name' => 'Cliente WhatsApp',
            'email' => 'cliente@logywa.localhost',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'tenant_id' => $this->tenant->id,
        ]);

        $this->customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $customerUser->id,
            'box_number' => 'WA1001',
            'phone' => '60000000',
            'balance' => 0,
            'points' => 0,
        ]);

        $this->invoice = Invoice::create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'number' => 'INV-WA-001',
            'subtotal' => 100.00,
            'tax' => 7.00,
            'total' => 107.00,
            'status' => 'unpaid',
            'currency' => 'USD',
            'due_date' => now()->addDays(7)->toDateString(),
        ]);

        session(['tenant_id' => $this->tenant->id]);
    }

    public function test_normalize_phone_adds_panama_prefix()
    {
        $this->assertSame('+50760000000', WhatsAppService::normalizePhone('60000000'));
        $this->assertSame('+50761234567', WhatsAppService::normalizePhone('+507 6123-4567'));
        $this->assertSame('+15551234567', WhatsAppService::normalizePhone('+1 555-123-4567'));
        $this->assertSame('+34600123456', WhatsAppService::normalizePhone('+34 600 123 456'));
        $this->assertNull(WhatsAppService::normalizePhone(null));
        $this->assertNull(WhatsAppService::normalizePhone(''));
    }

    public function test_send_invoice_sends_text_when_pdf_disabled()
    {
        AppSetting::set('whatsapp_pdf_enabled', '0');

        Http::fake([
            'graph.facebook.com/*' => Http::response(['messages' => [['id' => 'wamid.abc']]], 200),
        ]);

        $result = app(WhatsAppService::class)->sendInvoice($this->invoice);

        $this->assertTrue($result['ok']);
        $this->assertNotNull($this->invoice->fresh()->whatsapp_sent_at);

        Http::assertSent(fn ($request) => str_contains($request->url(), 'graph.facebook.com')
            && $request['type'] === 'text'
            && $request['to'] === '+50760000000'
            && str_contains($request['text']['body'], 'INV-WA-001')
            && str_contains($request['text']['body'], '/portal/facturas'));
    }

    public function test_send_invoice_sends_document_when_pdf_enabled()
    {
        AppSetting::set('whatsapp_pdf_enabled', '1');

        Http::fake([
            'graph.facebook.com/*' => Http::response(['messages' => [['id' => 'wamid.def']]], 200),
        ]);

        $result = app(WhatsAppService::class)->sendInvoice($this->invoice);

        $this->assertTrue($result['ok']);
        $this->assertNotNull($this->invoice->fresh()->whatsapp_sent_at);

        Http::assertSent(fn ($request) => $request['type'] === 'document'
            && $request['document']['filename'] === 'Factura_INV-WA-001.pdf');
    }

    public function test_send_invoice_falls_back_to_template_out_of_window()
    {
        AppSetting::set('whatsapp_pdf_enabled', '0');

        Http::fakeSequence()
            ->push(['error' => ['code' => 131047, 'message' => 'Re-engagement message']], 400)
            ->push(['messages' => [['id' => 'wamid.tpl']]], 200);

        $result = app(WhatsAppService::class)->sendInvoice($this->invoice);

        $this->assertTrue($result['ok']);
        $this->assertNotNull($this->invoice->fresh()->whatsapp_sent_at);

        Http::assertSent(fn ($request) => ($request['type'] ?? null) === 'template'
            && $request['template']['name'] === 'factura_whatsapp');
    }

    public function test_send_invoice_returns_error_when_not_configured()
    {
        AppSetting::set('whatsapp_pdf_enabled', '0');
        $this->tenant->update(['settings_json' => ['currency' => 'USD']]);

        $result = app(WhatsAppService::class)->sendInvoice($this->invoice);

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('no está configurado', $result['error']);
        $this->assertNull($this->invoice->fresh()->whatsapp_sent_at);
    }

    public function test_send_invoice_returns_error_when_customer_has_no_phone()
    {
        AppSetting::set('whatsapp_pdf_enabled', '0');
        $this->customer->update(['phone' => null]);

        $result = app(WhatsAppService::class)->sendInvoice($this->invoice);

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('no tiene un teléfono', $result['error']);
    }

    public function test_invoice_list_send_whatsapp_dispatches_job_when_configured()
    {
        Bus::fake();
        $this->actingAs($this->admin);

        Livewire::test(InvoiceList::class)
            ->call('sendWhatsApp', $this->invoice->id)
            ->assertHasNoErrors();

        Bus::assertDispatched(SendInvoiceWhatsApp::class);
    }

    public function test_invoice_list_send_whatsapp_blocks_when_not_configured()
    {
        Bus::fake();
        $this->actingAs($this->admin);
        $this->tenant->update(['settings_json' => ['currency' => 'USD']]);

        Livewire::test(InvoiceList::class)
            ->call('sendWhatsApp', $this->invoice->id)
            ->assertHasNoErrors();

        Bus::assertNotDispatched(SendInvoiceWhatsApp::class);
    }
}
