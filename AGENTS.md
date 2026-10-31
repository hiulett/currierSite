# AGENTS.md — LogiSaaS (courier)

Plataforma SaaS multi-tenant para couriers (recepción, inventario, facturación, cotizaciones, fletes).
Frontend y backend en un solo repo Laravel. Aplicación móvil Flutter en `/mobile`.

## Stack y arquitectura

- **Backend**: Laravel 12, PHP 8.2, Livewire 4 (Vite como asset bridge).
- **Frontend**: Blade + Livewire, Tailwind 4 + Vite 7, Bootstrap 4/AdminKit, Chart.js, Feather icons.
- **Base de datos**: SQLite en desarrollo local (`database/database.sqlite`), **MySQL en producción (Railway)**. Cache/session/queue usan `database` por defecto; **Redis** está configurado (`predis`, ver `config/database.php` + `.env.example`) para habilitarse con `CACHE_STORE/SESSION_DRIVER/QUEUE_CONNECTION=redis`.
- **Multi-tenancy**: trait `App\Traits\BelongsToTenant` (global scope) + middleware `IdentifyTenant` (sesión `tenant_id`). Superadmin (`is_superadmin`) omite el scope; impersonación vía `impersonate_tenant_id`.
- **Módulos clave**: recepción/paquetes, inventario, embarques, entregas, manifiestos, facturación (invoices + cotizaciones), fletes, gastos, clientes, tickets, fidelización.
- **Despliegue**: Railway (Dockerfile **multi-stage** + `railway-deploy.sh`). Colas para correo. **CI**: GitHub Actions (`.github/workflows/ci.yml`: pint, tests, `composer audit`, build Vite).

## Comandos canónicos

| Comando | Uso |
|---|---|
| `composer dev` | Levanta todo: `php artisan serve` + queue + pail + vite |
| `php artisan serve` | Servidor local (puerto 8001 por defecto, ver `.env`) |
| `npm run build` | Compila assets de producción (Vite) |
| `npm run dev` | Vite en modo watch |
| `composer test` | Suite completa (limpia config + `php artisan test`) |
| `php vendor/bin/phpunit --filter <Test>` | Loop rápido (sin build/restore) |
| `composer lint` | Verifica formato (Pint en modo `--test`, usado en CI) |
| `composer format` | Aplica formato Pint |
| `composer analyse` | PHPStan/Larastan nivel 5 (usa `phpstan-baseline.neon`; falla solo con errores NUEVOS) |
| `composer security` | Auditoría de dependencias (`composer audit`) |
| `vendor/bin/pint` | Formato de código (Laravel Pint) |
| `php artisan migrate` | Migraciones (dev: SQLite; prod: MySQL) |

## Si el cambio es X → estos archivos

| Cambio | Archivos |
|---|---|
| Nuevo módulo de negocio | `routes/web.php`, `app/Livewire/<Area>/*.php`, `resources/views/livewire/<area>/*.blade.php`, migración en `database/migrations/`, modelo en `app/Models/` |
| Cotizaciones | `app/Livewire/Billing/CreateQuotation.php`, `QuotationList.php`, `app/Http/Controllers/Billing/QuotationController.php`, `app/Models/Quotation*.php`, vistas `livewire/billing/*quotation*` |
| Facturación | `app/Livewire/Billing/{CreateInvoice,InvoiceList,EditInvoice}.php`, `app/Http/Controllers/Billing/InvoiceController.php`, `app/Services/Billing/InvoiceNumberGenerator.php`, `app/Listeners/MarkInvoiceEmailSent.php` |
| Envío de facturas/correos | `app/Notifications/InvoiceSent.php`, `app/Notifications/PackagesArrivedNotification.php`, `app/Listeners/MarkInvoiceEmailSent.php`, `app/Providers/AppServiceProvider.php` (registro del listener) |
| Paquetes/recepción | `app/Livewire/Logistics/*.php`, `app/Models/{Package,PackageStatus}.php` |
| Multi-tenancy / scopes | `app/Traits/BelongsToTenant.php`, `app/Http/Middleware/IdentifyTenant.php` |
| Layout / sidebar | `resources/views/components/layouts/app.blade.php`, `resources/views/components/layouts/partials/sidebar.blade.php`, `app/Providers/AppServiceProvider.php` |
| UI / assets | `resources/js/app.js`, `resources/css/app.css`, `vite.config.js` |
| Charts (code-split) | `resources/js/charts.js` (chunk propio vía `@vite`); init con `whenChartReady` en dashboard/quotation-list/super-admin dashboard |
| Componentes UI reutilizables | `resources/views/components/{page-header,card,stat,modal,empty-state}.blade.php` |

## Convenciones

- **Siempre** que agregues lógica de negocio, hazla testeable: inyecta abstracciones/`IDateTimeProvider` en vez de `now()`/`DateTime::now` hardcodeados cuando aplique; usa `now()` de Laravel en queries.
- **Nunca** uses SQL específico de MySQL en bruto (`DATE_FORMAT`, etc.): usa `App\Helpers\DatabaseHelper::formatMonth()` (SQLite/MySQL/PostgreSQL). El bug clásico de este repo fue `DATE_FORMAT` rompiendo SQLite.
- Modelos nuevos de tenant usan `use \App\Traits\BelongsToTenant;` y `protected $fillable` explícito.
- Livewire 4: usa `$this->dispatch(...)` y `Livewire.on(...)` (el listener nativo `window.addEventListener` NO captura eventos de Livewire). `$listeners` en el componente sigue funcionando.
- Vistas: estilo Bootstrap 4 + clases de AdminKit; modales con `wire:ignore.self` y `@livewire(...)` anidado.
- **Prefiere** los componentes Blade (`<x-page-header>`, `<x-card>`, `<x-stat>`, `<x-modal>`, `<x-empty-state>`) sobre HTML repetido.
- Livewire 4: eventos con `@script` + `Livewire.on(...)` (NO `window.addEventListener`); JS de componente va en `@script`, no en `<script>` inline.
- **Navegación SPA**: los links internos del menú usan `wire:navigate` (navegación sin recarga). Al agregar un link de navegación interno, añade `wire:navigate`. Los `<script>` de setup global de los layouts llevan `data-navigate-once`.
- Textos UI en español. `lang/es.json` existe pero no se traduce todo.
- **Números de negocio únicos** (facturas, cotizaciones, etc.): nunca uses `rand()` ni `date()` sueltos. Usa `App\Services\Billing\InvoiceNumberGenerator` (secuencia diaria por tenant, `INV-{Ymd}-{0001}`) y un índice único compuesto `(tenant_id, number)`.
- **Envío de correos**: la notificación es `ShouldQueue`; `email_sent_at` se marca en `App\Listeners\MarkInvoiceEmailSent` al evento `NotificationSent`, no al encolar. No pongas `Log::error(...$e...)` fuera del `catch`.
- **Feedback de acciones Livewire**: botones que guardan/envían llevan `wire:loading.attr="disabled"` + `wire:target` y un `spinner-border` (patrón en `create-invoice.blade.php` e `invoice-list.blade.php`). Nunca pongas `d-flex`/`d-inline-flex`/`d-block` en el mismo elemento con `wire:loading`: esas utilidades usan `!important` y anulan el `display:none` de Livewire (el spinner se queda visible siempre); envuelve el contenido en un `<span>` interno.
- **Iconos Feather**: nunca pongas directivas (`wire:*`, `x-on:*`, `@click`) en el mismo elemento que `data-feather`. `feather.replace()` arma el SVG como string y los atributos con `:` rompen el parser XML (`replaceChild` con `null`). El layout (`app.blade.php`) ya trae `initFeather()` propio, que renderiza icono por icono con `<template>` y tolera esos atributos.

## Estado conocido

- **Tests**: suite PHPUnit verde (92 tests: Billing, BillingFlow, InvoiceDelivery, QuotationRepro, DriverTrip, Expense, TenantFeatureToggle, Loyalty*, WhatsApp*, etc.).
- Datos de dev en SQLite; `quotations`/`invoices` casi vacías (solo registros de prueba).
- Quirk: `email_verified_at` NO está en `$fillable` de `App\Models\User` (usar `forceFill`).
- Quirk: `Tenant::current()` cae a `Tenant::first()` si no hay sesión (solo dev).
- Quirk: la cola de correo en Railway corre dentro del contenedor web con reinicio automático (`railway-deploy.sh`); revisar `jobs`/`failed_jobs` si los correos no llegan. Lo recomendado es un servicio de worker aparte.
- Deuda: varias vistas duplican lógica; PHPStan con baseline (`phpstan-baseline.neon`, 259 errores heredados) en `phpstan.neon`. El móvil (`/mobile`) está desacoplado.
- Documentación de la épica de facturación: `HISTORIAS_USUARIO_FACTURACION.md`.

## Seguridad (qué NO commitear)

- NUNCA commitees `.env`, `server.log`, credenciales reales ni API keys.
- Los ejemplos (`*.example`, `*.http`, seeders) usan placeholders `${VAR}`.
- No expongas secretos en logs ni responses; usa `config/` + env en lugar de hardcodear.
- No escribas credenciales SMTP/pasarelas de pago en el código (van por tenant `settings_json` en BD, no en git).
