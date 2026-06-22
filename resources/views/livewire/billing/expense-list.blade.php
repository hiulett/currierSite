<div>
    <!-- Header -->
    <div class="row mb-3">
        <div class="col-12 col-md-6">
            <h1 class="h3 mb-1 uppercase font-black tracking-tight">Egresos y Gastos Administrativos</h1>
            <p class="text-muted small">Controle los costos fijos, variables y operativos de la empresa.</p>
        </div>
        <div class="col-12 col-md-6 text-md-end d-flex justify-content-md-end gap-2 align-items-center">
            <button wire:click="$toggle('managing_categories')" class="btn btn-sm btn-light border shadow-sm fw-bold">
                <i class="align-middle me-1" data-feather="settings"></i> CATEGORÍAS
            </button>
            <button wire:click="openCreateModal" class="btn btn-sm btn-danger shadow-sm fw-black uppercase">
                <i class="align-middle me-1" data-feather="plus"></i> REGISTRAR EGRESO
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message"><strong>¡Éxito!</strong> {{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message"><strong>¡Error!</strong> {{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Cards Summary -->
    <div class="row mb-4">
        <!-- Monthly Expenses -->
        <div class="col-12 col-md-4 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-white">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-3 me-3">
                            <i class="align-middle" data-feather="minus-circle" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <span class="text-muted text-uppercase font-bold small d-block mb-1">Gastos del Mes</span>
                            <h3 class="mb-0 fw-black text-dark">{{ $currency }} {{ number_format($stats['month_total'], 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- YTD Expenses -->
        <div class="col-12 col-md-4 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-white">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-dark bg-opacity-10 text-dark rounded-3 p-3 me-3">
                            <i class="align-middle" data-feather="calendar" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <span class="text-muted text-uppercase font-bold small d-block mb-1">Acumulado del Año (YTD)</span>
                            <h3 class="mb-0 fw-black text-dark">{{ $currency }} {{ number_format($stats['ytd_total'], 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Category -->
        <div class="col-12 col-md-4 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-white">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3">
                            <i class="align-middle" data-feather="trending-up" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <span class="text-muted text-uppercase font-bold small d-block mb-1">Mayor Categoría del Mes</span>
                            <h3 class="mb-0 fw-black text-dark">{{ $stats['top_category_name'] }}</h3>
                            <span class="text-muted xsmall font-bold">{{ $currency }} {{ number_format($stats['top_category_amount'], 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-bottom p-3">
            <div class="row g-2 align-items-center">
                <!-- Search -->
                <div class="col-12 col-md-3">
                    <input type="text" wire:model.live="search" class="form-control form-control-sm" placeholder="Buscar por descripción o ref...">
                </div>
                <!-- Category Filter -->
                <div class="col-12 col-md-3">
                    <select wire:model.live="category_filter" class="form-select form-select-sm">
                        <option value="">Todas las Categorías</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Date From -->
                <div class="col-12 col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white">Desde</span>
                        <input type="date" wire:model.live="date_from" class="form-control">
                    </div>
                </div>
                <!-- Date To -->
                <div class="col-12 col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white">Hasta</span>
                        <input type="date" wire:model.live="date_to" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover table-striped my-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Categoría</th>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Método Pago</th>
                        <th>Referencia</th>
                        <th>Soporte</th>
                        <th class="text-end">Monto</th>
                        <th class="pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $exp)
                        <tr wire:key="expense-{{ $exp->id }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xs bg-danger bg-opacity-10 text-danger rounded me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                        <i class="align-middle" data-feather="{{ $exp->category->icon ?? 'tag' }}" style="width: 14px; height: 14px;"></i>
                                    </div>
                                    <span class="fw-bold">{{ $exp->category->name ?? 'Otros' }}</span>
                                </div>
                            </td>
                            <td class="small fw-bold text-muted">{{ $exp->expense_date->format('d/m/Y') }}</td>
                            <td><span class="fw-bold text-dark">{{ $exp->description ?: 'Sin descripción' }}</span></td>
                            <td><span class="badge bg-light text-dark border text-uppercase" style="font-size: 0.65rem;">{{ $exp->payment_method ?: 'S/D' }}</span></td>
                            <td class="small text-muted">{{ $exp->reference_number ?: '-' }}</td>
                            <td>
                                @if($exp->attachment_path)
                                    <a href="{{ $exp->attachment_path }}" target="_blank" class="btn btn-xs btn-light border text-primary" title="Ver Comprobante">
                                        <i class="align-middle" data-feather="file" style="width: 12px; height: 12px;"></i> Adjunto
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-end fw-black text-danger">- {{ $currency }} {{ number_format($exp->amount, 2) }}</td>
                            <td class="pe-4 text-end">
                                <div class="btn-group">
                                    <button wire:click="editExpense({{ $exp->id }})" class="btn btn-sm btn-light border shadow-sm" title="Editar">
                                        <i class="align-middle text-primary" data-feather="edit-2"></i>
                                    </button>
                                    <button onclick="confirm('¿Está seguro de eliminar este egreso?') || event.stopImmediatePropagation()" wire:click="deleteExpense({{ $exp->id }})" class="btn btn-sm btn-light border shadow-sm" title="Eliminar">
                                        <i class="align-middle text-danger" data-feather="trash-2"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="align-middle me-2" data-feather="info"></i> No se encontraron registros de egresos para los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer border-top bg-light">
            {{ $expenses->links() }}
        </div>
    </div>

    <!-- Category Settings Panel (Modal) -->
    @if($managing_categories)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                    <div class="modal-header bg-dark text-white p-4">
                        <h5 class="modal-title uppercase font-black tracking-widest text-white">Gestionar Categorías de Gastos</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="$set('managing_categories', false)"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold uppercase mb-0 small text-muted">Listado de Categorías</h6>
                            <button wire:click="openCategoryCreate" class="btn btn-xs btn-primary fw-bold uppercase">
                                <i class="align-middle me-1" data-feather="plus"></i> AGREGAR
                            </button>
                        </div>

                        <!-- Add/Edit Category Subsection -->
                        @if($creating_or_editing_category)
                            <div class="card border-0 shadow-sm p-3 mb-3 bg-white">
                                <h6 class="fw-black text-uppercase text-muted small mb-2">{{ $category_id ? 'Editar Categoría' : 'Nueva Categoría' }}</h6>
                                <div class="row g-2">
                                    <div class="col-7">
                                        <label class="form-label xsmall text-muted fw-bold uppercase">Nombre</label>
                                        <input type="text" wire:model="category_name" class="form-control form-control-sm border shadow-sm" placeholder="Ej. Combustible" required>
                                    </div>
                                    <div class="col-5">
                                        <label class="form-label xsmall text-muted fw-bold uppercase">Ícono (Feather)</label>
                                        <select wire:model="category_icon" class="form-select form-select-sm border shadow-sm">
                                            <option value="tag">Etiqueta (tag)</option>
                                            <option value="zap">Electricidad (zap)</option>
                                            <option value="droplet">Agua (droplet)</option>
                                            <option value="phone">Teléfono (phone)</option>
                                            <option value="globe">Internet (globe)</option>
                                            <option value="users">Planilla (users)</option>
                                            <option value="home">Alquiler (home)</option>
                                            <option value="package">Aduana (package)</option>
                                            <option value="send">Aerolínea (send)</option>
                                            <option value="truck">Gasolina (truck)</option>
                                            <option value="activity">Otros (activity)</option>
                                            <option value="settings">Ajustes (settings)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-3 d-flex justify-content-end gap-2">
                                    <button type="button" wire:click="$set('creating_or_editing_category', false)" class="btn btn-xs btn-light border">Cancelar</button>
                                    <button type="button" wire:click="saveCategory" class="btn btn-xs btn-primary fw-bold text-uppercase">Guardar</button>
                                </div>
                            </div>
                        @endif

                        <div class="list-group shadow-sm overflow-auto style-scrollbar" style="max-height: 250px;">
                            @foreach($categories as $cat)
                                <div class="list-group-item d-flex justify-content-between align-items-center py-2 bg-white" wire:key="cat-item-{{ $cat->id }}">
                                    <div class="d-flex align-items-center">
                                        <i class="align-middle text-muted me-2" data-feather="{{ $cat->icon ?? 'tag' }}" style="width: 14px;"></i>
                                        <span class="fw-bold text-dark">{{ $cat->name }}</span>
                                    </div>
                                    <div class="btn-group">
                                        <button wire:click="editCategory({{ $cat->id }})" class="btn btn-xs btn-light border py-0 px-2" title="Editar">
                                            <i class="align-middle text-primary" data-feather="edit-2" style="width: 12px;"></i>
                                        </button>
                                        <button onclick="confirm('¿Desea eliminar esta categoría?') || event.stopImmediatePropagation()" wire:click="deleteCategory({{ $cat->id }})" class="btn btn-xs btn-light border py-0 px-2" title="Eliminar">
                                            <i class="align-middle text-danger" data-feather="trash-2" style="width: 12px;"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-top-0 p-4 pt-0 d-flex justify-content-end">
                        <button type="button" class="btn btn-dark fw-bold px-4" wire:click="$set('managing_categories', false)">CERRAR</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Expense Modal (Create/Edit) -->
    @if($creating_or_editing)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                    <div class="modal-header bg-danger text-white p-4">
                        <h5 class="modal-title uppercase font-black tracking-widest text-white">{{ $expense_id ? 'Editar Egreso/Gasto' : 'Registrar Nuevo Egreso/Gasto' }}</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="$set('creating_or_editing', false)"></button>
                    </div>
                    <form wire:submit="saveExpense">
                        <div class="modal-body p-4 bg-light">
                            <div class="row g-3">
                                <!-- Category -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-black uppercase text-muted">Categoría del Gasto</label>
                                    <select wire:model="expense_category_id" class="form-select border-0 shadow-sm" required>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('expense_category_id') <span class="text-danger xsmall">{{ $message }}</span> @enderror
                                </div>

                                <!-- Date -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-black uppercase text-muted">Fecha del Gasto</label>
                                    <input type="date" wire:model="expense_date" class="form-control border-0 shadow-sm" required>
                                    @error('expense_date') <span class="text-danger xsmall">{{ $message }}</span> @enderror
                                </div>

                                <!-- Amount -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-black uppercase text-muted">Monto ({{ $currency }})</label>
                                    <input type="number" step="0.01" wire:model="amount" class="form-control border-0 shadow-sm font-bold" placeholder="0.00" required>
                                    @error('amount') <span class="text-danger xsmall">{{ $message }}</span> @enderror
                                </div>

                                <!-- Payment Method -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-black uppercase text-muted">Método de Pago</label>
                                    <select wire:model="payment_method" class="form-select border-0 shadow-sm">
                                        <option value="transferencia">Transferencia ACH / Banco</option>
                                        <option value="efectivo">Efectivo / Caja Chica</option>
                                        <option value="tarjeta">Tarjeta de Crédito</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="yappy">Yappy / Pago Móvil</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                    @error('payment_method') <span class="text-danger xsmall">{{ $message }}</span> @enderror
                                </div>

                                <!-- Reference -->
                                <div class="col-12">
                                    <label class="form-label small fw-black uppercase text-muted">Número de Referencia / Factura</label>
                                    <input type="text" wire:model="reference_number" class="form-control border-0 shadow-sm" placeholder="Ej. Ref-908123 (opcional)">
                                    @error('reference_number') <span class="text-danger xsmall">{{ $message }}</span> @enderror
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label class="form-label small fw-black uppercase text-muted">Detalle / Concepto</label>
                                    <textarea wire:model="description" class="form-control border-0 shadow-sm" rows="3" placeholder="Ej. Pago de la factura de electricidad del mes de Junio (opcional)"></textarea>
                                    @error('description') <span class="text-danger xsmall">{{ $message }}</span> @enderror
                                </div>

                                <!-- File Upload -->
                                <div class="col-12">
                                    <label class="form-label small fw-black uppercase text-muted">Adjuntar Comprobante (PDF, JPG, PNG)</label>
                                    <input type="file" wire:model="attachment" class="form-control border-0 shadow-sm bg-white">
                                    <div class="form-text xsmall">Máximo 4MB de tamaño.</div>
                                    @error('attachment') <span class="text-danger xsmall">{{ $message }}</span> @enderror

                                    @if ($current_attachment_path)
                                        <div class="mt-2">
                                            <span class="xsmall text-muted">Archivo actual:</span>
                                            <a href="{{ $current_attachment_path }}" target="_blank" class="badge bg-light border text-dark text-decoration-none">
                                                Ver adjunto existente
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-white border-top-0 p-4 pt-0 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light fw-bold" wire:click="$set('creating_or_editing', false)">CANCELAR</button>
                            <button type="submit" class="btn btn-danger px-4 shadow-lg fw-black">GUARDAR GASTO</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
