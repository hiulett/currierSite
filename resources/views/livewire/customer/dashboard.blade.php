<div class="container-fluid p-0">
    <!-- Header Section -->
    <div class="row mb-2 align-items-center">
        <div class="col-auto d-none d-sm-block">
            <h1 class="h4 mb-0 uppercase font-black tracking-tight text-dark">
                {{ __('¡Hola, :name!', ['name' => explode(' ', $customer->user->name ?? 'Usuario')[0]]) }} 👋
            </h1>
            <p class="text-muted xsmall mb-0">Bienvenido a tu centro de control logístico.</p>
        </div>
        <div class="col-auto ms-auto text-end">
            <div class="card d-inline-block border-0 shadow-sm bg-primary text-white px-3 py-1 mb-0" style="border-radius: 0.75rem;">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <p class="xsmall font-black uppercase mb-0 text-white-50" style="font-size: 0.55rem;">Tu Casillero</p>
                        <h5 class="mb-0 fw-black text-white">{{ $customer->box_number }}</h5>
                    </div>
                    <i data-feather="box" style="width: 18px; height: 18px; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Loyalty Progress Card -->
    @if($currentLevel || $nextLevel)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-auto text-center mb-3 mb-md-0">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm mb-2"
                                 style="width: 64px; height: 64px; background-color: {{ $currentLevel->color ?? '#6c757d' }}; color: white;">
                                <i data-feather="{{ $currentLevel->icon ?? 'star' }}" style="width: 32px; height: 32px;"></i>
                            </div>
                            <h5 class="fw-black mb-0 uppercase small text-dark">{{ $currentLevel->name ?? 'Miembro' }}</h5>
                        </div>
                        <div class="col-md flex-grow-1 px-md-4">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <div>
                                    <h4 class="mb-0 fw-black text-dark">{{ number_format($customer->points) }} <span class="xsmall text-muted fw-bold">PUNTOS</span></h4>
                                    @if($nextLevel)
                                        <p class="mb-0 xsmall text-muted font-bold uppercase">Te faltan {{ number_format($nextLevel->min_points - $customer->points) }} puntos para ser <span style="color: {{ $nextLevel->color }}">{{ $nextLevel->name }}</span></p>
                                    @else
                                        <p class="mb-0 xsmall text-success font-bold uppercase">¡Has alcanzado el nivel máximo!</p>
                                    @endif
                                </div>
                                <div class="text-end d-none d-sm-block">
                                    <span class="xsmall font-black text-muted uppercase">Beneficio Actual:</span>
                                    <div class="fw-bold text-primary">{{ $currentLevel ? ($currentLevel->multiplier > 1 ? (($currentLevel->multiplier - 1) * 100) . '% Extra Puntos' : 'Nivel Base') : 'Ninguno' }}</div>
                                </div>
                            </div>

                            @if($nextLevel)
                                @php
                                    $min = $currentLevel ? $currentLevel->min_points : 0;
                                    $max = $nextLevel->min_points;
                                    $divisor = ($max - $min) ?: 1;
                                    $progress = (($customer->points - $min) / $divisor) * 100;
                                    $progress = max(0, min(100, $progress));
                                @endphp
                                <div class="progress" style="height: 12px; border-radius: 6px; background-color: #e9ecef;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                         style="width: {{ $progress }}%; background-color: {{ $currentLevel->color ?? '#3b7ddd' }};"
                                         aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            @else
                                <div class="progress" style="height: 12px; border-radius: 6px;">
                                    <div class="progress-bar bg-success w-100" role="progressbar"></div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-auto mt-3 mt-md-0 text-center">
                            <a href="#" class="btn btn-outline-dark btn-sm fw-black uppercase px-4 rounded-pill">
                                VER BENEFICIOS
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Stats Row -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-xl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm overflow-hidden hover-lift transition-all">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-1 fw-black text-dark">{{ count($recent_packages) }}</h3>
                            <p class="mb-0 text-uppercase font-bold xsmall text-muted">Paquetes en Bodega</p>
                        </div>
                        <div class="stat bg-primary-light text-primary">
                            <i class="align-middle" data-feather="home"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm overflow-hidden hover-lift transition-all">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-1 fw-black text-primary">{{ $packages_in_transit_count }}</h3>
                            <p class="mb-0 text-uppercase font-bold xsmall text-muted">Viniendo al País</p>
                        </div>
                        <div class="stat bg-info-light text-info">
                            <i class="align-middle" data-feather="truck"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm overflow-hidden hover-lift transition-all border-start border-danger border-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-1 fw-black text-danger">{{ $unpaid_invoices_count }}</h3>
                            <p class="mb-0 text-uppercase font-bold xsmall text-muted">Facturas por Pagar</p>
                        </div>
                        <div class="stat bg-danger-light text-danger">
                            <i class="align-middle" data-feather="credit-card"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm overflow-hidden hover-lift transition-all">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-1 fw-black text-warning">{{ number_format($customer->points) }}</h3>
                            <p class="mb-0 text-uppercase font-bold xsmall text-muted">LogiPuntos VIP</p>
                        </div>
                        <div class="stat bg-warning-light text-warning">
                            <i class="align-middle" data-feather="star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content: Activity -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm mb-4 h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 uppercase font-black small">Actividad de mi Carga</h5>
                    <a href="{{ route('customer.packages') }}" class="btn btn-xs btn-light border text-uppercase fw-bold">Ver Historial</a>
                </div>
                <div class="card-body p-4 p-md-5">
                    @forelse($recent_packages as $package)
                        <div class="d-flex align-items-start mb-4">
                            <div class="stat bg-primary-light text-primary rounded-circle me-3 flex-shrink-0">
                                <i class="align-middle" data-feather="package" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                    <div>
                                        <strong class="text-dark">{{ $package->tracking_number }}</strong>
                                        <div class="text-muted xsmall">{{ $package->description ?? 'Carga sin descripción' }}</div>
                                    </div>
                                    <div class="text-end">
                                        @php
                                            $badgeClass = match($package->status) {
                                                'received' => 'bg-info',
                                                'in_transit' => 'bg-warning',
                                                'arrived' => 'bg-success',
                                                'ready_for_pickup' => 'bg-primary',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} text-uppercase xsmall px-2 py-1">{{ str_replace('_', ' ', $package->status) }}</span>
                                        <div class="text-muted xsmall mt-1">{{ $package->created_at->format('d M') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!$loop->last) <hr class="my-4 opacity-50"> @endif
                    @empty
                        <div class="text-center py-5">
                            <i data-feather="inbox" class="text-muted mb-3" style="width: 48px; height: 48px; opacity: 0.2;"></i>
                            <p class="text-muted small">No hay carga activa registrada en este momento.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar: Addresses -->
        <div class="col-12 col-xl-4">
            <div class="card bg-dark text-white border-0 shadow-lg mb-4" style="border-radius: 1rem;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <i data-feather="map-pin" class="text-primary me-3" style="width: 24px; height: 24px;"></i>
                        <h5 class="card-title text-white mb-0 uppercase font-black small tracking-widest">Tus Direcciones USA</h5>
                    </div>

                    @php
                        $tenant = \App\Models\Tenant::find(session('tenant_id'));
                        $settings = $tenant->settings_json ?? [];

                        $airEnabled = $settings['service_air_enabled'] ?? true;
                        $maritimeEnabled = $settings['service_maritime_enabled'] ?? true;
                    @endphp

                    <!-- AIR SERVICE ADDRESS -->
                    @if($airEnabled && isset($settings['air_address']))
                        <div class="mb-4">
                            <h6 class="xsmall font-black text-primary uppercase mb-3 tracking-widest"><i class="align-middle me-1" data-feather="send" style="width: 12px;"></i> Servicio Aéreo</h6>
                            <div class="bg-white bg-opacity-10 p-4 rounded-4 border border-white border-opacity-10 position-relative shadow-sm overflow-hidden">
                                <div class="position-absolute top-0 end-0 bg-primary opacity-10 rounded-circle" style="width: 100px; height: 100px; transform: translate(30%, -30%);"></div>

                                <button class="btn btn-link btn-sm p-0 text-white-50 xsmall fw-bold shadow-none border-0 position-absolute top-0 end-0 m-3"
                                    onclick="navigator.clipboard.writeText('{{ $customer->box_number_air ?: $customer->box_number }} {{ $customer->user->name }}\n{{ $settings['air_address'] }}\nCIUDAD: {{ $settings['air_city'] }}\nESTADO: {{ $settings['air_state'] }}\nZIP CODE: {{ $settings['air_zip_code'] }}\nTEL: {{ $settings['air_phone'] }}'); alert('¡Dirección Aérea copiada!');">
                                    <i data-feather="copy" class="me-1" style="width: 14px;"></i> COPIAR
                                </button>

                                <div class="position-relative">
                                    <div class="mb-3">
                                        <h5 class="fw-black text-white mb-0">
                                            <span class="text-primary">{{ $customer->box_number_air ?: $customer->box_number }}</span> {{ $customer->user->name }}
                                        </h5>
                                    </div>
                                    <div class="font-monospace small text-white-50 leading-loose">
                                        <div class="mb-1 text-white fw-bold">{{ $settings['air_address'] }}</div>
                                        <div class="mb-0">CIUDAD: <span class="text-white">{{ $settings['air_city'] }}</span></div>
                                        <div class="mb-0">ESTADO: <span class="text-white">{{ $settings['air_state'] }}</span></div>
                                        <div class="mb-0">ZIP CODE: <span class="text-white">{{ $settings['air_zip_code'] }}</span></div>
                                        <div class="mb-0">TEL: <span class="text-white">{{ $settings['air_phone'] }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($airEnabled && $maritimeEnabled)
                        <hr class="my-4 border-white border-opacity-10">
                    @endif

                    <!-- MARITIME SERVICE ADDRESS -->
                    @if($maritimeEnabled && isset($settings['maritime_address']))
                        <div class="mb-4">
                            <h6 class="xsmall font-black text-info uppercase mb-3 tracking-widest"><i class="align-middle me-1" data-feather="anchor" style="width: 12px;"></i> Servicio Marítimo</h6>
                            <div class="bg-white bg-opacity-10 p-4 rounded-4 border border-white border-opacity-10 position-relative shadow-sm overflow-hidden">
                                <div class="position-absolute top-0 end-0 bg-info opacity-10 rounded-circle" style="width: 100px; height: 100px; transform: translate(30%, -30%);"></div>

                                <button class="btn btn-link btn-sm p-0 text-white-50 xsmall fw-bold shadow-none border-0 position-absolute top-0 end-0 m-3"
                                    onclick="navigator.clipboard.writeText('{{ $customer->box_number_maritime ?: $customer->box_number }} {{ $customer->user->name }}\n{{ $settings['maritime_address'] }}\nCIUDAD: {{ $settings['maritime_city'] }}\nESTADO: {{ $settings['maritime_state'] }}\nZIP CODE: {{ $settings['maritime_zip_code'] }}\nTEL: {{ $settings['maritime_phone'] }}'); alert('¡Dirección Marítima copiada!');">
                                    <i data-feather="copy" class="me-1" style="width: 14px;"></i> COPIAR
                                </button>

                                <div class="position-relative">
                                    <div class="mb-3">
                                        <h5 class="fw-black text-white mb-0">
                                            <span class="text-info">{{ $customer->box_number_maritime ?: $customer->box_number }}</span> {{ $customer->user->name }}
                                        </h5>
                                    </div>
                                    <div class="font-monospace small text-white-50 leading-loose">
                                        <div class="mb-1 text-white fw-bold">{{ $settings['maritime_address'] }}</div>
                                        <div class="mb-0">CIUDAD: <span class="text-white">{{ $settings['maritime_city'] }}</span></div>
                                        <div class="mb-0">ESTADO: <span class="text-white">{{ $settings['maritime_state'] }}</span></div>
                                        <div class="mb-0">ZIP CODE: <span class="text-white">{{ $settings['maritime_zip_code'] }}</span></div>
                                        <div class="mb-0">TEL: <span class="text-white">{{ $settings['maritime_phone'] }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 p-3 bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-3 text-center">
                        <p class="xsmall mb-0 text-warning fw-bold leading-sm">
                            <i data-feather="alert-triangle" class="me-1" style="width: 12px;"></i>
                            Usa tu identificador azul para asegurar que tu carga llegue a tu cuenta.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <div class="card-body p-4 text-center">
                    <h6 class="fw-black text-muted text-uppercase xsmall tracking-widest mb-3">Rastreo de Paquetes Externos</h6>
                    <form action="{{ route('customer.tracking') }}" method="GET">
                        <div class="input-group input-group-sm mb-2 shadow-sm border rounded-pill overflow-hidden">
                            <input type="text" name="search_tracking" class="form-control border-0 ps-3" placeholder="UPS, FedEx, USPS...">
                            <button type="submit" class="btn btn-primary px-3 fw-black uppercase tracking-tighter">
                                <i data-feather="search" style="width: 14px;"></i>
                            </button>
                        </div>
                    </form>
                    <p class="xsmall text-muted mb-0">Rastrea tus compras de USA en tiempo real.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-4 text-center">
                    <h6 class="fw-black text-muted text-uppercase xsmall tracking-widest mb-3">Centro de Ayuda</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('customer.tickets.index') }}" class="btn btn-outline-primary btn-sm fw-black shadow-sm">
                            SOPORTE TÉCNICO
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
