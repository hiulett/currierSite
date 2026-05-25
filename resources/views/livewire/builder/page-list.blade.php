<div>
    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h3 mb-2 uppercase font-black tracking-tight">Website Builder</h1>
            <p class="text-muted">Gestione el contenido y la apariencia de su sitio web público.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6 col-lg-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm transform transition hover:scale-105">
                <div class="card-body py-4 text-center">
                    <div class="stat text-primary bg-primary-light mx-auto mb-3">
                        <i class="align-middle" data-feather="palette"></i>
                    </div>
                    <h5 class="fw-black text-uppercase small">Identidad Visual</h5>
                    <p class="small text-muted mb-3">Colores, logo y tipografía.</p>
                    <a href="{{ route('builder.brand') }}" class="btn btn-sm btn-primary fw-bold">CONFIGURAR</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm transform transition hover:scale-105">
                <div class="card-body py-4 text-center">
                    <div class="stat text-success bg-success-light mx-auto mb-3">
                        <i class="align-middle" data-feather="zap"></i>
                    </div>
                    <h5 class="fw-black text-uppercase small">Widgets & SDK</h5>
                    <p class="small text-muted mb-3">Calculadora y rastreo externo.</p>
                    <a href="{{ route('builder.integrations') }}" class="btn btn-sm btn-success fw-bold text-white">INTEGRAR</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm transform transition hover:scale-105">
                <div class="card-body py-4 text-center">
                    <div class="stat text-info bg-info-light mx-auto mb-3">
                        <i class="align-middle" data-feather="mail"></i>
                    </div>
                    <h5 class="fw-black text-uppercase small">Config. Correo</h5>
                    <p class="small text-muted mb-3">Servidor SMTP personalizado.</p>
                    <a href="{{ route('builder.mail') }}" class="btn btn-sm btn-info fw-bold text-white">AJUSTAR</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm transform transition hover:scale-105">
                <div class="card-body py-4 text-center opacity-50">
                    <div class="stat text-dark bg-light mx-auto mb-3">
                        <i class="align-middle" data-feather="layout"></i>
                    </div>
                    <h5 class="fw-black text-uppercase small">Editor de Páginas</h5>
                    <p class="small text-muted mb-3">Próximamente...</p>
                    <button class="btn btn-sm btn-secondary fw-bold" disabled>BLOQUEADO</button>
                </div>
            </div>
        </div>
    </div>
</div>
