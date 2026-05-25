<div>
    <!-- Repack Dashboard -->
    <div class="row mb-4">
        <div class="col-12 col-sm-4 col-xxl-3 d-flex">
            <a href="{{ route('logistics.inventory', ['filter_status' => 'received']) }}" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-primary text-white text-decoration-none">
                <div class="card-body py-4">
                    <h3 class="mb-1 fw-black text-white">{{ $stats['waiting_repack'] }}</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Paquetes para consolidar</p>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-4 col-xxl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-warning text-white">
                <div class="card-body py-4">
                    <h3 class="mb-1 fw-black text-white">{{ number_format($stats['total_weight_pending'], 2) }} lbs</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Peso pendiente</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4 col-xxl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-success text-white">
                <div class="card-body py-4">
                    <h3 class="mb-1 fw-black text-white">{{ $stats['completed_today'] }}</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Consolidados hoy</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2 uppercase font-black tracking-tight">Interfaz de Reempaque</h1>
            <p class="text-muted">Consolida múltiples paquetes de un cliente en una sola caja nueva.</p>
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
        <!-- Sidebar: Search & New Box -->
        <div class="col-12 col-xl-4 order-2 order-xl-1">
            <!-- Step 1: Search -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0 font-black small uppercase">1. Identificar Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="mb-0">
                        <label class="form-label small font-bold text-uppercase text-muted">Número de Casillero</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="align-middle" data-feather="search"></i></span>
                            <input type="text" wire:model.live="box_number" placeholder="PTY-XXXXX" class="form-control form-control-lg fw-bold">
                        </div>
                    </div>

                    @if($found_customer)
                        <div class="mt-4 p-3 bg-primary-light rounded-3 border border-primary border-opacity-10">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-center font-bold me-3">
                                    {{ substr($found_customer->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-black text-primary leading-none">{{ $found_customer->user->name }}</div>
                                    <div class="small text-primary opacity-75 font-bold">{{ $found_customer->box_number }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if(count($selected_packages) > 0)
                <!-- Step 3: New Box Details -->
                <div class="card bg-dark text-white shadow-lg animate-in slide-in-from-bottom-2">
                    <div class="card-header bg-transparent border-bottom border-white border-opacity-10">
                        <h5 class="card-title text-white mb-0 font-black small uppercase">3. Generar Nueva Caja</h5>
                    </div>
                    <form wire:submit.prevent="processRepack">
                        <div class="card-body p-4">
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label small font-bold text-uppercase text-white-50">Peso Final (lbs)</label>
                                    <input type="number" step="0.01" wire:model="new_weight" class="form-control bg-white bg-opacity-10 border-0 text-white fw-bold">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small font-bold text-uppercase text-white-50">Bodega</label>
                                    <select wire:model="warehouse_id" class="form-select bg-white bg-opacity-10 border-0 text-white fw-bold">
                                        <option value="">...</option>
                                        @foreach($warehouses as $wh)
                                            <option value="{{ $wh->id }}">{{ $wh->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row g-2 mb-4">
                                <div class="col-4">
                                    <label class="form-label small font-bold text-uppercase text-white-50" style="font-size: 0.6rem;">Largo</label>
                                    <input type="number" wire:model="new_length" class="form-control form-control-sm bg-white bg-opacity-10 border-0 text-white text-center">
                                </div>
                                <div class="col-4">
                                    <label class="form-label small font-bold text-uppercase text-white-50" style="font-size: 0.6rem;">Ancho</label>
                                    <input type="number" wire:model="new_width" class="form-control form-control-sm bg-white bg-opacity-10 border-0 text-white text-center">
                                </div>
                                <div class="col-4">
                                    <label class="form-label small font-bold text-uppercase text-white-50" style="font-size: 0.6rem;">Alto</label>
                                    <input type="number" wire:model="new_height" class="form-control form-control-sm bg-white bg-opacity-10 border-0 text-white text-center">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small font-bold text-uppercase text-white-50">Descripción del Contenido</label>
                                <input type="text" wire:model="new_description" class="form-control bg-white bg-opacity-10 border-0 text-white" placeholder="Ej: Ropa y electrónicos">
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 fw-black text-uppercase tracking-widest shadow-lg transform transition hover:scale-105">
                                <i class="align-middle me-2" data-feather="package"></i> Procesar Reempaque
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <!-- Main Content: Package Selection -->
        <div class="col-12 col-xl-8 order-1 order-xl-2 mb-4">
            @if($found_customer)
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 font-black small uppercase">2. Seleccionar Carga a Consolidar</h5>
                        <span class="badge bg-primary px-3 py-2 fw-black">{{ count($selected_packages) }} PAQUETES SELECCIONADOS</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 600px;">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4"></th>
                                        <th>Tracking</th>
                                        <th>Descripción</th>
                                        <th>Peso</th>
                                        <th class="pe-4 text-end">Ingreso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($packages as $pkg)
                                        <tr wire:click="togglePackage({{ $pkg->id }})" class="cursor-pointer {{ in_array($pkg->id, $selected_packages) ? 'table-primary' : '' }}">
                                            <td class="ps-4 text-center">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" {{ in_array($pkg->id, $selected_packages) ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td class="fw-black text-dark">
                                                {{ $pkg->tracking_number }}
                                                @if($pkg->delivery_type === 'home_delivery')
                                                    <i data-feather="map-pin" class="text-primary ms-1" style="width: 10px;" title="Domicilio"></i>
                                                @else
                                                    <i data-feather="home" class="text-success ms-1" style="width: 10px;" title="Local"></i>
                                                @endif
                                            </td>
                                            <td class="small">{{ Str::limit($pkg->description, 40) }}</td>
                                            <td><span class="badge bg-light text-dark border">{{ $pkg->weight }} lbs</span></td>
                                            <td class="pe-4 text-end text-muted small">{{ $pkg->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-5 text-center text-muted italic">
                                                No hay carga pendiente de reempaque para este cliente.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-dashed h-100 d-flex align-items-center justify-center p-5 text-center bg-light bg-opacity-50">
                    <div class="py-5">
                        <div class="avatar avatar-xl bg-white shadow-sm rounded-circle d-flex align-items-center justify-center mx-auto mb-4" style="width: 100px; height: 100px;">
                            <i class="text-muted opacity-25" data-feather="box" style="width: 50px; height: 50px;"></i>
                        </div>
                        <h4 class="text-muted fw-black uppercase tracking-widest">Esperando ID de Cliente</h4>
                        <p class="text-muted small">Ingresa el casillero en el panel de la izquierda para ver los paquetes disponibles.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
