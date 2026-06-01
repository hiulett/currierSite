# 🌐 Plan de Implementación: Sitio Web Corporativo LogiSaaS

Este documento detalla el plan para crear una presencia web de clase mundial para promocionar **LogiSaaS** como el software líder para empresas de Courier y Logística.

## 🎯 1. Objetivo del Sitio
Convertir visitantes en clientes potenciales (Leads) mediante una narrativa visual moderna que proyecte robustez, innovación y escalabilidad.

---

## 🎨 2. Concepto Visual y Experiencia (UX/UI)
*   **Estilo**: "SaaS Moderno" (Inspirado en Stripe/Linear). Limpio, tipografía bold, uso de espacios en blanco y degradados sutiles.
*   **Efecto Parallax**: Uso de profundidad visual mediante capas que se mueven a distintas velocidades al hacer scroll, creando una experiencia inmersiva y de alta tecnología.
*   **Interactividad**: Animaciones suaves al hacer scroll, previsualizaciones de la app en marcos de dispositivos (Mockups).
*   **Independencia**: Aunque vivirá en el mismo repositorio para facilitar el despliegue, tendrá un diseño 100% distinto al dashboard operativo, usando una arquitectura de vistas separada.

---

## 🏗️ 3. Estructura de Contenido (Secciones)

### A. Hero Section (El Primer Impacto)
*   Título potente: "La infraestructura digital para tu Courier del futuro."
*   Subtítulo: "Automatiza recepciones, fideliza clientes y gestiona tus finanzas en una sola plataforma Cloud."
*   Botones de Acción: "Ver Planes" y "Solicitar Demo".

### B. Módulos Core (Lo que vendemos)
1.  **Recepción Inteligente**: Scanner y OCR para procesar carga en segundos.
2.  **Fidelización 2.0**: Sistema de puntos y niveles para retener clientes.
3.  **Gobernanza Total**: Panel administrativo para control de sucursales y empleados.
4.  **Pagos Automatizados**: Integración con Yappy, PayPal y Stripe.

### C. Beneficios de Negocio
*   **Reducción de Errores**: Menos pérdida de paquetes por ingreso manual.
*   **Marca Blanca**: La app lleva el logo y colores del cliente.
*   **Soporte Multi-sucursal**: Miami y múltiples hubs en Panamá.

### D. Tabla de Precios (Monetización)
Visualización profesional de los planes: **Startup**, **Business** y **Enterprise**.

---

## 🛠️ 4. Hoja de Ruta Técnica

1.  **Estructura Base**: Crear `LandingController` y layout `landing.blade.php` con fuentes premium (Inter/Geist).
2.  **Motor Parallax & Animaciones**: Implementar librerías ligeras de movimiento (como GSAP o Locomotive Scroll) para los efectos de profundidad y revelado de secciones.
3.  **Desarrollo Hero & Features**: Implementar las secciones de ventas con Tailwind CSS y capas parallax.
4.  **Simulador de Ganancias (Opcional)**: Una pequeña herramienta donde el prospecto pone cuántos paquetes mueve y ve cuánto tiempo ahorraría.
5.  **Formulario de Contacto**: Integrado para que los mensajes lleguen al Super Admin.

---

## 📋 5. Entrega
*   El código se subirá a una rama limpia o carpeta dedicada.
*   Se configurará la ruta `/` (raíz) para que sea la cara pública de tu negocio.

---
**¿Aprobado para iniciar con el prototipo de la Hero Section y el Layout Corporativo?**
