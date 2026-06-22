<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura {{ $invoice->number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; }
        .header { margin-bottom: 30px; }
        .company-name { font-size: 24px; font-weight: bold; color: #1a56db; }
        .invoice-details { float: right; text-align: right; }
        .customer-details { margin-bottom: 40px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background-color: #f3f4f6; text-align: left; padding: 10px; border-bottom: 2px solid #ddd; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .total-section { float: right; width: 250px; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .total-label { font-weight: bold; }
        .grand-total { font-size: 18px; font-weight: bold; color: #1a56db; margin-top: 10px; border-top: 2px solid #1a56db; padding-top: 10px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <div class="invoice-details">
            <div style="font-size: 20px; font-weight: bold;">FACTURA</div>
            <div>#{{ $invoice->number }}</div>
            <div>Fecha: {{ $invoice->created_at->format('d/m/Y') }}</div>
            <div>Vence: {{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : 'N/A' }}</div>
        </div>

        @php
            $tenant = $invoice->tenant;
        @endphp

        @if(isset($logoBase64) && $logoBase64)
            <img src="{{ $logoBase64 }}" style="max-height: 70px; max-width: 280px; margin-bottom: 10px;">
        @else
            <div class="company-name">{{ $tenant->name ?? config('app.name') }}</div>
        @endif
    </div>

    <div class="customer-details">
        <strong>CLIENTE:</strong><br>
        {{ $invoice->customer->user->name }}<br>
        Casillero: {{ $invoice->customer->box_number }}<br>
        {{ $invoice->customer->address }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th style="text-align: right;">Cantidad</th>
                <th style="text-align: right;">Precio</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td style="text-align: right;">{{ $item->quantity }}</td>
                <td style="text-align: right;">{{ number_format($item->unit_price, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; padding: 5px 0;"><strong>Subtotal:</strong></td>
                <td style="border: none; padding: 5px 0; text-align: right;">{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            @if($invoice->tax > 0)
            <tr>
                <td style="border: none; padding: 5px 0;"><strong>Impuestos:</strong></td>
                <td style="border: none; padding: 5px 0; text-align: right;">{{ $invoice->currency }} {{ number_format($invoice->tax, 2) }}</td>
            </tr>
            @endif
            @if($invoice->discount > 0)
            <tr>
                <td style="border: none; padding: 5px 0;"><strong>Descuento:</strong></td>
                <td style="border: none; padding: 5px 0; text-align: right;">-{{ $invoice->currency }} {{ number_format($invoice->discount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td style="border: none; padding: 10px 0; border-top: 2px solid #1a56db;"><span style="font-size: 18px; color: #1a56db;"><strong>TOTAL:</strong></span></td>
                <td style="border: none; padding: 10px 0; border-top: 2px solid #1a56db; text-align: right;"><span style="font-size: 18px; color: #1a56db;"><strong>{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</strong></span></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Este documento es una representación física de una factura electrónica generada por {{ $invoice->tenant->name ?? 'LogiSaaS' }}.
    </div>
</body>
</html>
