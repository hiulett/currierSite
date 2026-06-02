<div class="container-fluid p-0">

    {{-- ══════════════════════════════════════════
        MODE SELECTOR HEADER
    ══════════════════════════════════════════ --}}
    @if(!$isModal)
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 px-1">
            <div>
                <h4 class="fw-black mb-1 text-dark d-flex align-items-center gap-2" style="letter-spacing:-.3px;">
                    <span class="d-flex align-items-center justify-content-center rounded-lg text-white flex-shrink-0"
                          style="width:34px;height:34px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);border-radius:.625rem;">
                        <i data-feather="zap" style="width:16px;height:16px;"></i>
                    </span>
                    Smart Reception Hub
                </h4>
                <p class="text-muted small mb-0 ms-5 ps-1">Registro rápido y automatizado de carga</p>
            </div>
            <div class="d-inline-flex p-1 gap-1" style="background:#f1f5f9;border-radius:.75rem;">
                <button wire:click="$set('mode', 'manual')"
                        class="btn btn-sm fw-black px-4 py-2 d-flex align-items-center gap-2 transition-all"
                        style="border-radius:.625rem;font-size:.78rem;letter-spacing:.03em;border:0;{{ $mode == 'manual' ? 'background:white;color:#0f172a;box-shadow:0 2px 4px rgba(0,0,0,.06);' : 'background:transparent;color:#64748b;' }}">
                    <i data-feather="edit-3" style="width:14px;height:14px;"></i>
                    MANUAL
                </button>
                <button wire:click="$set('mode', 'ocr')"
                        class="btn btn-sm fw-black px-4 py-2 d-flex align-items-center gap-2 transition-all"
                        style="border-radius:.625rem;font-size:.78rem;letter-spacing:.03em;border:0;{{ $mode == 'ocr' ? 'background:white;color:#0f172a;box-shadow:0 2px 4px rgba(0,0,0,.06);' : 'background:transparent;color:#64748b;' }}">
                    <i data-feather="cpu" style="width:14px;height:14px;"></i>
                    FACTURA OCR
                </button>
            </div>
        </div>
    @else
        <div class="d-flex justify-content-center mb-4">
            <div class="d-inline-flex p-1 gap-1" style="background:#e2e8f0;border-radius:.75rem;">
                <button wire:click="$set('mode', 'manual')"
                        class="btn btn-sm fw-black px-4 py-2 d-flex align-items-center gap-2 transition-all"
                        style="border-radius:.625rem;font-size:.78rem;letter-spacing:.03em;border:0;{{ $mode == 'manual' ? 'background:white;color:#0f172a;box-shadow:0 2px 4px rgba(0,0,0,.06);' : 'background:transparent;color:#64748b;' }}">
                    <i data-feather="edit-3" style="width:14px;height:14px;"></i>
                    REGISTRO MANUAL
                </button>
                <button wire:click="$set('mode', 'ocr')"
                        class="btn btn-sm fw-black px-4 py-2 d-flex align-items-center gap-2 transition-all"
                        style="border-radius:.625rem;font-size:.78rem;letter-spacing:.03em;border:0;{{ $mode == 'ocr' ? 'background:white;color:#0f172a;box-shadow:0 2px 4px rgba(0,0,0,.06);' : 'background:transparent;color:#64748b;' }}">
                    <i data-feather="cpu" style="width:14px;height:14px;"></i>
                    FACTURA OCR
                </button>
            </div>
        </div>
    @endif

    <div class="row g-4">
        {{-- ─── LEFT COLUMN: Action Panel ─── --}}
        <div class="{{ $isModal ? 'col-lg-5' : 'col-xl-5 col-xxl-4' }}">
            @if($mode == 'manual')
                {{-- Manual Entry Form --}}
                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius:1rem; background: white;">
                    <div class="px-4 py-3" style="background:linear-gradient(135deg,#f8fafc,#f1f5f9); border-bottom: 1px solid #e2e8f0;">
                        <p class="xsmall text-uppercase fw-black text-primary mb-0" style="letter-spacing:.15em; font-size: 0.75rem;">
                            <i data-feather="edit-3" class="me-1 align-middle" style="width:14px; height:14px;"></i> Ingreso Individual
                        </p>
                    </div>

                    <div class="card-body p-4">
                        @if (session()->has('message'))
                            <div class="mb-4 p-3 d-flex align-items-center gap-2"
                                 style="background:linear-gradient(135deg,#dcfce7,#f0fdf4);border:1.5px solid #86efac;border-radius:.75rem;">
                                <i data-feather="check-circle" style="width:16px;height:16px;color:#16a34a;" class="flex-shrink-0"></i>
                                <span class="small fw-bold text-success">{{ session('message') }}</span>
                            </div>
                        @endif

                        <form wire:submit.prevent="saveManual">
                            {{-- Tracking --}}
                            <div class="mb-3">
                                <label class="form-label xsmall fw-black text-uppercase text-muted" style="letter-spacing:.08em;">Nº de Tracking</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="border-radius:.625rem 0 0 .625rem; border:1.5px solid #e2e8f0; border-right:0;">
                                        <i data-feather="barcode" style="width:16px;height:16px;color:#64748b;"></i>
                                    </span>
                                    <input type="text" wire:model="tracking_number"
                                           class="form-control fw-black border-start-0 @error('tracking_number') is-invalid @enderror"
                                           style="font-size:1.05rem;border:1.5px solid #e2e8f0;border-left:0;border-radius:0 .625rem .625rem 0;background:#fafafa;color:#1d4ed8;padding:.7rem 1rem;"
                                           placeholder="Escanee o digite tracking..." autofocus>
                                </div>
                                @error('tracking_number') <div class="text-danger xsmall fw-bold mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Customer Search --}}
                            <div class="mb-3 position-relative">
                                <label class="form-label xsmall fw-black text-uppercase text-muted" style="letter-spacing:.08em;">Cliente</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="border-radius:.625rem 0 0 .625rem; border:1.5px solid #e2e8f0; border-right:0;">
                                        <i data-feather="user" style="width:16px;height:16px;color:#64748b;"></i>
                                    </span>
                                    <input type="text" wire:model.live="customer_search"
                                           class="form-control border-start-0 @error('box_number') is-invalid @enderror"
                                           style="border:1.5px solid #e2e8f0;border-left:0;border-radius:0 .625rem .625rem 0;background:#fafafa;padding:.7rem 1rem;"
                                           placeholder="Buscar por Nombre o Box...">
                                </div>
                                @error('box_number') <div class="text-danger xsmall fw-bold mt-1">{{ $message }}</div> @enderror

                                @if(!empty($search_results))
                                    <div class="position-absolute w-100 bg-white overflow-hidden"
                                         style="z-index:1000;border-radius:.625rem;border:1.5px solid #e2e8f0;box-shadow:0 12px 24px rgba(0,0,0,.12);margin-top:4px;">
                                        @foreach($search_results as $result)
                                            <button type="button"
                                                    wire:click="selectCustomer({{ $result->id }})"
                                                    class="w-100 text-start p-3 border-0 border-bottom d-flex align-items-center justify-content-between btn btn-white transition-all hover:bg-light"
                                                    style="border-radius:0;">
                                                <span class="fw-black text-dark" style="font-size:0.85rem;">{{ $result->box_number }}</span>
                                                <span class="small text-muted font-bold">{{ $result->user->name }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Customer Info Widget if found --}}
                                @if($found_customer)
                                    <div class="mt-3 p-3 rounded-3 border d-flex align-items-center justify-content-between animate__animated animate__fadeIn" 
                                         style="background: #f8fafc; border-color: #e2e8f0 !important;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center font-bold" 
                                                 style="width: 34px; height: 34px; font-size: 0.85rem; background: linear-gradient(135deg,#3b82f6,#1d4ed8);">
                                                {{ substr($found_customer->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-black text-dark small" style="line-height:1.2;">{{ $found_customer->user->name }}</div>
                                                <div class="xsmall text-primary font-black uppercase" style="font-size:0.65rem;">{{ $found_customer->box_number }}</div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="xsmall text-muted font-bold" style="font-size: 0.6rem;">Saldo</div>
                                            <div class="small fw-black {{ $found_customer->balance > 0 ? 'text-danger' : 'text-success' }}">
                                                ${{ number_format($found_customer->balance, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Weight & Warehouse --}}
                            <div class="row g-2 mb-4">
                                <div class="col-6">
                                    <label class="form-label xsmall fw-black text-uppercase text-muted" style="letter-spacing:.08em;">Peso (lb)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" wire:model="weight"
                                               class="form-control fw-black text-center @error('weight') is-invalid @enderror"
                                               style="border:1.5px solid #e2e8f0;border-radius:.625rem;padding:.7rem 1rem;background:#fafafa;">
                                    </div>
                                    @error('weight') <div class="text-danger xsmall fw-bold mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-6">
                                    <label class="form-label xsmall fw-black text-uppercase text-muted" style="letter-spacing:.08em;">Bodega</label>
                                    <select wire:model="warehouse_id"
                                            class="form-select fw-bold @error('warehouse_id') is-invalid @enderror"
                                            style="border:1.5px solid #e2e8f0;border-radius:.625rem;padding:.7rem 1rem;background:#fafafa;">
                                        @foreach($warehouses as $wh)
                                            <option value="{{ $wh->id }}">{{ $wh->code }}</option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_id') <div class="text-danger xsmall fw-bold mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Submit --}}
                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    class="btn btn-primary w-100 fw-black py-3 d-flex align-items-center justify-content-center gap-2 transition-all"
                                    style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);border:0;border-radius:.625rem;font-size:.85rem;letter-spacing:.05em;box-shadow:0 4px 14px rgba(59,130,246,.25);">
                                <span wire:loading.remove wire:target="saveManual" class="d-flex align-items-center gap-2">
                                    <i data-feather="check-circle" style="width:16px;height:16px;"></i>
                                    REGISTRAR PAQUETE
                                </span>
                                <span wire:loading wire:target="saveManual" class="d-flex align-items-center gap-2">
                                    <span class="spinner-border spinner-border-sm" role="status" style="width: 14px; height: 14px;"></span>
                                    PROCESANDO...
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Recent Activity --}}
            <div class="card border-0 mt-4 overflow-hidden shadow-sm" style="border-radius:1rem; background: white;">
                <div class="px-4 py-3" style="background:linear-gradient(135deg,#f8fafc,#f1f5f9); border-bottom: 1px solid #e2e8f0;">
                    <p class="xsmall text-uppercase fw-black text-muted mb-0 d-flex align-items-center gap-2" style="letter-spacing:.15em; font-size: 0.75rem;">
                        <i data-feather="clock" style="width:14px; height:14px;"></i> Actividad Reciente
                    </p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <tbody>
                                @forelse($lastPackages as $lp)
                                    <tr style="border-bottom:1px solid #f1f5f9;" class="align-middle">
                                        <td class="ps-4 py-3">
                                            <div class="fw-bold font-monospace text-dark" style="font-size:.85rem;">{{ $lp->tracking_number }}</div>
                                            <div class="xsmall text-muted mt-1">
                                                @if($lp->customer)
                                                    <span class="badge fw-black" style="background:#eff6ff; color:#1d4ed8; font-size: 0.65rem;">{{ $lp->customer->box_number }}</span>
                                                @else
                                                    <span class="badge bg-warning-light text-warning fw-black" style="font-size: 0.65rem;">SIN ASIGNAR</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-end pe-4 py-3">
                                            <span class="badge fw-black px-2 py-1"
                                                  style="background:#f1f5f9;color:#475569;border-radius:.4rem;font-size:.7rem;">
                                                {{ $lp->weight }} lb
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-4 text-muted small italic">Sin registros recientes</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── RIGHT COLUMN: OCR / Placeholder ─── --}}
        <div class="{{ $isModal ? 'col-lg-7' : 'col-xl-7 col-xxl-8' }}">
            @if($mode == 'ocr')
                <div class="card border-0 overflow-hidden shadow-sm" style="border-radius:1rem; background: white;">

                    {{-- OCR Header --}}
                    <div class="px-4 py-3 d-flex flex-wrap align-items-center justify-content-between gap-3"
                         style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border-bottom:1px solid #e2e8f0;">
                        <div>
                            <p class="xsmall text-uppercase fw-black text-primary mb-1" style="letter-spacing:.15em; font-size: 0.75rem;">
                                <i data-feather="cpu" class="me-1 align-middle" style="width:14px; height:14px;"></i> Lote Esperado de Factura
                            </p>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="xsmall fw-black text-muted text-uppercase" style="letter-spacing:.08em; font-size: 0.65rem;">Nº Factura:</span>
                                <input type="text" wire:model="invoiceNumber"
                                       class="form-control form-control-sm fw-black text-primary d-inline"
                                       style="width:140px;border:1.5px solid #e2e8f0;border-radius:.5rem;background:white;padding:.35rem .6rem; font-size: 0.8rem;"
                                       placeholder="Ej: 1922984">
                            </div>
                        </div>
                        @if(!empty($ocrResults))
                            <button wire:click="saveAllOCRItems"
                                    wire:loading.attr="disabled"
                                    class="btn btn-sm fw-black px-4 py-2 d-flex align-items-center gap-2 shadow-sm transition-all"
                                    style="background:linear-gradient(135deg,#10b981,#047857);color:white;border:0;border-radius:.625rem;font-size:.78rem; box-shadow:0 4px 12px rgba(16,185,129,.2); border-radius:.625rem;">
                                <span wire:loading.remove wire:target="saveAllOCRItems" class="d-flex align-items-center gap-1">
                                    <i data-feather="check-square" style="width:14px;height:14px;"></i>
                                    CONFIRMAR LOTE
                                </span>
                                <span wire:loading wire:target="saveAllOCRItems" class="d-flex align-items-center gap-1">
                                    <span class="spinner-border spinner-border-sm" role="status" style="width:12px; height:12px;"></span>
                                    GENERANDO...
                                </span>
                            </button>
                        @endif
                    </div>

                    {{-- OCR Body --}}
                    <div class="card-body p-4">
                        @if (session()->has('message'))
                            <div class="mb-4 p-3 d-flex align-items-center gap-2"
                                 style="background:linear-gradient(135deg,#dcfce7,#f0fdf4);border:1.5px solid #86efac;border-radius:.75rem;">
                                <i data-feather="check-circle" style="width:16px;height:16px;color:#16a34a;" class="flex-shrink-0"></i>
                                <span class="small fw-bold text-success">{{ session('message') }}</span>
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <div class="mb-4 p-3 d-flex align-items-center gap-2"
                                 style="background:linear-gradient(135deg,#fee2e2,#fef2f2);border:1.5px solid #fecaca;border-radius:.75rem;">
                                <i data-feather="alert-triangle" style="width:16px;height:16px;color:#dc2626;" class="flex-shrink-0"></i>
                                <span class="small fw-bold text-danger">{{ session('error') }}</span>
                            </div>
                        @endif

                        @if(empty($ocrResults))
                            {{-- Upload Area --}}
                            <div class="text-center p-5 position-relative hover-shadow transition-all"
                                 style="border:2px dashed #cbd5e1;border-radius:1rem;background:linear-gradient(135deg,#fafafa,#f8fafc);">
                                <input type="file" wire:model="invoiceFile" id="invoiceInput" class="d-none" accept=".pdf,.jpg,.png">

                                <label for="invoiceInput" style="cursor:pointer;" wire:loading.remove wire:target="invoiceFile" class="d-block mb-0">
                                    <div class="d-flex align-items-center justify-content-center mx-auto mb-4 pulse-animation"
                                         style="width:64px;height:64px;background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:1rem;">
                                        <i data-feather="cpu" style="width:28px;height:28px;color:#3b82f6;"></i>
                                    </div>
                                    <h5 class="fw-black text-dark mb-2" style="font-size: 1.1rem;">Subir Factura de Proveedor</h5>
                                    <p class="text-muted small mb-4" style="max-width: 380px; margin: 0 auto; line-height: 1.4;">
                                        Sube el documento PDF o imagen de tu manifiesto. Extraeremos automáticamente trackings, pesos y dimensiones.
                                    </p>
                                    <span class="badge fw-black px-3 py-2"
                                          style="background:#eff6ff;color:#3b82f6;border-radius:.5rem;font-size:.7rem; letter-spacing: 0.05em;">
                                        PDF · JPG · PNG (MÁX. 10MB)
                                    </span>
                                </label>

                                <div wire:loading wire:target="invoiceFile" class="py-4">
                                    <div class="spinner-border text-primary mb-3" role="status" style="width:2.5rem;height:2.5rem;"></div>
                                    <h5 class="fw-black text-dark">Analizando Documento...</h5>
                                    <p class="text-muted small mb-0">Nuestro motor OCR con IA está extrayendo la información</p>
                                </div>
                            </div>
                        @else
                            {{-- Results Table --}}
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr style="border-bottom:2px solid #e2e8f0; position: sticky; top: 0; background: white; z-index: 10;">
                                            <td class="ps-3 xsmall fw-black text-uppercase text-muted py-2" style="letter-spacing:.08em; font-size: 0.65rem;">Tracking / ID</td>
                                            <td class="text-center xsmall fw-black text-uppercase text-muted py-2" style="letter-spacing:.08em; font-size: 0.65rem;">Peso</td>
                                            <td class="text-center xsmall fw-black text-uppercase text-muted py-2" style="letter-spacing:.08em; font-size: 0.65rem;">Dimensiones</td>
                                            <td class="text-center xsmall fw-black text-uppercase text-muted py-2" style="letter-spacing:.08em; font-size: 0.65rem;">Estado</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ocrResults as $item)
                                            <tr style="border-bottom:1px solid #f1f5f9;">
                                                <td class="ps-3 py-3">
                                                    <span class="fw-bold font-monospace text-dark" style="font-size:0.9rem;">{{ $item['tracking'] }}</span>
                                                </td>
                                                <td class="text-center py-3">
                                                    <span class="fw-black text-dark" style="font-size:0.9rem;">{{ $item['weight'] }} lb</span>
                                                </td>
                                                <td class="text-center py-3">
                                                    <span class="xsmall text-muted font-bold">{{ $item['length'] }} × {{ $item['height'] }} × {{ $item['width'] }}</span>
                                                </td>
                                                <td class="text-center py-3">
                                                    <span class="badge fw-black px-2 py-1"
                                                          style="background:#fef3c7;color:#92400e;border-radius:.4rem;font-size:.65rem;">
                                                        ESPERADO
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                {{-- Placeholder when in Manual mode --}}
                <div class="card border-0 h-100 overflow-hidden position-relative shadow-sm d-flex flex-column"
                     style="border-radius:1rem;background:linear-gradient(135deg,#0f172a,#1e293b);min-height:430px;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center text-white p-5 flex-grow-1">
                        <div class="mb-4 d-flex align-items-center justify-content-center pulse-glow"
                             style="width:80px;height:80px;background:rgba(255,255,255,.05);border-radius:1.25rem;border:1px solid rgba(255,255,255,.08);">
                            <i data-feather="zap" style="width:36px;height:36px;color:#60a5fa;"></i>
                        </div>
                        <h4 class="fw-black text-uppercase mb-2" style="letter-spacing:1px;color:rgba(255,255,255,.95); font-size: 1.25rem;">
                            Hub de Recepción Inteligente 360º
                        </h4>
                        <p class="mb-4 text-white-50 small" style="max-width:400px; line-height: 1.5;">
                            Agiliza el ingreso de carga. Cambia a <strong class="text-white">FACTURA OCR</strong> arriba para procesar documentos de forma masiva y notificar automáticamente.
                        </p>

                        {{-- Flow Steps representation --}}
                        <div class="row g-3 w-100 justify-content-center mt-2 border-top pt-4 border-white-10" style="max-width: 580px;">
                            <div class="col-4 text-center">
                                <div class="rounded-circle bg-white-10 mx-auto d-flex align-items-center justify-content-center mb-2" style="width:36px; height:36px;">
                                    <i data-feather="cpu" style="width:16px; height:16px; color:#60a5fa;"></i>
                                </div>
                                <div class="fw-bold xsmall text-white-50" style="font-size:0.7rem;">Lectura Inteligente (OCR)</div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="rounded-circle bg-white-10 mx-auto d-flex align-items-center justify-content-center mb-2" style="width:36px; height:36px;">
                                    <i data-feather="message-circle" style="width:16px; height:16px; color:#34d399;"></i>
                                </div>
                                <div class="fw-bold xsmall text-white-50" style="font-size:0.7rem;">Notificación WhatsApp</div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="rounded-circle bg-white-10 mx-auto d-flex align-items-center justify-content-center mb-2" style="width:36px; height:36px;">
                                    <i data-feather="file-text" style="width:16px; height:16px; color:#facc15;"></i>
                                </div>
                                <div class="fw-bold xsmall text-white-50" style="font-size:0.7rem;">Facturación y Cobro Auto</div>
                            </div>
                        </div>
                    </div>
                    {{-- Decorative circles --}}
                    <div class="position-absolute" style="left:-40px;top:-40px;width:160px;height:160px;background:rgba(59,130,246,.05);border-radius:50%; pointer-events: none;"></div>
                    <div class="position-absolute" style="right:-40px;bottom:-40px;width:200px;height:200px;background:rgba(99,102,241,.04);border-radius:50%; pointer-events: none;"></div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        Livewire.on('package-saved', () => {
            setTimeout(() => { 
                if (typeof feather !== 'undefined') {
                    feather.replace(); 
                }
            }, 150);
        });
    });
</script>

<style>
    .bg-white-10 {
        background: rgba(255, 255, 255, 0.06);
    }
    .border-white-10 {
        border-color: rgba(255, 255, 255, 0.08) !important;
    }
    .pulse-animation {
        animation: pulse-light 2s infinite;
    }
    .pulse-glow {
        animation: pulse-glow 2s infinite;
    }
    @keyframes pulse-light {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
        }
        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
        }
    }
    @keyframes pulse-glow {
        0% {
            box-shadow: 0 0 0 0 rgba(96, 165, 250, 0.3);
        }
        70% {
            box-shadow: 0 0 0 15px rgba(96, 165, 250, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(96, 165, 250, 0);
        }
    }
    .hover-shadow:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border-color: #3b82f6 !important;
    }
    .transition-all {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>
