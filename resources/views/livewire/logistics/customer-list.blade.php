<div>
    <!-- Customers Dashboard -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter', '')" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-primary text-white">
                <div class="card-body py-4 text-center">
                    <h3 class="mb-2 fw-black text-white">{{ number_format($stats['total_customers']) }}</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Total Clientes</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter', 'unverified')" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-warning text-dark">
                <div class="card-body py-4 text-center">
                    <h3 class="mb-2 fw-black text-dark">{{ number_format($stats['unverified_emails']) }}</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Emails sin Validar</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter', 'inactive')" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-secondary text-white">
                <div class="card-body py-4 text-center">
                    <h3 class="mb-2 fw-black text-white">{{ number_format($stats['inactive_users']) }}</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Sin Compras (7+ días)</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <a href="{{ route('logistics.lockers') }}" class="card flex-fill border-0 shadow-sm transform transition hover:scale-102 text-decoration-none bg-info text-white">
                <div class="card-body py-4 text-center">
                    <h3 class="mb-2 fw-black text-white">{{ $stats['active_lockers'] }}</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Casilleros Físicos</p>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center bg-light border-bottom gap-3">
                    <div>
                        <h5 class="card-title mb-0 uppercase font-black small">
                            @if($filter === 'new')
                                <span class="text-success"><i data-feather="star" class="me-1"></i> Nuevos Registros (Últimas 48h)</span>
                            @elseif($filter === 'unverified')
                                <span class="text-warning"><i data-feather="mail" class="me-1"></i> Emails Pendientes de Validación</span>
                            @elseif($filter === 'inactive')
                                <span class="text-danger"><i data-feather="user-minus" class="me-1"></i> Clientes sin Actividad (7+ días)</span>
                            @else
                                Gestión de Clientes
                            @endif
                        </h5>
                        <div class="text-muted small mt-1">{{ $customers->total() }} clientes encontrados</div>
                    </div>
                    <div class="d-flex gap-2 w-100 w-md-auto">
                        @if($filter === 'new')
                            <button wire:click="$set('filter', '')" class="btn btn-sm btn-outline-dark fw-bold">VER TODOS</button>
                        @endif
                        <button wire:click="openCreateModal" class="btn btn-primary shadow-lg transform transition hover:scale-105 fw-black">
                            <i class="align-middle me-1" data-feather="plus-circle"></i> NUEVO CLIENTE
                        </button>
                        <div class="input-group input-group-sm flex-grow-1" style="min-width: 300px;">
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Nombre, ID, Casillero o Email...">
                            <span class="input-group-text bg-white">
                                <i class="align-middle text-muted" data-feather="search" style="width: 14px;"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped my-0">
                        <thead>
                            <tr>
                                <th class="ps-4 cursor-pointer" wire:click="sortBy('box_number')">
                                    Casillero
                                    @if($sortField === 'box_number')
                                        <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                                    @endif
                                </th>
                                <th class="cursor-pointer" wire:click="sortBy('identification_number')">
                                    Nombre / ID
                                    @if($sortField === 'identification_number')
                                        <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                                    @endif
                                </th>
                                <th class="hidden md:table-cell">Contacto</th>
                                <th class="text-center cursor-pointer" wire:click="sortBy('balance')">
                                    Saldo
                                    @if($sortField === 'balance')
                                        <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                                    @endif
                                </th>
                                <th class="pe-4 text-end">Acciones</th>
                            </tr>
                        </thead>
                        @foreach($customers as $c)
                            <tbody x-data="{ expanded: false }" class="border-0" wire:key="customer-body-{{ $c->id }}">
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge bg-primary-light text-primary fw-bold text-uppercase" title="ID Principal">{{ $c->box_number }}</span>
                                            @if($c->box_number_air)
                                                <span class="xsmall font-black text-info uppercase" style="font-size: 0.55rem;"><i data-feather="send" style="width: 8px; height: 8px;"></i> {{ $c->box_number_air }}</span>
                                            @endif
                                            @if($c->box_number_maritime)
                                                <span class="xsmall font-black text-warning uppercase" style="font-size: 0.55rem;"><i data-feather="anchor" style="width: 8px; height: 8px;"></i> {{ $c->box_number_maritime }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-black text-dark">{{ $c->user->name }}</div>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="text-muted small" style="font-size: 0.65rem;">ID: {{ $c->identification_number ?? 'S/N' }} | Registrado: {{ $c->created_at->format('d/m/Y') }}</div>
                                            @if($c->user->email_verified_at)
                                                <span class="badge bg-success-light text-success font-black uppercase" style="font-size: 0.5rem;"><i data-feather="check-circle" style="width: 8px; height: 8px;"></i> VALIDADO</span>
                                            @else
                                                <span class="badge bg-warning-light text-warning font-black uppercase" style="font-size: 0.5rem;"><i data-feather="alert-triangle" style="width: 8px; height: 8px;"></i> PENDIENTE</span>
                                            @endif
                                        </div>
                                        @if($c->address)
                                            <div class="text-primary xsmall fw-bold mt-1 uppercase" style="font-size: 0.6rem;"><i data-feather="map-pin" class="me-1" style="width: 10px;"></i> {{ Str::limit($c->address, 40) }}</div>
                                        @endif
                                    </td>
                                    <td class="hidden md:table-cell">
                                        <div class="small fw-bold text-muted">{{ $c->user->email }}</div>
                                        <div class="text-muted small" style="font-size: 0.65rem;">{{ $c->phone ?? 'Sin teléfono' }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-black {{ $c->balance > 0 ? 'text-danger' : 'text-success' }}">
                                            ${{ number_format($c->balance, 2) }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group">
                                            <button @click="expanded = !expanded" class="btn btn-sm btn-light border shadow-sm" title="Ver Detalles">
                                                <i x-show="!expanded" class="align-middle text-dark" data-feather="eye"></i>
                                                <i x-show="expanded" class="align-middle text-dark" data-feather="chevron-up" style="display:none;"></i>
                                            </button>
                                            <button wire:click="openEditModal({{ $c->id }})" class="btn btn-sm btn-light border shadow-sm" title="Editar Cliente">
                                                <i class="align-middle text-dark" data-feather="edit-2"></i>
                                            </button>
                                            <button wire:click="openPasswordModal({{ $c->id }})" class="btn btn-sm btn-light border shadow-sm" title="Cambiar Contraseña">
                                                <i class="align-middle text-danger" data-feather="key"></i>
                                            </button>
                                            <button wire:click="deleteCustomer({{ $c->id }})" wire:confirm="¿Estás seguro de eliminar este cliente? Se borrará también su cuenta de acceso." class="btn btn-sm btn-light border shadow-sm text-danger" title="Eliminar Cliente">
                                                <i class="align-middle" data-feather="trash-2"></i>
                                            </button>
                                            <a href="{{ route('billing.index', ['search' => $c->box_number]) }}" class="btn btn-sm btn-light border shadow-sm" title="Ver Facturas">
                                                <i class="align-middle text-primary" data-feather="file-text"></i>
                                            </a>
                                            <a href="{{ route('billing.statement', ['c' => $c->id]) }}" class="btn btn-sm btn-light border shadow-sm" title="Ver Estado de Cuenta">
                                                <i class="align-middle text-info" data-feather="bar-chart-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Expanded Info -->
                                <tr x-show="expanded" x-cloak class="bg-light bg-opacity-50">
                                    <td colspan="5" class="p-4">
                                        <div class="card border-0 shadow-sm mb-0">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <h6 class="fw-black text-uppercase small text-muted mb-3">Dirección de Entrega Local</h6>
                                                        <p class="small mb-0">{{ $c->address ?? 'No proporcionada por el cliente.' }}</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="fw-black text-uppercase small text-muted mb-3">Identificadores de Casillero</h6>
                                                        <div class="small mb-1"><strong>Aéreo:</strong> {{ $c->box_number_air ?: 'N/A' }}</div>
                                                        <div class="small"><strong>Marítimo:</strong> {{ $c->box_number_maritime ?: 'N/A' }}</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="fw-black text-uppercase small text-muted mb-3">Información de Cuenta</h6>
                                                        <div class="small mb-1"><strong>Nivel Actual:</strong>
                                                            @if($c->level)
                                                                <span class="badge" style="background-color: {{ $c->level->color }}">{{ $c->level->name }}</span>
                                                            @else
                                                                <span class="text-muted">Estándar</span>
                                                            @endif
                                                        </div>
                                                        <div class="small mb-1"><strong>Puntos acumulados:</strong> {{ number_format($c->points) }} pts</div>
                                                        <div class="small"><strong>Locker asignado:</strong> {{ $c->locker ? $c->locker->code : 'Ninguno' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>
                </div>
                <div class="card-footer border-top bg-light">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para nuevo/editar cliente -->
    <div class="modal fade" id="customerModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                <div class="modal-header bg-primary text-white p-4">
                    <h5 class="modal-title uppercase font-black tracking-widest text-white">
                        <i class="align-middle me-2" data-feather="{{ $is_editing ? 'edit' : 'user-plus' }}"></i>
                        {{ $is_editing ? 'Editar Información del Cliente' : 'Nuevo Registro de Cliente' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="saveCustomer">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small font-black text-uppercase text-muted">Nombre Completo</label>
                                <input type="text" wire:model="name" class="form-control form-control-lg fw-bold border-2" placeholder="Juan Pérez">
                                @error('name') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small font-black text-uppercase text-muted">Correo Electrónico</label>
                                <input type="email" wire:model="email" class="form-control form-control-lg border-2" placeholder="juan@ejemplo.com">
                                @error('email') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-3">
                                <label class="form-label small font-black text-uppercase text-muted">Identificación</label>
                                <input type="text" wire:model="identification_number" class="form-control border-2 fw-bold" placeholder="8-000-000">
                                @error('identification_number') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small font-black text-uppercase text-muted">Teléfono</label>
                                <input type="text" wire:model="phone" class="form-control border-2 fw-bold" placeholder="+507 ...">
                                @error('phone') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small font-black text-uppercase text-muted">Casillero Físico</label>
                                <select wire:model="locker_id" class="form-select border-2">
                                    <option value="">Ninguno</option>
                                    @foreach($availableLockers as $locker)
                                        <option value="{{ $locker->id }}">{{ $locker->code }} ({{ $locker->max_weight }} lbs)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small font-black text-uppercase text-muted">Nivel Lealtad</label>
                                <select wire:model="loyalty_level_id" class="form-select border-2 fw-bold">
                                    <option value="">Auto (Puntos)</option>
                                    @foreach($loyaltyLevels as $level)
                                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label class="form-label small font-black text-uppercase text-muted">ID Principal (Master)</label>
                                <input type="text" wire:model="box_number" class="form-control border-2 fw-bold text-primary">
                                @error('box_number') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small font-black text-uppercase text-muted text-info"><i data-feather="send" class="me-1" style="width:12px;"></i> ID Aéreo</label>
                                <input type="text" wire:model="box_number_air" class="form-control border-2 fw-bold text-info">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small font-black text-uppercase text-muted text-warning"><i data-feather="anchor" class="me-1" style="width:12px;"></i> ID Marítimo</label>
                                <input type="text" wire:model="box_number_maritime" class="form-control border-2 fw-bold text-warning">
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label small font-black text-uppercase text-muted">Dirección de Entrega Local</label>
                            <textarea wire:model="address" rows="2" class="form-control border-2" placeholder="Dirección completa para entregas a domicilio..."></textarea>
                            @error('address') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                        </div>

                        @if(!$is_editing)
                            <div class="alert alert-info mt-4 border-0 shadow-none xsmall py-2">
                                <i data-feather="info" class="me-2" style="width:12px;"></i> La contraseña inicial será <strong>password123</strong>. El cliente debe cambiarla al entrar.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer bg-light p-4">
                        <button type="button" class="btn btn-light border fw-bold text-uppercase" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-lg fw-black text-uppercase">
                            {{ $is_editing ? 'GUARDAR CAMBIOS' : 'CREAR CLIENTE' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para resetear contraseña -->
    <div class="modal fade" id="passwordResetModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title uppercase font-black small tracking-widest text-white">
                        <i class="align-middle me-2" data-feather="key"></i> Nueva Contraseña
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="resetPassword">
                    <div class="modal-body p-4 text-center">
                        <p class="text-muted small mb-4">Ingresa la nueva contraseña para el cliente. Se recomienda una combinación fuerte.</p>
                        <input type="text" wire:model="new_password" class="form-control form-control-lg border-2 text-center fw-bold" placeholder="Escribe aquí...">
                        @error('new_password') <div class="text-danger small mt-2 fw-bold">{{ $message }}</div> @enderror
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-light fw-bold text-uppercase" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger px-4 fw-black text-uppercase">ACTUALIZAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('open-customer-modal', event => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('customerModal')).show();
        });

        window.addEventListener('customer-saved', event => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('customerModal')).hide();
        });

        window.addEventListener('open-password-modal', event => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('passwordResetModal')).show();
        });

        window.addEventListener('close-password-modal', event => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('passwordResetModal')).hide();
        });
    </script>
</div>
