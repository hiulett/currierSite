<x-layouts.landing>
    <div class="relative bg-[#0f172a] text-[#f1f5f9] overflow-hidden">
        <!-- Background Grid & Glow Effects -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 opacity-[0.05]" style="background-image: radial-gradient(#3b7ddd 1px, transparent 1px); background-size: 40px 40px;"></div>
            <div class="absolute top-[-10%] left-[-10%] w-[600px] h-[600px] bg-[#3b7ddd]/20 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[100px]"></div>
        </div>

        @php
            $features = [
                [
                    'title' => 'Recepción Inteligente',
                    'desc' => 'Motor OCR avanzado que procesa guías de Amazon y USPS en milisegundos.',
                    'icon' => 'fa-qrcode'
                ],
                [
                    'title' => 'Fidelización 2.0',
                    'desc' => 'Gamifica tu logística con puntos por peso que tus clientes aman.',
                    'icon' => 'fa-star'
                ],
                [
                    'title' => 'Gobernanza Total',
                    'desc' => 'Control de inventario por racks y auditoría financiera en tiempo real.',
                    'icon' => 'fa-shield-halved'
                ],
                [
                    'title' => 'Pagos Automatizados',
                    'desc' => 'Conciliación instantánea de Yappy y ACH sin intervención manual.',
                    'icon' => 'fa-bolt'
                ]
            ];
        @endphp

        <div class="relative z-10">
            <!-- Hero Section -->
            <section class="pt-40 pb-20 lg:pt-52 lg:pb-32">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-[#3b7ddd]/10 border border-[#3b7ddd]/20 text-[#3b7ddd] text-xs font-black uppercase tracking-widest mb-8 animate-fade-in">
                        <span class="flex h-2 w-2 rounded-full bg-[#3b7ddd] mr-2"></span>
                        Infraestructura de Grado Empresarial
                    </div>
                    <h1 class="text-5xl md:text-8xl font-black tracking-tighter text-[#f1f5f9] mb-8 leading-[0.9] uppercase italic">
                        La infraestructura digital <br>
                        <span class="text-[#3b7ddd]">para tu Courier del futuro</span>
                    </h1>
                    <p class="max-w-2xl mx-auto text-lg md:text-xl text-[#94a3b8] mb-12 font-medium leading-relaxed">
                        Elimina el caos operativo. LogySaaS es el sistema operativo integral diseñado para escalar agencias de carga con IA y automatización financiera.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center items-center gap-6">
                        <a href="#pricing" class="group relative px-10 py-5 bg-[#3b7ddd] text-white text-lg font-black rounded-xl shadow-2xl shadow-blue-500/20 transition-all hover:-translate-y-1 hover:shadow-blue-500/40">
                            VER PLANES
                            <i class="fas fa-chevron-right ms-2 group-hover:translate-x-1 transition-transform text-sm"></i>
                        </a>
                        <a href="#contact" class="px-10 py-5 bg-white/5 backdrop-blur-md border border-white/10 text-[#f1f5f9] text-lg font-black rounded-xl hover:bg-white/10 transition-all">
                            SOLICITAR DEMO
                        </a>
                    </div>

                    <!-- Efficiency Bar -->
                    <div class="mt-24 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto py-8 border-y border-white/5">
                        <div class="text-center">
                            <p class="text-2xl font-black text-[#f1f5f9]">5x</p>
                            <p class="text-[10px] font-black text-[#94a3b8] uppercase tracking-widest">Recepción más rápida</p>
                        </div>
                        <div class="text-center border-l border-white/5">
                            <p class="text-2xl font-black text-[#f1f5f9]">0%</p>
                            <p class="text-[10px] font-black text-[#94a3b8] uppercase tracking-widest">Errores de Tracking</p>
                        </div>
                        <div class="text-center border-l border-white/5">
                            <p class="text-2xl font-black text-[#f1f5f9]">100%</p>
                            <p class="text-[10px] font-black text-[#94a3b8] uppercase tracking-widest">Aislamiento de Datos</p>
                        </div>
                        <div class="text-center border-l border-white/5">
                            <p class="text-2xl font-black text-[#f1f5f9]">24h</p>
                            <p class="text-[10px] font-black text-[#94a3b8] uppercase tracking-widest">Tiempo de Setup</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Feature Grid -->
            <section id="features" class="py-24 relative">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($features as $f)
                        <div class="p-10 bg-[#1e293b] rounded-3xl border border-white/5 hover:border-[#3b7ddd]/50 transition-all group">
                            <div class="w-12 h-12 bg-[#3b7ddd]/10 text-[#3b7ddd] rounded-xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                                <i class="fas {{ $f['icon'] }} text-xl"></i>
                            </div>
                            <h3 class="text-xl font-black text-[#f1f5f9] mb-4 uppercase tracking-tighter">{{ $f['title'] }}</h3>
                            <p class="text-[#94a3b8] text-sm leading-relaxed font-medium">
                                {{ $f['desc'] }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Product Preview -->
            <section class="py-24 bg-gradient-to-b from-[#0f172a] to-[#1e293b]/50">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-5xl font-black tracking-tighter text-[#f1f5f9] uppercase italic">Control Total en una sola pantalla</h2>
                    </div>
                    <div class="relative p-2 bg-[#1e293b] rounded-[2.5rem] border border-white/10 shadow-2xl overflow-hidden shadow-[#3b7ddd]/10">
                        <div class="bg-[#0f172a] rounded-[2rem] overflow-hidden border border-white/5">
                            <div class="h-8 bg-slate-800/80 border-b border-white/5 flex items-center px-6 gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-500/30"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-yellow-500/30"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-green-500/30"></div>
                                <div class="ml-4 h-3 w-48 bg-white/5 rounded-full"></div>
                            </div>
                            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=2000" alt="LogySaaS Dashboard Preview" class="w-full opacity-80 hover:opacity-100 transition-opacity duration-700 grayscale hover:grayscale-0">
                        </div>
                    </div>
                </div>
            </section>

            <!-- ROI Calculator (High Conversion) -->
            <section id="roi" class="py-24 bg-white text-[#0f172a]" x-data="{
                paquetes: 1500,
                costoManual: 2.00,
                get ahorro() { return Math.round(this.paquetes * (this.costoManual - 0.40)) }
            }">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                        <div>
                            <span class="text-[#3b7ddd] font-black uppercase tracking-[0.2em] text-xs">Calculadora de Eficiencia</span>
                            <h2 class="text-4xl md:text-6xl font-black tracking-tighter mt-4 mb-8 leading-none">Mira cuánto dinero estás <span class="text-red-500">perdiendo</span> hoy.</h2>
                            <p class="text-slate-500 text-lg font-medium mb-12">Nuestros clientes ahorran en promedio 120 horas de trabajo al mes eliminando la digitación manual y la revisión de Yappy.</p>

                            <div class="space-y-8">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Paquetes por Mes</label>
                                    <input type="range" min="500" max="10000" step="100" x-model="paquetes" class="w-full h-2 bg-slate-100 rounded-full appearance-none cursor-pointer accent-[#3b7ddd]">
                                    <div class="flex justify-between mt-2 text-xl font-black" x-text="paquetes.toLocaleString()"></div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Costo Operativo Manual ($)</label>
                                    <input type="range" min="1.00" max="5.00" step="0.50" x-model="costoManual" class="w-full h-2 bg-slate-100 rounded-full appearance-none cursor-pointer accent-[#3b7ddd]">
                                    <div class="flex justify-between mt-2 text-xl font-black" x-text="'$' + parseFloat(costoManual).toFixed(2)"></div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-[#0f172a] p-12 rounded-[3rem] text-white shadow-2xl relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-8">
                                <i class="fas fa-chart-line text-6xl text-white/5"></i>
                            </div>
                            <p class="text-[#94a3b8] font-black uppercase tracking-widest text-xs mb-2">Tu ahorro potencial con LogySaaS</p>
                            <p class="text-6xl md:text-8xl font-black tracking-tight text-[#3b7ddd]" x-text="'$' + ahorro.toLocaleString()"></p>
                            <p class="text-[#94a3b8] font-bold mt-4">Dólares ahorrados por mes en costos operativos.</p>

                            <div class="mt-12 pt-8 border-t border-white/10">
                                <a href="#contact" class="inline-flex items-center text-[#3b7ddd] font-black uppercase tracking-widest text-sm hover:translate-x-2 transition-transform">
                                    Recuperar mi dinero ahora <i class="fas fa-arrow-right ms-3"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Yappy / Local Focus -->
            <section class="py-24 bg-[#f1f5f9] border-y border-slate-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row items-center gap-16">
                        <div class="flex-1">
                            <div class="w-20 h-20 bg-[#3b7ddd] rounded-3xl flex items-center justify-center text-white text-4xl shadow-xl shadow-blue-500/20 mb-8">
                                <span class="font-black">Y</span>
                            </div>
                            <h2 class="text-4xl font-black tracking-tighter text-[#0f172a] mb-6 uppercase italic">Integración Nativa con Yappy</h2>
                            <p class="text-slate-600 text-lg leading-relaxed font-medium">
                                Olvida la revisión manual de capturas de pantalla. Nuestra pasarela inteligente para Panamá valida el pago de tus clientes al instante, liberando sus paquetes automáticamente.
                            </p>
                        </div>
                        <div class="flex-1 grid grid-cols-2 gap-4">
                            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 text-center">
                                <p class="text-3xl font-black text-[#3b7ddd] mb-1">ACH</p>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Multibank / General</p>
                            </div>
                            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 text-center">
                                <p class="text-3xl font-black text-[#3b7ddd] mb-1">Cards</p>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Visa / Master / Amex</p>
                            </div>
                            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 text-center col-span-2">
                                <p class="text-3xl font-black text-green-600 mb-1">WhatsApp</p>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Notificaciones Automáticas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials -->
            <section class="py-24 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-3xl md:text-5xl font-black tracking-tighter text-[#0f172a] uppercase italic mb-16">Confiado por Agencias Líderes</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="p-10 bg-[#f8fafc] rounded-[2.5rem] border border-slate-100 text-left">
                            <div class="flex text-amber-400 mb-6 gap-1"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                            <p class="text-slate-600 font-medium italic mb-8">"Implementamos LogySaaS en 48 horas y el OCR cambió nuestra vida. Recibimos 300 paquetes en 20 minutos."</p>
                            <p class="text-[#0f172a] font-black text-sm uppercase">Logy Express</p>
                        </div>
                        <div class="p-10 bg-[#0f172a] rounded-[2.5rem] text-left text-white shadow-2xl">
                            <div class="flex text-amber-400 mb-6 gap-1"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                            <p class="text-[#94a3b8] font-medium italic mb-8">"La conciliación de Yappy era un dolor de cabeza. Con LogySaaS el sistema lo hace solo. La mejor inversión de 2024."</p>
                            <p class="text-[#3b7ddd] font-black text-sm uppercase">Global Cargo</p>
                        </div>
                        <div class="p-10 bg-[#f8fafc] rounded-[2.5rem] border border-slate-100 text-left">
                            <div class="flex text-amber-400 mb-6 gap-1"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                            <p class="text-slate-600 font-medium italic mb-8">"Nuestros clientes aman el portal web. Las pre-alertas llegan solas y el tracking es en tiempo real."</p>
                            <p class="text-[#0f172a] font-black text-sm uppercase">Panama Courier</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Pricing -->
            <section id="pricing" class="py-24 bg-[#0f172a] text-[#f1f5f9]">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-20">
                        <h2 class="text-4xl md:text-6xl font-black tracking-tighter uppercase italic mb-6">Planes que escalan contigo</h2>
                        <p class="text-[#94a3b8] font-medium">Precios transparentes, sin cargos por paquete.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Startup -->
                        <div class="p-12 bg-[#1e293b] rounded-[3rem] border border-white/5 flex flex-col">
                            <h3 class="text-2xl font-black mb-2 uppercase tracking-tighter">Startup</h3>
                            <div class="flex items-baseline mb-8">
                                <span class="text-4xl font-black">$30</span>
                                <span class="text-[#94a3b8] text-sm font-bold ms-2">/mes</span>
                            </div>
                            <ul class="space-y-4 mb-12 flex-grow">
                                <li class="flex items-center text-sm font-bold text-[#94a3b8]"><i class="fas fa-check text-[#3b7ddd] me-3"></i> Recepción OCR</li>
                                <li class="flex items-center text-sm font-bold text-[#94a3b8]"><i class="fas fa-check text-[#3b7ddd] me-3"></i> Portal Clientes</li>
                                <li class="flex items-center text-sm font-bold text-[#94a3b8]"><i class="fas fa-check text-[#3b7ddd] me-3"></i> Inventario Racks</li>
                                <li class="flex items-center text-sm font-bold text-[#94a3b8]"><i class="fas fa-check text-[#3b7ddd] me-3"></i> Facturación Auto</li>
                            </ul>
                            <a href="#contact" class="block text-center py-4 bg-white/5 border border-white/10 rounded-xl font-black hover:bg-white/10 transition-all uppercase tracking-widest text-xs">Empezar Ahora</a>
                        </div>

                        <!-- Business -->
                        <div class="p-12 bg-gradient-to-br from-[#3b7ddd] to-indigo-700 rounded-[3rem] flex flex-col shadow-2xl shadow-blue-500/20 transform scale-105 relative z-20">
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white text-[#0f172a] text-[10px] font-black px-6 py-2 rounded-full uppercase tracking-[0.2em] shadow-lg">Más Popular</div>
                            <h3 class="text-2xl font-black mb-2 uppercase tracking-tighter">Business</h3>
                            <div class="flex items-baseline mb-8">
                                <span class="text-4xl font-black">$45</span>
                                <span class="text-white/70 text-sm font-bold ms-2">/mes</span>
                            </div>
                            <ul class="space-y-4 mb-12 flex-grow">
                                <li class="flex items-center text-sm font-black text-white"><i class="fas fa-check text-white me-3"></i> Todo en Startup</li>
                                <li class="flex items-center text-sm font-black text-white"><i class="fas fa-check text-white me-3"></i> Bot WhatsApp</li>
                                <li class="flex items-center text-sm font-black text-white"><i class="fas fa-check text-white me-3"></i> Fidelización</li>
                                <li class="flex items-center text-sm font-black text-white"><i class="fas fa-check text-white me-3"></i> Marca Blanca Full</li>
                            </ul>
                            <a href="#contact" class="block text-center py-4 bg-white text-[#0f172a] rounded-xl font-black hover:bg-slate-100 transition-all uppercase tracking-widest text-xs">Solicitar Demo</a>
                        </div>

                        <!-- Enterprise -->
                        <div class="p-12 bg-[#1e293b] rounded-[3rem] border border-white/5 flex flex-col">
                            <h3 class="text-2xl font-black mb-2 uppercase tracking-tighter">Enterprise</h3>
                            <div class="flex items-baseline mb-8">
                                <span class="text-4xl font-black">$55</span>
                                <span class="text-[#94a3b8] text-sm font-bold ms-2">/mes</span>
                            </div>
                            <ul class="space-y-4 mb-12 flex-grow">
                                <li class="flex items-center text-sm font-bold text-[#94a3b8]"><i class="fas fa-check text-[#3b7ddd] me-3"></i> Todo en Business</li>
                                <li class="flex items-center text-sm font-bold text-[#94a3b8]"><i class="fas fa-check text-[#3b7ddd] me-3"></i> App Móvil Nativa</li>
                                <li class="flex items-center text-sm font-bold text-[#94a3b8]"><i class="fas fa-check text-[#3b7ddd] me-3"></i> Multisupervisores</li>
                                <li class="flex items-center text-sm font-bold text-[#94a3b8]"><i class="fas fa-check text-[#3b7ddd] me-3"></i> Soporte VIP 24/7</li>
                            </ul>
                            <a href="#contact" class="block text-center py-4 bg-white/5 border border-white/10 rounded-xl font-black hover:bg-white/10 transition-all uppercase tracking-widest text-xs">Hablar con Ventas</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ -->
            <section id="faq" class="py-24 bg-white text-[#0f172a]">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-black tracking-tighter uppercase italic">Preguntas Frecuentes</h2>
                    </div>
                    <div class="space-y-6" x-data="{ open: null }">
                        <div class="border-b border-slate-100 pb-6">
                            <button @click="open = open === 1 ? null : 1" class="w-full text-left flex justify-between items-center group">
                                <span class="text-lg font-black uppercase tracking-tight group-hover:text-[#3b7ddd] transition-colors">¿Realmente toma 48 horas el setup?</span>
                                <i class="fas fa-plus text-[#3b7ddd]" :class="open === 1 ? 'rotate-45' : ''"></i>
                            </button>
                            <div x-show="open === 1" x-collapse class="mt-4 text-[#94a3b8] font-medium leading-relaxed">
                                Sí. Una vez contratado, nuestro equipo técnico configura tu instancia, tu dominio y tu marca blanca en menos de 48 horas laborales. Estarás operando al tercer día.
                            </div>
                        </div>
                        <div class="border-b border-slate-100 pb-6">
                            <button @click="open = open === 2 ? null : 2" class="w-full text-left flex justify-between items-center group">
                                <span class="text-lg font-black uppercase tracking-tight group-hover:text-[#3b7ddd] transition-colors">¿Cómo funciona la lectura de facturas?</span>
                                <i class="fas fa-plus text-[#3b7ddd]" :class="open === 2 ? 'rotate-45' : ''"></i>
                            </button>
                            <div x-show="open === 2" x-collapse class="mt-4 text-[#94a3b8] font-medium leading-relaxed">
                                Utilizamos visión artificial (OCR) para leer archivos PDF o fotos de etiquetas. El sistema extrae el tracking, el peso y lo asocia automáticamente al cliente, eliminando errores de digitación.
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Final CTA -->
            <section id="contact" class="py-24 relative overflow-hidden bg-[#3b7ddd]">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0" style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 20px 20px;"></div>
                </div>
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 text-white">
                    <h2 class="text-5xl md:text-7xl font-black tracking-tighter uppercase italic mb-8 leading-none">Domina el mercado de Couriers hoy</h2>
                    <p class="text-xl font-bold mb-12 opacity-90">Únete a las agencias que están escalando sin estrés operativo.</p>
                    <div class="bg-white p-1 md:p-2 rounded-2xl shadow-2xl flex flex-col md:flex-row gap-2 max-w-2xl mx-auto">
                        <input type="email" placeholder="tu@empresa.com" class="flex-grow px-8 py-4 text-[#0f172a] font-bold focus:outline-none rounded-xl">
                        <button class="bg-[#0f172a] text-white px-10 py-4 rounded-xl font-black uppercase tracking-widest hover:bg-[#1e293b] transition-all">Empezar Gratis</button>
                    </div>
                    <p class="mt-8 text-sm font-bold opacity-75">30 días de prueba · Sin tarjeta de crédito · Soporte 24/7</p>
                </div>
            </section>
        </div>
    </div>
</x-layouts.landing>
