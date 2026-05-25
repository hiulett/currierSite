<div class="container-fluid p-0">
    <div class="row mb-2">
        <div class="col-12 col-md-6">
            <h1 class="h4 mb-0 uppercase font-black tracking-tight text-dark">Soporte Técnico</h1>
            <p class="text-muted xsmall mb-0">¿Tienes alguna duda o problema? Estamos para ayudarte.</p>
        </div>
        <div class="col-12 col-md-6 text-md-end mt-2 mt-md-0">
            <button class="btn btn-sm btn-primary fw-black shadow-sm" data-bs-toggle="modal" data-bs-target="#newTicketModal">
                <i class="align-middle me-1" data-feather="message-circle" style="width: 14px; height: 14px;"></i> NUEVO REQUERIMIENTO
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message">
                <strong>¡Éxito!</strong> {{ session('message') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-light border-bottom py-3">
                    <h5 class="card-title mb-0 uppercase font-black small">Mis Tickets de Soporte</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 cursor-pointer" wire:click="sortBy('subject')">
                                    Ticket / Asunto
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
                                <th class="pe-4 text-end cursor-pointer" wire:click="sortBy('updated_at')">
                                    Última Actualización
                                    @if($sortField === 'updated_at')
                                        <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                                    @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr onclick="window.location='{{ route('customer.tickets.detail', $ticket->id) }}'" style="cursor: pointer;">
                                    <td class="ps-4">
                                        <div class="fw-black text-dark">{{ $ticket->subject }}</div>
                                        <div class="text-muted small">ID: #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $priorityClass = match($ticket->priority) {
                                                'high' => 'text-danger',
                                                'medium' => 'text-warning',
                                                default => 'text-info'
                                            };
                                        @endphp
                                        <span class="small fw-black text-uppercase {{ $priorityClass }}">
                                            <i class="align-middle me-1" data-feather="alert-circle" style="width: 12px;"></i>
                                            {{ $ticket->priority }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $statusStyles = [
                                                'open' => 'bg-info',
                                                'in_progress' => 'bg-warning',
                                                'resolved' => 'bg-success',
                                                'closed' => 'bg-secondary',
                                            ][$ticket->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $statusStyles }} text-uppercase font-black" style="font-size: 0.6rem;">
                                            {{ str_replace('_', ' ', $ticket->status) }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end text-muted small">
                                        {{ $ticket->updated_at->format('d/m/Y h:i A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="stat bg-light text-muted mx-auto mb-3" style="width: 60px; height: 60px;">
                                            <i data-feather="inbox" style="width: 30px; height: 30px;"></i>
                                        </div>
                                        <p class="text-muted small">No tienes tickets de soporte registrados aún.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- New Ticket Modal (Bootstrap 5) -->
    <div class="modal fade" id="newTicketModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
                <div class="modal-header bg-primary text-white py-4">
                    <h5 class="modal-title fw-black text-uppercase small tracking-widest text-white">Nuevo Requerimiento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="createTicket">
                    <div class="modal-body p-4 p-md-5">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label font-black text-uppercase small tracking-wider text-muted mb-2">Asunto / Tema</label>
                                <input type="text" wire:model="subject" placeholder="Ej: No reconozco un cargo..."
                                       class="form-control form-control-lg fw-bold @error('subject') is-invalid @enderror">
                                @error('subject') <div class="text-danger xsmall mt-1 fw-bold">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label font-black text-uppercase small tracking-wider text-muted mb-2">Prioridad</label>
                                <select wire:model="priority" class="form-select form-select-lg fw-bold">
                                    <option value="low">Baja - Consulta General</option>
                                    <option value="medium">Media - Inconveniente con Carga</option>
                                    <option value="high">Alta - Urgente / Facturación</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label font-black text-uppercase small tracking-wider text-muted mb-2">Detalles del problema</label>
                                <textarea wire:model="message" rows="4" placeholder="Describe detalladamente tu requerimiento..."
                                          class="form-control @error('message') is-invalid @enderror"></textarea>
                                @error('message') <div class="text-danger xsmall mt-1 fw-bold">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-4">
                        <button type="button" class="btn btn-light border fw-bold text-uppercase" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary px-4 fw-black text-uppercase tracking-widest shadow" wire:loading.attr="disabled">
                            <span wire:loading.remove>Enviar Ticket</span>
                            <span wire:loading><div class="spinner-border spinner-border-sm me-2" role="status"></div> Enviando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('ticket-created', () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('newTicketModal'));
            if (modal) modal.hide();
        });
    });
</script>
