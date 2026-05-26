<div>
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 1rem;">
                <div class="card-body p-5 text-center">
                    <h2 class="fw-black uppercase tracking-widest text-primary mb-2">Rastreo Global Inteligente</h2>
                    <p class="text-muted small mb-4">Consulta el estado real de cualquier paquete (Internacional + Local Panamá)</p>

                    <div class="input-group input-group-lg shadow-sm border rounded-pill overflow-hidden bg-light">
                        <input type="text" wire:model.defer="search_tracking" wire:keydown.enter="search"
                               wire:loading.attr="disabled"
                               class="form-control border-0 bg-light ps-4 fw-bold"
                               placeholder="Ingresa Tracking (UPS, FedEx, USPS, Fuzion...)">
                        <button wire:click="search" wire:loading.attr="disabled" class="btn btn-primary px-4 fw-black uppercase tracking-tighter d-flex align-items-center">
                            <span wire:loading.remove wire:target="search">
                                <i class="align-middle me-1" data-feather="search"></i> Consultar Live
                            </span>
                            <span wire:loading wire:target="search">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span> Consultando Redes...
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Loading State Results Placeholder -->
            <div wire:loading wire:target="search" class="w-100 animate-pulse">
                <div class="card border-0 shadow-sm mb-4 p-5 text-center rounded-4 bg-white">
                    <div class="spinner-grow text-primary mb-3" role="status"></div>
                    <h4 class="fw-black uppercase text-muted">Sincronizando con Servidores Globales</h4>
                    <p class="small text-muted mb-0">Estamos consultando UPS, FedEx, DHL y FuzionCargo. Por favor espere...</p>
                </div>
            </div>

            <div wire:loading.remove wire:target="search">
                @if($searched)
                <div class="animate-fade-in">
                    <!-- Dashboard Data Summary -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm p-3 bg-white text-center rounded-3">
                                <span class="text-uppercase xsmall font-black text-muted tracking-widest">Estado Actual</span>
                                <div class="mt-1">
                                    @if($package)
                                        <span class="badge bg-primary uppercase font-black px-3">{{ $package->status }}</span>
                                    @elseif($external_data)
                                        <span class="badge bg-info uppercase font-black px-3">{{ $external_data['status'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm p-3 bg-white text-center rounded-3">
                                <span class="text-uppercase xsmall font-black text-muted tracking-widest">Fuente de Datos</span>
                                <div class="mt-1 fw-black small uppercase">
                                    @if($package)
                                        <span class="text-success"><i data-feather="database" class="me-1" style="width: 12px;"></i> LogiSaaS Local</span>
                                    @else
                                        <span class="text-info"><i data-feather="globe" class="me-1" style="width: 12px;"></i> Intel. Externa</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm p-3 bg-white text-center rounded-3">
                                <span class="text-uppercase xsmall font-black text-muted tracking-widest">Transportista</span>
                                <div class="mt-1 fw-black small uppercase text-primary">
                                    {{ $external_data['carrier'] ?? 'LogiSaaS' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($package || $external_data)
                        <!-- Results Card -->
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                            <div class="card-header bg-dark p-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-white mb-0 fw-black uppercase tracking-tighter">Historial del Envío</h5>
                                    <small class="text-white-50 font-mono">{{ $package ? $package->tracking_number : $search_tracking }}</small>
                                </div>
                                @if($package)
                                    <a href="{{ route('logistics.inventory', ['search' => $package->tracking_number]) }}" class="btn btn-sm btn-outline-light fw-bold px-3">Ver en Inventario</a>
                                @endif
                            </div>
                            <div class="card-body p-4 bg-white">
                                <div class="timeline mt-3">
                                    @php
                                        $history = $package ? $package->trackingEvents->map(fn($e) => [
                                            'status' => strtoupper($e->status),
                                            'date' => $e->created_at->format('d M, Y H:i'),
                                            'location' => $e->location,
                                            'notes' => $e->notes,
                                            'source' => 'Local'
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
                                                            {{ $item['status'] }}
                                                            <span class="badge bg-light text-muted xsmall font-bold ms-2">{{ $item['source'] ?? 'External' }}</span>
                                                        </h6>
                                                        <p class="text-muted xsmall fw-bold uppercase mt-1 mb-1">
                                                            <i data-feather="map-pin" class="me-1" style="width: 10px;"></i> {{ $item['location'] ?? 'Ubicación no especificada' }}
                                                        </p>
                                                    </div>
                                                    <span class="text-muted font-mono xsmall">{{ $item['date'] }}</span>
                                                </div>
                                                <div class="p-3 bg-light rounded-3 small text-dark mt-2 border border-dashed">
                                                    {{ $item['notes'] ?? 'Registro de actividad detectado por el sistema.' }}
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
                        <!-- Not Found -->
                        <div class="card border-0 shadow-sm bg-danger-light text-danger p-5 text-center rounded-4">
                            <i data-feather="x-circle" class="mx-auto mb-3" style="width: 64px; height: 48px;"></i>
                            <h4 class="fw-black uppercase">Tracking no detectado</h4>
                            <p class="mb-0 small font-bold">No se encontraron registros locales ni en las redes de UPS, FedEx, USPS o FuzionCargo.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
