<x-layouts.landing>
    <div class="bg-[#0f172a] text-slate-200 overflow-x-hidden">
        <!-- GLOBAL BACKGROUND DECORATIONS -->
        <div class="fixed inset-0 pointer-events-none z-0">
            <!-- Subtle Grid -->
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(#3b7ddd 1px, transparent 1px), linear-gradient(90deg, #3b7ddd 1px, transparent 1px); background-size: 50px 50px;"></div>
            <!-- Glows -->
            <div class="absolute top-[-10%] left-[-10%] w-[800px] h-[800px] bg-blue-600/10 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-[20%] right-[-5%] w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[100px]"></div>
        </div>

        @php
            $features = [
                [
                    'title' => 'Recepción Inteligente',
                    'desc' => 'Motor OCR avanzado que procesa guías de Amazon y USPS en milisegundos.',
                    'icon' => 'fa-qrcode',
                    'color' => 'blue'
                ],
                [
                    'title' => 'Fidelización 2.0',
                    'desc' => 'Gamifica tu logística con puntos por peso que tus clientes aman.',
                    'icon' => 'fa-star',
                    'color' => 'indigo'
                ],
                [
                    'title' => 'Gobernanza Total',
                    'desc' => 'Control de inventario por racks y auditoría financiera en tiempo real.',
                    'icon' => 'fa-shield-halved',
                    'color' => 'purple'
                ],
                [
                    'title' => 'Pagos Automatizados',
                    'desc' => 'Conciliación instantánea de Yappy y ACH sin intervención manual.',
                    'icon' => 'fa-bolt',
                    'color' => 'amber'
                ]
            ];
        @endphp

        <div class="relative z-10">
            <!-- Hero Section -->
            <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto overflow-hidden">
                <div class="text-center">
                    <div class="hero-reveal inline-flex items-center px-4 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-black uppercase tracking-[0.2em] mb-8">
                        <span class="flex h-2 w-2 rounded-full bg-blue-500 mr-2 animate-ping"></span>
                        Infraestructura Logística de Próxima Generación
                    </div>

                    <h1 class="hero-reveal text-5xl md:text-8xl font-black tracking-tighter text-white mb-8 leading-[0.9] uppercase italic">
                        La infraestructura digital <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">para tu Courier del futuro</span>
                    </h1>

                    <p class="hero-reveal max-w-2xl mx-auto text-lg md:text-xl text-slate-400 mb-12 font-medium leading-relaxed">
                        Elimina el caos operativo. LogySaaS es el sistema operativo integral diseñado para escalar agencias de carga con IA y automatización financiera.
                    </p>

                    <div class="hero-reveal flex flex-col sm:flex-row justify-center items-center gap-6">
                        <a href="#pricing" class="group relative px-10 py-5 bg-blue-600 text-white text-lg font-black rounded-2xl shadow-2xl shadow-blue-500/30 transition-all hover:-translate-y-1 hover:shadow-blue-500/50">
                            VER PLANES
                            <i class="fas fa-chevron-right ms-2 group-hover:translate-x-1 transition-transform text-sm"></i>
                        </a>
                        <a href="#contact" class="px-10 py-5 bg-white/5 backdrop-blur-md border border-white/10 text-white text-lg font-black rounded-2xl hover:bg-white/10 transition-all">
                            SOLICITAR DEMO
                        </a>
                    </div>

                    <!-- Metrics bar -->
                    <div class="hero-reveal mt-24 grid grid-cols-2 md:grid-cols-4 gap-8 py-10 border-y border-white/5 max-w-4xl mx-auto">
                        <div>
                            <p class="text-3xl font-black text-white">5x</p>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">Más rápido</p>
                        </div>
                        <div class="md:border-l border-white/5">
                            <p class="text-3xl font-black text-white">0%</p>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">Errores</p>
                        </div>
                        <div class="md:border-l border-white/5">
                            <p class="text-3xl font-black text-white">100%</p>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">Seguro</p>
                        </div>
                        <div class="md:border-l border-white/5">
                            <p class="text-3xl font-black text-white">24h</p>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">Setup</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Feature Bento Grid -->
            <section id="features" class="py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($features as $f)
                    <div class="reveal p-10 bg-slate-900/50 backdrop-blur-sm rounded-[2.5rem] border border-white/5 hover:border-{{ $f['color'] }}-500/50 transition-all group">
                        <div class="w-14 h-14 bg-{{ $f['color'] }}-500/10 text-{{ $f['color'] }}-400 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <i class="fas {{ $f['icon'] }} text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-white mb-4 uppercase tracking-tighter">{{ $f['title'] }}</h3>
                        <p class="text-slate-400 text-sm leading-relaxed font-medium">
                            {{ $f['desc'] }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </section>

            <!-- Dashboard Preview -->
            <section class="py-24 px-4 sm:px-6 lg:px-8">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-16 reveal">
                        <h2 class="text-4xl md:text-6xl font-black tracking-tighter text-white uppercase italic leading-none">
                            Control Total <br>
                            <span class="text-blue-500">en una sola pantalla</span>
                        </h2>
                    </div>

                    <div class="reveal relative p-4 bg-slate-800 rounded-[3rem] border border-white/10 shadow-2xl shadow-blue-500/10">
                        <div class="bg-[#0f172a] rounded-[2.5rem] overflow-hidden border border-white/5">
                            <div class="h-10 bg-slate-900/80 border-b border-white/5 flex items-center px-8 gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-500/20"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500/20"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500/20"></div>
                                <div class="ml-6 h-4 w-64 bg-white/5 rounded-full"></div>
                            </div>
                            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=2000" alt="Dashboard" class="w-full opacity-100 transition-opacity duration-700">
                        </div>
                    </div>
                </div>
            </section>

            <!-- ROI Calculator -->
            <section id="roi" class="py-24 bg-blue-600 text-white relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0" style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 30px 30px;"></div>
                </div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10" x-data="{
                    paquetes: 2000,
                    costoManual: 1.50,
                    get ahorro() { return Math.round(this.paquetes * (this.costoManual - 0.35)) }
                }">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                        <div class="reveal">
                            <span class="bg-white/20 px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest">Calculadora ROI</span>
                            <h2 class="text-5xl md:text-7xl font-black tracking-tighter mt-6 mb-8 leading-[0.9] uppercase italic">
                                Deja de <span class="text-slate-900 underline decoration-white/30">perder dinero</span> en cada paquete.
                            </h2>
                            <p class="text-blue-100 text-xl font-medium mb-12 leading-relaxed">
                                Automatizar tu recepción y cobranza no es un gasto, es la inversión más rentable de tu agencia este año.
                            </p>

                            <div class="space-y-10">
                                <div>
                                    <div class="flex justify-between mb-4">
                                        <span class="text-xs font-black uppercase tracking-widest">Paquetes por Mes</span>
                                        <span class="text-2xl font-black" x-text="paquetes.toLocaleString()"></span>
                                    </div>
                                    <input type="range" min="500" max="15000" step="100" x-model="paquetes" class="w-full h-1.5 bg-white/20 rounded-full appearance-none cursor-pointer accent-white">
                                </div>
                                <div>
                                    <div class="flex justify-between mb-4">
                                        <span class="text-xs font-black uppercase tracking-widest">Costo Operativo Actual</span>
                                        <span class="text-2xl font-black" x-text="'$' + parseFloat(costoManual).toFixed(2)"></span>
                                    </div>
                                    <input type="range" min="0.50" max="5.00" step="0.10" x-model="costoManual" class="w-full h-1.5 bg-white/20 rounded-full appearance-none cursor-pointer accent-white">
                                </div>
                            </div>
                        </div>

                        <div class="reveal bg-slate-900 p-12 rounded-[3.5rem] shadow-3xl transform lg:rotate-2 group transition-transform hover:rotate-0">
                            <p class="text-blue-400 font-black uppercase tracking-widest text-[10px] mb-4">Ahorro Mensual Estimado</p>
                            <p class="text-7xl md:text-9xl font-black tracking-tight text-white mb-6" x-text="'$' + ahorro.toLocaleString()"></p>
                            <div class="h-1 w-20 bg-blue-500 mb-8"></div>
                            <p class="text-slate-400 font-medium text-lg leading-relaxed mb-12">
                                Este es el capital que hoy estás desperdiciando en procesos manuales y errores humanos. LogySaaS te lo devuelve.
                            </p>
                            <a href="#contact" class="inline-flex items-center px-10 py-5 bg-blue-600 text-white font-black rounded-2xl shadow-xl hover:bg-blue-700 transition-all uppercase tracking-widest text-sm">
                                Reclamar mi ahorro <i class="fas fa-arrow-right ms-3"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Yappy / Local -->
            <section class="py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                <div class="flex flex-col lg:flex-row items-center gap-20">
                    <div class="flex-1 reveal">
                        <div class="w-24 h-24 bg-blue-600 rounded-[2rem] flex items-center justify-center text-white text-5xl shadow-2xl shadow-blue-500/20 mb-10">
                            <span class="font-black italic">Y</span>
                        </div>
                        <h2 class="text-5xl font-black tracking-tighter text-white mb-8 uppercase italic leading-none">Integración <span class="text-blue-500">Yappy</span> Nativa</h2>
                        <p class="text-slate-400 text-lg leading-relaxed font-medium max-w-xl">
                            La primera plataforma SaaS en Panamá con validación automática de pagos. Tus clientes pagan, el sistema confirma y el paquete se libera para entrega sin que tú muevas un dedo.
                        </p>
                    </div>
                    <div class="flex-1 grid grid-cols-2 gap-6 reveal">
                        <div class="p-10 bg-slate-900 rounded-[2.5rem] border border-white/5 text-center">
                            <i class="fas fa-university text-3xl text-blue-400 mb-6"></i>
                            <p class="text-2xl font-black text-white mb-1">ACH</p>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Multi-banco</p>
                        </div>
                        <div class="p-10 bg-slate-900 rounded-[2.5rem] border border-white/5 text-center">
                            <i class="fas fa-credit-card text-3xl text-blue-400 mb-6"></i>
                            <p class="text-2xl font-black text-white mb-1">Cards</p>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Global</p>
                        </div>
                        <div class="p-10 bg-blue-600/10 rounded-[2.5rem] border border-blue-600/20 text-center col-span-2">
                            <i class="fab fa-whatsapp text-3xl text-green-400 mb-6"></i>
                            <p class="text-2xl font-black text-white mb-1">WhatsApp Business</p>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Notificaciones 24/7</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Pricing -->
            <section id="pricing" class="py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                <div class="text-center mb-20 reveal">
                    <h2 class="text-4xl md:text-7xl font-black tracking-tighter text-white uppercase italic leading-none mb-6">Planes Simples</h2>
                    <p class="text-slate-400 font-medium">Inversión transparente para agencias ambiciosas.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Startup -->
                    <div class="reveal p-12 bg-slate-900 rounded-[3rem] border border-white/5 flex flex-col hover:border-blue-500/30 transition-all">
                        <h3 class="text-2xl font-black text-white mb-2 uppercase tracking-tighter italic">Startup</h3>
                        <div class="flex items-baseline mb-10">
                            <span class="text-5xl font-black text-white">$30</span>
                            <span class="text-slate-500 text-sm font-bold ms-2">/mes</span>
                        </div>
                        <ul class="space-y-5 mb-12 flex-grow">
                            @foreach(['Recepción OCR', 'Portal Clientes', 'Inventario Racks', 'Facturación Auto'] as $item)
                            <li class="flex items-center text-sm font-bold text-slate-400">
                                <i class="fas fa-check text-blue-500 me-4"></i> {{ $item }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="#contact" class="block text-center py-5 bg-white/5 border border-white/10 rounded-2xl font-black text-white hover:bg-white/10 transition-all uppercase tracking-widest text-[10px]">Empezar Ahora</a>
                    </div>

                    <!-- Business -->
                    <div class="reveal p-12 bg-blue-600 rounded-[3rem] flex flex-col shadow-3xl shadow-blue-500/20 transform md:scale-110 relative z-20">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white text-blue-600 text-[10px] font-black px-8 py-2.5 rounded-full uppercase tracking-[0.3em] shadow-xl">Más Elegido</div>
                        <h3 class="text-2xl font-black text-white mb-2 uppercase tracking-tighter italic">Business</h3>
                        <div class="flex items-baseline mb-10">
                            <span class="text-5xl font-black text-white">$45</span>
                            <span class="text-white/70 text-sm font-bold ms-2">/mes</span>
                        </div>
                        <ul class="space-y-5 mb-12 flex-grow">
                            @foreach(['Todo en Startup', 'Bot WhatsApp AI', 'Loyalty System', 'Marca Blanca Full'] as $item)
                            <li class="flex items-center text-sm font-black text-white">
                                <i class="fas fa-check text-white me-4"></i> {{ $item }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="#contact" class="block text-center py-5 bg-white text-blue-600 rounded-2xl font-black hover:bg-slate-100 transition-all uppercase tracking-widest text-[10px]">Solicitar Demo</a>
                    </div>

                    <!-- Enterprise -->
                    <div class="reveal p-12 bg-slate-900 rounded-[3rem] border border-white/5 flex flex-col hover:border-blue-500/30 transition-all">
                        <h3 class="text-2xl font-black text-white mb-2 uppercase tracking-tighter italic">Enterprise</h3>
                        <div class="flex items-baseline mb-10">
                            <span class="text-5xl font-black text-white">$55</span>
                            <span class="text-slate-500 text-sm font-bold ms-2">/mes</span>
                        </div>
                        <ul class="space-y-5 mb-12 flex-grow">
                            @foreach(['Todo en Business', 'App Móvil Nativa', 'Multi-supervisores', 'Soporte VIP 24/7'] as $item)
                            <li class="flex items-center text-sm font-bold text-slate-400">
                                <i class="fas fa-check text-blue-500 me-4"></i> {{ $item }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="#contact" class="block text-center py-5 bg-white/5 border border-white/10 rounded-2xl font-black text-white hover:bg-white/10 transition-all uppercase tracking-widest text-[10px]">Hablar con Ventas</a>
                    </div>
                </div>
            </section>

            <!-- Final CTA -->
            <section id="contact" class="py-32 relative overflow-hidden">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="reveal text-5xl md:text-9xl font-black tracking-tighter text-white uppercase italic leading-none mb-12">Escala hoy</h2>
                    <div class="reveal bg-white/5 backdrop-blur-xl p-10 md:p-20 rounded-[4rem] border border-white/10 shadow-3xl">
                        <h3 class="text-3xl font-black text-white mb-4">¿Dudas? Habla con un experto</h3>
                        <p class="text-slate-400 text-lg mb-12">Estamos listos para implementar tu courier en tiempo récord.</p>
                        <livewire:public.contact-form />
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            if (typeof gsap === 'undefined') return;
            gsap.registerPlugin(ScrollTrigger);

            // Staggered reveal for Hero
            gsap.from(".hero-reveal", {
                duration: 1.2,
                y: 60,
                opacity: 0,
                stagger: 0.2,
                ease: "power4.out"
            });

            // Reveal Sections
            gsap.utils.toArray('.reveal').forEach(elem => {
                gsap.from(elem, {
                    scrollTrigger: {
                        trigger: elem,
                        start: "top 90%",
                    },
                    duration: 1,
                    y: 40,
                    opacity: 0,
                    ease: "power3.out"
                });
            });
        });
    </script>
</x-layouts.landing>
