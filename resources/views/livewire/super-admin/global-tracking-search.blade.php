<div>
    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h3 mb-2 uppercase font-black tracking-tight">Buscador Maestro de Carga</h1>
            <p class="text-muted">Localice cualquier paquete en todo el ecosistema {{ \App\Models\AppSetting::get('platform_name', config('app.name')) }} usando el Tracking ID.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="input-group input-group-lg shadow-sm">
                <span class="input-group-text bg-white border-end-0"><i data-feather="search" class="text-primary"></i></span>
                <input type="text" wire:model.live.debounce.300ms="search"
                       class="form-control border-start-0 ps-0 fw-bold"
                       placeholder="Escriba el Tracking ID o descripción (Mínimo 3 caracteres)...">
            </div>
        </div>
    </div>

    @if(strlen($search) >= 3)
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light border-bottom">
                <h5 class="card-title mb-0 uppercase font-black small">Resultados de Búsqueda</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped my-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Tracking / Descripción</th>
                            <th>Empresa (Tenant)</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th class="pe-4 text-end">Peso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $p)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-black text-dark">{{ $p->tracking_number }}</div>
                                    <div class="small text-muted">{{ Str::limit($p->description, 40) }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-light text-primary fw-black uppercase">{{ $p->tenant?->name }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $p->customer?->user?->name ?? 'S/D' }}</div>
                                    <div class="small text-muted">{{ $p->customer?->box_number }}</div>
                                </td>
                                <td>
                                    <span class="badge text-uppercase" style="background-color: {{ $p->getStatusColor() }}">
                                        {{ $p->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end fw-black">{{ number_format($p->weight, 2) }} lbs</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i data-feather="package" class="mb-3 text-muted" style="width: 48px; height: 48px;"></i>
                                    <p class="text-muted fw-bold">No se encontraron paquetes con ese criterio.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($packages, 'links'))
                <div class="card-footer bg-light border-top">
                    {{ $packages->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-3">
                <i data-feather="search" class="text-muted opacity-25" style="width: 80px; height: 80px;"></i>
            </div>
            <h4 class="text-muted font-black uppercase">Esperando criterio de búsqueda...</h4>
            <p class="text-muted small">Ingrese al menos 3 caracteres para iniciar la búsqueda global.</p>
        </div>
    @endif
</div>
