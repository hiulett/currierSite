<div>
    <!-- Metrics Dashboard -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-primary text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ $currency }} {{ number_format($stats['total_pipeline'], 2) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Pipeline Potencial</p>
                        </div>
                        <div class="stat bg-white bg-opacity-25 text-white">
                            <i class="align-middle" data-feather="trending-up"></i>
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
                            <h3 class="mb-2 text-white">{{ $currency }} {{ number_format($stats['total_won'], 2) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Ingresos Cerrados</p>
                        </div>
                        <div class="stat bg-white bg-opacity-25 text-white">
                            <i class="align-middle" data-feather="dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-info text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ $stats['conversion_rate'] }}%</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Tasa de Conversión</p>
                        </div>
                        <div class="stat bg-white bg-opacity-25 text-white">
                            <i class="align-middle" data-feather="activity"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm bg-dark text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white">{{ $stats['draft_count'] + $stats['sent_count'] + $stats['accepted_count'] + $stats['rejected_count'] }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Total Cotizaciones</p>
                        </div>
                        <div class="stat bg-white bg-opacity-25 text-white">
                            <i class="align-middle" data-feather="file-text"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="card-title mb-0 uppercase font-black small text-muted" style="letter-spacing: 0.05em;">Volumen de Cotizaciones (Últimos 6 meses)</h5>
                </div>
                <div class="card-body pb-4">
                    <div style="height: 250px; position: relative;">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="card-title mb-0 uppercase font-black small text-muted" style="letter-spacing: 0.05em;">Distribución por Estado</h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center pb-4">
                    <div style="width: 100%; height: 250px; position: relative;">
                        <canvas id="pieChart"></canvas>
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

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-3">
                    <h5 class="card-title mb-0 uppercase font-black small">Gestión de Cotizaciones</h5>
                </div>
                <div class="col-md-9 text-md-end">
                    <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                        <button onclick="openQuotationModal()" class="btn btn-primary fw-black text-uppercase">
                            <i class="align-middle me-1" data-feather="plus"></i> Nueva Cotización
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-body bg-light border-bottom p-3">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-0"><i data-feather="search" style="width: 14px;"></i></span>
                        <input type="text" wire:model.live="search" class="form-control border-0" placeholder="Buscar cotización o cliente...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="filter_status" class="form-select form-select-sm border-0">
                        <option value="">Todos los Estados</option>
                        <option value="draft">Borrador</option>
                        <option value="sent">Enviada</option>
                        <option value="email_sent">✉ Correo Enviado</option>
                        <option value="accepted">Aceptada</option>
                        <option value="rejected">Rechazada</option>
                        <option value="invoiced">Facturada</option>
                    </select>
                </div>
                <div class="col-md-6">
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
                        <th class="ps-4 cursor-pointer" wire:click="sortBy('number')">Cotización / Fecha</th>
                        <th>Cliente</th>
                        <th class="cursor-pointer" wire:click="sortBy('total')">Monto Total</th>
                        <th>Estado</th>
                        <th class="pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quotations as $quotation)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-black text-dark">{{ $quotation->number }}</div>
                                <div class="text-muted xsmall font-bold uppercase">{{ $quotation->created_at->format('d M, Y') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">
                                    @if($quotation->customer?->user)
                                        {{ $quotation->customer->user->name }}
                                    @else
                                        {{ $quotation->client_name ?? 'S/D' }} <span class="badge bg-light text-secondary border font-normal ms-1" style="font-size: 0.65rem; font-weight: normal;">No Registrado</span>
                                    @endif
                                </div>
                                <div class="text-primary small font-black uppercase tracking-tighter">
                                    {{ $quotation->customer?->user?->email ?? $quotation->client_email }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-black text-dark">{{ $currency }} {{ number_format($quotation->total, 2) }}</div>
                                <div class="text-muted xsmall">Sub: {{ $currency }} {{ number_format($quotation->subtotal, 2) }}</div>
                            </td>
                            <td>
                                @php
                                    $statusColor = [
                                        'draft'      => '#6c757d',
                                        'sent'       => '#17a2b8',
                                        'email_sent' => '#0d9488',
                                        'accepted'   => '#28a745',
                                        'rejected'   => '#dc3545',
                                        'invoiced'   => '#6f42c1',
                                    ][$quotation->status] ?? '#6c757d';
                                    
                                    $statusLabel = [
                                        'draft'      => 'Borrador',
                                        'sent'       => 'Enviada',
                                        'email_sent' => '✉ Correo Enviado',
                                        'accepted'   => 'Aceptada',
                                        'rejected'   => 'Rechazada',
                                        'invoiced'   => 'Facturada',
                                    ][$quotation->status] ?? $quotation->status;
                                @endphp
                                <div class="dropdown">
                                    <button class="btn btn-sm text-white text-uppercase" style="background-color: {{ $statusColor }}; font-size: 0.65rem;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ $statusLabel }} <i class="align-middle ms-1" data-feather="chevron-down" style="width: 12px; height: 12px;"></i>
                                    </button>
                                    <ul class="dropdown-menu shadow border-0" style="font-size: 0.85rem;">
                                        <li><h6 class="dropdown-header">Cambiar Estado</h6></li>
                                        <li><a class="dropdown-item" href="#" wire:click.prevent="markAsStatus({{ $quotation->id }}, 'draft')"><i class="align-middle me-1 text-muted" data-feather="edit-2" style="width: 14px;"></i> Borrador</a></li>
                                        <li><a class="dropdown-item" href="#" wire:click.prevent="markAsStatus({{ $quotation->id }}, 'sent')"><i class="align-middle me-1 text-info" data-feather="send" style="width: 14px;"></i> Enviada</a></li>
                                        <li><a class="dropdown-item" href="#" wire:click.prevent="markAsStatus({{ $quotation->id }}, 'accepted')"><i class="align-middle me-1 text-success" data-feather="check-circle" style="width: 14px;"></i> Aceptada</a></li>
                                        <li><a class="dropdown-item" href="#" wire:click.prevent="markAsStatus({{ $quotation->id }}, 'rejected')"><i class="align-middle me-1 text-danger" data-feather="x-circle" style="width: 14px;"></i> Rechazada</a></li>
                                        <li><a class="dropdown-item" href="#" wire:click.prevent="markAsStatus({{ $quotation->id }}, 'invoiced')"><i class="align-middle me-1" data-feather="file-text" style="color: #6f42c1; width: 14px;"></i> Facturada</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" wire:click.prevent="markAsStatus({{ $quotation->id }}, 'email_sent')"><i class="align-middle me-1" data-feather="mail" style="color: #0d9488; width: 14px;"></i> ✉ Correo Enviado</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group shadow-none">
                                    <a href="{{ route('billing.quotations.download', $quotation) }}" target="_blank" class="btn btn-sm btn-light border" title="Imprimir PDF">
                                        <i class="align-middle text-primary" data-feather="printer" style="width: 14px;"></i>
                                    </a>
                                    <button wire:click="sendEmail({{ $quotation->id }})" class="btn btn-sm btn-light border" title="Enviar Email al Cliente">
                                        <i class="align-middle text-info" data-feather="mail" style="width: 14px;"></i>
                                    </button>
                                    <button wire:click="deleteQuotation({{ $quotation->id }})" wire:confirm="¿Seguro que deseas eliminar esta cotización?" class="btn btn-sm btn-outline-danger" title="Eliminar Cotización">
                                        <i class="align-middle" data-feather="trash-2" style="width: 14px;"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted italic">No se encontraron cotizaciones.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-top">
            {{ $quotations->links() }}
        </div>
    </div>

    <!-- Modal for Creating Quotation -->
    <div class="modal fade" id="modalCreateQuotation" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" style="border-radius: 1rem; overflow: hidden;">
                <div class="modal-header bg-primary text-white p-4">
                    <h5 class="modal-title uppercase font-black tracking-widest">
                        <i class="align-middle me-2" data-feather="file-text"></i> Generar Nueva Cotización
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light p-0">
                    @livewire('billing.create-quotation')
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts and Styles for Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.openQuotationModal = function() {
            var el = document.getElementById('modalCreateQuotation');
                var myModal = bootstrap.Modal.getOrCreateInstance(el);
                Livewire.dispatch('openCreateQuotationModal');
                myModal.show();
            }

            window.addEventListener('quotation-saved', event => {
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalCreateQuotation')).hide();
            });

            document.addEventListener('livewire:initialized', () => {
                const initCharts = () => {
                    const ctxBar = document.getElementById('barChart');
                    const ctxPie = document.getElementById('pieChart');

                    if (ctxBar && !window.quotationBarChart) {
                        const ctx = ctxBar.getContext('2d');
                        const gradient = ctx.createLinearGradient(0, 0, 0, 240);
                        gradient.addColorStop(0, 'rgba(13, 110, 253, 0.25)');
                        gradient.addColorStop(1, 'rgba(13, 110, 253, 0.00)');

                        window.quotationBarChart = new Chart(ctxBar, {
                            type: 'line',
                            data: {
                                labels: {!! json_encode($chartLabels) !!},
                                datasets: [{
                                    label: 'Cotizaciones Generadas',
                                    data: {!! json_encode($chartData) !!},
                                    borderColor: '#0d6efd',
                                    borderWidth: 3,
                                    backgroundColor: gradient,
                                    fill: true,
                                    tension: 0.35,
                                    pointBackgroundColor: '#ffffff',
                                    pointBorderColor: '#0d6efd',
                                    pointBorderWidth: 2,
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                    pointHoverBackgroundColor: '#0d6efd',
                                    pointHoverBorderColor: '#ffffff',
                                    pointHoverBorderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        padding: 12,
                                        cornerRadius: 8,
                                        backgroundColor: '#1e293b',
                                        titleFont: { family: 'Inter, system-ui', weight: 'bold' },
                                        bodyFont: { family: 'Inter, system-ui' }
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: {
                                            display: false
                                        },
                                        ticks: {
                                            font: {
                                                family: 'Inter, system-ui',
                                                size: 11
                                            }
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            stepSize: 1,
                                            font: {
                                                family: 'Inter, system-ui',
                                                size: 11
                                            }
                                        },
                                        grid: {
                                            color: '#e2e8f0',
                                            borderDash: [5, 5]
                                        }
                                    }
                                }
                            }
                        });
                    }

                    if (ctxPie && !window.quotationPieChart) {
                        window.quotationPieChart = new Chart(ctxPie, {
                            type: 'doughnut',
                            data: {
                                labels: ['Borrador', 'Enviada', 'Aceptada', 'Rechazada'],
                                datasets: [{
                                    data: {!! json_encode($pieChartData) !!},
                                    backgroundColor: ['#94a3b8', '#38bdf8', '#10b981', '#f43f5e'],
                                    hoverOffset: 4,
                                    borderWidth: 2,
                                    borderColor: '#ffffff'
                                }]
                            },
                            plugins: [{
                                id: 'centerText',
                                beforeDraw(chart) {
                                    const { ctx } = chart;
                                    const meta = chart.getDatasetMeta(0);
                                    if (meta.data && meta.data.length > 0) {
                                        const center = meta.data[0];
                                        const centerX = center.x;
                                        const centerY = center.y;

                                        ctx.save();
                                        ctx.textAlign = 'center';
                                        ctx.textBaseline = 'middle';

                                        // Top label
                                        ctx.font = '500 11px Inter, system-ui';
                                        ctx.fillStyle = '#64748b';
                                        ctx.fillText('TOTAL', centerX, centerY - 10);

                                        // Bottom value
                                        ctx.font = 'bold 20px Inter, system-ui';
                                        ctx.fillStyle = '#1e293b';
                                        const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                        ctx.fillText(total, centerX, centerY + 12);

                                        ctx.restore();
                                    }
                                }
                            }],
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '75%',
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            boxWidth: 10,
                                            padding: 15,
                                            font: {
                                                family: 'Inter, system-ui',
                                                size: 11
                                            }
                                        }
                                    },
                                    tooltip: {
                                        padding: 12,
                                        cornerRadius: 8,
                                        backgroundColor: '#1e293b',
                                        titleFont: { family: 'Inter, system-ui', weight: 'bold' },
                                        bodyFont: { family: 'Inter, system-ui' }
                                    }
                                }
                            }
                        });
                    }
                };

                initCharts();

                // Re-init when livewire component is updated
                Livewire.hook('request', ({ respond }) => {
                    respond(() => {
                        if (window.quotationBarChart) window.quotationBarChart.destroy();
                        if (window.quotationPieChart) window.quotationPieChart.destroy();
                        window.quotationBarChart = null;
                        window.quotationPieChart = null;
                        setTimeout(initCharts, 100);
                    });
                });
            });
    </script>
</div>
