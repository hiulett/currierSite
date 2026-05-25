<div>
    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h3 mb-2 uppercase font-black tracking-tight">Inventario Global de Carga</h1>
            <p class="text-muted">Monitoreo de todos los paquetes circulando en el ecosistema.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 border-bottom">
            <h5 class="card-title mb-0 uppercase font-black small text-primary">Carga en Tránsito (Todos los Tenants)</h5>
            <div class="d-flex gap-2 w-100 w-md-auto">
                <select wire:model.live="filter_tenant" class="form-select form-select-sm" style="width: 200px;">
                    <option value="">Todas las Empresas</option>
                    @foreach($tenants as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endforeach
                </select>
                <div class="input-group input-group-sm flex-grow-1" style="min-width: 250px;">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Tracking, Cliente o Casillero...">
                    <span class="input-group-text bg-white"><i class="align-middle text-muted" data-feather="search" style="width: 14px;"></i></span>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped my-0">
                <thead>
                    <tr>
                        <th class="ps-4 cursor-pointer" wire:click="sortBy('tenant_id')">
                            Empresa
                            @if($sortField === 'tenant_id')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="cursor-pointer" wire:click="sortBy('tracking_number')">
                            Tracking / Paquete
                            @if($sortField === 'tracking_number')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th>Cliente</th>
                        <th class="cursor-pointer" wire:click="sortBy('warehouse_id')">
                            Bodega
                            @if($sortField === 'warehouse_id')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="cursor-pointer" wire:click="sortBy('status')">
                            Estado
                            @if($sortField === 'status')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="pe-4 text-end cursor-pointer" wire:click="sortBy('weight')">
                            Peso
                            @if($sortField === 'weight')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $pkg)
                        <tr wire:key="pkg-{{ $pkg->id }}">
                            <td class="ps-4">
                                <span class="badge bg-dark text-white fw-black">{{ $pkg->tenant->name }}</span>
                            </td>
                            <td>
                                <div class="fw-black text-dark">{{ $pkg->tracking_number }}</div>
                                <div class="small text-muted">{{ Str::limit($pkg->description, 25) }}</div>
                            </td>
                            <td>
                                <div class="fw-bold small">{{ $pkg->customer->user->name }}</div>
                                <div class="text-primary font-black small" style="font-size: 0.65rem;">{{ $pkg->customer->box_number }}</div>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $pkg->warehouse->code }}</span></td>
                            <td>
                                <span class="badge bg-primary text-uppercase" style="font-size: 0.6rem;">{{ $pkg->status }}</span>
                            </td>
                            <td class="pe-4 text-end fw-black text-dark">{{ $pkg->weight }} lbs</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-5 text-center text-muted italic">No hay paquetes que coincidan con la búsqueda global.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer border-top bg-light">
            {{ $packages->links() }}
        </div>
    </div>
</div>
