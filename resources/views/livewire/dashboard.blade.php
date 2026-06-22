<div>
    <div class="row mb-2">
        <div class="col-12 col-md-6">
            <h1 class="h4 mb-0 uppercase font-black tracking-tight">{{ __('Resumen de Operaciones') }}</h1>
            <p class="text-muted xsmall mb-0">{{ __('Control estadístico de paquetes y facturación.') }}</p>
        </div>
        <div class="col-12 col-md-6 text-md-end">
            <div class="btn-group btn-group-sm shadow-sm">
                <button class="btn btn-white border dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="align-middle text-primary me-2" data-feather="calendar"></i>
                    {{ $days == 0 ? __('Hoy') : ($days == 30 ? __('Último Mes') : __('Últimos :days Días', ['days' => $days])) }}
                </button>
                <div class="dropdown-menu">
                    <button wire:click="setFilter(0)" class="dropdown-item">{{ __('Hoy') }}</button>
                    <button wire:click="setFilter(7)" class="dropdown-item">{{ __('Últimos 7 días') }}</button>
                    <button wire:click="setFilter(30)" class="dropdown-item">{{ __('Último Mes') }}</button>
                    <button wire:click="setFilter(90)" class="dropdown-item">{{ __('Últimos 90 días') }}</button>
                </div>
                <button wire:click="refresh" class="btn btn-primary fw-black">
                    <i class="align-middle me-1" data-feather="refresh-cw"></i> {{ __('ACTUALIZAR') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Modern Action Center (Horizontal & Compact) -->
    @if(!empty($actionAlerts))
        <div class="mb-4">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-dark text-white rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i data-feather="zap" style="width: 14px; height: 14px;"></i>
                </div>
                <h5 class="mb-0 uppercase font-black small tracking-widest text-dark">{{ __('Tareas que requieren tu atención') }}</h5>
                <div class="ms-auto">
                    <span class="badge bg-light text-dark border xsmall font-bold px-2 py-1">{{ count($actionAlerts) }} {{ __('ALERTAS') }}</span>
                </div>
            </div>

            <div class="d-flex overflow-x-auto pb-2 gap-3 no-scrollbar" style="scrollbar-width: none; -ms-overflow-style: none;">
                <style>
                    .no-scrollbar::-webkit-scrollbar { display: none; }
                    .alert-pill {
                        min-width: 280px;
                        max-width: 280px;
                        flex-shrink: 0;
                    }
                </style>
                @foreach($actionAlerts as $alert)
                    <a href="{{ $alert['link'] }}" class="alert-pill card border-0 shadow-sm mb-0 transform transition hover:scale-102 text-decoration-none">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-{{ $alert['type'] }} bg-opacity-10 text-{{ $alert['type'] }} rounded-3 p-2 me-3">
                                    <i class="align-middle" data-feather="{{ $alert['icon'] }}" style="width: 20px; height: 20px;"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-black text-dark text-uppercase xsmall">{{ __($alert['title']) }}</h6>
                                        <span class="badge bg-{{ $alert['type'] }} rounded-pill font-black" style="font-size: 0.7rem;">{{ $alert['count'] }}</span>
                                    </div>
                                    <div class="text-muted text-truncate mt-1" style="font-size: 0.65rem;">{{ __($alert['text']) }}</div>
                                </div>
                                <div class="ms-2">
                                    <i class="text-muted" data-feather="chevron-right" style="width: 14px;"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-xl-3 d-flex">
            <a href="{{ route('logistics.inventory') }}" class="card flex-fill border-0 shadow-sm overflow-hidden transform transition hover:scale-102 text-decoration-none bg-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 fw-black text-white">{{ number_format($total_packages) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75 d-flex align-items-center">
                                {{ __('Paquetes Totales') }}
                                <i class="align-middle ms-1 text-white opacity-75" data-feather="help-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Total de paquetes recibidos en bodega que están en proceso de entrega."></i>
                            </p>
                        </div>
                        <div class="stat bg-white bg-opacity-25 text-white">
                            <i class="align-middle" data-feather="package"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm overflow-hidden bg-success text-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white fw-black">{{ $currency }} {{ number_format($total_profit, 2) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75 d-flex align-items-center">
                                {{ __('Ganancia Real Acumulada') }}
                                <i class="align-middle ms-1 text-white opacity-75" data-feather="help-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Facturación total facturada menos egresos generales (y costo de fletes, según su preferencia en Ajustes Generales)."></i>
                            </p>
                        </div>
                        <div class="stat bg-white bg-opacity-25 text-white">
                            <i class="align-middle" data-feather="trending-up"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm overflow-hidden bg-info text-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white fw-black">{{ $currency }} {{ number_format($projected_profit, 2) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75 d-flex align-items-center">
                                {{ __('Ganancia Proyectada (Bodega)') }}
                                <i class="align-middle ms-1 text-white opacity-75" data-feather="help-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Ingresos estimados para los paquetes actualmente almacenados basándose en su peso y la tarifa por defecto, menos el costo estimado del flete."></i>
                            </p>
                        </div>
                        <div class="stat bg-white bg-opacity-25 text-white">
                            <i class="align-middle" data-feather="eye"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm overflow-hidden bg-dark text-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white fw-black">{{ number_format($avg_roi, 1) }}%</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75 d-flex align-items-center">
                                {{ __('ROI Promedio (Retorno)') }}
                                <i class="align-middle ms-1 text-white opacity-75" data-feather="help-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Margen de retorno promedio basado en los precios de facturación al cliente final contra los costos cobrados por proveedores de flete."></i>
                            </p>
                        </div>
                        <div class="stat bg-white bg-opacity-25 text-white">
                            <i class="align-middle" data-feather="percent"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row sortable-cards" id="dashboard-charts">
        <!-- Monthly Revenue vs Cost Chart -->
        <div class="col-12 col-lg-8 grid-item mb-4">
            <div class="card shadow-sm border-0 h-100" id="card-revenue">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 uppercase font-black small text-primary">
                        <i class="align-middle me-2 cursor-grab" data-feather="grid"></i>
                        {{ __('Rendimiento Financiero: Ingresos vs Costos') }}
                    </h5>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-light border" onclick="toggleFullscreen('card-revenue')">
                            <i data-feather="maximize" style="width: 14px; height: 14px;"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart chart-md" style="height: 300px;">
                        <canvas id="chartjs-revenue"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Movement Chart -->
        <div class="col-12 col-lg-4 grid-item mb-4">
            <div class="card shadow-sm border-0 h-100" id="card-movement">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 uppercase font-black small text-primary">
                        <i class="align-middle me-2 cursor-grab" data-feather="grid"></i>
                        {{ __('Volumen de Carga') }}
                    </h5>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-light border" onclick="toggleFullscreen('card-movement')">
                            <i data-feather="maximize" style="width: 14px; height: 14px;"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart chart-md" style="height: 300px;">
                        <canvas id="chartjs-movement"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Monthly Net Revenue vs Expenses Mixed Chart -->
        <div class="col-12 grid-item mb-4">
            <div class="card shadow-sm border-0" id="card-net-financials">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 uppercase font-black small text-primary">
                        <i class="align-middle me-2 cursor-grab" data-feather="grid"></i>
                        {{ __('Rendimiento Neto: Ingresos vs Egresos y Utilidad Neta') }}
                    </h5>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-light border" onclick="toggleFullscreen('card-net-financials')">
                            <i data-feather="maximize" style="width: 14px; height: 14px;"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart chart-lg" style="height: 350px;">
                        <canvas id="chartjs-net-financials"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row sortable-cards" id="dashboard-second-row">
        <!-- Recent Packages -->
        <div class="col-12 col-lg-8 grid-item mb-4">
            <div class="card shadow-sm border-0 h-100" id="card-recent-pkgs">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title mb-0 uppercase font-black small">
                        <i class="align-middle me-2 cursor-grab" data-feather="grid"></i>
                        {{ __('Últimos Ingresos de Carga') }}
                    </h5>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-light border" onclick="toggleFullscreen('card-recent-pkgs')">
                            <i data-feather="maximize" style="width: 14px; height: 14px;"></i>
                        </button>
                        <a href="{{ route('logistics.inventory') }}" class="btn btn-xs btn-light border text-uppercase fw-bold ms-2">{{ __('Ver Todo') }}</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">{{ __('Tracking') }}</th>
                                <th>{{ __('Cliente') }}</th>
                                <th>{{ __('Estado') }}</th>
                                <th class="pe-4 text-end">{{ __('Peso') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_packages as $pkg)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-black text-dark">{{ $pkg->tracking_number }}</div>
                                    <div class="small text-muted">{{ Str::limit($pkg->description, 30) }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $pkg->customer?->user?->name ?? 'S/D' }}</div>
                                    <div class="small text-primary font-black uppercase" style="font-size: 0.6rem;">{{ $pkg->customer?->box_number }}</div>
                                </td>
                                <td>
                                    <span class="badge text-uppercase" style="font-size: 0.6rem; background-color: {{ $pkg->getStatusColor() }}">
                                        {{ $pkg->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end fw-bold">{{ $pkg->weight }} lbs</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Warehouse Distribution -->
        <div class="col-12 col-xl-4 mt-4 mt-lg-0 grid-item">
            <div class="card shadow-sm border-0 h-100" id="card-warehouse">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 uppercase font-black small">
                        <i class="align-middle me-2 cursor-grab" data-feather="grid"></i>
                        {{ __('Distribución por Bodega') }}
                    </h5>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-light border" onclick="toggleFullscreen('card-warehouse')">
                            <i data-feather="maximize" style="width: 14px; height: 14px;"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart chart-sm mb-3">
                        <canvas id="chartjs-warehouse-pie"></canvas>
                    </div>
                    <table class="table table-sm mt-3 mb-0">
                        <tbody>
                            @foreach($warehouseLabels as $index => $label)
                            <tr>
                                <td class="small"><i class="fas fa-circle me-2 text-primary" style="font-size: 8px;"></i> {{ $label }}</td>
                                <td class="text-end fw-black small">{{ $warehouseData[$index] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Initialization Script -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        function toggleFullscreen(cardId) {
            const card = document.getElementById(cardId);
            card.classList.toggle('card-fullscreen');

            // Re-render charts to fit new size
            setTimeout(() => {
                if (window.movementChart) window.movementChart.resize();
                if (window.revenueChart) window.revenueChart.resize();
                if (window.warehouseChart) window.warehouseChart.resize();
                if (window.netFinancialsChart) window.netFinancialsChart.resize();
            }, 100);
        }

        document.addEventListener("livewire:navigated", initDashboardFeatures);
        document.addEventListener("DOMContentLoaded", initDashboardFeatures);

        function initDashboardFeatures() {
            initDashboardCharts();

            ['dashboard-charts', 'dashboard-second-row'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    new Sortable(el, {
                        animation: 150,
                        ghostClass: 'bg-light',
                        handle: '.cursor-grab',
                        onEnd: function() {
                            console.log('Grid reordered');
                        }
                    });
                }
            });
        }

        function initDashboardCharts() {
            if (!document.getElementById("chartjs-movement")) return;

            // Simple cleanup
            if (window.movementChart instanceof Chart) window.movementChart.destroy();
            if (window.revenueChart instanceof Chart) window.revenueChart.destroy();
            if (window.warehouseChart instanceof Chart) window.warehouseChart.destroy();
            if (window.netFinancialsChart instanceof Chart) window.netFinancialsChart.destroy();

            const primaryColor = '#3b7ddd';
            const successColor = '#28a745';
            const dangerColor = '#dc3545';
            const projectionColor = '#adb5bd';

            // 1. Movement Chart (Line)
            window.movementChart = new Chart(document.getElementById("chartjs-movement"), {
                type: "line",
                data: {
                    labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                    datasets: [{
                        label: "{{ __('Paquetes') }}",
                        fill: true,
                        backgroundColor: "rgba(59, 125, 221, 0.1)",
                        borderColor: primaryColor,
                        data: @json($chartData),
                        tension: 0.4
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { display: true, borderDash: [2, 2] } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // 2. Financial Chart (Bar + Line Meta)
            window.revenueChart = new Chart(document.getElementById("chartjs-revenue"), {
                type: "bar",
                data: {
                    labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                    datasets: [
                        {
                            label: "{{ __('Ingresos') }}",
                            backgroundColor: successColor,
                            data: @json($revenueData),
                            barPercentage: .5,
                            categoryPercentage: .5
                        },
                        {
                            label: "{{ __('Costos') }}",
                            backgroundColor: dangerColor,
                            data: @json($costData),
                            barPercentage: .5,
                            categoryPercentage: .5
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
                                    return context.dataset.label + ': {{ $currency }} ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [2, 2] },
                            ticks: {
                                callback: function(value) { return '{{ $currency }} ' + (value >= 1000 ? (value/1000).toFixed(1) + 'k' : value); }
                            }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });

            // 3. Warehouse Distribution (Doughnut)
            window.warehouseChart = new Chart(document.getElementById("chartjs-warehouse-pie"), {
                type: "doughnut",
                data: {
                    labels: @json($warehouseLabels),
                    datasets: [{
                        data: @json($warehouseData),
                        backgroundColor: [primaryColor, '#ffc107', '#17a2b8', '#e83e8c', '#6f42c1'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: { legend: { display: false } }
                }
            });

            // 4. Net Profit Chart (Mixed: Bar + Line)
            if (document.getElementById("chartjs-net-financials")) {
                window.netFinancialsChart = new Chart(document.getElementById("chartjs-net-financials"), {
                    type: "bar",
                    data: {
                        labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                        datasets: [
                            {
                                label: "{{ __('Ingresos (Facturado)') }}",
                                backgroundColor: '#10b981', // Emerald
                                data: @json($revenueData),
                                barPercentage: .4,
                                categoryPercentage: .5,
                                order: 2
                            },
                            {
                                label: "{{ __('Egresos (Gastos)') }}",
                                backgroundColor: '#f43f5e', // Rose
                                data: @json($expenseData),
                                barPercentage: .4,
                                categoryPercentage: .5,
                                order: 2
                            },
                            {
                                label: "{{ __('Ganancia Real Neta') }}",
                                type: "line",
                                borderColor: '#3b7ddd', // Blue
                                backgroundColor: "rgba(59, 125, 221, 0.1)",
                                data: @json($netProfitData),
                                fill: true,
                                tension: 0.4,
                                order: 1
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
                                        return context.dataset.label + ': {{ $currency }} ' + context.parsed.y.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { borderDash: [2, 2] },
                                ticks: {
                                    callback: function(value) { return '{{ $currency }} ' + (value >= 1000 ? (value/1000).toFixed(1) + 'k' : value); }
                                }
                            },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        }
    </script>
</div>
