<div>
    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h3 mb-2 uppercase font-black tracking-tight">Centro de Reportes</h1>
            <p class="text-muted">Análisis detallado y exportación de datos operativos.</p>
        </div>
    </div>

    @if(empty($available_reports))
        <div class="card bg-light border-dashed py-5 text-center">
            <div class="card-body">
                <i data-feather="lock" class="text-muted opacity-25 mb-3" style="width: 60px; height: 60px;"></i>
                <h4 class="text-muted fw-black uppercase">Módulos Desactivados</h4>
                <p class="text-muted">No tienes reportes activos en tu plan actual. Contacta al administrador para habilitarlos.</p>
            </div>
        </div>
    @else
        <div class="row">
            <!-- Sidebar: Available Reports -->
            <div class="col-12 col-lg-4 col-xl-3">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white py-3">
                        <h5 class="card-title text-white mb-0 small uppercase font-black">Reportes Disponibles</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($available_reports as $slug => $info)
                            <button wire:click="selectReport('{{ $slug }}')"
                                    class="list-group-item list-group-item-action py-3 d-flex align-items-center {{ $active_report == $slug ? 'bg-primary-light border-start border-primary border-4 text-primary active' : '' }}">
                                <i class="me-3" data-feather="{{ $info['icon'] }}" style="width: 18px; height: 18px;"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-black small uppercase leading-tight">{{ $info['name'] }}</div>
                                    <div class="text-muted" style="font-size: 0.65rem;">{{ $info['desc'] }}</div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Main Panel: Report Data -->
            <div class="col-12 col-lg-8 col-xl-9">
                @if($active_report)
                    <div class="card shadow-sm border-0 animate-in fade-in zoom-in duration-300">
                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                            <h5 class="card-title mb-0 uppercase font-black small">{{ $available_reports[$active_report]['name'] }}</h5>
                            <button class="btn btn-sm btn-success fw-black text-uppercase tracking-tighter">
                                <i class="align-middle me-1" data-feather="download"></i> Exportar Excel
                            </button>
                        </div>
                        <div class="card-body p-4">
                            @if($active_report == 'inventory_stock')
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Bodega</th>
                                                <th class="text-center">Paquetes en Stock</th>
                                                <th class="text-end">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($report_data as $wh)
                                                <tr>
                                                    <td class="fw-bold">{{ $wh->name }}</td>
                                                    <td class="text-center h4 mb-0 fw-black text-primary">{{ $wh->packages_count }}</td>
                                                    <td class="text-end"><span class="badge bg-success">Operativa</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @elseif($active_report == 'revenue_daily')
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th class="text-end">Total Recaudado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($report_data as $row)
                                                <tr>
                                                    <td class="fw-bold">{{ date('d M, Y', strtotime($row->date)) }}</td>
                                                    <td class="text-end fw-black text-success">${{ number_format($row->total, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @elseif($active_report == 'customer_debt')
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Cliente</th>
                                                <th>Casillero</th>
                                                <th class="text-end">Saldo Pendiente</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($report_data as $c)
                                                <tr>
                                                    <td class="fw-bold">{{ $c->user->name }}</td>
                                                    <td><span class="badge bg-primary-light text-primary">{{ $c->box_number }}</span></td>
                                                    <td class="text-end fw-black text-danger">${{ number_format($c->balance, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @elseif($active_report == 'package_status')
                                <div class="row g-4 mb-4">
                                    @foreach($report_data as $row)
                                        @php $dummy = new \App\Models\Package(['status' => $row->status]); @endphp
                                        <div class="col-6 col-md-4 col-xl-3">
                                            <div class="p-3 bg-light rounded-3 text-center border">
                                                <h3 class="fw-black mb-1" style="color: {{ $dummy->getStatusColor() }}">{{ $row->count }}</h3>
                                                <p class="small text-muted text-uppercase mb-0 font-bold" style="font-size: 0.6rem;">{{ $dummy->getStatusLabel() }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif($active_report == 'stagnant_cargo')
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Tracking / Paquete</th>
                                                <th>Cliente</th>
                                                <th>Bodega</th>
                                                <th class="text-end">Días en Stock</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($report_data as $pkg)
                                                <tr>
                                                    <td>
                                                        <div class="fw-black text-dark">{{ $pkg->tracking_number }}</div>
                                                        <div class="small text-muted">{{ Str::limit($pkg->description, 30) }}</div>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold small">{{ $pkg->customer->user->name }}</div>
                                                        <div class="text-primary font-black small" style="font-size: 0.65rem;">{{ $pkg->customer->box_number }}</div>
                                                    </td>
                                                    <td><span class="badge bg-light text-dark border">{{ $pkg->warehouse->code }}</span></td>
                                                    <td class="text-end">
                                                        <span class="badge bg-danger px-3 py-2 fw-black">
                                                            {{ now()->diffInDays($pkg->created_at) }} Días
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-5 text-muted italic">No hay carga estancada crítica (más de 15 días).</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            @elseif($active_report == 'tax_collection')
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th class="text-end">Impuestos Recaudados</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($report_data as $row)
                                                <tr>
                                                    <td class="fw-bold">{{ date('d M, Y', strtotime($row->date)) }}</td>
                                                    <td class="text-end fw-black text-info">${{ number_format($row->total_tax, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @elseif($active_report == 'volume_weight')
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Mes</th>
                                                <th class="text-center">Peso Real Total</th>
                                                <th class="text-center">Peso Volumétrico</th>
                                                <th class="text-end">Diferencia</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($report_data as $row)
                                                <tr>
                                                    <td class="fw-bold">{{ $row->month }}</td>
                                                    <td class="text-center">{{ number_format($row->total_weight, 2) }} lbs</td>
                                                    <td class="text-center">{{ number_format($row->total_vlb, 2) }} vlb</td>
                                                    <td class="text-end fw-black {{ $row->total_vlb > $row->total_weight ? 'text-danger' : 'text-success' }}">
                                                        {{ number_format(abs($row->total_vlb - $row->total_weight), 2) }} lbs
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i data-feather="bar-chart-2" class="text-primary opacity-25" style="width: 80px; height: 80px;"></i>
                                    </div>
                                    <h5 class="text-muted fw-bold uppercase">Reporte en Construcción</h5>
                                    <p class="text-muted small">Este módulo está siendo procesado por el motor analítico.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="card bg-light border-dashed h-100 d-flex align-items-center justify-content-center p-5 text-center">
                        <div class="py-5">
                            <i data-feather="trending-up" class="text-muted opacity-25 mb-4" style="width: 80px; height: 80px;"></i>
                            <h4 class="text-muted fw-black uppercase tracking-widest">Seleccione un Reporte</h4>
                            <p class="text-muted small">Haz clic en un reporte de la izquierda para visualizar los datos estadísticos.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
