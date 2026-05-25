<div>
    <div class="row mb-3">
        <div class="col-12 d-flex align-items-center">
            <a href="{{ route('logistics.delivery') }}" class="btn btn-light shadow-sm me-3">
                <i data-feather="arrow-left"></i>
            </a>
            <h1 class="h3 mb-0 uppercase font-black">Hoja de Ruta: {{ $delivery->route_name }}</h1>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message"><strong>¡Éxito!</strong> {{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Package List -->
        <div class="col-12 col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0 uppercase font-black small">Paquetes en esta Ruta</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Tracking</th>
                                <th>Cliente</th>
                                <th class="pe-4 text-end">Peso</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($delivery->packages as $pkg)
                                <tr>
                                    <td class="ps-4 fw-black text-dark">{{ $pkg->tracking_number }}</td>
                                    <td>
                                        <div class="fw-bold small">{{ $pkg->customer->user->name }}</div>
                                        <div class="text-primary font-black small">{{ $pkg->customer->box_number }}</div>
                                    </td>
                                    <td class="pe-4 text-end fw-black text-dark small">{{ $pkg->weight }} lbs</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($delivery->cod_amount > 0)
                <div class="card bg-warning text-dark shadow-sm">
                    <div class="card-body py-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="text-uppercase font-black mb-1">Monto a Cobrar (COD)</h5>
                            <p class="mb-0 opacity-75 small">Este monto debe ser recaudado por el repartidor.</p>
                        </div>
                        <h2 class="fw-black mb-0">${{ number_format($delivery->cod_amount, 2) }}</h2>
                    </div>
                </div>
            @endif
        </div>

        <!-- POD Form -->
        <div class="col-12 col-lg-5">
            <div class="card shadow-lg">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title text-white mb-0 uppercase font-black">Prueba de Entrega (POD)</h5>
                </div>
                <form wire:submit.prevent="completeDelivery">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label small font-black text-uppercase text-muted">Evidencia Fotográfica</label>
                            <input type="file" wire:model="photo" class="form-control" accept="image/*">
                            @if($photo)
                                <img src="{{ $photo->temporaryUrl() }}" class="img-fluid rounded mt-2 border" style="max-height: 200px;">
                            @endif
                            @error('photo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4" x-data="{
                            init() {
                                if (navigator.geolocation) {
                                    navigator.geolocation.getCurrentPosition(pos => {
                                        @this.setLocation(pos.coords.latitude, pos.coords.longitude);
                                    });
                                }
                            }
                        }">
                            <label class="form-label small font-black text-uppercase text-muted">Ubicación GPS</label>
                            <div class="p-3 bg-light rounded-3 border d-flex align-items-center">
                                <i class="text-success me-2" data-feather="map-pin"></i>
                                <span class="small fw-bold text-muted">
                                    @if($latitude)
                                        {{ $latitude }}, {{ $longitude }} (Detectado)
                                    @else
                                        Detectando coordenadas...
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label small font-black text-uppercase text-muted">Notas de Entrega</label>
                            <textarea wire:model="notes" rows="3" class="form-control" placeholder="Ej: Se dejó en recepción con guardia..."></textarea>
                        </div>
                    </div>
                    <div class="card-footer bg-light p-4">
                        <button type="submit" class="btn btn-success w-100 py-3 fw-black text-uppercase tracking-widest shadow-lg transform transition hover:scale-105">
                            <i class="align-middle me-2" data-feather="check-circle"></i> Finalizar Entrega
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
