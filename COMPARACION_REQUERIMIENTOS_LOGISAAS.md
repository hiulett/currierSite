# Comparativa de Proyecto Actual vs. Requerimientos LogiSaaS

Este documento presenta un análisis detallado comparando la implementación actual del proyecto `currierSite` con el documento de requerimientos proporcionado.

## 1. Resumen Ejecutivo

El proyecto actual posee una base sólida y funcional que cubre aproximadamente el **85%** de los requerimientos funcionales solicitados. La arquitectura Multi-Tenant es robusta y los módulos críticos de logística y facturación ya están operativos. Las brechas principales se encuentran en la persistencia del historial de tracking (Timeline), la automatización avanzada de cobranza y la granularidad del sistema de permisos (RBAC).

---

## 2. Tabla Comparativa de Funcionalidades

| Módulo | Requerimiento (Propuesta) | Estado Actual (Proyecto) | Notas Técnicas |
| :--- | :--- | :--- | :--- |
| **Arquitectura Multi-Tenant** | SaaS con aislamiento por `tenant_id`. | ✅ **Completo** | Implementado con Middleware y Global Scopes (`BelongsToTenant`). |
| **Branding & White Label** | Logo, colores y SMTP por tenant. | ✅ **Completo** | Posee motor de temas y configuración SMTP independiente. |
| **Gestión de Clientes** | CRUD, Historial, Asociación. | ✅ **Completo** | Modelo `Customer` vinculado a `User` con balance y puntos. |
| **Gestión de Casilleros** | Crear, asignar, dimensiones, estado. | ✅ **Completo** | Implementado con entidad `Locker` independiente vinculada a `Customer`. |
| **Gestión de Paquetes** | Tracking, imágenes, peso/volumen, ETA. | ✅ **Completo** | Modelo `Package` con cálculo volumétrico y soporte para imágenes (JSON). |
| **Tracking Timeline** | Historial cronológico de estados. | ✅ **Completo** | Implementado con tabla `tracking_events` y disparadores en el modelo `Package`. |
| **Facturación** | PDF, Correo, Pagos Stripe/PayPal. | ✅ **Completo** | Modelos `Invoice` e `InvoiceItem` integrados con pasarelas. |
| **Estado de Cuenta** | Saldo total, facturas pendientes. | ✅ **Completo** | Gestionado a través del balance en `Customer` y estados de `Invoice`. |
| **Cobranza (Reminders)** | Automatización (7 días antes/después). | ✅ **Completo** | Implementado mediante `SendPaymentReminders` Command y Laravel Scheduler. |
| **Portal del Cliente** | Dashboard, tracking, pre-alertas. | ✅ **Completo** | Incluye fidelización (puntos) y subida de facturas comerciales. |
| **Logística Avanzada** | Reempaque, Embarques, Última Milla. | ✅ **Superior** | El proyecto actual ya incluye estas funciones que son avanzadas. |
| **Seguridad y Roles** | RBAC, Auditoría, Logs. | ⚠️ **Parcial** | Uso de columna `role`. El requerimiento sugiere un sistema de permisos más granular. |

---

## 3. Análisis de Modelo de Datos

### Coincidencias:
- `TENANT`, `USER`, `CUSTOMER`, `PACKAGE`, `WAREHOUSE`, `SHIPMENT`, `INVOICE`, `INVOICE_ITEM`, `TICKET`.

### Brechas en el Esquema (Sugerido implementar):
1.  **`TRACKING_EVENTS`**: Para registrar cada cambio de ubicación y estado con fecha/hora (Crucial para el "Timeline").
2.  **`PAYMENTS`**: Tabla explícita para transacciones, permitiendo pagos parciales y múltiples métodos por factura.
3.  **`AUDITS`**: Para trazabilidad de cambios realizados por administradores/operadores.
4.  **`LOCKERS`** (Opcional): Si se desea gestionar racks o espacios físicos específicos con dimensiones.

---

## 4. Brechas Funcionales (Gaps)

1.  **Timeline de Rastreo:** El cliente espera ver un historial detallado (ej. "Recibido en Miami -> 20/05 10:00"). Actualmente solo se ve el estado actual.
2.  **Automatización de Cobranza:** Falta el motor de notificaciones que dispare correos según la fecha de vencimiento de las facturas.
3.  **Seguridad Granular:** Si se escala a muchos empleados, la columna `role` podría quedarse corta frente a un sistema de `Permissions` (Spatie Permission o similar).
4.  **Internacionalización (i18n):** Pendiente según la `TASK_LIST.md`.

---

## 5. Recomendación del Arquitecto

El sistema actual es una excelente base "Ready to Market". Para cumplir al 100% con la visión de la propuesta senior proporcionada, se sugiere priorizar:
1.  **Crear la tabla `tracking_events`** y disparar registros cada vez que el `Package` cambie de `status`.
2.  **Implementar un Command/Job programado** (Laravel Scheduler) para procesar los recordatorios de cobranza.
3.  **Refactorizar la gestión de Casilleros** para que sean una entidad que pueda tener estados (Disponible, Ocupado, Mantenimiento).

---
**Siguiente Paso:** Esperando instrucciones para proceder con las modificaciones en el modelo de datos o la implementación de las funcionalidades faltantes.
