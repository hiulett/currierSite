<div>
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-0 fw-black text-dark">Mis Cotizaciones</h1>
            <p class="text-muted xsmall mb-0">Consulta y descarga las cotizaciones que hemos preparado para ti.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center"><i data-feather="check-circle" class="me-2"></i><div>{{ session('message') }}</div></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center"><i data-feather="alert-octagon" class="me-2"></i><div>{{ session('error') }}</div></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 cursor-pointer" wire:click="sortBy('number')">
                            Nº Cotización
                            @if($sortField === 'number')
                                <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px;"></i>
                            @endif
                        </th>
                        <th>Monto Total</th>
                        <th>Estado</th>
                        <th class="pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quotations as $quotation)
                        @php
                            $statusLabel = [
                                'draft'      => 'Borrador',
                                'sent'       => 'Enviada',
                                'email_sent' => 'Enviada',
                                'accepted'   => 'Aceptada',
                                'rejected'   => 'Rechazada',
                                'invoiced'   => 'Facturada',
                            ][$quotation->status] ?? ucfirst($quotation->status);
                            $statusColor = [
                                'draft'      => '#6c757d',
                                'sent'       => '#17a2b8',
                                'email_sent' => '#0d9488',
                                'accepted'   => '#28a745',
                                'rejected'   => '#dc3545',
                                'invoiced'   => '#6f42c1',
                            ][$quotation->status] ?? '#6c757d';
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="fw-black text-dark">{{ $quotation->number }}</div>
                                <div class="text-muted xsmall">{{ $quotation->created_at->format('d M, Y') }}</div>
                            </td>
                            <td class="fw-bold">{{ $quotation->tenant->settings_json['currency'] ?? 'USD' }} {{ number_format($quotation->total, 2) }}</td>
                            <td>
                                <span class="badge text-uppercase" style="font-size: 0.65rem; background-color: {{ $statusColor }}">{{ $statusLabel }}</span>
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('customer.quotations.download', $quotation) }}" target="_blank" class="btn btn-sm btn-light border shadow-sm" title="Descargar PDF" aria-label="Descargar PDF de {{ $quotation->number }}">
                                    <i class="align-middle text-primary" data-feather="printer" style="width: 14px;"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i data-feather="file-text" class="d-block mx-auto mb-2 opacity-25" style="width: 48px; height: 48px;"></i>
                                Aún no tienes cotizaciones.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-top">
            {{ $quotations->links() }}
        </div>
    </div>
</div>
