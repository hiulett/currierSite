# Credenciales y Datos de Prueba - LogiSaaS

Este documento contiene la información necesaria para probar las diferentes capas del sistema después de ejecutar el seeder.

## 1. Super Administrador (Control Global)
- **Email:** `superadmin@logisaas.com`
- **Password:** `admin123`
- **Uso:** Gestión de planes y visualización de todos los Tenants.

## 2. Administrador de Tenant (LogiExpress)
- **Email:** `admin@logiexpress.com`
- **Password:** `password`
- **Tenant:** LogiExpress Panama (`logiexpress.test`)
- **Uso:** Gestión de bodega, creación de facturas y configuración de la marca.

## 3. Cliente Final (Juan Perez)
- **Email:** `cliente@gmail.com`
- **Password:** `password`
- **Casillero:** `LEX-5501`
- **Uso:** Ver paquetes en tránsito, pre-alertar y probar las pasarelas de pago (Stripe/PayPal).

---

## 🔗 Mapa de Rutas del Sistema

### 🌐 Rutas Públicas (Sin Login)
- **Inicio:** [http://127.0.0.1:8000/](http://127.0.0.1:8000/)
- **Login:** [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)
- **Registro:** [http://127.0.0.1:8000/register](http://127.0.0.1:8000/register)
- **Rastreo Público:** [http://127.0.0.1:8000/tracking](http://127.0.0.1:8000/tracking)
- **Calculadora de Aranceles:** [http://127.0.0.1:8000/calculadora](http://127.0.0.1:8000/calculadora)

### 📦 Módulo de Logística (Admin/Operador)
- **Recepción (Scanner):** [http://127.0.0.1:8000/logistica/recepcion](http://127.0.0.1:8000/logistica/recepcion)
- **Inventario:** [http://127.0.0.1:8000/logistica/inventario](http://127.0.0.1:8000/logistica/inventario)
- **Reempaque:** [http://127.0.0.1:8000/logistica/reempaque](http://127.0.0.1:8000/logistica/reempaque)
- **Embarques (Bulk):** [http://127.0.0.1:8000/logistica/embarques](http://127.0.0.1:8000/logistica/embarques)
- **Última Milla:** [http://127.0.0.1:8000/logistica/ultima-milla](http://127.0.0.1:8000/logistica/ultima-milla)

### 💳 Administración y White Label
- **Facturación:** [http://127.0.0.1:8000/facturacion](http://127.0.0.1:8000/facturacion)
- **Identidad Visual:** [http://127.0.0.1:8000/builder/brand](http://127.0.0.1:8000/builder/brand)
- **Configuración Correo:** [http://127.0.0.1:8000/builder/mail](http://127.0.0.1:8000/builder/mail)
- **Integraciones (SDK):** [http://127.0.0.1:8000/builder/integraciones](http://127.0.0.1:8000/builder/integraciones)

### 👤 Portal del Cliente
- **Dashboard:** [http://127.0.0.1:8000/portal](http://127.0.0.1:8000/portal)
- **Soporte Técnico:** [http://127.0.0.1:8000/portal/soporte](http://127.0.0.1:8000/portal/soporte)
- **WhatsApp IA:** [http://127.0.0.1:8000/portal/whatsapp](http://127.0.0.1:8000/portal/whatsapp)

### 🛠️ SuperAdmin (Global)
- **Dashboard Global:** [http://127.0.0.1:8000/superadmin](http://127.0.0.1:8000/superadmin)

---

## Datos Insertados para Pruebas

### Bodegas (Warehouses)
- **Miami Main Hub (MIA-01):** Centro de recepción principal en USA.
- **Panama Center (PTY-01):** Centro de distribución local.

### Clientes Adicionales
- **Juan Perez:** `cliente@gmail.com` | Casillero: `LEX-5501` | ID: `8-765-4321`
- **Maria Lopez:** `mlopez@test.com` | Casillero: `LEX-5502` | ID: `PE-123-456`
- **Carlos Ruiz:** `cruiz@test.com` | Casillero: `LEX-5503` | ID: `4-111-2222` (Tiene factura vencida)
- **Ana Smith:** `asmith@test.com` | Casillero: `LEX-5504` | ID: `N-20-3333`

### Paquetes por Estado (Trackings)
- `UPS1001`: Recibido (Juan Perez)
- `FEDEX2002`: En Tránsito (Juan Perez)
- `DHL3003`: Llegó al País (Maria Lopez)
- `AMZ4004`: Listo para Retiro (Maria Lopez)
- `USPS5005`: Pre-alerta (Carlos Ruiz)
- `UPS6006`: Entregado (Carlos Ruiz)
- `PTY7007`: En Ruta de Entrega (Juan Perez)

### Facturas Diversas
- `INV-001`: **Pendiente ($25.50)** - Cliente: Juan Perez.
- `INV-002`: **PAGADA ($110.00)** - Cliente: Maria Lopez.
- `INV-003`: **VENCIDA ($45.00)** - Cliente: Carlos Ruiz (Úsala para probar alertas de deuda en Despacho).

### Casilleros Físicos (Lockers)
- Códigos: `SEC-A-01` al `SEC-A-05` (Todos disponibles para asignación).
