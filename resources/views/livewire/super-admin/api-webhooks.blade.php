<div>
    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h3 mb-2 uppercase font-black tracking-tight">API & Webhooks Globales</h1>
            <p class="text-muted">Gestione las integraciones externas y la salida de datos del núcleo.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small">Endpoints de Webhook</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped my-0">
                        <thead>
                            <tr>
                                <th class="ps-4">URL de Destino</th>
                                <th>Eventos</th>
                                <th>Estado</th>
                                <th class="pe-4 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">https://analytics.external.com/api/webhook</div>
                                    <div class="small text-muted">Analytics Sync</div>
                                </td>
                                <td><span class="badge bg-primary-light text-primary">package.created</span></td>
                                <td><span class="badge bg-success">ACTIVO</span></td>
                                <td class="pe-4 text-end">
                                    <button class="btn btn-sm btn-light border shadow-sm"><i data-feather="edit-2" style="width: 12px;"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light border-top text-end">
                    <button class="btn btn-primary btn-sm fw-bold">AÑADIR ENDPOINT</button>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small">Logs de Ejecución</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div>
                                <span class="badge bg-success me-2">200 OK</span>
                                <span class="fw-bold small">package.delivered</span>
                                <div class="text-muted" style="font-size: 0.65rem;">Enviado a https://notify.service.com - hace 10 min</div>
                            </div>
                            <button class="btn btn-xs btn-light border">Payload</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small">Credenciales Maestras</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label small font-black text-uppercase text-muted">Master Client ID</label>
                        <div class="input-group">
                            <input type="text" value="LGS_MASTER_772" class="form-control bg-light border-0 fw-mono" readonly>
                            <button class="btn btn-light border"><i data-feather="copy" style="width: 14px;"></i></button>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small font-black text-uppercase text-muted">Master Secret Key</label>
                        <div class="input-group">
                            <input type="password" value="************************" class="form-control bg-light border-0 fw-mono" readonly>
                            <button class="btn btn-light border"><i data-feather="eye" style="width: 14px;"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-info text-white shadow-lg">
                <div class="card-body p-4">
                    <h5 class="text-white uppercase font-black mb-3 small">Documentación API</h5>
                    <p class="small mb-4 opacity-75">Integre otros servicios con el núcleo de {{ \App\Models\AppSetting::get('platform_name', config('app.name')) }} usando nuestra API REST profesional.</p>
                    <a href="#" class="btn btn-light btn-sm w-100 fw-bold">LEER DOCUMENTACIÓN</a>
                </div>
            </div>
        </div>
    </div>
</div>
