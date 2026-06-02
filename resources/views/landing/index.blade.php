<x-layouts.landing>
    <!-- GLOBAL PARALLAX BACKGROUND -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <!-- Main World Map -->
        <div class="absolute inset-0 opacity-20 scale-125" id="global-parallax-bg">
            <img src="https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&q=80&w=2400" alt="World Map Background" class="w-full h-full object-cover">
        </div>

        <!-- Interactive Blobs -->
        <div class="parallax-element absolute top-[-10%] left-[-5%] w-[800px] h-[800px] bg-blue-100/40 rounded-full blur-[120px]" data-speed="0.03"></div>
        <div class="parallax-element absolute top-[40%] right-[-10%] w-[600px] h-[600px] bg-indigo-100/30 rounded-full blur-[120px]" data-speed="0.06"></div>
        <div class="parallax-element absolute bottom-[-10%] left-[10%] w-[700px] h-[700px] bg-purple-50/50 rounded-full blur-[120px]" data-speed="0.04"></div>
    </div>

    @php
        $realCoreFeatures = [
            'Recepción Inteligente (Smart Reception)',
            'Motor de Extracción OCR e IA',
            'Gestión Masiva de Manifiestos',
            'Control de Inventario y Racks',
            'Consolidación y Reempaque',
            'Sistema de Casilleros (Locker ID)',
            'Última Milla y Firma Digital',
            'Facturación Electrónica Automática',
            'Billetera Digital (Wallet)',
            'Checkout Stripe / PayPal / Yappy',
            'Dashboard Premium de Clientes',
            'Sistema de Pre-Alertas Online',
            'Configuración de Marca Blanca',
            'Gestión de Roles y Permisos',
            'Sistema de Puntos (Loyalty)',
            'Motor de Promociones y Cupones',
            'Constructor de Sitios (CMS)'
        ];

        $whatsappFeatures = [
            'Notificaciones de Llegada vía WhatsApp',
            'Alertas de Saldo Pendiente (WA)',
            'ChatBot de Rastreo Automatizado',
            'Comunicación Directa desde el Admin'
        ];

        $mobileFeatures = [
            'App Móvil para Entregas a Domicilio',
            'Gestión de Perfiles para Conductores',
            'Aplicación Móvil Nativa (iOS & Android)',
            'Notificaciones Push en Tiempo Real',
            'Escaneo de Tracking vía Móvil',
            'Soporte Prioritario y Consultoría'
        ];
    @endphp

    <div class="relative z-10">
        <!-- Hero Section -->
        <div class="relative pt-32 pb-20 lg:pt-40 lg:pb-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center">
                    <div class="hero-badge inline-flex items-center px-4 py-1.5 rounded-full bg-blue-50 text-blue-700 text-sm font-bold mb-8 border border-blue-100 uppercase tracking-wider shadow-sm">
                        <span class="flex h-2 w-2 rounded-full bg-blue-600 mr-2 animate-ping"></span>
                        LogiSaaS: La evolución de la logística digital
                    </div>
                    <h1 class="hero-title text-5xl md:text-7xl font-black tracking-tighter text-slate-900 mb-8 leading-[1.1]">
                        Tu Courier, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600">Sin Límites</span>
                    </h1>
                    <p class="hero-subtitle max-w-3xl mx-auto text-lg md:text-xl text-slate-600 mb-12 leading-relaxed font-medium">
                        Una plataforma SaaS integral diseñada para transformar operaciones logísticas locales en potencias internacionales. White-label, automatización y escalabilidad en un solo lugar.
                    </p>
                    <div class="hero-buttons flex flex-col sm:flex-row justify-center gap-6">
                        <a href="#pricing" class="inline-flex items-center justify-center px-10 py-4 border border-transparent text-lg font-bold rounded-full text-white bg-blue-600 hover:bg-blue-700 shadow-2xl shadow-blue-300 transition-all transform hover:-translate-y-1 hover:scale-105">
                            Ver Planes
                        </a>
                        <a href="#contact" class="inline-flex items-center justify-center px-10 py-4 border-2 border-slate-200 text-lg font-bold rounded-full text-slate-700 bg-white/80 backdrop-blur hover:bg-slate-50 shadow-sm transition-all transform hover:-translate-y-1">
                            Solicitar Demo
                        </a>
                    </div>

                    <!-- Product Showcase -->
                    <div class="hero-mockup mt-20 relative max-w-5xl mx-auto">
                        <div class="absolute -inset-10 bg-gradient-to-r from-blue-500/10 via-indigo-500/10 to-purple-500/10 rounded-[4rem] blur-3xl z-0"></div>
                        <div class="relative bg-slate-900 rounded-[3rem] p-3 shadow-2xl border border-white/20 overflow-hidden transform perspective-2000">
                            <div class="bg-slate-800 rounded-[2rem] overflow-hidden border border-slate-700">
                                <div class="h-10 bg-slate-900/80 border-b border-slate-700 flex items-center px-8 gap-2 backdrop-blur">
                                    <div class="w-3 h-3 rounded-full bg-red-500/40"></div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-500/40"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-500/40"></div>
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
                    <span class="text-2xl font-black text-slate-900 italic">LOGY EXPRESS</span>
                    <span class="text-2xl font-black text-slate-900 italic">FASTBOX.PA</span>
                    <span class="text-2xl font-black text-slate-900 italic">GLOBAL CARGO</span>
                    <span class="text-2xl font-black text-slate-900 italic">PANAMA COURIER</span>
                </div>
            </div>
        </div>

        <!-- How it Works -->
        <div class="py-24 bg-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-20 reveal">
                    <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-4">Eficiencia Garantizada</h2>
                    <h3 class="text-4xl md:text-5xl font-black text-slate-900">Tu camino al éxito</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                    <div class="p-8 reveal shadow-sm hover:shadow-md transition-all bg-slate-50 rounded-3xl border border-slate-100">
                        <div class="w-16 h-16 bg-slate-200 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl font-black">1</div>
                        <h4 class="font-bold text-xl mb-4">Registro</h4>
                        <p class="text-slate-500 text-sm leading-relaxed">Crea tu cuenta corporativa y configura tu subdominio en segundos.</p>
                    </div>
                    <div class="p-8 reveal shadow-sm hover:shadow-md transition-all bg-slate-50 rounded-3xl border border-slate-100" data-delay="0.1">
                        <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl font-black">2</div>
                        <h4 class="font-bold text-xl mb-4">White Label</h4>
                        <p class="text-slate-500 text-sm leading-relaxed">Sube tu logo y colores. La plataforma se convierte en tu propia App.</p>
                    </div>
                    <div class="p-8 reveal shadow-sm hover:shadow-md transition-all bg-slate-50 rounded-3xl border border-slate-100" data-delay="0.2">
                        <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl font-black">3</div>
                        <h4 class="font-bold text-xl mb-4">Bodegas</h4>
                        <p class="text-slate-500 text-sm leading-relaxed">Configura tus direcciones en Miami y sucursales locales.</p>
                    </div>
                    <div class="p-8 reveal shadow-sm hover:shadow-md transition-all bg-slate-900 text-white rounded-3xl border border-slate-800" data-delay="0.3">
                        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl font-black">4</div>
                        <h4 class="font-bold text-xl mb-4 italic">¡Lanzamiento!</h4>
                        <p class="text-slate-400 text-sm leading-relaxed">Tus clientes ya pueden registrarse y mover carga.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Bento Grid -->
        <div id="features" class="py-24 bg-slate-50 relative overflow-hidden">
            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-[800px] h-[800px] border border-blue-100 rounded-full opacity-20 pointer-events-none" id="parallax-circle-1"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-20 reveal">
                    <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-4">Innovación Constante</h2>
                    <h3 class="text-3xl md:text-5xl font-black text-slate-900 mb-6">Módulos de Clase Mundial</h3>
                    <p class="max-w-2xl mx-auto text-slate-600">Herramientas intuitivas que potencian cada área de tu negocio.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 h-auto">
                    <!-- Smart Reception -->
                    <div class="md:col-span-8 bg-white p-12 rounded-[3rem] border border-slate-200 overflow-hidden relative group reveal">
                        <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-blue-50 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-1000"></div>
                        <div class="relative z-10">
                            <div class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center mb-8 shadow-xl shadow-blue-200">
                                <i class="fas fa-barcode text-3xl"></i>
                            </div>
                            <h3 class="text-3xl font-black text-slate-900 mb-6 tracking-tight">Recepción Inteligente 2.0</h3>
                            <p class="text-slate-600 text-lg leading-relaxed max-w-lg">Procesa paquetes en milisegundos. Nuestro motor OCR extrae trackings y asocia paquetes a clientes automáticamente sin errores manuales.</p>
                        </div>
                    </div>

                    <!-- Loyalty -->
                    <div class="md:col-span-4 bg-slate-900 p-12 rounded-[3rem] text-white relative group overflow-hidden reveal">
                        <div class="w-14 h-14 bg-indigo-500 rounded-2xl flex items-center justify-center mb-8 shadow-xl">
                            <i class="fas fa-star text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-black mb-6 tracking-tight">Fidelización</h3>
                        <p class="text-slate-400 leading-relaxed">Sistema de puntos y niveles gamificado para retener a tus clientes.</p>
                    </div>

                    <!-- Customer Portal -->
                    <div class="md:col-span-5 bg-white p-12 rounded-[3rem] border border-slate-200 shadow-sm reveal">
                         <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-8">
                            <i class="fas fa-user-circle text-3xl"></i>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 mb-6 tracking-tight">Portal Premium</h3>
                        <p class="text-slate-600 text-lg leading-relaxed">Pre-alertas, calculadora de fletes y billetera digital en una interfaz móvil intuitiva.</p>
                    </div>

                    <!-- Payments -->
                    <div class="md:col-span-7 bg-blue-600 p-12 rounded-[3rem] text-white relative group overflow-hidden reveal">
                        <h3 class="text-4xl font-black mb-6 tracking-tight">Pagos & Finanzas</h3>
                        <p class="text-blue-100 text-xl leading-relaxed max-w-md">Integración nativa con Yappy, PayPal y Stripe. Genera facturas automáticas y gestiona cobros sin esfuerzo.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Solutions Section -->
        <div id="solutions" class="py-24 bg-white relative overflow-hidden border-y border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-20 reveal">
                    <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-4">Soluciones para cada etapa</h2>
                    <h3 class="text-4xl font-black text-slate-900 tracking-tighter">Adaptado a tu modelo de negocio</h3>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <div class="bg-slate-50 p-12 rounded-[3rem] border border-slate-200 shadow-sm reveal hover:bg-white hover:shadow-xl transition-all">
                        <h4 class="text-2xl font-black text-slate-900 mb-6 italic uppercase">Casilleros Virtuales</h4>
                        <p class="text-slate-600 leading-relaxed">Ideal para agencias que ofrecen dirección en USA. Gestión automática de Locker ID y notificaciones de llegada.</p>
                    </div>
                    <div class="bg-slate-50 p-12 rounded-[3rem] border border-slate-200 shadow-sm reveal hover:bg-white hover:shadow-xl transition-all" data-delay="0.1">
                        <h4 class="text-2xl font-black text-slate-900 mb-6 italic uppercase">Agencias de Carga</h4>
                        <p class="text-slate-600 leading-relaxed">Consolidación, reempaque y manejo de carga pesada con reportes de inventario exhaustivos.</p>
                    </div>
                    <div class="bg-slate-50 p-12 rounded-[3rem] border border-slate-200 shadow-sm reveal hover:bg-white hover:shadow-xl transition-all" data-delay="0.2">
                        <h4 class="text-2xl font-black text-slate-900 mb-6 italic uppercase">Última Milla</h4>
                        <p class="text-slate-600 leading-relaxed">Optimización de rutas de entrega local, firmas digitales y gestión de mensajeros en campo.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Integrations Section -->
        <div class="py-32 bg-slate-900 text-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                    <div class="reveal">
                        <h2 class="text-blue-500 font-bold uppercase tracking-widest text-sm mb-4">Ecosistema Conectado</h2>
                        <h3 class="text-5xl font-black mb-8 tracking-tighter leading-tight">Integraciones que impulsan tu crecimiento</h3>
                        <p class="text-slate-400 text-xl leading-relaxed mb-12">LogiSaaS se conecta con los gigantes del e-commerce y pagos para que tu operación nunca se detenga.</p>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="bg-white/5 p-6 rounded-3xl border border-white/10 flex items-center gap-4">
                                <i class="fab fa-amazon text-2xl text-blue-400"></i>
                                <span class="font-bold">Amazon OCR</span>
                            </div>
                            <div class="bg-white/5 p-6 rounded-3xl border border-white/10 flex items-center gap-4">
                                <i class="fab fa-stripe text-2xl text-blue-400"></i>
                                <span class="font-bold">Stripe</span>
                            </div>
                            <div class="bg-white/5 p-6 rounded-3xl border border-white/10 flex items-center gap-4">
                                <i class="fab fa-paypal text-2xl text-blue-400"></i>
                                <span class="font-bold">PayPal</span>
                            </div>
                            <div class="bg-white/5 p-6 rounded-3xl border border-white/10 flex items-center gap-4">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center font-black text-xs">Y</div>
                                <span class="font-bold">Yappy PA</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative reveal flex justify-center">
                         <div class="w-80 h-80 bg-blue-600/20 rounded-full blur-[100px] absolute"></div>
                         <div class="grid grid-cols-3 gap-4">
                            @for($i=0; $i<9; $i++)
                            <div class="w-20 h-20 bg-white/5 rounded-2xl border border-white/10 flex items-center justify-center">
                                <div class="w-10 h-10 bg-blue-600/30 rounded-xl"></div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing Section -->
        <div id="pricing" class="py-24 bg-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-20 reveal">
                    <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-4">Transparencia Total</h2>
                    <p class="text-4xl md:text-6xl font-black text-slate-900 mb-8 tracking-tighter">Planes que crecen contigo</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                    <!-- Startup Plan -->
                    <div class="pricing-card p-10 rounded-[3rem] border border-slate-200 bg-white flex flex-col transition-all hover:border-blue-600 group reveal">
                        <div class="mb-8">
                            <h3 class="text-2xl font-black text-slate-900 mb-2">Startup</h3>
                            <p class="text-slate-500 text-sm italic">Poder operativo completo para iniciar.</p>
                        </div>
                        <div class="mb-10 flex flex-col justify-center h-20">
                            <h4 class="text-3xl font-black text-slate-900">Consultar Precio</h4>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Funciones Core de LogySaaS</p>
                        </div>
                        <ul class="space-y-3 mb-10 flex-grow">
                            @foreach($realCoreFeatures as $feature)
                            <li class="flex items-start text-slate-600 font-bold text-[0.75rem]">
                                <svg class="w-3.5 h-3.5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="#contact" class="block text-center py-4 px-8 rounded-full border-2 border-slate-900 font-black text-slate-900 group-hover:bg-slate-900 group-hover:text-white transition-all">Empezar ahora</a>
                    </div>

                    <!-- Business Plan -->
                    <div class="pricing-card p-10 rounded-[3rem] bg-slate-900 flex flex-col shadow-2xl shadow-blue-900/20 relative transition-all transform hover:-translate-y-2 text-white reveal">
                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-blue-600 text-white text-xs font-black px-8 py-2 rounded-full uppercase tracking-[0.2em] shadow-lg">RECOMENDADO</div>
                        <div class="mb-8">
                            <h3 class="text-2xl font-black mb-2">Business</h3>
                            <p class="text-slate-400 text-sm italic">Automatización y comunicación 24/7.</p>
                        </div>
                        <div class="mb-10 flex flex-col justify-center h-20">
                            <h4 class="text-3xl font-black text-white">Consultar Precio</h4>
                            <p class="text-blue-400 text-xs font-bold uppercase tracking-widest">Todo Incluido + WhatsApp</p>
                        </div>
                        <ul class="space-y-3 mb-10 flex-grow">
                            @foreach($realCoreFeatures as $feature)
                            <li class="flex items-start text-slate-400 font-bold text-[0.75rem]">
                                <svg class="w-3.5 h-3.5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ $feature }}
                            </li>
                            @endforeach
                            <li class="h-px bg-white/10 my-4 text-blue-400 text-xs text-center uppercase tracking-widest font-black">Novedad WhatsApp</li>
                            @foreach($whatsappFeatures as $extra)
                            <li class="flex items-start text-white font-black text-[0.75rem]">
                                <svg class="w-4 h-4 text-green-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ $extra }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="#contact" class="block text-center py-4 px-8 rounded-full bg-blue-600 text-white font-black hover:bg-blue-700 shadow-xl shadow-blue-600/30 transition-all">Solicitar Cotización</a>
                    </div>

                    <!-- Enterprise Plan -->
                    <div class="pricing-card p-10 rounded-[3rem] border border-slate-200 bg-white flex flex-col transition-all hover:border-blue-600 group reveal">
                        <div class="mb-8">
                            <h3 class="text-2xl font-black text-slate-900 mb-2">Enterprise</h3>
                            <p class="text-slate-500 text-sm italic">Para corporaciones y redes globales.</p>
                        </div>
                        <div class="mb-10 flex flex-col justify-center h-20">
                            <h4 class="text-3xl font-black text-blue-600">Personalizado</h4>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Ecosistema Móvil y API</p>
                        </div>
                        <ul class="space-y-3 mb-10 flex-grow">
                            @foreach($realCoreFeatures as $feature)
                            <li class="flex items-start text-slate-600 font-bold text-[0.75rem]">
                                <svg class="w-3.5 h-3.5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ $feature }}
                            </li>
                            @endforeach
                            @foreach($whatsappFeatures as $extra)
                            <li class="flex items-start text-slate-600 font-bold text-[0.75rem]">
                                <svg class="w-3.5 h-3.5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ $extra }}
                            </li>
                            @endforeach
                            <li class="h-px bg-slate-200 my-4 text-blue-600 text-xs text-center uppercase tracking-widest font-black">Exclusivo Enterprise</li>
                            @foreach($mobileFeatures as $extra)
                            <li class="flex items-start text-slate-900 font-black text-[0.75rem]">
                                <svg class="w-4 h-4 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                {{ $extra }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="#contact" class="block text-center py-4 px-8 rounded-full border-2 border-slate-900 font-black text-slate-900 group-hover:bg-slate-900 group-hover:text-white transition-all">Hablar con Ventas</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div id="faq" class="py-24 bg-slate-50/80 backdrop-blur-sm">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-20 reveal">
                    <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-4">Soporte y Recursos</h2>
                    <h3 class="text-4xl font-black text-slate-900 tracking-tighter">Despeja tus dudas</h3>
                </div>

                <div class="space-y-4 reveal">
                    <div x-data="{ open: false }" class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden transition-all shadow-sm hover:shadow-md">
                        <button @click="open = !open" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                            <span class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">¿Cómo funciona la marca blanca?</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-500" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" x-cloak class="px-8 pb-6 text-slate-600 leading-relaxed text-sm">
                            Podrás configurar tu logo, colores corporativos y conectar tu propio dominio. Tus clientes entrarán a "tu-empresa.com" y verán una plataforma que parece desarrollada 100% por ti.
                        </div>
                    </div>

                    <div x-data="{ open: false }" class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden transition-all shadow-sm hover:shadow-md">
                        <button @click="open = !open" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                            <span class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">¿Es compatible con bodegas en Miami?</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-500" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" x-cloak class="px-8 pb-6 text-slate-600 leading-relaxed text-sm">
                            Sí, está diseñado específicamente para el flujo Miami -> Latinoamérica. Permite gestionar recepciones en el extranjero y tránsitos internacionales hasta el destino final.
                        </div>
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

            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="bg-white rounded-[3.5rem] p-10 md:p-20 shadow-2xl">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-black text-slate-900 mb-6 tracking-tighter">Construyamos el futuro de tu logística</h2>
                        <p class="text-slate-600 text-lg font-medium">Déjanos tus datos y un especialista te contactará hoy mismo.</p>
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
