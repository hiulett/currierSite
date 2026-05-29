<div class="container-fluid p-0">
    <div class="row mb-2">
        <div class="col-12 col-md-6">
            <h1 class="h4 mb-0 uppercase font-black tracking-tight text-dark">Mis Facturas</h1>
            <p class="text-muted xsmall mb-0">Consulta tu historial de pagos y descarga tus comprobantes.</p>
        </div>
        <div class="col-12 col-md-6 text-md-end mt-2 mt-md-0">
            <div class="card d-inline-block border-0 shadow-sm bg-white px-3 py-1" style="border-radius: 0.75rem;">
                <span class="text-muted xsmall font-black uppercase me-2" style="font-size: 0.55rem;">Saldo Pendiente:</span>
                <span class="h5 mb-0 fw-black text-danger">${{ number_format(auth()->user()->customer->balance ?? 0, 2) }}</span>
            </div>
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

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message">
                <strong>Error:</strong> {{ session('error') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-light border-bottom py-3">
                    <h5 class="card-title mb-0 uppercase font-black small">Detalle de Facturación</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 cursor-pointer" wire:click="sortBy('number')">
                                    No. Factura
                                    @if($sortField === 'number')
                                        <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                                    @endif
                                </th>
                                <th class="cursor-pointer" wire:click="sortBy('created_at')">
                                    Fecha Emisión
                                    @if($sortField === 'created_at')
                                        <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                                    @endif
                                </th>
                                <th class="text-end cursor-pointer" wire:click="sortBy('total')">
                                    Monto Total
                                    @if($sortField === 'total')
                                        <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                                    @endif
                                </th>
                                <th class="text-center cursor-pointer" wire:click="sortBy('status')">
                                    Estado
                                    @if($sortField === 'status')
                                        <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                                    @endif
                                </th>
                                <th class="pe-4 text-end">Acciones / Pago</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-black text-dark">{{ $invoice->number }}</div>
                                        <div class="text-muted xsmall uppercase font-bold">Concepto: Envío de Carga</div>
                                    </td>
                                    <td>
                                        <div class="text-dark fw-bold small">{{ $invoice->created_at->format('d M, Y') }}</div>
                                        <div class="text-muted xsmall font-bold text-uppercase">Vence: {{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : 'N/A' }}</div>
                                    </td>
                                    <td class="text-end">
                                        <div class="fw-black text-dark h5 mb-0">${{ number_format($invoice->total, 2) }}</div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $statusStyles = [
                                                'paid' => 'bg-success',
                                                'unpaid' => 'bg-danger',
                                                'pending' => 'bg-warning',
                                                'cancelled' => 'bg-secondary',
                                            ][$invoice->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $statusStyles }} text-uppercase font-black" style="font-size: 0.6rem; tracking-widest: 0.05rem;">
                                            {{ $invoice->status === 'unpaid' ? 'PENDIENTE' : ($invoice->status === 'paid' ? 'PAGADA' : $invoice->status) }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group">
                                            @php
                                                $whatsappPhone = auth()->user()->tenant->settings_json['whatsapp_phone'] ?? '50766554433';
                                                $waMsg = App\Helpers\WhatsAppHelper::getPaymentSupportMessage($invoice->number);
                                                $waUrl = App\Helpers\WhatsAppHelper::getLink($whatsappPhone, $waMsg);
                                            @endphp
                                            <a href="{{ $waUrl }}" target="_blank" class="btn btn-sm btn-light border shadow-sm" title="Consultar por WhatsApp">
                                                <i class="align-middle text-success" data-feather="message-circle" style="width: 14px;"></i>
                                            </a>

                                            <a href="{{ route('customer.invoices.download', $invoice) }}" target="_blank" class="btn btn-sm btn-light border shadow-sm" title="Descargar PDF">
                                                <i class="align-middle text-dark" data-feather="download" style="width: 14px;"></i> PDF
                                            </a>

                                            @if($invoice->status !== 'paid' && $invoice->status !== 'cancelled' && $invoice->status !== 'pending')
                                                <a href="{{ route('customer.checkout', $invoice->id) }}" class="btn btn-sm btn-primary shadow-sm font-black text-uppercase" style="font-size: 0.65rem;">
                                                    <i class="align-middle me-1" data-feather="credit-card" style="width: 12px;"></i> PAGAR Y RECIBIR
                                                </a>
                                            @elseif($invoice->status === 'pending')
                                                <span class="badge bg-warning text-dark text-uppercase xsmall">Pago en Revisión</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-5 text-center">
                                        <div class="stat bg-light text-muted mx-auto mb-3" style="width: 60px; height: 60px;">
                                            <i data-feather="file-text" style="width: 30px; height: 30px;"></i>
                                        </div>
                                        <p class="text-muted small">No tienes facturas registradas en tu historial.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($invoices->hasPages())
                    <div class="card-footer bg-white border-top py-3">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Support Quick Help -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 bg-info bg-opacity-10" style="border-radius: 1rem;">
                <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-start">
                        <div class="text-info me-3">
                            <i data-feather="help-circle" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <h6 class="fw-black text-dark text-uppercase small mb-1">¿Tienes dudas sobre un cargo?</h6>
                            <p class="text-muted xsmall mb-0 leading-sm">Si encuentras alguna discrepancia en tus facturas, puedes abrir un ticket de soporte y nuestro departamento de contabilidad te atenderá.</p>
                        </div>
                    </div>
                    <a href="{{ route('customer.tickets.index') }}" class="btn btn-info btn-sm fw-black uppercase px-4 shadow-sm">
                        Contactar Soporte
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
