# Task List: LogiSaaS Global Courier Ecosystem

Este documento detalla el estado actual del desarrollo de la plataforma.

## ✅ Funcionalidades Listas (Ready)
### 1. Núcleo Multi-Tenant (Foundations)
- [x] Base de datos única con discriminador (`tenant_id`).
- [x] Middleware de identificación por dominio/subdominio.
- [x] Trait `BelongsToTenant` con Global Scopes para aislamiento automático.
- [x] Autenticación Multi-tenant (Fortify) con Roles (SuperAdmin, Admin, Customer).

### 2. Módulo de Logística (Core)
- [x] Modelos: `Warehouse`, `Customer`, `Package`.
- [x] Interfaz de Recepción (Scanner) con identificación en tiempo real.
- [x] **Cálculo Volumétrico:** Fórmula automática `(L x W x H) / 166` en recepción.
- [x] **Etiquetas Térmicas:** Generación de etiquetas PDF 4x6 para bodega.
- [x] Listado de Inventario con búsqueda avanzada.
- [x] Sistema de estados de paquetes (Prealert, Received, In Transit, etc.).
- [x] **Módulo de Embarques:** Agrupación de paquetes en manifiestos y cambio de estado masivo.
- [x] Notificaciones Automáticas: Envío de correos al recibir carga.

### 3. Módulo de Facturación (Billing)
- [x] Modelos: `Invoice`, `InvoiceItem`.
- [x] Creador de Facturas dinámico y Dashboard de Cuentas por Cobrar.
- [x] **Pasarelas de Pago:** Integración completa con **Stripe** y **PayPal**.
- [x] **Reportes:** Exportación de facturas a **PDF**.

### 4. Website Builder & Integrations
- [x] Motor de Temas: Identidad visual (Colores y Fuentes) por Tenant.
- [x] **Widgets Embebidos (SDK):** Snippets de tracking y calculadora para sitios externos. ✅
- [x] **Portal en Subdominio:** Login/Registro listos para vincular a webs existentes. ✅
- [x] Editor Visual Profesional (Opcional): Para quienes no tienen sitio web.
- [x] **Configuración SMTP por Tenant:** Personalización de remitente de correos.

### 5. Portal del Cliente (White Label)
- [x] Dashboard personalizado con instrucciones de casillero.
- [x] **Integración Transparente:** Layouts adaptables para Iframes y Redirecciones. ✅
- [x] **Fidelización:** Sistema de **Puntos por peso** y **Red de Referidos**.
- [x] Formulario de Pre-alerta con **subida de Factura Comercial**.
- [x] Historial de Facturación con botones de pago y descarga PDF.

### 6. Super Administrador (Global Control)
- [x] Dashboard global de métricas y salud del sistema. ✅
- [x] Gestión de Tenants y Planes de Suscripción. ✅
- [x] **Impersonación (God Mode):** Ver el panel de cualquier tenant con un clic. ✅
- [x] **Gestor de Módulos:** Activar/Desactivar funciones (Reempaque, IA, etc.) por Tenant. ✅
- [x] **Inventario Global:** Tabla consolidada de toda la carga del ecosistema. ✅

---

## ⏳ Funcionalidades Pendientes (Pending)

### 🚀 Alta Prioridad (Mejora de UX y Última Milla)
- [x] **Optimización de Última Milla:** Implementar Manifiesto de Reparto Local para conductores. ✅
- [x] **Control de Entregas (POD):** Implementar Firma Digital y Evidencia Fotográfica en la entrega. ✅
- [x] **Liquidación de Choferes:** Módulo para control de cobros en efectivo (COD) por repartidor. ✅
- [x] **Geofencing de Entrega:** Registro de coordenadas GPS automáticas al momento de la entrega. ✅
- [x] **Módulo de Despacho (Counter):** Gestión de entregas en oficina con verificación de pagos y firma. ✅

### 📈 Prioridad Media (Expansión)
- [x] **PWA / App Móvil:** Versión base con manifest.json y soporte para instalación en Android/iOS. ✅
- [x] **Multilenguaje (i18n):** Infraestructura de traducciones dinámica por Tenant y soporte inicial Español/Inglés. ✅
- [ ] **Módulo de Compras (Personal Shopper):** Integración para que el cliente solicite compras directas en tiendas USA.
- [ ] **Seguros de Carga:** Ocupación de pólizas automáticas por valor declarado.

---
**Nota:** El sistema ha alcanzado un nivel de madurez operativa alto. El enfoque actual se desplaza hacia la **Eficiencia Logística (Reempaque)** y la **Última Milla (Delivery)**.
