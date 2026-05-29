# PLAN MAESTRO DE IMPLEMENTACIÓN - LOGISAAS ERP

## 1. Arquitectura General (Tecnología 100% Gratuita)
*   **Backend:** Laravel 10+ (Open Source).
*   **Frontend:** Livewire 3 + Tailwind CSS (Gratis).
*   **OCR / Extracción:** `smalot/pdfparser` para PDFs digitales y `Tesseract OCR` (Motor local gratuito) para imágenes.
*   **Base de Datos:** MySQL / MariaDB (Gratis).
*   **WhatsApp:** Automatización vía `wa.me` links y Webhooks gratuitos. No se usarán APIs de pago por mensaje.
*   **Almacenamiento:** Disco local (Storage) o Cloudflare R2 (Free Tier).

## 2. Módulos y Funcionalidades

### Módulo 1: Recepción de Carga y Manifiestos
*   **Lectura de Factura:** Procesamiento local en el servidor (sin APIs externas).
*   **Lógica de Negocio:** Comparación local de strings para detectar Trackings.
*   **Estados del Manifiesto:** Pendiente, En recepción, Parcial, Completado, Con discrepancias, Cerrado.
*   **Flujo de Recepción:**
    *   Interfaz optimizada para escaneo rápido.
    *   Validación en tiempo real (Paquete esperado, extra, duplicado o inexistente).
    *   Registro de discrepancias automático al finalizar.
*   **Inventario:** Los paquetes no afectan la operatividad hasta ser confirmados físicamente.

### Módulo 2: Fidelización (Puntos y Niveles)
*   **Configuración:** Reglas de acumulación por libra (configurable por tenant).
*   **Niveles:** Estructura dinámica (Bronze, Silver, Gold, etc.) con multiplicadores y beneficios.
*   **Gamificación:** Barra de progreso visual para el cliente ("Te faltan X puntos para el siguiente nivel").
*   **Recompensas:** Catálogo administrable de créditos, descuentos o regalos.

### Módulo 3: Sistema de Promociones
*   **Motor de Reglas:** Cupones, descuentos VIP, bonos temporales y puntos extra.
*   **Visibilidad:** Banners no invasivos en el dashboard y notificaciones push/WhatsApp.

### Módulo 4: Pagos y Entregas (Delivery 2.0)
*   **Gestión de Direcciones:** Uso de la dirección de registro para delivery automático.
*   **Checkout Multi-método:**
    *   Automáticos: Tarjeta de Crédito (Stripe/PayPal).
    *   Manuales: Yappy, Transferencia/ACH (con flujo de aprobación de comprobante).
    *   Contra Entrega: Efectivo/Yappy al recibir.
*   **Logística de Entrega:** Asignación de rutas, captura de firma y foto de evidencia.

## 3. Modelo de Datos Conceptual (Nuevas Tablas)
*   `manifests`: ID, tenant_id, status, totals_expected, totals_received.
*   `manifest_items`: tracking_number, status (expected/received/extra), scanned_at.
*   `loyalty_levels`: name, min_points, multiplier, color, icon.
*   `loyalty_points_history`: customer_id, points, type (earn/burn), reference.
*   `promotions`: code, discount_type, value, active_until.
*   `payment_proofs`: invoice_id, image_url, status (pending/approved/rejected).

## 4. Estrategia de Implementación (Roadmap)
1.  **Fase 1 (Logística Core):** Implementación de Manifiestos y motor OCR.
2.  **Fase 2 (Recepción):** UI de escaneo y gestión de discrepancias.
3.  **Fase 3 (Fidelización):** Sistema de puntos, niveles y panel del cliente.
4.  **Fase 4 (E-Commerce):** Integración de pagos, comprobantes y checkout.
5.  **Fase 5 (Delivery & Marketing):** Gestión de rutas y sistema de promociones/WhatsApp.

## 5. Riesgos y Mitigación
*   **Precisión OCR:** Implementar validación manual humana para trackings no reconocidos.
*   **Concurrencia:** Uso de WebSockets para evitar que dos operadores procesen el mismo paquete.
*   **Seguridad:** Transacciones de base de datos para asegurar que los puntos se asignen solo tras pago confirmado.

## 6. Integración WhatsApp
*   Consultas de saldo de puntos y estado de paquetes vía Bot.
*   Notificaciones de llegada de carga y cupones promocionales.

---
**Estado:** Pendiente de Aprobación.
