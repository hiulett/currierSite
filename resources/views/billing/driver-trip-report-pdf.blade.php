<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Control de Fletes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #1a1a2e;
            background: #fff;
        }

        .page-container {
            padding: 20px 30px;
        }

        /* ── HEADER ── */
        .report-header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 3px solid #2B3A67;
            padding-bottom: 15px;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 60%;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 40%;
        }

        .header-logo {
            max-height: 50px;
            max-width: 180px;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 20px;
            font-weight: 900;
            color: #2B3A67;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 3px;
        }

        .report-subtitle {
            font-size: 11px;
            color: #6c757d;
        }

        .company-name {
            font-size: 14px;
            font-weight: 700;
            color: #2B3A67;
            margin-bottom: 3px;
        }

        .date-range-badge {
            display: inline-block;
            background: #EEF2FF;
            color: #2B3A67;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
        }

        /* ── KPI CARDS ── */
        .kpi-row {
            display: table;
            width: 100%;
            margin-bottom: 18px;
        }

        .kpi-card {
            display: table-cell;
            width: 33.33%;
            padding: 0 6px;
        }

        .kpi-card:first-child {
            padding-left: 0;
        }

        .kpi-card:last-child {
            padding-right: 0;
        }

        .kpi-inner {
            padding: 12px 16px;
            border-radius: 8px;
            color: #fff;
        }

        .kpi-outsourcing .kpi-inner {
            background: #1a1a2e;
        }

        .kpi-client .kpi-inner {
            background: #3B82F6;
        }

        .kpi-revenue .kpi-inner {
            background: #10B981;
        }

        .kpi-value {
            font-size: 18px;
            font-weight: 900;
            margin-bottom: 2px;
        }

        .kpi-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.85;
        }

        /* ── TABLE ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .data-table thead th {
            background: #2B3A67;
            color: #fff;
            padding: 8px 6px;
            text-align: left;
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 700;
        }

        .data-table thead th:first-child {
            border-radius: 4px 0 0 0;
        }

        .data-table thead th:last-child {
            border-radius: 0 4px 0 0;
        }

        .data-table tbody td {
            padding: 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
        }

        .data-table tbody tr:nth-child(even) {
            background: #F8F9FA;
        }

        .data-table tbody tr:nth-child(odd) {
            background: #fff;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: 700;
        }

        .text-success {
            color: #10B981;
        }

        .text-primary {
            color: #3B82F6;
        }

        .text-muted {
            color: #6c757d;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge-warning {
            background: #FEF3C7;
            color: #92400E;
        }

        .badge-info {
            background: #DBEAFE;
            color: #1E40AF;
        }

        /* ── TOTALS ROW ── */
        .totals-row td {
            background: #E8F5E9 !important;
            font-weight: 900;
            font-size: 10px;
            border-top: 2px solid #4CAF50;
            padding: 8px 6px;
        }

        /* ── FOOTER ── */
        .report-footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            display: table;
            width: 100%;
            font-size: 8px;
            color: #9CA3AF;
        }

        .footer-left {
            display: table-cell;
            width: 50%;
        }

        .footer-right {
            display: table-cell;
            width: 50%;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="page-container">
        {{-- HEADER --}}
        <div class="report-header">
            <div class="header-left">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" class="header-logo" alt="Logo"><br>
                @endif
                <div class="report-title">Reporte de Control de Fletes</div>
                <div class="report-subtitle">Planilla de Control de Fletes y Viajes</div>
            </div>
            <div class="header-right">
                @if($tenant)
                    <div class="company-name">{{ $tenant->company_name ?? $tenant->name ?? '' }}</div>
                @endif
                <div class="date-range-badge">📅 Período: {{ $dateRange }}</div>
                <br>
                <span style="font-size: 9px; color: #9CA3AF; margin-top: 4px; display: inline-block;">
                    Generado: {{ now()->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>

        {{-- KPI CARDS --}}
        <div class="kpi-row">
            <div class="kpi-card kpi-outsourcing">
                <div class="kpi-inner">
                    <div class="kpi-value">{{ $currency }} {{ number_format($stats['total_outsourcing'], 2) }}</div>
                    <div class="kpi-label">Total Outsourcing (Costos)</div>
                </div>
            </div>
            <div class="kpi-card kpi-client">
                <div class="kpi-inner">
                    <div class="kpi-value">{{ $currency }} {{ number_format($stats['total_client'], 2) }}</div>
                    <div class="kpi-label">Facturación Cliente Final</div>
                </div>
            </div>
            <div class="kpi-card kpi-revenue">
                <div class="kpi-inner">
                    <div class="kpi-value">{{ $currency }} {{ number_format($stats['total_revenue'], 2) }}</div>
                    <div class="kpi-label">Utilidad Total (Rev)</div>
                </div>
            </div>
        </div>

        {{-- DATA TABLE --}}
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Conductor</th>
                    <th>Empresa</th>
                    <th>Descripción</th>
                    <th class="text-right">Outsourcing</th>
                    <th class="text-right">Cliente Final</th>
                    <th class="text-right">Utilidad</th>
                    <th>Factura</th>
                    <th class="text-center">Estado Factura</th>
                    <th class="text-center">Pago Chofer</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trips as $trip)
                    <tr>
                        <td class="fw-bold">{{ $trip->date->format('d/m/Y') }}</td>
                        <td>{{ $trip->driver_name }}</td>
                        <td class="fw-bold">{{ $trip->company_name }}</td>
                        <td class="text-muted">{{ $trip->description }}</td>
                        <td class="text-right text-muted">{{ $currency }} {{ number_format($trip->outsourcing_cost, 2) }}</td>
                        <td class="text-right fw-bold text-primary">{{ $currency }} {{ number_format($trip->final_client_price, 2) }}</td>
                        <td class="text-right fw-bold text-success">{{ $currency }} {{ number_format($trip->revenue, 2) }}</td>
                        <td>{{ $trip->invoice_number ?? '-' }}</td>
                        <td class="text-center">
                            @php
                                $invClass = match($trip->invoice_status) {
                                    'PAGADA' => 'badge-success',
                                    'ABONO' => 'badge-info',
                                    'PENDIENTE' => 'badge-warning',
                                    default => '',
                                };
                            @endphp
                            <span class="badge {{ $invClass }}">{{ $trip->invoice_status }}</span>
                        </td>
                        <td class="text-center">
                            @php
                                $drvClass = match($trip->driver_payment_status) {
                                    'PAGADA' => 'badge-success',
                                    'PENDIENTE' => 'badge-warning',
                                    default => '',
                                };
                            @endphp
                            <span class="badge {{ $drvClass }}">{{ $trip->driver_payment_status }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center" style="padding: 30px; color: #9CA3AF; font-style: italic;">
                            No se encontraron registros para el período seleccionado.
                        </td>
                    </tr>
                @endforelse

                {{-- FILA DE TOTALES --}}
                @if($trips->count() > 0)
                    <tr class="totals-row">
                        <td colspan="4" class="text-right" style="text-transform: uppercase; letter-spacing: 1px;">
                            TOTALES ({{ $trips->count() }} registros)
                        </td>
                        <td class="text-right">{{ $currency }} {{ number_format($stats['total_outsourcing'], 2) }}</td>
                        <td class="text-right">{{ $currency }} {{ number_format($stats['total_client'], 2) }}</td>
                        <td class="text-right">{{ $currency }} {{ number_format($stats['total_revenue'], 2) }}</td>
                        <td colspan="3"></td>
                    </tr>
                @endif
            </tbody>
        </table>

        {{-- FOOTER --}}
        <div class="report-footer">
            <div class="footer-left">
                Documento generado automáticamente — {{ $tenant->company_name ?? $tenant->name ?? 'LogiSaaS' }}
            </div>
            <div class="footer-right">
                {{ now()->format('d/m/Y H:i:s') }}
            </div>
        </div>
    </div>
</body>
</html>
