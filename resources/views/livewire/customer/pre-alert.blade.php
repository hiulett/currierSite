<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="h3 mb-0 uppercase font-black tracking-tight text-dark">Pre-alertar Paquete</h2>
            <p class="text-muted small">Avísanos sobre tu compra para procesarla de inmediato al recibirla en bodega.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Registro de Pre-alerta</h5>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="row">
                            <!-- Tracking Number -->
                            <div class="mb-3 col-12">
                                <label class="form-label" for="tracking_number">Número de Tracking</label>
                                <input type="text" wire:model="tracking_number" id="tracking_number" placeholder="Ej: 1Z9999999999999999"
                                       class="form-control @error('tracking_number') is-invalid @enderror">
                                @error('tracking_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Warehouse -->
                            <div class="mb-3 col-md-7">
                                <label class="form-label" for="warehouse_id">Bodega de Destino</label>
                                <select wire:model="warehouse_id" id="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror">
                                    <option value="">Seleccione bodega...</option>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}">{{ $wh->name }} ({{ $wh->code }})</option>
                                    @endforeach
                                </select>
                                @error('warehouse_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Declared Value -->
                            <div class="mb-3 col-md-5">
                                <label class="form-label" for="declared_value">Valor Declarado (USD)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" wire:model="declared_value" id="declared_value" placeholder="0.00"
                                           class="form-control @error('declared_value') is-invalid @enderror">
                                </div>
                                @error('declared_value') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3 col-12">
                                <label class="form-label" for="description">Contenido / Descripción</label>
                                <textarea wire:model="description" id="description" rows="2" placeholder="Describe brevemente lo que compraste..."
                                          class="form-control @error('description') is-invalid @enderror"></textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Invoice File -->
                            <div class="mb-3 col-12">
                                <label class="form-label">Adjuntar Factura Comercial</label>
                                <div class="p-4 border rounded text-center position-relative mb-2 bg-light">
                                    <input type="file" wire:model="invoice_file" class="position-absolute start-0 top-0 w-100 h-100 opacity-0 cursor-pointer" style="z-index: 10;">
                                    <div class="py-2">
                                        <i data-feather="upload-cloud" class="text-muted mb-2" style="width: 32px; height: 32px;"></i>
                                        <h6 class="mb-1">
                                            @if($invoice_file)
                                                <span class="text-success">✅ {{ $invoice_file->getClientOriginalName() }}</span>
                                            @else
                                                Haz clic o arrastra tu PDF/Imagen aquí
                                            @endif
                                        </h6>
                                        <p class="text-muted small mb-0">PDF, JPG o PNG (Máx 4MB)</p>
                                    </div>
                                </div>

                                @if($invoice_file && !$is_scanning)
                                    <button type="button" wire:click="scanInvoice" class="btn btn-outline-dark btn-sm">
                                        <i class="align-middle me-1" data-feather="zap" style="width: 12px;"></i> Magic Scan IA
                                    </button>
                                @endif

                                @if($is_scanning)
                                    <div class="d-flex align-items-center text-primary mt-2">
                                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                        <span class="small fw-bold">Procesando con IA...</span>
                                    </div>
                                @endif
                                @error('invoice_file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-light">
                                <i class="align-middle me-1" data-feather="arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                Confirmar Pre-alerta <i class="align-middle ms-2" data-feather="check-circle"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar Tips -->
            <div class="card bg-light border-0">
                <div class="card-body">
                    <h5 class="card-title small mb-3 uppercase font-bold text-muted">Información Importante</h5>
                    <div class="d-flex align-items-start">
                        <i class="text-primary me-3 mt-1" data-feather="info" style="width: 16px;"></i>
                        <p class="small mb-0 text-muted">Al pre-alertar, nuestro equipo en Miami identificará tu paquete mucho más rápido, incluso si la etiqueta de la tienda está dañada o incompleta.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
