<div>
    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h3 mb-2 uppercase font-black tracking-tight">Auditoría Financiera Global</h1>
            <p class="text-muted">Supervise todas las facturas emitidas por los Tenants a sus clientes finales.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-bottom p-4">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-black uppercase text-muted">Búsqueda</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i data-feather="search" style="width: 14px;"></i></span>
                        <input type="text" wire:model.live="search" class="form-control border-start-0 ps-0" placeholder="Número de factura...">
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-black uppercase text-muted">Empresa</label>
                    <select wire:model.live="filter_tenant" class="form-select form-select-sm">
                        <option value="">Todos los Tenants</option>
                        @foreach($tenants as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-black uppercase text-muted">Estado de Pago</label>
                    <select wire:model.live="filter_status" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="paid">Pagado</option>
                        <option value="unpaid">Pendiente</option>
                        <option value="cancelled">Cancelado</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped my-0">
                <thead>
                    <tr>
                        <th class="ps-4 cursor-pointer" wire:click="sortBy('number')">Factura</th>
                        <th>Empresa (Tenant)</th>
                        <th>Cliente Final</th>
                        <th class="cursor-pointer" wire:click="sortBy('total')">Total</th>
                        <th>Estado</th>
                        <th class="pe-4 text-end">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $inv)
                        <tr wire:key="inv-{{ $inv->id }}">
                            <td class="ps-4 fw-black text-dark">{{ $inv->number }}</td>
                            <td>
                                <span class="badge bg-primary-light text-primary fw-black uppercase">{{ $inv->tenant?->name }}</span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $inv->customer?->user?->name ?? 'S/D' }}</div>
                                <div class="small text-muted">{{ $inv->customer?->box_number }}</div>
                            </td>
                            <td class="fw-black text-dark">${{ number_format($inv->total, 2) }}</td>
                            <td>
                                <span class="badge {{ $inv->status === 'paid' ? 'bg-success' : ($inv->status === 'unpaid' ? 'bg-warning' : 'bg-danger') }} text-uppercase">
                                    {{ $inv->status === 'paid' ? 'COBRADO' : ($inv->status === 'unpaid' ? 'PENDIENTE' : 'CANCELADO') }}
                                </span>
                            </td>
                            <td class="pe-4 text-end small fw-bold text-muted">{{ $inv->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer border-top bg-light">
            {{ $invoices->links() }}
        </div>
    </div>
</div>
