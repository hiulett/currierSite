<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 uppercase font-black tracking-tight text-dark">Registro de Canjes LOGYPUNTOS</h1>
            <p class="text-muted small">Auditoría de recompensas canjeadas por los clientes.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message">{{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-bottom py-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control" placeholder="Buscar por nombre o correo del cliente...">
                </div>
                <div class="col-md-4">
                    <select wire:model="filter_status" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="pending">Pendiente</option>
                        <option value="completed">Completado</option>
                        <option value="cancelled">Cancelado</option>
                    </select>
                </div>
                <div class="col-md-2 text-md-end">
                    <span class="xsmall text-muted fw-bold">{{ $redemptions->total() }} canje(s)</span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="xsmall text-muted text-uppercase">Cliente</th>
                            <th class="xsmall text-muted text-uppercase">Casillero</th>
                            <th class="xsmall text-muted text-uppercase">Recompensa</th>
                            <th class="xsmall text-muted text-uppercase text-end">Puntos</th>
                            <th class="xsmall text-muted text-uppercase text-end">Lb gratis</th>
                            <th class="xsmall text-muted text-uppercase">Estado</th>
                            <th class="xsmall text-muted text-uppercase text-end">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($redemptions as $redemption)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark xsmall">{{ $redemption->customer?->user?->name ?? '—' }}</div>
                                    <div class="text-muted xsmall">{{ $redemption->customer?->user?->email ?? '—' }}</div>
                                </td>
                                <td class="xsmall">{{ $redemption->customer?->box_number ?? '—' }}</td>
                                <td class="xsmall">{{ $redemption->reward_name }}</td>
                                <td class="xsmall text-end fw-bold text-danger">{{ number_format($redemption->points_spent) }}</td>
                                <td class="xsmall text-end fw-bold text-success">{{ number_format($redemption->free_pounds_granted, 0) }}</td>
                                <td>
                                    <span class="badge {{ $redemption->status === 'completed' ? 'bg-success' : ($redemption->status === 'cancelled' ? 'bg-secondary' : 'bg-warning text-dark') }} text-uppercase xsmall">
                                        {{ $redemption->status }}
                                    </span>
                                </td>
                                <td class="xsmall text-muted text-end">{{ $redemption->redeemed_at?->format('d M Y H:i') ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted xsmall py-5">Aún no hay canjes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($redemptions->hasPages())
            <div class="card-footer bg-white border-top">
                {{ $redemptions->links() }}
            </div>
        @endif
    </div>
</div>
