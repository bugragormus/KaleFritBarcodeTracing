<?php

namespace App\Exports;

use App\Models\Barcode;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class FritSalesHistoryExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $filters;

    public function __construct($startDate, $endDate, $filters = [])
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = Barcode::query()
            ->with(['stock', 'quantity', 'company', 'deliveredBy'])
            ->whereIn('status', [Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED])
            ->whereBetween('delivered_at', [$this->startDate, $this->endDate]);

        // Apply filters if provided
        if (!empty($this->filters['stock_id'])) {
            $query->whereIn('stock_id', (array) $this->filters['stock_id']);
        }
        if (!empty($this->filters['barcode_id'])) {
            $query->whereIn('id', (array) $this->filters['barcode_id']);
        }
        if (!empty($this->filters['load_number'])) {
            $query->whereIn('load_number', (array) $this->filters['load_number']);
        }
        if (!empty($this->filters['company_id'])) {
            $query->whereIn('company_id', (array) $this->filters['company_id']);
        }
        if (!empty($this->filters['user_id'])) {
            $query->whereIn('delivered_by', (array) $this->filters['user_id']);
        }

        $data = $query->orderBy('delivered_at', 'desc')->get();

        return view('exports.frit-sales-history', [
            'data' => $data,
            'startDate' => $this->startDate->format('d.m.Y'),
            'endDate' => $this->endDate->format('d.m.Y')
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:H2')->getFont()->setBold(true);
        $sheet->getStyle('A1:H2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        return [
            1 => ['font' => ['size' => 14]],
            2 => ['font' => ['bold' => true]],
        ];
    }
}
