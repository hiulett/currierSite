<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 uppercase font-black tracking-tight text-dark">Configuración de Fidelización</h1>
            <p class="text-muted small">Gestiona niveles, puntos y beneficios para tus clientes.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message">{{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- General Loyalty Settings -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small">Reglas Generales</h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="saveGeneral">
                        <div class="mb-3">
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:model="loyalty_enabled">
                                <span class="form-check-label font-bold small text-uppercase">Programa de Fidelización Activo</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-bold small text-uppercase text-muted">Puntos por Libra (Weight)</label>
                            <div class="input-group">
                                <input type="number" wire:model="points_per_pound" class="form-control fw-bold" step="0.1">
                                <span class="input-group-text">Pts / Lb</span>
                            </div>
                            <div class="form-text xsmall">Define cuántos puntos gana el cliente por cada libra de peso real facturado.</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-black text-uppercase">
                            GUARDAR CONFIGURACIÓN
                        </button>
                    </form>
                </div>
            </div>

            <!-- Form to Add/Edit Levels -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 uppercase font-black small text-white">{{ $is_editing ? 'Editar Nivel' : 'Nuevo Nivel' }}</h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="saveLevel">
                        <div class="mb-3">
                            <label class="form-label font-bold small text-uppercase text-muted">Nombre del Nivel</label>
                            <input type="text" wire:model="name" class="form-control" placeholder="Ej: Silver, Gold, Platinum...">
                            @error('name') <span class="text-danger xsmall">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-bold small text-uppercase text-muted">Puntos Mínimos</label>
                            <input type="number" wire:model="min_points" class="form-control" placeholder="Ej: 500">
                            @error('min_points') <span class="text-danger xsmall">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-bold small text-uppercase text-muted">Multiplicador de Puntos</label>
                            <input type="number" wire:model="multiplier" class="form-control" step="0.01" placeholder="Ej: 1.10">
                            <div class="form-text xsmall">1.00 = Normal, 1.10 = 10% extra de puntos.</div>
                            @error('multiplier') <span class="text-danger xsmall">{{ $message }}</span> @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label font-bold small text-uppercase text-muted">Color</label>
                                <input type="color" wire:model="color" class="form-control form-control-color w-100">
                            </div>
                            <div class="col-6">
                                <label class="form-label font-bold small text-uppercase text-muted">Icono (Feather)</label>
                                <input type="text" wire:model="icon" class="form-control" placeholder="star, award, zap...">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark flex-grow-1 fw-black text-uppercase">
                                {{ $is_editing ? 'ACTUALIZAR' : 'CREAR NIVEL' }}
                            </button>
                            @if($is_editing)
                                <button type="button" wire:click="resetForm" class="btn btn-light border">
                                    <i data-feather="x"></i>
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Levels List -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 uppercase font-black small text-dark">Niveles de Lealtad Configuradores</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Nivel</th>
                                <th>Rango Puntos</th>
                                <th>Multiplicador</th>
                                <th>Color / Icono</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($levels as $level)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                                 style="width: 32px; height: 32px; background-color: {{ $level->color }}20; color: {{ $level->color }}">
                                                <i data-feather="{{ $level->icon ?: 'star' }}" style="width: 16px;"></i>
                                            </div>
                                            <span class="fw-black text-dark">{{ $level->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark fw-bold border">
                                            Desde {{ number_format($level->min_points) }} pts
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $level->multiplier }}x</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2" style="width: 20px; height: 20px; background-color: {{ $level->color }}; border-radius: 4px;"></div>
                                            <span class="xsmall font-monospace">{{ $level->color }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button wire:click="editLevel({{ $level->id }})" class="btn btn-sm btn-light border me-1">
                                            <i data-feather="edit-2" class="align-middle" style="width: 14px;"></i>
                                        </button>
                                        <button wire:click="deleteLevel({{ $level->id }})" wire:confirm="¿Seguro que quieres eliminar este nivel?" class="btn btn-sm btn-light border text-danger">
                                            <i data-feather="trash-2" class="align-middle" style="width: 14px;"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted italic">
                                        No hay niveles configurados. Define el primero para comenzar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="alert alert-info mt-4 border-0 shadow-sm d-flex align-items-center">
                <i data-feather="info" class="me-3"></i>
                <div class="small">
                    <strong>Tip:</strong> El sistema asigna automáticamente el nivel al cliente basándose en sus puntos acumulados. Asegúrate de que los rangos no se solapen.
                </div>
            </div>
        </div>
    </div>
</div>
