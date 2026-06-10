<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LogiSaaS') }} - Software para Couriers | Automatiza tu Logística</title>
    <meta name="description" content="LogiSaaS: Software SaaS integral para couriers y casilleros virtuales. Automatiza recepciones con OCR, fideliza clientes con puntos, e integra Stripe/PayPal. Setup en 48 horas.">
    <meta name="keywords" content="software courier, logistica, gestion de carga, erp logistica, casilleros virtuales, courier panama, software logistica, automatizar courier">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:title" content="LogiSaaS - Automatiza tu Courier en 48 Horas">
    <meta property="og:description" content="Deja de perder paquetes y clientes. Software integral para couriers con OCR, fidelización y pagos integrados. Desde $30/mes.">
    <meta property="og:image" content="{{ asset('img/og-image.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ config('app.url') }}">
    <meta property="twitter:title" content="LogiSaaS - Automatiza tu Courier en 48 Horas">
    <meta property="twitter:description" content="Deja de perder paquetes y clientes. Software integral para couriers con OCR, fidelización y pagos integrados. Desde $30/mes.">
    <meta property="twitter:image" content="{{ asset('img/og-image.jpg') }}">

    <!-- Schema.org Structured Data -->
    @verbatim
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "LogiSaaS",
        "applicationCategory": "BusinessApplication",
        "operatingSystem": "Web",
        "description": "Plataforma SaaS integral para la gestión de couriers, agencias de carga y logística de última milla.",
        "offers": {
            "@type": "AggregateOffer",
            "lowPrice": "30",
            "highPrice": "55",
            "priceCurrency": "USD",
            "offerCount": "3"
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.9",
            "ratingCount": "127"
        }
    }
    </script>
    @endverbatim

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <!-- Alpine Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js is included in Livewire 3 -->
    @livewireStyles
</head>
<body class="font-sans antialiased bg-white text-slate-900">
    <div class="min-h-screen" style="font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
        <!-- Navigation -->
        <nav x-data="{ open: false, scrolled: false }"
             x-on:scroll.window="scrolled = (window.pageYOffset > 20)"
             :class="scrolled ? 'bg-slate-900/80 backdrop-blur-2xl border-white/5 py-3 shadow-2xl' : 'bg-transparent py-6'"
             class="fixed w-full z-50 transition-all duration-500 border-b border-transparent">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-16">
                        <a href="/" class="group flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:rotate-12 transition-transform duration-300">
                                <i class="fas fa-box-open text-white text-lg"></i>
                            </div>
                            <span class="text-2xl font-black tracking-tighter text-white uppercase italic">
                                Logi<span class="text-blue-500">SaaS</span>
                            </span>
                        </a>
                        <div class="hidden lg:flex items-center space-x-2">
                            <a href="#features" class="text-[#94a3b8] hover:text-white px-4 py-2 text-[11px] font-black uppercase tracking-widest transition-all rounded-full hover:bg-white/5">Características</a>
                            <a href="#roi" class="text-[#94a3b8] hover:text-white px-4 py-2 text-[11px] font-black uppercase tracking-widest transition-all rounded-full hover:bg-white/5">ROI</a>
                            <a href="#pricing" class="text-[#94a3b8] hover:text-white px-4 py-2 text-[11px] font-black uppercase tracking-widest transition-all rounded-full hover:bg-white/5">Precios</a>
                        </div>
                    </div>

                    <div class="hidden lg:flex items-center gap-6">
                        <a href="{{ route('login') }}" class="text-[#94a3b8] hover:text-white text-[11px] font-black uppercase tracking-widest transition-colors">
                            ACCESO
                        </a>
                        <a href="#contact" class="inline-flex items-center justify-center px-8 py-3 bg-white text-slate-900 text-[11px] font-black uppercase tracking-widest rounded-full hover:bg-[#3b7ddd] hover:text-white transition-all transform hover:-translate-y-0.5 active:scale-95 shadow-xl">
                            COMENZAR
                        </a>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex items-center lg:hidden">
                        <button x-on:click="open = !open" :class="scrolled ? 'text-slate-600' : 'text-white'" class="inline-flex items-center justify-center p-2 rounded-xl hover:bg-white/10 transition-colors">
                            <i class="fas fa-bars text-xl" x-show="!open"></i>
                            <i class="fas fa-times text-xl" x-show="open" x-cloak></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-cloak
                 class="lg:hidden bg-white border-b border-slate-100 absolute w-full shadow-2xl">
                <div class="px-4 pt-4 pb-8 space-y-2">
                    <a href="#features" x-on:click="open = false" class="block px-4 py-3 rounded-xl text-base font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-all">Características</a>
                    <a href="#solutions" x-on:click="open = false" class="block px-4 py-3 rounded-xl text-base font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-all">Soluciones</a>
                    <a href="#roi" x-on:click="open = false" class="block px-4 py-3 rounded-xl text-base font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-all">Calculadora ROI</a>
                    <a href="#pricing" x-on:click="open = false" class="block px-4 py-3 rounded-xl text-base font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-all">Precios</a>
                    <a href="#faq" x-on:click="open = false" class="block px-4 py-3 rounded-xl text-base font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-all">FAQ</a>
                    <div class="pt-4 flex flex-col gap-3">
                        <a href="{{ route('login') }}" class="flex items-center justify-center px-4 py-3 rounded-xl text-base font-bold text-slate-700 border border-slate-200">Iniciar Sesión</a>
                        <a href="#contact" x-on:click="open = false" class="flex items-center justify-center px-4 py-3 rounded-xl text-base font-bold text-white bg-blue-600 shadow-lg shadow-blue-200">Prueba 30 Días Gratis</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Sticky CTA Bar — Appears after scrolling past hero -->
        <div x-data="{ show: false, atContact: false }"
             x-on:scroll.window="
                show = window.pageYOffset > 800;
                const contactEl = document.getElementById('contact');
                if (contactEl) {
                    const rect = contactEl.getBoundingClientRect();
                    atContact = rect.top < window.innerHeight && rect.bottom > 0;
                }
             "
             x-show="show && !atContact"
             x-transition:enter="transition ease-out duration-400"
             x-transition:enter-start="translate-y-full opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-full opacity-0"
             x-cloak
             class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-xl border-t border-slate-200 shadow-[0_-4px_20px_rgba(0,0,0,0.08)] py-3 px-4">
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
                <div class="hidden sm:block">
                    <p class="text-sm font-bold text-slate-900">¿Listo para automatizar tu courier?</p>
                    <p class="text-xs text-slate-500">30 días gratis · Sin tarjeta de crédito · Cancela cuando quieras</p>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="#pricing" class="text-sm font-bold text-slate-600 hover:text-blue-600 transition-colors hidden sm:block">Ver precios</a>
                    <a href="#contact" class="cta-shine flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-3 bg-blue-600 text-white text-sm font-bold rounded-full hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">
                        Empezar Gratis <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-slate-900 text-white pt-24 pb-12 border-t border-white/10 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-16 mb-20">
                    <div class="md:col-span-4">
                        <a href="/" class="text-3xl font-black tracking-tighter text-white mb-8 block">Logi<span class="text-blue-500">SaaS</span></a>
                        <p class="text-slate-400 text-lg leading-relaxed mb-8">
                            Empoderamos a las empresas de logística con tecnología de punta. Desde la recepción en Miami hasta la entrega final, LogiSaaS es tu socio estratégico.
                        </p>

                        <!-- Trust Badges -->
                        <div class="flex flex-wrap gap-3 mb-8">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-400 bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                                <i class="fas fa-lock text-green-400"></i> SSL 256-bit
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-400 bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                                <i class="fas fa-cloud text-blue-400"></i> AWS Cloud
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-400 bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                                🇵🇦 Hecho en Panamá
                            </span>
                        </div>

                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center hover:bg-blue-600 transition-colors"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center hover:bg-blue-600 transition-colors"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center hover:bg-blue-600 transition-colors"><i class="fab fa-x-twitter"></i></a>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-black uppercase tracking-[0.2em] text-blue-500 mb-8">Producto</h3>
                        <ul class="space-y-4 text-slate-400">
                            <li><a href="#features" class="hover:text-white transition-colors">Características</a></li>
                            <li><a href="#solutions" class="hover:text-white transition-colors">Soluciones</a></li>
                            <li><a href="#pricing" class="hover:text-white transition-colors">Planes</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Integraciones</a></li>
                        </ul>
                    </div>
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-black uppercase tracking-[0.2em] text-blue-500 mb-8">Recursos</h3>
                        <ul class="space-y-4 text-slate-400">
                            <li><a href="#faq" class="hover:text-white transition-colors">Ayuda / FAQ</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Documentación</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Blog Logístico</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">API Status</a></li>
                        </ul>
                    </div>
                    <div class="md:col-span-4">
                        <h3 class="text-sm font-black uppercase tracking-[0.2em] text-blue-500 mb-8">Boletín</h3>
                        <p class="text-slate-400 mb-6 italic">Suscríbete para recibir tendencias logísticas y actualizaciones del sistema.</p>
                        <form class="flex gap-2">
                            <input type="email" placeholder="tu@email.com" class="flex-grow bg-white/5 border border-white/10 rounded-full px-6 py-3 text-white focus:outline-none focus:border-blue-500 transition-all">
                            <button class="bg-white text-slate-900 font-bold rounded-full px-6 py-3 hover:bg-blue-500 hover:text-white transition-all">OK</button>
                        </form>
                    </div>
                </div>
                <div class="border-t border-white/5 pt-12 flex flex-col md:flex-row justify-between items-center gap-6">
                    <p class="text-slate-500 text-sm">
                        &copy; {{ date('Y') }} LogiSaaS Global. Todos los derechos reservados.
                    </p>
                    <div class="flex space-x-8 text-slate-500 text-sm font-medium">
                        <a href="#" class="hover:text-white transition-colors">Privacidad</a>
                        <a href="#" class="hover:text-white transition-colors">Términos</a>
                        <a href="#" class="hover:text-white transition-colors">Cookies</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>
</html>
