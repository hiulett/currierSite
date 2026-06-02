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
                        LogiSaaS: Redefiniendo la rentabilidad logística
                    </div>
                    <h1 class="hero-title text-5xl md:text-7xl font-black tracking-tighter text-slate-900 mb-8 leading-[1.1]">
                        Deja de perder paquetes y clientes. <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600">Automatiza tu courier en 48 horas.</span>
                    </h1>
                    <p class="hero-subtitle max-w-3xl mx-auto text-lg md:text-xl text-slate-600 mb-12 leading-relaxed font-medium">
                        La única plataforma SaaS todo en uno que automatiza recepciones con OCR, fideliza a tus clientes mediante puntos de lealtad y gestiona cobros de forma automatizada. Setup exprés y marca blanca completa.
                    </p>
                    <div class="hero-buttons flex flex-col sm:flex-row justify-center items-center gap-6">
                        <a href="#contact" class="cta-shine inline-flex items-center justify-center px-10 py-4 border border-transparent text-lg font-bold rounded-full text-white bg-blue-600 hover:bg-blue-700 shadow-2xl shadow-blue-300 transition-all transform hover:-translate-y-1 hover:scale-105">
                            Probar 30 Días Gratis <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        <a href="#pricing" class="inline-flex items-center justify-center px-10 py-4 border-2 border-slate-200 text-lg font-bold rounded-full text-slate-700 bg-white/85 backdrop-blur hover:bg-slate-50 shadow-sm transition-all transform hover:-translate-y-1">
                            Ver Planes & Precios
                        </a>
                    </div>
                    
                    <div class="mt-6 text-sm text-slate-500 flex flex-wrap justify-center gap-x-6 gap-y-2 font-semibold">
                        <span><i class="fas fa-check text-green-500 mr-1.5"></i> Setup en 48 Horas</span>
                        <span><i class="fas fa-check text-green-500 mr-1.5"></i> Soporte 24/7 en Español</span>
                        <span><i class="fas fa-check text-green-500 mr-1.5"></i> Sin Tarjeta de Crédito</span>
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

        <!-- Trust Section & Counter Metrics -->
        <div class="py-20 bg-slate-50/50 backdrop-blur-sm border-y border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-xs font-black text-slate-400 uppercase tracking-[0.4em] mb-12">Liderando la industria logística en la región</p>
                <div class="flex flex-wrap justify-center items-center gap-16 md:gap-32 opacity-50 grayscale hover:grayscale-0 transition-all duration-500 mb-16">
                    <span class="text-2xl font-black text-slate-900 italic">LOGY EXPRESS</span>
                    <span class="text-2xl font-black text-slate-900 italic">FASTBOX.PA</span>
                    <span class="text-2xl font-black text-slate-900 italic">GLOBAL CARGO</span>
                    <span class="text-2xl font-black text-slate-900 italic">PANAMA COURIER</span>
                </div>

                <!-- Stats Bar -->
                <div id="metrics-bar" class="grid grid-cols-2 md:grid-cols-4 gap-8 pt-8 border-t border-slate-200/60 text-center">
                    <div class="p-4">
                        <p class="text-3xl md:text-5xl font-black text-blue-600 tracking-tight mb-2">
                            +<span class="js-counter-metric" data-target="1200000">5010</span>
                        </p>
                        <p class="text-xs md:text-sm font-bold text-slate-500 uppercase tracking-wider">Paquetes Procesados</p>
                    </div>
                    <div class="p-4">
                        <p class="text-3xl md:text-5xl font-black text-indigo-600 tracking-tight mb-2">
                            +<span class="js-counter-metric" data-target="150">45</span>
                        </p>
                        <p class="text-xs md:text-sm font-bold text-slate-500 uppercase tracking-wider">Couriers Activos</p>
                    </div>
                    <div class="p-4">
                        <p class="text-3xl md:text-5xl font-black text-purple-600 tracking-tight mb-2">
                            <span class="js-counter-metric" data-target="99">99.9</span>.<span class="js-counter-metric" data-target="9">0</span>%
                        </p>
                        <p class="text-xs md:text-sm font-bold text-slate-500 uppercase tracking-wider">Uptime Garantizado</p>
                    </div>
                    <div class="p-4">
                        <p class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight mb-2">
                            +<span class="js-counter-metric" data-target="12">13</span>
                        </p>
                        <p class="text-xs md:text-sm font-bold text-slate-500 uppercase tracking-wider">Países Conectados</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- How it Works -->
        <div class="py-24 bg-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-20 reveal">
                    <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-4">Proceso Sin Complicaciones</h2>
                    <h3 class="text-4xl md:text-5xl font-black text-slate-900">Tu Courier Operando en 4 Pasos</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                    <div class="p-8 reveal shadow-sm hover:shadow-md transition-all bg-slate-50 rounded-3xl border border-slate-100">
                        <div class="w-16 h-16 bg-slate-200 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl font-black">1</div>
                        <h4 class="font-bold text-xl mb-4">Registro y Setup</h4>
                        <p class="text-slate-500 text-sm leading-relaxed">Crea tu cuenta corporativa y configura tu subdominio seguro en minutos. Sin conocimientos técnicos requeridos.</p>
                    </div>
                    <div class="p-8 reveal shadow-sm hover:shadow-md transition-all bg-slate-50 rounded-3xl border border-slate-100" data-delay="0.1">
                        <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl font-black">2</div>
                        <h4 class="font-bold text-xl mb-4">Sube tus Clientes</h4>
                        <p class="text-slate-500 text-sm leading-relaxed">Importa tu base de clientes existente con un solo clic. Cada uno recibe acceso automático a su portal personalizado.</p>
                    </div>
                    <div class="p-8 reveal shadow-sm hover:shadow-md transition-all bg-slate-50 rounded-3xl border border-slate-100" data-delay="0.2">
                        <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl font-black">3</div>
                        <h4 class="font-bold text-xl mb-4">Ingresa tus Paquetes</h4>
                        <p class="text-slate-500 text-sm leading-relaxed">Registra la carga en bodega y el sistema notifica automáticamente a cada cliente por WhatsApp y correo.</p>
                    </div>
                    <div class="p-8 reveal shadow-sm hover:shadow-md transition-all bg-slate-900 text-white rounded-3xl border border-slate-800" data-delay="0.3">
                        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl font-black">4</div>
                        <h4 class="font-bold text-xl mb-4 italic">¡Lanzamiento!</h4>
                        <p class="text-slate-400 text-sm leading-relaxed">Tus clientes se registran, pre-alertan carga y pagan sus facturas de forma completamente automatizada.</p>
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
                    <h3 class="text-3xl md:text-5xl font-black text-slate-900 mb-6">Módulos de Alta Conversión y Eficiencia</h3>
                    <p class="max-w-2xl mx-auto text-slate-600">Herramientas intuitivas que incrementan la lealtad y eliminan el trabajo manual repetitivo.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 h-auto">
                    <!-- Smart Reception -->
                    <div class="md:col-span-8 bg-white p-12 rounded-[3rem] border border-slate-200 overflow-hidden relative group reveal shadow-sm">
                        <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-blue-50 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-1000"></div>
                        <div class="relative z-10">
                            <div class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center mb-8 shadow-xl shadow-blue-200">
                                <i class="fas fa-barcode text-3xl"></i>
                            </div>
                            <h3 class="text-3xl font-black text-slate-900 mb-6 tracking-tight">Recepción Inteligente con OCR e IA</h3>
                            <p class="text-slate-600 text-lg leading-relaxed max-w-lg">Procesa paquetes en milisegundos. Nuestro motor avanzado de OCR extrae de forma inteligente los trackings (Amazon, USPS, DHL) y los asocia a la cuenta del cliente instantáneamente sin errores tipográficos.</p>
                        </div>
                    </div>

                    <!-- Loyalty -->
                    <div class="md:col-span-4 bg-slate-900 p-12 rounded-[3rem] text-white relative group overflow-hidden reveal shadow-xl">
                        <div class="w-14 h-14 bg-indigo-500 rounded-2xl flex items-center justify-center mb-8 shadow-xl">
                            <i class="fas fa-star text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-black mb-6 tracking-tight">Fidelización por Puntos</h3>
                        <p class="text-slate-400 leading-relaxed mb-6">Gamifica tu negocio de casilleros. Permite a tus clientes acumular puntos por cada libra de carga movilizada y canjearlos por fletes gratis.</p>
                    </div>

                    <!-- Customer Portal -->
                    <div class="md:col-span-5 bg-white p-12 rounded-[3rem] border border-slate-200 shadow-sm reveal">
                         <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-8">
                            <i class="fas fa-user-circle text-3xl"></i>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 mb-6 tracking-tight">Portal Web Premium</h3>
                        <p class="text-slate-600 text-lg leading-relaxed">Calculadora de fletes en vivo, seguimiento en tiempo real, registro de pre-alertas ultra-rápido y una billetera digital integrada para abonos instantáneos.</p>
                    </div>

                    <!-- Payments -->
                    <div class="md:col-span-7 bg-blue-600 p-12 rounded-[3rem] text-white relative group overflow-hidden reveal shadow-xl">
                        <h3 class="text-4xl font-black mb-6 tracking-tight">Pasarelas de Pago Integradas</h3>
                        <p class="text-blue-100 text-xl leading-relaxed max-w-md mb-6">Ofrece checkout directo y seguro con Stripe, PayPal y Yappy (Panamá). Facturación electrónica automatizada que reduce la morosidad a cero.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Solutions Section -->
        <div id="solutions" class="py-24 bg-white relative overflow-hidden border-y border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-20 reveal">
                    <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-4">Soluciones para cada etapa</h2>
                    <h3 class="text-4xl font-black text-slate-900 tracking-tighter">Adaptado a tu Modelo de Negocio</h3>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <div class="bg-slate-50 p-12 rounded-[3rem] border border-slate-200 shadow-sm reveal hover:bg-white hover:shadow-xl transition-all">
                        <h4 class="text-2xl font-black text-slate-900 mb-6 italic uppercase">Casilleros Virtuales</h4>
                        <p class="text-slate-600 leading-relaxed">Perfecto para empresas de fletes rápidas con dirección en Miami. Genera automáticamente los Locker ID del cliente y notificaciones de estatus instantáneas.</p>
                    </div>
                    <div class="bg-slate-50 p-12 rounded-[3rem] border border-slate-200 shadow-sm reveal hover:bg-white hover:shadow-xl transition-all" data-delay="0.1">
                        <h4 class="text-2xl font-black text-slate-900 mb-6 italic uppercase">Agencias de Carga</h4>
                        <p class="text-slate-600 leading-relaxed">Consolidación ágil, reempaque seguro de mercancías pesadas, y control de racks mediante código de barras para una trazabilidad total.</p>
                    </div>
                    <div class="bg-slate-50 p-12 rounded-[3rem] border border-slate-200 shadow-sm reveal hover:bg-white hover:shadow-xl transition-all" data-delay="0.2">
                        <h4 class="text-2xl font-black text-slate-900 mb-6 italic uppercase">Última Milla</h4>
                        <p class="text-slate-600 leading-relaxed">Optimización dinámica de rutas de entrega local, firmas digitales en campo y app nativa intuitiva para conductores y motorizados.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Interactive ROI Calculator Section (CRO) -->
        <div id="roi" class="py-24 bg-gradient-to-br from-slate-900 to-indigo-950 text-white relative overflow-hidden">
            <div class="absolute -right-40 -top-40 w-96 h-96 bg-blue-600/20 rounded-full blur-[120px] pointer-events-none"></div>
            <div class="absolute -left-40 -bottom-40 w-96 h-96 bg-purple-600/20 rounded-full blur-[120px] pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10" x-data="{
                paquetes: 2000,
                costoPorPaquete: 1.50,
                get horasAhorradas() {
                    return Math.round(this.paquetes * 0.05);
                },
                get ahorroMensual() {
                    return Math.round(this.paquetes * (this.costoPorPaquete - 0.30));
                },
                get ahorroAnual() {
                    return this.ahorroMensual * 12;
                }
            }">
                <div class="text-center mb-16 reveal">
                    <span class="px-4 py-1.5 rounded-full bg-blue-500/10 text-blue-400 text-sm font-bold mb-4 inline-block border border-blue-500/20 uppercase tracking-widest">Calculadora de Impacto</span>
                    <h3 class="text-4xl md:text-5xl font-black tracking-tight mt-2">Calcula tu Retorno de Inversión (ROI)</h3>
                    <p class="text-slate-400 mt-4 max-w-2xl mx-auto">Visualiza las horas de trabajo operativo y el dinero mensual que ahorrarás migrando hoy mismo a LogiSaaS.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Inputs -->
                    <div class="bg-white/5 backdrop-blur-md p-8 md:p-12 rounded-[3rem] border border-white/10 space-y-8 reveal">
                        <div>
                            <label class="block text-sm font-bold text-slate-300 uppercase tracking-widest mb-4">
                                Paquetes procesados al mes: <span class="text-blue-400 text-lg font-black" x-text="paquetes.toLocaleString()"></span>
                            </label>
                            <input type="range" min="500" max="20000" step="500" x-model="paquetes" class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-blue-500">
                            <div class="flex justify-between text-xs text-slate-400 mt-2 font-bold">
                                <span>500</span>
                                <span>10,000</span>
                                <span>20,000</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-300 uppercase tracking-widest mb-4">
                                Costo operativo manual por paquete: <span class="text-indigo-400 text-lg font-black" x-text="'$' + parseFloat(costoPorPaquete).toFixed(2) + ' USD'"></span>
                            </label>
                            <input type="range" min="0.50" max="5.00" step="0.10" x-model="costoPorPaquete" class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-500">
                            <div class="flex justify-between text-xs text-slate-400 mt-2 font-bold">
                                <span>$0.50</span>
                                <span>$2.50</span>
                                <span>$5.00</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2 font-medium">※ Incluye salarios del personal de bodega, tiempos de digitación y resolución de errores.</p>
                        </div>
                    </div>

                    <!-- Results -->
                    <div class="space-y-8 reveal">
                        <div class="bg-gradient-to-br from-blue-600 to-indigo-600 p-10 rounded-[3rem] shadow-2xl relative overflow-hidden group">
                            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-700"></div>
                            <h4 class="text-sm font-bold uppercase tracking-wider text-blue-100 mb-2">Ahorro Mensual Estimado</h4>
                            <p class="text-5xl md:text-7xl font-black tracking-tight" x-text="'$' + ahorroMensual.toLocaleString() + ' USD'"></p>
                            <div class="mt-6 pt-6 border-t border-white/20 flex items-center justify-between">
                                <span class="text-xs text-blue-100 font-bold uppercase tracking-wider">Ahorro Anualizado</span>
                                <span class="text-2xl font-black text-white" x-text="'$' + ahorroAnual.toLocaleString() + ' USD'"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="bg-white/5 p-8 rounded-[2rem] border border-white/10">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Horas Operativas Ahorradas</p>
                                <p class="text-3xl font-black text-blue-400" x-text="horasAhorradas + ' hrs/mes'"></p>
                            </div>
                            <div class="bg-white/5 p-8 rounded-[2rem] border border-white/10">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Costo con LogiSaaS</p>
                                <p class="text-3xl font-black text-green-400">$0.30 <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">/ paquete</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonials Section (Social Proof) -->
        <div class="py-24 bg-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-20 reveal">
                    <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-4">Caso de Éxito Real</h2>
                    <h3 class="text-4xl md:text-5xl font-black text-slate-900">Nuestros Clientes lo Confirman</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Testimonial 1 -->
                    <div class="testimonial-card p-10 rounded-[3rem] bg-slate-50 border border-slate-100 shadow-sm flex flex-col justify-between hover:shadow-xl transition-all duration-300">
                        <div>
                            <div class="flex text-amber-400 mb-6 gap-1">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <p class="text-slate-600 leading-relaxed italic mb-8 text-base">
                                "Antes llamábamos a cada cliente para avisarle que su paquete llegó. Hoy LogiSaaS lo hace solo: WhatsApp, correo, portal de seguimiento... nuestros clientes están siempre informados y nosotros nos enfocamos en crecer."
                            </p>
                        </div>
                        <div class="flex items-center gap-4 border-t border-slate-200/60 pt-6">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-700 text-lg">CM</div>
                            <div>
                                <h4 class="font-black text-slate-900 text-sm">Carlos Mendoza</h4>
                                <p class="text-slate-500 text-xs font-bold">Director General, Logy Express</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="testimonial-card p-10 rounded-[3rem] bg-slate-900 text-white shadow-xl flex flex-col justify-between hover:shadow-2xl transition-all duration-300 transform md:-translate-y-2">
                        <div>
                            <div class="flex text-amber-400 mb-6 gap-1">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <p class="text-slate-300 leading-relaxed italic mb-8 text-base">
                                "Nuestros clientes aman el portal web Premium. Las notificaciones automáticas de llegada vía WhatsApp redujeron las consultas repetitivas a nuestro equipo de soporte en un 60%. ¡Es una excelente inversión!"
                            </p>
                        </div>
                        <div class="flex items-center gap-4 border-t border-white/10 pt-6">
                            <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center font-bold text-white text-lg">VS</div>
                            <div>
                                <h4 class="font-black text-white text-sm">Valeria Santos</h4>
                                <p class="text-slate-400 text-xs font-bold">Co-fundadora, Fastbox.pa</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="testimonial-card p-10 rounded-[3rem] bg-slate-50 border border-slate-100 shadow-sm flex flex-col justify-between hover:shadow-xl transition-all duration-300">
                        <div>
                            <div class="flex text-amber-400 mb-6 gap-1">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <p class="text-slate-600 leading-relaxed italic mb-8 text-base">
                                "Migrar a LogiSaaS fue la mejor decisión para escalar. Implementamos todo en menos de 48 horas y en el primer mes procesamos más de 15,000 paquetes sin errores manuales. El ROI es inmediato."
                            </p>
                        </div>
                        <div class="flex items-center gap-4 border-t border-slate-200/60 pt-6">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-700 text-lg">RC</div>
                            <div>
                                <h4 class="font-black text-slate-900 text-sm">Roberto Chen</h4>
                                <p class="text-slate-500 text-xs font-bold">CEO, Panama Courier</p>
                            </div>
                        </div>
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
                        <p class="text-slate-400 text-xl leading-relaxed mb-12">LogiSaaS se conecta de forma nativa con los gigantes globales y pasarelas de pago para asegurar que tu flujo comercial nunca se interrumpa.</p>

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

        <!-- Pricing Section with Anclaje & Toggle -->
        <div id="pricing" class="py-24 bg-white relative overflow-hidden" x-data="{ billingPeriod: 'annual' }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 reveal">
                    <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-4">Planes Sin Comisiones Ocultas</h2>
                    <p class="text-4xl md:text-6xl font-black text-slate-900 mb-8 tracking-tighter">Inversión Transparente que Escala</p>

                    <!-- Toggle Button -->
                    <div class="flex items-center justify-center gap-4 mt-6">
                        <span class="text-sm font-bold" :class="billingPeriod === 'monthly' ? 'text-slate-900' : 'text-slate-400'">Pago Mensual</span>
                        <button x-on:click="billingPeriod = billingPeriod === 'monthly' ? 'annual' : 'monthly'" class="w-14 h-8 rounded-full p-1 bg-blue-600 transition-colors focus:outline-none flex items-center">
                            <span class="w-6 h-6 rounded-full bg-white shadow-md transform transition-transform duration-300" :class="billingPeriod === 'annual' ? 'translate-x-6' : 'translate-x-0'"></span>
                        </button>
                        <span class="text-sm font-bold flex items-center gap-1.5" :class="billingPeriod === 'annual' ? 'text-slate-900' : 'text-slate-400'">
                            Pago Anual 
                            <span class="bg-green-100 text-green-700 text-xs font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider">Ahorra 20%</span>
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                    <!-- Startup Plan -->
                    <div class="pricing-card p-10 rounded-[3rem] border border-slate-200 bg-white flex flex-col transition-all hover:border-blue-600 group reveal shadow-sm hover:shadow-lg">
                        <div class="mb-8">
                            <h3 class="text-2xl font-black text-slate-900 mb-2">Startup</h3>
                            <p class="text-slate-500 text-sm italic">Poder operativo completo para iniciar.</p>
                        </div>
                        <div class="mb-10 flex flex-col justify-center h-24">
                            <div class="flex items-baseline text-slate-900">
                                <span class="text-lg font-bold mr-1">$</span>
                                <span class="text-5xl font-black tracking-tight" x-text="billingPeriod === 'annual' ? '24' : '30'">30</span>
                                <span class="text-slate-500 text-sm font-bold ml-2">/ mes</span>
                            </div>
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mt-2" x-show="billingPeriod === 'annual'">Cobrado anualmente ($288/año)</p>
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mt-2" x-show="billingPeriod === 'monthly'">Cobrado mes a mes</p>
                        </div>
                        <ul class="space-y-3 mb-10 flex-grow">
                            @foreach($realCoreFeatures as $feature)
                            <li class="flex items-start text-slate-600 font-bold text-[0.75rem]">
                                <svg class="w-3.5 h-3.5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="#contact" class="block text-center py-4 px-8 rounded-full border-2 border-slate-900 font-black text-slate-900 hover:bg-slate-900 hover:text-white transition-all">Empezar de Inmediato</a>
                    </div>

                    <!-- Business Plan (RECOMENDADO) -->
                    <div class="pricing-card p-10 rounded-[3rem] bg-slate-900 flex flex-col shadow-2xl shadow-blue-900/30 relative transition-all transform hover:-translate-y-2 text-white reveal">
                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-blue-600 text-white text-xs font-black px-8 py-2 rounded-full uppercase tracking-[0.2em] shadow-lg">RECOMENDADO</div>
                        <div class="mb-8">
                            <h3 class="text-2xl font-black mb-2">Business</h3>
                            <p class="text-slate-400 text-sm italic">Automatización y comunicación 24/7.</p>
                        </div>
                        <div class="mb-10 flex flex-col justify-center h-24">
                            <div class="flex items-baseline text-white">
                                <span class="text-lg font-bold mr-1">$</span>
                                <span class="text-5xl font-black tracking-tight text-blue-400" x-text="billingPeriod === 'annual' ? '36' : '45'">45</span>
                                <span class="text-slate-400 text-sm font-bold ml-2">/ mes</span>
                            </div>
                            <p class="text-blue-400 text-[10px] font-black uppercase tracking-widest mt-2" x-show="billingPeriod === 'annual'">Cobrado anualmente ($432/año)</p>
                            <p class="text-blue-400 text-[10px] font-black uppercase tracking-widest mt-2" x-show="billingPeriod === 'monthly'">Cobrado mes a mes</p>
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
                        <a href="#contact" class="cta-shine block text-center py-4 px-8 rounded-full bg-blue-600 text-white font-black hover:bg-blue-700 shadow-xl shadow-blue-600/30 transition-all">Solicitar Demo Gratis</a>
                    </div>

                    <!-- Enterprise Plan -->
                    <div class="pricing-card p-10 rounded-[3rem] border border-slate-200 bg-white flex flex-col transition-all hover:border-blue-600 group reveal shadow-sm hover:shadow-lg">
                        <div class="mb-8">
                            <h3 class="text-2xl font-black text-slate-900 mb-2">Enterprise</h3>
                            <p class="text-slate-500 text-sm italic">Para corporaciones y redes globales.</p>
                        </div>
                        <div class="mb-10 flex flex-col justify-center h-24">
                            <div class="flex items-baseline text-slate-900">
                                <span class="text-lg font-bold mr-1">$</span>
                                <span class="text-5xl font-black tracking-tight" x-text="billingPeriod === 'annual' ? '44' : '55'">55</span>
                                <span class="text-slate-500 text-sm font-bold ml-2">/ mes</span>
                            </div>
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mt-2" x-show="billingPeriod === 'annual'">Cobrado anualmente ($528/año)</p>
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mt-2" x-show="billingPeriod === 'monthly'">Cobrado mes a mes</p>
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
                        <a href="#contact" class="block text-center py-4 px-8 rounded-full border-2 border-slate-900 font-black text-slate-900 hover:bg-slate-900 hover:text-white transition-all">Hablar con Ventas</a>
                    </div>
                </div>

                <!-- Trust Signal under pricing -->
                <div class="mt-16 bg-blue-50 border border-blue-100 rounded-[2.5rem] p-8 text-center max-w-4xl mx-auto reveal">
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                        <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-3xl shadow-lg">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="text-left sm:max-w-xl">
                            <h4 class="font-black text-slate-900 text-lg mb-1">Garantía de Satisfacción de 30 Días</h4>
                            <p class="text-slate-600 text-sm">Prueba LogiSaaS con total tranquilidad. Si en los primeros 30 días decides que no es la solución perfecta para tu courier, te devolvemos el 100% de tu dinero de manera inmediata. Sin preguntas incómodas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section (8 Preguntas para derribar objeciones) -->
        <div id="faq" class="py-24 bg-slate-50/80 backdrop-blur-sm">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-20 reveal">
                    <h2 class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-4">Soporte y Respuestas</h2>
                    <h3 class="text-4xl font-black text-slate-900 tracking-tighter">Despeja Todas tus Dudas Operativas</h3>
                </div>

                <div class="space-y-4 reveal" x-data="{ activeAccordion: null }">
                    <!-- Q1 -->
                    <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden transition-all shadow-sm hover:shadow-md">
                        <button x-on:click="activeAccordion = activeAccordion === 1 ? null : 1" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                            <span class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">¿Cómo funciona la marca blanca?</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-500" :class="{'rotate-180': activeAccordion === 1}"></i>
                        </button>
                        <div x-show="activeAccordion === 1" x-collapse x-cloak class="px-8 pb-6 text-slate-600 leading-relaxed text-sm">
                            Podrás configurar tu logotipo, colores corporativos y conectar tu propio dominio. Tus clientes entrarán a "tu-empresa.com" y verán una plataforma personalizada que parece desarrollada 100% por tu marca.
                        </div>
                    </div>

                    <!-- Q2 -->
                    <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden transition-all shadow-sm hover:shadow-md">
                        <button x-on:click="activeAccordion = activeAccordion === 2 ? null : 2" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                            <span class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">¿Es compatible con bodegas en Miami y envíos internacionales?</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-500" :class="{'rotate-180': activeAccordion === 2}"></i>
                        </button>
                        <div x-show="activeAccordion === 2" x-collapse x-cloak class="px-8 pb-6 text-slate-600 leading-relaxed text-sm">
                            Sí, está diseñado específicamente para el flujo logístico Miami -> Latinoamérica. Permite gestionar recepciones en el extranjero, control de contenedores y tránsitos internacionales hasta el destino final con sus respectivas aduanas.
                        </div>
                    </div>

                    <!-- Q3 -->
                    <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden transition-all shadow-sm hover:shadow-md">
                        <button x-on:click="activeAccordion = activeAccordion === 3 ? null : 3" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                            <span class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">¿Cuánto tiempo toma el setup y la implementación completa?</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-500" :class="{'rotate-180': activeAccordion === 3}"></i>
                        </button>
                        <div x-show="activeAccordion === 3" x-collapse x-cloak class="px-8 pb-6 text-slate-600 leading-relaxed text-sm">
                            Menos de 48 horas. Te entregamos la plataforma operativa lista con tu dominio y marca corporativa configurada. Podrás comenzar a registrar clientes y carga de inmediato sin fricciones de desarrollo.
                        </div>
                    </div>

                    <!-- Q4 -->
                    <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden transition-all shadow-sm hover:shadow-md">
                        <button x-on:click="activeAccordion = activeAccordion === 4 ? null : 4" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                            <span class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">¿Qué pasa si ya tengo un sistema y quiero migrar mi base de datos?</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-500" :class="{'rotate-180': activeAccordion === 4}"></i>
                        </button>
                        <div x-show="activeAccordion === 4" x-collapse x-cloak class="px-8 pb-6 text-slate-600 leading-relaxed text-sm">
                            Ofrecemos migración gratuita e importación masiva de tus clientes históricos y trackings activos mediante plantillas Excel. Tu servicio no se interrumpirá en ningún momento.
                        </div>
                    </div>

                    <!-- Q5 -->
                    <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden transition-all shadow-sm hover:shadow-md">
                        <button x-on:click="activeAccordion = activeAccordion === 5 ? null : 5" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                            <span class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">¿Incluye capacitación para mi equipo operativo y administrativo?</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-500" :class="{'rotate-180': activeAccordion === 5}"></i>
                        </button>
                        <div x-show="activeAccordion === 5" x-collapse x-cloak class="px-8 pb-6 text-slate-600 leading-relaxed text-sm">
                            Sí, todos nuestros planes incluyen acceso ilimitado a nuestra biblioteca de tutoriales en video y una sesión de capacitación técnica en vivo 1-a-1 para todo tu equipo.
                        </div>
                    </div>

                    <!-- Q6 -->
                    <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden transition-all shadow-sm hover:shadow-md">
                        <button x-on:click="activeAccordion = activeAccordion === 6 ? null : 6" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                            <span class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">¿Mis datos y los de mis clientes están completamente seguros?</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-500" :class="{'rotate-180': activeAccordion === 6}"></i>
                        </button>
                        <div x-show="activeAccordion === 6" x-collapse x-cloak class="px-8 pb-6 text-slate-600 leading-relaxed text-sm">
                            Absolutamente. Toda la información viaja encriptada con SSL de 256 bits y se aloja en infraestructura redundante en Amazon Web Services (AWS) con copias de seguridad diarias automatizadas.
                        </div>
                    </div>

                    <!-- Q7 -->
                    <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden transition-all shadow-sm hover:shadow-md">
                        <button x-on:click="activeAccordion = activeAccordion === 7 ? null : 7" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                            <span class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">¿Hay contratos a largo plazo o puedo cancelar cuando quiera?</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-500" :class="{'rotate-180': activeAccordion === 7}"></i>
                        </button>
                        <div x-show="activeAccordion === 7" x-collapse x-cloak class="px-8 pb-6 text-slate-600 leading-relaxed text-sm">
                            No hay contratos de permanencia obligatorios. Puedes cancelar, cambiar o degradar tu plan en cualquier momento que lo desees sin cargos ocultos ni penalizaciones.
                        </div>
                    </div>

                    <!-- Q8 -->
                    <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden transition-all shadow-sm hover:shadow-md">
                        <button x-on:click="activeAccordion = activeAccordion === 8 ? null : 8" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                            <span class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">¿Ofrecen soporte técnico en español y soporte de emergencia?</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-500" :class="{'rotate-180': activeAccordion === 8}"></i>
                        </button>
                        <div x-show="activeAccordion === 8" x-collapse x-cloak class="px-8 pb-6 text-slate-600 leading-relaxed text-sm">
                            Sí. El soporte es 100% en español por WhatsApp corporativo, correo electrónico y tickets de soporte. Además, monitoreamos proactivamente nuestros servidores 24/7.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Section with Trust signals -->
        <div id="contact" class="py-24 bg-slate-900 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                <div class="absolute top-0 left-0 w-96 h-96 bg-blue-600 rounded-full blur-[120px]"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-600 rounded-full blur-[120px]"></div>
            </div>

            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="bg-white rounded-[3.5rem] p-10 md:p-20 shadow-2xl">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-black text-slate-900 mb-6 tracking-tighter">Construyamos el Futuro de tu Courier</h2>
                        <p class="text-slate-600 text-lg font-medium mb-4">Déjanos tus datos y un especialista técnico te contactará hoy mismo.</p>
                        <div class="flex items-center justify-center gap-6 text-xs font-bold text-slate-400 uppercase tracking-widest mt-4">
                            <span><i class="fas fa-clock text-blue-600 mr-1.5"></i> Respuesta en < 2 horas</span>
                            <span><i class="fas fa-bolt text-indigo-600 mr-1.5"></i> Setup express gratis</span>
                        </div>
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

            // GSAP Metrics Counter Animation
            const counterMetrics = document.querySelectorAll(".js-counter-metric");
            if (counterMetrics.length > 0) {
                gsap.from(counterMetrics, {
                    scrollTrigger: {
                        trigger: "#metrics-bar",
                        start: "top 80%",
                    },
                    innerText: 0,
                    duration: 2,
                    snap: { innerText: 1 },
                    ease: "power2.out",
                    onUpdate: function() {
                        counterMetrics.forEach(metric => {
                            if(metric.innerText) {
                                // Add commas for formatting large numbers
                                const val = parseInt(metric.innerText);
                                if (!isNaN(val)) {
                                    metric.innerText = val.toLocaleString();
                                }
                            }
                        });
                    }
                });
            }

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
