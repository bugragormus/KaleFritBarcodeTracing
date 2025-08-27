<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class BarcodeExport implements FromView, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    use Exportable;

    /**
     * @var \App\Models\Barcode
     */
    private $barcodes;

    public function __construct(Collection $barcodes)
    {
        $this->barcodes = $barcodes;
    }

    public function view(): View
    {
        return view('exports.barcodes', [
            'barcodes' => $this->barcodes
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true]
            ]
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
