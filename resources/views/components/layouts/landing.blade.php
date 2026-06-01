<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LogiSaaS') }} - Infraestructura Digital para Couriers</title>
    <meta name="description" content="LogiSaaS: La plataforma integral para la gestión de couriers, agencias de carga y logística de última milla. Automatiza recepciones y fideliza clientes.">
    <meta name="keywords" content="software courier, logistica, gestion de carga, erp logistica, fidelizacion clientes, panama logistics">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:title" content="LogiSaaS - Infraestructura Digital para Couriers">
    <meta property="og:description" content="Automatiza tu agencia de carga con la plataforma más moderna del mercado.">
    <meta property="og:image" content="{{ asset('img/og-image.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ config('app.url') }}">
    <meta property="twitter:title" content="LogiSaaS - Infraestructura Digital para Couriers">
    <meta property="twitter:description" content="Automatiza tu agencia de carga con la plataforma más moderna del mercado.">
    <meta property="twitter:image" content="{{ asset('img/og-image.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js is included in Livewire 3, but if needed for landing without livewire components: -->
    @livewireStyles
</head>
<body class="font-sans antialiased bg-white text-slate-900">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav x-data="{ open: false }" class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <a href="/" class="flex-shrink-0 flex items-center gap-2">
                            <span class="text-2xl font-extrabold tracking-tight text-blue-600">Logi<span class="text-slate-900">SaaS</span></span>
                        </a>
                        <div class="hidden md:ml-10 md:flex md:space-x-8">
                            <a href="#features" class="text-slate-600 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors">Características</a>
                            <a href="#pricing" class="text-slate-600 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors">Precios</a>
                            <a href="{{ route('public.calculator') }}" class="text-slate-600 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors">Calculadora</a>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-slate-600 hover:text-slate-900 px-3 py-2 text-sm font-medium transition-colors">Iniciar Sesión</a>
                        <a href="#contact" class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-semibold rounded-full text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all transform hover:-translate-y-0.5">
                            Solicitar Demo
                        </a>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex items-center md:hidden">
                        <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="open" x-cloak class="md:hidden bg-white border-b border-slate-100">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="#features" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Características</a>
                    <a href="#pricing" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Precios</a>
                    <a href="{{ route('public.calculator') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Calculadora</a>
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Iniciar Sesión</a>
                    <a href="#contact" class="block px-3 py-2 rounded-md text-base font-medium text-blue-600 font-bold">Solicitar Demo</a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-slate-50 border-t border-slate-200 pt-16 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                    <div class="col-span-1 md:col-span-1">
                        <span class="text-xl font-extrabold tracking-tight text-blue-600">Logi<span class="text-slate-900">SaaS</span></span>
                        <p class="mt-4 text-slate-500 text-sm leading-relaxed">
                            La plataforma integral para la gestión de couriers, agencias de carga y logística de última milla.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Producto</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-slate-500 hover:text-blue-600 text-sm transition-colors">Características</a></li>
                            <li><a href="#" class="text-slate-500 hover:text-blue-600 text-sm transition-colors">Precios</a></li>
                            <li><a href="#" class="text-slate-500 hover:text-blue-600 text-sm transition-colors">Documentación</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Empresa</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-slate-500 hover:text-blue-600 text-sm transition-colors">Sobre nosotros</a></li>
                            <li><a href="#" class="text-slate-500 hover:text-blue-600 text-sm transition-colors">Contacto</a></li>
                            <li><a href="#" class="text-slate-500 hover:text-blue-600 text-sm transition-colors">Blog</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Legal</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-slate-500 hover:text-blue-600 text-sm transition-colors">Términos</a></li>
                            <li><a href="#" class="text-slate-500 hover:text-blue-600 text-sm transition-colors">Privacidad</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-slate-200 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-slate-400 text-xs text-center md:text-left">
                        &copy; {{ date('Y') }} LogiSaaS. Todos los derechos reservados.
                    </p>
                    <div class="flex space-x-6">
                        <!-- Social Icons -->
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
</body>
</html>
