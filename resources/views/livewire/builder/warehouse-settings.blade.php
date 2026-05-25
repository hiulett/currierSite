<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-6">
            <h2 class="h3 mb-0 uppercase font-black tracking-tight text-dark">Gestión de Bodegas</h2>
            <p class="text-muted small mb-0">Configura los centros de recepción de carga internacionales.</p>
        </div>
        <div class="col-12 col-md-6 text-md-end mt-3 mt-md-0">
            <button wire:click="createWarehouse" class="btn btn-primary fw-black shadow-lg">
                <i class="align-middle me-1" data-feather="plus-circle"></i> AGREGAR NUEVA BODEGA
            </button>
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
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-light border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 uppercase font-black small">Listado de Bodegas</h5>
                    <div class="input-group input-group-sm" style="max-width: 250px;">
                        <input type="text" wire:model.live="search" class="form-control" placeholder="Buscar bodega...">
                        <span class="input-group-text bg-white"><i data-feather="search" style="width: 14px;"></i></span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Código / Nombre</th>
                                <th>Dirección Completa</th>
                                <th class="text-center">Servicio</th>
                                <th class="text-center">Estado</th>
                                <th class="pe-4 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warehouses as $wh)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-black text-dark h5 mb-0">{{ $wh->code }}</div>
                                        <div class="text-muted small uppercase font-bold">{{ $wh->name }}</div>
                                    </td>
                                    <td>
                                        <div class="small fw-bold text-dark">{{ $wh->address }}</div>
                                        <div class="text-muted xsmall uppercase">{{ $wh->city }}, {{ $wh->state }} {{ $wh->zip_code }}</div>
                                    </td>
                                    <td class="text-center">
                                        @if($wh->service_type === 'air' || $wh->service_type === 'both')
                                            <span class="badge bg-primary-light text-primary mb-1" title="Aéreo"><i data-feather="send" style="width: 10px;"></i></span>
                                        @endif
                                        @if($wh->service_type === 'maritime' || $wh->service_type === 'both')
                                            <span class="badge bg-info-light text-info mb-1" title="Marítimo"><i data-feather="anchor" style="width: 10px;"></i></span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" wire:click="toggleStatus({{ $wh->id }})" {{ $wh->is_active ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group">
                                            <button wire:click="editWarehouse({{ $wh->id }})" class="btn btn-sm btn-light border shadow-sm" title="Editar">
                                                <i class="align-middle text-dark" data-feather="edit-2"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted italic">No hay bodegas configuradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Warehouse Modal -->
    <div class="modal fade" id="warehouseModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
                <div class="modal-header bg-primary text-white py-4">
                    <h5 class="modal-title fw-black text-uppercase small tracking-widest text-white">
                        {{ $is_editing ? 'Editar Bodega' : 'Nueva Bodega' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body p-4 p-md-5">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label font-black text-uppercase small text-muted">Código</label>
                                <input type="text" wire:model="code" class="form-control fw-bold border-2" placeholder="MIA">
                                @error('code') <div class="text-danger xsmall mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-8">
                                <label class="form-label font-black text-uppercase small text-muted">Nombre Bodega</label>
                                <input type="text" wire:model="name" class="form-control fw-bold border-2" placeholder="Miami Hub Principal">
                                @error('name') <div class="text-danger xsmall mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label font-black text-uppercase small text-muted">Dirección Línea 1</label>
                                <input type="text" wire:model="address" class="form-control border-2">
                                @error('address') <div class="text-danger xsmall mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label font-black text-uppercase small text-muted">Ciudad</label>
                                <input type="text" wire:model="city" class="form-control border-2">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label font-black text-uppercase small text-muted">Estado</label>
                                <input type="text" wire:model="state" class="form-control border-2">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label font-black text-uppercase small text-muted">Zip Code</label>
                                <input type="text" wire:model="zip_code" class="form-control border-2">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label font-black text-uppercase small text-muted">País</label>
                                <input type="text" wire:model="country" class="form-control border-2">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-black text-uppercase small text-muted">Tipo de Servicio</label>
                                <select wire:model="service_type" class="form-select border-2 fw-bold">
                                    <option value="air">Solo Aéreo</option>
                                    <option value="maritime">Solo Marítimo</option>
                                    <option value="both">Ambos Servicios</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-4">
                        <button type="button" class="btn btn-light border fw-bold text-uppercase" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 fw-black text-uppercase shadow">
                            {{ $is_editing ? 'Actualizar Bodega' : 'Crear Bodega' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('open-warehouse-modal', () => {
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('warehouseModal'));
            modal.show();
        });

        window.addEventListener('close-warehouse-modal', () => {
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('warehouseModal'));
            if (modal) modal.hide();
        });
    </script>
</div>
