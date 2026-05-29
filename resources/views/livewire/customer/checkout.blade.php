<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h4 mb-0 uppercase font-black tracking-tight text-dark">Finalizar Entrega</h1>
            <p class="text-muted xsmall mb-0">Selecciona cómo quieres recibir tu carga y cómo deseas pagar.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Step 1: Delivery Method -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 uppercase font-black small"><span class="badge bg-primary me-2">1</span> Método de Entrega</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="card-selection border rounded-3 p-3 d-block cursor-pointer transition-all {{ $delivery_method === 'pickup' ? 'border-primary bg-primary bg-opacity-10' : 'bg-white' }}">
                                <input type="radio" wire:model.live="delivery_method" value="pickup" class="d-none">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-white shadow-sm p-2 me-3">
                                        <i data-feather="home" class="text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-black text-dark text-uppercase small">Retiro en Sucursal</div>
                                        <div class="xsmall text-muted">Retira gratis en nuestra oficina.</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="card-selection border rounded-3 p-3 d-block cursor-pointer transition-all {{ $delivery_method === 'home_delivery' ? 'border-primary bg-primary bg-opacity-10' : 'bg-white' }}">
                                <input type="radio" wire:model.live="delivery_method" value="home_delivery" class="d-none">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-white shadow-sm p-2 me-3">
                                        <i data-feather="map-pin" class="text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-black text-dark text-uppercase small">Delivery a Domicilio</div>
                                        <div class="xsmall text-muted">Recibe en tu casa u oficina.</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Payment Method -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 uppercase font-black small"><span class="badge bg-primary me-2">2</span> Método de Pago</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="card-selection border rounded-3 p-3 d-block cursor-pointer h-100 {{ $payment_method === 'card' ? 'border-primary bg-primary bg-opacity-10' : 'bg-white' }}">
                                <input type="radio" wire:model.live="payment_method" value="card" class="d-none">
                                <div class="text-center">
                                    <i data-feather="credit-card" class="mb-2 {{ $payment_method === 'card' ? 'text-primary' : 'text-muted' }}"></i>
                                    <div class="fw-black text-dark text-uppercase xsmall">Tarjeta</div>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="card-selection border rounded-3 p-3 d-block cursor-pointer h-100 {{ $payment_method === 'yappy' ? 'border-primary bg-primary bg-opacity-10' : 'bg-white' }}">
                                <input type="radio" wire:model.live="payment_method" value="yappy" class="d-none">
                                <div class="text-center">
                                    <i data-feather="smartphone" class="mb-2 {{ $payment_method === 'yappy' ? 'text-primary' : 'text-muted' }}"></i>
                                    <div class="fw-black text-dark text-uppercase xsmall">Yappy</div>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="card-selection border rounded-3 p-3 d-block cursor-pointer h-100 {{ $payment_method === 'ach' ? 'border-primary bg-primary bg-opacity-10' : 'bg-white' }}">
                                <input type="radio" wire:model.live="payment_method" value="ach" class="d-none">
                                <div class="text-center">
                                    <i data-feather="repeat" class="mb-2 {{ $payment_method === 'ach' ? 'text-primary' : 'text-muted' }}"></i>
                                    <div class="fw-black text-dark text-uppercase xsmall">ACH / Transf.</div>
                                </div>
                            </label>
                        </div>
                        @if($delivery_method === 'home_delivery')
                        <div class="col-md-4">
                            <label class="card-selection border rounded-3 p-3 d-block cursor-pointer h-100 {{ $payment_method === 'cod' ? 'border-primary bg-primary bg-opacity-10' : 'bg-white' }}">
                                <input type="radio" wire:model.live="payment_method" value="cod" class="d-none">
                                <div class="text-center">
                                    <i data-feather="dollar-sign" class="mb-2 {{ $payment_method === 'cod' ? 'text-primary' : 'text-muted' }}"></i>
                                    <div class="fw-black text-dark text-uppercase xsmall">Contra Entrega</div>
                                </div>
                            </label>
                        </div>
                        @endif
                    </div>

                    @if(in_array($payment_method, ['yappy', 'ach']))
                        <div class="bg-light p-4 rounded-3 animate__animated animate__fadeIn">
                            <h6 class="fw-black text-uppercase small mb-3">Información de Pago</h6>
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <p class="xsmall text-muted mb-1 font-bold uppercase">Datos de la Cuenta:</p>
                                    <div class="bg-white p-2 rounded border small">
                                        <strong>Banco General</strong><br>
                                        Cuenta Corriente: 03-01-01-223344-5<br>
                                        Nombre: LogiSaaS Corp.
                                    </div>
                                </div>
                                <div class="col-sm-6 mt-3 mt-sm-0">
                                    <p class="xsmall text-muted mb-1 font-bold uppercase">Yappy:</p>
                                    <div class="bg-white p-2 rounded border small">
                                        <strong>@logisaas</strong><br>
                                        Tel: 6655-4433
                                    </div>
                                </div>
                            </div>

                            <label class="form-label font-bold small text-uppercase">Subir Comprobante (Captura/PDF)</label>
                            <input type="file" wire:model="payment_proof" class="form-control">
                            <div wire:loading wire:target="payment_proof" class="text-primary xsmall mt-1">Cargando archivo...</div>
                            @error('payment_proof') <span class="text-danger xsmall d-block">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    @if($payment_method === 'cod')
                        <div class="alert alert-warning border-0 animate__animated animate__fadeIn">
                            <i data-feather="info" class="me-2"></i>
                            <span class="xsmall">Pagarás al motorizado al momento de recibir tus paquetes. Aceptamos efectivo o Yappy en el sitio.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="card-title text-white mb-0 uppercase font-black small">Resumen de Pago</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="text-muted xsmall uppercase font-bold d-block mb-2">Factura No.</label>
                        <div class="fw-black h5">{{ $invoice->number }}</div>
                    </div>

                    <div class="border-top pt-3 mb-4">
                        <label class="text-muted xsmall uppercase font-bold d-block mb-3">Ítems Incluidos</label>
                        @foreach($invoice->items as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="xsmall text-dark font-bold">{{ $item->package ? $item->package->tracking_number : $item->description }}</span>
                                <span class="xsmall fw-black text-dark">${{ number_format($item->total, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-top pt-3 mb-4">
                        <div class="d-flex justify-content-between h4 mb-0">
                            <span class="fw-black text-dark">TOTAL:</span>
                            <span class="fw-black text-primary">${{ number_format($invoice->total, 2) }}</span>
                        </div>
                    </div>

                    <button wire:click="processCheckout"
                            wire:loading.attr="disabled"
                            class="btn btn-primary w-100 py-3 fw-black text-uppercase shadow-sm">
                        <span wire:loading.remove>CONFIRMAR Y FINALIZAR</span>
                        <span wire:loading>PROCESANDO...</span>
                    </button>

                    <p class="text-center xsmall text-muted mt-3 mb-0 italic">
                        Al confirmar, aceptas nuestras políticas de entrega y manejo de carga.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card-selection {
            border: 2px solid #f1f1f1;
        }
        .card-selection:hover {
            border-color: #3b7ddd;
        }
        .cursor-pointer { cursor: pointer; }
    </style>
</div>
