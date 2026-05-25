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
                                <i class="align-middle" data-feather="trending-up" style="width:12px;"></i>
                                <span class="fw-bold">+12%</span> este mes
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
            <a href="{{ route('super.inventory') }}" class="card flex-fill border-0 shadow-sm transform transition hover:scale-102 text-decoration-none bg-info text-white">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 text-white fw-black">{{ number_format($total_weight, 2) }} lbs</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Peso Total Almacenado</p>
                            <div class="mt-2 small opacity-50">Carga activa en bodegas</div>
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat bg-white bg-opacity-25 text-white">
                                <i class="align-middle" data-feather="database"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3 d-flex">
            <div class="card flex-fill bg-dark text-white shadow-lg border-0">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 fw-black text-white">${{ number_format($total_mrr, 2) }}</h3>
                            <p class="mb-0 text-uppercase font-bold small opacity-75">Ingresos del Ecosistema</p>
                            <div class="mt-2 small text-white-50">Suma de facturas pagadas</div>
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

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-12 col-lg-8 d-flex">
            <div class="card flex-fill w-100 border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small text-primary">Crecimiento de Carga (6 Meses)</h5>
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
                                <th>Dominio</th>
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
                                    <td><span class="badge bg-light text-dark border">{{ $t->domain }}</span></td>
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

        <!-- Infrastructure & Security -->
        <div class="col-12 col-lg-5 d-flex">
            <div class="card flex-fill w-100 border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small">Estado de Infraestructura</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4 p-3 rounded bg-light">
                        <div class="stat text-success bg-white shadow-sm me-3">
                            <i data-feather="check-circle"></i>
                        </div>
                        <div>
                            <div class="fw-black text-dark uppercase small">Base de Datos Principal</div>
                            <div class="small text-muted">Latencia: 14ms | Conexiones: 24</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4 p-3 rounded bg-light">
                        <div class="stat text-primary bg-white shadow-sm me-3">
                            <i data-feather="cloud"></i>
                        </div>
                        <div>
                            <div class="fw-black text-dark uppercase small">Sincronización Webhooks</div>
                            <div class="small text-muted">98.5% tasa de éxito (últimas 24h)</div>
                        </div>
                    </div>
                    <div class="p-4 rounded bg-dark text-white text-center shadow-lg">
                        <h6 class="text-uppercase font-black small mb-3 opacity-75">Capacidad del Ecosistema</h6>
                        <h2 class="fw-black mb-0">{{ $total_customers }}</h2>
                        <p class="small mb-3 text-white-50">Usuarios Finales Activos</p>
                        <div class="progress progress-sm bg-white bg-opacity-10 mb-2">
                            <div class="progress-bar bg-info" style="width: 45%"></div>
                        </div>
                        <span class="small opacity-50">45% de capacidad proyectada alcanzada</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById("chart-package-growth").getContext("2d");
            var gradient = ctx.createLinearGradient(0, 0, 0, 225);
            gradient.addColorStop(0, "rgba(59, 125, 221, 0.2)");
            gradient.addColorStop(1, "rgba(59, 125, 221, 0)");

            // Generate data from PHP
            const growthData = @json($package_growth);
            const labels = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
            // Extract values for the last 6 months
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
                    tooltips: { intersect: false },
                    hover: { intersect: true },
                    plugins: { filler: { propagate: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: {
                            grid: { color: "rgba(0,0,0,0.05)" },
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</div>
