<?php

use App\Http\Controllers\Billing\InvoiceController;
use App\Http\Controllers\Billing\QuotationController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Logistics\LabelController;
use App\Http\Controllers\Logistics\ReportController;
use App\Http\Controllers\PaymentController;
use App\Livewire\Billing\CreateInvoice;
use App\Livewire\Billing\DriverTripList;
use App\Livewire\Billing\EditInvoice;
use App\Livewire\Billing\ExpenseList;
use App\Livewire\Billing\InvoiceList;
use App\Livewire\Billing\PaymentApprovals;
use App\Livewire\Billing\QuotationList;
use App\Livewire\Billing\RedemptionList;
use App\Livewire\Billing\StatementOfAccount;
use App\Livewire\Builder\BrandSettings;
use App\Livewire\Builder\GeneralSettings;
use App\Livewire\Builder\Integrations;
use App\Livewire\Builder\LoyaltySettings;
use App\Livewire\Builder\MailSettings;
use App\Livewire\Builder\PackageStatusSettings;
use App\Livewire\Builder\PageList;
use App\Livewire\Builder\PromotionSettings;
use App\Livewire\Builder\RewardsSettings;
use App\Livewire\Builder\RoleManagement;
use App\Livewire\Builder\UserManagement;
use App\Livewire\Builder\WarehouseSettings;
use App\Livewire\Customer\Checkout;
use App\Livewire\Customer\Dashboard as CustomerDashboard;
use App\Livewire\Customer\InvoiceList as CustomerInvoiceList;
use App\Livewire\Customer\PackageList as CustomerPackageList;
use App\Livewire\Customer\PreAlert as CustomerPreAlert;
use App\Livewire\Customer\ProfileSettings as CustomerProfileSettings;
use App\Livewire\Customer\QuotationList as CustomerQuotationList;
use App\Livewire\Customer\Rewards as CustomerRewards;
use App\Livewire\Customer\ShippingCalculator as CustomerShippingCalculator;
use App\Livewire\Customer\TicketDetail as CustomerTicketDetail;
use App\Livewire\Customer\TicketList as CustomerTicketList;
use App\Livewire\Customer\Tracking;
use App\Livewire\Customer\WhatsappBot as CustomerWhatsappBot;
use App\Livewire\Dashboard;
use App\Livewire\Logistics\CounterDelivery;
use App\Livewire\Logistics\CustomerDetail;
use App\Livewire\Logistics\CustomerList;
use App\Livewire\Logistics\DeliveryManagement;
use App\Livewire\Logistics\DeliveryManifest;
use App\Livewire\Logistics\GlobalTracking;
use App\Livewire\Logistics\InventoryList;
use App\Livewire\Logistics\LockerList;
use App\Livewire\Logistics\ReceiveManifest;
use App\Livewire\Logistics\ReceivePackage;
use App\Livewire\Logistics\RepackInterface;
use App\Livewire\Logistics\ReportCenter;
use App\Livewire\Logistics\ShipmentDetail;
use App\Livewire\Logistics\ShipmentList;
use App\Livewire\Logistics\SmartReceptionHub;
use App\Livewire\Logistics\SupportTickets;
use App\Livewire\Public\DutyCalculator;
use App\Livewire\SuperAdmin\ApiWebhooks as SuperApiWebhooks;
use App\Livewire\SuperAdmin\BillingManagement;
use App\Livewire\SuperAdmin\CoreSettings as SuperCoreSettings;
use App\Livewire\SuperAdmin\Dashboard as SuperDashboard;
use App\Livewire\SuperAdmin\GlobalFinanceAudit;
use App\Livewire\SuperAdmin\GlobalInventory as SuperGlobalInventory;
use App\Livewire\SuperAdmin\GlobalTrackingSearch;
use App\Livewire\SuperAdmin\GlobalUserManagement;
use App\Livewire\SuperAdmin\PlanList as SuperPlanList;
use App\Livewire\SuperAdmin\TenantList as SuperTenantList;
use App\Models\Package;
use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/calculadora', DutyCalculator::class)->name('public.calculator');

// Tenant Gateways (Entry points for agency websites)
Route::get('/join/{slug}', function ($slug) {
    $slug = strtolower(trim($slug));
    $tenant = Tenant::where('subdomain', $slug)
        ->orWhere('login_url_slug', $slug)
        ->orWhere('uuid', $slug)
        ->firstOrFail();

    session(['tenant_id' => $tenant->id]);
    session()->save();

    return redirect()->route('register')
        ->withCookie(cookie('tenant_branding_id', $tenant->id, 43200, null, null, true, true, false, 'Lax'));
})->name('tenant.join');

Route::get('/access/{slug}', function ($slug) {
    $slug = strtolower(trim($slug));
    $tenant = Tenant::where('subdomain', $slug)
        ->orWhere('login_url_slug', $slug)
        ->orWhere('uuid', $slug)
        ->firstOrFail();

    session(['tenant_id' => $tenant->id]);
    session()->save();

    return redirect()->route('login')
        ->withCookie(cookie('tenant_branding_id', $tenant->id, 43200, null, null, true, true, false, 'Lax'));
})->name('tenant.access');

// Helper para verificar nombres de agencias (SOLO en entornos no productivos)
Route::get('/check-tenants', function () {
    if (app()->isProduction()) {
        return abort(404);
    }
    $tenants = Tenant::all(['name', 'subdomain', 'login_url_slug']);

    return $tenants;
});

Route::get('/debug-inventory', function () {
    if (app()->isProduction()) {
        return abort(404);
    }
    $packages = Package::withoutGlobalScopes()->get();
    echo '<h1>Diagnóstico de Inventario</h1>';
    echo '<h3>Total de Paquetes en DB: '.$packages->count().'</h3>';
    echo "<table border='1' cellpadding='5'>";
    echo '<thead><tr><th>ID</th><th>Tracking</th><th>Tenant</th><th>Estado</th></tr></thead>';
    echo '<tbody>';
    foreach ($packages as $p) {
        echo "<tr><td>{$p->id}</td><td>{$p->tracking_number}</td><td>{$p->tenant_id}</td><td>{$p->status}</td></tr>";
    }
    echo '</tbody></table>';
    if ($packages->isEmpty()) {
        echo '<p>La tabla de paquetes está realmente VACÍA.</p>';
    }
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Admin/Logistics Routes
    Route::middleware(['can:access-admin'])->group(function () {
        Route::get('/logistica/recepcion', ReceivePackage::class)->name('logistics.receive')->middleware('can:logistics.receive');
        Route::get('/logistica/recepcion-inteligente', SmartReceptionHub::class)->name('logistics.smart-reception')->middleware('can:logistics.receive');
        Route::get('/logistica/recepcion-manifiesto', ReceiveManifest::class)->name('logistics.receive-manifest')->middleware('can:logistics.receive');
        Route::get('/logistica/inventario', InventoryList::class)->name('logistics.inventory')->middleware('can:logistics.inventory');
        Route::get('/logistica/inventario/export', [ReportController::class, 'exportInventory'])->name('logistics.inventory.export')->middleware('can:logistics.inventory');
        Route::get('/logistica/reempaque', RepackInterface::class)->name('logistics.repack')->middleware('can:logistics.repack');
        Route::get('/logistica/ultima-milla', DeliveryManagement::class)->name('logistics.delivery')->middleware('can:logistics.delivery');
        Route::get('/logistica/ultima-milla/{id}', DeliveryManifest::class)->name('logistics.delivery.manifest')->middleware('can:logistics.delivery');
        Route::get('/logistica/despacho', CounterDelivery::class)->name('logistics.counter')->middleware('can:logistics.counter');
        Route::get('/logistica/reportes', ReportCenter::class)->name('logistics.reports')->middleware('can:logistics.reports');
        Route::get('/logistica/soporte', SupportTickets::class)->name('logistics.tickets')->middleware('can:tickets.manage');
        Route::get('/logistica/clientes', CustomerList::class)->name('logistics.customers')->middleware('can:customers.view');
        Route::get('/logistica/clientes/{customer}', CustomerDetail::class)->name('logistics.customers.detail')->middleware('can:customers.view');
        Route::get('/logistica/casilleros', LockerList::class)->name('logistics.lockers')->middleware('can:logistics.inventory');
        Route::get('/logistica/rastreo-global', GlobalTracking::class)->name('logistics.tracking')->middleware('can:access-admin');
        Route::get('/logistica/etiqueta/{package}', [LabelController::class, 'print'])->name('logistics.label')->middleware('can:logistics.receive');

        // Shipments
        Route::get('/logistica/embarques', ShipmentList::class)->name('logistics.shipments.index')->middleware('can:logistics.shipments');
        Route::get('/logistica/embarques/{shipment}', ShipmentDetail::class)->name('logistics.shipments.detail')->middleware('can:logistics.shipments');

        Route::middleware(['tenant.feature:billing'])->group(function () {
            Route::get('/facturacion', InvoiceList::class)->name('billing.index')->middleware('can:billing.view');
            Route::get('/facturacion/nueva', CreateInvoice::class)->name('billing.create')->middleware('can:billing.manage');
            Route::get('/facturacion/{invoice}/editar', EditInvoice::class)->name('billing.edit')->middleware('can:billing.manage');
            Route::get('/facturacion/validar-pagos', PaymentApprovals::class)->name('billing.approvals')->middleware('can:billing.manage');
            Route::get('/facturacion/{invoice}/download', [InvoiceController::class, 'download'])->name('billing.download')->middleware('can:billing.view');
            Route::get('/facturacion/estado-cuenta', StatementOfAccount::class)->name('billing.statement')->middleware('can:billing.view');
            Route::get('/facturacion/estado-cuenta/{customer}/download', [InvoiceController::class, 'downloadStatement'])->name('billing.statement.download')->middleware('can:billing.view');

            // Quotations
            Route::get('/cotizaciones', QuotationList::class)->name('billing.quotations.index')->middleware('can:billing.view');
            Route::get('/cotizaciones/{quotation}/download', [QuotationController::class, 'download'])->name('billing.quotations.download')->middleware('can:billing.view');

            // Control de Fletes / Viajes
            Route::get('/control-fletes', DriverTripList::class)->name('billing.driver-trips.index')->middleware('can:billing.view');

            // Canjes LOGYPUNTOS
            Route::get('/facturacion/canjeos', RedemptionList::class)->name('billing.redemptions')->middleware('can:billing.view');
        });

        // Egresos (Expenses)
        Route::get('/egresos', ExpenseList::class)
            ->name('billing.expenses.index')
            ->middleware(['can:billing.view', 'tenant.feature:expenses']);

        Route::get('/builder', PageList::class)->name('builder.index')->middleware('can:settings.general');
        Route::get('/builder/brand', BrandSettings::class)->name('builder.brand')->middleware('can:settings.brand');
        Route::get('/builder/mail', MailSettings::class)->name('builder.mail')->middleware('can:settings.general');
        Route::get('/builder/general', GeneralSettings::class)->name('builder.general')->middleware('can:settings.general');
        Route::get('/builder/integraciones', Integrations::class)->name('builder.integrations')->middleware('can:settings.general');
        Route::get('/builder/bodegas', WarehouseSettings::class)->name('builder.warehouses')->middleware('can:settings.general');
        Route::get('/builder/usuarios', UserManagement::class)->name('builder.users')->middleware('can:settings.users');
        Route::get('/builder/roles', RoleManagement::class)->name('builder.roles')->middleware('can:settings.users');
        Route::get('/builder/estados', PackageStatusSettings::class)->name('builder.statuses')->middleware('can:settings.general');
        Route::get('/builder/fidelizacion', LoyaltySettings::class)->name('builder.loyalty')->middleware('can:settings.general');
        Route::get('/builder/recompensas', RewardsSettings::class)->name('builder.rewards')->middleware('can:settings.general');
        Route::get('/builder/promociones', PromotionSettings::class)->name('builder.promotions')->middleware('can:settings.general');

        // Temporary Data Sync Route (SOLO no-productivo)
        Route::get('/system/sync-data', function () {
            if (app()->isProduction()) {
                return abort(404);
            }
            try {
                Artisan::call('db:seed', [
                    '--class' => 'ProductionDataSyncSeeder',
                    '--force' => true,
                ]);

                return '¡Sincronización Exitosa! Los 231 clientes han sido cargados. Puedes volver atrás.';
            } catch (Exception $e) {
                return 'Error en sincronización: '.$e->getMessage();
            }
        })->name('system.sync');
    });

    // Customer Portal Routes
    Route::group(['prefix' => 'portal'], function () {
        Route::get('/', CustomerDashboard::class)->name('customer.dashboard');
        Route::get('/paquetes', CustomerPackageList::class)->name('customer.packages');
        Route::get('/rastreo', Tracking::class)->name('customer.tracking');
        Route::get('/prealertar', CustomerPreAlert::class)->name('customer.pre-alert');
        Route::get('/facturas', CustomerInvoiceList::class)->name('customer.invoices');
        Route::get('/cotizaciones', CustomerQuotationList::class)->name('customer.quotations');
        Route::get('/cotizaciones/{quotation}/download', [QuotationController::class, 'download'])->name('customer.quotations.download');
        Route::get('/perfil', CustomerProfileSettings::class)->name('customer.profile');
        Route::get('/calculadora', CustomerShippingCalculator::class)->name('customer.calculator');
        Route::get('/recompensas', CustomerRewards::class)->name('customer.rewards');
        Route::get('/facturas/{invoice}/download', [InvoiceController::class, 'download'])->name('customer.invoices.download');

        // Support Tickets
        Route::get('/soporte', CustomerTicketList::class)->name('customer.tickets.index');
        Route::get('/soporte/{ticket}', CustomerTicketDetail::class)->name('customer.tickets.detail');
        Route::get('/whatsapp', CustomerWhatsappBot::class)->name('customer.whatsapp');

        // Payments & Checkout
        Route::get('/facturas/{invoice_id}/checkout', Checkout::class)->name('customer.checkout');
        Route::get('/facturas/{invoice}/pay', [PaymentController::class, 'checkout'])->name('payment.checkout');
        Route::get('/facturas/{invoice}/paypal', [PaymentController::class, 'paypalCheckout'])->name('payment.paypal');
        Route::get('/payment/success/{invoice}', [PaymentController::class, 'success'])->name('payment.success');
        Route::get('/payment/paypal/success/{invoice}', [PaymentController::class, 'paypalSuccess'])->name('payment.paypal.success');
        Route::get('/payment/cancel/{invoice}', [PaymentController::class, 'cancel'])->name('payment.cancel');
    });

    // SuperAdmin Routes
    Route::middleware(['can:access-superadmin'])->group(function () {
        Route::get('/superadmin/stop-impersonating', function () {
            session()->forget('impersonate_tenant_id');

            return redirect()->route('super.tenants');
        })->name('super.stop-impersonating');

        Route::group(['prefix' => 'superadmin'], function () {
            Route::get('/', SuperDashboard::class)->name('super.dashboard');
            Route::get('/tenants', SuperTenantList::class)->name('super.tenants');
            Route::get('/usuarios-globales', GlobalUserManagement::class)->name('super.users');
            Route::get('/auditoria-financiera', GlobalFinanceAudit::class)->name('super.audit');
            Route::get('/tracking-maestro', GlobalTrackingSearch::class)->name('super.tracking');
            Route::get('/planes', SuperPlanList::class)->name('super.plans');
            Route::get('/facturacion', BillingManagement::class)->name('super.billing');
            Route::get('/inventario', SuperGlobalInventory::class)->name('super.inventory');
            Route::get('/ajustes-nucleo', SuperCoreSettings::class)->name('super.settings');
            Route::get('/api-webhooks', SuperApiWebhooks::class)->name('super.api');
        });
    });
});
