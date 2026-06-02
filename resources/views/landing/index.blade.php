<x-layouts.landing>
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-white pt-32 pb-20 lg:pt-48 lg:pb-32">
        <!-- Parallax Background Elements -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
            <!-- Main Background Image with Parallax (Fixed Visibility) -->
            <div class="parallax-element absolute inset-0 z-0 opacity-10" data-speed="0.02">
                <img src="https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&q=80&w=2000" alt="World Map Background" class="w-full h-full object-cover">
            </div>

            <!-- Animated Blobs -->
            <div class="parallax-element absolute top-[10%] left-[5%] w-64 h-64 bg-blue-100 rounded-full blur-3xl opacity-60" data-speed="0.05"></div>
            <div class="parallax-element absolute top-[40%] right-[10%] w-96 h-96 bg-indigo-100 rounded-full blur-3xl opacity-60" data-speed="0.1"></div>
            <div class="parallax-element absolute bottom-[10%] left-[15%] w-80 h-80 bg-purple-50 rounded-full blur-3xl opacity-60" data-speed="0.08"></div>

            <!-- Floating Decorative Shapes -->
            <div class="parallax-element absolute top-1/4 right-1/4 w-12 h-12 bg-blue-500/10 rounded-lg rotate-12" data-speed="0.15"></div>
            <div class="parallax-element absolute bottom-1/3 left-1/4 w-16 h-16 border-2 border-indigo-500/10 rounded-full" data-speed="0.12"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <div class="hero-badge inline-flex items-center px-4 py-1.5 rounded-full bg-blue-50 text-blue-700 text-sm font-bold mb-6 border border-blue-100 uppercase tracking-wider">
                    <span class="flex h-2 w-2 rounded-full bg-blue-600 mr-2 animate-ping"></span>
                    Nuevo: Recepción Inteligente 2.0
                </div>
                <h1 class="hero-title text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 mb-6 leading-[1.1]">
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
                <div class="hero-mockup mt-20 relative max-w-5xl mx-auto">
                    <div class="absolute -inset-4 bg-gradient-to-r from-blue-500/20 to-indigo-500/20 rounded-[2.5rem] blur-2xl z-0"></div>
                    <div class="relative bg-slate-900 rounded-[2rem] p-2 shadow-2xl border border-white/10 overflow-hidden transform perspective-1000">
                        <div class="bg-slate-800 rounded-[1.5rem] overflow-hidden border border-slate-700">
                            <div class="h-10 bg-slate-800 border-b border-slate-700 flex items-center px-6 gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-500/40"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500/40"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500/40"></div>
                                <div class="ml-4 h-4 w-64 bg-slate-700/50 rounded-full"></div>
                            </div>
                            <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=2000" alt="Dashboard Analytics" class="w-full opacity-90 hover:opacity-100 transition-opacity duration-700">
                        </div>
                    </div>

                    <!-- Floating Stat Cards -->
                    <div class="parallax-element absolute -right-8 top-1/4 bg-white p-4 rounded-2xl shadow-xl border border-slate-100 hidden lg:block" data-speed="0.2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500 font-bold uppercase">ROI Promedio</div>
                                <div class="text-lg font-black text-slate-900">+42%</div>
                            </div>
                        </div>
                    </div>

                    <div class="parallax-element absolute -left-12 bottom-1/4 bg-white p-4 rounded-2xl shadow-xl border border-slate-100 hidden lg:block" data-speed="0.15">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500 font-bold uppercase">Paquetes Hoy</div>
                                <div class="text-lg font-black text-slate-900">1,284</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trust Section -->
    <div class="py-12 bg-white border-y border-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm font-bold text-slate-400 uppercase tracking-[0.2em] mb-8">Empresas que confían en nuestra tecnología</p>
            <div class="flex flex-wrap justify-center gap-12 md:gap-24 opacity-40 grayscale hover:grayscale-0 transition-all duration-500">
                <span class="text-2xl font-black text-slate-900 italic">LOGY EXPRESS</span>
                <span class="text-2xl font-black text-slate-900">FASTBOX</span>
                <span class="text-2xl font-black text-slate-900">GLOBAL CARGO</span>
                <span class="text-2xl font-black text-slate-900">PANAMA COURIER</span>
            </div>
        </div>
    </div>

    <!-- Features Bento Grid -->
    <div id="features" class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-[800px] h-[800px] border border-blue-100 rounded-full opacity-20 pointer-events-none" id="parallax-circle-1"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20 reveal">
                <h2 class="text-base font-bold text-blue-600 uppercase tracking-widest mb-2">Módulos de Clase Mundial</h2>
                <p class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-6">Todo lo que necesitas en una sola caja</p>
                <p class="max-w-2xl mx-auto text-slate-600">Hemos condensado años de experiencia logística en herramientas intuitivas que potencian cada área de tu negocio.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-6 md:grid-rows-2 gap-6 h-auto">
                <!-- Smart Reception -->
                <div class="md:col-span-3 bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl transition-all reveal group overflow-hidden relative">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-200">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Recepción Inteligente</h3>
                        <p class="text-slate-600 mb-6">Nuestro motor OCR procesa facturas y etiquetas de Amazon/UPS en milisegundos. Escanea, pesa y notifica en un solo paso.</p>
                        <ul class="space-y-2">
                            <li class="flex items-center text-sm text-slate-500 font-medium"><svg class="w-4 h-4 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Extracción de Tracking vía IA</li>
                            <li class="flex items-center text-sm text-slate-500 font-medium"><svg class="w-4 h-4 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Registro Masivo de Manifiestos</li>
                        </ul>
                    </div>
                </div>

                <!-- Customer Portal -->
                <div class="md:col-span-3 bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl transition-all reveal delay-100 group overflow-hidden relative">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-indigo-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-indigo-200">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Portal de Clientes</h3>
                        <p class="text-slate-600 mb-6">Ofrece a tus clientes una experiencia Premium. Pre-alertas, calculadora de fletes y billetera digital en una interfaz móvil intuitiva.</p>
                        <ul class="space-y-2">
                            <li class="flex items-center text-sm text-slate-500 font-medium"><svg class="w-4 h-4 text-indigo-600 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Seguimiento en Tiempo Real</li>
                            <li class="flex items-center text-sm text-slate-500 font-medium"><svg class="w-4 h-4 text-indigo-600 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Autogestión de Perfil y Pagos</li>
                        </ul>
                    </div>
                </div>

                <!-- Loyalty 2.0 -->
                <div class="md:col-span-2 bg-slate-900 p-8 rounded-[2.5rem] shadow-xl hover:shadow-2xl transition-all reveal group text-white">
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-orange-500 text-white rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Fidelización 2.0</h3>
                    <p class="text-slate-400">Sistema de puntos y niveles gamificado. Convierte a clientes ocasionales en fans leales de tu marca.</p>
                </div>

                <!-- FinTech & Payments -->
                <div class="md:col-span-4 bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl transition-all reveal delay-200 group flex flex-col md:flex-row gap-8 items-center">
                    <div class="flex-grow">
                        <div class="w-14 h-14 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1m4.99-4a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Facturación y Pagos</h3>
                        <p class="text-slate-600">Integración nativa con Yappy, PayPal y Stripe. Genera facturas automáticas y gestiona estados de cuenta sin esfuerzo.</p>
                    </div>
                    <div class="flex-shrink-0 bg-slate-50 p-6 rounded-3xl border border-slate-100 w-full md:w-48 text-center">
                        <div class="text-3xl font-black text-slate-900 mb-1">$0.00</div>
                        <div class="text-xs font-bold text-slate-400 uppercase">Cero Fugas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Operations Insight Section -->
    <div class="py-24 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div class="reveal">
                    <h2 class="text-base font-bold text-blue-600 uppercase tracking-widest mb-4">Eficiencia Operativa</h2>
                    <h3 class="text-4xl font-extrabold text-slate-900 mb-6 leading-tight">Diseñado para la vida real en bodega</h3>

                    <div class="space-y-8">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold">01</div>
                            <div>
                                <h4 class="text-xl font-bold text-slate-900 mb-2">Reducción drástica de errores</h4>
                                <p class="text-slate-600">Elimina el ingreso manual. Nuestro sistema valida trackings, identifica clientes por ID de casillero y alerta si un paquete no estaba esperado.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold">02</div>
                            <div>
                                <h4 class="text-xl font-bold text-slate-900 mb-2">Marca Blanca 100% Personalizable</h4>
                                <p class="text-slate-600">Configura tus propios colores, sube tu logo y usa tu propio dominio (CNAME). La plataforma es totalmente tuya ante los ojos del cliente.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center font-bold">03</div>
                            <div>
                                <h4 class="text-xl font-bold text-slate-900 mb-2">Gestión de Casilleros Automática</h4>
                                <p class="text-slate-600">Asignación instantánea de direcciones en Miami y Panamá. El sistema genera el número de casillero (Locker ID) al momento del registro.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative reveal">
                    <div class="absolute -inset-10 bg-blue-100 rounded-full blur-3xl opacity-30 z-0"></div>
                    <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&q=80&w=1200" alt="Warehouse Efficiency" class="relative z-10 rounded-[2.5rem] shadow-2xl border-8 border-white">
                    <div class="absolute -bottom-6 -right-6 bg-blue-600 text-white p-8 rounded-3xl shadow-xl z-20 hidden md:block">
                        <div class="text-4xl font-black mb-1">99.9%</div>
                        <div class="text-sm font-bold uppercase tracking-wider opacity-80">Precisión en Inventario</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section (New) -->
    <div class="py-24 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal">
                <h2 class="text-base font-bold text-blue-600 uppercase tracking-widest mb-2">Preguntas Frecuentes</h2>
                <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900">¿Tienes dudas? Nosotros las resolvemos</h3>
            </div>

            <div class="space-y-4 reveal">
                <div x-data="{ open: false }" class="bg-white rounded-2xl border border-slate-200 overflow-hidden transition-all">
                    <button @click="open = !open" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                        <span class="font-bold text-slate-900 group-hover:text-blue-600 transition-colors">¿Cómo funciona la marca blanca?</span>
                        <svg class="w-5 h-5 text-slate-400 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-cloak class="px-8 pb-6 text-slate-600 leading-relaxed">
                        Podrás configurar tu logo, colores corporativos y conectar tu propio dominio. Tus clientes entrarán a "tu-empresa.com" y verán una plataforma que parece desarrollada 100% por ti.
                    </div>
                </div>

                <div x-data="{ open: false }" class="bg-white rounded-2xl border border-slate-200 overflow-hidden transition-all">
                    <button @click="open = !open" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                        <span class="font-bold text-slate-900 group-hover:text-blue-600 transition-colors">¿Necesito conocimientos técnicos para empezar?</span>
                        <svg class="w-5 h-5 text-slate-400 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-cloak class="px-8 pb-6 text-slate-600 leading-relaxed">
                        Para nada. LogiSaaS es una solución "Plug & Play". Nosotros nos encargamos de la infraestructura, seguridad y actualizaciones. Tú solo te preocupas de mover carga.
                    </div>
                </div>

                <div x-data="{ open: false }" class="bg-white rounded-2xl border border-slate-200 overflow-hidden transition-all">
                    <button @click="open = !open" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                        <span class="font-bold text-slate-900 group-hover:text-blue-600 transition-colors">¿Es compatible con bodegas en Miami?</span>
                        <svg class="w-5 h-5 text-slate-400 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-cloak class="px-8 pb-6 text-slate-600 leading-relaxed">
                        Sí, está diseñado específicamente para el flujo Miami -> Latinoamérica. Permite gestionar recepciones en el extranjero y tránsitos internacionales hasta el destino final.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Section -->
    <div id="pricing" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal">
                <h2 class="text-base font-bold text-blue-600 uppercase tracking-widest mb-2">Planes y Precios</h2>
                <p class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-6">Escala a tu propio ritmo</p>
                <p class="mt-4 text-slate-600">Sin costos ocultos. Cancela cuando quieras.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Startup Plan -->
                <div class="pricing-card p-10 rounded-[2.5rem] border border-slate-200 bg-white flex flex-col transition-all hover:border-blue-200 hover:shadow-xl">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Startup</h3>
                    <p class="text-slate-500 text-sm mb-6">Para agencias nuevas que están despegando.</p>
                    <div class="mb-6">
                        <span class="text-5xl font-extrabold text-slate-900 tracking-tighter">$49</span>
                        <span class="text-slate-500 font-medium">/mes</span>
                    </div>
                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-center text-sm text-slate-600 font-medium">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Hasta 500 paquetes/mes
                        </li>
                        <li class="flex items-center text-sm text-slate-600 font-medium">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            1 Sucursal (Miami + Local)
                        </li>
                        <li class="flex items-center text-sm text-slate-600 font-medium">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Portal de Clientes Básico
                        </li>
                    </ul>
                    <a href="#contact" class="block text-center py-4 px-6 rounded-full border-2 border-slate-900 font-bold text-slate-900 hover:bg-slate-900 hover:text-white transition-all">Empezar ahora</a>
                </div>

                <!-- Business Plan -->
                <div class="pricing-card p-10 rounded-[2.5rem] bg-slate-900 flex flex-col shadow-2xl shadow-blue-900/20 relative transition-all transform hover:-translate-y-2 text-white">
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-blue-600 text-white text-xs font-bold px-6 py-2 rounded-full uppercase tracking-widest shadow-lg">Más popular</div>
                    <h3 class="text-xl font-bold mb-2">Business</h3>
                    <p class="text-slate-400 text-sm mb-6">La solución completa para empresas en crecimiento.</p>
                    <div class="mb-6">
                        <span class="text-5xl font-extrabold tracking-tighter">$149</span>
                        <span class="text-slate-400 font-medium">/mes</span>
                    </div>
                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-center text-sm text-slate-300 font-medium">
                            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Paquetes Ilimitados
                        </li>
                        <li class="flex items-center text-sm text-slate-300 font-medium">
                            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Sistema de Fidelización (Puntos)
                        </li>
                        <li class="flex items-center text-sm text-slate-300 font-medium">
                            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Pasarelas de Pago Integradas
                        </li>
                        <li class="flex items-center text-sm text-slate-300 font-medium">
                            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Marca Blanca (Logo & Colores)
                        </li>
                    </ul>
                    <a href="#contact" class="block text-center py-4 px-6 rounded-full bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-xl shadow-blue-600/30 transition-all">Solicitar Demo</a>
                </div>

                <!-- Enterprise Plan -->
                <div class="pricing-card p-10 rounded-[2.5rem] border border-slate-200 bg-white flex flex-col transition-all hover:border-blue-200 hover:shadow-xl">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Enterprise</h3>
                    <p class="text-slate-500 text-sm mb-6">Para redes multi-país y flujos complejos.</p>
                    <div class="mb-6">
                        <span class="text-5xl font-extrabold tracking-tighter text-slate-900">Custom</span>
                    </div>
                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-center text-sm text-slate-600 font-medium">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Multi-sucursales Ilimitadas
                        </li>
                        <li class="flex items-center text-sm text-slate-600 font-medium">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            API Access & Webhooks
                        </li>
                        <li class="flex items-center text-sm text-slate-600 font-medium">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Soporte Prioritario 24/7
                        </li>
                    </ul>
                    <a href="#contact" class="block text-center py-4 px-6 rounded-full border-2 border-slate-900 font-bold text-slate-900 hover:bg-slate-900 hover:text-white transition-all">Contactar Ventas</a>
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
            <div class="bg-white rounded-[3rem] p-8 md:p-16 shadow-2xl">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">¿Listo para transformar tu negocio?</h2>
                    <p class="text-slate-600 text-lg">Únete a la nueva era logística. Déjanos tus datos y te guiaremos en cada paso.</p>
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
            tl.from(".hero-badge", { duration: 0.8, y: -20, opacity: 0, ease: "back.out(1.7)" })
              .from(".hero-title", { duration: 1, y: 50, opacity: 0, ease: "power4.out" }, "-=0.5")
              .from(".hero-subtitle", { duration: 1, y: 30, opacity: 0, ease: "power4.out" }, "-=0.7")
              .from(".hero-buttons", { duration: 1, y: 20, opacity: 0, ease: "power4.out" }, "-=0.7")
              .from(".hero-mockup", { duration: 1.5, scale: 0.95, opacity: 0, ease: "power4.out" }, "-=0.8");

            // Mouse Move Parallax Effect (More subtle and smooth)
            document.addEventListener("mousemove", (e) => {
                const mouseX = e.clientX;
                const mouseY = e.clientY;

                gsap.to(".parallax-element", {
                    duration: 1.5,
                    x: (i, target) => {
                        const speed = target.getAttribute("data-speed") || 0.05;
                        return (mouseX - window.innerWidth / 2) * speed;
                    },
                    y: (i, target) => {
                        const speed = target.getAttribute("data-speed") || 0.05;
                        return (mouseY - window.innerHeight / 2) * speed;
                    },
                    ease: "power2.out"
                });
            });

            // Scroll Reveal Animations
            gsap.utils.toArray('.reveal').forEach(elem => {
                gsap.from(elem, {
                    scrollTrigger: {
                        trigger: elem,
                        start: "top 90%",
                    },
                    duration: 1.2,
                    y: 40,
                    opacity: 0,
                    ease: "power3.out"
                });
            });

            // Feature Cards Stagger (Integrated with Bento Grid)
            gsap.from(".md\\:col-span-3, .md\\:col-span-2, .md\\:col-span-4", {
                scrollTrigger: {
                    trigger: "#features",
                    start: "top 75%",
                },
                duration: 1,
                y: 50,
                opacity: 0,
                stagger: 0.15,
                ease: "power3.out"
            });

            // Scroll Parallax for decoration
            gsap.to("#parallax-circle-1", {
                scrollTrigger: {
                    trigger: "#features",
                    start: "top bottom",
                    end: "bottom top",
                    scrub: true
                },
                y: -150,
                ease: "none"
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
