# Análisis Profundo: Implementación Multi-Tenant LogiSaaS V2

Este documento detalla la arquitectura técnica para implementar un sistema Multi-Tenant robusto, con marca blanca y aislamiento de datos completo, garantizando compatibilidad total con **Railway.app**.

---

## 1. Fase 1: Resolución de Tenant (Entry Points & Middleware)
**Objetivo:** Capturar la identidad de la agencia desde links externos y mantenerla.

*   **Rutas de Entrada (Para botones en webs de agencias):**
    *   `GET /join/{slug}`: Identifica agencia -> Guarda en Session/Cookie -> Redirige a `/register`.
    *   `GET /access/{slug}`: Identifica agencia -> Guarda en Session/Cookie -> Redirige a `/login`.
*   **Middleware `IdentifyTenant`:**
    1. Busca `slug` en la URL actual (si es ruta de entrada).
    2. Busca `tenant_id` en Session.
    3. Busca `tenant_id` en Cookie (Fallback para cuando la sesión expira).
    4. Detecta Subdominio (Modo White Label Pro).
*   **Persistencia de Contexto:** 
    *   Usaremos una cookie `tenant_branding_id` para que el logo de la agencia se mantenga visible incluso si el usuario no ha iniciado sesión aún.

## 2. Fase 2: Compatibilidad con Railway (Networking & Proxy)
Railway utiliza un balanceador de carga que actúa como proxy. Para que Laravel detecte correctamente los subdominios y el protocolo HTTPS:

*   **Trusted Proxies:**
    *   En `bootstrap/app.php`, debemos asegurar que `$middleware->trustProxies(at: '*')` esté activo (Laravel 11+ lo trae por defecto). Esto permite que `request()->getHost()` devuelva el subdominio real y no la IP interna de Railway.
*   **SSL Wildcard:**
    *   Railway gestiona certificados SSL automáticamente para `*.logisaas.com`. 
    *   **Nota Técnica:** Si el dominio usa Cloudflare, el registro DNS `_acme-challenge` debe estar en modo "DNS Only" para que Railway pueda renovar el certificado.

## 3. Fase 3: Marca Blanca y Personalización (Multi-branding)
**Objetivo:** Dinamismo total sin duplicar archivos, integrando el logo desde Cloudflare R2.

*   **View Composer Global:**
    *   Inyectaremos las variables `$currentTenant`, `$logoUrl` y `$brandColors` en todas las vistas de `auth.*` y `layouts.*`.
*   **Logo Dinámico (Cloudflare R2):**
    *   **Fuente de verdad:** `tenants.theme_config_json->logo_url`.
    *   El middleware, tras identificar al tenant, extraerá esta URL.
    *   Si una agencia no tiene logo, el sistema hará fallback al logo por defecto de LogiSaaS.
*   **Estilos por Agencia:**
    *   Inyectaremos un bloque `<style>` en el HTML con las variables CSS del tenant (`--primary-color`, etc.) extraídas de su configuración JSON.

## 4. Fase 4: Aislamiento de Datos (Global Scopes)
**Objetivo:** Seguridad total a nivel de Base de Datos.

*   **Trait `BelongsToTenant` con Protección de Bucle:**
    *   El filtro usará `session('tenant_id')` como fuente de verdad.
    *   **Evitar Recursión:** Nunca llamaremos a `auth()->user()` dentro del Scope. En su lugar, el Middleware se encargará de "aplanar" el ID del tenant en la sesión al inicio del ciclo de vida de la petición.
    *   **Bypass SuperAdmin:** Los administradores globales tendrán una bandera `is_root` en sesión para ver la data de todos los tenants.

## 5. Sistema de Correo Dinámico
*   **Switcher "On-the-Fly":**
    *   Implementaremos un ServiceProvider que intercepta el envío de correos.
    *   Si `tenants.settings_json` tiene credenciales SMTP/SendGrid, se sobreescribe la configuración de `mail.mailers.smtp` solo para esa ejecución.

---

## Plan de Ejecución Inmediato

### Paso 1: Configuración de Entorno (Railway Ready)
1.  Actualizar `.env` con `SESSION_DOMAIN=.localhost` (en local) y `.logisaas.com` (en Railway).
2.  Verificar `bootstrap/app.php` para asegurar el manejo de proxies.

### Paso 2: Middleware de Identificación Pro
Implementar `IdentifyTenant.php` con la siguiente jerarquía de búsqueda:
1. `session('impersonate_tenant_id')` (Modo Dios).
2. Host/Subdominio real.
3. `session('tenant_id')` previo.

### Paso 3: Trait de Seguridad
Reescribir `BelongsToTenant.php` usando un flag estático `isResolving` para garantizar que no haya fugas de memoria.

---

**¿Confirmas este análisis para proceder con la implementación técnica del Paso 1?**
