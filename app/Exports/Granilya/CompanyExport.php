<?php

namespace App\Exports\Granilya;

use App\Models\GranilyaCompany;
use App\Models\GranilyaProduction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CompanyExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $company;
    protected $periodInfo;
    protected $data;

    public function __construct($company, $periodInfo, $data)
    {
        $this->company = $company;
        $this->periodInfo = $periodInfo;
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.granilya.company-report', [
            'company' => $this->company,
            'periodInfo' => $this->periodInfo,
            'data' => $this->data
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E5')->getFont()->setBold(true);
        return [];
    }
}
