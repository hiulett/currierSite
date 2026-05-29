<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-0 uppercase font-black tracking-tight text-dark">Recepción por Manifiesto</h1>
            <p class="text-muted small">Concilia la factura del transportista con la carga física recibida.</p>
        </div>
        <div class="col-md-6 text-md-end">
            @if($view_mode !== 'list')
                <button wire:click="$set('view_mode', 'list')" class="btn btn-outline-dark">
                    <i class="align-middle me-1" data-feather="arrow-left"></i> Volver al Listado
                </button>
            @else
                <button wire:click="$set('view_mode', 'create')" class="btn btn-primary shadow-sm fw-black">
                    <i class="align-middle me-1" data-feather="plus"></i> NUEVO MANIFIESTO
                </button>
            @endif
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message"><strong>¡Éxito!</strong> {{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- VIEW: LIST OF MANIFESTS -->
    @if($view_mode === 'list')
        <div class="card shadow-sm">
            <div class="card-header bg-light border-bottom">
                <div class="row align-items-center g-3">
                    <div class="col-md-4">
                        <h5 class="card-title mb-0 uppercase font-black small">Historial de Manifiestos</h5>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i data-feather="search" style="width: 14px;"></i></span>
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0" placeholder="Buscar manifiesto o factura...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select wire:model.live="status_filter" class="form-select form-select-sm">
                            <option value="">Todos los estados</option>
                            <option value="pending">Pendiente</option>
                            <option value="processing">En Recepción</option>
                            <option value="reconciled">Conciliado</option>
                            <option value="closed">Cerrado</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4 text-muted small">#</th>
                            <th>Nº Manifiesto</th>
                            <th>Factura Ref.</th>
                            <th>Estado</th>
                            <th>Responsable</th>
                            <th>Ítems (Rec/Exp)</th>
                            <th>Fecha</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($manifests as $index => $manifest)
                            <tr wire:key="manifest-row-{{ $manifest->id }}">
                                <td class="ps-4 text-muted small">{{ ($manifests->currentPage() - 1) * $manifests->perPage() + $loop->iteration }}</td>
                                <td>
                                    <div class="fw-black text-dark">{{ $manifest->number }}</div>
                                    @if($manifest->carrier_name) <div class="xsmall text-muted">{{ $manifest->carrier_name }}</div> @endif
                                </td>
                                <td>{{ $manifest->carrier_invoice_number }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($manifest->status) {
                                            'pending' => 'bg-secondary',
                                            'processing' => 'bg-primary',
                                            'reconciled' => 'bg-success',
                                            'closed' => 'bg-dark',
                                            default => 'bg-light text-dark'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} text-uppercase">{{ $manifest->status }}</span>
                                </td>
                                <td>
                                    <div class="small fw-bold">{{ $manifest->creator->name ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $manifest->total_items_received }} / {{ $manifest->total_items_expected }}</div>
                                    <div class="progress mt-1" style="height: 4px; width: 80px;">
                                        @php
                                            $progress = $manifest->total_items_expected > 0 ? ($manifest->total_items_received / $manifest->total_items_expected * 100) : 0;
                                        @endphp
                                        <div class="progress-bar {{ $progress >= 100 ? 'bg-success' : 'bg-primary' }}" role="progressbar" style="width: {{ $progress }}%"></div>
                                    </div>
                                </td>
                                <td class="small">{{ $manifest->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <button wire:click="selectManifest({{ $manifest->id }})" class="btn btn-sm btn-light border" title="Escanear">
                                            <i data-feather="{{ $manifest->status === 'processing' || $manifest->status === 'pending' ? 'play' : 'eye' }}" style="width: 14px;"></i>
                                        </button>
                                        <button wire:click="openEditModal({{ $manifest->id }})" class="btn btn-sm btn-light border" title="Editar">
                                            <i data-feather="edit-2" style="width: 14px;"></i>
                                        </button>
                                        <button wire:click="deleteManifest({{ $manifest->id }})" wire:confirm="¿Seguro que quieres eliminar este manifiesto?" class="btn btn-sm btn-light border text-danger" title="Eliminar">
                                            <i data-feather="trash-2" style="width: 14px;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">No hay manifiestos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-top">
                {{ $manifests->links() }}
            </div>
        </div>
    @endif

    <!-- CRUD MODAL (UNIFICADO) -->
    @if($isEditModalOpen)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-dark text-white py-3">
                        <h5 class="modal-title text-white uppercase font-black small">Editar Encabezado de Manifiesto</h5>
                        <button type="button" wire:click="closeEditModal" class="btn-close btn-close-white"></button>
                    </div>
                    <form wire:submit.prevent="saveManifestHeader">
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label small font-bold text-uppercase">Nº Manifiesto</label>
                                <input type="text" wire:model="number" class="form-control fw-bold">
                                @error('number') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small font-bold text-uppercase">Transportista</label>
                                <input type="text" wire:model="carrier_name" class="form-control" placeholder="Ej: Global Express">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small font-bold text-uppercase">Factura Ref.</label>
                                <input type="text" wire:model="carrier_invoice" class="form-control">
                                @error('carrier_invoice') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small font-bold text-uppercase">Estado</label>
                                <select wire:model="status" class="form-select">
                                    <option value="pending">Pendiente</option>
                                    <option value="processing">En Recepción</option>
                                    <option value="reconciled">Conciliado</option>
                                    <option value="closed">Cerrado</option>
                                </select>
                            </div>

                            <div class="mb-0">
                                <label class="form-label small font-bold text-uppercase">Descripción / Notas</label>
                                <textarea wire:model="description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-light p-3">
                            <button type="button" wire:click="closeEditModal" class="btn btn-white border uppercase font-bold small">Cancelar</button>
                            <button type="submit" class="btn btn-dark fw-black uppercase small px-4 shadow-sm">
                                GUARDAR CAMBIOS
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- VIEW: REVIEW EXTRACTED TRACKINGS -->
    @if($view_mode === 'review')
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-warning text-dark py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 uppercase font-black small"><i class="align-middle me-1" data-feather="eye"></i> Revisión de Datos Extraídos</h5>
                        <span class="badge bg-dark">{{ count($extracted_trackings) }} ÍTEMS</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4 p-3 bg-light rounded border">
                            <label class="form-label font-bold small text-uppercase text-muted">Confirmar Nº Factura</label>
                            <input type="text" wire:model="carrier_invoice" class="form-control form-control-lg fw-black">
                        </div>

                        <div class="mb-3 d-flex gap-2">
                            <input type="text" wire:model="new_tracking" wire:keydown.enter="addManualTracking" class="form-control" placeholder="Agregar tracking manualmente...">
                            <button wire:click="addManualTracking" class="btn btn-dark fw-bold">AGREGAR</button>
                        </div>

                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-sm table-hover border align-middle">
                                <thead class="bg-light sticky-top">
                                    <tr>
                                        <th class="ps-3">#</th>
                                        <th>Tracking ID</th>
                                        <th class="text-center">Peso (lb)</th>
                                        <th class="text-center">Dims (LxHxA)</th>
                                        <th class="text-end pe-3">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($extracted_trackings as $index => $item)
                                        <tr>
                                            <td class="ps-3 text-muted small">{{ $index + 1 }}</td>
                                            <td>
                                                @if($editing_index === $index)
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" wire:model="editing_value" class="form-control fw-bold" autofocus>
                                                        <button wire:click="saveEdit" class="btn btn-success"><i data-feather="check" style="width: 12px;"></i></button>
                                                    </div>
                                                @else
                                                    <span class="fw-bold">{{ $item['tracking'] }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info-light text-info fw-black">{{ $item['weight'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="small text-muted">{{ $item['length'] }} x {{ $item['height'] }} x {{ $item['width'] }}</span>
                                            </td>
                                            <td class="text-end pe-3">
                                                <button wire:click="editTracking({{ $index }})" class="btn btn-sm btn-link text-primary p-0 me-2"><i data-feather="edit-2" style="width: 14px;"></i></button>
                                                <button wire:click="removeTracking({{ $index }})" class="btn btn-sm btn-link text-danger p-0"><i data-feather="trash-2" style="width: 14px;"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button wire:click="confirmReview"
                                    wire:loading.attr="disabled"
                                    class="btn btn-lg btn-success fw-black text-uppercase py-3 shadow d-flex align-items-center justify-content-center">
                                <span wire:loading.remove wire:target="confirmReview">
                                    APROBAR TODOS Y EMPEZAR ESCANEO <i class="align-middle ms-2" data-feather="play"></i>
                                </span>
                                <span wire:loading wire:target="confirmReview">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    PROCESANDO LOTE...
                                </span>
                            </button>
                            <button wire:click="$set('view_mode', 'create')" class="btn btn-light border text-uppercase small">DESCARTAR Y VOLVER</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if($view_mode === 'create')
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="card-title text-white mb-0 uppercase font-black small">Registrar Manifiesto de Factura</h5>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form wire:submit.prevent="createManifest">
                            <div class="mb-4">
                                <label class="form-label font-bold small text-uppercase text-muted">Subir Factura PDF (Opcional - OCR Automático)</label>
                                <div class="input-group">
                                    <input type="file" wire:model="manifest_file" class="form-control" id="manifestFile">
                                    <label class="input-group-text" for="manifestFile"><i data-feather="upload"></i></label>
                                </div>
                                <div wire:loading wire:target="manifest_file" class="text-primary small mt-1">
                                    <span class="spinner-border spinner-border-sm" role="status"></span> Procesando PDF con OCR local...
                                </div>
                                @if (session()->has('ocr_message')) <div class="text-success small mt-1 fw-bold">{{ session('ocr_message') }}</div> @endif
                                @if (session()->has('ocr_error')) <div class="text-danger small mt-1 fw-bold">{{ session('ocr_error') }}</div> @endif
                                @error('manifest_file') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label font-bold small text-uppercase text-muted">Nº de Factura del Transportista / Aduana</label>
                                <input type="text" wire:model="carrier_invoice" class="form-control form-control-lg fw-bold" placeholder="Ej: INV-99887766">
                                @error('carrier_invoice') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label font-bold small text-uppercase text-muted">Trackings de la Factura (Pega la lista aquí)</label>
                                <textarea wire:model="tracking_input" rows="8" class="form-control font-monospace" placeholder="Pega los trackings separados por comas, espacios o líneas..."></textarea>
                                <div class="form-text">El sistema detectará automáticamente cada número de seguimiento individual.</div>
                                @error('tracking_input') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit" class="btn btn-lg btn-primary w-100 fw-black text-uppercase py-3">
                                INICIAR CONCILIACIÓN <i class="align-middle ms-2" data-feather="play"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- VIEW: SCANNING / RECONCILIATION -->
    @if($view_mode === 'scanning' && $activeManifest)
        <div class="row">
            <div class="col-lg-5">
                <!-- Warehouse Selector for this session -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-body p-3">
                        <label class="form-label small font-black text-uppercase text-muted mb-2">Bodega de Recepción</label>
                        <select wire:model.live="warehouse_id" class="form-select fw-bold border-2">
                            @foreach(\App\Models\Warehouse::all() as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->code }} - {{ $wh->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-muted xsmall mt-2 mb-0 italic">Los paquetes no registrados se crearán automáticamente en esta bodega.</p>
                    </div>
                </div>

                <div class="card shadow-lg border-0 bg-dark text-white mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title text-white opacity-50 small mb-3 uppercase tracking-widest">Escáner de Recepción</h5>
                        <form wire:submit.prevent="processScan">
                            <input type="text" wire:model="scanner_input"
                                   id="scanner_field"
                                   autofocus
                                   class="form-control form-control-lg bg-white bg-opacity-10 border-0 text-white fw-black text-center py-4 mb-3"
                                   style="font-size: 1.5rem;"
                                   placeholder="ESCANEE TRACKING AHORA">
                            <p class="text-center xsmall opacity-50 mb-0 italic">Mantén el cursor en este campo para escaneos rápidos.</p>
                        </form>
                    </div>
                </div>

                @if (session()->has('scan_message'))
                    <div class="alert alert-success border-0 shadow-sm animate__animated animate__pulse">
                        <i class="align-middle me-2" data-feather="check"></i> {{ session('scan_message') }}
                    </div>
                @endif
                @if (session()->has('scan_warning'))
                    <div class="alert alert-warning border-0 shadow-sm">
                        <i class="align-middle me-2" data-feather="alert-triangle"></i> {{ session('scan_warning') }}
                    </div>
                @endif
                @if (session()->has('scan_error'))
                    <div class="alert alert-danger border-0 shadow-sm animate__animated animate__shakeX">
                        <i class="align-middle me-2" data-feather="x-circle"></i> {{ session('scan_error') }}
                    </div>
                @endif

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-bottom d-flex justify-content-between">
                        <h5 class="card-title mb-0 uppercase font-black small">Resumen del Proceso</h5>
                        <span class="badge bg-primary">{{ $activeManifest->status }}</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row text-center">
                            <div class="col-4 border-end">
                                <h2 class="fw-black mb-0">{{ $activeManifest->total_items_expected }}</h2>
                                <p class="text-muted xsmall uppercase font-bold mb-0">Facturados</p>
                            </div>
                            <div class="col-4 border-end">
                                <h2 class="fw-black text-success mb-0">{{ $activeManifest->total_items_received }}</h2>
                                <p class="text-muted xsmall uppercase font-bold mb-0">Recibidos</p>
                            </div>
                            <div class="col-4">
                                <h2 class="fw-black text-danger mb-0">{{ $items->where('status', 'surplus')->count() }}</h2>
                                <p class="text-muted xsmall uppercase font-bold mb-0">Sobrantes</p>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button wire:confirm="¿Estás seguro de cerrar el manifiesto? Los ítems no recibidos se marcarán como faltantes."
                                    wire:click="closeManifest"
                                    class="btn btn-dark fw-black uppercase py-3">
                                CERRAR Y CONCILIAR <i class="align-middle ms-2" data-feather="check-square"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 uppercase font-black small">Detalle de Comparación</h5>
                    </div>
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table class="table table-hover table-striped align-middle mb-0">
                            <thead class="bg-light sticky-top">
                                <tr>
                                    <th class="ps-4 text-muted small">#</th>
                                    <th>Tracking</th>
                                    <th>Estado</th>
                                    <th class="text-end pe-4">Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr class="{{ $item->status === 'received' ? 'table-success' : ($item->status === 'surplus' ? 'table-danger' : '') }}">
                                        <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $item->tracking_number }}</div>
                                            @if($item->observation)
                                                <div class="xsmall text-danger italic">{{ $item->observation }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match($item->status) {
                                                    'received' => 'badge bg-success',
                                                    'surplus' => 'badge bg-danger',
                                                    'expected' => 'badge bg-secondary opacity-50',
                                                    'missing' => 'badge bg-warning',
                                                    default => 'badge bg-light text-dark'
                                                };
                                            @endphp
                                            <span class="{{ $statusClass }} text-uppercase" style="font-size: 0.6rem;">
                                                {{ $item->status === 'received' ? 'MATCH' : ($item->status === 'surplus' ? 'SOBRANTE' : $item->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4 small">
                                            {{ $item->scanned_at ? $item->scanned_at->format('H:i:s') : '--:--' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- VIEW: DETAIL / REPORT -->
    @if($view_mode === 'detail' && $activeManifest)
        <div class="row">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white py-3">
                        <h5 class="card-title text-white mb-0 uppercase font-black small">Información del Manifiesto</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="text-muted xsmall uppercase font-bold d-block">Nº Manifiesto</label>
                            <div class="fw-black h5 text-dark">{{ $activeManifest->number }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted xsmall uppercase font-bold d-block">Factura Transportista</label>
                            <div class="fw-bold text-dark">{{ $activeManifest->carrier_invoice_number }}</div>
                        </div>
                        <div class="mb-3 border-top pt-3">
                            <label class="text-muted xsmall uppercase font-bold d-block">Creado Por</label>
                            <div class="small fw-bold">{{ $activeManifest->creator->name ?? 'Sistema' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted xsmall uppercase font-bold d-block">Fecha Proceso</label>
                            <div class="small fw-bold">{{ $activeManifest->created_at->format('d M, Y H:i') }}</div>
                        </div>
                        <div class="mb-0 border-top pt-3">
                            <label class="text-muted xsmall uppercase font-bold d-block">Estado Final</label>
                            <span class="badge bg-success text-uppercase">{{ $activeManifest->status }}</span>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="card-title uppercase font-black small mb-4">Métricas de Conciliación</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Paquetes Facturados:</span>
                            <span class="fw-black text-dark">{{ $activeManifest->total_items_expected }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted text-success font-bold">Recibidos Correctos:</span>
                            <span class="fw-black text-success">{{ $activeManifest->total_items_received }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted text-warning font-bold">Faltantes (Shortage):</span>
                            <span class="fw-black text-warning">{{ $items->where('status', 'missing')->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-0">
                            <span class="text-muted text-danger font-bold">Sobrantes (Surplus):</span>
                            <span class="fw-black text-danger">{{ $items->where('status', 'surplus')->count() }}</span>
                        </div>

                        <div class="mt-4 pt-3 border-top text-center gap-2 d-flex flex-column">
                            <button wire:click="reopenManifest" class="btn btn-warning btn-sm w-100 fw-black uppercase">
                                <i class="align-middle me-1" data-feather="edit"></i> REABRIR PARA EDICIÓN
                            </button>
                            <button onclick="window.print()" class="btn btn-outline-dark btn-sm w-100">
                                <i class="align-middle me-1" data-feather="printer"></i> IMPRIMIR REPORTE
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                        <h5 class="card-title mb-0 uppercase font-black small">Resultados de la Carga</h5>
                        <div class="btn-group">
                            <button wire:click="$set('item_status_filter', 'all')" class="btn btn-xs {{ $item_status_filter === 'all' ? 'btn-secondary active' : 'btn-outline-secondary' }}">Todos</button>
                            <button wire:click="$set('item_status_filter', 'missing')" class="btn btn-xs {{ $item_status_filter === 'missing' ? 'btn-warning active' : 'btn-outline-warning' }}">Faltantes</button>
                            <button wire:click="$set('item_status_filter', 'surplus')" class="btn btn-xs {{ $item_status_filter === 'surplus' ? 'btn-danger active' : 'btn-outline-danger' }}">Sobrantes</button>
                        </div>
                    </div>
                    <div class="table-responsive" style="max-height: 700px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light sticky-top">
                                <tr>
                                    <th class="ps-4 text-muted small">#</th>
                                    <th>Tracking</th>
                                    <th>Estado</th>
                                    <th>Paquete en Sistema</th>
                                    <th class="text-end pe-4">Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                        <td class="fw-bold text-dark">{{ $item->tracking_number }}</td>
                                        <td>
                                            @php
                                                $sClass = match($item->status) {
                                                    'received' => 'badge bg-success',
                                                    'surplus' => 'badge bg-danger',
                                                    'missing' => 'badge bg-warning',
                                                    default => 'badge bg-secondary'
                                                };
                                            @endphp
                                            <span class="{{ $sClass }} text-uppercase" style="font-size: 0.6rem;">{{ $item->status }}</span>
                                        </td>
                                        <td>
                                            @if($item->package)
                                                @if($item->package->customer)
                                                    <span class="small text-primary fw-bold">{{ $item->package->customer->box_number }}</span>
                                                @else
                                                    <span class="badge bg-warning-light text-warning xsmall fw-bold">SIN DUEÑO</span>
                                                @endif
                                            @else
                                                <span class="text-muted small">No vinculado</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4 small text-muted italic">
                                            {{ $item->observation ?? 'Sin novedad' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:initialized', () => {
            // Usando sonidos desde CDN gratuito para asegurar que funcionen inmediatamente
            const successSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2568/2568-preview.mp3');
            const warningSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2571/2571-preview.mp3');
            const errorSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2573/2573-preview.mp3');

            Livewire.on('play-sound', (data) => {
                const type = data.type;
                let player;

                if (type === 'success') player = successSound;
                if (type === 'warning') player = warningSound;
                if (type === 'error') player = errorSound;

                if (player) {
                    player.currentTime = 0; // Reiniciar para permitir sonidos rápidos seguidos
                    player.play().catch(e => {
                        console.warn('El sonido no pudo reproducirse. Asegúrate de interactuar con la página primero (Políticas de navegador).');
                    });
                }
            });

            Livewire.hook('morph.updated', ({ el, component }) => {
                const scannerField = document.getElementById('scanner_field');
                if (scannerField) {
                    scannerField.focus();
                }
            });
        });
    </script>
</div>
