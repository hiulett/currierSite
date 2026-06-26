<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="h3 mb-0 uppercase font-black tracking-tight text-dark">Calculadora de Fletes</h2>
            <p class="text-muted small">Estima el costo de tu envío incluyendo fletes, aranceles y manejos.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="row g-4">
                <!-- Input Section -->
                <div class="col-12 col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Datos del Paquete</h5>
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="calculate">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Servicio</label>
                                    <div class="d-flex gap-3">
                                        @if($airEnabled)
                                            <div class="flex-fill">
                                                <input type="radio" class="btn-check" wire:model.live="service_type" value="air" id="air_service" autocomplete="off" checked>
                                                <label class="btn btn-outline-primary w-100 py-2" for="air_service">
                                                    <i class="align-middle me-1" data-feather="send"></i> Aéreo
                                                </label>
                                            </div>
                                        @endif
                                        @if($maritimeEnabled)
                                            <div class="flex-fill">
                                                <input type="radio" class="btn-check" wire:model.live="service_type" value="maritime" id="sea_service" autocomplete="off">
                                                <label class="btn btn-outline-info w-100 py-2" for="sea_service">
                                                    <i class="align-middle me-1" data-feather="anchor"></i> Marítimo
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="weight">Peso Real (lbs)</label>
                                        <div class="input-group">
                                            <input type="number" step="0.1" wire:model="weight" id="weight" class="form-control @error('weight') is-invalid @enderror">
                                            <span class="input-group-text">lbs</span>
                                        </div>
                                        @error('weight') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="declared_value">Valor Declarado (USD)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" wire:model="declared_value" id="declared_value" class="form-control @error('declared_value') is-invalid @enderror">
                                        </div>
                                        @error('declared_value') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>



                                <div class="mb-4">
                                    <label class="form-label">Dimensiones (Pulgadas - Opcional)</label>
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <input type="number" wire:model="length" placeholder="Largo" class="form-control text-center @error('length') is-invalid @enderror">
                                        </div>
                                        <div class="col-4">
                                            <input type="number" wire:model="width" placeholder="Ancho" class="form-control text-center @error('width') is-invalid @enderror">
                                        </div>
                                        <div class="col-4">
                                            <input type="number" wire:model="height" placeholder="Alto" class="form-control text-center @error('height') is-invalid @enderror">
                                        </div>
                                    </div>
                                    @if($errors->has('length') || $errors->has('width') || $errors->has('height'))
                                        <span class="text-danger small">Las dimensiones deben ser numéricas.</span>
                                    @endif
                                    <p class="text-muted small mt-2 mb-0">El sistema calculará automáticamente si aplica el peso volumétrico.</p>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    CALCULAR ESTIMADO <i class="align-middle ms-2" data-feather="activity"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Result Section -->
                <div class="col-12 col-md-5">
                    @if($result)
                        <div class="card bg-primary text-white">
                            <div class="card-header bg-transparent border-0 text-center pt-4">
                                <h6 class="text-white-50 small mb-1 uppercase font-bold">Total Estimado</h6>
                                <h2 class="mb-0 text-white" style="font-size: 2.5rem;">${{ number_format($result['total'], 2) }}</h2>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="small text-white-50">Flete ({{ number_format($result['chargeable_weight'], 1) }} lbs)</span>
                                        <span class="fw-bold">${{ number_format($result['shipping_cost'], 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-0">
                                        <span class="small text-white-50">Recargo Combustible (5%)</span>
                                        <span class="fw-bold">${{ number_format($result['fuel_surcharge'], 2) }}</span>
                                    </div>
                                </div>

                                <div class="p-3 bg-white bg-opacity-10 rounded mb-4">
                                    <div class="d-flex align-items-center mb-1 text-info">
                                        <i data-feather="info" class="me-2" style="width: 14px;"></i>
                                        <span class="small font-bold uppercase">Desglose de Peso</span>
                                    </div>
                                    <p class="small mb-0 text-white-50">
                                        @if($result['is_volumetric'])
                                            Se ha aplicado el <strong>peso volumétrico</strong>.
                                        @else
                                            Se ha aplicado el <strong>peso real</strong>.
                                        @endif
                                    </p>
                                </div>

                                <div class="text-center">
                                    <p class="small italic mb-0 text-white-50">Este es un cálculo estimado. El valor final puede variar.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card h-100 border-dashed d-flex align-items-center justify-content-center text-center p-5 bg-light">
                            <div>
                                <div class="stat bg-white text-muted mx-auto mb-4" style="width: 60px; height: 60px;">
                                    <i data-feather="pocket" style="width: 28px; height: 28px;"></i>
                                </div>
                                <h5 class="text-dark mb-2">Esperando Datos</h5>
                                <p class="text-muted small">Completa el formulario para obtener un presupuesto detallado de tu envío.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
