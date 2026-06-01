# TASK LIST: IMPLEMENTACIÓN ERP LOGISAAS (NUEVOS MÓDULOS)

Este documento rastrea el progreso detallado de los 5 módulos definidos en el Plan Maestro de Implementación.

## 🟢 FASE 1: Automatización de Manifiestos y OCR (Core Logístico)
- [ ] **Estructura de Datos:**
    - [x] Crear migración para `manifests` (Tablas existentes, se validarán ajustes).
    - [x] Crear migración para `manifest_items` (Tablas existentes).
- [ ] **Lógica de Extracción (OCR Gratuito):**
    - [x] Instalar `smalot/pdfparser` vía Composer.
    - [x] Crear `ManifestParserService` para procesar PDFs de proveedores (Amazon, DHL, etc.).
    - [x] Implementar lógica de detección de Trackings IDs y Números de Factura en bloques de texto.
- [ ] **Interfaz de Usuario (Admin):**
    - [x] Pantalla de subida de PDF y previsualización de datos extraídos.
    - [x] Botón "Generar Lote Pendiente" para crear el manifiesto.

## 🟢 FASE 2: Recepción Inteligente (Operaciones)
- [ ] **Interfaz de Escaneo (Livewire):**
    - [x] Crear componente `ReceiveManifestScanner` optimizado para pistolas de barcode (Integrado en ReceiveManifest).
    - [x] Implementar feedback visual (Verde: Esperado, Amarillo: Extra, Rojo: Error/Duplicado).
    - [x] Sonidos de alerta para escaneo (Gratis: Archivos .mp3 locales).
- [ ] **Gestión de Discrepancias:**
    - [x] Modal de resumen al finalizar: "Faltantes: X, Extras: Y" (Implementado en vista Detail).
    - [ ] Reporte automático de diferencias para el proveedor.

## 🔵 FASE 3: Fidelización 2.0 (Puntos y Niveles)
- [ ] **Configuración de Niveles:**
    - [x] Tabla `loyalty_levels` (Nombre, Rango Puntos, Multiplicador, Icono, Color).
    - [x] CRUD en el panel de configuración del Tenant.
- [ ] **Motor de Puntos:**
    - [x] Evento `PackageDelivered` para asignar puntos automáticamente según peso/configuración (Implementado en Modelo Package + LoyaltyService).
    - [x] Historial de movimientos de puntos para el cliente (Tabla `loyalty_points_history`).
- [ ] **Dashboard Cliente:**
    - [x] Barra de progreso visual (Gamificación) en el Dashboard del Cliente.
    - [ ] Sección "Mis Beneficios Unlockeados".

## 🔵 FASE 4: Pagos y Entregas (Finanzas & Delivery)
- [ ] **Checkout de Entrega:**
    - [x] Selección de método de entrega (Sucursal vs Delivery).
    - [x] Selección de método de pago (Incluyendo Contra Entrega).
- [ ] **Validación de Pagos Manuales:**
    - [x] Interfaz para que el cliente suba fotos de comprobantes (Yappy/ACH).
    - [x] Panel de aprobación/rechazo para administración con notificaciones.
- [ ] **App de Reparto (Mejoras):**
    - [ ] Integración de cobros registrados en sitio (Cash/Yappy).

## 🟡 FASE 5: Promociones y Automatización WhatsApp
- [ ] **Motor de Promociones:**
    - [x] Creación de cupones con reglas (X% descuento, $X fijos, Vencimiento).
- [ ] **WhatsApp Bot (Gratuito):**
    - [x] Generación automática de mensajes pre-formateados para el cliente (WhatsAppHelper).
    - [x] Integración de botones "Contactar por WhatsApp" en puntos críticos (Facturas/Pagos).

## 🔥 FASE 6: Inteligencia Super Admin (Gobernanza)
- [ ] **Métricas Globales (Inteligencia Operativa):**
    - [ ] Dashboard Maestro: Conteo de paquetes, pesos y estados de toda la red.
    - [ ] Analítica de Usuarios: Usuarios online (15min) y activos (7 días).
    - [ ] Salud Financiera: Resumen consolidado de facturación y morosidad de todos los tenants.
- [ ] **Gobernanza y Soporte:**
    - [ ] Impersonación: Botón "Login as" en lista de Tenants (Super Admin visualiza como el cliente).
    - [x] Estructura de Cobranza: Campos `next_billing_at` y `payment_warning_active` en DB.
    - [ ] Sistema de Recordatorios: Banner automático en el Dashboard del Tenant basado en fecha de vencimiento.
- [ ] **Buscador Maestro:**
    - [ ] Explorador Cross-Tenant: Buscar trackings en todas las empresas simultáneamente.

---
**Leyenda de Estados:**
- 🔴 Bloqueado / No iniciado
- 🟡 En progreso
- 🟢 Listo para pruebas
- ✅ Finalizado
