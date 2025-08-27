<?php

namespace App\Exports;

use App\Models\Stock;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StockExport implements FromView, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    use Exportable;

    /**
     * @var \App\Models\Stock
     */
    private $stocks;

    public function __construct(Collection $stocks)
    {
        $this->stocks = $stocks;
    }

    public function view(): View
    {
        return view('exports.stocks', [
            'stocks' => $this->stocks
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
