<div>
    <!-- Last Mile Dashboard -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="setFilter('pending')" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-primary text-white {{ $filter_status === 'pending' ? 'ring-active' : '' }}">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ $stats['pending_deliveries'] }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Rutas Pendientes</p>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="setFilter('out_for_delivery')" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-warning text-white {{ $filter_status === 'out_for_delivery' ? 'ring-active' : '' }}">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ $stats['on_route'] }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">En Ruta de Entrega</p>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="map-pin"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="setFilter('delivered')" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-success text-white {{ $filter_status === 'delivered' ? 'ring-active' : '' }}">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ $stats['delivered_today'] }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Entregados Hoy</p>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="check-square"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="setFilter('')" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-info text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ $packages->count() }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Carga por Asignar</p>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="package"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message">
                <strong>¡Éxito!</strong> {{ session('message') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Sidebar: Form & List -->
        <div class="col-12 col-xl-4 order-2 order-xl-1">
            <div class="card bg-dark text-white shadow-lg mb-4">
                <div class="card-header bg-transparent border-bottom border-white border-opacity-10">
                    <h5 class="card-title text-white mb-0 uppercase font-black small">Crear Hoja de Ruta</h5>
                </div>
                <form wire:submit.prevent="createDelivery">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label small font-bold text-uppercase text-white-50">Nombre de la Ruta</label>
                            <input type="text" wire:model="route_name" placeholder="Ej: Ruta Norte AM" class="form-control bg-white bg-opacity-10 border-0 text-white fw-bold shadow-none">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small font-bold text-uppercase text-white-50">Asignar Repartidor</label>
                            <select wire:model="driver_id" class="form-select bg-white bg-opacity-10 border-0 text-white fw-bold shadow-none">
                                <option value="" class="text-dark">Seleccione...</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" class="text-dark">{{ $driver->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small font-bold text-uppercase text-white-50">Monto a Cobrar (COD)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white bg-opacity-10 border-0 text-white">$</span>
                                <input type="number" step="0.01" wire:model="cod_amount" placeholder="0.00" class="form-control bg-white bg-opacity-10 border-0 text-white fw-bold shadow-none">
                            </div>
                            <div class="form-text text-white-50 small">Suma total de fletes si se cobra en efectivo al cliente.</div>
                        </div>

                        <div class="p-3 rounded-3 mb-4" style="background: rgba(255,255,255,0.05); border: 1px dashed rgba(255,255,255,0.2);">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small font-bold text-white-50 text-uppercase">Paquetes seleccionados:</span>
                                <span class="h4 mb-0 fw-black text-primary">{{ count($selected_packages) }}</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 fw-black text-uppercase tracking-widest shadow-lg transform transition hover:scale-105">
                            <i class="align-middle me-2" data-feather="send"></i> Despachar Ruta
                        </button>
                    </div>
                </form>
            </div>

            <!-- List of active deliveries -->
            <div class="card shadow-sm">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small">Rutas Recientes</h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($deliveries as $del)
                        <div class="list-group-item py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold text-dark">{{ $del->route_name }}</div>
                                    <div class="small text-muted text-uppercase" style="font-size: 0.65rem;">
                                        <i class="align-middle me-1" data-feather="user" style="width: 10px;"></i> {{ $del->driver->name }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <a href="{{ route('logistics.delivery.manifest', $del->id) }}" class="btn btn-sm btn-dark text-uppercase fw-black mb-1" style="font-size: 0.6rem;">GESTIONAR</a>
                                    <div class="small fw-black text-primary">{{ $del->packages_count }} pkgs</div>
                                    @if($del->cod_amount > 0)
                                        <div class="small fw-bold {{ $del->cod_collected ? 'text-success' : 'text-danger' }}" style="font-size: 0.6rem;">
                                            <i class="align-middle" data-feather="dollar-sign" style="width: 10px;"></i>
                                            ${{ number_format($del->cod_amount, 2) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer bg-light p-2">
                    {{ $deliveries->links() }}
                </div>
            </div>
        </div>

        <!-- Package Selection -->
        <div class="col-12 col-xl-8 order-1 order-xl-2 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small">Carga Lista para Entrega</h5>
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" wire:model.live="search_package" class="form-control" placeholder="Buscar carga...">
                        <span class="input-group-text bg-white border-start-0"><i class="align-middle text-muted" data-feather="filter" style="width: 14px;"></i></span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 700px;">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width: 40px;"></th>
                                    <th>Tracking</th>
                                    <th>Cliente</th>
                                    <th class="pe-4 text-end">Peso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($packages as $pkg)
                                    <tr wire:click="togglePackage({{ $pkg->id }})" class="cursor-pointer {{ in_array($pkg->id, $selected_packages) ? 'table-primary' : '' }}">
                                        <td class="ps-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" {{ in_array($pkg->id, $selected_packages) ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-black text-dark">{{ $pkg->tracking_number }}</div>
                                            <div class="text-muted small" style="font-size: 0.65rem;">
                                                @if($pkg->delivery_type === 'home_delivery')
                                                    <span class="text-primary fw-bold"><i data-feather="map-pin" style="width: 10px;"></i> DOMICILIO</span>
                                                @else
                                                    <span class="text-muted"><i data-feather="home" style="width: 10px;"></i> LOCAL</span>
                                                @endif
                                                • {{ Str::limit($pkg->description, 20) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-xs bg-primary text-white rounded-circle d-flex align-items-center justify-center font-bold me-2" style="width: 24px; height: 24px; font-size: 0.6rem;">
                                                    {{ substr($pkg->customer->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold small leading-none">{{ explode(' ', $pkg->customer->user->name)[0] }}</div>
                                                    <div class="text-primary font-black" style="font-size: 0.6rem;">{{ $pkg->customer->box_number }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="pe-4 text-end fw-black text-dark small">{{ $pkg->weight }} lbs</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-5 text-center text-muted italic">No hay carga disponible para asignar a rutas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
