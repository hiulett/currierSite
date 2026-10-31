# Historias de Usuario — Facturación (creación y envío)

**Épica:** Estabilización del ciclo de facturación (crear → notificar → cobrar).
**Origen:** Diagnóstico del 2026-10-30 (facturas que no se creaban y correos que nunca salían).
**Estado:** Implementado.

---

## HU-FAC-01 — Crear factura seleccionando paquetes

**Como** operador de facturación
**Quiero** generar una factura marcando solo los paquetes pendientes del cliente
**Para** no tener que escribir una línea manual cuando todo el cobro viene del inventario.

### Criterios de aceptación
- [x] Al marcar el primer paquete se descarta automáticamente la fila vacía del formulario.
- [x] Al guardar se descartan filas totalmente vacías antes de validar.
- [x] Si tras el filtro no queda ningún concepto, se muestra el error "Agregue al menos un concepto o seleccione un paquete para facturar".
- [x] El subtotal de cada línea se recalcula en el servidor (`cantidad × precio`), sin confiar en el total del navegador.
- [x] La factura, sus ítems y el balance del cliente se guardan en una sola transacción.
- [x] LOGYPUNTOS se acumula después del commit: un fallo de fidelización ya no revierte la factura.
- [x] Si `found_customer` aún es null (carrera con `wire:model.live`), se resuelve por `box_number` antes de guardar.
- [x] Un fallo inesperado se registra con contexto y se muestra un mensaje claro, sin pantalla de error genérica.
- [x] Al terminar, el formulario muestra "Factura INV-… generada exitosamente."

### Implementación
- `app/Livewire/Billing/CreateInvoice.php` → `togglePackage()`, `save()`.
- Prueba: `tests/Feature/InvoiceDeliveryTest::test_create_invoice_with_only_selected_packages_does_not_require_manual_row`.

---

## HU-FAC-02 — Ver por qué no se pudo generar la factura

**Como** operador
**Quiero** ver los errores de validación al generar una factura
**Para** no quedarme con un botón que "no hace nada".

### Criterios de aceptación
- [x] El formulario muestra un bloque de alerta con todos los errores de validación (`$errors->all()`).
- [x] Se mantienen los mensajes inline por campo existentes (ej. `box_number`).
- [x] El guardado sigue mostrando el mensaje de éxito cuando todo es válido.

### Implementación
- `resources/views/livewire/billing/create-invoice.blade.php` (bloque `@if ($errors->any())`).

---

## HU-FAC-03 — Folio de factura único y secuencial

**Como** administrador del courier
**Quiero** que cada factura tenga un número único por tenant, secuencial por día
**Para** evitar facturas que fallan por duplicado y facilitar la auditoría contable.

### Criterios de aceptación
- [x] Formato `INV-{Ymd}-{0001}` (`INV-20261030-0001`).
- [x] La secuencia se reinicia cada día y es independiente por tenant.
- [x] Si dos procesos generan el mismo número, se reintenta (hasta 5 veces) antes de fallar.
- [x] El índice único de `invoices` pasa de global (`number`) a compuesto (`tenant_id`, `number`).
- [x] Se aplica en las tres rutas de creación: facturación manual, recepción con factura automática y asignación masiva de inventario.

### Implementación
- `app/Services/Billing/InvoiceNumberGenerator.php` (nuevo).
- `database/migrations/2026_10_30_000001_make_invoice_number_unique_per_tenant.php` (nuevo).
- `app/Livewire/Billing/CreateInvoice.php`, `app/Livewire/Logistics/ReceivePackage.php`, `app/Livewire/Logistics/InventoryList.php`.
- Pruebas: `tests/Feature/InvoiceDeliveryTest::test_invoice_numbers_are_sequential_per_tenant_and_day`, `test_same_number_is_allowed_for_different_tenants`.

---

## HU-FAC-04 — Enviar la factura por correo con estado real

**Como** operador
**Quiero** enviar la factura al cliente por correo y saber si realmente salió
**Para** no repetir envíos ni asumir que el cliente ya la recibió.

### Criterios de aceptación
- [x] El correo adjunta el PDF de la factura y usa la plantilla del tenant.
- [x] `email_sent_at` se marca **solo** cuando el canal de correo confirma el envío (evento `NotificationSent`), no al encolar.
- [x] Al encolar se registra `email_queued_at` y el listado muestra "✉ En cola" mientras no haya resultado (si permanece así, el worker no está procesando).
- [x] Si el envío falla, `email_error` guarda el motivo (`NotificationFailed`) y el listado muestra "✉ Error" con el detalle en el tooltip; el fallo también queda en el log.
- [x] Si el cliente no tiene correo, se muestra un error explícito.
- [x] Un fallo de envío ya no rompe la notificación por un `Log::error` mal ubicado (`$e` fuera del `catch`).
- [x] El envío no bloquea la petición: SMTP con `timeout` de 15s.
- [x] La notificación de llegada de paquetes (`PackagesArrivedNotification`) sigue el mismo comportamiento.

### Implementación
- `app/Notifications/InvoiceSent.php`, `app/Notifications/PackagesArrivedNotification.php`.
- `app/Services/TenantMailConfigurator.php` (config SMTP única: middleware + worker).
- `app/Listeners/MarkInvoiceEmailSent.php` y `app/Listeners/MarkInvoiceEmailFailed.php` + registro en `app/Providers/AppServiceProvider.php`.
- `app/Livewire/Billing/InvoiceList.php` → `sendEmail()`.
- Migración `2026_10_31_000001_add_email_status_to_invoices_table.php` (`email_queued_at`, `email_error`).
- Pruebas: `tests/Feature/InvoiceDeliveryTest` (envío correcto y registro de fallo).

---

## HU-FAC-05 — Feedback visual mientras se procesa

**Como** operador
**Quiero** ver un spinner y el botón deshabilitado mientras genero o envío una factura
**Para** saber que el sistema está trabajando y no hacer doble clic.

### Criterios de aceptación
- [x] "GENERAR FACTURA AHORA" muestra `spinner-border` + texto "GENERANDO FACTURA..." y queda deshabilitado (`wire:loading` + `wire:target="save"`).
- [x] El spinner solo es visible durante la petición: el elemento con `wire:loading` no lleva utilidades de display (`d-flex`, `d-inline-flex`, etc.) porque su `!important` anula el `display:none` que aplica Livewire.
- [x] El botón de correo de cada fila muestra spinner propio y se deshabilita solo para esa factura (`wire:target="sendEmail({id})"`).
- [x] Al terminar, el estado vuelve a la normalidad sin recargar la página.

### Implementación
- `resources/views/livewire/billing/create-invoice.blade.php`.
- `resources/views/livewire/billing/invoice-list.blade.php`.

---

## HU-FAC-06 — Recepción de paquete con factura automática sin error final

**Como** operador de bodega
**Quiero** que al registrar un paquete con factura automática el proceso termine sin errores
**Para** no repetir la recepción y duplicar paquete/factura.

### Criterios de aceptación
- [x] El paquete queda en la lista de recientes usando el cliente encontrado (`$this->found_customer->id`).
- [x] Ya no se produce `Undefined variable $customer` después de confirmar la transacción.
- [x] La factura automática usa el generador de folios por tenant.

### Implementación
- `app/Livewire/Logistics/ReceivePackage.php` → `save()`.

---

## HU-FAC-07 — Cola de correo siempre disponible

**Como** responsable de operaciones
**Quiero** que el worker de colas se reinicie solo si se detiene
**Para** que los correos no queden atrapados en `jobs` hasta el próximo deploy.

### Criterios de aceptación
- [x] `railway-deploy.sh` ejecuta el worker en un bucle con reinicio automático.
- [x] El worker se recicla antes del límite (`--max-time=3500`) y vuelve a arrancar en 2 s.
- [x] Documentado que lo recomendado en producción es un servicio de worker independiente.

### Implementación
- `railway-deploy.sh`.
- **Operación pendiente:** monitorear `php artisan queue:failed` y `failed_jobs` tras el despliegue.

---

## HU-FAC-08 — Iconos de la interfaz sin errores en consola

**Como** usuario del panel
**Quiero** que los iconos se rendericen siempre
**Para** no ver pantallas incompletas ni errores de JavaScript al navegar.

### Criterios de aceptación
- [x] Ningún icono `<i data-feather>` lleva directivas Livewire/Alpine en el mismo elemento.
- [x] El layout renderiza los iconos uno por uno con `<template>` (parser HTML tolerante) en vez de `feather.replace()`.
- [x] Los iconos inválidos se omiten con un `console.warn` que indica el nombre, sin romper el resto de la página.
- [x] Verificado en navegador: `/dashboard`, `/facturacion`, `/facturacion/nueva`, `/logistica/inventario` y `/logistica/recepcion` sin errores de consola.

### Implementación
- `resources/views/components/layouts/app.blade.php` → `renderFeatherIcon()`, `initFeather()`.
- `resources/views/livewire/billing/invoice-list.blade.php` → directivas de carga movidas a un `<span>` contenedor.

---

## HU-FAC-09 — Logo de la empresa en todas las facturas

**Como** administrador del courier
**Quiero** que todas las facturas muestren el logo de Logy Express
**Para** mantener una imagen de marca consistente en PDF, web y correo.

### Criterios de aceptación
- [x] El PDF de la factura muestra el logo en el encabezado a 260 px de ancho (y el estado de cuenta a 220 px).
- [x] La factura abierta online (misma vista PDF) y el listado de facturación muestran el logo.
- [x] El correo de la factura muestra el logo (220 px) en el encabezado y mantiene el PDF adjunto.
- [x] El logo se resuelve con `Tenant::getLogoUrl()` (prioriza `public/logos/{subdominio}.png`) y `DocumentPdf::logoBase64()` para PDFs.
- [x] Funciona aunque el logo del tenant esté en local o en una URL remota, y si falta cae al nombre de la empresa.

### Implementación
- `public/logos/logyexpress.png` (nuevo logo 1000×321).
- `app/Helpers/DocumentPdf.php`, `app/Http/Controllers/Billing/InvoiceController.php`.
- `app/Notifications/InvoiceSent.php`, `app/Notifications/PackagesArrivedNotification.php`.
- `app/Services/TenantMailConfigurator.php` + `resources/views/vendor/mail/html/header.blade.php`.
- Vistas: `billing/invoice-pdf.blade.php`, `billing/statement-pdf.blade.php`, `livewire/billing/invoice-list.blade.php`, `livewire/customer/invoice-list.blade.php`.
- Prueba: `tests/Feature/InvoiceDeliveryTest::test_invoice_documents_use_the_tenant_logo`.

---

## Definición de terminado (global)

- [x] Suite PHPUnit verde (`92 tests`).
- [x] Pint aplicado a los archivos modificados.
- [x] Documentación actualizada: `AGENTS.md`, `TASK_LIST.md` y este archivo.
- [ ] Verificación en producción: `jobs`, `failed_jobs` y badge "✉ Enviado" coherentes con los correos recibidos.
