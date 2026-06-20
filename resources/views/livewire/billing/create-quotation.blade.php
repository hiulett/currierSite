<div>
    <div class="p-4">
        <form wire:submit.prevent="save">
            <!-- Customer Selection -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label font-black text-uppercase small text-muted">Seleccionar Cliente</label>
                    <div class="position-relative">
                        <input type="text" class="form-control" wire:model.live="search_customer" placeholder="Buscar por nombre, email o número de casillero...">
                        
                        @if(!empty($search_customer) && empty($customer_id))
                            <div class="position-absolute w-100 mt-1 shadow bg-white border rounded z-3" style="max-height: 200px; overflow-y: auto;">
                                @forelse($customers as $c)
                                    <div class="p-2 border-bottom cursor-pointer hover-bg-light" wire:click="selectCustomer({{ $c->id }}, '{{ $c->user->name }} - {{ $c->box_number }}')">
                                        <div class="fw-bold">{{ $c->user->name }}</div>
                                        <div class="small text-muted">{{ $c->user->email }} | PTY: {{ $c->box_number }}</div>
                                    </div>
                                @empty
                                    <div class="p-2 text-muted small">No se encontraron clientes.</div>
                                @endforelse
                            </div>
                        @endif
                    </div>
                    @error('customer_id') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Items -->
            <div class="card border mb-4 shadow-sm">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0 text-uppercase font-black small">Artículos de la Cotización</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped align-middle mb-0">
                            <thead class="border-bottom text-muted small uppercase">
                                <tr>
                                    <th style="width: 15%;"># Item</th>
                                    <th style="width: 35%;">Descripción</th>
                                    <th style="width: 10%;">Cant.</th>
                                    <th style="width: 15%;">Precio Unit.</th>
                                    <th style="width: 15%;">Manejo Unit.</th>
                                    <th style="width: 15%;" class="text-end">Total</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $index => $item)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" wire:model="items.{{ $index }}.item_number" placeholder="Opcional">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" wire:model="items.{{ $index }}.description" placeholder="Ej: Laptop Dell XPS 15">
                                            @error('items.'.$index.'.description') <span class="text-danger xsmall">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="number" step="0.1" class="form-control form-control-sm" wire:model.live="items.{{ $index }}.quantity">
                                            @error('items.'.$index.'.quantity') <span class="text-danger xsmall">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">{{ $currency }}</span>
                                                <input type="number" step="0.01" class="form-control" wire:model.live="items.{{ $index }}.price">
                                            </div>
                                            @error('items.'.$index.'.price') <span class="text-danger xsmall">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">{{ $currency }}</span>
                                                <input type="number" step="0.01" class="form-control" wire:model.live="items.{{ $index }}.handling_price">
                                            </div>
                                            @error('items.'.$index.'.handling_price') <span class="text-danger xsmall">{{ $message }}</span> @enderror
                                        </td>
                                        <td class="text-end fw-black text-dark">
                                            {{ $currency }} {{ number_format($item['total'], 2) }}
                                        </td>
                                        <td class="text-end">
                                            @if(count($items) > 1)
                                                <button type="button" class="btn btn-sm btn-link text-danger p-0" wire:click="removeItem({{ $index }})">
                                                    <i class="align-middle" data-feather="trash-2" style="width: 16px;"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top">
                    <button type="button" class="btn btn-sm btn-light border fw-bold" wire:click="addItem">
                        <i class="align-middle me-1" data-feather="plus"></i> Agregar Fila
                    </button>
                </div>
            </div>

            <!-- Notes & Totals -->
            <div class="row">
                <div class="col-md-7">
                    <label class="form-label font-black text-uppercase small text-muted">Notas / Términos de la Cotización</label>
                    <textarea class="form-control" wire:model="notes" rows="4" placeholder="Condiciones de pago, tiempo de entrega, validez de la oferta..."></textarea>
                </div>
                <div class="col-md-5">
                    <div class="bg-light p-4 rounded border">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted fw-bold uppercase small">Subtotal Artículos:</span>
                            <span class="fw-bold">{{ $currency }} {{ number_format($this->subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                            <span class="text-muted fw-bold uppercase small">Manejo / Gestión:</span>
                            <span class="fw-bold">{{ $currency }} {{ number_format($this->handlingTotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-dark fw-black uppercase">Total a Cotizar:</span>
                            <h4 class="fw-black mb-0 text-primary">{{ $currency }} {{ number_format($this->total, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-4 pt-3 border-top text-end">
                <button type="button" class="btn btn-light border text-uppercase fw-bold me-2 px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary text-uppercase fw-black px-5 shadow-sm" wire:loading.attr="disabled">
                    <span wire:loading.remove>Guardar Cotización</span>
                    <span wire:loading>Guardando...</span>
                </button>
            </div>
        </form>
    </div>
</div>
