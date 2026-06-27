<div>

    {{-- ══════════════════════════════════════════
        METRICS BAR
    ══════════════════════════════════════════ --}}
    <div class="row g-3 mb-5">
        {{-- Recibidos Hoy --}}
        <div class="col-6 col-md-3">
            <a href="{{ route('logistics.inventory', ['filter_status' => 'received']) }}"
               class="d-block text-decoration-none h-100">
                <div class="card border-0 h-100 overflow-hidden position-relative"
                     style="background: linear-gradient(135deg,#2563eb 0%,#4f46e5 100%); border-radius: 1rem;">
                    <div class="card-body p-4 text-white position-relative z-1">
                        <p class="xsmall text-uppercase font-black opacity-75 mb-2 tracking-widest">Recibidos Hoy</p>
                        <h2 class="fw-black mb-0" style="font-size:2.5rem; letter-spacing:-1px;">{{ $stats['received_today'] }}</h2>
                        <p class="xsmall opacity-50 mt-1 mb-0">paquetes</p>
                    </div>
                    <div class="position-absolute" style="right:-20px;bottom:-20px;width:80px;height:80px;background:rgba(255,255,255,.08);border-radius:50%;"></div>
                </div>
            </a>
        </div>

        {{-- Peso Ingresado --}}
        <div class="col-6 col-md-3">
            <div class="card border-0 h-100 overflow-hidden position-relative"
                 style="background: linear-gradient(135deg,#0891b2 0%,#06b6d4 100%); border-radius: 1rem;">
                <div class="card-body p-4 text-white position-relative z-1">
                    <p class="xsmall text-uppercase font-black opacity-75 mb-2 tracking-widest">Peso Ingresado</p>
                    <h2 class="fw-black mb-0" style="font-size:2rem; letter-spacing:-1px;">{{ number_format($stats['weight_today'], 1) }}</h2>
                    <p class="xsmall opacity-50 mt-1 mb-0">libras hoy</p>
                </div>
                <div class="position-absolute" style="right:-20px;bottom:-20px;width:80px;height:80px;background:rgba(255,255,255,.08);border-radius:50%;"></div>
            </div>
        </div>

        {{-- Últimos Paquetes --}}
        <div class="col-12 col-md-6">
            <div class="card border-0 h-100"
                 style="background: linear-gradient(135deg,#0f172a 0%,#1e293b 100%); border-radius: 1rem;">
                <div class="card-body p-4 text-white">
                    <p class="xsmall text-uppercase font-black opacity-50 mb-3 tracking-widest">Últimos Registros</p>
                    <div class="d-flex flex-wrap gap-2">
                        @forelse($stats['last_packages']->take(3) as $lp)
                            <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-pill"
                                 style="background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.12); font-size:.72rem;">
                                <span class="rounded-circle bg-blue-500 d-inline-block" style="width:7px;height:7px;background:#3b82f6;flex-shrink:0;"></span>
                                <span class="fw-black text-white font-monospace">{{ $lp->tracking_number }}</span>
                            </div>
                        @empty
                            <span class="opacity-40 small">Sin registros recientes</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
        SUCCESS ALERT
    ══════════════════════════════════════════ --}}
    @if (session()->has('message'))
        <div class="mb-5 p-4 d-flex align-items-center justify-content-between gap-4"
             style="background:linear-gradient(135deg,#dcfce7,#f0fdf4); border:1.5px solid #86efac; border-radius:1rem;">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-circle bg-success text-white flex-shrink-0"
                     style="width:42px;height:42px;">
                    <i data-feather="check" style="width:20px;height:20px;"></i>
                </div>
                <div>
                    <p class="fw-black text-success mb-0" style="font-size:.9rem;">¡Paquete registrado con éxito!</p>
                    <p class="text-success small mb-0 opacity-75">{{ session('message') }}</p>
                </div>
            </div>
            @if($last_package_id)
                <a href="{{ route('logistics.label', $last_package_id) }}" target="_blank"
                   class="btn btn-success fw-black px-4 py-2 d-flex align-items-center gap-2 flex-shrink-0 shadow-sm"
                   style="border-radius:.75rem; white-space:nowrap;">
                    <i data-feather="printer" style="width:16px;height:16px;"></i>
                    Imprimir Etiqueta
                </a>
            @endif
        </div>
    @endif

    {{-- ══════════════════════════════════════════
        MAIN LAYOUT
    ══════════════════════════════════════════ --}}
    <div class="row g-4">

        {{-- ─── FORM COLUMN ─── --}}
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius:1.25rem;">

                {{-- Card Header --}}
                <div class="card-header border-0 px-5 pt-5 pb-4"
                     style="background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-xl text-white flex-shrink-0"
                             style="width:42px;height:42px;background:linear-gradient(135deg,#2563eb,#4f46e5);border-radius:.75rem;">
                            <i data-feather="package" style="width:20px;height:20px;"></i>
                        </div>
                        <div>
                            <h5 class="fw-black mb-0 text-dark" style="letter-spacing:-.3px;">Registro de Ingreso</h5>
                            <p class="small text-muted mb-0">Completa los campos para registrar el paquete</p>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="save">
                    <div class="card-body px-5 py-4">

                        {{-- SECTION: Identificación --}}
                        <p class="xsmall text-uppercase font-black text-muted tracking-widest mb-3"
                           style="letter-spacing:.15em;">① Identificación del Paquete</p>

                        <div class="row g-4 mb-5">
                            {{-- Tracking --}}
                            <div class="col-md-4">
                                <label class="form-label small fw-black text-dark mb-2">
                                    Nº Tracking
                                    <span class="text-danger ms-1">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"
                                          style="border-radius:.75rem 0 0 .75rem;border:1.5px solid #e2e8f0;border-right:0;">
                                        <i data-feather="barcode" style="width:16px;height:16px;color:#94a3b8;"></i>
                                    </span>
                                    <input type="text" wire:model="tracking_number"
                                           class="form-control fw-black border-start-0 @error('tracking_number') is-invalid @enderror"
                                           style="font-size:1.1rem;border:1.5px solid #e2e8f0;border-left:0;border-radius:0 .75rem .75rem 0;background:#fafafa;padding:.8rem 1rem;"
                                           placeholder="Escanear..." autofocus>
                                </div>
                                @error('tracking_number')
                                    <div class="text-danger small mt-1 fw-bold">
                                        <i data-feather="alert-circle" style="width:12px;"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Service Type --}}
                            <div class="col-md-3">
                                <label class="form-label small fw-black text-dark mb-2">
                                    Servicio
                                    <span class="text-danger ms-1">*</span>
                                </label>
                                <select wire:model="service_type" class="form-select fw-black" style="font-size:1.1rem;border:1.5px solid #e2e8f0;border-radius:.75rem;padding:.8rem 1rem;background:#fafafa;">
                                    <option value="air">Aéreo</option>
                                    <option value="maritime">Marítimo</option>
                                </select>
                            </div>

                            {{-- Buscar Cliente --}}
                            <div class="col-md-5 position-relative" x-data="{ open: true }">
                                <label class="form-label small fw-black text-dark mb-2">
                                    Cliente / Casillero
                                    <span class="text-danger ms-1">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"
                                          style="border-radius:.75rem 0 0 .75rem;border:1.5px solid #e2e8f0;border-right:0;">
                                        <i data-feather="user" style="width:16px;height:16px;color:#94a3b8;"></i>
                                    </span>
                                    <input type="text" wire:model.live="customer_search"
                                           @input="open = true"
                                           class="form-control fw-bold text-primary border-start-0 @error('box_number') is-invalid @enderror"
                                           style="font-size:1rem;border:1.5px solid #e2e8f0;border-left:0;border-radius:0 .75rem .75rem 0;background:#fafafa;padding:.8rem 1rem;"
                                           placeholder="Nombre, Email o PTY-...">
                                </div>

                                {{-- Dropdown resultados --}}
                                @if(!empty($search_results))
                                    <div x-show="open"
                                         class="position-absolute w-100 bg-white mt-1 overflow-hidden"
                                         style="z-index:500;border-radius:.875rem;border:1.5px solid #e2e8f0;box-shadow:0 20px 40px rgba(0,0,0,.12);left:0;right:0;top:100%;">
                                        @foreach($search_results as $result)
                                            <button type="button"
                                                    wire:click="selectCustomer({{ $result->id }})"
                                                    @click="open = false"
                                                    class="btn btn-white w-100 text-start border-0 border-bottom d-flex align-items-center justify-content-between p-3"
                                                    style="border-radius:0;">
                                                <div>
                                                    <div class="fw-black text-dark">{{ $result->user->name }}</div>
                                                    <div class="xsmall text-muted">{{ $result->user->email }}</div>
                                                </div>
                                                <span class="badge fw-black text-uppercase px-3"
                                                      style="background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#1d4ed8;font-size:.65rem;">
                                                    {{ $result->box_number }}
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Recientes --}}
                                @if($recentCustomers->count() > 0)
                                    <div class="mt-2 d-flex flex-wrap gap-1 align-items-center">
                                        <span class="xsmall text-muted text-uppercase font-black" style="font-size:.6rem;letter-spacing:.1em;">Recientes:</span>
                                        @foreach($recentCustomers as $recent)
                                            <button type="button"
                                                    wire:click="selectCustomer({{ $recent->id }})"
                                                    class="btn btn-sm fw-black text-uppercase px-2 py-0"
                                                    style="font-size:.65rem;border:1.5px solid #bfdbfe;color:#1d4ed8;background:#eff6ff;border-radius:999px;">
                                                <span class="d-inline-block rounded-circle bg-success me-1" style="width:5px;height:5px;"></span>
                                                {{ explode(' ', $recent->user->name)[0] }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endif

                                @error('box_number')
                                    <div class="text-danger small mt-1 fw-bold">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- SECTION: Datos Físicos --}}
                        <p class="xsmall text-uppercase font-black text-muted tracking-widest mb-3"
                           style="letter-spacing:.15em;">② Datos Físicos</p>

                        <div class="row g-4 mb-5">
                            {{-- Peso y Bodega --}}
                            <div class="col-md-6">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label small fw-black text-dark mb-2">Peso Real (lbs)</label>
                                        <input type="number" step="0.01" wire:model="weight"
                                               class="form-control fw-black text-center @error('weight') is-invalid @enderror"
                                               style="font-size:1.3rem;border:1.5px solid #e2e8f0;border-radius:.75rem;padding:.75rem;background:#fafafa;">
                                        @error('weight')
                                            <div class="text-danger xsmall mt-1 fw-bold">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-black text-dark mb-2">Bodega</label>
                                        <select wire:model="warehouse_id"
                                                class="form-select fw-bold @error('warehouse_id') is-invalid @enderror"
                                                style="border:1.5px solid #e2e8f0;border-radius:.75rem;padding:.75rem;background:#fafafa;">
                                            <option value="">Seleccione...</option>
                                            @foreach($warehouses as $wh)
                                                <option value="{{ $wh->id }}">{{ $wh->name }} ({{ $wh->code }})</option>
                                            @endforeach
                                        </select>
                                        @error('warehouse_id')
                                            <div class="text-danger xsmall mt-1 fw-bold">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Dimensiones --}}
                            <div class="col-md-6">
                                <div class="p-4 h-100"
                                     style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe);border:1.5px solid #bae6fd;border-radius:.875rem;">
                                    <label class="form-label xsmall fw-black text-uppercase mb-3 d-block"
                                           style="color:#0369a1;letter-spacing:.1em;">
                                        <i data-feather="maximize-2" style="width:12px;height:12px;" class="me-1"></i>
                                        Dimensiones Volumétricas (Pulgadas)
                                    </label>
                                    <div class="row g-2 mb-2">
                                        <div class="col-4">
                                            <input type="number" placeholder="Largo"
                                                   wire:model.live="length"
                                                   class="form-control text-center fw-bold"
                                                   style="border:1.5px solid #7dd3fc;border-radius:.5rem;background:white;padding:.5rem;">
                                            <p class="xsmall text-center text-muted mt-1 mb-0" style="font-size:.6rem;">LARGO</p>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" placeholder="Ancho"
                                                   wire:model.live="width"
                                                   class="form-control text-center fw-bold"
                                                   style="border:1.5px solid #7dd3fc;border-radius:.5rem;background:white;padding:.5rem;">
                                            <p class="xsmall text-center text-muted mt-1 mb-0" style="font-size:.6rem;">ANCHO</p>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" placeholder="Alto"
                                                   wire:model.live="height"
                                                   class="form-control text-center fw-bold"
                                                   style="border:1.5px solid #7dd3fc;border-radius:.5rem;background:white;padding:.5rem;">
                                            <p class="xsmall text-center text-muted mt-1 mb-0" style="font-size:.6rem;">ALTO</p>
                                        </div>
                                    </div>
                                    @if($volumetric_weight > 0)
                                        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-info-subtle mt-2">
                                            <span class="xsmall fw-black text-uppercase" style="color:#0369a1;font-size:.65rem;">Peso Volumétrico:</span>
                                            <span class="fw-black" style="font-size:1.1rem;color:#0369a1;">{{ $volumetric_weight }} <span style="font-size:.7rem;">vlb</span></span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- SECTION: Descripción --}}
                        <p class="xsmall text-uppercase font-black text-muted tracking-widest mb-3"
                           style="letter-spacing:.15em;">③ Descripción del Contenido</p>
                        <textarea wire:model="description" rows="3"
                                  placeholder="¿Qué contiene el paquete? (Opcional)"
                                  class="form-control"
                                  style="border:1.5px solid #e2e8f0;border-radius:.75rem;background:#fafafa;resize:none;padding:1rem;"></textarea>
                    </div>

                    {{-- Card Footer --}}
                    <div class="card-footer border-0 px-5 py-4 d-flex justify-content-between align-items-center"
                         style="background:#f8fafc;">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" wire:model="auto_invoice" id="autoInvoiceSwitch" style="width:2.4em;height:1.3em;">
                            <label class="form-check-label fw-bold text-muted small ms-2" for="autoInvoiceSwitch">
                                Generar factura automáticamente <span class="text-primary fw-black">($2.50/lb)</span>
                            </label>
                        </div>

                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="btn btn-primary fw-black px-5 py-3 d-flex align-items-center gap-2 shadow"
                                style="background:linear-gradient(135deg,#2563eb,#4f46e5);border:0;border-radius:.875rem;font-size:.9rem;letter-spacing:.05em;">
                            <span wire:loading.remove wire:target="save" class="d-flex align-items-center gap-2">
                                <i data-feather="check-circle" style="width:18px;height:18px;"></i>
                                CONFIRMAR INGRESO
                            </span>
                            <span wire:loading wire:target="save" class="d-flex align-items-center gap-2">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                                PROCESANDO...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ─── SIDEBAR COLUMN ─── --}}
        <div class="col-12 col-lg-4 d-flex flex-column gap-4">

            {{-- Customer Info Card --}}
            <div class="card border-0 shadow-sm overflow-hidden flex-fill" style="border-radius:1.25rem;">
                @if($found_customer)
                    <div style="height:4px;background:linear-gradient(90deg,#2563eb,#4f46e5,#7c3aed);"></div>
                    <div class="card-body p-5 text-center">
                        {{-- Avatar --}}
                        <div class="d-flex align-items-center justify-content-center mx-auto mb-4 fw-black text-white"
                             style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#4f46e5);font-size:1.8rem;box-shadow:0 8px 24px rgba(37,99,235,.3);">
                            {{ substr($found_customer->user->name, 0, 1) }}
                        </div>

                        <h4 class="fw-black text-dark mb-1" style="letter-spacing:-.3px;">{{ $found_customer->user->name }}</h4>
                        <div class="badge fw-black text-uppercase px-3 py-2 mb-4"
                             style="background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#1d4ed8;font-size:.75rem;border-radius:.5rem;">
                            {{ $found_customer->box_number }}
                        </div>

                        {{-- Stats --}}
                        <div class="row g-0 border-top pt-4 mt-2">
                            <div class="col-6 border-end text-center pb-2">
                                <p class="xsmall text-uppercase fw-black text-muted mb-1" style="font-size:.6rem;letter-spacing:.1em;">Puntos</p>
                                <p class="fw-black mb-0 d-flex align-items-center justify-content-center gap-1"
                                   style="font-size:1.3rem;">
                                    <i data-feather="star" class="text-warning" style="width:14px;height:14px;"></i>
                                    {{ number_format($found_customer->points) }}
                                </p>
                            </div>
                            <div class="col-6 text-center pb-2">
                                <p class="xsmall text-uppercase fw-black text-muted mb-1" style="font-size:.6rem;letter-spacing:.1em;">Saldo</p>
                                <p class="fw-black mb-0 {{ $found_customer->balance > 0 ? 'text-danger' : 'text-success' }}"
                                   style="font-size:1.3rem;">
                                    ${{ number_format($found_customer->balance, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card-body p-5 text-center d-flex flex-column align-items-center justify-content-center"
                         style="min-height:260px;">
                        <div class="d-flex align-items-center justify-content-center rounded-circle mb-4"
                             style="width:70px;height:70px;background:#f1f5f9;">
                            <i data-feather="user" style="width:30px;height:30px;color:#94a3b8;"></i>
                        </div>
                        <p class="xsmall fw-black text-uppercase text-muted mb-0 tracking-widest" style="letter-spacing:.12em;">
                            Esperando selección<br>de cliente
                        </p>
                    </div>
                @endif
            </div>

            {{-- Tips Card --}}
            <div class="card border-0 overflow-hidden" style="border-radius:1.25rem;background:linear-gradient(135deg,#0f172a,#1e293b);">
                <div class="card-body p-4">
                    <p class="xsmall text-uppercase fw-black mb-4 tracking-widest" style="color:rgba(255,255,255,.35);letter-spacing:.15em;">
                        Tips de Operación
                    </p>
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                             style="width:26px;height:26px;background:rgba(34,197,94,.15);margin-top:2px;">
                            <i data-feather="zap" style="width:13px;height:13px;color:#4ade80;"></i>
                        </div>
                        <p class="small mb-0" style="color:rgba(255,255,255,.55);line-height:1.5;">
                            Escanea el código de barras directamente para evitar errores de captura manual.
                        </p>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                             style="width:26px;height:26px;background:rgba(34,197,94,.15);margin-top:2px;">
                            <i data-feather="maximize-2" style="width:13px;height:13px;color:#4ade80;"></i>
                        </div>
                        <p class="small mb-0" style="color:rgba(255,255,255,.55);line-height:1.5;">
                            Ingresa dimensiones para paquetes voluminosos — el peso vlb se calcula automáticamente.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
