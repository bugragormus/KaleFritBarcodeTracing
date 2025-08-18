<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WarehouseExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    /**
     * @var Collection
     */
    private $warehouses;

    public function __construct(Collection $warehouses)
    {
        $this->warehouses = $warehouses;
    }

    public function view(): View
    {
        return view('exports.warehouses', [
            'warehouses' => $this->warehouses
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
}
