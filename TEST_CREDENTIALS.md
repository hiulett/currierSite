# Credenciales y Datos de Prueba - LogiSaaS (Producción Railway)

Este documento contiene la información necesaria para probar las diferentes capas del sistema en el entorno de producción.

## 1. Super Administrador (Control Global Maestro)
- **Email:** `admin@logisaas.com`
- **Password:** `password123`
- **Uso:** Gestión de planes, visualización de todos los Tenants y facturación SaaS.
- **Acceso:** [https://curriersite-production.up.railway.app/superadmin](https://curriersite-production.up.railway.app/superadmin)

## 2. Administrador de Tenant (LogiExpress Panama)
- **Email:** `admin@logiexpress.com`
- **Password:** `password`
- **Tenant:** LogiExpress Panama (`logiexpress.test`)
- **Uso:** Gestión de bodega, creación de facturas y configuración de la marca.
- **Acceso:** [https://curriersite-production.up.railway.app/dashboard](https://curriersite-production.up.railway.app/dashboard)

## 3. Cliente Final (Juan Perez)
- **Email:** `juan.perez_1@test.com`
- **Password:** `password`
- **Casillero:** `LOG-5000`
- **Uso:** Ver paquetes en tránsito, pre-alertar y probar portal del cliente.
- **Acceso:** [https://curriersite-production.up.railway.app/portal](https://curriersite-production.up.railway.app/portal)

---

## 🔗 Mapa de Rutas del Sistema (Producción)

### 🌐 Rutas Públicas (Sin Login)
- **Inicio:** [https://curriersite-production.up.railway.app/](https://curriersite-production.up.railway.app/)
- **Login:** [https://curriersite-production.up.railway.app/login](https://curriersite-production.up.railway.app/login)
- **Registro:** [https://curriersite-production.up.railway.app/register](https://curriersite-production.up.railway.app/register)
- **Rastreo Público:** [https://curriersite-production.up.railway.app/tracking](https://curriersite-production.up.railway.app/tracking)

### 📦 Módulo de Logística (Admin/Operador)
- **Recepción (Scanner):** [https://curriersite-production.up.railway.app/logistica/recepcion](https://curriersite-production.up.railway.app/logistica/recepcion)
- **Inventario:** [https://curriersite-production.up.railway.app/logistica/inventario](https://curriersite-production.up.railway.app/logistica/inventario)
- **Última Milla:** [https://curriersite-production.up.railway.app/logistica/ultima-milla](https://curriersite-production.up.railway.app/logistica/ultima-milla)
- **Despacho (Counter):** [https://curriersite-production.up.railway.app/logistica/despacho](https://curriersite-production.up.railway.app/logistica/despacho)

### 💳 Administración y White Label
- **Facturación:** [https://curriersite-production.up.railway.app/facturacion](https://curriersite-production.up.railway.app/facturacion)
- **Identidad Visual:** [https://curriersite-production.up.railway.app/builder/brand](https://curriersite-production.up.railway.app/builder/brand)
- **Usuarios y Roles:** [https://curriersite-production.up.railway.app/builder/usuarios](https://curriersite-production.up.railway.app/builder/usuarios)

---

## Datos Disponibles para Pruebas (LogiExpress)

### Bodegas (Warehouses)
- **Miami Hub (MIA-1):** Centro de recepción principal en USA.
- **Centro Local (LOC-1):** Centro de distribución local.

### Clientes Adicionales (LogiExpress)
- **Maria Lopez:** `maria.lopez_1@test.com` | Casillero: `LOG-5001`
- **Carlos Ruiz:** `carlos.ruiz_1@test.com` | Casillero: `LOG-5002` (Tiene factura vencida)

### Facturas Diversas
- `INV-OLD-1-0`: **VENCIDA** - Cliente: Juan Perez. (Usa para probar alertas de deuda).
- `INV-PAID-1-1`: **PAGADA** - Cliente: Maria Lopez.

---

## Otros Tenants para Pruebas
### Global Cargo China
- **Admin:** `admin@globalcargo.com` | **Pass:** `password`
- **Subdominio:** `globalcargo`
- **Color:** Rojo (`#dc2626`)

### SpeedShip USA
- **Admin:** `admin@speedship.com` | **Pass:** `password`
- **Subdominio:** `speedship`
- **Color:** Verde (`#16a34a`)
