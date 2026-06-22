<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="h3 mb-0 uppercase font-black tracking-tight text-dark">Integraciones y Pagos</h2>
            <p class="text-muted small">Configura tus pasarelas de pago y conecta {{ \App\Models\Tenant::current()?->name ?? config('app.name') }} con tu ecosistema digital.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message">
                <strong>¡Éxito!</strong> {{ session('message') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Payment Gateways -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 uppercase font-black small"><i class="align-middle me-2 text-primary" data-feather="credit-card"></i> Pasarelas de Pago</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form wire:submit.prevent="savePayments">
                        <!-- Stripe Section -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg" alt="Stripe" style="height: 25px;">
                                <div class="ms-3 badge bg-primary-light text-primary font-black xsmall uppercase">Tarjetas de Crédito</div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label xsmall font-black uppercase text-muted">Stripe Publishable Key</label>
                                    <input type="text" wire:model="stripe_key" class="form-control font-bold" placeholder="pk_test_...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label xsmall font-black uppercase text-muted">Stripe Secret Key</label>
                                    <input type="password" wire:model="stripe_secret" class="form-control font-bold" placeholder="sk_test_...">
                                </div>
                            </div>
                        </div>

                        <hr class="my-5">

                        <!-- PayPal Section -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" style="height: 25px;">
                                <div class="ms-3 badge bg-warning-light text-warning font-black xsmall uppercase">Pagos Express</div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label class="form-label xsmall font-black uppercase text-muted">Modo de Operación</label>
                                    <select wire:model="paypal_mode" class="form-select font-bold">
                                        <option value="sandbox">Sandbox (Pruebas)</option>
                                        <option value="live">Live (Producción)</option>
                                    </select>
                                </div>

                                @if($paypal_mode === 'sandbox')
                                    <div class="col-md-6">
                                        <label class="form-label xsmall font-black uppercase text-muted">PayPal Sandbox Client ID</label>
                                        <input type="text" wire:model="paypal_sandbox_client_id" class="form-control font-bold">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label xsmall font-black uppercase text-muted">PayPal Sandbox Secret</label>
                                        <input type="password" wire:model="paypal_sandbox_client_secret" class="form-control font-bold">
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <label class="form-label xsmall font-black uppercase text-muted">PayPal Live Client ID</label>
                                        <input type="text" wire:model="paypal_live_client_id" class="form-control font-bold">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label xsmall font-black uppercase text-muted">PayPal Live Secret</label>
                                        <input type="password" wire:model="paypal_live_client_secret" class="form-control font-bold">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-lg px-5 fw-black uppercase shadow-lg">
                                Guardar Configuración de Pagos <i class="align-middle ms-2" data-feather="save"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Widgets & Side Info -->
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 uppercase font-black small">Widgets para tu Web</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4 pb-4 border-bottom">
                        <h6 class="fw-black text-dark uppercase small mb-2"><i class="align-middle me-1 text-primary" data-feather="search"></i> Rastreo de Paquetes</h6>
                        <p class="text-muted xsmall mb-3">Inserta este código en tu sitio web principal.</p>
                        <div class="bg-dark rounded-3 p-3 position-relative">
                            <code class="text-info xsmall" style="font-size: 0.6rem;">
                                &lt;iframe src="{{ $baseUrl }}/tracking?embedded=true" width="100%" height="500px" style="border:none;"&gt;&lt;/iframe&gt;
                            </code>
                        </div>
                    </div>

                    <div>
                        <h6 class="fw-black text-dark uppercase small mb-2"><i class="align-middle me-1 text-warning" data-feather="percent"></i> Calculadora de Fletes</h6>
                        <p class="text-muted xsmall mb-3">Permite a tus clientes cotizar sus compras.</p>
                        <div class="bg-dark rounded-3 p-3 position-relative">
                            <code class="text-info xsmall" style="font-size: 0.6rem;">
                                &lt;iframe src="{{ $baseUrl }}/calculadora?embedded=true" width="100%" height="600px" style="border:none;"&gt;&lt;/iframe&gt;
                            </code>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-primary text-white border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="stat text-white bg-white bg-opacity-25 rounded-circle me-3">
                            <i data-feather="shield"></i>
                        </div>
                        <div>
                            <h6 class="fw-black text-white uppercase mb-1">Seguridad de Datos</h6>
                            <p class="text-white-50 xsmall mb-0">Tus llaves de API se encriptan antes de guardarse en nuestra base de datos para garantizar la máxima seguridad financiera.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
