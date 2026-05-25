<div>
    <form wire:submit.prevent="save">
        <div class="row">
            <!-- Customer Info -->
            <div class="col-md-4">
                <div class="card shadow-none border mb-3">
                    <div class="card-header bg-light py-2">
                        <h6 class="card-title mb-0 small font-black uppercase text-muted">Datos del Cliente</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label class="form-label small font-bold text-uppercase">Nº Casillero</label>
                            <input type="text" wire:model.live="box_number"
                                   class="form-control form-control-lg fw-black text-primary border-2"
                                   placeholder="PTY-XXXXX">
                            @error('box_number') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                        </div>

                        @if($found_customer)
                            <div class="p-3 bg-primary-light rounded-3 border border-primary border-opacity-10 animate-in fade-in duration-300">
                                <div class="fw-black text-primary leading-tight">{{ $found_customer->user->name }}</div>
                                <div class="small text-muted mb-1">{{ $found_customer->user->email }}</div>
                                <span class="badge bg-primary px-2 py-1">{{ $found_customer->box_number }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card shadow-none border">
                    <div class="card-header bg-light py-2">
                        <h6 class="card-title mb-0 small font-black uppercase text-muted">Observaciones</h6>
                    </div>
                    <div class="card-body p-3">
                        <textarea wire:model="notes" rows="3" class="form-control" placeholder="Notas internas o para el cliente..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="col-md-8">
                <div class="card shadow-none border mb-3">
                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0 small font-black uppercase text-muted">Conceptos de Facturación</h6>
                        <button type="button" wire:click="addItem" class="btn btn-xs btn-primary font-bold"><i class="align-middle" data-feather="plus"></i> AGREGAR LÍNEA</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 py-2">Descripción</th>
                                    <th class="text-center py-2" style="width: 80px;">Cant</th>
                                    <th class="text-end py-2" style="width: 120px;">Precio</th>
                                    <th class="text-end py-2" style="width: 120px;">Total</th>
                                    <th class="pe-3 py-2" style="width: 40px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $index => $item)
                                    <tr>
                                        <td class="ps-3 py-2">
                                            <input type="text" wire:model="items.{{ $index }}.description"
                                                   class="form-control form-control-sm" placeholder="Ej: Flete Miami-Panamá">
                                        </td>
                                        <td class="py-2">
                                            <input type="number" step="0.01" wire:model="items.{{ $index }}.quantity" wire:change="updateItemTotal({{ $index }})"
                                                   class="form-control form-control-sm text-center fw-bold">
                                        </td>
                                        <td class="py-2">
                                            <input type="number" step="0.01" wire:model="items.{{ $index }}.unit_price" wire:change="updateItemTotal({{ $index }})"
                                                   class="form-control form-control-sm text-end fw-bold">
                                        </td>
                                        <td class="py-2 text-end fw-black text-dark">
                                            ${{ number_format($item['total'], 2) }}
                                        </td>
                                        <td class="pe-3 py-2 text-end">
                                            <button type="button" wire:click="removeItem({{ $index }})" class="btn btn-link text-danger p-0">
                                                <i class="align-middle" data-feather="trash-2" style="width: 14px;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Totals -->
                <div class="card shadow-none border">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-sm-6 ms-auto">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td class="text-muted small text-uppercase font-bold">Subtotal:</td>
                                        <td class="text-end fw-bold">${{ number_format(collect($items)->sum('total'), 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted small text-uppercase font-bold">Impuesto ({{ $tax_percent }}%):</td>
                                        <td class="text-end fw-bold">${{ number_format(collect($items)->sum('total') * ($tax_percent / 100), 2) }}</td>
                                    </tr>
                                    <tr class="border-top">
                                        <td class="h4 fw-black text-uppercase pt-2">Total:</td>
                                        <td class="text-end h4 fw-black text-primary pt-2">${{ number_format(collect($items)->sum('total') * (1 + $tax_percent / 100), 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-lg btn-success py-3 fw-black text-uppercase tracking-widest shadow-lg">
                        <i class="align-middle me-2" data-feather="check-circle"></i> GENERAR FACTURA AHORA
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
