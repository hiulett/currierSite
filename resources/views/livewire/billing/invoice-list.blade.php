<div x-data="{ isPaying: @entangle('is_paying') }">
    <!-- Billing Stats -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter_status', '')" class="card flex-fill cursor-pointer border-0 shadow-sm transform transition hover:scale-102 bg-dark text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ $currency }} {{ number_format($stats['total_invoiced'], 2) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Total Facturado</p>
                        </div>
                        <div class="stat bg-white bg-opacity-25 text-white">
                            <i class="align-middle" data-feather="bar-chart-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter_status', 'unpaid')" class="card flex-fill cursor-pointer border-0 shadow-sm transform transition hover:scale-102 bg-danger text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ $currency }} {{ number_format($stats['unpaid_amount'], 2) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Pendiente de Cobro</p>
                        </div>
                        <div class="stat bg-white bg-opacity-25 text-white">
                            <i class="align-middle" data-feather="dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter_status', 'paid')" class="card flex-fill cursor-pointer border-0 shadow-sm transform transition hover:scale-102 bg-success text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ $currency }} {{ number_format($stats['paid_today'], 2) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Recaudado Hoy</p>
                        </div>
                        <div class="stat bg-white bg-opacity-25 text-white">
                            <i class="align-middle" data-feather="check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter_status', 'overdue')" class="card flex-fill cursor-pointer border-0 shadow-sm transform transition hover:scale-102 bg-warning text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ $stats['overdue_count'] }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Facturas Vencidas</p>
                        </div>
                        <div class="stat bg-white bg-opacity-25 text-white">
                            <i class="align-middle" data-feather="alert-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Removed Graph per user request -->

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4 border-0" role="alert">
            <div class="alert-message"><strong>¡Éxito!</strong> {{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-3">
                    <h5 class="card-title mb-0 uppercase font-black small">Gestión de Facturación</h5>
                </div>
                <div class="col-md-9 text-md-end">
                    <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                        <button onclick="openInvoiceModal()" class="btn btn-primary fw-black text-uppercase">
                            <i class="align-middle me-1" data-feather="plus"></i> Nueva Factura
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="card-body bg-light border-bottom p-3">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-0"><i data-feather="search" style="width: 14px;"></i></span>
                        <input type="text" wire:model.live="search" class="form-control border-0" placeholder="Buscar factura, cliente o PTY...">
                    </div>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="filter_status" class="form-select form-select-sm border-0">
                        <option value="">Todos los Estados</option>
                        <option value="unpaid">Pendientes</option>
                        <option value="paid">Pagadas</option>
                        <option value="overdue">Vencidas</option>
                        <option value="cancelled">Anuladas</option>
                        <option value="email_sent">✉ Correo Enviado</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-0 text-muted xsmall uppercase font-bold">Desde</span>
                        <input type="date" wire:model.live="filter_date_from" class="form-control border-0">
                        <span class="input-group-text bg-white border-0 text-muted xsmall uppercase font-bold">Hasta</span>
                        <input type="date" wire:model.live="filter_date_to" class="form-control border-0">
                    </div>
                </div>
                <div class="col-md-3 text-end">
                    @if(!empty($selected_invoices))
                        <button wire:click="openPaymentModal()" class="btn btn-sm btn-success fw-black text-uppercase px-3 shadow-sm">
                            <i class="align-middle me-1" data-feather="check-circle"></i> Cobrar Selección ({{ count($selected_invoices) }})
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 40px;" class="ps-4">
                            <input type="checkbox" class="form-check-input" wire:model.live="selectAll">
                        </th>
                        <th class="cursor-pointer" wire:click="sortBy('number')">Factura / Fecha</th>
                        <th>Cliente</th>
                        <th class="cursor-pointer" wire:click="sortBy('total')">Monto Total</th>
                        <th>Estado</th>
                        <th class="pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr class="{{ in_array($invoice->id, $selected_invoices) ? 'bg-primary bg-opacity-5' : '' }}">
                            <td class="ps-4">
                                <input type="checkbox" class="form-check-input" value="{{ $invoice->id }}" wire:model.live="selected_invoices">
                            </td>
                            <td>
                                <div class="fw-black text-dark">{{ $invoice->number }}</div>
                                <div class="text-muted xsmall font-bold uppercase">{{ $invoice->created_at->format('d M, Y') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $invoice->customer?->user?->name ?? 'S/D' }}</div>
                                <div class="text-primary small font-black uppercase tracking-tighter">{{ $invoice->customer?->box_number }}</div>
                            </td>
                            <td>
                                <div class="fw-black text-dark">{{ $currency }} {{ number_format($invoice->total, 2) }}</div>
                                <div class="text-muted xsmall">Sub: {{ $currency }} {{ number_format($invoice->subtotal, 2) }}</div>
                            </td>
                            <td>
                                @php
                                    $isOverdue = $invoice->status === 'unpaid' && $invoice->due_date && $invoice->due_date < now()->today();
                                    $statusColor = [
                                        'paid'      => '#1cbb8c',
                                        'unpaid'    => ($isOverdue ? '#dc3545' : '#fcb92c'),
                                        'cancelled' => '#6c757d',
                                    ][$invoice->status] ?? '#6c757d';
                                    $statusLabel = [
                                        'paid'      => 'Pagada',
                                        'unpaid'    => ($isOverdue ? 'Vencida' : 'Pendiente'),
                                        'cancelled' => 'Anulada',
                                    ][$invoice->status] ?? ucfirst($invoice->status);
                                @endphp
                                <div class="d-flex align-items-center gap-1">
                                    <span class="badge text-uppercase" style="font-size: 0.65rem; background-color: {{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                    @if($invoice->email_sent_at)
                                        <span title="Correo enviado el {{ $invoice->email_sent_at->format('d/m/Y H:i') }}" class="badge" style="font-size: 0.65rem; background-color: #0d9488;">✉ Enviado</span>
                                    @endif
                                </div>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group shadow-none">
                                    <a href="{{ route('billing.download', $invoice) }}" target="_blank" class="btn btn-sm btn-light border" title="Imprimir PDF">
                                        <i class="align-middle text-primary" data-feather="printer" style="width: 14px;"></i>
                                    </a>
                                    <button wire:click="sendEmail({{ $invoice->id }})" class="btn btn-sm btn-light border" title="Enviar Email">
                                        <i class="align-middle text-info" data-feather="mail" style="width: 14px;"></i>
                                    </button>
                                    @if($invoice->status !== 'paid')
                                        <button wire:click="openPaymentModal({{ $invoice->id }})" class="btn btn-sm btn-success fw-bold px-3">
                                            COBRAR
                                        </button>
                                        <button wire:click="voidInvoice({{ $invoice->id }})" wire:confirm="¿Seguro que deseas anular esta factura?" class="btn btn-sm btn-outline-danger" title="Anular Factura">
                                            <i class="align-middle" data-feather="slash" style="width: 14px;"></i>
                                        </button>
                                    @endif
                                    <button wire:click="deleteInvoice({{ $invoice->id }})" wire:confirm="¿Seguro que deseas eliminar permanentemente esta factura?" class="btn btn-sm btn-outline-danger" title="Eliminar Factura">
                                        <i class="align-middle" data-feather="trash-2" style="width: 14px;"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted italic">No se encontraron facturas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-top">
            {{ $invoices->links() }}
        </div>
    </div>

    <!-- Payment Processing Sidebar (Slide-over) -->
    <div x-show="isPaying"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="position-fixed top-0 end-0 h-100 bg-white shadow-lg z-3 border-start"
         style="width: 400px; display: none; z-index: 1060;">

        <div class="h-100 d-flex flex-column">
            <div class="p-4 bg-success text-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="modal-title uppercase font-black tracking-widest text-white mb-0">Registrar Cobro</h5>
                    <button @click="isPaying = false" class="btn-close btn-close-white"></button>
                </div>
                <p class="mb-0 small opacity-75">Confirma el ingreso de dinero para las facturas seleccionadas.</p>
            </div>

            <div class="flex-grow-1 overflow-y-auto p-4">
                <div class="mb-4">
                    <label class="form-label small font-black text-uppercase text-muted">Método de Pago</label>
                    <select wire:model="payment_method" class="form-select border-2 fw-bold">
                        <option value="cash">Efectivo</option>
                        <option value="yappy">Yappy</option>
                        <option value="ach">Transferencia / ACH</option>
                        <option value="card">Tarjeta Crédito/Débito</option>
                        <option value="wallet">Billetera (Saldo Interno)</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label small font-black text-uppercase text-muted">Referencia / Comprobante</label>
                    <input type="text" wire:model="payment_reference" class="form-control border-2" placeholder="Nº de transacción o comentario...">
                </div>

                <div class="alert bg-light border-0 small">
                    <i class="align-middle me-1 text-info" data-feather="info" style="width: 14px;"></i>
                    Al confirmar, el estado de la factura cambiará a <strong>PAGADA</strong> y se descontará del saldo pendiente del cliente.
                </div>
            </div>

            <div class="p-4 bg-light border-top">
                <button wire:click="confirmPayment" wire:loading.attr="disabled" class="btn btn-success btn-lg w-100 fw-black uppercase py-3 shadow-lg">
                    <span wire:loading.remove>CONFIRMAR PAGO</span>
                    <span wire:loading><span class="spinner-border spinner-border-sm me-2"></span>PROCESANDO...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Background Overlay for Slide-over -->
    <div x-show="isPaying" @click="isPaying = false" x-transition:opacity class="position-fixed top-0 start-0 w-100 h-100 bg-dark opacity-50 z-2" style="display: none; z-index: 1055;"></div>

    <!-- Modal for Creating Invoice (Bootstrap 5 Style) -->
    <div class="modal fade" id="modalCreateInvoice" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" style="border-radius: 1rem; overflow: hidden;">
                <div class="modal-header bg-primary text-white p-4">
                    <h5 class="modal-title uppercase font-black tracking-widest">
                        <i class="align-middle me-2" data-feather="file-text"></i> Generar Nueva Factura
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light p-0">
                    @livewire('billing.create-invoice')
                </div>
            </div>
        </div>
    </div>

    <script>
        function openInvoiceModal() {
            var el = document.getElementById('modalCreateInvoice');
            var myModal = bootstrap.Modal.getOrCreateInstance(el);
            myModal.show();
        }

        window.addEventListener('invoice-saved', event => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalCreateInvoice')).hide();
        });
    </script>
</div>
