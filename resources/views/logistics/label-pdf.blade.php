<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; margin: 0; padding: 10px; }
        .label-container { border: 2px solid #000; height: 100%; padding: 10px; box-sizing: border-box; }
        .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; text-align: center; }
        .company-name { font-size: 18px; font-weight: bold; }
        .tracking-section { text-align: center; margin-bottom: 20px; }
        .tracking-number { font-size: 20px; font-weight: bold; display: block; margin-top: 5px; }
        .customer-section { border-top: 1px solid #000; padding-top: 10px; }
        .box-number { font-size: 32px; font-weight: 900; text-align: center; margin: 10px 0; border: 3px solid #000; padding: 5px; }
        .customer-name { font-size: 14px; font-weight: bold; }
        .footer { position: absolute; bottom: 10px; width: 100%; font-size: 10px; text-align: center; }
        .barcode-placeholder { height: 60px; background: #eee; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="label-container">
        <div class="header">
            <div class="company-name">{{ config('app.name') }}</div>
            <div style="font-size: 10px;">{{ $package->warehouse->name }} ({{ $package->warehouse->code }})</div>
        </div>

        <div class="tracking-section">
            <span style="font-size: 10px; font-weight: bold; text-transform: uppercase;">Tracking Ext:</span>
            <span class="tracking-number">{{ $package->tracking_number }}</span>
            <div style="margin-top: 10px;" class="barcode-placeholder">
                [ BARCODE: {{ $package->tracking_number }} ]
            </div>
        </div>

        <div class="customer-section">
            <div style="font-size: 10px; font-weight: bold;">DESTINATARIO:</div>
            <div class="box-number">{{ $package->customer->box_number }}</div>
            <div class="customer-name">{{ strtoupper($package->customer->user->name) }}</div>
            <div style="font-size: 10px; margin-top: 5px;">{{ $package->customer->phone }}</div>
        </div>

        <div style="margin-top: 20px; font-size: 14px; font-weight: bold; text-align: right;">
            PESO: {{ $package->weight }} lbs
        </div>

        <div class="footer">
            Generado por {{ $package->tenant->name ?? 'LogiSaaS' }} - {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>
