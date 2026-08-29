<div>
    <x-app.flash />

    {{-- Header de identidad --}}
    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <a href="{{ route('logistics.customers') }}" class="btn btn-sm btn-light border shadow-none" title="Volver a la Base de Clientes" aria-label="Volver a la Base de Clientes">
                    <i data-feather="arrow-left" style="width: 14px; height: 14px;"></i>
                </a>
                <div class="avatar avatar-lg bg-primary text-white d-flex align-items-center justify-content-center rounded-circle font-black" style="width: 56px; height: 56px; font-size: 1.4rem;">
                    {{ strtoupper(substr($customer->user->name, 0, 1)) }}
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <h1 class="h4 mb-0 fw-black text-dark">{{ $customer->user->name }}</h1>
                        @if($customer->user->email_verified_at)
                            <span class="text-success" title="Email verificado"><i data-feather="check-circle" style="width: 16px;"></i></span>
                        @endif
                        @if($customer->level)
                            <x-app.status-badge :label="$customer->level->name" :color="$customer->level->color" :uppercase="false" />
                        @endif
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                        <span class="badge bg-primary text-white font-black px-2 py-1 shadow-sm" style="font-size: 0.9rem; border-radius: 4px;">{{ $customer->box_number }}</span>
                        <span class="text-muted small"><i data-feather="hash" style="width: 12px; height: 12px;"></i> {{ $customer->identification_number ?? 'S/N' }}</span>
                        <span class="text-muted small"><i data-feather="mail" style="width: 12px; height: 12px;"></i> {{ $customer->user->email }}</span>
                        @if($customer->phone)
                            <span class="text-muted small"><i data-feather="phone" style="width: 12px; height: 12px;"></i> {{ $customer->phone }}</span>
                        @endif
                        <span class="text-muted small"><i data-feather="clock" style="width: 12px; height: 12px;"></i> Última actividad: {{ $customer->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('billing.create', ['customer' => $customer->id]) }}" class="btn btn-success btn-sm fw-black text-uppercase shadow-sm">
                        <i class="align-middle me-1" data-feather="file-text" style="width: 14px;"></i> Nueva Factura
                    </a>
                    <a href="{{ route('billing.statement', ['c' => $customer->id]) }}" class="btn btn-outline-primary btn-sm fw-black text-uppercase shadow-sm">
                        <i class="align-middle me-1" data-feather="bar-chart-2" style="width: 14px;"></i> Estado de Cuenta
                    </a>
                    <button wire:click="openEditModal" class="btn btn-primary btn-sm fw-black text-uppercase shadow-sm">
                        <i class="align-middle me-1" data-feather="edit-2" style="width: 14px;"></i> Editar
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-light border btn-sm fw-black text-uppercase shadow-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="align-middle me-1" data-feather="key" style="width: 14px;"></i> Credenciales
                        </button>
                        <div class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                            <button wire:click="openPasswordModal" class="dropdown-item py-2"><i data-feather="refresh-cw" class="me-2 text-primary" style="width: 14px;"></i> Nueva contraseña</button>
                            <button wire:click="confirmSendPassword" class="dropdown-item py-2"><i data-feather="mail" class="me-2 text-warning" style="width: 14px;"></i> Enviar credenciales</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Barra de saldo --}}
            <div class="mt-4 pt-3 border-top d-flex flex-wrap align-items-center gap-4">
                <div>
                    <div class="xsmall text-muted text-uppercase font-black">Saldo Pendiente</div>
                    <div class="h4 mb-0 fw-black" style="color: {{ $stats['balance'] > 0 ? '#dc3545' : '#198754' }};">
                        {{ $currency }} {{ number_format($stats['balance'], 2) }}
                    </div>
                </div>
                <div>
                    <div class="xsmall text-muted text-uppercase font-black">Total Facturado</div>
                    <div class="h5 mb-0 fw-bold text-dark">{{ $currency }} {{ number_format($stats['total_billed'], 2) }}</div>
                </div>
                <div>
                    <div class="xsmall text-muted text-uppercase font-black">Puntos</div>
                    <div class="h5 mb-0 fw-bold text-dark">{{ number_format($stats['points']) }} pts</div>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('logistics.smart-reception') }}" class="btn btn-sm btn-light border fw-black text-uppercase">
                        <i class="align-middle me-1" data-feather="zap" style="width: 14px;"></i> Nueva Recepción
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- KPI strip --}}
    <div class="row mb-4">
        <div class="col-6 col-md-4 col-xl-2 d-flex mb-3">
            <div class="card flex-fill border-0 shadow-sm bg-primary text-white">
                <div class="card-body py-3 text-center">
                    <h4 class="mb-1 fw-black text-white">{{ number_format($stats['total_packages']) }}</h4>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Paquetes</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2 d-flex mb-3">
            <div class="card flex-fill border-0 shadow-sm bg-info text-white">
                <div class="card-body py-3 text-center">
                    <h4 class="mb-1 fw-black text-white">{{ number_format($stats['active_packages']) }}</h4>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">En Proceso</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2 d-flex mb-3">
            <div class="card flex-fill border-0 shadow-sm bg-success text-white">
                <div class="card-body py-3 text-center">
                    <h4 class="mb-1 fw-black text-white">{{ number_format($stats['invoices_count']) }}</h4>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Facturas</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2 d-flex mb-3">
            <div class="card flex-fill border-0 shadow-sm bg-danger text-white">
                <div class="card-body py-3 text-center">
                    <h4 class="mb-1 fw-black text-white">{{ $currency }} {{ number_format($stats['balance'], 2) }}</h4>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Deuda</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2 d-flex mb-3">
            <div class="card flex-fill border-0 shadow-sm bg-warning text-dark">
                <div class="card-body py-3 text-center">
                    <h4 class="mb-1 fw-black text-dark">{{ number_format($stats['points']) }}</h4>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Puntos</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2 d-flex mb-3">
            <div class="card flex-fill border-0 shadow-sm bg-dark text-white">
                <div class="card-body py-3 text-center">
                    <h4 class="mb-1 fw-black text-white">{{ number_format($stats['open_tickets']) }}</h4>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Tickets</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
            <button class="nav-link {{ $tab === 'packages' ? 'active fw-black' : '' }}" wire:click="setTab('packages')" type="button">
                <i data-feather="package" class="me-1" style="width: 14px;"></i> Paquetes
                <span class="badge bg-primary ms-1">{{ $stats['total_packages'] }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $tab === 'invoices' ? 'active fw-black' : '' }}" wire:click="setTab('invoices')" type="button">
                <i data-feather="file-text" class="me-1" style="width: 14px;"></i> Facturas
                <span class="badge bg-primary ms-1">{{ $stats['invoices_count'] }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $tab === 'tickets' ? 'active fw-black' : '' }}" wire:click="setTab('tickets')" type="button">
                <i data-feather="message-square" class="me-1" style="width: 14px;"></i> Tickets
                <span class="badge bg-primary ms-1">{{ $tickets->count() }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $tab === 'activity' ? 'active fw-black' : '' }}" wire:click="setTab('activity')" type="button">
                <i data-feather="activity" class="me-1" style="width: 14px;"></i> Actividad
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $tab === 'details' ? 'active fw-black' : '' }}" wire:click="setTab('details')" type="button">
                <i data-feather="user" class="me-1" style="width: 14px;"></i> Notas y Direcciones
            </button>
        </li>
    </ul>

    {{-- Tab: Paquetes --}}
    @if($tab === 'packages')
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light bg-opacity-50">
                        <tr>
                            <th class="ps-4">Tracking</th>
                            <th>Bodega</th>
                            <th class="text-center">Peso</th>
                            <th>Estado</th>
                            <th class="pe-4 text-end">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-black text-dark">{{ $package->tracking_number }}</div>
                                    <div class="text-muted xsmall">{{ Str::limit($package->description ?? 'Sin descripción', 40) }}</div>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $package->warehouse->code ?? 'N/A' }}</span></td>
                                <td class="text-center fw-bold">{{ $package->weight }} lbs</td>
                                <td>
                                    <x-app.status-badge :label="$package->getStatusLabel()" :color="$package->getStatusColor()" />
                                </td>
                                <td class="pe-4 text-end small text-muted">{{ $package->created_at->format('d M, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i data-feather="package" class="d-block mx-auto mb-2 opacity-25" style="width: 48px; height: 48px;"></i>
                                    Este cliente aún no tiene paquetes registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Tab: Facturas --}}
    @if($tab === 'invoices')
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light bg-opacity-50">
                        <tr>
                            <th class="ps-4">Nº Factura</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th class="pe-4 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-black text-dark">{{ $invoice->number }}</div>
                                    <div class="text-muted xsmall">{{ $invoice->created_at->format('d M, Y') }}</div>
                                </td>
                                <td class="fw-bold">{{ $currency }} {{ number_format($invoice->total, 2) }}</td>
                                <td>
                                    <span class="badge text-uppercase" style="font-size: 0.65rem; background-color: {{ $invoice->getStatusColor() }}">{{ $invoice->getStatusLabel() }}</span>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('billing.download', $invoice) }}" target="_blank" class="btn btn-sm btn-light border shadow-none" title="Descargar PDF" aria-label="Descargar PDF de {{ $invoice->number }}">
                                        <i data-feather="printer" style="width: 14px;"></i>
                                    </a>
                                    <button wire:click="sendWhatsApp({{ $invoice->id }})" class="btn btn-sm btn-light border shadow-none" title="Enviar por WhatsApp" aria-label="Enviar factura {{ $invoice->number }} por WhatsApp">
                                        <i class="text-success" data-feather="message-circle" style="width: 14px;"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i data-feather="file-text" class="d-block mx-auto mb-2 opacity-25" style="width: 48px; height: 48px;"></i>
                                    Este cliente aún no tiene facturas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Tab: Tickets --}}
    @if($tab === 'tickets')
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light bg-opacity-50">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Asunto</th>
                            <th class="text-center">Prioridad</th>
                            <th class="text-center">Estado</th>
                            <th class="pe-4 text-end">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            @php
                                $prioClass = [
                                    'high' => 'bg-danger',
                                    'medium' => 'bg-warning text-dark',
                                    'low' => 'bg-info',
                                ][$ticket->priority] ?? 'bg-secondary';
                                $statusClass = [
                                    'open' => 'bg-success',
                                    'pending' => 'bg-warning text-dark',
                                    'closed' => 'bg-secondary',
                                ][$ticket->status] ?? 'bg-dark';
                            @endphp
                            <tr>
                                <td class="ps-4 fw-bold">#{{ $ticket->id }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $ticket->subject }}</div>
                                    <div class="text-muted xsmall">{{ $ticket->messages->count() }} mensajes</div>
                                </td>
                                <td class="text-center"><span class="badge {{ $prioClass }} text-uppercase" style="font-size: 0.6rem;">{{ $ticket->priority }}</span></td>
                                <td class="text-center"><span class="badge {{ $statusClass }} text-uppercase" style="font-size: 0.6rem;">{{ $ticket->status }}</span></td>
                                <td class="pe-4 text-end small text-muted">{{ $ticket->created_at->format('d M, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i data-feather="message-square" class="d-block mx-auto mb-2 opacity-25" style="width: 48px; height: 48px;"></i>
                                    Este cliente no tiene tickets de soporte.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Tab: Actividad --}}
    @if($tab === 'activity')
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @forelse($activity as $event)
                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                        <div class="bg-{{ $event['color'] }} bg-opacity-10 text-{{ $event['color'] }} rounded-3 p-2 me-3 flex-shrink-0">
                            <i data-feather="{{ $event['icon'] }}" style="width: 16px; height: 16px;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-dark small">{{ $event['text'] }}</div>
                            <div class="text-muted xsmall">{{ $event['at'] ? $event['at']->format('d M, Y H:i') : '—' }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i data-feather="activity" class="d-block mx-auto mb-2 opacity-25" style="width: 48px; height: 48px;"></i>
                        Sin actividad registrada para este cliente.
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    {{-- Tab: Notas y Direcciones --}}
    @if($tab === 'details')
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 uppercase font-black small">Dirección Local</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-muted">{{ $customer->address ?: 'No registrada.' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 uppercase font-black small">Identificadores</h5>
                    </div>
                    <div class="card-body">
                        <div class="small mb-2"><strong>ID Master:</strong> {{ $customer->box_number }}</div>
                        @if($airEnabled)
                            <div class="small mb-2"><strong>ID Aéreo:</strong> {{ $customer->box_number_air ?: 'N/A' }}</div>
                        @endif
                        @if($maritimeEnabled)
                            <div class="small mb-2"><strong>ID Marítimo:</strong> {{ $customer->box_number_maritime ?: 'N/A' }}</div>
                        @endif
                        <div class="small mb-2"><strong>Casillero Físico:</strong> {{ $customer->locker->code ?? 'Sin asignar' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 uppercase font-black small text-warning">Notas Administrativas (Privado)</h5>
                    </div>
                    <div class="card-body">
                        @if($customer->admin_notes)
                            <p class="mb-0">{{ $customer->admin_notes }}</p>
                        @else
                            <p class="mb-0 text-muted">Sin notas registradas.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Editar (reutiliza los mismos campos que la Base de Clientes) --}}
    <div class="modal fade" id="customerModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                <div class="modal-header bg-primary text-white p-4">
                    <h5 class="modal-title uppercase font-black tracking-widest text-white">
                        <i class="align-middle me-2" data-feather="edit"></i> Editar Cliente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form wire:submit.prevent="saveCustomer">
                    <div class="modal-body p-4 p-md-5">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label xsmall font-black text-uppercase text-muted">Nombre Completo <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name" class="form-control fw-bold border-2">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label xsmall font-black text-uppercase text-muted">Email <span class="text-danger">*</span></label>
                                <input type="email" wire:model="email" class="form-control border-2">
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label xsmall font-black text-uppercase text-muted">Identificación <span class="text-danger">*</span></label>
                                <input type="text" wire:model="identification_number" class="form-control border-2">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label xsmall font-black text-uppercase text-muted">Teléfono <span class="text-danger">*</span></label>
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
                            <div class="col-md-6">
                                <label class="form-label xsmall font-black text-uppercase text-muted">Tarifa</label>
                                <select wire:model="rate_type" class="form-select border-2">
                                    <option value="regular">Regular ($2.50/lb)</option>
                                    <option value="reseller">Revendedor</option>
                                    <option value="special">Tarifa Especial</option>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end pb-1">
                                <div class="form-check form-switch d-flex align-items-center gap-2">
                                    <input class="form-check-input mt-0" type="checkbox" id="loyaltyEligibleSwitch" wire:model="is_loyalty_eligible" style="width: 40px; height: 20px;">
                                    <label class="form-check-label small fw-bold mb-0" for="loyaltyEligibleSwitch">
                                        Participa en LOGYPUNTOS
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label xsmall font-black text-uppercase text-muted">ID Master <span class="text-danger">*</span></label>
                                <input type="text" wire:model="box_number" class="form-control border-2 fw-black text-primary">
                            </div>
                            @if($airEnabled)
                                <div class="col-md-4">
                                    <label class="form-label xsmall font-black text-uppercase text-muted">ID Aéreo</label>
                                    <input type="text" wire:model="box_number_air" class="form-control border-2">
                                </div>
                            @endif
                            @if($maritimeEnabled)
                                <div class="col-md-4">
                                    <label class="form-label xsmall font-black text-uppercase text-muted">ID Marítimo</label>
                                    <input type="text" wire:model="box_number_maritime" class="form-control border-2">
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label xsmall font-black text-uppercase text-muted">Dirección Local</label>
                            <textarea wire:model="address" rows="2" class="form-control border-2"></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label xsmall font-black text-uppercase text-muted text-warning"><i data-feather="edit-3" style="width: 12px;"></i> Notas Administrativas (Privado)</label>
                            <textarea wire:model="admin_notes" rows="2" class="form-control border-2 bg-light bg-opacity-50" placeholder="Escribe aquí recordatorios o detalles sobre este cliente..."></textarea>
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

    @include('livewire.logistics.partials.customer-credential-modals')

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
        window.addEventListener('open-confirm-password-modal', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmPasswordModal')).show();
        });
        window.addEventListener('close-confirm-password-modal', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmPasswordModal')).hide();
        });
    </script>
</div>
