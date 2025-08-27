<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaboratoryReportExport implements FromView, ShouldAutoSize, WithStyles
{
    protected array $summary;
    protected $report;
    protected $generalRejectionStats;
    protected $rejectionReasonsAnalysis;
    protected $startDate;
    protected $endDate;

    public function __construct(
        array $summary,
        $report,
        $generalRejectionStats,
        $rejectionReasonsAnalysis,
        $startDate,
        $endDate
    ) {
        $this->summary = $summary;
        $this->report = $report;
        $this->generalRejectionStats = $generalRejectionStats;
        $this->rejectionReasonsAnalysis = $rejectionReasonsAnalysis;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        return view('exports.laboratory-report', [
            'summary' => $this->summary,
            'report' => $this->report,
            'generalRejectionStats' => $this->generalRejectionStats,
            'rejectionReasonsAnalysis' => $this->rejectionReasonsAnalysis,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Bold header row(s)
        $sheet->getStyle('A1:Z1')->getFont()->setBold(true);
        return [];
    }
}
