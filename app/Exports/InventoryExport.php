<?php

namespace App\Exports;

use App\Models\Package;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventoryExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Package::with(['customer.user', 'warehouse'])->get();
    }

    public function headings(): array
    {
        return [
            'Tracking',
            'Cliente',
            'Casillero',
            'Bodega',
            'Peso (lbs)',
            'Estado',
            'Fecha Ingreso',
        ];
    }

    public function map($package): array
    {
        return [
            $package->tracking_number,
            $package->customer->user->name,
            $package->customer->box_number,
            $package->warehouse->code,
            $package->weight,
            strtoupper($package->status),
            $package->created_at->format('d/m/Y'),
        ];
    }
}
