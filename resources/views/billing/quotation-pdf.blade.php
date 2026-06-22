<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cotización {{ $quotation->number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; }
        .header { margin-bottom: 30px; }
        .company-name { font-size: 24px; font-weight: bold; color: #0d6efd; }
        .invoice-details { float: right; text-align: right; }
        .customer-details { margin-bottom: 40px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background-color: #f3f4f6; text-align: left; padding: 10px; border-bottom: 2px solid #ddd; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .total-section { float: right; width: 250px; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .total-label { font-weight: bold; }
        .grand-total { font-size: 18px; font-weight: bold; color: #0d6efd; margin-top: 10px; border-top: 2px solid #0d6efd; padding-top: 10px; }
        .notes { margin-top: 40px; font-size: 12px; color: #555; background: #f8f9fa; padding: 15px; border-left: 4px solid #0d6efd; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <div class="invoice-details">
            <div style="font-size: 20px; font-weight: bold;">COTIZACIÓN</div>
            <div>#{{ $quotation->number }}</div>
            <div>Fecha: {{ $quotation->created_at->format('d/m/Y') }}</div>
        </div>

        @php
            $tenant = $quotation->tenant;
        @endphp

        @if(isset($logoBase64) && $logoBase64)
            <img src="{{ $logoBase64 }}" style="max-height: 70px; max-width: 280px; margin-bottom: 10px;">
        @else
            <div class="company-name">{{ $tenant->name ?? config('app.name') }}</div>
        @endif
    </div>

    <div class="customer-details">
        <strong>COTIZADO A:</strong><br>
        {{ $quotation->customer?->user?->name ?? $quotation->client_name ?? 'S/D' }}<br>
        @if($quotation->customer)
            Casillero: {{ $quotation->customer->box_number ?? 'S/D' }}<br>
        @endif
        Email: {{ $quotation->customer?->user?->email ?? $quotation->client_email ?? '' }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Item #</th>
                <th style="width: 35%;">Descripción</th>
                <th style="width: 10%; text-align: right;">Cant.</th>
                <th style="width: 20%; text-align: right;">Precio Unit.</th>
                <th style="width: 20%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $item)
            <tr>
                <td>{{ $item->item_number ?: '-' }}</td>
                <td>{{ $item->description }}</td>
                <td style="text-align: right;">{{ $item->quantity }}</td>
                <td style="text-align: right;">{{ number_format($item->price, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; padding: 5px 0;"><strong>Subtotal Artículos:</strong></td>
                <td style="border: none; padding: 5px 0; text-align: right;">{{ $currency }} {{ number_format($quotation->subtotal, 2) }}</td>
            </tr>
            @if($quotation->handling_total > 0)
            <tr>
                <td style="border: none; padding: 5px 0;"><strong>Cargos por Manejo:</strong></td>
                <td style="border: none; padding: 5px 0; text-align: right;">{{ $currency }} {{ number_format($quotation->handling_total, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td style="border: none; padding: 10px 0; border-top: 2px solid #0d6efd;"><span style="font-size: 18px; color: #0d6efd;"><strong>TOTAL:</strong></span></td>
                <td style="border: none; padding: 10px 0; border-top: 2px solid #0d6efd; text-align: right;"><span style="font-size: 18px; color: #0d6efd;"><strong>{{ $currency }} {{ number_format($quotation->total, 2) }}</strong></span></td>
            </tr>
        </table>
    </div>

    @if($quotation->notes)
    <div style="clear: both;"></div>
    <div class="notes">
        <strong>NOTAS Y TÉRMINOS:</strong><br>
        {!! nl2br(e($quotation->notes)) !!}
    </div>
    @endif

    <div class="footer">
        Este documento es una cotización comercial generada por {{ $tenant->name ?? 'LogiSaaS' }} y no representa una factura fiscal ni obliga a su pago. Los precios están sujetos a cambios.
    </div>
</body>
</html>
