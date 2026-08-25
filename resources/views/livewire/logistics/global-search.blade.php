<div>
    @if($show)
        <div class="position-fixed top-0 start-0 w-100 h-100" style="background: rgba(15, 23, 42, 0.5); z-index: 2000;" wire:click="close"></div>
        <div class="position-fixed start-50 translate-middle-x mt-5" style="z-index: 2001; width: 620px; max-width: 94vw;">
            <div class="card border-0 shadow-lg" style="border-radius: 1rem; overflow: hidden;">
                <div class="card-body p-4">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white border-0 ps-0"><i data-feather="search" style="width: 18px; height: 18px;"></i></span>
                        <input type="text" wire:model.live.debounce.250ms="query" wire:keydown.escape="close" class="form-control form-control-lg border-0 fw-bold ps-0" placeholder="Buscar módulos, clientes o paquetes..." autofocus>
                    </div>

                    @if(trim($query) === '')
                        @if(!empty($modules))
                            <div class="small text-muted text-uppercase fw-black mb-2">Módulos</div>
                            <div class="row g-2">
                                @foreach($modules as $module)
                                    <div class="col-6">
                                        <a href="{{ $module['url'] }}" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none text-dark" style="border: 1px solid #e9ecef;">
                                            <i data-feather="{{ $module['icon'] }}" style="width: 16px; height: 16px; flex-shrink: 0;"></i>
                                            <span class="small fw-bold">{{ $module['name'] }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        @if($customers->isEmpty() && $packages->isEmpty() && empty($modules))
                            <div class="text-center py-4 text-muted">
                                <i data-feather="search" class="d-block mx-auto mb-2 opacity-25" style="width: 40px; height: 40px;"></i>
                                Sin resultados para "{{ $query }}".
                            </div>
                        @endif

                        @if(!empty($modules))
                            <div class="small text-muted text-uppercase fw-black mb-2">Módulos</div>
                            @foreach($modules as $module)
                                <a href="{{ $module['url'] }}" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none text-dark">
                                    <i data-feather="{{ $module['icon'] }}" style="width: 16px; height: 16px; flex-shrink: 0;"></i>
                                    <span class="small fw-bold">{{ $module['name'] }}</span>
                                </a>
                            @endforeach
                        @endif

                        @if($customers->isNotEmpty())
                            <div class="small text-muted text-uppercase fw-black mt-3 mb-2">Clientes</div>
                            @foreach($customers as $customer)
                                <a href="{{ route('logistics.customers.detail', $customer) }}" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none text-dark">
                                    <div class="avatar avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center font-bold" style="width: 28px; height: 28px; flex-shrink: 0;">{{ strtoupper(substr($customer->user?->name ?? '?', 0, 1)) }}</div>
                                    <div>
                                        <div class="small fw-bold">{{ $customer->user?->name ?? 'Sin usuario' }}</div>
                                        <div class="xsmall text-primary fw-bold">{{ $customer->box_number }}</div>
                                    </div>
                                </a>
                            @endforeach
                        @endif

                        @if($packages->isNotEmpty())
                            <div class="small text-muted text-uppercase fw-black mt-3 mb-2">Paquetes</div>
                            @foreach($packages as $package)
                                <a href="{{ $package->customer_id ? route('logistics.customers.detail', $package->customer_id) : route('logistics.inventory', ['search' => $package->tracking_number]) }}" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none text-dark">
                                    <i data-feather="package" style="width: 16px; height: 16px; flex-shrink: 0;"></i>
                                    <div>
                                        <div class="small fw-bold">{{ $package->tracking_number }}</div>
                                        <div class="xsmall text-muted">{{ $package->customer?->user?->name ?? 'Sin asignar' }}</div>
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    @endif
                </div>
                <div class="card-footer bg-light border-0 py-2 px-4">
                    <div class="d-flex align-items-center gap-3 text-muted xsmall">
                        <span><kbd class="bg-white border">Ctrl</kbd> + <kbd class="bg-white border">K</kbd> abrir</span>
                        <span><kbd class="bg-white border">Esc</kbd> cerrar</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
