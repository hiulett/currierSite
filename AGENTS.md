# AGENTS.md — LogiSaaS (courier)

Plataforma SaaS multi-tenant para couriers (recepción, inventario, facturación, cotizaciones, fletes).
Frontend y backend en un solo repo Laravel. Aplicación móvil Flutter en `/mobile`.

## Stack y arquitectura

- **Backend**: Laravel 12, PHP 8.2, Livewire 4 (Vite como asset bridge).
- **Frontend**: Blade + Livewire, Tailwind 4 + Vite 7, Bootstrap 4/AdminKit, Chart.js, Feather icons.
- **Base de datos**: SQLite en desarrollo local (`database/database.sqlite`), **MySQL en producción (Railway)**.
- **Multi-tenancy**: trait `App\Traits\BelongsToTenant` (global scope) + middleware `IdentifyTenant` (sesión `tenant_id`). Superadmin (`is_superadmin`) omite el scope; impersonación vía `impersonate_tenant_id`.
- **Módulos clave**: recepción/paquetes, inventario, embarques, entregas, manifiestos, facturación (invoices + cotizaciones), fletes, gastos, clientes, tickets, fidelización.
- **Despliegue**: Railway (Dockerfile + `railway-deploy.sh`). Colas para correo.

## Comandos canónicos

| Comando | Uso |
|---|---|
| `composer dev` | Levanta todo: `php artisan serve` + queue + pail + vite |
| `php artisan serve` | Servidor local (puerto 8001 por defecto, ver `.env`) |
| `npm run build` | Compila assets de producción (Vite) |
| `npm run dev` | Vite en modo watch |
| `composer test` | Suite completa (limpia config + `php artisan test`) |
| `php vendor/bin/phpunit --filter <Test>` | Loop rápido (sin build/restore) |
| `vendor/bin/pint` | Formato de código (Laravel Pint) |
| `php artisan migrate` | Migraciones (dev: SQLite; prod: MySQL) |

## Si el cambio es X → estos archivos

| Cambio | Archivos |
|---|---|
| Nuevo módulo de negocio | `routes/web.php`, `app/Livewire/<Area>/*.php`, `resources/views/livewire/<area>/*.blade.php`, migración en `database/migrations/`, modelo en `app/Models/` |
| Cotizaciones | `app/Livewire/Billing/CreateQuotation.php`, `QuotationList.php`, `app/Http/Controllers/Billing/QuotationController.php`, `app/Models/Quotation*.php`, vistas `livewire/billing/*quotation*` |
| Facturación | `app/Livewire/Billing/{CreateInvoice,InvoiceList,EditInvoice}.php`, `app/Http/Controllers/Billing/InvoiceController.php` |
| Paquetes/recepción | `app/Livewire/Logistics/*.php`, `app/Models/{Package,PackageStatus}.php` |
| Multi-tenancy / scopes | `app/Traits/BelongsToTenant.php`, `app/Http/Middleware/IdentifyTenant.php` |
| Layout / sidebar | `resources/views/components/layouts/app.blade.php`, `app/Providers/AppServiceProvider.php` |
| UI / assets | `resources/js/app.js`, `resources/css/app.css`, `vite.config.js` |

## Convenciones

- **Siempre** que agregues lógica de negocio, hazla testeable: inyecta abstracciones/`IDateTimeProvider` en vez de `now()`/`DateTime::now` hardcodeados cuando aplique; usa `now()` de Laravel en queries.
- **Nunca** uses SQL específico de MySQL en bruto (`DATE_FORMAT`, etc.): usa `App\Helpers\DatabaseHelper::formatMonth()` (SQLite/MySQL/PostgreSQL). El bug clásico de este repo fue `DATE_FORMAT` rompiendo SQLite.
- Modelos nuevos de tenant usan `use \App\Traits\BelongsToTenant;` y `protected $fillable` explícito.
- Livewire 4: usa `$this->dispatch(...)` y `Livewire.on(...)` (el listener nativo `window.addEventListener` NO captura eventos de Livewire). `$listeners` en el componente sigue funcionando.
- Vistas: estilo Bootstrap 4 + clases de AdminKit; modales con `wire:ignore.self` y `@livewire(...)` anidado.
- Textos UI en español. `lang/es.json` existe pero no se traduce todo.

## Estado conocido

- **Tests**: suite PHPUnit verde (24 tests: Billing, QuotationRepro, DriverTrip, Expense, TenantFeatureToggle, etc.).
- Datos de dev en SQLite; `quotations`/`invoices` casi vacías (solo registros de prueba).
- Quirk: `email_verified_at` NO está en `$fillable` de `App\Models\User` (usar `forceFill`).
- Quirk: `Tenant::current()` cae a `Tenant::first()` si no hay sesión (solo dev).
- Deuda: varias vistas duplican lógica; no hay PHPStan. El móvil (`/mobile`) está desacoplado.

## Seguridad (qué NO commitear)

- NUNCA commitees `.env`, `server.log`, credenciales reales ni API keys.
- Los ejemplos (`*.example`, `*.http`, seeders) usan placeholders `${VAR}`.
- No expongas secretos en logs ni responses; usa `config/` + env en lugar de hardcodear.
- No escribas credenciales SMTP/pasarelas de pago en el código (van por tenant `settings_json` en BD, no en git).
