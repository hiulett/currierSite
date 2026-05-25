<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12 col-lg-8 mx-auto">
            <h2 class="h3 mb-0 uppercase font-black tracking-tight text-dark">Agente WhatsApp IA</h2>
            <p class="text-muted small">Automatización y consultas en tiempo real vía WhatsApp para tu casillero.</p>
        </div>
    </div>

    @if(!$isConnected)
        <div class="row">
            <div class="col-12 col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm p-4 p-md-5 text-center" style="border-radius: 1.5rem;">
                    <div class="card-body py-5">
                        <div class="stat bg-success-light text-success rounded-circle mx-auto mb-4" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                            <i data-feather="message-circle" style="width: 50px; height: 50px;"></i>
                        </div>
                        <h3 class="fw-black text-dark uppercase mb-3">Conecta tu Casillero</h3>
                        <p class="text-muted mb-5 px-lg-5">Vincular tu cuenta te permite rastrear carga, consultar saldo y recibir alertas automáticas de llegada directamente a tu celular.</p>

                        <div class="d-grid gap-3 col-md-8 mx-auto">
                            <button wire:click="connect" class="btn btn-success btn-lg fw-black uppercase py-4 shadow-lg" style="border-radius: 1rem;">
                                <i class="align-middle me-2" data-feather="link"></i> VINCULAR MI WHATSAPP
                            </button>
                            <span class="text-muted xsmall uppercase font-bold tracking-widest">Servicio Gratuito para Clientes VIP</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <!-- Sidebar: Connection Status & Commands -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                    <div class="card-header bg-success text-white py-3">
                        <h6 class="card-title text-white mb-0 uppercase font-black small tracking-widest">Estado del Servicio</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-success rounded-circle me-3 animate-pulse shadow-success" style="width: 12px; height: 12px;"></div>
                            <span class="fw-black text-dark text-uppercase small">Cuenta Vinculada</span>
                        </div>
                        <p class="text-muted small mb-4">Tu número principal está recibiendo notificaciones automáticas de movimiento de carga.</p>
                        <hr class="my-4">
                        <button class="btn btn-outline-danger btn-sm w-100 fw-black uppercase">
                            <i class="align-middle me-1" data-feather="log-out" style="width: 12px;"></i> Desvincular Cuenta
                        </button>
                    </div>
                </div>

                <div class="card bg-dark text-white border-0 shadow-sm mb-4 overflow-hidden">
                    <div class="card-body p-4">
                        <h6 class="text-white-50 xsmall font-black uppercase tracking-widest mb-3">Comandos Disponibles</h6>
                        <div class="list-group list-group-flush bg-transparent">
                            <div class="list-group-item bg-transparent border-white border-opacity-10 px-0 py-2 d-flex align-items-center text-white">
                                <i data-feather="package" class="text-primary me-2" style="width: 14px;"></i>
                                <span class="small font-bold">"Mis paquetes"</span>
                            </div>
                            <div class="list-group-item bg-transparent border-white border-opacity-10 px-0 py-2 d-flex align-items-center text-white">
                                <i data-feather="dollar-sign" class="text-success me-2" style="width: 14px;"></i>
                                <span class="small font-bold">"Mi saldo"</span>
                            </div>
                            <div class="list-group-item bg-transparent border-0 px-0 py-2 d-flex align-items-center text-white">
                                <i data-feather="search" class="text-info me-2" style="width: 14px;"></i>
                                <span class="small font-bold">"Tracking [número]"</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main: Chat Simulation -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm overflow-hidden d-flex flex-column h-100" style="min-height: 600px;">
                    <!-- Chat Header -->
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <div class="d-flex align-items-center">
                            <div class="stat bg-success-light text-success rounded-circle p-2 me-3">
                                <i data-feather="cpu" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <h6 class="fw-black text-dark mb-0">LogiBot IA <span class="badge bg-success-light text-success ms-2 xsmall">ONLINE</span></h6>
                                <p class="text-muted xsmall mb-0">Asistente Virtual Inteligente</p>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Body -->
                    <div class="card-body p-4 bg-light bg-opacity-50 overflow-auto" style="flex: 1;">
                        <div class="d-flex flex-column gap-4">
                            <!-- Bot Welcome -->
                            <div class="d-flex justify-content-start">
                                <div class="bg-white p-3 shadow-sm border" style="border-radius: 0 1.5rem 1.5rem 1.5rem; max-width: 85%;">
                                    <p class="text-dark small mb-0 fw-medium leading-relaxed">
                                        ¡Hola! Soy tu asistente de **LogiSaaS**. ¿En qué puedo ayudarte hoy? Puedes preguntarme por tus paquetes o tu saldo actual.
                                    </p>
                                </div>
                            </div>

                            @if($botReply)
                                <div class="d-flex justify-content-end mb-1">
                                    <div class="bg-primary text-white p-3 shadow-sm" style="border-radius: 1.5rem 1.5rem 0 1.5rem; max-width: 85%;">
                                        <p class="small mb-0 fw-bold">Consulta realizada</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start animate-fade-in">
                                    <div class="bg-white p-3 shadow-sm border border-success" style="border-radius: 0 1.5rem 1.5rem 1.5rem; max-width: 85%;">
                                        <div class="d-flex align-items-center mb-2">
                                            <i data-feather="cpu" class="text-success me-2" style="width: 12px;"></i>
                                            <span class="xsmall font-black text-success uppercase">Respuesta de IA</span>
                                        </div>
                                        <p class="text-dark small mb-0 fw-medium leading-relaxed">{{ $botReply }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="card-footer bg-white border-top p-4">
                        <div class="input-group input-group-lg border rounded-pill overflow-hidden shadow-sm">
                            <input type="text" wire:model="simulatedMessage" wire:keydown.enter="sendSimulatedMessage"
                                   class="form-control border-0 ps-4 bg-white" placeholder="Escribe tu consulta aquí...">
                            <button wire:click="sendSimulatedMessage" class="btn btn-success border-0 px-4 fw-black">
                                <i class="align-middle" data-feather="send"></i>
                            </button>
                        </div>
                        <p class="text-center text-muted xsmall mt-3 mb-0 italic">Esta es una interfaz de prueba para el motor de Inteligencia Artificial que alimenta nuestro canal de WhatsApp.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
