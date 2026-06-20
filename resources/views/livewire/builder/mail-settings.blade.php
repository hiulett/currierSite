<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-6">
            <h2 class="h3 mb-0 uppercase font-black tracking-tight text-dark">Configuración de Correo</h2>
            <p class="text-muted small mb-0">Personaliza el método y servidor desde el cual se envían las notificaciones.</p>
        </div>
        <div class="col-12 col-md-6 text-md-end mt-3 mt-md-0">
            <button wire:click="save" wire:loading.attr="disabled" class="btn btn-primary fw-black shadow-lg px-4">
                <span wire:loading.remove wire:target="save"><i class="align-middle me-1" data-feather="save"></i> GUARDAR CONFIGURACIÓN</span>
                <span wire:loading wire:target="save"><span class="spinner-border spinner-border-sm me-1"></span> GUARDANDO...</span>
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message">
                <strong>¡Éxito!</strong> {{ session('message') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12 col-lg-8">
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible shadow-sm mb-4" role="alert">
                    <div class="alert-message">
                        <strong>¡Error!</strong> {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Driver Selection -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 uppercase font-black small text-primary"><i class="align-middle me-2" data-feather="settings"></i> Método de Envío</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label xsmall font-black text-uppercase text-muted">Protocolo / API</label>
                            <select wire:model.live="mail_driver" class="form-select border-2 fw-bold">
                                <option value="smtp">SMTP (Tradicional)</option>
                                <option value="sendgrid_api">SendGrid API (Recomendado)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SMTP Server Settings -->
            @if($mail_driver === 'smtp')
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 uppercase font-black small text-primary"><i class="align-middle me-2" data-feather="server"></i> Configuración SMTP</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="form-label xsmall font-black text-uppercase text-muted">Host SMTP</label>
                            <input type="text" wire:model="mail_host" class="form-control border-2 fw-bold" placeholder="smtp.mailtrap.io">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label xsmall font-black text-uppercase text-muted">Puerto</label>
                            <input type="text" wire:model="mail_port" class="form-control border-2 fw-bold" placeholder="587">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label xsmall font-black text-uppercase text-muted">Usuario / Email</label>
                            <input type="text" wire:model="mail_username" class="form-control border-2 fw-bold">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label xsmall font-black text-uppercase text-muted">Contraseña</label>
                            <input type="password" wire:model="mail_password" class="form-control border-2 fw-bold">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label xsmall font-black text-uppercase text-muted">Encriptación</label>
                            <select wire:model="mail_encryption" class="form-select border-2 fw-bold">
                                <option value="tls">TLS (Recomendado)</option>
                                <option value="ssl">SSL</option>
                                <option value="none">Ninguna</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- SendGrid API Settings -->
            <div class="card border-0 shadow-sm mb-4 border-start border-primary border-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 uppercase font-black small text-primary"><i class="align-middle me-2" data-feather="zap"></i> Configuración SendGrid API</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label xsmall font-black text-uppercase text-muted">SendGrid API Key</label>
                            <input type="password" wire:model="mail_password" class="form-control border-2 fw-bold" placeholder="SG.xxxxxxxxxxxxxxxx">
                            <div class="form-text xsmall mt-2">Introduce tu clave de API generada en el panel de SendGrid. El envío se realizará mediante peticiones HTTP seguras (más rápido y fiable).</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Sender Identity -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 uppercase font-black small text-primary"><i class="align-middle me-2" data-feather="user"></i> Identidad del Remitente</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label xsmall font-black text-uppercase text-muted">Email Remitente</label>
                            <input type="email" wire:model="mail_from_address" class="form-control border-2 fw-bold" placeholder="no-reply@tuempresa.com">
                            <div class="form-text xsmall mt-1">Este correo debe estar verificado en tu proveedor (SendGrid/SMTP).</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label xsmall font-black text-uppercase text-muted">Nombre Remitente</label>
                            <input type="text" wire:model="mail_from_name" class="form-control border-2 fw-bold">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-2">
            <!-- Email Templates -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 uppercase font-black small text-primary"><i class="align-middle me-2" data-feather="file-text"></i> Plantillas de Correo (Mensajes)</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="alert alert-soft-primary small mb-4">
                        <i data-feather="info" class="me-1"></i>
                        <strong>Variables disponibles:</strong> Puedes usar estos comodines en tus textos y el sistema los reemplazará por los datos reales al enviar el correo.
                        <div class="mt-2 text-dark font-monospace">
                            <code>{nombre_cliente}</code>, <code>{numero_documento}</code>, <code>{monto_total}</code>, <code>{fecha_vencimiento}</code>, <code>{nombre_empresa}</code>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label xsmall font-black text-uppercase text-muted">Plantilla Facturas</label>
                            <textarea wire:model="invoice_email_template" class="form-control border-2" rows="10"></textarea>
                            <div class="form-text xsmall mt-1">Este texto se enviará en el cuerpo del correo al enviar una factura.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label xsmall font-black text-uppercase text-muted">Plantilla Cotizaciones</label>
                            <textarea wire:model="quotation_email_template" class="form-control border-2" rows="10"></textarea>
                            <div class="form-text xsmall mt-1">Este texto se enviará en el cuerpo del correo al enviar una cotización.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-lg-4">
            <div class="card bg-dark text-white border-0 shadow-lg mb-4" style="border-radius: 1rem;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="stat bg-primary text-white rounded-circle me-3">
                            <i data-feather="zap"></i>
                        </div>
                        <h5 class="card-title text-white mb-0 uppercase font-black small tracking-widest">¿Por qué usar API?</h5>
                    </div>
                    <p class="text-white-50 small leading-relaxed">
                        El envío por API es superior al SMTP tradicional:
                    </p>
                    <ul class="text-white-50 small ps-3 mb-0">
                        <li class="mb-2"><strong>Mayor Velocidad:</strong> No hay negociación de conexión manual.</li>
                        <li class="mb-2"><strong>Más Seguro:</strong> No requiere abrir puertos de red (25, 465, 587).</li>
                        <li><strong>Mejor Entregabilidad:</strong> Menos propenso a ser bloqueado por firewalls de servidores.</li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <div class="mb-3 text-warning">
                        <i data-feather="alert-triangle" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                    </div>
                    <h6 class="fw-black text-dark uppercase small">Prueba de Conexión</h6>
                    <p class="text-muted xsmall mb-3">Una vez guardes, te recomendamos enviar un correo de prueba.</p>
                    
                    <div class="mb-3 text-start">
                        <label class="form-label xsmall font-black text-uppercase text-muted">Enviar prueba a:</label>
                        <input type="email" wire:model="test_email_address" class="form-control form-control-sm border-2 fw-bold text-center" placeholder="tu@correo.com">
                    </div>

                    <button wire:click="sendTestMail" wire:loading.attr="disabled" class="btn btn-outline-dark btn-sm rounded-pill px-4 fw-bold w-100">
                        <span wire:loading.remove wire:target="sendTestMail">ENVIAR TEST</span>
                        <span wire:loading wire:target="sendTestMail">
                            <span class="spinner-border spinner-border-sm me-1"></span> ENVIANDO...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
