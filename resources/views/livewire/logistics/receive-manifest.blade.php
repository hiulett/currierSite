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
                <h5 class="card-title mb-0 uppercase font-black small">Historial de Manifiestos</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nº Manifiesto</th>
                            <th>Factura Ref.</th>
                            <th>Estado</th>
                            <th>Responsable</th>
                            <th>Ítems (Rec/Exp)</th>
                            <th>Fecha</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($manifests as $manifest)
                            <tr>
                                <td class="fw-black text-dark">{{ $manifest->number }}</td>
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
                                <td class="text-end">
                                    <button wire:click="selectManifest({{ $manifest->id }})" class="btn btn-sm btn-light border">
                                        <i class="align-middle me-1" data-feather="{{ $manifest->status === 'processing' ? 'play' : 'eye' }}"></i>
                                        {{ $manifest->status === 'processing' ? 'Continuar' : 'Detalles' }}
                                    </button>
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

    <!-- VIEW: CREATE MANIFEST -->
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
                                    <th class="ps-4">Tracking</th>
                                    <th>Estado</th>
                                    <th class="text-end pe-4">Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr class="{{ $item->status === 'received' ? 'table-success' : ($item->status === 'surplus' ? 'table-danger' : '') }}">
                                        <td class="ps-4">
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

                        <div class="mt-4 pt-3 border-top text-center">
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
                            <button class="btn btn-xs btn-outline-secondary active">Todos</button>
                            <button class="btn btn-xs btn-outline-warning">Faltantes</button>
                            <button class="btn btn-xs btn-outline-danger">Sobrantes</button>
                        </div>
                    </div>
                    <div class="table-responsive" style="max-height: 700px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light sticky-top">
                                <tr>
                                    <th class="ps-4">Tracking</th>
                                    <th>Estado</th>
                                    <th>Paquete en Sistema</th>
                                    <th class="text-end pe-4">Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark">{{ $item->tracking_number }}</td>
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
                                                <span class="small text-primary fw-bold">PTY-{{ $item->package->customer->box_number }}</span>
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
            Livewire.hook('morph.updated', ({ el, component }) => {
                const scannerField = document.getElementById('scanner_field');
                if (scannerField) {
                    scannerField.focus();
                }
            });
        });
    </script>
</div>
