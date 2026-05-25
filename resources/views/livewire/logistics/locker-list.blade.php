<div>
    <!-- Lockers Dashboard -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter_status', '')" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-primary text-white">
                <div class="card-body py-4 text-center">
                    <h3 class="mb-2 fw-black text-white">{{ $stats['total_lockers'] }}</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Total Casilleros</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter_status', 'available')" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-success text-white">
                <div class="card-body py-4 text-center">
                    <h3 class="mb-2 fw-black text-white">{{ $stats['available'] }}</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Disponibles</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter_status', 'occupied')" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-info text-white">
                <div class="card-body py-4 text-center">
                    <h3 class="mb-2 fw-black text-white">{{ $stats['occupied'] }}</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">En Uso</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter_status', 'maintenance')" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-danger text-white">
                <div class="card-body py-4 text-center">
                    <h3 class="mb-2 fw-black text-white">{{ $stats['maintenance'] }}</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">En Mantenimiento</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 border-bottom">
            <h5 class="card-title mb-0 uppercase font-black small">Control de Inventario de Casilleros</h5>
            <div class="d-flex gap-2 w-100 w-md-auto">
                <button onclick="openLockerModal()" class="btn btn-primary shadow-lg transform transition hover:scale-105 fw-black">
                    <i class="align-middle me-1" data-feather="plus-circle"></i> NUEVO CASILLERO
                </button>
                <div class="input-group input-group-sm flex-grow-1" style="min-width: 200px;">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Buscar código...">
                    <span class="input-group-text bg-white"><i class="align-middle text-muted" data-feather="search" style="width: 14px;"></i></span>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 cursor-pointer" wire:click="sortBy('code')">
                            Código
                            @if($sortField === 'code')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="cursor-pointer" wire:click="sortBy('status')">
                            Estado
                            @if($sortField === 'status')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th>Dimensiones (LxWxH)</th>
                        <th class="cursor-pointer" wire:click="sortBy('max_weight')">
                            Peso Máx.
                            @if($sortField === 'max_weight')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th>Asignado a</th>
                        <th class="pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($lockers as $locker)
                    <tr>
                        <td class="ps-4 fw-black text-dark">{{ $locker->code }}</td>
                        <td>
                            @php
                                $statusBadge = [
                                    'available' => 'bg-success',
                                    'occupied' => 'bg-info',
                                    'maintenance' => 'bg-danger'
                                ][$locker->status] ?? 'bg-dark';
                            @endphp
                            <span class="badge {{ $statusBadge }} text-uppercase" style="font-size: 0.65rem;">{{ $locker->status }}</span>
                        </td>
                        <td class="small text-muted">{{ $locker->length ?? '-' }} x {{ $locker->width ?? '-' }} x {{ $locker->height ?? '-' }} in</td>
                        <td><span class="fw-bold">{{ $locker->max_weight ?? '-' }} lbs</span></td>
                        <td class="small">
                            @if($locker->customer)
                                <div class="fw-bold text-dark">{{ $locker->customer->user->name }}</div>
                            @else
                                <span class="text-muted opacity-50">Disponible</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <button class="btn btn-sm btn-light border shadow-sm" title="Editar Casillero">
                                <i class="align-middle text-dark" data-feather="edit-2"></i>
                                <span class="ms-1 d-none d-md-inline fw-bold text-uppercase" style="font-size: 0.65rem;">Editar</span>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer border-top bg-light">
            {{ $lockers->links() }}
        </div>
    </div>

    <!-- Add Locker Modal (Bootstrap 5 Style) -->
    <div class="modal fade" id="modalAddLocker" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                <div class="modal-header bg-primary text-white p-4">
                    <h5 class="modal-title uppercase font-black tracking-widest text-white">
                        <i class="align-middle me-2" data-feather="grid"></i> Nuevo Casillero Físico
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="createLocker">
                    <div class="modal-body bg-light p-4">
                        <div class="mb-3">
                            <label class="form-label small font-black text-uppercase text-muted">Código de Ubicación</label>
                            <input wire:model="code" type="text" class="form-control form-control-lg fw-black text-primary border-2" placeholder="Ej: SECTOR-A-01">
                            @error('code') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small font-black text-uppercase text-muted">Estado Inicial</label>
                                <select wire:model="status" class="form-select border-2">
                                    <option value="available">Disponible</option>
                                    <option value="occupied">Ocupado</option>
                                    <option value="maintenance">Mantenimiento</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small font-black text-uppercase text-muted">Peso Máx (lbs)</label>
                                <input wire:model="max_weight" type="number" step="0.01" class="form-control border-2">
                            </div>
                        </div>
                        <div class="p-3 bg-white rounded-3 border">
                            <label class="form-label small font-black text-uppercase text-primary mb-3 d-block">Dimensiones del Espacio (Inches)</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="number" placeholder="L" wire:model="length" class="form-control form-control-sm text-center fw-bold">
                                </div>
                                <div class="col-4">
                                    <input type="number" placeholder="W" wire:model="width" class="form-control form-control-sm text-center fw-bold">
                                </div>
                                <div class="col-4">
                                    <input type="number" placeholder="H" wire:model="height" class="form-control form-control-sm text-center fw-bold">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-top-0 p-4 pt-0 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">CANCELAR</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-lg transform transition hover:scale-105 fw-black">GUARDAR CASILLERO</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openLockerModal() {
            var el = document.getElementById('modalAddLocker');
            var myModal = bootstrap.Modal.getOrCreateInstance(el);
            myModal.show();
        }

        window.addEventListener('locker-saved', event => {
             bootstrap.Modal.getOrCreateInstance(document.getElementById('modalAddLocker')).hide();
        });
    </script>
</div>
