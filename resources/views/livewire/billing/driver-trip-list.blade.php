<div>
    <!-- Metrics Cards -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-xxl-4 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-dark text-white" style="border-radius: 12px;">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ $currency }} {{ number_format($stats['total_outsourcing'], 2) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Total Outsourcing (Costos)</p>
                        </div>
                        <div class="stat bg-white bg-opacity-25 text-white">
                            <i class="align-middle" data-feather="truck"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-4 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-primary text-white" style="border-radius: 12px;">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ $currency }} {{ number_format($stats['total_client'], 2) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Facturación Cliente Final</p>
                        </div>
                        <div class="stat bg-white bg-opacity-25 text-white">
                            <i class="align-middle" data-feather="dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-4 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-success text-white" style="border-radius: 12px;">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ $currency }} {{ number_format($stats['total_revenue'], 2) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Utilidad Total (Rev)</p>
                        </div>
                        <div class="stat bg-white bg-opacity-25 text-white">
                            <i class="align-middle" data-feather="trending-up"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4 border-0" role="alert">
            <div class="alert-message"><strong>¡Éxito!</strong> {{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-header bg-white border-bottom py-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title mb-0 uppercase font-black small text-muted" style="letter-spacing: 0.05em;">Planilla de Control de Fletes y Viajes</h5>
                </div>
                <div class="col-md-6 text-md-end">
                    <button wire:click="openCreateModal" class="btn btn-primary fw-black text-uppercase font-bold small">
                        <i class="align-middle me-1" data-feather="plus"></i> Nuevo Flete / Viaje
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="card-body bg-light border-bottom p-3">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-0"><i data-feather="search" style="width: 14px;"></i></span>
                        <input type="text" wire:model.live="search" class="form-control border-0" placeholder="Buscar chofer, empresa, factura...">
                    </div>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="filter_invoice_status" class="form-select form-select-sm border-0 font-bold">
                        <option value="">Estado Factura (Todos)</option>
                        <option value="PENDIENTE">PENDIENTE</option>
                        <option value="PAGADA">PAGADA</option>
                        <option value="ABONO">ABONO</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="filter_driver_payment_status" class="form-select form-select-sm border-0 font-bold">
                        <option value="">Pago Chofer (Todos)</option>
                        <option value="PENDIENTE">PENDIENTE</option>
                        <option value="PAGADA">PAGADA</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-0 text-muted xsmall uppercase font-bold">Desde</span>
                        <input type="date" wire:model.live="filter_date_from" class="form-control border-0">
                        <span class="input-group-text bg-white border-0 text-muted xsmall uppercase font-bold">Hasta</span>
                        <input type="date" wire:model.live="filter_date_to" class="form-control border-0">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 cursor-pointer" wire:click="sortBy('date')">Fecha</th>
                        <th class="cursor-pointer" wire:click="sortBy('driver_name')">Conductor</th>
                        <th class="cursor-pointer" wire:click="sortBy('company_name')">Empresa</th>
                        <th>Descripción</th>
                        <th class="text-end cursor-pointer" wire:click="sortBy('outsourcing_cost')">Outsourcing</th>
                        <th class="text-end cursor-pointer" wire:click="sortBy('final_client_price')">Cliente Final</th>
                        <th class="text-end cursor-pointer" wire:click="sortBy('revenue')">Rev (Utilidad)</th>
                        <th>Factura</th>
                        <th>Status Factura</th>
                        <th>Pago Chofer</th>
                        <th class="pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trips as $trip)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ $trip->date->format('d/m/Y') }}</td>
                            <td><span class="badge bg-secondary font-black" style="font-size: 0.75rem;">{{ $trip->driver_name }}</span></td>
                            <td class="fw-black text-dark">{{ $trip->company_name }}</td>
                            <td class="small text-muted">{{ $trip->description }}</td>
                            <td class="text-end fw-bold text-muted">{{ $currency }} {{ number_format($trip->outsourcing_cost, 2) }}</td>
                            <td class="text-end fw-bold text-primary">{{ $currency }} {{ number_format($trip->final_client_price, 2) }}</td>
                            <td class="text-end fw-black text-success">{{ $currency }} {{ number_format($trip->revenue, 2) }}</td>
                            <td>
                                @if($trip->invoice_number)
                                    <span class="fw-bold text-dark"><i data-feather="file-text" style="width: 12px; height: 12px;" class="me-1 text-muted"></i>{{ $trip->invoice_number }}</span>
                                @else
                                    <span class="text-muted italic xsmall">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $invBadge = [
                                        'PAGADA' => 'bg-success',
                                        'ABONO' => 'bg-info',
                                        'PENDIENTE' => 'bg-warning text-dark',
                                    ][$trip->invoice_status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $invBadge }} font-bold text-uppercase" style="font-size: 0.65rem;">{{ $trip->invoice_status }}</span>
                            </td>
                            <td>
                                @php
                                    $drvBadge = [
                                        'PAGADA' => 'bg-success',
                                        'PENDIENTE' => 'bg-warning text-dark',
                                    ][$trip->driver_payment_status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $drvBadge }} font-bold text-uppercase" style="font-size: 0.65rem;">{{ $trip->driver_payment_status }}</span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group">
                                    <button wire:click="editTrip({{ $trip->id }})" class="btn btn-sm btn-light border" title="Editar Registro">
                                        <i class="align-middle text-primary" data-feather="edit" style="width: 14px; height: 14px;"></i>
                                    </button>
                                    <button wire:click="deleteTrip({{ $trip->id }})" wire:confirm="¿Seguro que deseas eliminar este registro de flete/viaje?" class="btn btn-sm btn-outline-danger" title="Eliminar Registro">
                                        <i class="align-middle" data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-5 text-muted italic">No se encontraron registros de fletes/viajes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-top">
            {{ $trips->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="modalDriverTripForm" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header bg-primary text-white p-4">
                    <h5 class="modal-title uppercase font-black tracking-widest text-white">
                        <i class="align-middle me-2" data-feather="truck"></i> {{ $isEditMode ? 'Editar Flete / Viaje' : 'Registrar Nuevo Flete / Viaje' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="saveTrip">
                    <div class="modal-body bg-light p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label font-bold text-uppercase small text-muted">Fecha del Viaje</label>
                                <input type="date" class="form-control" wire:model="date">
                                @error('date') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-bold text-uppercase small text-muted">Conductor (Chofer)</label>
                                <input type="text" class="form-control" wire:model="driver_name" placeholder="Ej: TGR, Juan Pérez">
                                @error('driver_name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-bold text-uppercase small text-muted">Empresa Contratante</label>
                                <input type="text" class="form-control" wire:model="company_name" placeholder="Ej: GLC PANAMA, LOGY FREIGHT">
                                @error('company_name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-bold text-uppercase small text-muted">Factura de Referencia (Opcional)</label>
                                <input type="text" class="form-control" wire:model="invoice_number" placeholder="Ej: 505, 511/510">
                                @error('invoice_number') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label font-bold text-uppercase small text-muted">Descripción del Servicio / Ruta</label>
                                <textarea class="form-control" wire:model="description" rows="2" placeholder="Ej: TOCUMEN - HOWARD 26PIE"></textarea>
                                @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-bold text-uppercase small text-muted">Outsourcing (Costo Chofer)</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $currency }}</span>
                                    <input type="number" step="0.01" class="form-control" wire:model.live="outsourcing_cost">
                                </div>
                                @error('outsourcing_cost') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-bold text-uppercase small text-muted">Cliente Final (Facturación)</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $currency }}</span>
                                    <input type="number" step="0.01" class="form-control" wire:model.live="final_client_price">
                                </div>
                                @error('final_client_price') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-bold text-uppercase small text-muted">Estado de la Factura</label>
                                <select class="form-select font-bold" wire:model="invoice_status">
                                    <option value="PENDIENTE">PENDIENTE</option>
                                    <option value="PAGADA">PAGADA</option>
                                    <option value="ABONO">ABONO</option>
                                </select>
                                @error('invoice_status') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-bold text-uppercase small text-muted">Estado Pago Chofer</label>
                                <select class="form-select font-bold" wire:model="driver_payment_status">
                                    <option value="PENDIENTE">PENDIENTE</option>
                                    <option value="PAGADA">PAGADA</option>
                                </select>
                                @error('driver_payment_status') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-12 bg-white p-3 border rounded shadow-sm d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small uppercase font-bold">Cálculo de Utilidad (Rev):</span>
                                    <h4 class="mb-0 text-success fw-black">
                                        {{ $currency }} {{ number_format(floatval($final_client_price ?? 0) - floatval($outsourcing_cost ?? 0), 2) }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white">
                        <button type="button" class="btn btn-light border text-uppercase font-bold small" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary text-uppercase font-bold small px-4">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Helper Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            window.addEventListener('open-modal', event => {
                const modalId = event.detail;
                const el = document.getElementById(modalId);
                if (el) {
                    const myModal = bootstrap.Modal.getOrCreateInstance(el);
                    myModal.show();
                }
            });

            window.addEventListener('close-modal', event => {
                const modalId = event.detail;
                const el = document.getElementById(modalId);
                if (el) {
                    const myModal = bootstrap.Modal.getOrCreateInstance(el);
                    myModal.hide();
                }
            });
        });
    </script>
</div>
