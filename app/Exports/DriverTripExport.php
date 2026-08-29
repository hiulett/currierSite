<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DriverTripExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings, WithMapping, WithStyles
{
    protected Collection $trips;

    protected string $currency;

    public function __construct(Collection $trips, string $currency = 'USD')
    {
        $this->trips = $trips;
        $this->currency = $currency;
    }

    public function collection(): Collection
    {
        return $this->trips;
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Conductor',
            'Empresa',
            'Descripción',
            'Outsourcing ('.$this->currency.')',
            'Cliente Final ('.$this->currency.')',
            'Utilidad ('.$this->currency.')',
            'Factura',
            'Estado Factura',
            'Pago Chofer',
        ];
    }

    public function map($trip): array
    {
        return [
            $trip->date->format('d/m/Y'),
            $trip->driver_name,
            $trip->company_name,
            $trip->description,
            (float) $trip->outsourcing_cost,
            (float) $trip->final_client_price,
            (float) $trip->revenue,
            $trip->invoice_number ?? '-',
            $trip->invoice_status,
            $trip->driver_payment_status,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // #,##0.00
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Encabezados en negrita
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2B3A67'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $rowCount = $this->trips->count() + 1; // +1 for header

                // Bordes para toda la tabla
                $sheet->getStyle('A1:J'.$rowCount)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D1D5DB'],
                        ],
                    ],
                ]);

                // Alinear columnas monetarias a la derecha
                $sheet->getStyle('E2:G'.$rowCount)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Altura del encabezado
                $sheet->getRowDimension(1)->setRowHeight(28);

                // Fila de totales
                $totalRow = $rowCount + 1;
                $sheet->setCellValue('D'.$totalRow, 'TOTALES');
                $sheet->setCellValue('E'.$totalRow, $this->trips->sum('outsourcing_cost'));
                $sheet->setCellValue('F'.$totalRow, $this->trips->sum('final_client_price'));
                $sheet->setCellValue('G'.$totalRow, $this->trips->sum('revenue'));

                // Estilo de la fila de totales
                $sheet->getStyle('D'.$totalRow.':G'.$totalRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => '1A1A2E'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E8F5E9'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '4CAF50'],
                        ],
                    ],
                    'numberFormat' => [
                        'formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                    ],
                ]);

                // Filas alternas con color de fondo sutil
                for ($i = 2; $i <= $rowCount; $i++) {
                    if ($i % 2 === 0) {
                        $sheet->getStyle('A'.$i.':J'.$i)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F8F9FA'],
                            ],
                        ]);
                    }
                }
            },
        ];
    }
}
