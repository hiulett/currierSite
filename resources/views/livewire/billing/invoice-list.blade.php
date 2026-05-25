<div>
    <!-- Billing Dashboard -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div wire:click="$set('filter_status', '')" class="card flex-fill cursor-pointer border-0 shadow-sm transform transition hover:scale-102 bg-dark text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ $currency }} {{ number_format($stats['total_invoiced'], 2) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Total Facturado</p>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="bar-chart-2"></i>
                            </div>
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
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="dollar-sign"></i>
                            </div>
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
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="check-circle"></i>
                            </div>
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
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="file-text"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart for Billing -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" id="card-billing-revenue">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 uppercase font-black small text-primary">{{ __('Historial de Facturación Mensual (:year)', ['year' => date('Y')]) }}</h5>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-light border" onclick="toggleFullscreen('card-billing-revenue')">
                            <i data-feather="maximize" style="width: 14px; height: 14px;"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart chart-md" style="height: 250px;">
                        <canvas id="chartjs-revenue-billing"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message"><strong>¡Éxito!</strong> {{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card flex-fill">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center bg-light border-bottom gap-3">
            <h5 class="card-title mb-0 uppercase font-black small">Gestión de Facturación</h5>
            <div class="d-flex flex-wrap gap-2 w-100 w-md-auto">
                <button onclick="openInvoiceModal()" class="btn btn-primary shadow-sm fw-black">
                    <i class="align-middle me-1" data-feather="plus-circle"></i> NUEVA FACTURA
                </button>
                <select wire:model.live="filter_status" class="form-select form-select-sm" style="width: 150px;">
                    <option value="">Todos los Estados</option>
                    <option value="unpaid">Pendientes</option>
                    <option value="paid">Pagadas</option>
                    <option value="overdue">Vencidas ({{ $stats['overdue_count'] }})</option>
                    <option value="cancelled">Anuladas</option>
                </select>
                <div class="input-group input-group-sm flex-grow-1" style="min-width: 250px;">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Buscar factura o casillero...">
                    <span class="input-group-text bg-white"><i class="align-middle" data-feather="search"></i></span>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped my-0">
                <thead>
                    <tr>
                        <th class="ps-4 cursor-pointer" wire:click="sortBy('number')">
                            Nº Factura
                            @if($sortField === 'number')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th>Cliente</th>
                        <th class="cursor-pointer" wire:click="sortBy('total')">
                            Total
                            @if($sortField === 'total')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="cursor-pointer" wire:click="sortBy('status')">
                            Estado
                            @if($sortField === 'status')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="cursor-pointer" wire:click="sortBy('created_at')">
                            Fecha
                            @if($sortField === 'created_at')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td class="ps-4 fw-black text-dark">{{ $invoice->number }}</td>
                            <td>
                                <div class="fw-bold leading-none">{{ $invoice->customer->user->name }}</div>
                                <div class="text-primary small font-black uppercase tracking-tighter">{{ $invoice->customer->box_number }}</div>
                            </td>
                            <td class="fw-black text-dark">{{ $currency }} {{ number_format($invoice->total, 2) }}</td>
                            <td>
                                @php
                                    $isOverdue = $invoice->status === 'unpaid' && $invoice->due_date && $invoice->due_date < now()->today();
                                    $statusClass = [
                                        'paid' => 'bg-success',
                                        'unpaid' => ($isOverdue ? 'bg-danger' : 'bg-warning'),
                                        'cancelled' => 'bg-secondary',
                                    ][$invoice->status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $statusClass }} text-uppercase" style="font-size: 0.65rem;">
                                    {{ $isOverdue ? 'VENCIDA' : $invoice->status }}
                                </span>
                            </td>
                            <td class="small fw-bold text-muted">
                                {{ $invoice->created_at->format('d M, Y') }}
                                @if($invoice->status === 'unpaid')
                                    <div class="text-xs {{ $isOverdue ? 'text-danger' : '' }}">Vence: {{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : 'N/A' }}</div>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group">
                                    <a href="{{ route('billing.download', $invoice) }}" target="_blank" class="btn btn-sm btn-light border shadow-sm" title="Imprimir PDF">
                                        <i class="align-middle text-primary" data-feather="printer"></i>
                                    </a>
                                    <button wire:click="sendEmail({{ $invoice->id }})" wire:loading.attr="disabled" class="btn btn-sm btn-light border shadow-sm" title="Enviar por Correo">
                                        <i class="align-middle text-info" data-feather="mail"></i>
                                    </button>
                                    @if($invoice->status !== 'paid')
                                        <button wire:click="markAsPaid({{ $invoice->id }})" wire:loading.attr="disabled" class="btn btn-sm btn-light border text-success shadow-sm" title="Marcar como Pagada">
                                            <i class="align-middle" data-feather="check"></i>
                                            <span class="ms-1 d-none d-md-inline fw-bold text-uppercase" style="font-size: 0.65rem;">Cobrar</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted italic">
                                No se encontraron facturas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $invoices->links() }}
        </div>
    </div>

    <!-- Modal for Creating Invoice (Bootstrap 5 Style) -->
    <div class="modal fade" id="modalCreateInvoice" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                <div class="modal-header bg-primary text-white p-4">
                    <h5 class="modal-title uppercase font-black tracking-widest">
                        <i class="align-middle me-2" data-feather="file-text"></i> Generar Nueva Factura
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light p-0">
                    <div class="p-3 p-md-4">
                        @livewire('billing.create-invoice')
                    </div>
                </div>
                <div class="modal-footer bg-white border-top-0 p-3">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">CERRAR VENTANA</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFullscreen(cardId) {
            const card = document.getElementById(cardId);
            card.classList.toggle('card-fullscreen');

            setTimeout(() => {
                if (window.revenueChartBilling) window.revenueChartBilling.resize();
            }, 100);
        }

        function openInvoiceModal() {
            var el = document.getElementById('modalCreateInvoice');
            var myModal = bootstrap.Modal.getOrCreateInstance(el);
            myModal.show();
        }

        window.addEventListener('invoice-saved', event => {
            // Optional: Close after success
            // bootstrap.Modal.getOrCreateInstance(document.getElementById('modalCreateInvoice')).hide();
        });

        // Initialize Revenue Chart
        function initRevenueChart() {
            const ctx = document.getElementById("chartjs-revenue-billing");
            if (!ctx) return;

            if (window.revenueChartBilling) window.revenueChartBilling.destroy();

            const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim() || '#3b7ddd';
            const projectionColor = '#adb5bd';

            window.revenueChartBilling = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                    datasets: [
                        {
                            label: "{{ __('Ingresos Reales') }}",
                            backgroundColor: primaryColor,
                            borderColor: primaryColor,
                            data: @json($revenueData),
                            barPercentage: .5,
                            categoryPercentage: .5
                        },
                        {
                            label: "{{ __('Proyección / Meta') }}",
                            type: 'line',
                            backgroundColor: 'transparent',
                            borderColor: projectionColor,
                            borderDash: [5, 5],
                            pointRadius: 0,
                            data: @json($projectionData),
                            tension: 0.1,
                            fill: false
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ' {{ $currency }} ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [2, 2] },
                            ticks: {
                                callback: function(value) {
                                    return '{{ $currency }} ' + value.toLocaleString();
                                }
                            }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        document.addEventListener("livewire:navigated", initRevenueChart);
        document.addEventListener("DOMContentLoaded", initRevenueChart);
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', ({ el, component }) => {
                requestAnimationFrame(() => {
                    initRevenueChart();
                });
            });
        });
    </script>
</div>
