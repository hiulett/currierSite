# Documento de Requerimientos Funcionales y Técnicos: LogiSaaS
## Plataforma SaaS Multiempresa de Courier y Logística Internacional

**Versión:** 1.0  
**Estado:** Borrador Maestro  
**Roles de Autoría:** Arquitecto Empresarial, Arquitecto de Software, Product Owner Senior, Analista de Negocios.

---

## 1. Documento de Visión del Producto
LogiSaaS nace para democratizar el acceso a tecnología de punta para empresas de logística, carga internacional y casilleros virtuales. La visión es permitir que cualquier emprendedor logístico pueda lanzar su operación digital en minutos, con herramientas que compiten con las grandes corporaciones del sector (DHL, FedEx, etc.), bajo un modelo de suscripción escalable.

## 2. Requerimientos Funcionales
### Módulo 1: Super Administrador (Admin Global)
- Gestión completa de Tenants (Empresas).
- Configuración de planes de suscripción y límites (almacenamiento, usuarios, paquetes).
- Dashboard de métricas consolidadas (MRR, Churn, volumen de carga global).
- Marketplace de integraciones (activar/desactivar módulos para empresas específicas).

### Módulo 2: Gestión de Empresas (Tenant Admin)
- White-labeling: Logo, colores, dominio personalizado (CNAME).
- Estructura organizacional: Sucursales, bodegas y roles de empleados.

### Módulo 3: Sitio Web Corporativo & Builder
- CMS integrado para páginas informativas.
- **Website Builder:** Editor drag-and-drop para personalizar la experiencia de marca sin código.

### Módulo 4: Portal de Clientes
- Autogestión de perfil y documentos de identidad.
- Pre-alertas de paquetes.
- Calculadora de fletes y tracking en tiempo real.

### Módulo 5: Gestión de Casilleros
- Asignación automática de direcciones físicas en el extranjero (Miami, etc.).
- Formato de dirección personalizable: `[Nombre] [ID_CASILLERO] [Dirección Bodega]`.

### Módulo 6: Gestión Logística (Operaciones)
- Recepción masiva vía Scanner/App.
- Gestión de inventario en bodega (ubicaciones/racks).
- Procesos de valor agregado: Consolidación, reempaque, fotos de contenido.

### Módulo 8: Facturación y Pagos
- Facturación electrónica multi-país.
- Wallet de cliente: Pagos por anticipado o contra-entrega.
- Integración con pasarelas (Stripe, PayPal, procesadores locales).

## 3. Requerimientos No Funcionales
- **Disponibilidad:** 99.9% (High Availability).
- **Seguridad:** Aislamiento de datos nivel Base de Datos (Tenant ID). Encriptación en tránsito y reposo.
- **Rendimiento:** Carga de página < 2s. Soporte para concurrencia masiva durante temporadas altas (Black Friday).
- **Escalabilidad:** Arquitectura de microservicios o monolito modular preparado para auto-scaling.

## 4. Casos de Uso
1. **CU-01: Registro de Nueva Empresa:** Un emprendedor se registra, paga su plan y el sistema despliega automáticamente su subdominio y entorno administrativo.
2. **CU-02: Recepción de Paquete:** El operario escanea un tracking de Amazon, el sistema identifica al cliente por el ID del casillero y notifica automáticamente al usuario con una foto.
3. **CU-03: Solicitud de Consolidación:** Un cliente selecciona 5 paquetes recibidos y solicita un solo envío para ahorrar costos; el sistema genera una orden de trabajo para bodega.

## 5. Historias de Usuario (Ejemplos)
- **Como** Cliente, **quiero** pre-alertar mis compras de Amazon **para** que la bodega sepa qué esperar y el proceso de ingreso sea más rápido.
- **Como** Operario, **quiero** tomar fotos del paquete al llegar **para** documentar el estado físico y evitar reclamos por daños.
- **Como** Dueño de Empresa, **quiero** ver un reporte de paquetes en tránsito **para** proyectar mis ingresos de la semana.

## 6. Epics
- **Epic Logistics Core:** Todo lo relacionado al movimiento de carga.
- **Epic FinTech:** Pasarelas de pago, facturación y estados de cuenta.
- **Epic Tenant Management:** Ciclo de vida de las empresas suscritas.
- **Epic UI Builder:** Herramientas de personalización visual para las empresas.

## 7. Roadmap
- **Q1:** MVP - Registro de empresas, Recepción básica, Tracking y Facturación simple.
- **Q2:** Website Builder, Integración con Stripe, App Móvil (Portal Cliente).
- **Q3:** Inteligencia Artificial (OCR de facturas), CRM avanzado, Automatizaciones de Marketing.
- **Q4:** Expansión internacional, Facturación electrónica local para 5 países clave.

## 8. Arquitectura de Software
- **Patrón:** Clean Architecture.
- **Frontend:** Laravel Livewire + Alpine.js (TALL Stack) para una experiencia SPA sin la complejidad de React/Vue.
- **Backend:** PHP 8.4 con Laravel 11+.
- **Comunicación:** API RESTful para futuros clientes móviles y externos.
- **Background Tasks:** Redis Queues para procesamiento de correos, PDFs y reportes.

## 9. Modelo de Dominio
- **Entidades:** Tenant, Warehouse, Customer, Package, Shipment, Invoice, Payment, Tracker.
- **Value Objects:** Dimensions, Weight, Address, Currency.

## 10. Modelo de Base de Datos
- **Table `tenants`:** `id, uuid, name, domain, status, plan_id, settings_json`.
- **Table `packages`:** `id, tenant_id, customer_id, tracking_number, weight, volume, status, warehouse_id`.
- **Table `customers`:** `id, tenant_id, user_id, box_number, points, balance`.

## 11. Diseño Multi-Tenant
Se utilizará una **Base de Datos Única con Discriminador**.
- Cada tabla relevante tendrá una columna `tenant_id`.
- Un Middleware de Laravel identificará el Tenant por el dominio/subdominio de la petición.
- Se aplicará un Global Scope en los modelos de Eloquent para filtrar automáticamente por el `tenant_id` activo.

## 12. Diseño UI/UX
- **Admin:** Basado en AdminLTE / Tailwind UI. Dashboard limpio, enfocado en datos operativos.
- **Portal Cliente:** Mobile-first, botones grandes, acceso rápido a "Mis Paquetes" y "Pre-alertar".
- **Colores:** Dinámicos según la configuración del Tenant (CSS Variables).

## 13. Estructura de APIs
- `/api/v1/auth`: Autenticación JWT.
- `/api/v1/packages`: Consulta de paquetes y estados.
- `/api/v1/webhooks`: Recepción de eventos de transportistas externos.

## 14. Estrategia de Escalabilidad
- Separación de servicios: Web server, Worker server y Database server.
- Uso de S3 para almacenamiento masivo de fotos de paquetes.
- Caché intensivo con Redis para configuraciones de Tenants frecuentemente consultadas.

## 15. Estrategia de Despliegue
- **Infraestructura:** AWS o DigitalOcean.
- **CI/CD:** GitHub Actions para pruebas automáticas y despliegue a producción.
- **Contenedores:** Docker para garantizar paridad de entornos.

## 16. Estrategia SaaS
- Onboarding automatizado.
- Soporte via sistema de tickets integrado.
- Documentación (Knowledge Base) centralizada pero con marca blanca para cada empresa.

## 17. Modelo de Monetización
- **Suscripción Mensual:** Tiered pricing (Starter, Professional, Enterprise).
- **Add-ons:** Cobro extra por módulos de IA (OCR), usuarios adicionales o almacenamiento extra.

## 18. Riesgos Técnicos
- **Seguridad de Datos:** Riesgo de fuga de datos entre Tenants. Mitigación: Auditorías de código y tests de integración sobre los Scopes.
- **Latencia:** Crecimiento acelerado de la BD. Mitigación: Estrategias de particionamiento y optimización de índices.

## 19. Plan de Implementación por Fases
- **Fase 1 (Semanas 1-4):** Base Multi-tenant y Auth.
- **Fase 2 (Semanas 5-10):** Logística central (Recepción y Bodega).
- **Fase 3 (Semanas 11-16):** Facturación, Pagos y Portal Cliente.
- **Fase 4 (Semanas 17-24):** Website Builder e Inteligencia Artificial.

## 20. Estimación de Esfuerzo
- **Backend:** 40% (Lógica de negocio compleja).
- **Frontend/UX:** 30% (Foco en usabilidad operativa).
- **DevOps/Infra:** 15%.
- **QA/Testing:** 15%.

---
**Nota Final:** La inclusión del **Website Builder** permite que la plataforma no solo sea un software de gestión, sino una solución "Negocio en una Caja" (Business in a Box), reduciendo drásticamente el Time-to-Market de los nuevos couriers.
