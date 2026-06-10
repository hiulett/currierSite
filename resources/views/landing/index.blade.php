<x-layouts.landing>
    <div x-data="landingPage()">
        <!-- Hero Section -->
        <section class="hero-gradient relative overflow-hidden py-stack-lg lg:py-32">
            <div id="hero-parallax-bg" class="absolute inset-0 opacity-[0.1] pointer-events-none scale-150 origin-top">
                <img src="https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&q=80&w=2400" class="w-full h-full object-cover" alt="background pattern">
            </div>

            <div class="max-w-container-max mx-auto px-margin-desktop grid lg:grid-cols-2 gap-12 items-center relative z-10">
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 bg-white/50 border border-border-light px-3 py-1 rounded-full text-secondary font-label-sm text-label-sm">
                        <span class="material-symbols-outlined text-[16px]">speed</span> Setup en 48 Horas
                    </div>
                    <h1 class="font-headline-lg text-headline-lg text-primary leading-tight">
                        Deja de perder paquetes y clientes. <span class="text-secondary">Automatiza tu courier en 48 horas.</span>
                    </h1>
                    <p class="font-body-lg text-body-lg text-on-surface-variant max-w-xl">
                        La única plataforma SaaS todo en uno que automatiza recepciones con OCR, fideliza a tus clientes mediante puntos de lealtad y gestiona cobros de forma automatizada.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="#contact" class="bg-secondary text-white px-8 py-4 rounded-lg font-label-md text-label-md shadow-lg shadow-secondary/20 hover:scale-[1.02] transition-transform text-center">Probar 30 Días Gratis</a>
                        <a href="#pricing" class="bg-white text-on-secondary-fixed-variant border border-border-light px-8 py-4 rounded-lg font-label-md text-label-md hover:bg-surface-subtle transition-colors text-center">Ver Planes & Precios</a>
                    </div>
                    <div class="flex items-center gap-8 pt-8 opacity-70">
                        <div class="flex items-center gap-2 font-label-sm text-label-sm">
                            <span class="material-symbols-outlined text-data-teal" style="font-variation-settings: 'FILL' 1;">check_circle</span> Soporte 24/7 en Español
                        </div>
                        <div class="flex items-center gap-2 font-label-sm text-label-sm">
                            <span class="material-symbols-outlined text-data-teal" style="font-variation-settings: 'FILL' 1;">check_circle</span> Sin Tarjeta de Crédito
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -top-12 -left-12 w-64 h-64 bg-secondary/10 rounded-full blur-3xl animate-pulse"></div>
                    <div class="relative glass-card p-4 rounded-2xl shadow-2xl overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=2426" alt="Dashboard preview" class="w-full rounded-xl">
                    </div>
                </div>
            </div>
        </section>

        <!-- Trust Signals -->
        <section class="bg-white py-12 border-b border-border-light">
            <div class="max-w-container-max mx-auto px-margin-desktop">
                <p class="text-center font-label-sm text-label-sm text-outline mb-8 tracking-widest uppercase">Liderando la industria logística en la región</p>
                <div class="flex flex-wrap justify-center items-center gap-12 grayscale opacity-60">
                    <span class="font-bold text-headline-sm">LOGY EXPRESS</span>
                    <span class="font-bold text-headline-sm">FASTBOX.PA</span>
                    <span class="font-bold text-headline-sm">GLOBAL CARGO</span>
                    <span class="font-bold text-headline-sm">PANAMA COURIER</span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-12 text-center">
                    <div>
                        <div class="font-headline-md text-headline-md text-secondary">+5000</div>
                        <div class="font-label-sm text-label-sm text-on-surface-variant">Paquetes Procesados</div>
                    </div>
                    <div>
                        <div class="font-headline-md text-headline-md text-secondary">+45</div>
                        <div class="font-label-sm text-label-sm text-on-surface-variant">Couriers Activos</div>
                    </div>
                    <div>
                        <div class="font-headline-md text-headline-md text-secondary">99.9%</div>
                        <div class="font-label-sm text-label-sm text-on-surface-variant">Uptime Garantizado</div>
                    </div>
                    <div>
                        <div class="font-headline-md text-headline-md text-secondary">+13</div>
                        <div class="font-label-sm text-label-sm text-on-surface-variant">Países Conectados</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Process Section -->
        <section class="py-stack-lg bg-surface-subtle" id="features">
            <div class="max-w-container-max mx-auto px-margin-desktop">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h2 class="font-label-md text-label-md text-secondary mb-4 uppercase tracking-widest">Proceso Sin Complicaciones</h2>
                    <h3 class="font-headline-lg text-headline-lg text-on-surface">Tu Courier Operando en 4 Pasos</h3>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-gutter relative">
                    <!-- Step 1 -->
                    <div class="relative group">
                        <div class="bg-white p-8 rounded-xl border border-border-light shadow-sm h-full hover:border-secondary transition-colors">
                            <div class="w-12 h-12 bg-secondary/10 rounded-lg flex items-center justify-center text-secondary font-bold text-xl mb-6">1</div>
                            <h4 class="font-title-lg text-title-lg mb-3">Registro y Setup</h4>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Crea tu cuenta corporativa y configura tu subdominio seguro en minutos. Sin conocimientos técnicos requeridos.</p>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div class="relative group">
                        <div class="bg-white p-8 rounded-xl border border-border-light shadow-sm h-full hover:border-secondary transition-colors">
                            <div class="w-12 h-12 bg-secondary/10 rounded-lg flex items-center justify-center text-secondary font-bold text-xl mb-6">2</div>
                            <h4 class="font-title-lg text-title-lg mb-3">Sube tus Clientes</h4>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Importa tu base de clientes existente con un solo clic. Cada uno recibe acceso automático a su portal personalizado.</p>
                        </div>
                    </div>
                    <!-- Step 3 -->
                    <div class="relative group">
                        <div class="bg-white p-8 rounded-xl border border-border-light shadow-sm h-full hover:border-secondary transition-colors">
                            <div class="w-12 h-12 bg-secondary/10 rounded-lg flex items-center justify-center text-secondary font-bold text-xl mb-6">3</div>
                            <h4 class="font-title-lg text-title-lg mb-3">Ingresa tus Paquetes</h4>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Registra la carga en bodega y el sistema notifica automáticamente a cada cliente por WhatsApp y correo.</p>
                        </div>
                    </div>
                    <!-- Step 4 -->
                    <div class="relative group">
                        <div class="bg-white p-8 rounded-xl border border-border-light shadow-sm h-full hover:border-secondary transition-colors">
                            <div class="w-12 h-12 bg-secondary/10 rounded-lg flex items-center justify-center text-secondary font-bold text-xl mb-6">4</div>
                            <h4 class="font-title-lg text-title-lg mb-3">¡Lanzamiento!</h4>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Tus clientes se registran, pre-alertan carga y pagan sus facturas de forma completamente automatizada.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bento Grid Features -->
        <section class="py-stack-lg bg-white" id="solutions">
            <div class="max-w-container-max mx-auto px-margin-desktop">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                    <div class="max-w-xl">
                        <h2 class="font-label-md text-label-md text-secondary mb-4 uppercase tracking-widest">Innovación Constante</h2>
                        <h3 class="font-headline-lg text-headline-lg text-on-surface">Módulos de Alta Conversión y Eficiencia</h3>
                    </div>
                    <p class="font-body-md text-body-md text-on-surface-variant max-w-sm">Herramientas intuitivas que incrementan la lealtad y eliminan el trabajo manual repetitivo.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-gutter">
                    <!-- Feature Large 1 -->
                    <div class="md:col-span-8 group bento-card">
                        <div class="bento-inner h-full bg-on-secondary-fixed rounded-2xl overflow-hidden relative min-h-[400px]">
                            <div class="p-10 relative z-10 text-white flex flex-col h-full justify-between">
                                <div class="max-w-md">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-6">
                                        <span class="material-symbols-outlined text-white">document_scanner</span>
                                    </div>
                                    <h4 class="font-headline-sm text-headline-sm mb-4">Recepción Inteligente con OCR e IA</h4>
                                    <p class="font-body-md text-body-md opacity-80">Procesa paquetes en milisegundos. Nuestro motor avanzado de OCR extrae de forma inteligente los trackings (Amazon, USPS, DHL) y los asocia a la cuenta del cliente instantáneamente.</p>
                                </div>
                            </div>
                            <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&q=80&w=2070" class="absolute inset-0 w-full h-full object-cover opacity-20" alt="Logistics processing">
                        </div>
                    </div>
                    <!-- Feature Small 1 -->
                    <div class="md:col-span-4 group bento-card">
                        <div class="bento-inner h-full bg-surface-container rounded-2xl p-10 flex flex-col justify-between border border-border-light">
                            <div>
                                <div class="w-12 h-12 bg-secondary rounded-xl flex items-center justify-center mb-6">
                                    <span class="material-symbols-outlined text-white">card_membership</span>
                                </div>
                                <h4 class="font-title-lg text-title-lg mb-4 text-on-surface">Fidelización por Puntos</h4>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">Gamifica tu negocio de casilleros. Permite a tus clientes acumular puntos por cada libra de carga movilizada.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Feature Small 2 -->
                    <div class="md:col-span-4 group bento-card">
                        <div class="bento-inner h-full bg-white rounded-2xl p-10 flex flex-col justify-between border border-border-light shadow-sm">
                            <div>
                                <div class="w-12 h-12 bg-data-teal rounded-xl flex items-center justify-center mb-6 text-white">
                                    <span class="material-symbols-outlined">payments</span>
                                </div>
                                <h4 class="font-title-lg text-title-lg mb-4 text-on-surface">Pasarelas de Pago</h4>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">Checkout directo con Stripe, PayPal y Yappy. Facturación electrónica automatizada que reduce la morosidad a cero.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Feature Large 2 -->
                    <div class="md:col-span-8 group bento-card">
                        <div class="bento-inner h-full bg-secondary text-white rounded-2xl p-10 overflow-hidden relative border border-secondary min-h-[300px]">
                            <div class="relative z-10 flex flex-col md:flex-row gap-10 items-center h-full">
                                <div class="flex-1">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-6">
                                        <span class="material-symbols-outlined">web</span>
                                    </div>
                                    <h4 class="font-headline-sm text-headline-sm mb-4">Portal Web Premium</h4>
                                    <p class="font-body-md text-body-md opacity-90">Calculadora de fletes en vivo, seguimiento en tiempo real, registro de pre-alertas ultra-rápido y una billetera digital integrada para abonos instantáneos.</p>
                                </div>
                                <div class="w-full md:w-1/2 rotate-3 transform scale-110">
                                    <div class="bg-white/10 p-2 rounded-2xl backdrop-blur-md shadow-2xl">
                                        <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&q=80&w=1000" class="rounded-xl w-full h-48 object-cover" alt="Portal preview">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ROI Calculator -->
        <section class="py-stack-lg bg-inverse-surface text-white" id="roi">
            <div class="max-w-container-max mx-auto px-margin-desktop">
                <div class="grid lg:grid-cols-2 gap-20 items-center">
                    <div>
                        <h2 class="font-label-md text-label-md text-secondary mb-4 uppercase tracking-widest">Calculadora de Impacto</h2>
                        <h3 class="font-headline-lg text-headline-lg mb-8">Calcula tu Retorno de Inversión (ROI)</h3>
                        <p class="font-body-lg text-body-lg opacity-80 mb-12">Visualiza las horas de trabajo operativo y el dinero mensual que ahorrarás migrando hoy mismo a LogiSaaS.</p>
                        <div class="space-y-12">
                            <div class="space-y-6">
                                <div class="flex justify-between font-label-md text-label-md">
                                    <span>Paquetes procesados al mes:</span>
                                    <span class="text-secondary" x-text="pkgs.toLocaleString()">5,000</span>
                                </div>
                                <input type="range" min="500" max="20000" step="500" x-model="pkgs" class="w-full h-1 bg-white/20 rounded-lg appearance-none cursor-pointer accent-secondary">
                            </div>
                            <div class="space-y-6">
                                <div class="flex justify-between font-label-md text-label-md">
                                    <span>Costo operativo manual por paquete:</span>
                                    <span class="text-secondary" x-text="'$' + parseFloat(cost).toFixed(2)">$1.50</span>
                                </div>
                                <input type="range" min="0.5" max="5" step="0.25" x-model="cost" class="w-full h-1 bg-white/20 rounded-lg appearance-none cursor-pointer accent-secondary">
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-10 border border-white/10 backdrop-blur-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <p class="font-label-sm text-label-sm text-white/60">Ahorro Mensual Estimado</p>
                                <p class="font-headline-md text-headline-md text-secondary" x-text="'$' + Math.max(0, (pkgs * (cost - 0.3))).toLocaleString()">$4,250</p>
                            </div>
                            <div class="space-y-2">
                                <p class="font-label-sm text-label-sm text-white/60">Ahorro Anualizado</p>
                                <p class="font-headline-md text-headline-md text-data-teal" x-text="'$' + Math.max(0, (pkgs * (cost - 0.3) * 12)).toLocaleString()">$51,000</p>
                            </div>
                            <div class="space-y-2">
                                <p class="font-label-sm text-label-sm text-white/60">Horas Ahorradas</p>
                                <p class="font-headline-md text-headline-md text-white" x-text="Math.max(0, Math.round(pkgs * 0.07)) + 'h'">350h</p>
                            </div>
                            <div class="space-y-2">
                                <p class="font-label-sm text-label-sm text-white/60">Costo LogiSaaS</p>
                                <p class="font-headline-md text-headline-md text-secondary">$0.30/pkg</p>
                            </div>
                        </div>
                        <div class="mt-10 pt-10 border-t border-white/10">
                            <a href="#contact" class="block w-full bg-cta-orange text-white py-4 rounded-lg font-label-md text-label-md hover:scale-[1.01] transition-transform text-center">Migrar Ahora y Ahorrar</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-stack-lg bg-surface-subtle overflow-hidden">
            <div class="max-w-container-max mx-auto px-margin-desktop">
                <div class="text-center mb-16">
                    <h2 class="font-label-md text-label-md text-secondary mb-4 uppercase tracking-widest">Caso de Éxito Real</h2>
                    <h3 class="font-headline-lg text-headline-lg text-on-surface">Nuestros Clientes lo Confirman</h3>
                </div>
                <div class="grid md:grid-cols-3 gap-gutter">
                    <!-- Testimonial 1 -->
                    <div class="bg-white p-10 rounded-2xl shadow-sm border border-border-light relative">
                        <div class="absolute -top-4 -left-4 w-12 h-12 bg-secondary text-white rounded-full flex items-center justify-center font-bold text-xl">"</div>
                        <p class="font-body-md text-body-md text-on-surface-variant italic mb-8">"Antes llamábamos a cada cliente para avisarle que su paquete llegó. Hoy LogiSaaS lo hace solo: WhatsApp, correo, portal de seguimiento... nuestros clientes están siempre informados y nosotros nos enfocamos en crecer."</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-secondary-fixed text-on-secondary-fixed flex items-center justify-center font-bold text-xs uppercase tracking-tighter">CM</div>
                            <div>
                                <h4 class="font-label-md text-label-md">Carlos Mendoza</h4>
                                <p class="font-label-sm text-label-sm text-on-surface-variant">Director General, Logy Express</p>
                            </div>
                        </div>
                    </div>
                    <!-- Testimonial 2 -->
                    <div class="bg-white p-10 rounded-2xl shadow-sm border border-border-light relative">
                        <div class="absolute -top-4 -left-4 w-12 h-12 bg-secondary text-white rounded-full flex items-center justify-center font-bold text-xl">"</div>
                        <p class="font-body-md text-body-md text-on-surface-variant italic mb-8">"Nuestros clientes aman el portal web Premium. Las notificaciones automáticas de llegada vía WhatsApp redujeron las consultas repetitivas a nuestro equipo de soporte en un 60%. ¡Es una excelente inversión!"</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-secondary-fixed text-on-secondary-fixed flex items-center justify-center font-bold text-xs uppercase tracking-tighter">VS</div>
                            <div>
                                <h4 class="font-label-md text-label-md">Valeria Santos</h4>
                                <p class="font-label-sm text-label-sm text-on-surface-variant">Co-fundadora, Fastbox.pa</p>
                            </div>
                        </div>
                    </div>
                    <!-- Testimonial 3 -->
                    <div class="bg-white p-10 rounded-2xl shadow-sm border border-border-light relative">
                        <div class="absolute -top-4 -left-4 w-12 h-12 bg-secondary text-white rounded-full flex items-center justify-center font-bold text-xl">"</div>
                        <p class="font-body-md text-body-md text-on-surface-variant italic mb-8">"Migrar a LogiSaaS fue la mejor decisión para escalar. Implementamos todo en menos de 48 horas y en el primer mes procesamos más de 15,000 paquetes sin errores manuales. El ROI es inmediato."</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-secondary-fixed text-on-secondary-fixed flex items-center justify-center font-bold text-xs uppercase tracking-tighter">RC</div>
                            <div>
                                <h4 class="font-label-md text-label-md">Roberto Chen</h4>
                                <p class="font-label-sm text-label-sm text-on-surface-variant">CEO, Panama Courier</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section class="py-stack-lg bg-white" id="pricing">
            <div class="max-w-container-max mx-auto px-margin-desktop">
                <div class="text-center mb-16">
                    <h2 class="font-headline-lg text-headline-lg text-on-surface mb-4">Inversión Transparente que Escala</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant">Sin comisiones ocultas. Cancela cuando quieras.</p>
                </div>
                <div class="grid md:grid-cols-3 gap-gutter">
                    <!-- Basic -->
                    <div class="border border-border-light rounded-xl p-8 flex flex-col h-full hover:shadow-xl transition-shadow bg-white">
                        <div class="mb-8">
                            <h4 class="font-headline-sm text-headline-sm mb-2">Startup</h4>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Poder operativo completo para iniciar.</p>
                            <div class="mt-6">
                                <span class="font-headline-lg text-headline-lg text-on-surface">$30</span>
                                <span class="text-on-surface-variant">/ mes</span>
                            </div>
                        </div>
                        <ul class="space-y-4 mb-10 flex-grow">
                            <li class="flex items-center gap-3 font-body-sm text-body-sm text-on-surface">
                                <span class="material-symbols-outlined text-data-teal text-[20px]">check</span> Recepción Inteligente (OCR)
                            </li>
                            <li class="flex items-center gap-3 font-body-sm text-body-sm text-on-surface">
                                <span class="material-symbols-outlined text-data-teal text-[20px]">check</span> Gestión Masiva de Manifiestos
                            </li>
                            <li class="flex items-center gap-3 font-body-sm text-body-sm text-on-surface">
                                <span class="material-symbols-outlined text-data-teal text-[20px]">check</span> Facturación Automática
                            </li>
                            <li class="flex items-center gap-3 font-body-sm text-body-sm text-on-surface">
                                <span class="material-symbols-outlined text-data-teal text-[20px]">check</span> Billetera Digital (Wallet)
                            </li>
                            <li class="flex items-center gap-3 font-body-sm text-body-sm text-outline">
                                <span class="material-symbols-outlined text-[20px]">block</span> Notificaciones WhatsApp
                            </li>
                        </ul>
                        <a href="#contact" class="w-full py-4 border border-secondary text-secondary rounded-lg font-label-md text-label-md hover:bg-secondary/5 transition-colors text-center">Empezar de Inmediato</a>
                    </div>
                    <!-- Business (Featured) -->
                    <div class="border-2 border-secondary rounded-xl p-8 flex flex-col h-full relative shadow-2xl scale-105 z-10 bg-surface-bright">
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-secondary text-white px-4 py-1 rounded-full text-label-sm font-bold tracking-widest uppercase">Más Popular</div>
                        <div class="mb-8">
                            <h4 class="font-headline-sm text-headline-sm mb-2">Business</h4>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Automatización y comunicación 24/7.</p>
                            <div class="mt-6">
                                <span class="font-headline-lg text-headline-lg text-on-surface">$45</span>
                                <span class="text-on-surface-variant">/ mes</span>
                            </div>
                        </div>
                        <ul class="space-y-4 mb-10 flex-grow">
                            <li class="flex items-center gap-3 font-body-sm text-body-sm font-semibold text-on-surface">
                                <span class="material-symbols-outlined text-data-teal text-[20px]">check</span> Todo lo de Startup
                            </li>
                            <li class="flex items-center gap-3 font-body-sm text-body-sm text-on-surface">
                                <span class="material-symbols-outlined text-secondary text-[20px]">verified</span> Notificaciones WhatsApp
                            </li>
                            <li class="flex items-center gap-3 font-body-sm text-body-sm text-on-surface">
                                <span class="material-symbols-outlined text-data-teal text-[20px]">check</span> ChatBot de Rastreo Automático
                            </li>
                            <li class="flex items-center gap-3 font-body-sm text-body-sm text-on-surface">
                                <span class="material-symbols-outlined text-data-teal text-[20px]">check</span> Configuración Marca Blanca
                            </li>
                            <li class="flex items-center gap-3 font-body-sm text-body-sm text-on-surface">
                                <span class="material-symbols-outlined text-data-teal text-[20px]">check</span> Sistema de Puntos (Loyalty)
                            </li>
                        </ul>
                        <a href="#contact" class="w-full py-4 bg-secondary text-white rounded-lg font-label-md text-label-md shadow-lg shadow-secondary/20 hover:opacity-90 transition-opacity text-center">Solicitar Demo Gratis</a>
                    </div>
                    <!-- Enterprise -->
                    <div class="border border-border-light rounded-xl p-8 flex flex-col h-full hover:shadow-xl transition-shadow bg-white">
                        <div class="mb-8">
                            <h4 class="font-headline-sm text-headline-sm mb-2">Enterprise</h4>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Para corporaciones y redes globales.</p>
                            <div class="mt-6">
                                <span class="font-headline-lg text-headline-lg text-on-surface">$55</span>
                                <span class="text-on-surface-variant">/ mes</span>
                            </div>
                        </div>
                        <ul class="space-y-4 mb-10 flex-grow">
                            <li class="flex items-center gap-3 font-body-sm text-body-sm font-semibold text-on-surface">
                                <span class="material-symbols-outlined text-data-teal text-[20px]">check</span> Todo lo de Business
                            </li>
                            <li class="flex items-center gap-3 font-body-sm text-body-sm text-on-surface">
                                <span class="material-symbols-outlined text-secondary text-[20px]">verified</span> App Móvil iOS & Android
                            </li>
                            <li class="flex items-center gap-3 font-body-sm text-body-sm text-on-surface">
                                <span class="material-symbols-outlined text-data-teal text-[20px]">check</span> Perfiles para Conductores
                            </li>
                            <li class="flex items-center gap-3 font-body-sm text-body-sm text-on-surface">
                                <span class="material-symbols-outlined text-data-teal text-[20px]">check</span> Notificaciones Push Real-Time
                            </li>
                            <li class="flex items-center gap-3 font-body-sm text-body-sm text-on-surface">
                                <span class="material-symbols-outlined text-data-teal text-[20px]">check</span> Soporte Prioritario 1-a-1
                            </li>
                        </ul>
                        <a href="#contact" class="w-full py-4 border border-on-surface text-on-surface rounded-lg font-label-md text-label-md hover:bg-on-surface/5 transition-colors text-center">Hablar con Ventas</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA / Lead Form -->
        <section id="contact" class="py-stack-lg bg-on-secondary-fixed text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-secondary to-transparent"></div>
            </div>
            <div class="max-w-container-max mx-auto px-margin-desktop grid lg:grid-cols-2 gap-20 relative z-10 items-center">
                <div>
                    <h2 class="font-headline-lg text-headline-lg mb-6 leading-tight">Construyamos el Futuro de tu Courier</h2>
                    <p class="font-body-lg text-body-lg opacity-80 mb-8">Déjanos tus datos y un especialista técnico te contactará hoy mismo. Respuesta garantizada en menos de 2 horas.</p>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-secondary">verified_user</span>
                            <span class="font-body-md text-body-md">Garantía de Satisfacción de 30 Días</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-secondary">cloud_done</span>
                            <span class="font-body-md text-body-md">Infraestructura AWS Segura</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-10 text-on-surface shadow-2xl">
                    <livewire:public.contact-form />
                </div>
            </div>
        </section>
    </div>

    <script>
        function landingPage() {
            return {
                pkgs: 5000,
                cost: 1.5,
                init() {
                    // GSAP Parallax initialization
                    if (typeof gsap !== 'undefined') {
                        gsap.registerPlugin(ScrollTrigger);

                        gsap.to("#hero-parallax-bg", {
                            y: "300",
                            ease: "none",
                            scrollTrigger: {
                                trigger: "body",
                                start: "top top",
                                end: "bottom top",
                                scrub: 1
                            }
                        });
                    }
                }
            }
        }
    </script>
</x-layouts.landing>
