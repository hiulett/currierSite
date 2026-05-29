<div>
    @if(!$selected_customer_id)
        <!-- Main Dashboard View -->
        <div class="row mb-3">
            <div class="col-12">
                <h1 class="h3 mb-2 uppercase font-black tracking-tight">Estado de Cuentas Global</h1>
                <p class="text-muted">Gestión financiera centralizada de todos los clientes.</p>
            </div>
        </div>

        <!-- Dashboard Stats -->
        <div class="row mb-4">
            <div class="col-12 col-sm-6 col-xxl-3 d-flex">
                <div wire:click="setFilter('debt')" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-danger text-white {{ $filter_status === 'debt' ? 'ring-active' : '' }}">
                    <div class="card-body py-4">
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <h3 class="mb-2 text-white fw-black">{{ $currency }} {{ number_format($stats['total_debt'], 2) }}</h3>
                                <p class="mb-0 text-uppercase font-bold small opacity-75">Cartera por Cobrar</p>
                            </div>
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xxl-3 d-flex">
                <div wire:click="setFilter('')" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-primary text-white">
                    <div class="card-body py-4">
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <h3 class="mb-2 text-white fw-black">{{ number_format($stats['total_customers']) }}</h3>
                                <p class="mb-0 text-uppercase font-bold small opacity-75">Clientes Activos</p>
                            </div>
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xxl-3 d-flex">
                <div class="card flex-fill border-0 shadow-sm bg-warning text-white">
                    <div class="card-body py-4">
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <h3 class="mb-2 text-white fw-black">{{ number_format($stats['total_points']) }}</h3>
                                <p class="mb-0 text-uppercase font-bold small opacity-75">Puntos de Fidelidad</p>
                            </div>
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xxl-3 d-flex">
                <a href="{{ route('logistics.inventory') }}" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-info text-white text-decoration-none">
                    <div class="card-body py-4">
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <h3 class="mb-2 text-white fw-black">{{ number_format($stats['active_packages']) }}</h3>
                                <p class="mb-0 text-uppercase font-bold small opacity-75">Paquetes en Almacén</p>
                            </div>
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="package"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Customer Grid -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 py-3">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0 uppercase font-black small me-3 text-dark">Estados de Cuenta</h5>

                    <!-- Grouping Control (Dropdown Style) -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light border dropdown-toggle fw-black xsmall uppercase px-3 shadow-none rounded-pill" type="button" data-bs-toggle="dropdown">
                            <i class="align-middle me-1 text-primary" data-feather="layers" style="width: 12px;"></i>
                            @if($group_by === 'customer') Agrupado: Nombre
                            @elseif($group_by === 'locker') Agrupado: Locker
                            @elseif($group_by === 'debt') Agrupado: Saldo
                            @else Sin Agrupar @endif
                        </button>
                        <ul class="dropdown-menu shadow-lg border-0 rounded-3 xsmall fw-bold uppercase">
                            <li><a class="dropdown-item py-2 {{ $group_by === 'customer' ? 'active' : '' }}" href="#" wire:click.prevent="setGroupBy('customer')">Agrupar por Nombre</a></li>
                            <li><a class="dropdown-item py-2 {{ $group_by === 'locker' ? 'active' : '' }}" href="#" wire:click.prevent="setGroupBy('locker')">Agrupar por Locker</a></li>
                            <li><a class="dropdown-item py-2 {{ $group_by === 'debt' ? 'active' : '' }}" href="#" wire:click.prevent="setGroupBy('debt')">Agrupar por Saldo</a></li>
                        </ul>
                    </div>
                </div>

                <div class="input-group input-group-sm w-100 w-md-auto" style="min-width: 320px;">
                    <span class="input-group-text bg-light border-0 ps-3 rounded-start-pill"><i class="align-middle text-muted" data-feather="search" style="width: 14px;"></i></span>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-0 bg-light rounded-end-pill ps-0" placeholder="Buscar cliente o casillero...">
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="ps-4 py-3 xsmall font-black uppercase text-muted tracking-widest" style="width: 120px;">ID / Casillero</th>
                                <th class="xsmall font-black uppercase text-muted tracking-widest">Información del Cliente</th>
                                <th class="xsmall font-black uppercase text-muted tracking-widest">Saldo Pendiente</th>
                                <th class="text-center xsmall font-black uppercase text-muted tracking-widest">Puntos</th>
                                <th class="pe-4 text-end xsmall font-black uppercase text-muted tracking-widest" style="width: 150px;">Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $currentGroup = null; @endphp
                            @foreach($customers as $c)
                                @php
                                    $groupLabel = null;
                                    if($group_by === 'locker') $groupLabel = $c->locker_code_joined ?? 'Sin Locker Asignado';
                                    elseif($group_by === 'customer') $groupLabel = strtoupper(substr($c->user->name, 0, 1));
                                    elseif($group_by === 'debt') $groupLabel = $c->balance > 0 ? 'Cuentas Pendientes' : 'Cuentas al Día';
                                @endphp

                                @if($groupLabel !== $currentGroup)
                                    <tr class="bg-light bg-opacity-30 border-top">
                                        <td colspan="5" class="ps-4 py-2">
                                            <span class="fw-black text-primary text-uppercase xsmall tracking-widest">{{ $groupLabel }}</span>
                                        </td>
                                    </tr>
                                    @php $currentGroup = $groupLabel; @endphp
                                @endif

                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex flex-column align-items-start">
                                            <span class="badge bg-primary text-white px-2 py-1 xsmall fw-black text-uppercase shadow-sm" style="font-size: 0.6rem; letter-spacing: 0.02rem;">{{ $c->box_number }}</span>
                                            @if($c->locker)
                                                <div class="text-muted xsmall font-bold mt-1" style="font-size: 0.6rem;"><i data-feather="grid" class="me-1" style="width: 8px;"></i>{{ $c->locker->code }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $c->user->name }}</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">{{ $c->user->email }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-black {{ $c->balance > 0 ? 'text-danger' : 'text-success' }}" style="font-size: 0.9rem;">
                                            {{ $currency }} {{ number_format($c->balance, 2) }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-pill">
                                            <i class="align-middle me-1" data-feather="star" style="width: 10px;"></i>
                                            <span class="fw-black xsmall">{{ $c->points }}</span>
                                        </div>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <button wire:click="selectCustomer({{ $c->id }})" class="btn btn-sm btn-dark rounded-pill px-3 xsmall fw-black text-uppercase shadow-sm">
                                            Ver Estado <i class="align-middle ms-1" data-feather="arrow-right" style="width: 12px;"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top py-3">
                {{ $customers->links() }}
            </div>
        </div>
    @else
        <!-- Modern Individual Statement View -->
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 uppercase font-black tracking-tight">Estado de Cuenta Individual</h1>
                    <p class="text-muted mb-0">Consulta detallada para {{ $customer->user->name }}</p>
                </div>
                <button wire:click="clearSelection" class="btn btn-light border fw-black">
                    <i class="align-middle me-2" data-feather="arrow-left"></i> VOLVER AL LISTADO
                </button>
            </div>
        </div>

        <div class="row">
            <!-- Customer Summary Card -->
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm border-0 overflow-hidden">
                    <div class="bg-primary p-2"></div>
                    <div class="card-body p-4 text-center">
                        <div class="avatar avatar-xl bg-primary-light text-primary rounded-circle d-flex align-items-center justify-center font-black h2 mx-auto mb-3" style="width: 100px; height: 100px;">
                            {{ substr($customer->user->name, 0, 1) }}
                        </div>
                        <h3 class="fw-black text-dark mb-1">{{ $customer->user->name }}</h3>
                        <span class="badge bg-primary px-3 py-2 text-uppercase mb-4">{{ $customer->box_number }}</span>

                        <div class="list-group list-group-flush text-start border rounded-3 mb-4">
                            <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <span class="small text-muted text-uppercase font-black">Saldo Total</span>
                                <span class="h4 mb-0 fw-black {{ $customer->balance > 0 ? 'text-danger' : 'text-success' }}">{{ $currency }} {{ number_format($customer->balance, 2) }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <span class="small text-muted text-uppercase font-black">Puntos Acumulados</span>
                                <span class="h4 mb-0 fw-black text-warning">{{ $customer->points }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <span class="small text-muted text-uppercase font-black">Miembro Desde</span>
                                <span class="fw-bold">{{ $customer->created_at->format('M Y') }}</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('billing.create', ['customer' => $customer->id]) }}" class="btn btn-primary fw-black py-3">
                                <i class="align-middle me-2" data-feather="plus-circle"></i> GENERAR FACTURA
                            </a>
                            <a href="{{ route('billing.statement.download', $customer->id) }}" target="_blank" class="btn btn-light border fw-bold">
                                <i class="align-middle me-2" data-feather="printer"></i> IMPRIMIR REPORTE
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card bg-dark text-white shadow-lg">
                    <div class="card-body p-4 text-center">
                        <i data-feather="shield" class="text-white-50 mb-3" style="width: 40px; height: 40px;"></i>
                        <h5 class="text-white uppercase font-black mb-2">Garantía LogiSaaS</h5>
                        <p class="small text-white-50">Toda la información financiera está protegida y auditada bajo protocolos de seguridad SaaS.</p>
                    </div>
                </div>
            </div>

            <!-- Detailed Tables -->
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 uppercase font-black small"><i class="align-middle text-primary me-2" data-feather="file-text"></i> Movimientos de Facturación</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3">Nº Factura</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th class="pe-4 text-end">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $inv)
                                    <tr>
                                        <td class="ps-4 fw-black text-dark">{{ $inv->number }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $inv->created_at->format('d M, Y') }}</div>
                                            <div class="small text-muted">Vence: {{ $inv->due_date ? $inv->due_date->format('d/m/Y') : 'N/A' }}</div>
                                        </td>
                                        <td class="fw-black text-primary">{{ $currency }} {{ number_format($inv->total, 2) }}</td>
                                        <td>
                                            @php
                                                $isOverdue = $inv->status === 'unpaid' && $inv->due_date && $inv->due_date < now()->today();
                                                $badgeClass = match($inv->status) {
                                                    'paid' => 'bg-success',
                                                    'unpaid' => $isOverdue ? 'bg-danger' : 'bg-warning',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }} text-uppercase">{{ $isOverdue ? 'VENCIDA' : $inv->status }}</span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <a href="{{ route('billing.download', $inv) }}" target="_blank" class="btn btn-sm btn-light border">
                                                <i data-feather="download" style="width: 14px;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted italic">No se registran facturas para este cliente.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($invoices->hasPages())
                        <div class="card-footer bg-light">
                            {{ $invoices->links() }}
                        </div>
                    @endif
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 uppercase font-black small"><i class="align-middle text-primary me-2" data-feather="package"></i> Carga Activa en Sistema</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3">Tracking</th>
                                    <th>Peso</th>
                                    <th>Estado</th>
                                    <th class="pe-4 text-end">Bodega</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($packages as $pkg)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $pkg->tracking_number }}</div>
                                            <div class="small text-muted">{{ Str::limit($pkg->description, 30) }}</div>

                                            <!-- Quick Progress Bar (Alternative 2) -->
                                            @php
                                                $stages = ['prealert', 'received', 'in_transit', 'arrived', 'ready_for_pickup', 'delivered'];
                                                $currentIndex = array_search($pkg->status, $stages);
                                                if($currentIndex === false) $currentIndex = 0;
                                            @endphp
                                            <div class="d-none d-md-block" style="max-width: 250px;">
                                                <div class="tracking-progress">
                                                    @foreach($stages as $index => $stage)
                                                        <div class="tracking-step {{ $index < $currentIndex ? 'completed' : ($index == $currentIndex ? 'active' : '') }}"></div>
                                                    @endforeach
                                                </div>
                                                <div class="tracking-label-row">
                                                    <span class="tracking-label {{ $currentIndex == 0 ? 'active' : '' }}">Pre</span>
                                                    <span class="tracking-label {{ $currentIndex == 1 ? 'active' : '' }}">Rec</span>
                                                    <span class="tracking-label {{ $currentIndex == 2 ? 'active' : '' }}">Via</span>
                                                    <span class="tracking-label {{ $currentIndex == 3 ? 'active' : '' }}">Lleg</span>
                                                    <span class="tracking-label {{ $currentIndex >= 4 ? 'active' : '' }}">Listo</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-black">{{ $pkg->weight }} lbs</td>
                                        <td>
                                            <span class="badge bg-primary-light text-primary text-uppercase">{{ str_replace('_', ' ', $pkg->status) }}</span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <button wire:click="viewPackage({{ $pkg->id }})" class="btn btn-xs btn-primary shadow-sm fw-black">
                                                HISTORIAL <i class="align-middle ms-1" data-feather="clock" style="width: 10px;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted italic">No hay carga pendiente de entrega.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Package Timeline Modal -->
    @if($package_detail)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0" style="border-radius: 1.25rem; overflow: hidden;">
                    <div class="modal-header bg-dark text-white p-4">
                        <div>
                            <h5 class="modal-title uppercase font-black tracking-widest mb-0">Rastreo de Paquete</h5>
                            <small class="text-white-50">{{ $package_detail->tracking_number }}</small>
                        </div>
                        <button type="button" wire:click="closePackage" class="btn-close btn-close-white"></button>
                    </div>
                    <div class="modal-body p-0 bg-light">
                        <div class="p-4 bg-white border-bottom">
                            <div class="row text-center">
                                <div class="col-4 border-end">
                                    <span class="small text-muted text-uppercase d-block mb-1">Peso</span>
                                    <span class="fw-black">{{ $package_detail->weight }} lbs</span>
                                </div>
                                <div class="col-4 border-end">
                                    <span class="small text-muted text-uppercase d-block mb-1">Estado</span>
                                    <span class="badge bg-primary-light text-primary text-uppercase">{{ str_replace('_', ' ', $package_detail->status) }}</span>
                                </div>
                                <div class="col-4">
                                    <span class="small text-muted text-uppercase d-block mb-1">Bodega</span>
                                    <span class="fw-bold">{{ $package_detail->warehouse->code }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Modern Vertical Timeline -->
                        <div class="p-4" style="max-height: 400px; overflow-y: auto;">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="text-uppercase font-black small mb-0">Eventos de Carga</h6>
                                <button class="btn btn-xs btn-outline-primary fw-bold" onclick="alert('Conectando con servicio AfterShip API...')">
                                    <i class="align-middle me-1" data-feather="globe" style="width: 12px;"></i> RASTREO INTERNACIONAL
                                </button>
                            </div>
                            <div class="timeline timeline-modern">
                                @forelse($package_detail->trackingEvents as $event)
                                    <div class="timeline-item pb-4 position-relative ps-4">
                                        <!-- Timeline Line -->
                                        @if(!$loop->last)
                                            <div class="position-absolute h-100 border-start border-2 border-primary border-opacity-25" style="left: 7px; top: 20px;"></div>
                                        @endif

                                        <!-- Timeline Dot -->
                                        <div class="position-absolute rounded-circle bg-primary shadow-sm" style="left: 0; top: 4px; width: 16px; height: 16px; border: 3px solid white;"></div>

                                        <div class="ps-2">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <h6 class="mb-0 fw-black text-dark text-uppercase small">{{ str_replace('_', ' ', $event->status) }}</h6>
                                                <span class="text-muted" style="font-size: 0.65rem;">{{ $event->created_at->format('d M, h:i A') }}</span>
                                            </div>
                                            <p class="small text-muted mb-0">
                                                @if($event->location)
                                                    <i class="align-middle me-1" data-feather="map-pin" style="width: 10px;"></i> {{ $event->location }}
                                                @endif
                                                @if($event->notes)
                                                    <span class="d-block mt-1 italic">{{ $event->notes }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted">
                                        <i data-feather="info" class="mb-2 opacity-25"></i>
                                        <p class="small">No hay eventos registrados para este paquete.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white p-3">
                        <button type="button" wire:click="closePackage" class="btn btn-dark w-100 fw-black text-uppercase tracking-widest">Cerrar Detalle</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
