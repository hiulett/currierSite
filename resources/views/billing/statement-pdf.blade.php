<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Estado de Cuenta - {{ $customer->box_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.4; }
        .header { border-bottom: 3px solid #3b7ddd; padding-bottom: 10px; margin-bottom: 20px; }
        .company-name { font-size: 20px; font-weight: bold; color: #3b7ddd; text-transform: uppercase; }
        .report-title { font-size: 16px; font-weight: bold; margin-top: 5px; color: #555; }

        .summary-container { width: 100%; margin-bottom: 20px; }
        .summary-box { width: 48%; float: left; border: 1px solid #ddd; padding: 10px; border-radius: 5px; }
        .summary-box.right { float: right; }

        .section-title { background: #f4f7f6; padding: 5px 10px; font-weight: bold; text-transform: uppercase; margin-bottom: 10px; border-left: 4px solid #3b7ddd; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f8f9fa; text-align: left; padding: 8px; border-bottom: 2px solid #dee2e6; font-size: 10px; text-transform: uppercase; }
        td { padding: 8px; border-bottom: 1px solid #eee; }

        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .text-danger { color: #dc3545; }
        .text-success { color: #28a745; }

        .badge { padding: 2px 6px; border-radius: 3px; font-size: 9px; color: white; text-transform: uppercase; }
        .bg-success { background-color: #28a745; }
        .bg-warning { background-color: #ffc107; color: #212529; }
        .bg-danger { background-color: #dc3545; }

        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #ddd; padding-top: 5px; }
        .clearfix { clear: both; }
    </style>
</head>
<body>
    <div class="header">
        <div style="float: right; text-align: right;">
            <div>Fecha de Emisión: {{ date('d/m/Y H:i') }}</div>
            <div class="fw-bold" style="font-size: 14px; margin-top: 5px;">{{ $customer->box_number }}</div>
        </div>

        @php
            $tenant = $customer->tenant;
            $logoUrl = $tenant->theme_config_json['logo_url'] ?? null;
        @endphp

        @if($logoUrl)
            <img src="{{ $logoUrl }}" style="max-height: 60px; max-width: 250px; margin-bottom: 5px;">
        @else
            <div class="company-name">{{ $tenant->name ?? config('app.name') }}</div>
        @endif
        <div class="report-title">ESTADO DE CUENTA DE CLIENTE</div>
    </div>

    <div class="summary-container">
        <div class="summary-box">
            <div class="fw-bold" style="font-size: 13px;">{{ $customer->user->name }}</div>
            <div>Email: {{ $customer->user->email }}</div>
            <div>Teléfono: {{ $customer->phone ?? 'N/A' }}</div>
            <div>ID: {{ $customer->identification_number ?? 'N/A' }}</div>
        </div>
        <div class="summary-box right">
            <div class="section-title" style="margin-top: 0; border: none; background: transparent; padding: 0;">RESUMEN FINANCIERO</div>
            <div style="font-size: 18px; margin-top: 5px;">
                Saldo Pendiente: <span class="fw-bold {{ $customer->balance > 0 ? 'text-danger' : 'text-success' }}">${{ number_format($customer->balance, 2) }}</span>
            </div>
            <div style="margin-top: 5px;">Puntos Acumulados: <span class="fw-bold">{{ $customer->points }}</span></div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="section-title">Movimientos de Facturación</div>
    <table>
        <thead>
            <tr>
                <th>Nº Factura</th>
                <th>Fecha</th>
                <th>Vencimiento</th>
                <th>Estado</th>
                <th class="text-end">Monto Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $inv)
                @php
                    $isOverdue = $inv->status === 'unpaid' && $inv->due_date && $inv->due_date < now()->today();
                @endphp
                <tr>
                    <td class="fw-bold">{{ $inv->number }}</td>
                    <td>{{ $inv->created_at->format('d/m/Y') }}</td>
                    <td>{{ $inv->due_date ? $inv->due_date->format('d/m/Y') : 'N/A' }}</td>
                    <td>
                        @if($inv->status === 'paid')
                            <span class="badge bg-success">Pagada</span>
                        @elseif($isOverdue)
                            <span class="badge bg-danger">Vencida</span>
                        @else
                            <span class="badge bg-warning">Pendiente</span>
                        @endif
                    </td>
                    <td class="text-end fw-bold">${{ number_format($inv->total, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #888;">No se registran movimientos de facturación.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($packages->count() > 0)
        <div class="section-title">Paquetes en Bodega / Tránsito</div>
        <table>
            <thead>
                <tr>
                    <th>Tracking</th>
                    <th>Descripción</th>
                    <th>Peso</th>
                    <th>Estado</th>
                    <th class="text-end">Bodega</th>
                </tr>
            </thead>
            <tbody>
                @foreach($packages as $pkg)
                    <tr>
                        <td class="fw-bold">{{ $pkg->tracking_number }}</td>
                        <td>{{ Str::limit($pkg->description, 50) }}</td>
                        <td>{{ $pkg->weight }} lbs</td>
                        <td>{{ strtoupper(str_replace('_', ' ', $pkg->status)) }}</td>
                        <td class="text-end">{{ $pkg->warehouse->code }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        {{ config('app.name') }} - Sistema de Gestión Logística SaaS. Generado automáticamente.
    </div>
</body>
</html>
