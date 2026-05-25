<div>
    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h3 mb-2 uppercase font-black tracking-tight">Despacho en Oficina</h1>
            <p class="text-muted">Procesar retiro directo de paquetes por clientes.</p>
        </div>
    </div>

    <!-- Counter Dashboard -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <a href="{{ route('logistics.inventory', ['filter_status' => 'ready_for_pickup']) }}" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-primary text-white text-decoration-none">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ number_format($stats['waiting_pickup']) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Paquetes por Retirar</p>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="package"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-success text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ number_format($stats['delivered_today']) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Entregados Hoy</p>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-info text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ number_format($stats['weight_delivered_today'], 2) }} lbs</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Peso Despachado Hoy</p>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="database"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="setFilter('debt')" class="card flex-fill border-0 shadow-sm cursor-pointer transform transition hover:scale-102 bg-danger text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ number_format($stats['customers_with_debt']) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Clientes con Deuda</p>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="alert-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message"><strong>¡Éxito!</strong> {{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Search & Results -->
        <div class="col-12 col-xl-7">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 uppercase font-black small text-white">
                        <i class="align-middle me-2" data-feather="search"></i> Buscar Cliente o Tracking
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <input type="text" wire:model.live="search"
                               class="form-control form-control-lg fw-bold border-2 text-primary"
                               placeholder="Escanear tracking, nombre o PTY-..."
                               auto-focus>
                    </div>

                    @if(!empty($search_results))
                        <div class="list-group shadow-lg mb-4">
                            @foreach($search_results as $res)
                                <button wire:click="selectCustomer({{ $res->id }})"
                                        class="list-group-item list-group-item-action p-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-black text-dark">{{ $res->user->name }}</div>
                                        <div class="small text-muted">{{ $res->user->email }}</div>
                                    </div>
                                    <span class="badge bg-primary-light text-primary fw-black text-uppercase">{{ $res->box_number }}</span>
                                </button>
                            @endforeach
                        </div>
                    @endif

                    @if($customer)
                        <div class="table-responsive">
                            <h5 class="uppercase font-black small mb-3 text-muted">Paquetes Disponibles para Entrega</h5>
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;"></th>
                                        <th>Tracking</th>
                                        <th>Estado</th>
                                        <th class="text-end">Peso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($customer_packages as $pkg)
                                        <tr wire:click="togglePackage({{ $pkg->id }})" class="cursor-pointer {{ in_array($pkg->id, $selected_packages) ? 'table-primary' : '' }}">
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" {{ in_array($pkg->id, $selected_packages) ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $pkg->tracking_number }}</div>
                                                <div class="small text-muted" style="font-size: 0.65rem;">
                                                    @if($pkg->delivery_type === 'home_delivery')
                                                        <span class="text-primary fw-bold"><i data-feather="map-pin" style="width: 10px;"></i> DOMICILIO</span>
                                                    @else
                                                        <span class="text-success fw-bold"><i data-feather="home" style="width: 10px;"></i> LOCAL</span>
                                                    @endif
                                                    • {{ Str::limit($pkg->description, 20) }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border text-uppercase" style="font-size: 0.6rem;">{{ $pkg->status }}</span>
                                            </td>
                                            <td class="text-end fw-black text-dark">{{ $pkg->weight }} lbs</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted italic">No hay carga pendiente de retiro para este cliente.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i data-feather="user" style="width: 48px; height: 48px;" class="mb-3 text-muted opacity-25"></i>
                            <p class="fw-black text-uppercase text-muted">Busque un cliente para ver sus paquetes</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Summary & Action -->
        <div class="col-12 col-xl-5">
            @if($customer)
                <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                    <div class="bg-primary p-1"></div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar avatar-lg bg-primary-light text-primary rounded-circle d-flex align-items-center justify-center font-black h3 me-3" style="width: 60px; height: 60px;">
                                {{ substr($customer->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="fw-black text-dark mb-0">{{ $customer->user->name }}</h4>
                                <span class="badge bg-primary text-uppercase fw-bold">{{ $customer->box_number }}</span>
                            </div>
                        </div>

                        <!-- Payment Alert -->
                        @if($unpaid_invoices_count > 0)
                            <div class="alert alert-danger p-3 mb-4 d-flex align-items-center">
                                <i data-feather="alert-triangle" class="me-3"></i>
                                <div>
                                    <div class="fw-black text-uppercase small">Atención: Facturas Pendientes</div>
                                    <div class="small">El cliente tiene <strong>{{ $unpaid_invoices_count }}</strong> facturas sin pagar.</div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-success p-3 mb-4 d-flex align-items-center">
                                <i data-feather="check-circle" class="me-3"></i>
                                <div>
                                    <div class="fw-black text-uppercase small">Cliente al día</div>
                                    <div class="small">No se registran deudas pendientes.</div>
                                </div>
                            </div>
                        @endif

                        <div class="row g-0 border-top pt-4 mb-4">
                            <div class="col-6">
                                <span class="small text-muted text-uppercase fw-black d-block mb-1">Items Seleccionados</span>
                                <span class="h3 fw-black text-primary mb-0">{{ count($selected_packages) }}</span>
                            </div>
                            <div class="col-6 text-end">
                                <span class="small text-muted text-uppercase fw-black d-block mb-1">Peso Total</span>
                                <span class="h3 fw-black text-dark mb-0">
                                    {{ collect($customer_packages)->whereIn('id', $selected_packages)->sum('weight') }} lbs
                                </span>
                            </div>
                        </div>

                        <form wire:submit.prevent="deliverPackages">
                            <div class="mb-4">
                                <label class="form-label small font-black text-uppercase text-muted">Nombre de quien retira</label>
                                <input type="text" wire:model="receiver_name" class="form-control form-control-lg fw-bold border-2">
                                @error('receiver_name') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                            </div>

                            @if($unpaid_invoices_count > 0)
                                <div class="card bg-light border-warning mb-4">
                                    <div class="card-body p-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" wire:model.live="pay_pending_invoices" id="payInvoicesSwitch">
                                            <label class="form-check-label fw-black text-warning small uppercase" for="payInvoicesSwitch">
                                                Cobrar deuda pendiente (${{ number_format($customer->balance, 2) }})
                                            </label>
                                        </div>
                                        <p class="small text-muted mb-0 mt-1" style="font-size: 0.65rem;">Se marcarán {{ $unpaid_invoices_count }} factura(s) como pagadas automáticamente.</p>
                                    </div>
                                </div>
                            @endif

                            <button type="submit"
                                    class="btn btn-lg {{ $pay_pending_invoices ? 'btn-primary' : 'btn-success' }} w-100 py-3 fw-black text-uppercase tracking-widest shadow-lg transform transition hover:scale-105"
                                    @if(count($selected_packages) == 0) disabled @endif>
                                <i class="align-middle me-2" data-feather="{{ $pay_pending_invoices ? 'credit-card' : 'package' }}"></i>
                                {{ $pay_pending_invoices ? 'Cobrar y Entregar' : 'Procesar Entrega' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Operation Tips -->
            <div class="card bg-dark text-white shadow-lg border-0">
                <div class="card-body p-4">
                    <h5 class="text-white-50 small mb-4 uppercase font-black tracking-widest">Protocolo de Entrega</h5>
                    <div class="d-flex mb-3">
                        <div class="bg-success rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; min-width: 24px;">
                            <i data-feather="check" style="width: 14px; height: 14px;" class="text-white"></i>
                        </div>
                        <p class="small mb-0 text-white fw-bold">Verifica siempre la identidad del cliente o persona autorizada.</p>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="bg-success rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; min-width: 24px;">
                            <i data-feather="check" style="width: 14px; height: 14px;" class="text-white"></i>
                        </div>
                        <p class="small mb-0 text-white fw-bold">Confirma que todos los bultos físicos coincidan con el listado en pantalla.</p>
                    </div>
                    <div class="d-flex">
                        <div class="bg-danger rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; min-width: 24px;">
                            <i data-feather="dollar-sign" style="width: 14px; height: 14px;" class="text-white"></i>
                        </div>
                        <p class="small mb-0 text-white fw-bold">Si hay facturas pendientes, dirija al cliente a caja antes de entregar la carga.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
