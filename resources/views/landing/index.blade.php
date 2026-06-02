<x-layouts.landing>
    <!-- GLOBAL PARALLAX BACKGROUND -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <!-- Main World Map -->
        <div class="absolute inset-0 opacity-[0.05] scale-125" id="global-parallax-bg">
            <img src="https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&q=80&w=2400" alt="World Map Background" class="w-full h-full object-cover">
        </div>

        <!-- Interactive Blobs -->
        <div class="parallax-element absolute top-[-10%] left-[-5%] w-[800px] h-[800px] bg-blue-100/40 rounded-full blur-[120px]" data-speed="0.03"></div>
        <div class="parallax-element absolute top-[40%] right-[-10%] w-[600px] h-[600px] bg-indigo-100/30 rounded-full blur-[120px]" data-speed="0.06"></div>
        <div class="parallax-element absolute bottom-[-10%] left-[10%] w-[700px] h-[700px] bg-purple-50/50 rounded-full blur-[120px]" data-speed="0.04"></div>
    </div>

    <div class="relative z-10">
        <!-- Hero Section -->
        <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center">
                    <div class="hero-badge inline-flex items-center px-4 py-1.5 rounded-full bg-blue-50 text-blue-700 text-sm font-bold mb-8 border border-blue-100 uppercase tracking-wider shadow-sm">
                        <span class="flex h-2 w-2 rounded-full bg-blue-600 mr-2 animate-ping"></span>
                        LogiSaaS: La evolución de la logística digital
                    </div>
                    <h1 class="hero-title text-6xl md:text-8xl font-black tracking-tighter text-slate-900 mb-8 leading-[0.95]">
                        Tu Courier, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600">Sin Límites</span>
                    </h1>
                    <p class="hero-subtitle max-w-3xl mx-auto text-xl md:text-2xl text-slate-600 mb-12 leading-relaxed font-medium">
                        Una plataforma SaaS integral diseñada para transformar operaciones logísticas locales en potencias internacionales. White-label, automatización y escalabilidad en un solo lugar.
                    </p>
                    <div class="hero-buttons flex flex-col sm:flex-row justify-center gap-6">
                        <a href="#pricing" class="inline-flex items-center justify-center px-10 py-5 border border-transparent text-xl font-bold rounded-full text-white bg-blue-600 hover:bg-blue-700 shadow-2xl shadow-blue-300 transition-all transform hover:-translate-y-1 hover:scale-105">
                            Ver Planes
                        </a>
                        <a href="#contact" class="inline-flex items-center justify-center px-10 py-5 border-2 border-slate-200 text-xl font-bold rounded-full text-slate-700 bg-white/80 backdrop-blur hover:bg-slate-50 shadow-sm transition-all transform hover:-translate-y-1">
                            Solicitar Demo
                        </a>
                    </div>

                    <!-- Product Showcase -->
                    <div class="hero-mockup mt-24 relative max-w-6xl mx-auto">
                        <div class="absolute -inset-10 bg-gradient-to-r from-blue-500/10 via-indigo-500/10 to-purple-500/10 rounded-[4rem] blur-3xl z-0"></div>
                        <div class="relative bg-slate-900 rounded-[3rem] p-3 shadow-[0_40px_100px_-15px_rgba(0,0,0,0.3)] border border-white/20 overflow-hidden transform perspective-2000">
                            <div class="bg-slate-800 rounded-[2rem] overflow-hidden border border-slate-700">
                                <div class="h-12 bg-slate-900/80 border-b border-slate-700 flex items-center px-8 gap-2 backdrop-blur">
                                    <div class="w-3.5 h-3.5 rounded-full bg-red-500/40"></div>
                                    <div class="w-3.5 h-3.5 rounded-full bg-yellow-500/40"></div>
                                    <div class="w-3.5 h-3.5 rounded-full bg-green-500/40"></div>
                                    <div class="ml-6 h-5 w-80 bg-slate-700/30 rounded-full"></div>
                                </div>
                                <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=2400" alt="Admin Dashboard" class="w-full opacity-95 group-hover:opacity-100 transition-opacity duration-1000">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trust Section -->
        <div class="py-20 bg-slate-50/50 backdrop-blur-sm border-y border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-xs font-black text-slate-400 uppercase tracking-[0.4em] mb-12">Liderando la industria logística en la región</p>
                <div class="flex flex-wrap justify-center items-center gap-16 md:gap-32 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
                    <span class="text-3xl font-black text-slate-900 italic">LOGY EXPRESS</span>
                    <span class="text-3xl font-black text-slate-900 italic">FASTBOX.PA</span>
                    <span class="text-3xl font-black text-slate-900 italic">GLOBAL CARGO</span>
                    <span class="text-3xl font-black text-slate-900 italic">PANAMA COURIER</span>
                </div>
            </div>
        </div>

        <!-- Features Bento Grid -->
        <div id="features" class="py-32 bg-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-24 reveal">
                    <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-4">Potencial Ilimitado</h2>
                    <h3 class="text-5xl md:text-7xl font-black text-slate-900 mb-8 tracking-tighter">Todo lo que necesitas <br>para dominar el mercado</h3>
                    <p class="max-w-2xl mx-auto text-slate-600 text-lg">Módulos robustos diseñados por expertos en logística internacional.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 h-auto">
                    <!-- Smart Reception -->
                    <div class="md:col-span-8 bg-slate-50 p-12 rounded-[3rem] border border-slate-200 overflow-hidden relative group reveal">
                        <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-blue-100/50 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-1000"></div>
                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center mb-8 shadow-xl shadow-blue-200">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            </div>
                            <h3 class="text-4xl font-black text-slate-900 mb-6 tracking-tight">Recepción Inteligente 2.0</h3>
                            <p class="text-slate-600 text-xl leading-relaxed max-w-lg">Procesa miles de paquetes sin errores. Extracción automática de trackings vía OCR, validación de clientes y pesaje sincronizado en tiempo real.</p>
                        </div>
                    </div>

                    <!-- CRM & Loyalty -->
                    <div class="md:col-span-4 bg-slate-900 p-12 rounded-[3rem] text-white relative group overflow-hidden reveal">
                        <div class="w-16 h-16 bg-indigo-500 rounded-2xl flex items-center justify-center mb-8 shadow-xl shadow-indigo-500/50">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-black mb-6 tracking-tight">Fidelización Activa</h3>
                        <p class="text-slate-400 text-lg leading-relaxed">Sistema de puntos por libra y niveles de cliente para maximizar la retención.</p>
                    </div>

                    <!-- Customer Portal -->
                    <div class="md:col-span-5 bg-white p-12 rounded-[3rem] border border-slate-200 shadow-sm reveal">
                         <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-8">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 mb-6 tracking-tight">Experiencia del Cliente</h3>
                        <p class="text-slate-600 text-lg leading-relaxed">Un portal moderno donde tus clientes gestionan pre-alertas, pagos y tracking con un solo clic.</p>
                    </div>

                    <!-- Payments & Automation -->
                    <div class="md:col-span-7 bg-blue-600 p-12 rounded-[3rem] text-white relative group overflow-hidden reveal">
                        <div class="absolute -right-10 top-0 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                        <h3 class="text-4xl font-black mb-6 tracking-tight">Facturación & Pagos</h3>
                        <p class="text-blue-100 text-xl leading-relaxed max-w-md">Integración directa con Yappy, PayPal y Stripe. Olvídate de los errores de cobro y automatiza tus flujos de caja.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Solutions Section (New) -->
        <div id="solutions" class="py-32 bg-slate-50 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-24 reveal">
                    <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-4">Soluciones para cada etapa</h2>
                    <h3 class="text-5xl font-black text-slate-900 tracking-tighter">Adaptado a tu modelo de negocio</h3>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <div class="bg-white p-12 rounded-[3rem] border border-slate-200 shadow-sm reveal">
                        <h4 class="text-2xl font-black text-slate-900 mb-6 italic">Casilleros Virtuales</h4>
                        <p class="text-slate-600 leading-relaxed">Ideal para agencias que ofrecen dirección en USA. Gestión automática de Locker ID y notificaciones de llegada.</p>
                    </div>
                    <div class="bg-white p-12 rounded-[3rem] border border-slate-200 shadow-sm reveal" data-delay="0.1">
                        <h4 class="text-2xl font-black text-slate-900 mb-6 italic">Agencias de Carga</h4>
                        <p class="text-slate-600 leading-relaxed">Consolidación, reempaque y manejo de carga pesada con reportes de inventario exhaustivos.</p>
                    </div>
                    <div class="bg-white p-12 rounded-[3rem] border border-slate-200 shadow-sm reveal" data-delay="0.2">
                        <h4 class="text-2xl font-black text-slate-900 mb-6 italic">Última Milla</h4>
                        <p class="text-slate-600 leading-relaxed">Optimización de rutas de entrega local, firmas digitales y gestión de mensajeros.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing Section -->
        <div id="pricing" class="py-32 bg-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-24 reveal">
                    <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-4">Transparencia Total</h2>
                    <p class="text-5xl md:text-7xl font-black text-slate-900 mb-8 tracking-tighter">Planes que crecen contigo</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Startup Plan -->
                    <div class="pricing-card p-12 rounded-[3.5rem] border border-slate-200 bg-white flex flex-col transition-all hover:border-blue-600 group reveal">
                        <h3 class="text-2xl font-black text-slate-900 mb-2">Startup</h3>
                        <p class="text-slate-500 text-sm mb-8">Para agencias nuevas que están despegando.</p>
                        <div class="mb-10">
                            <span class="text-6xl font-black text-slate-900 tracking-tighter">$49</span>
                            <span class="text-slate-400 font-bold">/mes</span>
                        </div>
                        <ul class="space-y-6 mb-12 flex-grow">
                            <li class="flex items-center text-slate-600 font-bold"><svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Hasta 500 paquetes/mes</li>
                            <li class="flex items-center text-slate-600 font-bold"><svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 1 Sucursal (Miami + Local)</li>
                        </ul>
                        <a href="#contact" class="block text-center py-5 px-8 rounded-full border-2 border-slate-900 font-black text-slate-900 group-hover:bg-slate-900 group-hover:text-white transition-all">Empezar ahora</a>
                    </div>

                    <!-- Business Plan -->
                    <div class="pricing-card p-12 rounded-[3.5rem] bg-slate-900 flex flex-col shadow-2xl shadow-blue-900/40 relative transition-all transform hover:-translate-y-2 text-white reveal">
                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-blue-600 text-white text-xs font-black px-8 py-2 rounded-full uppercase tracking-[0.2em] shadow-lg">RECOMENDADO</div>
                        <h3 class="text-2xl font-black mb-2">Business</h3>
                        <p class="text-slate-400 text-sm mb-8">La solución completa para potencias regionales.</p>
                        <div class="mb-10">
                            <span class="text-6xl font-black tracking-tighter">$149</span>
                            <span class="text-slate-400 font-bold">/mes</span>
                        </div>
                        <ul class="space-y-6 mb-12 flex-grow">
                            <li class="flex items-center text-slate-300 font-bold"><svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Paquetes Ilimitados</li>
                            <li class="flex items-center text-slate-300 font-bold"><svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Fidelización Activa</li>
                            <li class="flex items-center text-slate-300 font-bold"><svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Marca Blanca Total</li>
                        </ul>
                        <a href="#contact" class="block text-center py-5 px-8 rounded-full bg-blue-600 text-white font-black hover:bg-blue-700 shadow-xl shadow-blue-600/30 transition-all">Solicitar Demo</a>
                    </div>

                    <!-- Enterprise Plan -->
                    <div class="pricing-card p-12 rounded-[3.5rem] border border-slate-200 bg-white flex flex-col transition-all hover:border-blue-600 group reveal">
                        <h3 class="text-2xl font-black text-slate-900 mb-2">Enterprise</h3>
                        <p class="text-slate-500 text-sm mb-8">Para redes multi-país y flujos corporativos.</p>
                        <div class="mb-10 h-20 flex flex-col justify-center">
                            <h4 class="text-4xl font-black text-blue-600">Contáctanos</h4>
                        </div>
                        <ul class="space-y-6 mb-12 flex-grow">
                            <li class="flex items-center text-slate-600 font-bold"><svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> API & Webhooks Access</li>
                            <li class="flex items-center text-slate-600 font-bold"><svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Soporte Prioritario 24/7</li>
                        </ul>
                        <a href="#contact" class="block text-center py-5 px-8 rounded-full border-2 border-slate-900 font-black text-slate-900 group-hover:bg-slate-900 group-hover:text-white transition-all">Hablar con Ventas</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div id="faq" class="py-32 bg-slate-50/80 backdrop-blur-sm">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-24 reveal">
                    <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-4">Soporte y Recursos</h2>
                    <h3 class="text-5xl font-black text-slate-900 tracking-tighter">Despeja tus dudas</h3>
                </div>

                <div class="space-y-4 reveal">
                    <div x-data="{ open: false }" class="bg-white rounded-3xl border border-slate-200 overflow-hidden transition-all shadow-sm hover:shadow-md">
                        <button @click="open = !open" class="w-full px-10 py-8 text-left flex justify-between items-center group">
                            <span class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors">¿Cómo funciona la marca blanca?</span>
                            <svg class="w-6 h-6 text-slate-400 transition-transform duration-500" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-cloak class="px-10 pb-8 text-slate-600 text-lg leading-relaxed">
                            Podrás configurar tu logo, colores corporativos y conectar tu propio dominio. Tus clientes entrarán a "tu-empresa.com" y verán una plataforma que parece desarrollada 100% por ti.
                        </div>
                    </div>

                    <div x-data="{ open: false }" class="bg-white rounded-3xl border border-slate-200 overflow-hidden transition-all shadow-sm hover:shadow-md">
                        <button @click="open = !open" class="w-full px-10 py-8 text-left flex justify-between items-center group">
                            <span class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors">¿Es compatible con bodegas en Miami?</span>
                            <svg class="w-6 h-6 text-slate-400 transition-transform duration-500" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-cloak class="px-10 pb-8 text-slate-600 text-lg leading-relaxed">
                            Sí, está diseñado específicamente para el flujo Miami -> Latinoamérica. Permite gestionar recepciones en el extranjero y tránsitos internacionales hasta el destino final.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Integrations Section (New) -->
        <div class="py-32 bg-slate-900 text-white relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                <div class="absolute top-[-20%] right-[-10%] w-[800px] h-[800px] bg-blue-600 rounded-full blur-[150px]"></div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                    <div class="reveal">
                        <h2 class="text-blue-500 font-bold uppercase tracking-widest text-sm mb-4">Ecosistema Conectado</h2>
                        <h3 class="text-5xl font-black mb-8 tracking-tighter leading-tight">Integraciones que impulsan tu crecimiento</h3>
                        <p class="text-slate-400 text-xl leading-relaxed mb-12">LogiSaaS no es una isla. Se conecta con los gigantes del e-commerce y pagos para que tu operación nunca se detenga.</p>

                        <div class="grid grid-cols-2 gap-8">
                            <div class="bg-white/5 p-6 rounded-3xl border border-white/10 flex items-center gap-4">
                                <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center font-bold text-blue-400">AMZ</div>
                                <span class="font-bold">Amazon OCR</span>
                            </div>
                            <div class="bg-white/5 p-6 rounded-3xl border border-white/10 flex items-center gap-4">
                                <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center font-bold text-blue-400">STR</div>
                                <span class="font-bold">Stripe</span>
                            </div>
                            <div class="bg-white/5 p-6 rounded-3xl border border-white/10 flex items-center gap-4">
                                <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center font-bold text-blue-400">PYP</div>
                                <span class="font-bold">PayPal</span>
                            </div>
                            <div class="bg-white/5 p-6 rounded-3xl border border-white/10 flex items-center gap-4">
                                <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center font-bold text-blue-400">YPY</div>
                                <span class="font-bold">Yappy PA</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative reveal">
                        <div class="grid grid-cols-3 gap-4 animate-pulse-slow">
                            @for($i=0; $i<9; $i++)
                            <div class="aspect-square bg-white/5 rounded-3xl border border-white/10 flex items-center justify-center">
                                <div class="w-12 h-12 bg-blue-600/20 rounded-2xl"></div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div id="contact" class="py-32 bg-slate-900 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                <div class="absolute top-0 left-0 w-96 h-96 bg-blue-600 rounded-full blur-[120px]"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-600 rounded-full blur-[120px]"></div>
            </div>

            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="bg-white rounded-[4rem] p-12 md:p-24 shadow-2xl">
                    <div class="text-center mb-16">
                        <h2 class="text-5xl font-black text-slate-900 mb-6 tracking-tighter">Construyamos el futuro de tu logística</h2>
                        <p class="text-slate-600 text-xl font-medium">Déjanos tus datos y un especialista te contactará hoy mismo.</p>
                    </div>

                    <livewire:public.contact-form />
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            if (typeof gsap === 'undefined') return;

            gsap.registerPlugin(ScrollTrigger);

            // Hero Animation
            const tl = gsap.timeline();
            tl.from(".hero-badge", { duration: 0.8, y: -20, opacity: 0, ease: "back.out(1.7)" })
              .from(".hero-title", { duration: 1, y: 50, opacity: 0, ease: "power4.out" }, "-=0.5")
              .from(".hero-subtitle", { duration: 1, y: 30, opacity: 0, ease: "power4.out" }, "-=0.7")
              .from(".hero-buttons", { duration: 1, y: 20, opacity: 0, ease: "power4.out" }, "-=0.7")
              .from(".hero-mockup", { duration: 1.5, scale: 0.95, opacity: 0, ease: "power4.out" }, "-=0.8");

            // Global Scroll Parallax
            gsap.to("#global-parallax-bg", {
                scrollTrigger: {
                    trigger: "body",
                    start: "top top",
                    end: "bottom bottom",
                    scrub: 1.5
                },
                y: 600,
                ease: "none"
            });

            // Reveal Sections
            gsap.utils.toArray('.reveal').forEach(elem => {
                gsap.from(elem, {
                    scrollTrigger: {
                        trigger: elem,
                        start: "top 85%",
                    },
                    duration: 1.2,
                    y: 60,
                    opacity: 0,
                    ease: "power3.out"
                });
            });

            // Mouse Move Parallax
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
        });
    </script>
</x-layouts.landing>
