<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | Automatiza tu Courier en 48 Horas</title>
    <meta name="description" content="Automatización logística con OCR, IA y programas de fidelización para couriers modernos.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- GSAP for Parallax -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <!-- Tailwind CDN with custom config (Restored for immediate results) -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "surface-container-highest": "#d5e3fc",
                    "on-tertiary-container": "#d95f00",
                    "on-error": "#ffffff",
                    "tertiary-fixed": "#ffdbca",
                    "on-surface": "#0d1c2e",
                    "on-secondary-fixed": "#00174b",
                    "outline-variant": "#c6c6cd",
                    "inverse-on-surface": "#eaf1ff",
                    "on-secondary-container": "#fefcff",
                    "surface-container-lowest": "#ffffff",
                    "secondary-fixed-dim": "#b4c5ff",
                    "on-primary-fixed-variant": "#3f465c",
                    "on-primary-fixed": "#131b2e",
                    "primary-fixed-dim": "#bec6e0",
                    "surface-dim": "#ccdbf3",
                    "secondary-container": "#316bf3",
                    "surface-tint": "#565e74",
                    "on-tertiary-fixed": "#341100",
                    "surface-container-low": "#eff4ff",
                    "on-surface-variant": "#45464d",
                    "on-secondary": "#ffffff",
                    "surface-base": "#FFFFFF",
                    "on-secondary-fixed-variant": "#003ea8",
                    "data-teal": "#0D9488",
                    "on-background": "#0d1c2e",
                    "primary": "#000000",
                    "on-tertiary": "#ffffff",
                    "primary-container": "#131b2e",
                    "tertiary": "#000000",
                    "on-primary-container": "#7c839b",
                    "surface-container": "#e6eeff",
                    "surface-container-high": "#dce9ff",
                    "surface-bright": "#f8f9ff",
                    "error-container": "#ffdad6",
                    "inverse-primary": "#bec6e0",
                    "surface": "#f8f9ff",
                    "secondary": "#0051d5",
                    "primary-fixed": "#dae2fd",
                    "border-light": "#E2E8F0",
                    "inverse-surface": "#233144",
                    "outline": "#76777d",
                    "surface-variant": "#d5e3fc",
                    "tertiary-fixed-dim": "#ffb690",
                    "on-error-container": "#93000a",
                    "on-tertiary-fixed-variant": "#783200",
                    "background": "#f8f9ff",
                    "surface-subtle": "#F8FAFC",
                    "on-primary": "#ffffff",
                    "secondary-fixed": "#dbe1ff",
                    "tertiary-container": "#341100",
                    "error": "#ba1a1a",
                    "cta-orange": "#F97316"
            },
            "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
            },
            "spacing": {
                    "margin-mobile": "16px",
                    "container-max": "1280px",
                    "base": "8px",
                    "margin-desktop": "40px",
                    "gutter": "24px",
                    "stack-md": "24px",
                    "stack-sm": "12px",
                    "stack-lg": "48px"
            },
            "fontFamily": {
                    "body-sm": ["Inter"],
                    "body-lg": ["Inter"],
                    "label-md": ["Inter"],
                    "headline-sm": ["Plus Jakarta Sans"],
                    "title-lg": ["Inter"],
                    "headline-md": ["Plus Jakarta Sans"],
                    "label-sm": ["Inter"],
                    "headline-lg": ["Plus Jakarta Sans"],
                    "headline-lg-mobile": ["Plus Jakarta Sans"],
                    "body-md": ["Inter"]
            },
            "fontSize": {
                    "headline-lg": ["3.5rem", { "lineHeight": "1.2", "fontWeight": "800" }],
                    "headline-md": ["2.5rem", { "lineHeight": "1.2", "fontWeight": "700" }],
                    "headline-sm": ["1.5rem", { "lineHeight": "1.2", "fontWeight": "700" }],
                    "title-lg": ["1.25rem", { "lineHeight": "1.5", "fontWeight": "600" }],
                    "label-md": ["0.875rem", { "lineHeight": "1.25", "fontWeight": "600" }],
                    "label-sm": ["0.75rem", { "lineHeight": "1rem", "fontWeight": "600" }],
                    "body-lg": ["1.125rem", { "lineHeight": "1.75" }],
                    "body-md": ["1rem", { "lineHeight": "1.5" }],
                    "body-sm": ["0.875rem", { "lineHeight": "1.25" }]
            }
          }
        }
      }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.5);
        }
        .zebra-table tr:nth-child(even) {
            background-color: #F8FAFC;
        }
        .hero-gradient {
            background: linear-gradient(135deg, #f8f9ff 0%, #e6eeff 100%);
        }
        .bento-inner {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .bento-card:hover .bento-inner {
            transform: translateY(-8px);
        }
        [x-cloak] { display: none !important; }
    </style>

    @livewireStyles
</head>
<body class="bg-background text-on-surface font-body-md antialiased">
    <!-- TopNavBar -->
    <header class="w-full sticky top-0 z-50 bg-surface-base border-b border-border-light shadow-sm">
        <nav x-data="{ open: false }" class="max-w-container-max mx-auto px-margin-desktop flex items-center justify-between h-20">
            <div class="flex items-center gap-12">
                <a class="font-headline-sm text-headline-sm font-bold text-on-surface" href="/">LogySaaS</a>
                <div class="hidden md:flex items-center gap-gutter">
                    <a class="font-label-md text-label-md text-on-surface-variant hover:text-secondary transition-colors duration-200" href="#features">Features</a>
                    <a class="font-label-md text-label-md text-on-surface-variant hover:text-secondary transition-colors duration-200" href="#solutions">Solutions</a>
                    <a class="font-label-md text-label-md text-on-surface-variant hover:text-secondary transition-colors duration-200" href="#pricing">Pricing</a>
                    <a class="font-label-md text-label-md text-on-surface-variant hover:text-secondary transition-colors duration-200" href="#roi">ROI Calculator</a>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="hidden lg:block font-label-md text-label-md text-on-surface-variant px-4 py-2 hover:text-secondary transition-colors">Acceso</a>
                <a href="#contact" class="bg-cta-orange text-white font-label-md text-label-md px-6 py-2.5 rounded-lg hover:opacity-90 transition-opacity">Get Started</a>

                <!-- Mobile menu button -->
                <button @click="open = !open" class="md:hidden text-on-surface">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>

            <!-- Mobile menu -->
            <div x-show="open" @click.away="open = false" x-cloak class="absolute top-20 left-0 w-full bg-white border-b border-border-light p-4 md:hidden shadow-lg">
                <div class="flex flex-col gap-4">
                    <a class="font-label-md text-label-md text-on-surface-variant" href="#features">Features</a>
                    <a class="font-label-md text-label-md text-on-surface-variant" href="#solutions">Solutions</a>
                    <a class="font-label-md text-label-md text-on-surface-variant" href="#pricing">Pricing</a>
                    <a class="font-label-md text-label-md text-on-surface-variant" href="#roi">ROI Calculator</a>
                    <a class="bg-cta-orange text-white text-center font-label-md text-label-md px-6 py-2.5 rounded-lg" href="#contact">Get Started</a>
                </div>
            </div>
        </nav>
    </header>

    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-surface-container py-stack-lg border-t border-border-light">
        <div class="max-w-container-max mx-auto px-margin-desktop grid grid-cols-1 md:grid-cols-4 gap-gutter mb-12">
            <div class="col-span-1 md:col-span-1">
                <a class="font-headline-sm text-headline-sm font-bold text-on-surface mb-4 block" href="#">LogySaaS</a>
                <p class="font-body-sm text-body-sm text-on-surface-variant mb-6">Empoderamos a las empresas de logística con tecnología de punta. Desde la recepción en Miami hasta la entrega final.</p>
                <div class="flex gap-4">
                    <span class="font-label-sm text-label-sm bg-white px-2 py-1 rounded border border-border-light">SSL 256-bit</span>
                    <span class="font-label-sm text-label-sm bg-white px-2 py-1 rounded border border-border-light">AWS Cloud</span>
                </div>
            </div>
            <div>
                <h5 class="font-label-md text-label-md text-on-surface mb-6 uppercase tracking-wider">Producto</h5>
                <ul class="space-y-4 font-body-sm text-body-sm text-on-surface-variant">
                    <li><a class="hover:text-secondary transition-colors" href="#">Características</a></li>
                    <li><a class="hover:text-secondary transition-colors" href="#">Soluciones</a></li>
                    <li><a class="hover:text-secondary transition-colors" href="#">Planes</a></li>
                    <li><a class="hover:text-secondary transition-colors" href="#">Integraciones</a></li>
                </ul>
            </div>
            <div>
                <h5 class="font-label-md text-label-md text-on-surface mb-6 uppercase tracking-wider">Recursos</h5>
                <ul class="space-y-4 font-body-sm text-body-sm text-on-surface-variant">
                    <li><a class="hover:text-secondary transition-colors" href="#">Ayuda / FAQ</a></li>
                    <li><a class="hover:text-secondary transition-colors" href="#">Documentación</a></li>
                    <li><a class="hover:text-secondary transition-colors" href="#">Blog Logístico</a></li>
                    <li><a class="hover:text-secondary transition-colors" href="#">API Status</a></li>
                </ul>
            </div>
            <div>
                <h5 class="font-label-md text-label-md text-on-surface mb-6 uppercase tracking-wider">Boletín</h5>
                <p class="font-body-sm text-body-sm text-on-surface-variant mb-4">Recibe tendencias logísticas y actualizaciones.</p>
                <form class="flex gap-2">
                    <input class="bg-white border-border-light rounded-lg px-4 py-2 text-sm focus:ring-secondary focus:border-secondary flex-grow outline-none" placeholder="Tu correo" type="email"/>
                    <button class="bg-secondary text-white px-4 py-2 rounded-lg font-label-md text-label-md hover:opacity-90">OK</button>
                </form>
            </div>
        </div>
        <div class="max-w-container-max mx-auto px-margin-desktop pt-8 border-t border-border-light flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="font-body-sm text-body-sm text-on-surface-variant">© {{ date('Y') }} LogySaaS. All rights reserved. Precision in Motion. 🇵🇦 Hecho en Panamá.</p>
            <div class="flex gap-6 font-body-sm text-body-sm text-on-surface-variant">
                <a class="hover:text-secondary underline transition-all" href="#">Privacy Policy</a>
                <a class="hover:text-secondary underline transition-all" href="#">Terms of Service</a>
                <a class="hover:text-secondary underline transition-all" href="#">Cookie Policy</a>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
