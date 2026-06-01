<div>
    <div class="row mb-3">
        <div class="col-auto d-none d-sm-block">
            <h1 class="h3 mb-0 uppercase font-black">Panel de Control Global</h1>
            <p class="text-muted">Métricas consolidadas de todo el ecosistema LogiSaaS.</p>
        </div>
    </div>

    <!-- Global Stats Row 1 -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <a href="{{ route('super.tenants') }}" class="card flex-fill border-0 shadow-sm transform transition hover:scale-102 text-decoration-none bg-primary text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 fw-black text-white">{{ number_format($total_tenants) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Empresas (Tenants)</p>
                            <div class="mt-2">
                                <span class="badge bg-white bg-opacity-25 text-white">{{ $active_tenants }} Activos</span>
                            </div>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="layers"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <a href="{{ route('super.inventory') }}" class="card flex-fill border-0 shadow-sm transform transition hover:scale-102 text-decoration-none bg-success text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 fw-black text-white">{{ number_format($total_packages) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Paquetes Globales</p>
                            <div class="mt-2 small opacity-75">
                                <i class="align-middle" data-feather="box" style="width:12px;"></i>
                                <span class="fw-bold">{{ number_format($total_weight, 1) }}</span> lbs totales
                            </div>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="package"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div class="card flex-fill border-0 shadow-sm transform transition hover:scale-102 bg-info text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white fw-black">{{ number_format($total_users) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Usuarios en Red</p>
                            <div class="mt-2 small opacity-75">
                                <span class="fw-bold">{{ $online_users }}</span> Online ahora
                            </div>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div class="card flex-fill bg-dark text-white shadow-lg border-0">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 fw-black text-white">${{ number_format($saas_revenue_month, 2) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Tu Ganancia (Este Mes)</p>
                            <div class="mt-2 small text-white-50">
                                SaaS: ${{ number_format($saas_pending, 2) }} por cobrar
                            </div>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat text-white" style="background: rgba(255,255,255,0.1);">
                                <i class="align-middle" data-feather="dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Health Row -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-12 col-md-4 p-4 border-end">
                            <h6 class="text-uppercase font-black small text-muted mb-3">Facturación Total Tenants</h6>
                            <h2 class="fw-black text-dark mb-1">${{ number_format($total_revenue, 2) }}</h2>
                            <p class="text-success small fw-bold mb-0">Dinero procesado por el sistema</p>
                        </div>
                        <div class="col-12 col-md-4 p-4 border-end bg-light">
                            <h6 class="text-uppercase font-black small text-muted mb-3">Cartera Pendiente (Logística)</h6>
                            <h2 class="fw-black text-danger mb-1">${{ number_format($pending_collection, 2) }}</h2>
                            <p class="text-muted small mb-0">Monto total por cobrar de todas las empresas</p>
                        </div>
                        <div class="col-12 col-md-4 p-4">
                            <h6 class="text-uppercase font-black small text-muted mb-3">Morosidad SaaS (Tus Facturas)</h6>
                            <h2 class="fw-black text-warning mb-1">{{ $saas_overdue_count }}</h2>
                            <p class="text-muted small mb-0">Empresas con mensualidad vencida</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-12 col-lg-8 d-flex">
            <div class="card flex-fill w-100 border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small text-primary">Crecimiento de Carga (Meses del Año)</h5>
                </div>
                <div class="card-body py-3">
                    <div class="chart chart-sm">
                        <canvas id="chart-package-growth"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 d-flex">
            <div class="card flex-fill w-100 border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small text-primary">Top Empresas por Volumen</h5>
                </div>
                <div class="card-body">
                    @foreach($top_tenants as $top)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold small text-uppercase text-dark">{{ $top->name }}</span>
                                <span class="badge bg-primary-light text-primary font-black">{{ $top->packages_count }} pkgs</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ ($top->packages_count / max($total_packages, 1)) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Tenants -->
        <div class="col-12 col-lg-7 d-flex">
            <div class="card flex-fill w-100 border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small">Últimas Empresas Registradas</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped my-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Empresa</th>
                                <th>Plan Contratado</th>
                                <th>Estado</th>
                                <th class="pe-4 text-end">Registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_tenants as $t)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-black text-dark">{{ $t->name }}</div>
                                        <div class="small text-muted">{{ $t->subdomain }}.logisaas.com</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold small">{{ $t->plan?->name ?? 'Sin Plan' }}</div>
                                        <div class="small text-muted">${{ number_format($t->plan?->price ?? 0, 2) }}/mes</div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $t->status === 'active' ? 'bg-success' : 'bg-danger' }} text-uppercase">
                                            {{ $t->status }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end small fw-bold text-muted">{{ $t->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light border-top text-center">
                    <a href="{{ route('super.tenants') }}" class="small fw-black text-uppercase text-decoration-none">Ver Todos los Tenants <i class="align-middle ms-1" data-feather="arrow-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Breakdown of Plans -->
        <div class="col-12 col-lg-5 d-flex">
            <div class="card flex-fill w-100 border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small text-primary">Planes Activos en el Sistema</h5>
                </div>
                <div class="card-body">
                    <div class="chart chart-xs mb-3">
                        <canvas id="chart-plans-usage"></canvas>
                    </div>
                    <table class="table table-sm mt-3 mb-0">
                        <thead>
                            <tr class="small font-black uppercase text-muted">
                                <th>Plan</th>
                                <th class="text-end">Empresas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plans_usage as $plan_name => $count)
                                <tr>
                                    <td class="small fw-bold">{{ $plan_name }}</td>
                                    <td class="text-end fw-black">{{ number_format($count) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Global Status Carga -->
        <div class="col-12 col-lg-7 d-flex">
            <div class="card flex-fill w-100 border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small">Estado Global de Carga</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div class="chart chart-xs">
                                <canvas id="chart-packages-status"></canvas>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr class="small font-black uppercase text-muted">
                                            <th>Estado</th>
                                            <th class="text-end">Conteo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($packages_by_status as $status => $count)
                                            <tr>
                                                <td class="small text-uppercase fw-bold">{{ $status }}</td>
                                                <td class="text-end fw-black">{{ number_format($count) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Growth Chart
            var ctx = document.getElementById("chart-package-growth").getContext("2d");
            var gradient = ctx.createLinearGradient(0, 0, 0, 225);
            gradient.addColorStop(0, "rgba(59, 125, 221, 0.2)");
            gradient.addColorStop(1, "rgba(59, 125, 221, 0)");

            const growthData = @json($package_growth);
            const labels = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
            const dataValues = labels.map((label, index) => {
                const monthNum = (index + 1).toString().padStart(2, '0');
                return growthData[monthNum] || 0;
            });

            new Chart(document.getElementById("chart-package-growth"), {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Paquetes",
                        fill: true,
                        backgroundColor: gradient,
                        borderColor: "#3b7ddd",
                        data: dataValues,
                        tension: 0.4
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: { display: false },
                    scales: {
                        x: { grid: { display: false } },
                        y: {
                            grid: { color: "rgba(0,0,0,0.05)" },
                            beginAtZero: true
                        }
                    }
                }
            });

            // 2. Status Distribution Chart
            const statusLabels = @json(array_keys($packages_by_status));
            const statusData = @json(array_values($packages_by_status));

            new Chart(document.getElementById("chart-packages-status"), {
                type: "doughnut",
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: ["#3b7ddd", "#28a745", "#ffc107", "#dc3545", "#6f42c1", "#17a2b8"],
                        borderWidth: 5,
                        borderColor: "#fff"
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    cutout: "70%",
                    plugins: { legend: { display: false } }
                }
            });

            // 3. Plans Usage Chart
            const planLabels = @json(array_keys($plans_usage));
            const planData = @json(array_values($plans_usage));

            new Chart(document.getElementById("chart-plans-usage"), {
                type: "bar",
                data: {
                    labels: planLabels,
                    datasets: [{
                        label: "Empresas",
                        data: planData,
                        backgroundColor: "#3b7ddd",
                        borderRadius: 5
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        });
    </script>
</div>
