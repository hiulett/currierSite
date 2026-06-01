<div>
    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h3 mb-2 uppercase font-black tracking-tight">Directorio Global de Usuarios</h1>
            <p class="text-muted">Administre todos los usuarios registrados en el ecosistema LogiSaaS.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-bottom p-4">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-black uppercase text-muted">Búsqueda</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i data-feather="search" style="width: 14px;"></i></span>
                        <input type="text" wire:model.live="search" class="form-control border-start-0 ps-0" placeholder="Nombre o email...">
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-black uppercase text-muted">Filtrar por Empresa</label>
                    <select wire:model.live="filter_tenant" class="form-select form-select-sm">
                        <option value="">Todos los Tenants</option>
                        @foreach($tenants as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-black uppercase text-muted">Filtrar por Rol</label>
                    <select wire:model.live="filter_role" class="form-select form-select-sm">
                        <option value="">Todos los Roles</option>
                        <option value="superadmin">Super Admin</option>
                        <option value="admin">Administrador</option>
                        <option value="operator">Operador</option>
                        <option value="customer">Cliente Final</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped my-0">
                <thead>
                    <tr>
                        <th class="ps-4 cursor-pointer" wire:click="sortBy('name')">Nombre</th>
                        <th>Empresa (Tenant)</th>
                        <th>Rol</th>
                        <th>Última Actividad</th>
                        <th class="pe-4 text-end">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr wire:key="user-{{ $u->id }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center fw-bold">
                                        {{ substr($u->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-black text-dark">{{ $u->name }}</div>
                                        <div class="small text-muted">{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($u->tenant)
                                    <span class="badge bg-primary-light text-primary fw-black uppercase">{{ $u->tenant->name }}</span>
                                @else
                                    <span class="badge bg-dark text-white fw-black uppercase">SISTEMA / GLOBAL</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border text-uppercase">{{ $u->role }}</span>
                            </td>
                            <td class="small fw-bold text-muted">
                                {{ $u->last_seen_at ? $u->last_seen_at->diffForHumans() : 'Nunca' }}
                            </td>
                            <td class="pe-4 text-end">
                                @if($u->email_verified_at)
                                    <span class="badge bg-success-light text-success xsmall font-black">VERIFICADO</span>
                                @else
                                    <span class="badge bg-warning-light text-warning xsmall font-black">PENDIENTE</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer border-top bg-light">
            {{ $users->links() }}
        </div>
    </div>
</div>
