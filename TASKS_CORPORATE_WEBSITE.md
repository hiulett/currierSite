# 📝 Lista de Tareas: Sitio Web Corporativo LogiSaaS

Este archivo detalla las tareas necesarias para implementar la presencia web corporativa de LogiSaaS, siguiendo el plan maestro de marketing y diseño.

## 🏗️ Fase 1: Cimientos y Estructura Base
- [x] **Configuración de Rutas**: Definir la ruta raíz `/` para que apunte al `LandingController`.
- [x] **Creación del Controlador**: Implementar `LandingController` para gestionar las vistas públicas.
- [x] **Layout Corporativo**: Crear `resources/views/layouts/landing.blade.php`.
    - [x] Importar fuentes premium (Inter/Geist).
    - [x] Configurar Navbar global (Logo, Features, Pricing, Login, CTA).
    - [x] Configurar Footer corporativo.
- [ ] **Assets**: Configurar Tailwind CSS para las variantes de color corporativas (Azules profundos, degradados).

## 🎨 Fase 2: Hero Section & Experiencia Visual
- [x] **Implementación Hero Section**:
    - [x] Título con tipografía bold y gradientes.
    - [x] Subtítulo persuasivo.
    - [x] Botones de acción (Primary: Ver Planes, Secondary: Solicitar Demo).
- [ ] **Efectos Parallax**: Configurar GSAP o una librería similar para los efectos de profundidad en el Hero.
- [ ] **Mockups de Producto**: Integrar imágenes/svgs que representen el dashboard del ERP en marcos de dispositivos móviles y desktop.

## 📦 Fase 3: Secciones de Venta (Content)
- [x] **Sección de Módulos Core**: Tarjetas interactivas para:
    - [x] Recepción Inteligente (Icono + Descripción).
    - [x] Fidelización 2.0 (Icono + Descripción).
    - [x] Gobernanza Total (Icono + Descripción).
    - [x] Pagos Automatizados (Icono + Descripción).
- [x] **Sección de Beneficios**: Diseño limpio destacando "Reducción de Errores", "Marca Blanca" y "Multi-sucursal".
- [x] **Tabla de Precios**:
    - [x] Card para Plan Startup.
    - [x] Card para Plan Business (Destacado).
    - [x] Card para Plan Enterprise.

## ⚡ Fase 4: Interactividad y Conversión
- [x] **Animaciones de Scroll**: Implementar revelado de secciones mediante scroll (fade-in, slide-up).
- [x] **Formulario de Contacto (Lead Gen)**:
    - [x] Crear componente Livewire para el formulario.
    - [x] Implementar envío de correos o guardado en BD para el Super Admin.
- [x] **Optimización SEO**: Configurar Meta tags, OpenGraph y Sitemap básico.

## 🚀 Fase 5: Ajustes Finales y Despliegue
- [x] **Responsive Design**: Asegurar que los efectos parallax y las tablas se vean perfectas en móviles.
- [x] **Pruebas de Carga**: Optimizar imágenes y scripts para una carga rápida.
- [x] **QA Final**: Revisión de links, ortografía y funcionamiento del formulario.

---
**Notas Adicionales:**
- Mantener el diseño independiente del dashboard de los clientes/admins.
- Priorizar la velocidad de carga (Lighthouse score > 90).
