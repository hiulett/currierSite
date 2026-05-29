<div class="container-fluid p-0">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1 class="h3 mb-3">⚡ Smart Reception Hub</h1>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group shadow-sm">
                <button wire:click="$set('mode', 'manual')" class="btn btn-{{ $mode == 'manual' ? 'primary' : 'white border' }} fw-bold">
                    <i class="align-middle me-2" data-feather="edit"></i> Recepción Manual
                </button>
                <button wire:click="$set('mode', 'ocr')" class="btn btn-{{ $mode == 'ocr' ? 'primary' : 'white border' }} fw-bold">
                    <i class="align-middle me-2" data-feather="cpu"></i> Registrar Factura (OCR)
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Columna de Acción (Izquierda) -->
        <div class="col-xl-5 col-xxl-4">
            @if($mode == 'manual')
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0 uppercase font-black small tracking-widest text-primary">Ingreso Individual</h5>
                    </div>
                    <div class="card-body">
                        @if (session()->has('message'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <div class="alert-message">{{ session('message') }}</div>
                            </div>
                        @endif

                        <form wire:submit.prevent="saveManual">
                            <div class="mb-3">
                                <label class="form-label small font-bold text-uppercase">Nº de Tracking</label>
                                <input type="text" wire:model="tracking_number" class="form-control form-control-lg fw-black text-primary border-2 @error('tracking_number') is-invalid @enderror" placeholder="Escanee el paquete..." autofocus>
                                @error('tracking_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3 position-relative">
                                <label class="form-label small font-bold text-uppercase">Cliente</label>
                                <input type="text" wire:model.live="customer_search" class="form-control @error('box_number') is-invalid @enderror" placeholder="Buscar por Nombre o Box...">
                                @error('box_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                @if(!empty($search_results))
                                    <div class="list-group position-absolute w-100 shadow-lg z-index-1000">
                                        @foreach($search_results as $result)
                                            <button type="button" wire:click="selectCustomer({{ $result->id }})" class="list-group-item list-group-item-action py-2">
                                                <strong>{{ $result->box_number }}</strong> - {{ $result->user->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small font-bold text-uppercase">Peso (lb)</label>
                                    <input type="number" step="0.01" wire:model="weight" class="form-control fw-bold border-2 @error('weight') is-invalid @enderror">
                                    @error('weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-6">
                                    <label class="form-label small font-bold text-uppercase">Bodega</label>
                                    <select wire:model="warehouse_id" class="form-select fw-bold @error('warehouse_id') is-invalid @enderror">
                                        @foreach($warehouses as $wh)
                                            <option value="{{ $wh->id }}">{{ $wh->code }}</option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit"
                                        wire:loading.attr="disabled"
                                        class="btn btn-primary btn-lg fw-black shadow-lg">
                                    <span wire:loading.remove wire:target="saveManual">REGISTRAR PAQUETE</span>
                                    <span wire:loading wire:target="saveManual">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span> PROCESANDO...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 uppercase font-black small tracking-widest">Actividad Reciente</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <tbody>
                                @foreach($lastPackages as $lp)
                                    <tr>
                                        <td class="ps-3 py-2">
                                            <div class="fw-black text-dark fs-6">{{ $lp->tracking_number }}</div>
                                            <div class="xsmall text-muted">
                                                @if($lp->customer)
                                                    {{ $lp->customer->box_number }}
                                                @else
                                                    <span class="text-warning fw-bold">SIN ASIGNAR</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-end pe-3 py-2">
                                            <span class="badge bg-light text-dark border xsmall">{{ $lp->weight }} lb</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna OCR (Derecha) -->
        <div class="col-xl-7 col-xxl-8">
            @if($mode == 'ocr')
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <div class="row align-items-center g-3">
                            <div class="col-md-4">
                                <h5 class="card-title mb-0 uppercase font-black small tracking-widest text-primary">Carga Esperada de Factura</h5>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0 fw-bold small text-uppercase">Nº Factura</span>
                                    <input type="text" wire:model="invoiceNumber" class="form-control border-start-0 fw-black text-primary" placeholder="Ej: 1922984">
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                @if(!empty($ocrResults))
                                    <button wire:click="saveAllOCRItems"
                                            wire:loading.attr="disabled"
                                            class="btn btn-xs btn-success fw-black">
                                        <span wire:loading.remove wire:target="saveAllOCRItems">
                                            <i data-feather="check-square" style="width: 12px;" class="me-1"></i> CONFIRMAR LOTE
                                        </span>
                                        <span wire:loading wire:target="saveAllOCRItems">
                                            <span class="spinner-border spinner-border-sm me-1" role="status"></span> GENERANDO...
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session()->has('message'))
                            <div class="alert alert-success border-0 shadow-sm mb-4">
                                <i data-feather="check-circle" class="me-2 text-success"></i> {{ session('message') }}
                            </div>
                        @endif

                        @if(empty($ocrResults))
                            <div class="mb-4 text-center p-5 border-dashed border-2 rounded">
                                <input type="file" wire:model="invoiceFile" id="invoiceInput" class="d-none" accept=".pdf,.jpg,.png">
                                <label for="invoiceInput" style="cursor: pointer;" wire:loading.remove wire:target="invoiceFile">
                                    <i data-feather="cpu" class="text-primary mb-3" style="width: 50px; height: 50px;"></i>
                                    <h4 class="fw-black uppercase">Subir Factura de Global Express</h4>
                                    <p class="text-muted">Extraeremos trackings, pesos y dimensiones para crear un lote esperado.</p>
                                </label>
                                <div wire:loading wire:target="invoiceFile" class="text-center py-4">
                                    <div class="spinner-border text-primary mb-3" role="status"></div>
                                    <h4 class="fw-black uppercase">Analizando Factura...</h4>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr class="xsmall font-black uppercase text-muted">
                                            <th class="ps-3">Tracking / ID</th>
                                            <th class="text-center">Peso</th>
                                            <th class="text-center">Dimensiones</th>
                                            <th class="text-center">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ocrResults as $item)
                                            <tr>
                                                <td class="ps-3">
                                                    <div class="fw-black text-dark fs-5">{{ $item['tracking'] }}</div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="fw-bold text-dark">{{ $item['weight'] }} lb</div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="xsmall text-muted">{{ $item['length'] }} x {{ $item['height'] }} x {{ $item['width'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark border">ESPERADO</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm bg-primary text-white overflow-hidden" style="min-height: 400px;">
                    <div class="card-body d-flex flex-column justify-content-center text-center p-5">
                        <div class="mb-4">
                            <i data-feather="zap" style="width: 80px; height: 80px; opacity: 0.2;"></i>
                        </div>
                        <h2 class="fw-black uppercase tracking-tighter mb-3">HUB DE RECEPCIÓN 360º</h2>
                        <p class="opacity-75 lead mb-4">Selecciona un modo arriba para comenzar el procesamiento de carga.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        feather.replace();
        Livewire.on('package-saved', () => {
            setTimeout(() => { feather.replace(); }, 100);
        });
    });
</script>

<style>
    .z-index-1000 { z-index: 1000; }
</style>
