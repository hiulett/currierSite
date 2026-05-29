<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-0 uppercase font-black tracking-tight text-dark">Validación de Pagos Manuales</h1>
            <p class="text-muted small">Aprueba o rechaza los comprobantes de Yappy y Transferencias.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <span class="badge bg-warning text-dark text-uppercase font-black px-3 py-2">
                {{ $proofs->total() }} PENDIENTES
            </span>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message">{{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 uppercase font-black small">Cola de Verificación</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Cliente / Casillero</th>
                                <th>Factura</th>
                                <th>Método</th>
                                <th>Fecha</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($proofs as $proof)
                                <tr class="{{ $selected_proof && $selected_proof->id === $proof->id ? 'table-primary' : '' }}">
                                    <td class="ps-4">
                                        <div class="fw-black text-dark">{{ $proof->invoice->customer->user->name }}</div>
                                        <div class="text-primary xsmall font-bold uppercase">{{ $proof->invoice->customer->box_number }}</div>
                                    </td>
                                    <td>
                                        <div class="small fw-bold">{{ $proof->invoice->number }}</div>
                                        <div class="text-muted xsmall">${{ number_format($proof->invoice->total, 2) }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border text-uppercase" style="font-size: 0.6rem;">{{ $proof->method }}</span>
                                    </td>
                                    <td class="small">{{ $proof->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-end pe-4">
                                        <button wire:click="selectProof({{ $proof->id }})" class="btn btn-sm btn-primary fw-black uppercase">
                                            REVISAR <i class="align-middle ms-1" data-feather="chevron-right"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted italic">No hay pagos pendientes de validación.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-top">
                    {{ $proofs->links() }}
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            @if($selected_proof)
                <div class="card shadow-sm border-0 animate__animated animate__fadeIn">
                    <div class="card-header bg-dark text-white py-3">
                        <h5 class="card-title text-white mb-0 uppercase font-black small text-center">Detalle del Comprobante</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <a href="{{ Storage::url($selected_proof->file_path) }}" target="_blank" class="d-block border rounded p-1 shadow-sm bg-white">
                                <img src="{{ Storage::url($selected_proof->file_path) }}" alt="Comprobante" class="img-fluid rounded" style="max-height: 400px;">
                            </a>
                            <p class="xsmall text-muted mt-2 italic">Click en la imagen para ver en pantalla completa.</p>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <label class="text-muted xsmall uppercase font-bold d-block">Monto Facturado</label>
                                <div class="fw-black h5 text-dark">${{ number_format($selected_proof->invoice->total, 2) }}</div>
                            </div>
                            <div class="col-6">
                                <label class="text-muted xsmall uppercase font-bold d-block">Método Utilizado</label>
                                <div class="fw-black text-primary text-uppercase">{{ $selected_proof->method }}</div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button wire:click="approve({{ $selected_proof->id }})"
                                    wire:confirm="¿Confirmas que el dinero ha ingresado a la cuenta?"
                                    class="btn btn-success fw-black uppercase py-3 shadow-sm">
                                <i class="align-middle me-2" data-feather="check-circle"></i> APROBAR PAGO
                            </button>

                            <hr class="my-3">

                            <div class="mb-3">
                                <label class="form-label font-bold small text-uppercase text-danger">Motivo de Rechazo (Obligatorio)</label>
                                <textarea wire:model="rejection_reason" class="form-control" rows="2" placeholder="Ej: Monto incorrecto, imagen ilegible..."></textarea>
                                @error('rejection_reason') <span class="text-danger xsmall">{{ $message }}</span> @enderror
                            </div>

                            <button wire:click="reject({{ $selected_proof->id }})"
                                    class="btn btn-outline-danger fw-black uppercase">
                                <i class="align-middle me-2" data-feather="x-circle"></i> RECHAZAR COMPROBANTE
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body p-5 text-center">
                        <i data-feather="mouse-pointer" class="text-muted mb-3" style="width: 48px; height: 48px; opacity: 0.3;"></i>
                        <h6 class="fw-black text-muted text-uppercase small">Selecciona un pago para revisar</h6>
                        <p class="xsmall text-muted mb-0">Los detalles y la imagen del comprobante aparecerán aquí.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
