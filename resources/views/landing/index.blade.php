<x-layouts.landing>
    <div class="bg-white text-slate-900 selection:bg-blue-100 selection:text-blue-900">
        <!-- GLOBAL PARALLAX BACKGROUND -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <!-- Subtle World Map or Connectivity Pattern -->
            <div id="global-parallax-bg" class="absolute inset-0 opacity-[0.03] scale-110"
                 style="background-image: url('https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&q=80&w=2400'); background-size: cover; background-position: center;"></div>

            <!-- Interactive Technical Shapes -->
            <div class="parallax-element absolute top-[15%] left-[5%] w-[400px] h-[400px] bg-blue-50/50 rounded-full blur-[100px]" data-speed="0.02"></div>
            <div class="parallax-element absolute bottom-[10%] right-[5%] w-[500px] h-[500px] bg-indigo-50/40 rounded-full blur-[120px]" data-speed="0.04"></div>

            <!-- Floating Abstract Logistics Elements (SVG Pattern) -->
            <div class="parallax-element absolute top-[40%] right-[15%] opacity-[0.05]" data-speed="0.08">
                <svg width="200" height="200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.5">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </div>
        </div>

        <div class="relative z-10">
            <!-- Hero Section: Clean & Professional -->
            <section class="relative pt-32 pb-24 lg:pt-48 lg:pb-40">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                        <div>
                            <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-700 text-[10px] font-extrabold uppercase tracking-widest mb-8">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 mr-2"></span>
                                Logística Global Simplificada
                            </div>
                            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 mb-8 leading-[1.05]">
                                La plataforma moderna para <span class="text-blue-600">agencias courier.</span>
                            </h1>
                            <p class="text-lg md:text-xl text-slate-500 mb-12 font-medium leading-relaxed max-w-lg">
                                Automatiza tu operación de casilleros internacionales con IA. Recupera el control de tus paquetes, cobros y clientes en una sola herramienta.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="#pricing" class="inline-flex items-center justify-center px-10 py-4 bg-blue-600 text-white font-bold rounded-full shadow-xl shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition-all">
                                    Ver demostración
                                    <i class="fas fa-arrow-right ms-2.5 text-xs opacity-70"></i>
                                </a>
                                <a href="#features" class="inline-flex items-center justify-center px-10 py-4 bg-white border border-slate-200 text-slate-700 font-bold rounded-full hover:bg-slate-50 transition-all">
                                    Cómo funciona
                                </a>
                            </div>
                            <!-- Simple Social Proof -->
                            <div class="mt-16 pt-8 border-t border-slate-100 flex items-center gap-10 opacity-40">
                                <span class="text-[10px] font-black uppercase tracking-tighter italic text-slate-900">Logy Express</span>
                                <span class="text-[10px] font-black uppercase tracking-tighter italic text-slate-900">Fastbox.pa</span>
                                <span class="text-[10px] font-black uppercase tracking-tighter italic text-slate-900">Global Cargo</span>
                            </div>
                        </div>

                        <!-- Refined Visual: Minimal Mockup -->
                        <div class="relative">
                            <div class="absolute inset-0 bg-blue-600/5 rounded-[4rem] blur-3xl"></div>
                            <div class="relative bg-white rounded-[3rem] p-4 shadow-[0_32px_64px_-12px_rgba(0,0,0,0.1)] border border-slate-100">
                                <div class="bg-slate-50 rounded-[2rem] overflow-hidden border border-slate-100 aspect-video relative group">
                                    <!-- Browser dots -->
                                    <div class="h-10 bg-white border-b border-slate-100 flex items-center px-6 gap-2">
                                        <div class="w-2.5 h-2.5 rounded-full bg-slate-200"></div>
                                        <div class="w-2.5 h-2.5 rounded-full bg-slate-200"></div>
                                        <div class="w-2.5 h-2.5 rounded-full bg-slate-200"></div>
                                    </div>
                                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=2400"
                                         alt="System Preview"
                                         class="w-full h-full object-cover opacity-90 transition-opacity group-hover:opacity-100">

                                    <!-- Floating Info Card -->
                                    <div class="absolute bottom-6 right-6 bg-white p-6 rounded-2xl shadow-2xl border border-slate-50 max-w-[200px] animate-bounce-slow">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check text-xs"></i>
                                            </div>
                                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Arribado</span>
                                        </div>
                                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-green-500 w-[100%]"></div>
                                        </div>
                                        <p class="text-xs font-bold text-slate-700 mt-3">Paquete LGX-101</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Feature Grid: Modular & Airy -->
            <section id="features" class="py-24 bg-slate-50/50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <div class="mb-20 max-w-2xl mx-auto">
                        <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 mb-4">Todo lo que necesitas para operar.</h2>
                        <p class="text-slate-500 font-medium leading-relaxed">Diseñado para eliminar el trabajo manual y maximizar la precisión en cada etapa del envío.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                        <div class="text-center group p-4">
                            <div class="w-16 h-16 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center justify-center mx-auto mb-8 transition-all group-hover:shadow-xl group-hover:border-blue-100 group-hover:-translate-y-1">
                                <i class="fas fa-barcode text-2xl text-blue-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Escaneo OCR con IA</h3>
                            <p class="text-slate-500 text-sm leading-relaxed">Lee automáticamente guías de transportistas y facturas de Amazon para cargar carga en segundos.</p>
                        </div>
                        <div class="text-center group p-4">
                            <div class="w-16 h-16 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center justify-center mx-auto mb-8 transition-all group-hover:shadow-xl group-hover:border-blue-100 group-hover:-translate-y-1">
                                <i class="fas fa-bolt text-2xl text-amber-500"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Conciliación de Pagos</h3>
                            <p class="text-slate-500 text-sm leading-relaxed">Integración con Yappy y ACH que valida transferencias automáticamente para liberar paquetes.</p>
                        </div>
                        <div class="text-center group p-4">
                            <div class="w-16 h-16 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center justify-center mx-auto mb-8 transition-all group-hover:shadow-xl group-hover:border-blue-100 group-hover:-translate-y-1">
                                <i class="fas fa-star text-2xl text-indigo-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Fidelización NAtiva</h3>
                            <p class="text-slate-500 text-sm leading-relaxed">Sistema de puntos por lealtad que recompensa a tus clientes según el volumen de carga que muevan.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ROI Dynamic Section -->
            <section id="roi" class="py-24 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto bg-slate-900 rounded-[3.5rem] p-12 md:p-24 overflow-hidden relative" x-data="{
                    paquetes: 1500,
                    get ahorro() { return (this.paquetes * 1.25).toLocaleString() }
                }">
                    <!-- Background decor -->
                    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(white 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>

                    <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                        <div>
                            <h2 class="text-4xl md:text-6xl font-extrabold tracking-tight text-white mb-8">
                                Recupera el tiempo que pierdes hoy.
                            </h2>
                            <p class="text-slate-400 text-lg font-medium leading-relaxed mb-12">
                                Automatizar tu recepción y facturación reduce el costo operativo por paquete en más de un 60%.
                            </p>
                            <div class="space-y-6">
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Paquetes por mes: <span class="text-white text-xl ml-2 font-black" x-text="paquetes.toLocaleString()"></span></label>
                                <input type="range" min="500" max="15000" step="100" x-model="paquetes" class="w-full h-1.5 bg-slate-700 rounded-full appearance-none cursor-pointer accent-blue-500">
                            </div>
                        </div>
                        <div class="bg-white p-12 rounded-[2.5rem] text-center shadow-3xl">
                            <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mb-4">Tu ahorro mensual potencial</p>
                            <p class="text-6xl md:text-8xl font-black tracking-tight text-slate-900" x-text="'$' + ahorro"></p>
                            <p class="text-slate-500 font-bold mt-4 uppercase text-xs tracking-widest">Dólares USD</p>
                            <div class="mt-10 pt-10 border-t border-slate-100">
                                <a href="#contact" class="inline-flex items-center text-blue-600 font-extrabold uppercase tracking-widest text-sm hover:translate-x-2 transition-transform">
                                    Prueba gratuita 30 días <i class="fas fa-arrow-right ms-3 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Pricing: Direct & Honest -->
            <section id="pricing" class="py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                <div class="text-center mb-24">
                    <h2 class="text-4xl font-extrabold tracking-tight text-slate-900 mb-6">Planes que crecen contigo.</h2>
                    <p class="text-slate-500 font-medium">Sin comisiones por paquete. Sin letras pequeñas.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach([
                        ['name' => 'Startup', 'price' => '30', 'desc' => 'Para agencias en crecimiento.', 'feat' => ['Recepción OCR', 'Portal Clientes', 'Inventario Racks']],
                        ['name' => 'Business', 'price' => '45', 'desc' => 'El favorito de los couriers.', 'feat' => ['Bot WhatsApp AI', 'Loyalty System', 'Marca Blanca Full'], 'featured' => true],
                        ['name' => 'Enterprise', 'price' => '55', 'desc' => 'Para grandes volúmenes.', 'feat' => ['App Móvil Nativa', 'Soporte VIP 24/7', 'Multi-supervisores']]
                    ] as $plan)
                    <div class="p-12 rounded-[2.5rem] border border-slate-100 flex flex-col {{ isset($plan['featured']) ? 'shadow-2xl shadow-blue-100 ring-2 ring-blue-600' : 'bg-white' }}">
                        <h3 class="text-2xl font-black text-slate-900 mb-2">{{ $plan['name'] }}</h3>
                        <p class="text-slate-400 text-sm font-medium mb-10">{{ $plan['desc'] }}</p>
                        <div class="flex items-baseline mb-12">
                            <span class="text-5xl font-black text-slate-900">${{ $plan['price'] }}</span>
                            <span class="text-slate-400 text-sm font-bold ml-2">/ mes</span>
                        </div>
                        <ul class="space-y-5 mb-16 flex-grow">
                            @foreach($plan['feat'] as $f)
                            <li class="flex items-center text-sm font-semibold text-slate-600">
                                <i class="fas fa-check text-blue-500 mr-4"></i> {{ $f }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="#contact" class="block text-center py-4 rounded-full font-black uppercase tracking-widest text-[10px] transition-all {{ isset($plan['featured']) ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-slate-900 text-white hover:bg-blue-600' }}">
                            Empezar ahora
                        </a>
                    </div>
                    @endforeach
                </div>
            </section>

            <!-- Final CTA: Human-Centric -->
            <section id="contact" class="py-40 bg-slate-50">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-4xl md:text-6xl font-extrabold tracking-tight text-slate-900 mb-8">
                        ¿Listo para dar el siguiente paso?
                    </h2>
                    <p class="text-slate-500 text-xl font-medium mb-16">Un especialista en logística te contactará en menos de 2 horas.</p>

                    <div class="bg-white p-10 md:p-20 rounded-[3rem] shadow-2xl border border-slate-100">
                        <livewire:public.contact-form />
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- REFINED SCRIPTS -->
    <script>
        window.addEventListener('load', () => {
            if (typeof gsap === 'undefined') return;
            gsap.registerPlugin(ScrollTrigger);

            // Global Scroll Parallax (Background pattern movement)
            gsap.to("#global-parallax-bg", {
                scrollTrigger: {
                    trigger: "body",
                    start: "top top",
                    end: "bottom bottom",
                    scrub: 1.5
                },
                y: 300,
                ease: "none"
            });

            // Mouse Move Parallax (Interactive shapes and glows)
            document.addEventListener("mousemove", (e) => {
                const mouseX = e.clientX;
                const mouseY = e.clientY;
                gsap.to(".parallax-element", {
                    duration: 2,
                    x: (i, target) => (mouseX - window.innerWidth / 2) * (target.getAttribute("data-speed") || 0.05),
                    y: (i, target) => (mouseY - window.innerHeight / 2) * (target.getAttribute("data-speed") || 0.05),
                    ease: "power2.out"
                });
            });

            // Hero Animation: Smooth entry
            const tl = gsap.timeline();
            tl.from("h1", { duration: 1.2, y: 50, opacity: 0, ease: "power4.out" })
              .from("h1 + p", { duration: 1, y: 30, opacity: 0, ease: "power4.out" }, "-=0.8")
              .from(".flex.flex-col.sm\\:flex-row.gap-4", { duration: 1, y: 20, opacity: 0, ease: "power4.out" }, "-=0.7");

            // Subtle Fade In Sections
            gsap.utils.toArray('section').forEach(section => {
                if (section.classList.contains('pt-32')) return; // Skip hero as it has its own timeline

                gsap.from(section, {
                    scrollTrigger: {
                        trigger: section,
                        start: "top 85%",
                    },
                    duration: 1,
                    y: 40,
                    opacity: 0,
                    ease: "power2.out"
                });
            });
        });
    </script>
</x-layouts.landing>
