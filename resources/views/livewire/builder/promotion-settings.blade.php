<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 uppercase font-black tracking-tight text-dark">Gestión de Promociones</h1>
            <p class="text-muted small">Crea cupones y descuentos dinámicos para tus clientes.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message">{{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- List -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 uppercase font-black small">Cupones y Campañas</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Nombre / Código</th>
                                <th>Descuento</th>
                                <th>Validez</th>
                                <th>Uso</th>
                                <th>Estado</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promotions as $promo)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-black text-dark">{{ $promo->name }}</div>
                                        <div class="badge bg-primary-light text-primary font-black xsmall">{{ $promo->code }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $promo->type === 'percentage' ? $promo->value . '%' : '$' . $promo->value }}</div>
                                    </td>
                                    <td class="small">
                                        @if($promo->start_date || $promo->end_date)
                                            {{ $promo->start_date?->format('d/m/Y') ?? '...' }} - {{ $promo->end_date?->format('d/m/Y') ?? '...' }}
                                        @else
                                            <span class="text-muted">Siempre activo</span>
                                        @endif
                                    </td>
                                    <td class="small">
                                        {{ $promo->used_count }} / {{ $promo->usage_limit ?? '∞' }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $promo->is_active && $promo->isValid() ? 'bg-success' : 'bg-danger' }} text-uppercase" style="font-size: 0.6rem;">
                                            {{ $promo->is_active && $promo->isValid() ? 'ACTIVO' : 'INACTIVO' }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button wire:click="edit({{ $promo->id }})" class="btn btn-sm btn-light border me-1">
                                            <i data-feather="edit-2" style="width: 14px;"></i>
                                        </button>
                                        <button wire:click="delete({{ $promo->id }})" wire:confirm="¿Seguro que quieres eliminar esta promoción?" class="btn btn-sm btn-light border text-danger">
                                            <i data-feather="trash-2" style="width: 14px;"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No hay promociones registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-top">
                    {{ $promotions->links() }}
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title text-white mb-0 uppercase font-black small">{{ $is_editing ? 'Editar Promoción' : 'Nueva Promoción' }}</h5>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label class="form-label font-bold small text-uppercase">Nombre de la Campaña</label>
                            <input type="text" wire:model="name" class="form-control" placeholder="Ej: Descuento Black Friday">
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-bold small text-uppercase">Código del Cupón</label>
                            <input type="text" wire:model="code" class="form-control fw-black" placeholder="Ej: BLACK2024">
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label font-bold small text-uppercase">Tipo</label>
                                <select wire:model="type" class="form-select">
                                    <option value="percentage">Porcentaje (%)</option>
                                    <option value="fixed">Fijo ($)</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label font-bold small text-uppercase">Valor</label>
                                <input type="number" wire:model="value" class="form-control" step="0.01">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label font-bold small text-uppercase">Desde</label>
                                <input type="date" wire:model="start_date" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label font-bold small text-uppercase">Hasta</label>
                                <input type="date" wire:model="end_date" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-bold small text-uppercase">Límite de Usos Totales</label>
                            <input type="number" wire:model="usage_limit" class="form-control" placeholder="Ej: 100 (Vacío para ilimitado)">
                        </div>

                        <div class="mb-4">
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:model="is_active">
                                <span class="form-check-label font-bold small text-uppercase">Promoción Activa</span>
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-black text-uppercase py-2">
                                {{ $is_editing ? 'ACTUALIZAR' : 'CREAR PROMOCIÓN' }}
                            </button>
                            @if($is_editing)
                                <button type="button" wire:click="resetForm" class="btn btn-light border fw-bold text-uppercase py-2">CANCELAR</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
