<?php

namespace Tests\Feature;

use App\Helpers\DocumentPdf;
use App\Livewire\Billing\CreateInvoice;
use App\Livewire\Billing\InvoiceList;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Warehouse;
use App\Notifications\InvoiceSent;
use App\Services\Billing\InvoiceNumberGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class InvoiceDeliveryTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = $this->makeTenant('delivery');
        $this->admin = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Admin Delivery',
            'email' => 'admin@delivery.local',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        session(['tenant_id' => $this->tenant->id]);
    }

    protected function makeTenant(string $slug, array $settings = []): Tenant
    {
        return Tenant::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Tenant '.$slug,
            'subdomain' => $slug.Str::random(4),
            'status' => 'active',
            'settings_json' => $settings,
        ]);
    }

    protected function makeCustomer(Tenant $tenant, string $box = 'BOX-1'): Customer
    {
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Cliente '.$box,
            'email' => strtolower($box).'@cliente.local',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        return Customer::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'box_number' => $box,
            'balance' => 0,
        ]);
    }

    protected function makeInvoice(Tenant $tenant, Customer $customer, string $number = 'INV-TEST-0001'): Invoice
    {
        return Invoice::create([
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'number' => $number,
            'subtotal' => 100,
            'tax' => 7,
            'total' => 107,
            'status' => 'unpaid',
            'due_date' => now()->addDays(7),
        ]);
    }

    public function test_invoice_notification_renders_and_marks_invoice_as_sent()
    {
        $customer = $this->makeCustomer($this->tenant);
        $invoice = $this->makeInvoice($this->tenant, $customer);

        $customer->user->notify(new InvoiceSent($invoice));

        $this->assertNotNull(
            $invoice->fresh()->email_sent_at,
            'El listener debe marcar email_sent_at al confirmarse el envío.'
        );
    }

    public function test_send_email_action_marks_invoice_only_after_delivery()
    {
        $this->actingAs($this->admin);

        $customer = $this->makeCustomer($this->tenant);
        $invoice = $this->makeInvoice($this->tenant, $customer, 'INV-ACTION-0001');

        Livewire::test(InvoiceList::class)
            ->call('sendEmail', $invoice->id)
            ->assertHasNoErrors();

        $fresh = $invoice->fresh();
        $this->assertNotNull($fresh->email_queued_at);
        $this->assertNotNull($fresh->email_sent_at);
        $this->assertNull($fresh->email_error);
    }

    public function test_invoice_documents_use_the_tenant_logo()
    {
        $this->tenant->update(['theme_config_json' => ['logo_url' => '/logos/logyexpress.png']]);

        $customer = $this->makeCustomer($this->tenant, 'BOX-LOGO');
        $invoice = $this->makeInvoice($this->tenant, $customer, 'INV-LOGO-0001');
        $invoice->load('tenant', 'customer.user', 'items');

        $logo = DocumentPdf::logoBase64($invoice->tenant);
        $this->assertNotNull($logo);
        $this->assertStringStartsWith('data:image/png;base64,', $logo);

        $html = view('billing.invoice-pdf', [
            'invoice' => $invoice,
            'logoBase64' => $logo,
        ])->render();

        $this->assertStringContainsString('data:image/png;base64,', $html);

        // El encabezado de los correos usa la misma URL de logo del tenant.
        $this->tenant->fresh()->setMailConfig();
        $this->assertStringEndsWith('logos/logyexpress.png', (string) config('app.tenant_logo_url'));
    }

    public function test_tenant_mail_config_sets_smtp_default_for_queued_notifications()
    {
        $this->tenant->update(['settings_json' => [
            'mail_driver' => 'smtp',
            'mail_host' => 'smtp.ejemplo.com',
            'mail_port' => '587',
            'mail_username' => 'usuario@ejemplo.com',
            'mail_password' => 'secreto',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'facturas@ejemplo.com',
            'mail_from_name' => 'Ejemplo',
        ]]);

        // Simula el worker de colas: sin IdentifyTenant y con el mailer global.
        config(['mail.default' => 'log']);

        $this->tenant->fresh()->setMailConfig();

        $this->assertSame('smtp', config('mail.default'));
        $this->assertSame('smtp.ejemplo.com', config('mail.mailers.smtp.host'));
        $this->assertSame('facturas@ejemplo.com', config('mail.from.address'));
        $this->assertSame(15, config('mail.mailers.smtp.timeout'));
    }

    public function test_failed_invoice_email_is_recorded_on_the_invoice()
    {
        $customer = $this->makeCustomer($this->tenant, 'BOX-FAIL');
        $invoice = $this->makeInvoice($this->tenant, $customer, 'INV-FAIL-0001');

        event(new NotificationFailed(
            $customer->user,
            new InvoiceSent($invoice),
            'mail',
            ['exception' => new \RuntimeException('SMTP caído')]
        ));

        $this->assertSame('SMTP caído', $invoice->fresh()->email_error);
    }

    public function test_create_invoice_with_only_selected_packages_does_not_require_manual_row()
    {
        $this->actingAs($this->admin);

        $customer = $this->makeCustomer($this->tenant, 'BOX-PKG');
        $warehouse = Warehouse::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Bodega',
            'code' => 'BDG',
        ]);

        $package = Package::create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $customer->id,
            'warehouse_id' => $warehouse->id,
            'tracking_number' => 'TRK-PKG-1',
            'weight' => 4,
            'status' => 'received',
            'service_type' => 'air',
        ]);

        Livewire::test(CreateInvoice::class)
            ->set('box_number', $customer->box_number)
            ->call('togglePackage', $package->id)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(1, Invoice::count());
        $this->assertSame(1, Invoice::first()->items()->count());
    }

    public function test_create_invoice_resolves_customer_when_live_model_has_not_arrived()
    {
        $this->actingAs($this->admin);

        $customer = $this->makeCustomer($this->tenant, 'BOX-RACE');

        Livewire::test(CreateInvoice::class)
            ->set('box_number', $customer->box_number)
            ->set('found_customer', null)
            ->set('items', [[
                'description' => 'Flete Aéreo - TEST',
                'quantity' => 2,
                'unit_price' => 5,
                'total' => 10,
            ]])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(1, Invoice::count());
        $this->assertSame($customer->id, Invoice::first()->customer_id);
    }

    public function test_invoice_numbers_are_sequential_per_tenant_and_day()
    {
        $generator = app(InvoiceNumberGenerator::class);
        $date = Carbon::parse('2026-10-30');

        $this->assertSame('INV-20261030-0001', $generator->next($this->tenant->id, $date));

        $customer = $this->makeCustomer($this->tenant, 'BOX-SEQ');

        $generator->create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $customer->id,
            'subtotal' => 10,
            'tax' => 0,
            'total' => 10,
            'status' => 'unpaid',
        ], $date);

        $this->assertSame('INV-20261030-0002', $generator->next($this->tenant->id, $date));

        // Otro tenant reinicia la secuencia.
        $other = $this->makeTenant('other');
        $this->assertSame('INV-20261030-0001', $generator->next($other->id, $date));
    }

    public function test_same_number_is_allowed_for_different_tenants()
    {
        $other = $this->makeTenant('other');

        $this->makeInvoice($this->tenant, $this->makeCustomer($this->tenant, 'BOX-A'), 'INV-SHARED-0001');

        $second = $this->makeInvoice($other, $this->makeCustomer($other, 'BOX-B'), 'INV-SHARED-0001');

        $this->assertSame('INV-SHARED-0001', $second->fresh()->number);
    }
}
