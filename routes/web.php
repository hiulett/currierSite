<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Logistics\ReceivePackage;
use App\Livewire\Logistics\SmartReceptionHub;
use App\Livewire\Logistics\ReceiveManifest;
use App\Livewire\Logistics\InventoryList;
use App\Livewire\Logistics\CustomerList;
use App\Livewire\Logistics\LockerList;
use App\Livewire\Logistics\ShipmentList;
use App\Livewire\Logistics\ShipmentDetail;
use App\Livewire\Logistics\RepackInterface;
use App\Livewire\Logistics\DeliveryManagement;
use App\Livewire\Logistics\DeliveryManifest;
use App\Livewire\Logistics\CounterDelivery;
use App\Livewire\Logistics\SupportTickets;
use App\Livewire\Logistics\ReportCenter;
use App\Livewire\Billing\InvoiceList;
use App\Livewire\Billing\CreateInvoice;
use App\Livewire\Billing\StatementOfAccount;
use App\Livewire\Builder\BrandSettings;
use App\Livewire\Builder\MailSettings;
use App\Livewire\Builder\GeneralSettings;
use App\Livewire\Builder\Integrations;
use App\Livewire\Builder\WarehouseSettings;
use App\Livewire\Builder\UserManagement;
use App\Livewire\Builder\RoleManagement;
use App\Livewire\Customer\Dashboard as CustomerDashboard;
use App\Livewire\Customer\PackageList as CustomerPackageList;
use App\Livewire\Customer\PreAlert as CustomerPreAlert;
use App\Livewire\Customer\InvoiceList as CustomerInvoiceList;
use App\Livewire\Customer\TicketList as CustomerTicketList;
use App\Livewire\Customer\TicketDetail as CustomerTicketDetail;
use App\Livewire\Customer\WhatsappBot as CustomerWhatsappBot;
use App\Livewire\Customer\ProfileSettings as CustomerProfileSettings;
use App\Livewire\Customer\ShippingCalculator as CustomerShippingCalculator;
use App\Livewire\SuperAdmin\Dashboard as SuperDashboard;
use App\Livewire\SuperAdmin\TenantList as SuperTenantList;
use App\Livewire\SuperAdmin\PlanList as SuperPlanList;
use App\Livewire\SuperAdmin\GlobalInventory as SuperGlobalInventory;
use App\Livewire\SuperAdmin\CoreSettings as SuperCoreSettings;
use App\Livewire\SuperAdmin\ApiWebhooks as SuperApiWebhooks;
use App\Livewire\Public\TrackingSearch;
use App\Livewire\Public\Home as PublicHome;
use App\Livewire\Public\DutyCalculator;
use App\Livewire\Dashboard;
use App\Http\Controllers\Billing\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Logistics\LabelController;
use App\Http\Controllers\Logistics\ReportController;

// Public Routes
Route::get('/', PublicHome::class)->name('home');
Route::get('/calculadora', DutyCalculator::class)->name('public.calculator');
// Route::get('/tracking', TrackingSearch::class)->name('public.tracking');

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
        Route::get('/logistica/casilleros', LockerList::class)->name('logistics.lockers')->middleware('can:logistics.inventory');
        Route::get('/logistica/rastreo-global', \App\Livewire\Logistics\GlobalTracking::class)->name('logistics.tracking')->middleware('can:access-admin');
        Route::get('/logistica/etiqueta/{package}', [LabelController::class, 'print'])->name('logistics.label')->middleware('can:logistics.receive');

        // Shipments
        Route::get('/logistica/embarques', ShipmentList::class)->name('logistics.shipments.index')->middleware('can:logistics.shipments');
        Route::get('/logistica/embarques/{shipment}', ShipmentDetail::class)->name('logistics.shipments.detail')->middleware('can:logistics.shipments');

        Route::get('/facturacion', InvoiceList::class)->name('billing.index')->middleware('can:billing.view');
        Route::get('/facturacion/nueva', CreateInvoice::class)->name('billing.create')->middleware('can:billing.manage');
        Route::get('/facturacion/validar-pagos', \App\Livewire\Billing\PaymentApprovals::class)->name('billing.approvals')->middleware('can:billing.manage');
        Route::get('/facturacion/{invoice}/download', [InvoiceController::class, 'download'])->name('billing.download')->middleware('can:billing.view');
        Route::get('/facturacion/estado-cuenta', StatementOfAccount::class)->name('billing.statement')->middleware('can:billing.view');
        Route::get('/facturacion/estado-cuenta/{customer}/download', [InvoiceController::class, 'downloadStatement'])->name('billing.statement.download')->middleware('can:billing.view');

        Route::get('/builder', App\Livewire\Builder\PageList::class)->name('builder.index')->middleware('can:settings.general');
        Route::get('/builder/brand', BrandSettings::class)->name('builder.brand')->middleware('can:settings.brand');
        Route::get('/builder/mail', MailSettings::class)->name('builder.mail')->middleware('can:settings.general');
        Route::get('/builder/general', GeneralSettings::class)->name('builder.general')->middleware('can:settings.general');
        Route::get('/builder/integraciones', Integrations::class)->name('builder.integrations')->middleware('can:settings.general');
        Route::get('/builder/bodegas', WarehouseSettings::class)->name('builder.warehouses')->middleware('can:settings.general');
        Route::get('/builder/usuarios', UserManagement::class)->name('builder.users')->middleware('can:settings.users');
        Route::get('/builder/roles', RoleManagement::class)->name('builder.roles')->middleware('can:settings.users');
        Route::get('/builder/estados', App\Livewire\Builder\PackageStatusSettings::class)->name('builder.statuses')->middleware('can:settings.general');
        Route::get('/builder/fidelizacion', App\Livewire\Builder\LoyaltySettings::class)->name('builder.loyalty')->middleware('can:settings.general');
        Route::get('/builder/promociones', \App\Livewire\Builder\PromotionSettings::class)->name('builder.promotions')->middleware('can:settings.general');
    });

    // Customer Portal Routes
    Route::group(['prefix' => 'portal'], function () {
        Route::get('/', CustomerDashboard::class)->name('customer.dashboard');
        Route::get('/paquetes', CustomerPackageList::class)->name('customer.packages');
        Route::get('/rastreo', \App\Livewire\Customer\Tracking::class)->name('customer.tracking');
        Route::get('/prealertar', CustomerPreAlert::class)->name('customer.pre-alert');
        Route::get('/facturas', CustomerInvoiceList::class)->name('customer.invoices');
        Route::get('/perfil', CustomerProfileSettings::class)->name('customer.profile');
        Route::get('/calculadora', CustomerShippingCalculator::class)->name('customer.calculator');
        Route::get('/facturas/{invoice}/download', [InvoiceController::class, 'download'])->name('customer.invoices.download');

        // Support Tickets
        Route::get('/soporte', CustomerTicketList::class)->name('customer.tickets.index');
        Route::get('/soporte/{ticket}', CustomerTicketDetail::class)->name('customer.tickets.detail');
        Route::get('/whatsapp', CustomerWhatsappBot::class)->name('customer.whatsapp');

        // Payments & Checkout
        Route::get('/facturas/{invoice_id}/checkout', \App\Livewire\Customer\Checkout::class)->name('customer.checkout');
        Route::get('/facturas/{invoice}/pay', [PaymentController::class, 'checkout'])->name('payment.checkout');
        Route::get('/facturas/{invoice}/paypal', [PaymentController::class, 'paypalCheckout'])->name('payment.paypal');
        Route::get('/payment/success/{invoice}', [PaymentController::class, 'success'])->name('payment.success');
        Route::get('/payment/paypal/success/{invoice}', [PaymentController::class, 'paypalSuccess'])->name('payment.paypal.success');
        Route::get('/payment/cancel/{invoice}', [PaymentController::class, 'cancel'])->name('payment.cancel');
    });

    // SuperAdmin Routes
    Route::middleware(['can:access-superadmin'])->group(function () {
        Route::get('/superadmin/stop-impersonating', function() {
            session()->forget('impersonate_tenant_id');
            return redirect()->route('super.tenants');
        })->name('super.stop-impersonating');

        Route::group(['prefix' => 'superadmin'], function () {
            Route::get('/', SuperDashboard::class)->name('super.dashboard');
            Route::get('/tenants', SuperTenantList::class)->name('super.tenants');
            Route::get('/planes', SuperPlanList::class)->name('super.plans');
            Route::get('/facturacion', App\Livewire\SuperAdmin\BillingManagement::class)->name('super.billing');
            Route::get('/inventario', SuperGlobalInventory::class)->name('super.inventory');
            Route::get('/ajustes-nucleo', SuperCoreSettings::class)->name('super.settings');
            Route::get('/api-webhooks', SuperApiWebhooks::class)->name('super.api');
        });
    });
});
