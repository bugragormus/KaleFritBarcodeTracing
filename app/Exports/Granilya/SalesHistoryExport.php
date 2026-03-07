<?php

namespace App\Exports\Granilya;

use App\Models\GranilyaProduction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesHistoryExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $startDate = $this->filters['date_start'] ?? now()->subDays(30)->format('Y-m-d');
        $endDate = $this->filters['date_end'] ?? now()->format('Y-m-d');

        $query = GranilyaProduction::where('status', GranilyaProduction::STATUS_DELIVERED)
            ->with(['stock', 'deliveryCompany', 'user', 'size']);

        if (!empty($this->filters['pallet_no'])) {
            $query->where('pallet_number', 'LIKE', '%' . $this->filters['pallet_no'] . '%');
        }

        if (!empty($this->filters['stock_id'])) {
            $query->whereIn('stock_id', (array)$this->filters['stock_id']);
        }

        if (!empty($this->filters['company_id'])) {
            $query->where('delivery_company_id', $this->filters['company_id']);
        }

        if (!empty($this->filters['user_id'])) {
            $query->where('user_id', $this->filters['user_id']);
        }

        $query->whereDate('delivered_at', '>=', $startDate)
              ->whereDate('delivered_at', '<=', $endDate);

        $sales = $query->orderBy('delivered_at', 'desc')->get();

        return view('exports.granilya.sales-history', [
            'sales' => $sales,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        return [];
    }
}
