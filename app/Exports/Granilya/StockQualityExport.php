<?php

namespace App\Exports\Granilya;

use App\Models\Stock;
use App\Models\GranilyaProduction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockQualityExport implements FromView, ShouldAutoSize, WithStyles
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
        $stockQualityData = Stock::whereHas('granilyaProductions', function($q) {
            $q->whereBetween('updated_at', [$this->startDate, $this->endDate]);
        })->with(['granilyaProductions' => function($q) {
            $q->whereBetween('updated_at', [$this->startDate, $this->endDate]);
        }])->get();

        $processedData = $stockQualityData->map(function($stock) {
            $productions = $stock->granilyaProductions;
            $total = $productions->count();
            $accepted = $productions->whereIn('status', [GranilyaProduction::STATUS_SHIPMENT_APPROVED, GranilyaProduction::STATUS_CUSTOMER_TRANSFER, GranilyaProduction::STATUS_DELIVERED, 6])->count();
            $rejected = $productions->where('status', GranilyaProduction::STATUS_REJECTED)->count();
            
            $reasons = [];
            foreach ($productions->where('status', GranilyaProduction::STATUS_REJECTED) as $p) {
                if ($p->sieve_test_result == 'Red') $reasons['Elek: ' . $p->sieve_reject_reason] = ($reasons['Elek: ' . $p->sieve_reject_reason] ?? 0) + 1;
                if ($p->surface_test_result == 'Red') $reasons['Yüzey: ' . $p->surface_reject_reason] = ($reasons['Yüzey: ' . $p->surface_reject_reason] ?? 0) + 1;
                if ($p->arge_test_result == 'Red') $reasons['Arge'] = ($reasons['Arge'] ?? 0) + 1;
            }
            $topReason = collect($reasons)->sortByDesc(function($v) { return $v; })->keys()->first() ?? '-';

            return [
                'name' => $stock->name,
                'total' => $total,
                'accepted' => $accepted,
                'rejected' => $rejected,
                'rate' => $total > 0 ? round(($accepted / $total) * 100, 1) : 0,
                'top_reason' => $topReason
            ];
        });

        return view('exports.granilya.stock-quality', [
            'data' => $processedData,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F3')->getFont()->setBold(true);
        return [];
    }
}
