<?php

namespace App\Exports\Granilya;

use App\Models\GranilyaCrusher;
use App\Models\GranilyaProduction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CrusherPerformanceExport implements FromView, ShouldAutoSize, WithStyles
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
        $crusherData = GranilyaCrusher::whereHas('granilyaProductions', function($q) {
            $q->whereBetween('updated_at', [$this->startDate, $this->endDate]);
        })->with(['granilyaProductions' => function($q) {
            $q->whereBetween('updated_at', [$this->startDate, $this->endDate]);
        }])->get()->map(function($crusher) {
            $productions = $crusher->granilyaProductions;
            $total = $productions->count();
            $accepted = $productions->whereIn('status', [GranilyaProduction::STATUS_SHIPMENT_APPROVED, GranilyaProduction::STATUS_CUSTOMER_TRANSFER, GranilyaProduction::STATUS_DELIVERED, 6])->count();
            $rejected = $productions->where('status', GranilyaProduction::STATUS_REJECTED)->count();

            return [
                'name' => $crusher->name,
                'total' => $total,
                'accepted' => $accepted,
                'rejected' => $rejected,
                'rate' => $total > 0 ? round(($accepted / $total) * 100, 1) : 0
            ];
        });

        return view('exports.granilya.crusher-performance', [
            'data' => $crusherData,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E3')->getFont()->setBold(true);
        return [];
    }
}
