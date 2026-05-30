<div>
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
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center bg-white border-bottom gap-3 py-3">
                    <div>
                        <h5 class="card-title mb-0 uppercase font-black small text-dark">
                            @if($filter === 'new')
                                <span class="text-success"><i data-feather="star" class="me-1" style="width:14px;"></i> Nuevos Registros</span>
                            @elseif($filter === 'unverified')
                                <span class="text-warning"><i data-feather="mail" class="me-1" style="width:14px;"></i> Pendientes de Validación</span>
                            @elseif($filter === 'inactive')
                                <span class="text-danger"><i data-feather="user-minus" class="me-1" style="width:14px;"></i> Sin Actividad</span>
                            @else
                                Gestión de Clientes
                            @endif
                        </h5>
                    </div>
                    <div class="d-flex gap-2 w-100 w-md-auto">
                        <button wire:click="openCreateModal" class="btn btn-primary shadow-sm fw-black rounded-pill px-3">
                            <i class="align-middle me-1" data-feather="plus-circle"></i> NUEVO CLIENTE
                        </button>
                        <div class="input-group input-group-sm flex-grow-1" style="min-width: 250px;">
                            <span class="input-group-text bg-light border-0 rounded-start-pill ps-3"><i data-feather="search" style="width:14px;"></i></span>
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-0 bg-light rounded-end-pill ps-0" placeholder="Buscar...">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="ps-4">Casillero</th>
                                <th>Información</th>
                                <th class="hidden md:table-cell">Contacto</th>
                                <th class="text-center">Saldo</th>
                                <th class="pe-4 text-end">Acciones</th>
                            </tr>
                        </thead>
                        @foreach($customers as $c)
                            <tbody x-data="{ expanded: false }" class="border-0" wire:key="customer-row-{{ $c->id }}">
                                <tr class="border-top">
                                    <td class="ps-4">
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge bg-primary text-white fw-black xsmall text-uppercase px-2 shadow-sm">{{ $c->box_number }}</span>
                                            @if($c->box_number_air)
                                                <span class="xsmall text-info font-bold uppercase" style="font-size: 0.55rem;"><i data-feather="send" style="width: 8px;"></i> {{ $c->box_number_air }}</span>
                                            @endif
                                            @if($c->box_number_maritime)
                                                <span class="xsmall text-warning font-bold uppercase" style="font-size: 0.55rem;"><i data-feather="anchor" style="width: 8px;"></i> {{ $c->box_number_maritime }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-black text-dark">{{ $c->user->name }}</div>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="text-muted" style="font-size: 0.65rem;">ID: {{ $c->identification_number ?? 'S/N' }}</div>
                                            @if($c->user->email_verified_at)
                                                <span class="text-success" title="Verificado"><i data-feather="check-circle" style="width: 10px;"></i></span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="hidden md:table-cell">
                                        <div class="small fw-bold text-muted">{{ $c->user->email }}</div>
                                        <div class="text-muted" style="font-size: 0.65rem;">{{ $c->phone ?? 'Sin teléfono' }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-black {{ $c->balance > 0 ? 'text-danger' : 'text-success' }}">
                                            ${{ number_format($c->balance, 2) }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group">
                                            <button @click="expanded = !expanded" class="btn btn-sm btn-light border shadow-none" title="Ver Detalles">
                                                <i x-show="!expanded" data-feather="eye" style="width:14px;"></i>
                                                <i x-show="expanded" data-feather="chevron-up" style="width:14px; display:none;"></i>
                                            </button>
                                            <button wire:click="openEditModal({{ $c->id }})" class="btn btn-sm btn-light border shadow-none" title="Editar">
                                                <i data-feather="edit-2" style="width:14px;"></i>
                                            </button>
                                            <button wire:click="openPasswordModal({{ $c->id }})" class="btn btn-sm btn-light border shadow-none text-danger" title="Seguridad">
                                                <i data-feather="key" style="width:14px;"></i>
                                            </button>
                                            <a href="{{ route('billing.statement', ['c' => $c->id]) }}" class="btn btn-sm btn-light border shadow-none text-info" title="Estado Cuenta">
                                                <i data-feather="bar-chart-2" style="width:14px;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr x-show="expanded" x-cloak class="bg-light bg-opacity-30 border-0">
                                    <td colspan="5" class="p-3 border-0">
                                        <div class="card border shadow-sm mb-0">
                                            <div class="card-body py-3">
                                                <div class="row text-start">
                                                    <div class="col-md-4">
                                                        <div class="text-uppercase xsmall font-black text-muted mb-2">Dirección</div>
                                                        <p class="small mb-0">{{ $c->address ?? 'No registrada.' }}</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="text-uppercase xsmall font-black text-muted mb-2">Cuenta</div>
                                                        <div class="small"><strong>Nivel:</strong>
                                                            @if($c->level)
                                                                <span class="badge" style="background-color: {{ $c->level->color }}">{{ $c->level->name }}</span>
                                                            @else
                                                                <span class="text-muted">Bronce (Auto)</span>
                                                            @endif
                                                        </div>
                                                        <div class="small mt-1"><strong>Puntos:</strong> {{ number_format($c->points) }} pts</div>
                                                    </div>
                                                    <div class="col-md-4 text-md-end d-flex flex-column justify-content-end">
                                                        <button wire:click="deleteCustomer({{ $c->id }})" wire:confirm="¿Borrar cliente?" class="btn btn-xs btn-outline-danger align-self-md-end">ELIMINAR PERFIL</button>
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
                <div class="card-footer bg-white border-top">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modals Section -->
    <div class="modal fade" id="customerModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                <div class="modal-header bg-primary text-white p-4">
                    <h5 class="modal-title uppercase font-black tracking-widest text-white">
                        <i class="align-middle me-2" data-feather="{{ $is_editing ? 'edit' : 'user-plus' }}"></i>
                        {{ $is_editing ? 'Editar Cliente' : 'Nuevo Cliente' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form wire:submit.prevent="saveCustomer">
                    <div class="modal-body p-4 p-md-5">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label xsmall font-black text-uppercase text-muted">Nombre Completo</label>
                                <input type="text" wire:model="name" class="form-control fw-bold border-2">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label xsmall font-black text-uppercase text-muted">Email</label>
                                <input type="email" wire:model="email" class="form-control border-2">
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label xsmall font-black text-uppercase text-muted">Identificación</label>
                                <input type="text" wire:model="identification_number" class="form-control border-2">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label xsmall font-black text-uppercase text-muted">Teléfono</label>
                                <input type="text" wire:model="phone" class="form-control border-2">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label xsmall font-black text-uppercase text-muted">Casillero Físico</label>
                                <select wire:model="locker_id" class="form-select border-2">
                                    <option value="">Ninguno</option>
                                    @foreach($availableLockers as $locker)
                                        <option value="{{ $locker->id }}">{{ $locker->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label xsmall font-black text-uppercase text-muted">Nivel</label>
                                <select wire:model="loyalty_level_id" class="form-select border-2">
                                    <option value="">Auto (Puntos)</option>
                                    @foreach($loyaltyLevels as $level)
                                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label xsmall font-black text-uppercase text-muted">ID Master</label>
                                <input type="text" wire:model="box_number" class="form-control border-2 fw-black text-primary">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label xsmall font-black text-uppercase text-muted">ID Aéreo</label>
                                <input type="text" wire:model="box_number_air" class="form-control border-2">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label xsmall font-black text-uppercase text-muted">ID Marítimo</label>
                                <input type="text" wire:model="box_number_maritime" class="form-control border-2">
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label xsmall font-black text-uppercase text-muted">Dirección Local</label>
                            <textarea wire:model="address" rows="2" class="form-control border-2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-4">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary px-4 fw-black">GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="passwordResetModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title uppercase font-black xsmall text-white">Nueva Contraseña</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form wire:submit.prevent="resetPassword">
                    <div class="modal-body p-4 text-center">
                        <input type="text" wire:model="new_password" class="form-control border-2 text-center fw-bold" placeholder="Contraseña...">
                        @error('new_password') <div class="text-danger xsmall mt-2">{{ $message }}</div> @enderror
                    </div>
                    <div class="modal-footer bg-light p-2">
                        <button type="submit" class="btn btn-danger w-100 fw-black">ACTUALIZAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('open-customer-modal', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('customerModal')).show();
        });
        window.addEventListener('customer-saved', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('customerModal')).hide();
        });
        window.addEventListener('open-password-modal', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('passwordResetModal')).show();
        });
        window.addEventListener('close-password-modal', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('passwordResetModal')).hide();
        });
    </script>
</div>
