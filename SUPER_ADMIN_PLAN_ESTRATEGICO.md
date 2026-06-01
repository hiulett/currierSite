# 🚀 Plan Estratégico: Super Admin LogiSaaS (Enfoque en Monitoreo Global)

Este documento redefine la sección de **Super Admin** como un Centro de Inteligencia Operativa. El objetivo es supervisar la salud del sistema basándose en los datos actuales (Versión Base), midiendo el rendimiento de los Tenants y el volumen total de carga.

---

## 📊 1. Dashboard de Control Maestro (Métricas en Tiempo Real)

El Dashboard principal se dividirá en 4 cuadrantes críticos para medir el ecosistema completo:

### A. Monitor de Carga Global (Logística)
*   **Total de Paquetes**: Conteo histórico y del mes en curso de toda la plataforma.
*   **Volumen por Estado**: Gráfico de pastel mostrando cuántos paquetes están en *Miami*, en *Tránsito*, o *Listos para Retiro* sumando todas las empresas.
*   **Kilos/Libras Totales**: Peso total procesado por el motor del sistema.

### B. Analítica de Usuarios (Crecimiento)
*   **Censo Global**: Total de Usuarios (`users`) vs Total de Perfiles Comerciales (`customers`).
*   **Usuarios Activos**: Usuarios que han iniciado sesión en los últimos 7 días.
*   **Usuarios Online**: Conteo de sesiones activas en los últimos 15 minutos (requiere tabla `sessions`).

### C. Pulso Financiero de los Tenants (Salud del Negocio)
*   **Facturación Consolidada**: Suma total de lo facturado por todos los tenants a sus clientes.
*   **Índice de Morosidad Global**: Cuánto dinero tienen pendiente de cobro los tenants (Facturas `unpaid`).
*   **Rendimiento por Empresa**: Ranking de los 5 tenants con mayor volumen de carga y facturación.

### D. Gestión de Ecosistema (Administración)
*   **Distribución de Planes**: Cuántas empresas están en Plan Básico, Pro o Enterprise.
*   **Estado de los Tenants**: Lista rápida de empresas con switch de activación inmediata.

---

## 🏗️ 2. Funcionalidades de Gestión (Basadas en la Versión Actual)

Para gestionar la base instalada sin introducir módulos externos complejos aún:

1.  **Impersonación "One-Click"**: Implementar la lógica de **Impersonación** para que el Super Admin pueda "entrar" a ver el dashboard de un Tenant específico tal cual como lo ve su administrador.
2.  **Sistema de Recordatorio de Pagos (Banners)**: 
    *   Banner de alerta en el dashboard del Tenant cuando su suscripción vence.
    *   Activación automática basada en fecha y desactivación manual/automática por el Super Admin.
3.  **Explorador de Paquetes Cross-Tenant**: Una tabla que permita al Super Admin buscar un número de tracking y ver instantáneamente a qué empresa pertenece y quién es el dueño.
4.  **Visor de Auditoría Financiera**: Capacidad de ver el listado de facturas de un Tenant específico desde el panel global para resolver disputas de soporte.

---

## 📋 3. Hoja de Ruta de Implementación (Priorizada)

| ID | Tarea | Prioridad | Estado |
|:---|:---|:---:|:---:|
| 1.1 | **Métricas de Carga**: Query global de paquetes y pesos por estado. | 🔥 Crítica | ✅ Finalizado |
| 1.2 | **Impersonación**: Botón "Login as" en la lista de Tenants. | 🔥 Crítica | ✅ Finalizado |
| 1.3 | **Cobranza Preventiva**: Banner de aviso de pago y lógica de fechas en Tenants. | 🟠 Alta | ✅ Finalizado |
| 2.1 | **Contador de Usuarios**: Lógica de usuarios online y activos (7 días). | 🟠 Alta | ✅ Finalizado |
| 2.2 | **Métricas Financieras**: Resumen de facturación total y saldos pendientes. | 🟠 Alta | ✅ Finalizado |
| 3.1 | **Mapa de Calor**: Gráfico de crecimiento de paquetes mes a mes (Global). | 🟡 Media | ✅ Finalizado |
| 3.2 | **Buscador Maestro**: Filtro de búsqueda por tracking en toda la DB. | 🟡 Media | ✅ Finalizado |

---

## 🎯 4. Definición de Éxito
- **Visibilidad 360°**: No tener que entrar a cada base de datos para saber cuántos paquetes se movieron hoy.
- **Detección Temprana**: Saber qué tenant no está facturando para ofrecerle capacitación o soporte.
- **Control Total**: Capacidad de suspender el acceso a una empresa con un solo click desde el panel global.

---
**¿Aprobado para iniciar con la Tarea 1.1 (Métricas de Carga Global) y 2.1 (Impersonación)?**
