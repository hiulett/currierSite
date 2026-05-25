<div>
    <!-- Reception Stats -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <a href="{{ route('logistics.inventory', ['filter_status' => 'received']) }}" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-primary text-white text-decoration-none">
                <div class="card-body py-4 text-center">
                    <h3 class="mb-2 text-white">{{ $stats['received_today'] }}</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Recibidos Hoy</p>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-info text-white">
                <div class="card-body py-4 text-center">
                    <h3 class="mb-2 text-white">{{ number_format($stats['weight_today'], 2) }} lbs</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Peso Ingresado Hoy</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-dark text-white">
                <div class="card-body py-2 px-4 d-flex align-items-center">
                    <div class="small fw-bold text-white-50 text-uppercase me-3" style="font-size: 0.65rem;">Últimos 3:</div>
                    <div class="d-flex gap-2">
                        @foreach($stats['last_packages']->take(3) as $lp)
                            <div class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 p-2" style="font-size: 0.6rem;">
                                <i class="align-middle me-1 text-info" data-feather="package" style="width: 10px;"></i>
                                {{ $lp->tracking_number }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <strong>¡Éxito!</strong> {{ session('message') }}
                    </div>
                    @if($last_package_id)
                        <a href="{{ route('logistics.label', $last_package_id) }}" target="_blank" class="btn btn-sm btn-success ms-3 font-bold">
                            <i class="align-middle" data-feather="printer"></i> IMPRIMIR ETIQUETA
                        </a>
                    @endif
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Main Form Column -->
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small">Registro de Ingreso de Paquete</h5>
                </div>

                <form wire:submit.prevent="save">
                    <div class="card-body p-4 p-md-5">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small font-black text-uppercase text-muted tracking-widest">Tracking Externo</label>
                                <input type="text" wire:model="tracking_number"
                                       class="form-control form-control-lg fw-bold border-2"
                                       style="font-size: 1.25rem;"
                                       placeholder="Escanear o escribir...">
                                @error('tracking_number') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 position-relative" x-data="{ open: true }">
                                <label class="form-label small font-black text-uppercase text-muted tracking-widest">Buscar Cliente / Casillero</label>
                                <input type="text" wire:model.live="customer_search"
                                       @input="open = true"
                                       class="form-control form-control-lg fw-bold border-2 text-primary"
                                       style="font-size: 1.25rem;"
                                       placeholder="Nombre, Email o PTY-...">

                                @if(!empty($search_results))
                                    <div x-show="open" class="position-absolute z-3 w-100 bg-white mt-1 shadow-lg border rounded-3 overflow-hidden" style="left: 0; right: 0;">
                                        @foreach($search_results as $result)
                                            <button type="button" wire:click="selectCustomer({{ $result->id }})" @click="open = false"
                                                    class="btn btn-white w-100 text-start p-3 hover:bg-light d-flex align-items-center justify-content-between border-bottom rounded-0 transition">
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $result->user->name }}</div>
                                                    <div class="small text-muted text-uppercase" style="font-size: 0.7rem;">{{ $result->user->email }}</div>
                                                </div>
                                                <span class="badge bg-primary-light text-primary fw-bold text-uppercase">{{ $result->box_number }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif

                                @if($recentCustomers->count() > 0)
                                    <div class="mt-2 d-flex flex-wrap gap-2">
                                        <span class="text-muted small w-100 text-uppercase font-black" style="font-size: 0.65rem;">Recientes:</span>
                                        @foreach($recentCustomers as $recent)
                                            <button type="button" wire:click="selectCustomer({{ $recent->id }})"
                                                    class="btn btn-xs btn-outline-primary py-1 px-2 rounded-pill text-uppercase fw-bold" style="font-size: 0.7rem;">
                                                <span class="d-inline-block bg-success rounded-circle me-1" style="width: 6px; height: 6px;"></span>
                                                {{ explode(' ', $recent->user->name)[0] }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endif

                                @error('box_number') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label small font-black text-uppercase text-muted">Peso Real (lbs)</label>
                                        <input type="number" step="0.01" wire:model="weight"
                                               class="form-control form-control-lg fw-bold text-center border-2">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small font-black text-uppercase text-muted">Bodega</label>
                                        <select wire:model="warehouse_id" class="form-select form-select-lg fw-bold">
                                            <option value="">Seleccione...</option>
                                            @foreach($warehouses as $wh)
                                                <option value="{{ $wh->id }}">{{ $wh->name }} ({{ $wh->code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @error('weight') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                                @error('warehouse_id') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <label class="form-label small font-black text-uppercase text-primary mb-3 d-block tracking-tighter">Dimensiones Volumétricas (Pulgadas)</label>
                                    <div class="row g-2 mb-2">
                                        <div class="col-4">
                                            <input type="number" placeholder="Largo" wire:model.live="length" class="form-control form-control-sm text-center fw-bold">
                                        </div>
                                        <div class="col-4">
                                            <input type="number" placeholder="Ancho" wire:model.live="width" class="form-control form-control-sm text-center fw-bold">
                                        </div>
                                        <div class="col-4">
                                            <input type="number" placeholder="Alto" wire:model.live="height" class="form-control form-control-sm text-center fw-bold">
                                        </div>
                                    </div>
                                    @if($volumetric_weight > 0)
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                            <span class="small font-black text-muted text-uppercase" style="font-size: 0.65rem;">Peso Volumétrico:</span>
                                            <span class="h5 mb-0 fw-black text-primary">{{ $volumetric_weight }} vlb</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label small font-black text-uppercase text-muted">Descripción del Contenido</label>
                            <textarea wire:model="description" rows="2"
                                      placeholder="¿Qué contiene el paquete? (Opcional)"
                                      class="form-control form-control-lg"></textarea>
                        </div>
                    </div>

                    <div class="card-footer bg-light p-4 d-flex justify-content-between align-items-center">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" wire:model="auto_invoice" id="autoInvoiceSwitch">
                            <label class="form-check-label fw-bold text-muted small uppercase" for="autoInvoiceSwitch">
                                Generar factura automáticamente ($2.50/lb)
                            </label>
                        </div>
                        <button type="submit" class="btn btn-lg btn-primary px-5 fw-black text-uppercase tracking-widest shadow-lg transform transition hover:scale-105">
                            Confirmar Ingreso <i class="align-middle ms-2" data-feather="check-circle"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Info Column -->
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm text-center overflow-hidden border-0">
                <div class="bg-primary" style="height: 6px;"></div>
                @if($found_customer)
                    <div class="card-body p-5">
                        <div class="avatar avatar-xl bg-primary-light text-primary rounded-circle d-flex align-items-center justify-center font-black h2 mx-auto mb-4" style="width: 80px; height: 80px;">
                            {{ substr($found_customer->user->name, 0, 1) }}
                        </div>
                        <h4 class="fw-black text-dark mb-1 leading-tight">{{ $found_customer->user->name }}</h4>
                        <div class="badge bg-primary text-uppercase fw-bold px-3 mb-4">{{ $found_customer->box_number }}</div>

                        <div class="row g-0 border-top pt-4 mt-4 text-start">
                            <div class="col-6 border-end pe-3 text-center">
                                <p class="small text-muted text-uppercase font-black mb-1" style="font-size: 0.6rem;">Puntos</p>
                                <p class="h5 mb-0 fw-black text-dark"><i class="align-middle text-warning me-1" data-feather="star" style="width: 14px;"></i> {{ $found_customer->points }}</p>
                            </div>
                            <div class="col-6 ps-3 text-center">
                                <p class="small text-muted text-uppercase font-black mb-1" style="font-size: 0.6rem;">Saldo</p>
                                <p class="h5 mb-0 fw-black {{ $found_customer->balance > 0 ? 'text-danger' : 'text-success' }}">${{ number_format($found_customer->balance, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card-body py-5 opacity-50">
                        <div class="w-20 h-20 bg-light rounded-circle d-flex align-items-center justify-center mx-auto mb-4" style="width: 70px; height: 70px;">
                            <i class="text-muted" data-feather="user" style="width: 30px; height: 30px;"></i>
                        </div>
                        <p class="small font-black text-uppercase text-muted tracking-widest">Esperando Selección de Cliente</p>
                    </div>
                @endif
            </div>

            <!-- Operational Tips -->
            <div class="card bg-dark text-white shadow-lg p-2">
                <div class="card-body">
                    <h5 class="card-title text-white opacity-50 small mb-4 uppercase tracking-widest">Tips de Operación</h5>
                    <div class="d-flex mb-3">
                        <i class="text-success me-3" data-feather="check" style="width: 16px;"></i>
                        <p class="small mb-0 text-white-50">Escanea el código de barras para evitar errores de captura.</p>
                    </div>
                    <div class="d-flex">
                        <i class="text-success me-3" data-feather="check" style="width: 16px;"></i>
                        <p class="small mb-0 text-white-50">Usa las dimensiones para paquetes voluminosos, el sistema calculará el peso vlb automáticamente.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
