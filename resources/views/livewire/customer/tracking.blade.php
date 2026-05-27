<div>
    <div class="row">
        <div class="col-12 col-lg-9 mx-auto">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 1.5rem;">
                <div class="card-body p-5 text-center">
                    <h2 class="fw-black uppercase tracking-widest text-primary mb-2">Rastreador Live</h2>
                    <p class="text-muted small mb-4">Ingresa el número de tracking de tu compra (Amazon, eBay, etc.) para ver su ubicación en tiempo real.</p>

                    <div class="input-group input-group-lg shadow-sm border rounded-pill overflow-hidden bg-light">
                        <input type="text" wire:model.defer="search_tracking" wire:keydown.enter="search"
                               wire:loading.attr="disabled"
                               class="form-control border-0 bg-light ps-4 fw-bold"
                               placeholder="Ej: 1Z9999999999999999">
                        <button wire:click="search" wire:loading.attr="disabled" class="btn btn-primary px-4 fw-black uppercase tracking-tighter d-flex align-items-center">
                            <span wire:loading.remove wire:target="search">
                                <i class="align-middle me-1" data-feather="search"></i> Rastrear
                            </span>
                            <span wire:loading wire:target="search">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span> Buscando...
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div wire:loading wire:target="search" class="w-100 animate-pulse text-center py-5">
                <div class="spinner-grow text-primary mb-3" role="status"></div>
                <h5 class="fw-black uppercase text-muted">Consultando Redes Internacionales</h5>
                <p class="small text-muted">Estamos sincronizando con UPS, FedEx y el sistema local de Panamá...</p>
            </div>

            <div wire:loading.remove wire:target="search">
                @if($searched)
                    <div class="animate-fade-in">
                        @if($package || $external_data)
                            <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                                <div class="card-header bg-dark p-4 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="text-white mb-0 fw-black uppercase tracking-tighter">Detalles del Envío</h5>
                                        <small class="text-white-50 font-mono">{{ $package ? $package->tracking_number : ($external_data['tracking'] ?? $search_tracking) }}</small>
                                    </div>
                                    @if($package)
                                        <span class="badge bg-primary uppercase font-black px-3">{{ $package->status }}</span>
                                    @elseif($external_data)
                                        <span class="badge bg-info uppercase font-black px-3">{{ $external_data['status'] }}</span>
                                    @endif
                                </div>
                                <div class="card-body p-4 bg-white">
                                    @if(!$package && isset($external_data['carrier']))
                                        <div class="alert alert-info border-0 shadow-sm rounded-3 py-2 px-3 mb-4 d-flex align-items-center">
                                            <i data-feather="truck" class="me-2" style="width: 16px;"></i>
                                            <span class="small font-bold uppercase">Transportista Detectado: <strong>{{ $external_data['carrier'] }}</strong></span>
                                        </div>
                                    @endif

                                    <div class="timeline mt-2">
                                        @php
                                            $history = $package ? $package->trackingEvents->map(fn($e) => [
                                                'status' => strtoupper($e->status),
                                                'date' => $e->created_at->format('d M, Y H:i'),
                                                'location' => $e->location,
                                                'notes' => $e->notes,
                                                'source' => 'LogiSaaS Local'
                                            ])->toArray() : ($external_data['history'] ?? []);
                                        @endphp

                                        @forelse($history as $index => $item)
                                            <div class="d-flex mb-4 relative">
                                                <div class="flex-shrink-0 text-center" style="width: 50px;">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto {{ $index == 0 ? 'bg-primary shadow-lg' : 'bg-light border' }}" style="width: 32px; height: 32px; z-index: 2; position: relative;">
                                                        @if($index == 0)
                                                            <i class="text-white" data-feather="check" style="width: 14px;"></i>
                                                        @else
                                                            <div class="bg-secondary rounded-circle" style="width: 6px; height: 6px;"></div>
                                                        @endif
                                                    </div>
                                                    @if(!$loop->last)
                                                        <div class="bg-light mx-auto" style="width: 2px; height: 100%; position: absolute; left: 24px; top: 32px; z-index: 1;"></div>
                                                    @endif
                                                </div>
                                                <div class="ms-3 flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h6 class="fw-black mb-0 uppercase small {{ $index == 0 ? 'text-primary' : 'text-dark' }}">
                                                                {{ str_replace('_', ' ', $item['status']) }}
                                                                <span class="badge bg-light text-muted xsmall font-bold ms-2" style="font-size: 0.5rem;">{{ $item['source'] ?? 'Global' }}</span>
                                                            </h6>
                                                            <p class="text-muted xsmall fw-bold uppercase mt-1 mb-1">
                                                                <i data-feather="map-pin" class="me-1" style="width: 10px;"></i> {{ $item['location'] ?? 'Ubicación no especificada' }}
                                                            </p>
                                                        </div>
                                                        <span class="text-muted font-mono xsmall">{{ $item['date'] }}</span>
                                                    </div>
                                                    <div class="p-3 bg-light rounded-3 small text-dark mt-2 border border-dashed" style="font-size: 0.75rem;">
                                                        {{ $item['notes'] ?? 'Estado del paquete actualizado.' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-5">
                                                <i data-feather="info" class="text-muted mb-3" style="width: 48px; height: 48px;"></i>
                                                <p class="text-muted italic">No hay historial disponible para este tracking.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="card border-0 shadow-sm bg-warning-light text-warning p-5 text-center rounded-4">
                                <i data-feather="alert-circle" class="mx-auto mb-3" style="width: 48px; height: 48px;"></i>
                                <h5 class="fw-black uppercase">Tracking no encontrado</h5>
                                <p class="mb-0 small font-bold">Asegúrate de que el número sea correcto. Si acabas de realizar la compra, puede tardar unas horas en aparecer en el sistema global.</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
