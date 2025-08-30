<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CompanyExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $companies;
    protected $startDate;
    protected $endDate;
    protected $period;

    public function __construct(Collection $companies, $startDate = null, $endDate = null, $period = null)
    {
        $this->companies = $companies;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->period = $period;
    }

    public function view(): \Illuminate\Contracts\View\View
    {
        return view('exports.companies', [
            'companies' => $this->companies,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'period' => $this->period
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ]
            ]
        ];
    }
}
