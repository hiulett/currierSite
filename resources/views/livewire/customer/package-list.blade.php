<div class="container-fluid p-0">
    <!-- Page Header -->
    <div class="row mb-2 align-items-center">
        <div class="col-12 col-md-6">
            <h1 class="h4 mb-0 uppercase font-black tracking-tight text-dark">Mis Paquetes</h1>
            <p class="text-muted xsmall mb-0">Control total de tus importaciones desde origen hasta destino.</p>
        </div>
        <div class="col-12 col-md-6 text-md-end mt-2 mt-md-0">
            <a href="{{ route('customer.pre-alert') }}" class="btn btn-sm btn-primary fw-black shadow-sm transform transition hover:scale-105">
                <i class="align-middle me-1" data-feather="plus-circle" style="width: 14px; height: 14px;"></i> PRE-ALERTAR COMPRA
            </a>
        </div>
    </div>

    <!-- Quick Stats for Customer -->
    <div class="row mb-4">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 mb-0">
                <div class="card-body p-3 text-center">
                    <h4 class="mb-0 fw-black text-info">{{ $stats['in_warehouse'] }}</h4>
                    <p class="xsmall text-muted text-uppercase mb-0 font-bold">En Miami</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 mb-0">
                <div class="card-body p-3 text-center">
                    <h4 class="mb-0 fw-black text-warning">{{ $stats['in_transit'] }}</h4>
                    <p class="xsmall text-muted text-uppercase mb-0 font-bold">En Tránsito</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mt-3 mt-md-0">
            <div class="card shadow-sm border-0 mb-0 border-bottom border-success border-4">
                <div class="card-body p-3 text-center">
                    <h4 class="mb-0 fw-black text-success">{{ $stats['ready'] }}</h4>
                    <p class="xsmall text-muted text-uppercase mb-0 font-bold">Listos PTY</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mt-3 mt-md-0">
            <div class="card shadow-sm border-0 mb-0 opacity-75">
                <div class="card-body p-3 text-center">
                    <h4 class="mb-0 fw-black text-muted">{{ $stats['delivered'] }}</h4>
                    <p class="xsmall text-muted text-uppercase mb-0 font-bold">Entregados</p>
                </div>
            </div>
        </div>
    </div>

    <!-- List & Filter Toolset -->
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-light border-bottom d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3 py-3">
            <div class="btn-group btn-group-sm shadow-none border rounded-3 p-1 bg-white">
                <button wire:click="$set('status', 'all')" class="btn {{ $status == 'all' ? 'btn-primary shadow-sm fw-black' : 'btn-white text-muted border-0' }}">Todos</button>
                <button wire:click="$set('status', 'received')" class="btn {{ $status == 'received' ? 'btn-primary shadow-sm fw-black' : 'btn-white text-muted border-0' }}">En Bodega</button>
                <button wire:click="$set('status', 'in_transit')" class="btn {{ $status == 'in_transit' ? 'btn-primary shadow-sm fw-black' : 'btn-white text-muted border-0' }}">En Tránsito</button>
                <button wire:click="$set('status', 'ready_for_pickup')" class="btn {{ $status == 'ready_for_pickup' ? 'btn-primary shadow-sm fw-black' : 'btn-white text-muted border-0' }}">Listo Entrega</button>
            </div>

            <div class="input-group input-group-sm" style="max-width: 350px;">
                <span class="input-group-text bg-white border-end-0 ps-3"><i data-feather="search" class="text-muted" style="width: 14px;"></i></span>
                <input type="text" wire:model.live="search" class="form-control border-start-0 ps-0" placeholder="Buscar por tracking o contenido...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 cursor-pointer" wire:click="sortBy('tracking_number')">
                            Tracking / Descripción
                            @if($sortField === 'tracking_number')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="cursor-pointer" wire:click="sortBy('warehouse_id')">
                            Origen
                            @if($sortField === 'warehouse_id')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="cursor-pointer" wire:click="sortBy('weight')">
                            Peso/Costo
                            @if($sortField === 'weight')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="text-center cursor-pointer" wire:click="sortBy('status')">
                            Estado de Envío
                            @if($sortField === 'status')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="pe-4 text-end">Rastreo</th>
                    </tr>
                </thead>
                @forelse($packages as $package)
                    <tbody x-data="{ expanded: false }" class="border-0" wire:key="pkg-body-{{ $package->id }}">
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-light text-primary rounded p-2 me-3 d-none d-sm-block">
                                        <i data-feather="package" style="width: 16px; height: 16px;"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('customer.tracking', ['search_tracking' => $package->tracking_number]) }}" class="fw-black text-primary hover:underline" title="Ver Rastreo Live">
                                            {{ $package->tracking_number }}
                                        </a>
                                        <div class="text-muted xsmall uppercase font-bold">{{ Str::limit($package->description ?? 'Sin descripción', 40) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-primary border font-black xsmall uppercase tracking-tighter">{{ $package->warehouse->code }}</span>
                            </td>
                            <td>
                                <div class="text-dark fw-black small">{{ $package->weight }} lbs</div>
                                <div class="text-muted xsmall fw-bold">${{ number_format($package->declared_value, 2) }} Dec.</div>
                            </td>
                            <td class="text-center">
                                <span class="badge text-uppercase font-black px-2 py-1" style="font-size: 0.6rem; background-color: {{ $package->getStatusColor() }}">
                                    {{ $package->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <button @click="expanded = !expanded" class="btn btn-sm btn-white border shadow-none font-black uppercase tracking-tighter" style="font-size: 0.65rem;">
                                    <span x-text="expanded ? 'Cerrar' : 'Detalles'"></span>
                                    <i x-show="!expanded" class="align-middle ms-1" data-feather="chevron-down" style="width: 12px;"></i>
                                    <i x-show="expanded" class="align-middle ms-1" data-feather="chevron-up" style="width: 12px; display:none;"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Expanded Details Row (Modern Timeline) -->
                        <tr x-show="expanded" x-cloak x-transition.opacity class="bg-light bg-opacity-50">
                            <td colspan="5" class="p-0 border-top-0">
                                <div class="p-4 p-md-5 bg-white border-top border-bottom border-light shadow-sm mb-0">
                                    <!-- Progress Tracker (Horizontal) -->
                                    <div class="mb-5 px-md-5">
                                        @php
                                            $stages = [
                                                'prealert' => ['icon' => 'bell', 'label' => 'Aviso'],
                                                'received' => ['icon' => 'home', 'label' => 'Bodega'],
                                                'in_transit' => ['icon' => 'truck', 'label' => 'Viaje'],
                                                'arrived' => ['icon' => 'flag', 'label' => 'Arribo'],
                                                'ready_for_pickup' => ['icon' => 'box', 'label' => 'Listo'],
                                                'delivered' => ['icon' => 'check-circle', 'label' => 'Fin']
                                            ];
                                            $currentStageIndex = array_search($package->status, array_keys($stages));
                                            if($currentStageIndex === false) $currentStageIndex = 0;
                                        @endphp
                                        <div class="d-flex justify-content-between position-relative">
                                            <!-- Progress Line Background -->
                                            <div class="position-absolute top-50 start-0 translate-middle-y w-100 bg-light" style="height: 4px; z-index: 1;"></div>
                                            <!-- Active Progress Line -->
                                            <div class="position-absolute top-50 start-0 translate-middle-y bg-primary transition-all duration-500"
                                                 style="height: 4px; z-index: 2; width: {{ ($currentStageIndex / (count($stages) - 1)) * 100 }}%;"></div>

                                            @foreach($stages as $key => $s)
                                                @php
                                                    $idx = array_search($key, array_keys($stages));
                                                    $isCompleted = $idx < $currentStageIndex;
                                                    $isActive = $idx == $currentStageIndex;
                                                @endphp
                                                <div class="position-relative text-center" style="z-index: 3;">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 shadow-sm transition-all
                                                        {{ $isActive ? 'bg-primary text-white scale-110' : ($isCompleted ? 'bg-primary text-white' : 'bg-white text-muted border') }}"
                                                         style="width: 32px; height: 32px; border-width: 2px !important;">
                                                        <i data-feather="{{ $s['icon'] }}" style="width: 14px; height: 14px;"></i>
                                                    </div>
                                                    <span class="xsmall font-black text-uppercase tracking-tighter {{ $isActive || $isCompleted ? 'text-primary' : 'text-muted opacity-50' }}" style="font-size: 0.55rem;">
                                                        {{ $s['label'] }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-lg-7 border-end">
                                            <h6 class="fw-black text-uppercase small text-dark mb-4 tracking-widest d-flex align-items-center">
                                                <i data-feather="list" class="me-2 text-primary" style="width: 16px;"></i> Historial de Movimientos
                                            </h6>
                                            <div class="ps-2">
                                                @forelse($package->trackingEvents as $event)
                                                    <div class="d-flex mb-4 position-relative">
                                                        @if(!$loop->last)
                                                            <div class="position-absolute border-start border-2 border-light" style="left: 17px; top: 35px; bottom: -20px; z-index: 1;"></div>
                                                        @endif
                                                        <div class="bg-{{ $loop->first ? 'primary' : 'light' }} text-{{ $loop->first ? 'white' : 'muted' }} rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0"
                                                             style="width: 36px; height: 36px; z-index: 2; border: 4px solid white; {{ $loop->first ? 'background-color: '.$package->getStatusColor().' !important;' : '' }}">
                                                            <i data-feather="{{ $package->getStatusIcon() }}" style="width: 14px; height: 14px;"></i>
                                                        </div>
                                                        <div class="ms-3 flex-grow-1">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <h6 class="mb-0 fw-black text-uppercase small" style="{{ $loop->first ? 'color: '.$package->getStatusColor().' !important;' : '' }}">
                                                                    {{ $package->getStatusLabel() }}
                                                                </h6>
                                                                <span class="xsmall text-muted font-bold">{{ $event->created_at->format('d M, Y • h:i A') }}</span>
                                                            </div>
                                                            <p class="small text-muted mb-0 mt-1 {{ $loop->first ? 'fw-bold' : '' }}">
                                                                {{ $event->notes ?? 'Actualización automática del sistema.' }}
                                                                @if($event->location)
                                                                    <span class="d-block xsmall opacity-75 mt-1 font-black uppercase text-primary">
                                                                        <i data-feather="map-pin" style="width: 10px;"></i> {{ $event->location }}
                                                                    </span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="text-center py-4 bg-light rounded-3">
                                                        <p class="text-muted small mb-0 italic">Estamos procesando tu carga. El historial aparecerá pronto.</p>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <div class="card bg-light border-0 shadow-none mb-0">
                                                <div class="card-body p-4 text-center">
                                                    <div class="avatar avatar-lg bg-white shadow-sm rounded-circle d-flex align-items-center justify-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                                        <i data-feather="info" class="text-primary"></i>
                                                    </div>
                                                    <h6 class="fw-black text-uppercase xsmall tracking-widest text-dark mb-4">Ficha del Paquete</h6>

                                                    <div class="d-grid gap-2">
                                                        <div class="bg-white p-3 rounded-3 border d-flex justify-content-between align-items-center">
                                                            <span class="xsmall text-muted font-black uppercase">Peso Real:</span>
                                                            <span class="fw-black text-dark">{{ $package->weight }} Lbs</span>
                                                        </div>
                                                        <div class="bg-white p-3 rounded-3 border d-flex justify-content-between align-items-center">
                                                            <span class="xsmall text-muted font-black uppercase">Bodega Actual:</span>
                                                            <span class="fw-black text-primary">{{ $package->warehouse->name }}</span>
                                                        </div>
                                                        <div class="bg-white p-3 rounded-3 border d-flex justify-content-between align-items-center">
                                                            <span class="xsmall text-muted font-black uppercase">Ingreso:</span>
                                                            <span class="fw-black text-dark">{{ $package->created_at->format('d/m/Y') }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="mt-4 pt-3 border-top text-start">
                                                        <h6 class="xsmall font-black text-uppercase text-muted mb-3">Preferencia de Entrega</h6>
                                                        <div class="btn-group w-100 shadow-sm">
                                                            <button wire:click="setDeliveryType({{ $package->id }}, 'pickup')"
                                                                    class="btn btn-sm {{ $package->delivery_type === 'pickup' ? 'btn-primary fw-black' : 'btn-light border text-muted' }}">
                                                                <i data-feather="home" class="me-1" style="width: 12px;"></i> LOCAL
                                                            </button>
                                                            <button wire:click="setDeliveryType({{ $package->id }}, 'home_delivery')"
                                                                    class="btn btn-sm {{ $package->delivery_type === 'home_delivery' ? 'btn-primary fw-black' : 'btn-light border text-muted' }}">
                                                                <i data-feather="map-pin" class="me-1" style="width: 12px;"></i> DOMICILIO
                                                            </button>
                                                        </div>
                                                        <p class="xsmall text-muted mt-2 mb-0 italic">Cambia esto antes de que el paquete llegue al país para agilizar tu entrega.</p>
                                                    </div>

                                                    <div class="mt-4 pt-3 border-top">
                                                        <p class="xsmall text-muted mb-0 italic leading-sm">Para reclamos o consultas sobre este paquete, utiliza nuestro chat de soporte indicando el número de tracking.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                @empty
                    <tbody>
                        <tr>
                            <td colspan="5" class="px-6 py-5 text-center border-0">
                                <div class="stat bg-light text-muted mx-auto mb-3" style="width: 80px; height: 80px;">
                                    <i data-feather="package" style="width: 40px; height: 40px;"></i>
                                </div>
                                <h5 class="fw-black text-dark uppercase mb-2">No se encontró carga</h5>
                                <p class="text-muted small">Prueba ajustando los filtros o realiza una pre-alerta de tu nueva compra.</p>
                            </td>
                        </tr>
                    </tbody>
                @endforelse
            </table>
        </div>
        @if($packages->hasPages())
            <div class="card-footer bg-light border-top py-3">
                {{ $packages->links() }}
            </div>
        @endif
    </div>
</div>
