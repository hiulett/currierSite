<div>
    <div class="row mb-3">
        <div class="col-md-8">
            <h1 class="h3 mb-2 uppercase font-black tracking-tight">Ajustes del Núcleo</h1>
            <p class="text-muted">Configuración global de la plataforma LogiSaaS.</p>
        </div>
        <div class="col-md-4 text-end">
            <button wire:click="save" class="btn btn-primary shadow-sm fw-black">
                <i class="align-middle me-1" data-feather="save"></i> GUARDAR CAMBIOS
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message"><strong>¡Éxito!</strong> {{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small">Identidad de la Plataforma</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label small font-black text-uppercase text-muted">Nombre del Sistema</label>
                        <input type="text" wire:model="platform_name" class="form-control form-control-lg fw-bold border-2">
                    </div>
                    <div class="mb-0">
                        <label class="form-label small font-black text-uppercase text-muted">Email de Soporte Global</label>
                        <input type="email" wire:model="support_email" class="form-control border-2">
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small text-danger">Mantenimiento y Estado</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label small font-black text-uppercase text-muted">Estado del Sistema</label>
                        <select wire:model="system_status" class="form-select border-2 fw-bold">
                            <option value="online">Operativo (Online)</option>
                            <option value="maintenance">Mantenimiento Global</option>
                            <option value="restricted">Solo Lectura</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small font-black text-uppercase text-muted">Mensaje de Mantenimiento</label>
                        <textarea wire:model="maintenance_message" class="form-control border-2" rows="3" placeholder="Estamos realizando mejoras técnicas..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small">Parámetros por Defecto</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label small font-black text-uppercase text-muted">Moneda Base</label>
                        <select wire:model="base_currency" class="form-select border-2">
                            <option value="USD">USD - Dólar Americano</option>
                            <option value="EUR">EUR - Euro</option>
                            <option value="PAB">PAB - Balboa Panameño</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small font-black text-uppercase text-muted">Zona Horaria Servidor</label>
                        <select wire:model="timezone" class="form-select border-2">
                            <option value="America/Panama">America/Panama</option>
                            <option value="America/New_York">America/New_York</option>
                            <option value="UTC">UTC (Universal)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card bg-dark text-white shadow-lg">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <i data-feather="database" style="width: 40px; height: 40px;" class="text-primary"></i>
                    </div>
                    <h5 class="text-white uppercase font-black mb-2">Backups del Sistema</h5>
                    <p class="small opacity-75">La última copia de seguridad automática se realizó hace 4 horas.</p>
                    <button class="btn btn-primary btn-sm fw-bold">EJECUTAR BACKUP AHORA</button>
                </div>
            </div>
        </div>
    </div>
</div>
