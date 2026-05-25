<div>
    <div class="row mb-3">
        <div class="col-auto d-none d-sm-block">
            <h1 class="h3 mb-2 uppercase font-black tracking-tight">Gestión de Embarques</h1>
            <p class="text-muted">Agrupa paquetes en manifiestos para envíos masivos.</p>
        </div>
        <div class="col-auto ms-auto text-end">
            <button onclick="openShipmentModal()" class="btn btn-primary btn-lg shadow-lg transform transition hover:scale-105">
                <i class="align-middle me-2" data-feather="plus-circle"></i> NUEVO EMBARQUE
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter_status', 'draft')" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-primary text-white">
                <div class="card-body py-4">
                    <h3 class="mb-2 fw-black text-white">{{ $stats['preparing'] }}</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">En Preparación</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter_status', 'in_transit')" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-warning text-white">
                <div class="card-body py-4 text-center">
                    <h3 class="mb-2 fw-black text-white">{{ $stats['in_transit'] }}</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Vuelos en Tránsito</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter_status', 'arrived')" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-success text-white">
                <div class="card-body py-4 text-center">
                    <h3 class="mb-2 fw-black text-white">{{ $stats['arrived_recently'] }}</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Llegaron Hoy</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter_status', '')" class="card flex-fill bg-dark text-white border-0 shadow-sm cursor-pointer transform transition hover:scale-102">
                <div class="card-body py-4 text-center">
                    <h3 class="mb-2 fw-black text-white">{{ number_format($shipments->total()) }}</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Paquetes Totales</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-light border-bottom">
            <h5 class="card-title mb-0 uppercase font-black small">Listado de Manifiestos</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped my-0">
                <thead>
                    <tr>
                        <th class="ps-4 cursor-pointer" wire:click="sortBy('manifest_number')">
                            Manifiesto #
                            @if($sortField === 'manifest_number')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="cursor-pointer" wire:click="sortBy('carrier_name')">
                            Carrier / Vuelo
                            @if($sortField === 'carrier_name')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="text-center">Paquetes</th>
                        <th class="text-center cursor-pointer" wire:click="sortBy('status')">
                            Estado
                            @if($sortField === 'status')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shipments as $sh)
                        <tr>
                            <td class="ps-4 fw-black text-dark">{{ $sh->manifest_number }}</td>
                            <td class="text-uppercase fw-bold text-muted small">{{ $sh->carrier_name ?? 'Sin asignar' }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary-light text-primary fw-bold px-3 py-1">{{ $sh->packages_count }} pkgs</span>
                            </td>
                            <td class="text-center">
                                @php
                                    $statusStyles = [
                                        'draft' => 'bg-secondary',
                                        'in_transit' => 'bg-warning',
                                        'arrived' => 'bg-success',
                                    ][$sh->status] ?? 'bg-dark';
                                @endphp
                                <span class="badge {{ $statusStyles }} text-uppercase" style="font-size: 0.65rem;">
                                    {{ $sh->status }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('logistics.shipments.detail', $sh->id) }}" class="btn btn-sm btn-dark text-uppercase fw-black" style="font-size: 0.65rem; letter-spacing: 0.05em; padding: 0.4rem 1rem;">GESTIONAR</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center text-muted italic">No hay embarques registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light border-top">
            {{ $shipments->links() }}
        </div>
    </div>

    <!-- Add Shipment Modal (Bootstrap 5 Style) -->
    <div class="modal fade" id="newShipmentModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                <div class="modal-header bg-primary text-white p-4">
                    <h5 class="modal-title uppercase font-black tracking-widest text-white">
                        <i class="align-middle me-2" data-feather="plus-circle"></i> Nuevo Embarque / Manifiesto
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="createShipment">
                    <div class="modal-body bg-light p-4">
                        <div class="mb-4">
                            <label class="form-label small text-muted text-uppercase fw-black tracking-widest">Número de Manifiesto</label>
                            <input type="text" wire:model="manifest_number" placeholder="Ej: AIR-2024-001" class="form-control form-control-lg fw-black text-primary border-2">
                            @error('manifest_number') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label small text-muted text-uppercase fw-black tracking-widest">Transportista / Aerolínea</label>
                            <input type="text" wire:model="carrier_name" placeholder="Ej: DHL Aviation, Copa Cargo" class="form-control border-2">
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-top-0 p-4 pt-0 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">CANCELAR</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-lg transform transition hover:scale-105 fw-black">CREAR MANIFIESTO</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openShipmentModal() {
            var el = document.getElementById('newShipmentModal');
            var myModal = bootstrap.Modal.getOrCreateInstance(el);
            myModal.show();
        }
    </script>
</div>
