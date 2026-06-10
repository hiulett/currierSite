<x-layouts.landing>
    <div class="bg-[#0f172a] text-[#f1f5f9] selection:bg-[#3b7ddd]/30">
        <!-- PREMIUM DECORATIONS -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <!-- Animated Background Grid -->
            <div class="absolute inset-0 opacity-[0.05]"
                 style="background-image: linear-gradient(#3b7ddd 1px, transparent 1px), linear-gradient(90deg, #3b7ddd 1px, transparent 1px); background-size: 60px 60px;"></div>

            <!-- Strategic Glows (Stitch Aesthetic) -->
            <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-blue-600/10 rounded-full blur-[120px] animate-pulse-slow"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[800px] h-[800px] bg-indigo-500/10 rounded-full blur-[100px]"></div>

            <!-- Center Ray -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-px h-full bg-gradient-to-b from-transparent via-blue-500/20 to-transparent"></div>
        </div>

        <div class="relative z-10">
            <!-- HERO SECTION (Split Distribution) -->
            <section class="relative pt-32 pb-24 lg:pt-56 lg:pb-40 overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                        <div class="hero-text-area">
                            <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/5 border border-white/10 backdrop-blur-md mb-8 animate-fade-in">
                                <span class="flex h-2 w-2 rounded-full bg-[#3b7ddd] mr-2 shadow-[0_0_10px_#3b7ddd]"></span>
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[#94a3b8]">Infraestructura Logística v2.0</span>
                            </div>

                            <h1 class="text-6xl md:text-8xl font-black tracking-tighter mb-8 leading-[0.85] uppercase italic">
                                Domina tu <br>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#3b7ddd] to-blue-400">OPERACIÓN</span> <br>
                                <span class="text-white">COURIER</span>
                            </h1>

                            <p class="max-w-lg text-lg md:text-xl text-[#94a3b8] mb-12 font-medium leading-relaxed">
                                El sistema operativo B2B que automatiza recepciones, fideliza clientes y asegura tu rentabilidad neta en Panamá.
                            </p>

                            <div class="flex flex-col sm:flex-row gap-5">
                                <a href="#pricing" class="cta-shine flex items-center justify-center px-10 py-5 bg-[#3b7ddd] text-white text-sm font-black rounded-xl shadow-2xl shadow-blue-500/20 hover:-translate-y-1 transition-all uppercase tracking-widest">
                                    Lanzar mi Agencia
                                    <i class="fas fa-arrow-right ms-3"></i>
                                </a>
                                <a href="#contact" class="flex items-center justify-center px-10 py-5 bg-white/5 border border-white/10 backdrop-blur-md text-white text-sm font-black rounded-xl hover:bg-white/10 transition-all uppercase tracking-widest">
                                    Demo Técnica
                                </a>
                            </div>

                            <!-- Trust Badges -->
                            <div class="mt-16 flex items-center gap-8 opacity-40 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-700">
                                <span class="text-xs font-black tracking-tighter uppercase italic">LOGY EXPRESS</span>
                                <span class="text-xs font-black tracking-tighter uppercase italic">FASTBOX.PA</span>
                                <span class="text-xs font-black tracking-tighter uppercase italic">PANAMA COURIER</span>
                            </div>
                        </div>

                        <!-- Hero Mockup (Floating Card Aesthetic) -->
                        <div class="relative lg:h-[600px] flex items-center justify-center">
                            <div class="absolute -inset-4 bg-[#3b7ddd]/20 rounded-[3rem] blur-3xl opacity-30"></div>
                            <div class="relative w-full aspect-square md:aspect-video lg:aspect-square bg-[#1e293b] rounded-[2.5rem] border border-white/10 shadow-2xl overflow-hidden group">
                                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>
                                <div class="h-10 bg-slate-900/90 border-b border-white/5 flex items-center px-8 gap-2">
                                    <div class="w-3 h-3 rounded-full bg-red-500/20"></div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-500/20"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-500/20"></div>
                                </div>
                                <div class="p-8">
                                    <div class="grid grid-cols-3 gap-4 mb-8">
                                        <div class="h-24 bg-white/5 rounded-2xl border border-white/5 animate-pulse"></div>
                                        <div class="h-24 bg-white/5 rounded-2xl border border-white/5 animate-pulse"></div>
                                        <div class="h-24 bg-[#3b7ddd]/10 rounded-2xl border border-[#3b7ddd]/20"></div>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="h-4 bg-white/5 rounded-full w-3/4"></div>
                                        <div class="h-4 bg-white/5 rounded-full w-1/2"></div>
                                        <div class="h-4 bg-white/5 rounded-full w-2/3"></div>
                                        <div class="h-40 bg-white/5 rounded-3xl border border-white/5 mt-8"></div>
                                    </div>
                                </div>
                                <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=2000"
                                     alt="Dashboard Preview"
                                     class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-50 group-hover:opacity-100 transition-opacity duration-1000">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- BENTO GRID FEATURES -->
            <section id="features" class="py-32 bg-slate-900/30 backdrop-blur-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="mb-20">
                        <h2 class="text-4xl md:text-6xl font-black tracking-tighter uppercase italic leading-none">
                            Ingeniería <br>
                            <span class="text-[#3b7ddd]">Sin Compromisos</span>
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Smart OCR Card (Large) -->
                        <div class="md:col-span-2 p-12 bg-[#1e293b] rounded-[3rem] border border-white/5 relative overflow-hidden group reveal">
                            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-[#3b7ddd]/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-1000"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 bg-[#3b7ddd] rounded-2xl flex items-center justify-center mb-10 shadow-xl shadow-blue-500/20">
                                    <i class="fas fa-microchip text-2xl text-white"></i>
                                </div>
                                <h3 class="text-3xl font-black mb-6 uppercase tracking-tighter italic">Recepción IA & OCR</h3>
                                <p class="text-[#94a3b8] text-lg leading-relaxed max-w-md">
                                    Extrae trackings, pesos y datos de clientes desde facturas de Amazon, eBay y USPS automáticamente. Sin errores manuales.
                                </p>
                            </div>
                        </div>

                        <!-- Loyalty Card -->
                        <div class="p-12 bg-[#1e293b] rounded-[3rem] border border-white/5 relative overflow-hidden group reveal">
                            <div class="w-14 h-14 bg-indigo-500/20 text-indigo-400 rounded-2xl flex items-center justify-center mb-10 border border-indigo-500/20">
                                <i class="fas fa-award text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-black mb-6 uppercase tracking-tighter italic">Fidelización 2.0</h3>
                            <p class="text-[#94a3b8] leading-relaxed">
                                Sistema de puntos por peso embarcado. Convierte a tus clientes ocasionales en fans recurrentes.
                            </p>
                        </div>

                        <!-- Data Governance -->
                        <div class="p-12 bg-[#1e293b] rounded-[3rem] border border-white/5 relative overflow-hidden group reveal">
                            <div class="w-14 h-14 bg-purple-500/20 text-purple-400 rounded-2xl flex items-center justify-center mb-10 border border-purple-500/20">
                                <i class="fas fa-lock text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-black mb-6 uppercase tracking-tighter italic">Privacidad Total</h3>
                            <p class="text-[#94a3b8] leading-relaxed">
                                Aislamiento de datos de grado bancario para cada Tenant. Tu información es solo tuya.
                            </p>
                        </div>

                        <!-- Yappy Bridge (Large) -->
                        <div class="md:col-span-2 p-12 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[3rem] text-white relative overflow-hidden group reveal shadow-3xl shadow-blue-500/10">
                            <div class="absolute right-12 top-12 opacity-10">
                                <i class="fas fa-bolt text-9xl"></i>
                            </div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center mb-10 border border-white/20">
                                    <span class="font-black text-2xl italic">Y</span>
                                </div>
                                <h3 class="text-4xl font-black mb-6 uppercase tracking-tighter italic leading-none">Bridge Yappy & ACH</h3>
                                <p class="text-blue-100 text-xl leading-relaxed max-w-lg">
                                    Conciliación automática para el mercado de Panamá. Valida capturas y abona saldos al instante sin intervención manual.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ROI DYNAMIC CALCULATOR -->
            <section id="roi" class="py-32 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto" x-data="{
                    paquetes: 2500,
                    manualCost: 1.80,
                    get saved() { return Math.round(this.paquetes * (this.manualCost - 0.35)) }
                }">
                    <div class="bg-white text-[#0f172a] rounded-[4rem] p-12 md:p-24 overflow-hidden relative shadow-3xl">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                            <div>
                                <span class="text-[#3b7ddd] font-black uppercase tracking-[0.3em] text-[10px]">Financial Impact</span>
                                <h2 class="text-5xl md:text-8xl font-black tracking-tighter mt-8 mb-12 leading-[0.85] uppercase italic">
                                    Deja de <br>
                                    <span class="text-red-500">QUEMAR</span> <br>
                                    capital.
                                </h2>
                                <div class="space-y-12 max-w-sm">
                                    <div class="space-y-4">
                                        <div class="flex justify-between text-xs font-black uppercase tracking-widest text-slate-400">
                                            <span>Paquetes Mensuales</span>
                                            <span class="text-[#0f172a] text-lg" x-text="paquetes.toLocaleString()"></span>
                                        </div>
                                        <input type="range" min="500" max="20000" step="100" x-model="paquetes" class="w-full h-1.5 bg-slate-100 rounded-full appearance-none cursor-pointer accent-[#3b7ddd]">
                                    </div>
                                    <div class="space-y-4">
                                        <div class="flex justify-between text-xs font-black uppercase tracking-widest text-slate-400">
                                            <span>Costo por digitación ($)</span>
                                            <span class="text-[#0f172a] text-lg" x-text="'$' + parseFloat(manualCost).toFixed(2)"></span>
                                        </div>
                                        <input type="range" min="0.50" max="5.00" step="0.10" x-model="manualCost" class="w-full h-1.5 bg-slate-100 rounded-full appearance-none cursor-pointer accent-[#3b7ddd]">
                                    </div>
                                </div>
                            </div>

                            <div class="bg-[#0f172a] rounded-[3.5rem] p-16 text-white text-center shadow-2xl transform lg:rotate-3 hover:rotate-0 transition-transform duration-500">
                                <p class="text-[#94a3b8] font-black uppercase tracking-widest text-[10px] mb-6">Tu ahorro potencial mensual</p>
                                <p class="text-7xl md:text-9xl font-black tracking-tight text-[#3b7ddd] mb-12" x-text="'$' + saved.toLocaleString()"></p>
                                <div class="h-px w-24 bg-white/10 mx-auto mb-12"></div>
                                <a href="#contact" class="inline-flex items-center text-sm font-black uppercase tracking-[0.2em] border-b-2 border-[#3b7ddd] pb-2 hover:text-[#3b7ddd] transition-colors">
                                    RECUPERAR MI DINERO AHORA
                                    <i class="fas fa-chevron-right ms-4 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- PRICING TIERS -->
            <section id="pricing" class="py-32 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                <div class="text-center mb-24 reveal">
                    <h2 class="text-5xl md:text-8xl font-black tracking-tighter text-white uppercase italic leading-none mb-6">Planes Elite</h2>
                    <p class="text-[#94a3b8] font-medium text-lg">Sin comisiones por paquete. Escala sin límites.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @php
                        $plans = [
                            ['name' => 'Startup', 'price' => '30', 'feat' => ['Recepción OCR', 'Portal Clientes', 'Inventario Racks']],
                            ['name' => 'Business', 'price' => '45', 'feat' => ['Bot WhatsApp AI', 'Loyalty System', 'Marca Blanca Full'], 'main' => true],
                            ['name' => 'Enterprise', 'price' => '55', 'feat' => ['App Móvil Nativa', 'Multi-supervisores', 'Soporte VIP 24/7']]
                        ];
                    @endphp

                    @foreach($plans as $plan)
                    <div class="reveal flex flex-col p-12 rounded-[3.5rem] {{ isset($plan['main']) ? 'bg-[#3b7ddd] shadow-3xl shadow-blue-500/20 md:scale-110 z-20' : 'bg-[#1e293b] border border-white/5' }}">
                        @if(isset($plan['main']))
                            <span class="inline-block bg-white text-[#3b7ddd] text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest mb-10 w-fit">Recomendado</span>
                        @endif
                        <h3 class="text-2xl font-black uppercase tracking-tighter italic mb-2 text-white">{{ $plan['name'] }}</h3>
                        <div class="flex items-baseline mb-12">
                            <span class="text-6xl font-black text-white">${{ $plan['price'] }}</span>
                            <span class="{{ isset($plan['main']) ? 'text-white/70' : 'text-[#94a3b8]' }} text-sm font-bold ms-3">/mes</span>
                        </div>
                        <ul class="space-y-6 mb-16 flex-grow">
                            @foreach($plan['feat'] as $f)
                            <li class="flex items-center text-sm font-bold {{ isset($plan['main']) ? 'text-white' : 'text-slate-400' }}">
                                <i class="fas fa-check {{ isset($plan['main']) ? 'text-blue-200' : 'text-[#3b7ddd]' }} me-4"></i>
                                {{ $f }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="#contact" class="block text-center py-5 rounded-2xl font-black uppercase tracking-widest text-[11px] transition-all {{ isset($plan['main']) ? 'bg-white text-[#3b7ddd] hover:bg-slate-100 shadow-xl' : 'bg-white/5 border border-white/10 text-white hover:bg-white/10' }}">
                            {{ isset($plan['main']) ? 'Solicitar Demo' : 'Empezar Ahora' }}
                        </a>
                    </div>
                    @endforeach
                </div>
            </section>

            <!-- CONTACT FORM -->
            <section id="contact" class="py-40 relative">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="reveal text-6xl md:text-9xl font-black tracking-tighter text-white uppercase italic leading-none mb-20 opacity-20">CONTACTO</h2>
                    <div class="reveal bg-[#1e293b] border border-white/5 p-12 md:p-24 rounded-[4rem] shadow-3xl">
                        <h3 class="text-4xl font-black mb-4 uppercase tracking-tighter italic leading-none text-white">¿Hablamos de negocios?</h3>
                        <p class="text-[#94a3b8] text-lg mb-16 font-medium">Un consultor técnico te contactará en menos de 2 horas.</p>
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

            gsap.from(".hero-text-area > *", {
                duration: 1.5,
                y: 80,
                opacity: 0,
                stagger: 0.15,
                ease: "power4.out"
            });

            gsap.utils.toArray('.reveal').forEach(elem => {
                gsap.from(elem, {
                    scrollTrigger: {
                        trigger: elem,
                        start: "top 95%",
                    },
                    duration: 1.2,
                    y: 40,
                    opacity: 0,
                    ease: "power3.out"
                });
            });
        });
    </script>
</x-layouts.landing>
