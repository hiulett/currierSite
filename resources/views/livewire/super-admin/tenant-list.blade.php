<div>
    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h3 mb-2 uppercase font-black tracking-tight">Gestión de Ecosistema (Tenants)</h1>
            <p class="text-muted">Controle las empresas registradas y sus funcionalidades permitidas.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message"><strong>¡Éxito!</strong> {{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 border-bottom">
            <h5 class="card-title mb-0 uppercase font-black small">Directorio de Empresas</h5>
            <div class="input-group input-group-sm" style="width: 300px;">
                <input type="text" wire:model.live="search" class="form-control" placeholder="Buscar por nombre o dominio...">
                <span class="input-group-text bg-white"><i class="align-middle text-muted" data-feather="search" style="width: 14px;"></i></span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped my-0">
                <thead>
                    <tr>
                        <th class="ps-4 cursor-pointer" wire:click="sortBy('name')">
                            Empresa / Tenant
                            @if($sortField === 'name')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="cursor-pointer" wire:click="sortBy('domain')">
                            Dominio Principal
                            @if($sortField === 'domain')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="cursor-pointer" wire:click="sortBy('status')">
                            Estado
                            @if($sortField === 'status')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="cursor-pointer" wire:click="sortBy('created_at')">
                            Fecha Registro
                            @if($sortField === 'created_at')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="pe-4 text-end">Control Maestro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tenants as $t)
                        <tr wire:key="tenant-{{ $t->id }}">
                            <td class="ps-4">
                                <div class="fw-black text-dark">{{ $t->name }}</div>
                                <div class="text-primary small font-bold">{{ $t->subdomain }}.logisaas.com</div>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $t->domain }}</span></td>
                            <td>
                                @if($t->status === 'active')
                                    <span class="badge bg-success">ACTIVO</span>
                                @else
                                    <span class="badge bg-danger">{{ strtoupper($t->status) }}</span>
                                @endif
                            </td>
                            <td class="small fw-bold text-muted">{{ $t->created_at->format('d/m/Y') }}</td>
                            <td class="pe-4 text-end">
                                <div class="btn-group">
                                    <button wire:click="editTenant({{ $t->id }})" class="btn btn-sm btn-light border shadow-sm" title="Editar">
                                        <i class="align-middle text-info" data-feather="edit-2"></i>
                                    </button>
                                    <button wire:click="configureBilling({{ $t->id }})" class="btn btn-sm btn-light border shadow-sm" title="Facturación">
                                        <i class="align-middle text-warning" data-feather="dollar-sign"></i>
                                        <span class="ms-1 d-none d-md-inline fw-bold text-uppercase" style="font-size: 0.6rem;">Pago</span>
                                    </button>
                                    <button wire:click="configureReports({{ $t->id }})" class="btn btn-sm btn-light border shadow-sm" title="Funcionalidades">
                                        <i class="align-middle text-primary" data-feather="settings"></i>
                                        <span class="ms-1 d-none d-md-inline fw-bold text-uppercase" style="font-size: 0.6rem;">Módulos</span>
                                    </button>
                                    <button wire:click="impersonate({{ $t->id }})" class="btn btn-sm btn-light border shadow-sm" title="Login As">
                                        <i class="align-middle text-dark" data-feather="user-check"></i>
                                        <span class="ms-1 d-none d-md-inline fw-bold text-uppercase" style="font-size: 0.6rem;">Entrar</span>
                                    </button>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-sm btn-light border shadow-sm dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="align-middle" data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @if($t->status === 'active')
                                                <button wire:click="updateStatus({{ $t->id }}, 'suspended')" class="dropdown-item text-danger fw-bold">Suspender Empresa</button>
                                            @else
                                                <button wire:click="updateStatus({{ $t->id }}, 'active')" class="dropdown-item text-success fw-bold">Activar Empresa</button>
                                            @endif
                                            <div class="dropdown-divider"></div>
                                            <button class="dropdown-item text-warning fw-bold">Modo Mantenimiento</button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer border-top bg-light">
            {{ $tenants->links() }}
        </div>
    </div>

    <!-- Modal for Tenant Profile Editing -->
    @if($editing_tenant_id)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                    <div class="modal-header bg-info text-white p-4">
                        <h5 class="modal-title uppercase font-black tracking-widest text-white">Editar Perfil de Empresa</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="$set('editing_tenant_id', null)"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-black uppercase text-muted">Nombre Comercial</label>
                                <input type="text" wire:model="tenant_name" class="form-control border-0 shadow-sm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-black uppercase text-muted">Subdominio</label>
                                <input type="text" wire:model="tenant_subdomain" class="form-control border-0 shadow-sm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-black uppercase text-muted">Dominio Propio</label>
                                <input type="text" wire:model="tenant_domain" class="form-control border-0 shadow-sm" placeholder="empresa.com">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-black uppercase text-muted">Plan de Servicio</label>
                                <select wire:model="tenant_plan_id" class="form-select border-0 shadow-sm">
                                    @foreach(\App\Models\Plan::all() as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }} - ${{ $p->price }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-black uppercase text-muted">Estado del Servicio</label>
                                <select wire:model="tenant_status" class="form-select border-0 shadow-sm">
                                    <option value="active">Activo</option>
                                    <option value="suspended">Suspendido</option>
                                    <option value="trial">Prueba</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-top-0 p-4 pt-0 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light fw-bold" wire:click="$set('editing_tenant_id', null)">CANCELAR</button>
                        <button type="button" class="btn btn-info text-white px-4 shadow-lg fw-black" wire:click="saveTenant">ACTUALIZAR EMPRESA</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal for Features/Modules (Bootstrap 5) -->
    @if($configuring_tenant_id)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                    <div class="modal-header bg-dark text-white p-4">
                        <h5 class="modal-title uppercase font-black tracking-widest text-white">
                            Control de Módulos: {{ \App\Models\Tenant::find($configuring_tenant_id)->name }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeConfig"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <div class="alert alert-info py-2 small">
                            <i data-feather="info" class="me-2" style="width: 14px;"></i>
                            Active o desactive los módulos a los que esta empresa tiene acceso.
                        </div>

                        <div class="list-group shadow-sm mb-4">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold">Módulo de Reempaque</div>
                                    <div class="small text-muted">Consolidación de carga para clientes.</div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" wire:model="features.repack">
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold">WhatsApp IA Agent</div>
                                    <div class="small text-muted">Chatbot inteligente para consultas.</div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" wire:model="features.whatsapp_ia">
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold">Gestión de Tickets</div>
                                    <div class="small text-muted">Sistema de soporte técnico interno.</div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" wire:model="features.tickets">
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold">Pagos en Línea</div>
                                    <div class="small text-muted">Acceso a pasarelas Stripe/PayPal.</div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" wire:model="features.online_payments">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-top-0 p-4 pt-0 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light fw-bold" wire:click="closeConfig">CERRAR</button>
                        <button type="button" class="btn btn-primary px-4 shadow-lg fw-black" wire:click="saveFeatures">GUARDAR CAMBIOS</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal for Billing Management -->
    @if($configuring_billing_id)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                    <div class="modal-header bg-warning text-dark p-4">
                        <h5 class="modal-title uppercase font-black tracking-widest">
                            Control de Cobranza: {{ \App\Models\Tenant::find($configuring_billing_id)->name }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="$set('configuring_billing_id', null)"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <div class="mb-4">
                            <label class="form-label small fw-black uppercase text-muted">Fecha de Próximo Pago</label>
                            <input type="date" wire:model="next_billing_at" class="form-control form-control-lg border-0 shadow-sm">
                            <div class="form-text xsmall">El sistema activará el banner automáticamente si la fecha es menor a hoy.</div>
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold">Banner de Alerta</div>
                                        <div class="small text-muted">Forzar visualización del aviso de pago.</div>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model="payment_warning_active">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-top-0 p-4 pt-0 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light fw-bold" wire:click="$set('configuring_billing_id', null)">CERRAR</button>
                        <button type="button" class="btn btn-warning px-4 shadow-lg fw-black" wire:click="saveBilling">GUARDAR COBRANZA</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
