<x-layouts.landing>
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-white pt-32 pb-20 lg:pt-48 lg:pb-32">
        <!-- Background Elements (Parallax ready) -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full pointer-events-none overflow-hidden z-0">
            <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-blue-50 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-[600px] h-[600px] bg-indigo-50 rounded-full blur-3xl opacity-50"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h1 class="hero-title text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 mb-6">
                    La infraestructura digital para tu <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Courier del futuro</span>
                </h1>
                <p class="hero-subtitle max-w-2xl mx-auto text-lg md:text-xl text-slate-600 mb-10 leading-relaxed">
                    Automatiza recepciones, fideliza clientes y gestiona tus finanzas en una sola plataforma Cloud diseñada para escalar tu negocio logístico.
                </p>
                <div class="hero-buttons flex flex-col sm:flex-row justify-center gap-4">
                    <a href="#pricing" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-bold rounded-full text-white bg-blue-600 hover:bg-blue-700 shadow-xl shadow-blue-200 transition-all transform hover:-translate-y-1">
                        Ver Planes
                    </a>
                    <a href="#contact" class="inline-flex items-center justify-center px-8 py-4 border border-slate-200 text-lg font-bold rounded-full text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-all transform hover:-translate-y-1">
                        Solicitar Demo
                    </a>
                </div>

                <!-- Product Preview Mockup -->
                <div class="hero-mockup mt-20 relative">
                    <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent z-10 h-full"></div>
                    <div class="bg-slate-900 rounded-2xl p-2 shadow-2xl shadow-blue-900/20 transform perspective-1000 rotate-x-5">
                        <div class="bg-slate-800 rounded-xl overflow-hidden border border-slate-700">
                            <div class="h-8 bg-slate-800 border-b border-slate-700 flex items-center px-4 gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-500/20"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-yellow-500/20"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-green-500/20"></div>
                            </div>
                            <img src="https://images.unsplash.com/photo-1551288049-bbbda546697a?auto=format&fit=crop&q=80&w=2000" alt="Dashboard Preview" class="w-full opacity-90 group-hover:opacity-100 transition-opacity">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal">
                <h2 class="text-base font-bold text-blue-600 uppercase tracking-widest mb-2">Potencia tu operación</h2>
                <p class="text-3xl md:text-4xl font-extrabold text-slate-900">Todo lo que necesitas para dominar el mercado</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Recepción Inteligente</h3>
                    <p class="text-slate-600 leading-relaxed">Procesa cientos de paquetes en minutos con nuestro motor OCR y escaneo de alta velocidad.</p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Fidelización 2.0</h3>
                    <p class="text-slate-600 leading-relaxed">Retén a tus clientes con un sistema de puntos y niveles gamificado que los motiva a mover más carga.</p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center text-purple-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.040M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Gobernanza Total</h3>
                    <p class="text-slate-600 leading-relaxed">Controla finanzas, sucursales y empleados desde un panel centralizado con reportes en tiempo real.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Section -->
    <div id="pricing" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal">
                <h2 class="text-base font-bold text-blue-600 uppercase tracking-widest mb-2">Planes y Precios</h2>
                <p class="text-3xl md:text-4xl font-extrabold text-slate-900">Escala a tu propio ritmo</p>
                <p class="mt-4 text-slate-600">Sin costos ocultos. Cancela cuando quieras.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Startup Plan -->
                <div class="pricing-card p-8 rounded-3xl border border-slate-200 bg-white flex flex-col transition-all hover:border-blue-200">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Startup</h3>
                    <p class="text-slate-500 text-sm mb-6">Para agencias nuevas que están despegando.</p>
                    <div class="mb-6">
                        <span class="text-4xl font-extrabold text-slate-900">$49</span>
                        <span class="text-slate-500">/mes</span>
                    </div>
                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-center text-sm text-slate-600">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Hasta 500 paquetes/mes
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            1 Sucursal (Miami + Local)
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Portal de Clientes Básico
                        </li>
                    </ul>
                    <a href="#contact" class="block text-center py-3 px-6 rounded-full border border-slate-200 font-bold text-slate-900 hover:bg-slate-50 transition-colors">Empezar ahora</a>
                </div>

                <!-- Business Plan -->
                <div class="pricing-card p-8 rounded-3xl border-2 border-blue-600 bg-white flex flex-col shadow-xl shadow-blue-100 relative transition-all transform hover:-translate-y-2">
                    <div class="absolute top-0 right-8 transform -translate-y-1/2 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Más popular</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Business</h3>
                    <p class="text-slate-500 text-sm mb-6">La solución completa para empresas en crecimiento.</p>
                    <div class="mb-6">
                        <span class="text-4xl font-extrabold text-slate-900">$149</span>
                        <span class="text-slate-500">/mes</span>
                    </div>
                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-center text-sm text-slate-600">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Paquetes Ilimitados
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Sistema de Fidelización (Puntos)
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Integración con Yappy & PayPal
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Marca Blanca (Logo & Colores)
                        </li>
                    </ul>
                    <a href="#" class="block text-center py-3 px-6 rounded-full bg-blue-600 text-white font-bold hover:bg-blue-700 transition-colors">Solicitar Demo</a>
                </div>

                <!-- Enterprise Plan -->
                <div class="pricing-card p-8 rounded-3xl border border-slate-200 bg-white flex flex-col transition-all hover:border-blue-200">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Enterprise</h3>
                    <p class="text-slate-500 text-sm mb-6">Para redes multi-país y flujos complejos.</p>
                    <div class="mb-6">
                        <span class="text-4xl font-extrabold text-slate-900">Custom</span>
                    </div>
                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-center text-sm text-slate-600">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Multi-sucursales Ilimitadas
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            API Access & Webhooks
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Soporte Prioritario 24/7
                        </li>
                    </ul>
                    <a href="#contact" class="block text-center py-3 px-6 rounded-full border border-slate-200 font-bold text-slate-900 hover:bg-slate-50 transition-colors">Contactar Ventas</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div id="contact" class="py-24 bg-slate-900 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
            <div class="absolute top-0 left-0 w-96 h-96 bg-blue-600 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-600 rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white rounded-[40px] p-8 md:p-16 shadow-2xl">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-extrabold text-slate-900 mb-4">¿Listo para transformar tu negocio?</h2>
                    <p class="text-slate-600">Déjanos tus datos y un experto se pondrá en contacto contigo en menos de 24 horas.</p>
                </div>

                <livewire:public.contact-form />
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.registerPlugin(ScrollTrigger);

            // Hero Entrance
            const tl = gsap.timeline();
            tl.from(".hero-title", { duration: 1, y: 50, opacity: 0, ease: "power4.out" })
              .from(".hero-subtitle", { duration: 1, y: 30, opacity: 0, ease: "power4.out" }, "-=0.7")
              .from(".hero-buttons", { duration: 1, y: 20, opacity: 0, ease: "power4.out" }, "-=0.7")
              .from(".hero-mockup", { duration: 1.5, scale: 0.9, opacity: 0, ease: "power4.out" }, "-=0.5");

            // Scroll Reveal Animations
            gsap.utils.toArray('.reveal').forEach(elem => {
                gsap.from(elem, {
                    scrollTrigger: {
                        trigger: elem,
                        start: "top 85%",
                    },
                    duration: 1,
                    y: 30,
                    opacity: 0,
                    ease: "power3.out"
                });
            });

            // Feature Cards Stagger
            gsap.from(".feature-card", {
                scrollTrigger: {
                    trigger: "#features",
                    start: "top 75%",
                },
                duration: 0.8,
                y: 40,
                opacity: 0,
                stagger: 0.2,
                ease: "power3.out"
            });

            // Pricing Cards Stagger
            gsap.from(".pricing-card", {
                scrollTrigger: {
                    trigger: "#pricing",
                    start: "top 75%",
                },
                duration: 0.8,
                y: 40,
                opacity: 0,
                stagger: 0.2,
                ease: "power3.out"
            });
        });
    </script>
</x-layouts.landing>
