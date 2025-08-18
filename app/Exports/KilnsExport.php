<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KilnsExport implements FromView, ShouldAutoSize, WithStyles
{
    public function __construct(private Collection $kilns)
    {
    }

    public function view(): \Illuminate\Contracts\View\View
    {
        return view('exports.kilns', [
            'kilns' => $this->kilns,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
            ],
        ];
    }
}
