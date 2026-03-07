<?php

namespace App\Exports\Granilya;

use App\Models\GranilyaProduction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaboratoryReportExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $data = GranilyaProduction::with(['stock', 'user', 'quantity'])
            ->whereBetween('updated_at', [$this->startDate, $this->endDate])
            ->whereIn('status', [
                GranilyaProduction::STATUS_PRE_APPROVED,
                GranilyaProduction::STATUS_SHIPMENT_APPROVED,
                GranilyaProduction::STATUS_REJECTED,
                6, 9, 10
            ])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('exports.granilya.laboratory-report', [
            'data' => $data,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I3')->getFont()->setBold(true);
        return [];
    }
}
