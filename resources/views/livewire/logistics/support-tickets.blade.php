<div>
    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h3 mb-2 uppercase font-black tracking-tight">Gestión de Soporte</h1>
            <p class="text-muted">Responda y gestione las solicitudes de ayuda de sus clientes.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message"><strong>¡Éxito!</strong> {{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-light border-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h5 class="card-title mb-0 uppercase font-black small">Tickets de Soporte</h5>
            <div class="d-flex flex-wrap gap-2 w-100 w-md-auto">
                <select wire:model.live="status_filter" class="form-select form-select-sm" style="width: 150px;">
                    <option value="">Todos los Estados</option>
                    <option value="open">Abiertos</option>
                    <option value="pending">En Espera</option>
                    <option value="closed">Cerrados</option>
                </select>
                <div class="input-group input-group-sm flex-grow-1" style="min-width: 200px;">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Buscar por asunto o cliente...">
                    <span class="input-group-text bg-white"><i class="align-middle" data-feather="search"></i></span>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped my-0">
                <thead>
                    <tr>
                        <th class="ps-4 cursor-pointer" wire:click="sortBy('id')">
                            ID
                            @if($sortField === 'id')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th>Cliente</th>
                        <th class="cursor-pointer" wire:click="sortBy('subject')">
                            Asunto
                            @if($sortField === 'subject')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                            @endif
                        </th>
                        <th class="cursor-pointer" wire:click="sortBy('priority')">
                            Prioridad
                            @if($sortField === 'priority')
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
                    @foreach($tickets as $ticket)
                        <tr>
                            <td class="ps-4 fw-bold">#{{ $ticket->id }}</td>
                            <td>
                                <div class="fw-bold">{{ $ticket->customer->user->name }}</div>
                                <div class="small text-primary font-black uppercase">{{ $ticket->customer->box_number }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $ticket->subject }}</div>
                                <div class="small text-muted">{{ $ticket->messages->count() }} mensajes</div>
                            </td>
                            <td>
                                @php
                                    $prioClass = [
                                        'high' => 'bg-danger',
                                        'medium' => 'bg-warning text-dark',
                                        'low' => 'bg-info',
                                    ][$ticket->priority] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $prioClass }} text-uppercase" style="font-size: 0.6rem;">{{ $ticket->priority }}</span>
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'open' => 'bg-success',
                                        'pending' => 'bg-warning text-dark',
                                        'closed' => 'bg-secondary',
                                    ][$ticket->status] ?? 'bg-dark';
                                @endphp
                                <span class="badge {{ $statusClass }} text-uppercase" style="font-size: 0.6rem;">{{ $ticket->status }}</span>
                            </td>
                            <td class="small text-muted">{{ $ticket->created_at->format('d M, Y') }}</td>
                            <td class="pe-4 text-end">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-light border" title="Responder">
                                        <i class="align-middle text-primary" data-feather="message-circle"></i>
                                    </button>
                                    @if($ticket->status !== 'closed')
                                        <button wire:click="closeTicket({{ $ticket->id }})" class="btn btn-sm btn-light border" title="Cerrar Ticket">
                                            <i class="align-middle text-danger" data-feather="check-square"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
