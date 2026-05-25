<div>
    <div class="row mb-3">
        <div class="col-12 col-md-6">
            <h1 class="h4 mb-0 uppercase font-black tracking-tight text-dark">Facturación a Tenants</h1>
            <p class="text-muted xsmall mb-0">Gestione el cobro de suscripciones a las empresas del ecosistema.</p>
        </div>
        <div class="col-12 col-md-6 text-md-end">
            <button onclick="openInvoiceModal()" class="btn btn-primary btn-sm fw-black shadow-sm">
                <i class="align-middle me-1" data-feather="plus-circle"></i> GENERAR FACTURA SAAS
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-danger text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white fw-black">${{ number_format($stats['total_receivable'], 2) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Por Cobrar</p>
                        </div>
                        <div class="stat bg-white bg-opacity-25 text-white">
                            <i class="align-middle" data-feather="dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-success text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white fw-black">${{ number_format($stats['paid_this_month'], 2) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Recaudado Mes</p>
                        </div>
                        <div class="stat bg-white bg-opacity-25 text-white">
                            <i class="align-middle" data-feather="check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-warning text-white">
                <div class="card-body py-4 text-center">
                    <h3 class="mb-2 fw-black text-white">{{ $stats['overdue_count'] }}</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Facturas Vencidas</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-primary text-white">
                <div class="card-body py-4 text-center">
                    <h3 class="mb-2 fw-black text-white">{{ $stats['pending_count'] }}</h3>
                    <p class="mb-0 text-uppercase font-bold small opacity-75">Pendientes</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 border-bottom">
            <h5 class="card-title mb-0 uppercase font-black small">Listado de Cobros SaaS</h5>
            <div class="d-flex gap-2 w-100 w-md-auto">
                <select wire:model.live="filter_status" class="form-select form-select-sm" style="width: 150px;">
                    <option value="">Todos los Estados</option>
                    <option value="unpaid">Pendientes</option>
                    <option value="paid">Pagadas</option>
                    <option value="overdue">Vencidas</option>
                </select>
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Buscar empresa...">
                    <span class="input-group-text bg-white"><i class="align-middle text-muted" data-feather="search" style="width: 14px;"></i></span>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped my-0">
                <thead>
                    <tr>
                        <th class="ps-4 cursor-pointer" wire:click="sortBy('number')">
                            Factura #
                            @if($sortField === 'number')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th>Empresa (Tenant)</th>
                        <th class="cursor-pointer" wire:click="sortBy('amount')">
                            Monto
                            @if($sortField === 'amount')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th>Plan</th>
                        <th class="text-center">Estado</th>
                        <th class="pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $inv)
                        <tr wire:key="inv-{{ $inv->id }}">
                            <td class="ps-4 fw-black text-dark">{{ $inv->number }}</td>
                            <td>
                                <div class="fw-bold">{{ $inv->tenant->name }}</div>
                                <div class="small text-muted">{{ $inv->tenant->subdomain }}.logisaas.com</div>
                            </td>
                            <td class="fw-black text-primary">${{ number_format($inv->amount, 2) }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $inv->plan?->name ?? 'N/A' }}</span></td>
                            <td class="text-center">
                                @php
                                    $isOverdue = $inv->status === 'unpaid' && $inv->due_date && $inv->due_date < now()->today();
                                    $badgeClass = match($inv->status) {
                                        'paid' => 'bg-success',
                                        'unpaid' => $isOverdue ? 'bg-danger' : 'bg-warning',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} text-uppercase" style="font-size: 0.6rem;">
                                    {{ $isOverdue ? 'VENCIDA' : $inv->status }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                @if($inv->status !== 'paid')
                                    <button wire:click="markAsPaid({{ $inv->id }})" class="btn btn-sm btn-success fw-bold">MARCAR PAGADA</button>
                                @endif
                                <button class="btn btn-sm btn-light border"><i data-feather="download" style="width: 14px;"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-5 text-center text-muted italic">No hay facturas de suscripción registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer border-top bg-light">
            {{ $invoices->links() }}
        </div>
    </div>

    <!-- Create Invoice Modal -->
    <div class="modal fade" id="createInvoiceModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
                <div class="modal-header bg-dark text-white p-4">
                    <h5 class="modal-title uppercase font-black tracking-widest text-white">Generar Factura SaaS</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="createInvoice">
                    <div class="modal-body p-4 bg-light">
                        <div class="mb-3">
                            <label class="form-label small font-black text-uppercase text-muted">Seleccionar Empresa</label>
                            <select wire:model="tenant_id" class="form-select border-2 fw-bold">
                                <option value="">Elija un tenant...</option>
                                @foreach($tenants as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->subdomain }})</option>
                                @endforeach
                            </select>
                            @error('tenant_id') <div class="text-danger xsmall mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label small font-black text-uppercase text-muted">Monto a Cobrar ($)</label>
                                <input type="number" step="0.01" wire:model="amount" class="form-control border-2 fw-bold">
                                @error('amount') <div class="text-danger xsmall mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label small font-black text-uppercase text-muted">Fecha de Vencimiento</label>
                                <input type="date" wire:model="due_date" class="form-control border-2 fw-bold">
                                @error('due_date') <div class="text-danger xsmall mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small font-black text-uppercase text-muted">Notas Internas</label>
                            <textarea wire:model="notes" rows="2" class="form-control border-2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-white p-4">
                        <button type="button" class="btn btn-light border fw-bold" data-bs-dismiss="modal">CANCELAR</button>
                        <button type="submit" class="btn btn-primary px-4 fw-black text-uppercase shadow-lg">GENERAR FACTURA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openInvoiceModal() {
            var el = document.getElementById('createInvoiceModal');
            var myModal = bootstrap.Modal.getOrCreateInstance(el);
            myModal.show();
        }
        window.addEventListener('close-modal', () => {
            bootstrap.Modal.getInstance(document.getElementById('createInvoiceModal')).hide();
        });
    </script>
</div>
