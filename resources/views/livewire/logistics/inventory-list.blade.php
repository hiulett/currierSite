<div>
    <!-- Inventory Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter_status', '')" class="card flex-fill cursor-pointer transform transition hover:scale-102 border-0 shadow-sm bg-primary text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ number_format($stats['total_count']) }}</h3>
                            <p class="mb-2 text-uppercase font-bold small opacity-75">Paquetes en Stock</p>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="box"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-dark text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ number_format($stats['total_weight'], 2) }} lbs</h3>
                            <p class="mb-2 text-uppercase font-bold small opacity-75">Peso Total Almacenado</p>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="database"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter_status', 'received')" class="card flex-fill cursor-pointer transform transition hover:scale-102 border-0 shadow-sm bg-info text-white {{ $filter_status === 'received' ? 'ring-active' : '' }}">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ $stats['by_status']->where('status', 'received')->first()->count ?? 0 }}</h3>
                            <p class="mb-2 text-uppercase font-bold small opacity-75">Recién Ingresados</p>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="plus-square"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter_status', 'in_transit')" class="card flex-fill cursor-pointer transform transition hover:scale-102 border-0 shadow-sm bg-warning text-white {{ $filter_status === 'in_transit' ? 'ring-active' : '' }}">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ $stats['by_status']->where('status', 'in_transit')->first()->count ?? 0 }}</h3>
                            <p class="mb-2 text-uppercase font-bold small opacity-75">En Tránsito</p>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="truck"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message"><strong>¡Éxito!</strong> {{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Distribution by Warehouse -->
        <div class="col-12 col-lg-6 d-flex">
            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Distribución por Bodega</h5>
                </div>
                <div class="card-body">
                    @foreach($stats['by_warehouse'] as $whStat)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold small text-uppercase">{{ $whStat->name }}</span>
                                <span class="badge bg-primary-light text-primary">{{ $whStat->count }} pkgs</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ ($whStat->count / max($stats['total_count'], 1)) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                    @if($stats['by_warehouse']->isEmpty())
                        <p class="text-center text-muted small py-4">No hay datos de distribución.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Breakdown -->
        <div class="col-12 col-lg-6 d-flex">
            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Estados de Inventario</h5>
                </div>
                <div class="card-body d-flex">
                    <div class="align-self-center w-100 text-center">
                        <div class="row g-3">
                            @foreach($stats['by_status'] as $statusStat)
                                @php
                                    // Create a dummy package to use the model's helper methods
                                    $dummy = new \App\Models\Package(['status' => $statusStat->status]);
                                @endphp
                                <div class="col-6 col-md-4">
                                    <div wire:click="$set('filter_status', '{{ $statusStat->status }}')"
                                         class="p-3 bg-light rounded-3 cursor-pointer transform transition hover:scale-105 {{ $filter_status === $statusStat->status ? 'border-primary ring-1 ring-primary' : '' }}"
                                         style="{{ $filter_status === $statusStat->status ? 'background-color: rgba(59, 125, 221, 0.1) !important;' : '' }}">
                                        <h4 class="mb-0 fw-black" style="color: {{ $dummy->getStatusColor() }}">{{ $statusStat->count }}</h4>
                                        <p class="small text-muted text-uppercase mb-0 font-bold" style="font-size: 0.6rem;">{{ $dummy->getStatusLabel() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Filter & List -->
    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center bg-light border-bottom gap-3">
            <h5 class="card-title mb-0 uppercase font-black small">Listado Detallado de Carga</h5>
            <div class="d-flex flex-wrap gap-2 w-100 w-md-auto">
                <button onclick="openReceiveModal()" class="btn btn-primary shadow-lg transform transition hover:scale-105">
                    <i class="align-middle me-1" data-feather="plus-circle"></i> NUEVO INGRESO
                </button>
                <select wire:model.live="filter_warehouse" class="form-select form-select-sm" style="width: 150px;">
                    <option value="">Todas las Bodegas</option>
                    @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}">{{ $wh->code }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filter_status" class="form-select form-select-sm" style="width: 150px;">
                    <option value="">Todos los Estados</option>
                    <option value="received">Recibido</option>
                    <option value="prealert">Pre-alerta</option>
                    <option value="in_transit">En Tránsito</option>
                    <option value="arrived">Llegó al País</option>
                    <option value="ready_for_pickup">Listo Entrega</option>
                </select>
                <select wire:model.live="filter_delivery_type" class="form-select form-select-sm" style="width: 130px;">
                    <option value="">Tipo Entrega</option>
                    <option value="pickup">Local</option>
                    <option value="home_delivery">Domicilio</option>
                </select>
                <div class="input-group input-group-sm flex-grow-1" style="min-width: 200px;">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Buscar tracking o casillero...">
                    <span class="input-group-text bg-white"><i class="align-middle" data-feather="search"></i></span>
                </div>
                <a href="{{ route('logistics.inventory.export') }}" class="btn btn-sm btn-success">
                    <i class="align-middle" data-feather="download"></i> Excel
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped my-0">
                <thead>
                    <tr>
                        <th class="ps-4 cursor-pointer" wire:click="sortBy('tracking_number')">
                            Paquete
                            @if($sortField === 'tracking_number')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="cursor-pointer" wire:click="sortBy('customer_id')">
                            Cliente
                            @if($sortField === 'customer_id')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th>Entrega</th>
                        <th class="cursor-pointer" wire:click="sortBy('warehouse_id')">
                            Bodega
                            @if($sortField === 'warehouse_id')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="cursor-pointer" wire:click="sortBy('weight')">
                            Peso
                            @if($sortField === 'weight')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="cursor-pointer" wire:click="sortBy('status')">
                            Estado
                            @if($sortField === 'status')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-black text-dark">{{ $package->tracking_number }}</div>
                                <div class="text-muted small" style="font-size: 0.7rem;">{{ Str::limit($package->description, 35) }}</div>
                            </td>
                            <td>
                                <div class="fw-bold leading-none">{{ $package->customer->user->name }}</div>
                                <div class="text-primary small font-black uppercase tracking-tighter">{{ $package->customer->box_number }}</div>
                            </td>
                            <td class="text-center">
                                @if($package->delivery_type === 'home_delivery')
                                    <span class="badge bg-primary-light text-primary" title="Entrega a Domicilio">
                                        <i data-feather="map-pin" style="width: 12px; height: 12px;"></i>
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border" title="Retiro en Local">
                                        <i data-feather="home" style="width: 12px; height: 12px;"></i>
                                    </span>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $package->warehouse->code }}</span></td>
                            <td><span class="fw-bold">{{ $package->weight }} lbs</span></td>
                            <td>
                                <span class="badge text-uppercase" style="font-size: 0.65rem; background-color: {{ $package->getStatusColor() }}">
                                    {{ $package->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group">
                                    <a href="{{ route('logistics.label', $package->id) }}" target="_blank" class="btn btn-sm btn-light border shadow-sm" title="Imprimir Etiqueta">
                                        <i class="align-middle text-primary" data-feather="printer"></i>
                                        <span class="ms-1 d-none d-md-inline fw-bold text-uppercase" style="font-size: 0.65rem;">Etiqueta</span>
                                    </a>
                                    <button wire:click="editPackage({{ $package->id }})" class="btn btn-sm btn-light border shadow-sm" title="Editar Paquete">
                                        <i class="align-middle text-dark" data-feather="edit-2"></i>
                                        <span class="ms-1 d-none d-md-inline fw-bold text-uppercase" style="font-size: 0.65rem;">Editar</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted italic">
                                No se encontraron paquetes con los filtros aplicados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer border-top bg-light">
            {{ $packages->links() }}
        </div>
    </div>

    <!-- Modal for Receiving Package (Bootstrap 5 Style) -->
    <div class="modal fade" id="modalReceivePackage" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title uppercase font-black tracking-widest">
                        <i class="align-middle me-2" data-feather="plus"></i> Nuevo Ingreso a Bodega
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light p-0">
                    <div class="p-3 p-md-4">
                        @livewire('logistics.receive-package')
                    </div>
                </div>
                <div class="modal-footer bg-white border-top-0">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">FINALIZAR Y CERRAR</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Package -->
    <div class="modal fade" id="modalEditPackage" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title uppercase font-black tracking-widest">
                        <i class="align-middle me-2" data-feather="edit-3"></i> Editar Información de Paquete
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="updatePackage">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small font-black text-uppercase text-muted">Número de Tracking</label>
                            <input type="text" wire:model="edit_tracking_number" class="form-control fw-bold border-2">
                            @error('edit_tracking_number') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label small font-black text-uppercase text-muted">Peso (lbs)</label>
                                <input type="number" step="0.01" wire:model="edit_weight" class="form-control fw-bold border-2">
                                @error('edit_weight') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label small font-black text-uppercase text-muted">Bodega</label>
                                <select wire:model="edit_warehouse_id" class="form-select fw-bold border-2">
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}">{{ $wh->code }} - {{ $wh->name }}</option>
                                    @endforeach
                                </select>
                                @error('edit_warehouse_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small font-black text-uppercase text-muted">Estado Actual</label>
                            <select wire:model="edit_status" class="form-select fw-bold border-2">
                                <option value="received">Recibido</option>
                                <option value="prealert">Pre-alerta</option>
                                <option value="in_transit">En Tránsito</option>
                                <option value="arrived">Llegó al País</option>
                                <option value="ready_for_pickup">Listo para Entrega</option>
                                <option value="out_for_delivery">En Ruta de Entrega</option>
                                <option value="delivered">Entregado</option>
                            </select>
                            @error('edit_status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label small font-black text-uppercase text-muted">Descripción / Contenido</label>
                            <textarea wire:model="edit_description" rows="2" class="form-control border-2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">CANCELAR</button>
                        <button type="submit" class="btn btn-primary fw-black px-4">GUARDAR CAMBIOS</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openReceiveModal() {
            var el = document.getElementById('modalReceivePackage');
            var myModal = bootstrap.Modal.getOrCreateInstance(el);
            myModal.show();
        }

        window.addEventListener('package-saved', event => {
            // Optional: Close modal after success if you prefer
            // bootstrap.Modal.getOrCreateInstance(document.getElementById('modalReceivePackage')).hide();
        });

        window.addEventListener('open-edit-modal', event => {
            var el = document.getElementById('modalEditPackage');
            var myModal = bootstrap.Modal.getOrCreateInstance(el);
            myModal.show();
        });

        window.addEventListener('close-edit-modal', event => {
            var el = document.getElementById('modalEditPackage');
            var myModal = bootstrap.Modal.getOrCreateInstance(el);
            myModal.hide();
        });
    </script>
</div>
