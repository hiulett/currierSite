<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-6">
            <h2 class="h3 mb-0 uppercase font-black tracking-tight text-dark">Configuración de Correo (SMTP)</h2>
            <p class="text-muted small mb-0">Personaliza el servidor desde el cual se envían las notificaciones a tus clientes.</p>
        </div>
        <div class="col-12 col-md-6 text-md-end mt-3 mt-md-0">
            <button wire:click="save" class="btn btn-primary fw-black shadow-lg px-4">
                <i class="align-middle me-1" data-feather="save"></i> GUARDAR CONFIGURACIÓN
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
            <!-- SMTP Server Settings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 uppercase font-black small text-primary"><i class="align-middle me-2" data-feather="server"></i> Servidor de Salida</h5>
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
                            <div class="form-text xsmall mt-1">Este es el correo que verán tus clientes al recibir notificaciones.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label xsmall font-black text-uppercase text-muted">Nombre Remitente</label>
                            <input type="text" wire:model="mail_from_name" class="form-control border-2 fw-bold">
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
                        <h5 class="card-title text-white mb-0 uppercase font-black small tracking-widest">¿Por qué configurar esto?</h5>
                    </div>
                    <p class="text-white-50 small leading-relaxed">
                        Por defecto, los correos se envían desde nuestro servidor general. Al configurar tu propio SMTP:
                    </p>
                    <ul class="text-white-50 small ps-3 mb-0">
                        <li class="mb-2">Tus clientes verán que los correos vienen directamente de <strong>tu marca</strong>.</li>
                        <li class="mb-2">Mejoras drásticamente la <strong>entregabilidad</strong> (evitas la carpeta de SPAM).</li>
                        <li>Generas mayor confianza y profesionalismo en cada notificación de carga.</li>
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
                    <button class="btn btn-outline-dark btn-sm rounded-pill px-4 fw-bold disabled">ENVIAR TEST (PRÓXIMAMENTE)</button>
                </div>
            </div>
        </div>
    </div>
</div>
