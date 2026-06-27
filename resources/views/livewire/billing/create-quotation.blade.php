<div>
    <div class="p-4 p-md-5">
        <form wire:submit.prevent="save">
            <!-- Header Section -->
            <div class="row mb-5">
                <!-- Panel Izquierdo: Cliente -->
                <div class="col-lg-7 border-end-lg pe-lg-4 mb-4 mb-lg-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-bold text-dark text-uppercase small tracking-wide">1. Información del Cliente</h6>
                        <div class="btn-group btn-group-sm shadow-sm" role="group">
                            <input type="radio" class="btn-check" name="is_registered" id="reg_client" value="1" wire:model.live="is_registered">
                            <label class="btn btn-outline-dark fw-bold px-3" for="reg_client">Registrado</label>

                            <input type="radio" class="btn-check" name="is_registered" id="unreg_client" value="0" wire:model.live="is_registered">
                            <label class="btn btn-outline-dark fw-bold px-3" for="unreg_client">Nuevo / Ocasional</label>
                        </div>
                    </div>

                    @if($is_registered)
                        <div class="position-relative mt-4">
                            <label class="form-label small text-muted fw-bold">Buscar Cliente en Base de Datos</label>
                            <div class="input-group input-group-lg shadow-sm">
                                <span class="input-group-text bg-white border-end-0"><i data-feather="search" class="text-muted"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" wire:model.live="search_customer" placeholder="Nombre, email o número PTY...">
                            </div>
                            
                            @if(!empty($search_customer) && empty($customer_id))
                                <div class="position-absolute w-100 mt-2 shadow-lg bg-white border rounded-3 z-3 overflow-hidden" style="max-height: 250px; overflow-y: auto;">
                                    @forelse($customers as $c)
                                        <div class="p-3 border-bottom cursor-pointer hover-bg-light transition-all" wire:click="selectCustomer({{ $c->id }}, '{{ $c->user->name }} - {{ $c->box_number }}')">
                                            <div class="fw-bold text-dark">{{ $c->user->name }}</div>
                                            <div class="small text-muted d-flex align-items-center mt-1">
                                                <i data-feather="mail" class="me-1" style="width: 12px;"></i> {{ $c->user->email }}
                                                <span class="mx-2">•</span>
                                                <span class="badge bg-light text-dark border">PTY: {{ $c->box_number }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-4 text-center text-muted small">
                                            <i data-feather="inbox" class="mb-2 opacity-50" style="width: 24px; height: 24px;"></i><br>
                                            No se encontraron coincidencias.
                                        </div>
                                    @endforelse
                                </div>
                            @endif
                            @error('customer_id') <div class="text-danger small mt-2 fw-bold"><i data-feather="alert-circle" style="width: 14px;"></i> {{ $message }}</div> @enderror
                        </div>
                    @else
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-bold">Nombre / Empresa</label>
                                <input type="text" class="form-control bg-light border-0" wire:model="client_name" placeholder="A quién va dirigida">
                                @error('client_name') <span class="text-danger xsmall fw-bold">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-bold">Apellido</label>
                                <input type="text" class="form-control bg-light border-0" wire:model="client_lastname" placeholder="Opcional">
                                @error('client_lastname') <span class="text-danger xsmall fw-bold">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted fw-bold">Correo Electrónico</label>
                                <input type="email" class="form-control bg-light border-0" wire:model="client_email" placeholder="correo@ejemplo.com">
                                @error('client_email') <span class="text-danger xsmall fw-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Panel Derecho: Servicio -->
                <div class="col-lg-5 ps-lg-4">
                    <h6 class="mb-4 fw-bold text-dark text-uppercase small tracking-wide">2. Vía de Transporte</h6>
                    
                    <div class="d-flex flex-column gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" class="btn-check" name="service_type" id="service_air" value="air" wire:model.live="service_type">
                            <div class="card mb-0 border shadow-none transition-all hover-border-primary {{ $service_type == 'air' ? 'border-primary bg-primary bg-opacity-10' : 'bg-white' }}">
                                <div class="card-body p-3 d-flex align-items-center">
                                    <div class="rounded-circle p-2 {{ $service_type == 'air' ? 'bg-primary text-white' : 'bg-light text-muted' }} me-3 transition-all">
                                        <i data-feather="send" style="width: 20px; height: 20px;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-black text-dark">Servicio Aéreo</div>
                                        <div class="small text-muted" style="font-size: 0.75rem;">Tarifa por libra real o volumen</div>
                                    </div>
                                    <div class="ms-auto text-primary" style="opacity: {{ $service_type == 'air' ? '1' : '0' }};">
                                        <i data-feather="check-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" class="btn-check" name="service_type" id="service_maritime" value="maritime" wire:model.live="service_type">
                            <div class="card mb-0 border shadow-none transition-all hover-border-info {{ $service_type == 'maritime' ? 'border-info bg-info bg-opacity-10' : 'bg-white' }}">
                                <div class="card-body p-3 d-flex align-items-center">
                                    <div class="rounded-circle p-2 {{ $service_type == 'maritime' ? 'bg-info text-white' : 'bg-light text-muted' }} me-3 transition-all">
                                        <i data-feather="anchor" style="width: 20px; height: 20px;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-black text-dark">Servicio Marítimo</div>
                                        <div class="small text-muted" style="font-size: 0.75rem;">Tarifa para carga pesada</div>
                                    </div>
                                    <div class="ms-auto text-info" style="opacity: {{ $service_type == 'maritime' ? '1' : '0' }};">
                                        <i data-feather="check-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-end mb-3 pb-2 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark text-uppercase small tracking-wide">3. Detalle de Artículos</h6>
                    <button type="button" class="btn btn-sm btn-outline-dark fw-bold rounded-pill px-3" wire:click="addItem">
                        <i data-feather="plus" class="me-1" style="width: 14px;"></i> Añadir Fila
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="text-muted small uppercase font-bold" style="font-size: 0.7rem;">
                            <tr class="border-bottom">
                                <th style="width: 15%;" class="ps-0">ID / Tracking</th>
                                <th style="width: 35%;">Descripción del Artículo</th>
                                <th style="width: 10%;">Peso (Lbs)</th>
                                <th style="width: 15%;">Tarifa <span class="text-primary">*</span></th>
                                <th style="width: 15%;">Manejo/Fijo</th>
                                <th style="width: 10%;" class="text-end">Monto</th>
                                <th style="width: 5%;" class="pe-0"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $index => $item)
                                <tr class="border-bottom border-light">
                                    <td class="ps-0 py-3">
                                        <input type="text" class="form-control bg-light border-0" wire:model="items.{{ $index }}.item_number" placeholder="Opcional">
                                    </td>
                                    <td class="py-3">
                                        <input type="text" class="form-control bg-light border-0" wire:model="items.{{ $index }}.description" placeholder="Ej: Laptop, Repuestos...">
                                        @error('items.'.$index.'.description') <span class="text-danger xsmall fw-bold">{{ $message }}</span> @enderror
                                    </td>
                                    <td class="py-3">
                                        <input type="number" step="0.1" class="form-control bg-light border-0 fw-bold text-center" wire:model.live="items.{{ $index }}.quantity">
                                        @error('items.'.$index.'.quantity') <span class="text-danger xsmall fw-bold">{{ $message }}</span> @enderror
                                    </td>
                                    <td class="py-3">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0 text-muted small">{{ $currency }}</span>
                                            <input type="number" step="0.01" class="form-control bg-light border-0" wire:model.live="items.{{ $index }}.price">
                                        </div>
                                        @error('items.'.$index.'.price') <span class="text-danger xsmall fw-bold">{{ $message }}</span> @enderror
                                    </td>
                                    <td class="py-3">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0 text-muted small">{{ $currency }}</span>
                                            <input type="number" step="0.01" class="form-control bg-light border-0" wire:model.live="items.{{ $index }}.handling_price">
                                        </div>
                                        @error('items.'.$index.'.handling_price') <span class="text-danger xsmall fw-bold">{{ $message }}</span> @enderror
                                    </td>
                                    <td class="py-3 text-end fw-black text-dark fs-6">
                                        {{ number_format($item['total'], 2) }}
                                    </td>
                                    <td class="py-3 pe-0 text-end">
                                        @if(count($items) > 1)
                                            <button type="button" class="btn btn-sm btn-link text-danger p-1 rounded hover-bg-light" wire:click="removeItem({{ $index }})">
                                                <i data-feather="x" style="width: 18px;"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-muted mt-2 xsmall">* La tarifa se ajusta automáticamente según la vía de transporte seleccionada, pero puede ser editada.</div>
                </div>
            </div>

            <!-- Notes & Totals -->
            <div class="row align-items-stretch">
                <div class="col-md-6 mb-4 mb-md-0 d-flex flex-column">
                    <label class="form-label fw-bold text-dark small text-uppercase tracking-wide">Notas y Términos Adicionales</label>
                    <textarea class="form-control bg-light border-0 flex-grow-1 p-3" wire:model="notes" placeholder="Agrega condiciones de pago, validez de la cotización, o cualquier comentario para el cliente..."></textarea>
                </div>
                <div class="col-md-5 offset-md-1">
                    <div class="card bg-dark text-white border-0 shadow-lg mb-0 h-100" style="border-radius: 1rem;">
                        <div class="card-body p-4 d-flex flex-column justify-content-center">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-white-50 fw-bold uppercase small">Subtotal Fletes:</span>
                                <span class="fw-bold">{{ $currency }} {{ number_format($this->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4 border-bottom border-secondary pb-4">
                                <span class="text-white-50 fw-bold uppercase small">Cargos de Manejo:</span>
                                <span class="fw-bold">{{ $currency }} {{ number_format($this->handlingTotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-white fw-black uppercase tracking-widest small d-block mb-1">Total Estimado</span>
                                    <span class="text-white-50 xsmall">Impuestos no incluidos</span>
                                </div>
                                <h2 class="fw-black mb-0 text-white display-6">{{ $currency }} {{ number_format($this->total, 2) }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top">
                <button type="button" class="btn btn-light px-4 py-2 fw-bold text-uppercase border-0 shadow-sm" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary px-5 py-2 fw-black text-uppercase shadow-lg d-flex align-items-center gap-2" wire:loading.attr="disabled">
                    <span wire:loading.remove><i data-feather="check-circle" style="width: 18px;"></i> {{ $quotation_id ? 'Guardar Cambios' : 'Guardar Cotización' }}</span>
                    <span wire:loading><span class="spinner-border spinner-border-sm"></span> Procesando...</span>
                </button>
            </div>
        </form>
    </div>
</div>
<style>
    .tracking-wide { letter-spacing: 0.05em; }
    .tracking-widest { letter-spacing: 0.1em; }
    .border-end-lg { border-right: 1px solid #dee2e6; }
    .hover-border-primary:hover { border-color: #3b82f6 !important; }
    .hover-border-info:hover { border-color: #0ea5e9 !important; }
    @media (max-width: 991.98px) {
        .border-end-lg { border-right: none; border-bottom: 1px solid #dee2e6; padding-bottom: 1.5rem; }
    }
</style>
<script>
    // Re-initialize feather icons after DOM update
    document.addEventListener('livewire:navigated', () => {
        if (typeof feather !== 'undefined') { feather.replace(); }
    });
    document.addEventListener('livewire:initialized', () => {
        Livewire.hook('morph.updated', (el, component) => {
            if (typeof feather !== 'undefined') { feather.replace(); }
        });
    });
</script>
