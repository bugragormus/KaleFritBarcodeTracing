<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class StockQualityAnalysisExport implements WithMultipleSheets
{
    protected $stockQualityData;
    protected $overallStats;
    protected $startDate;
    protected $endDate;

    public function __construct($stockQualityData, $overallStats, $startDate, $endDate)
    {
        $this->stockQualityData = $stockQualityData;
        $this->overallStats = $overallStats;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheets(): array
    {
        return [
            'Genel Özet' => new GeneralSummarySheet($this->overallStats, $this->startDate, $this->endDate),
            'Stok Detayları' => new StockDetailsSheet($this->stockQualityData),
            'Red Sebepleri' => new RejectionReasonsSheet($this->stockQualityData),
        ];
    }
}

class GeneralSummarySheet implements FromArray, WithTitle, WithStyles
{
    protected $overallStats;
    protected $startDate;
    protected $endDate;

    public function __construct($overallStats, $startDate, $endDate)
    {
        $this->overallStats = $overallStats;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function array(): array
    {
        $data = [
            ['STOK KALİTE ANALİZİ RAPORU'],
            [''],
            ['Rapor Tarihi:', now()->format('d.m.Y H:i')],
            ['Analiz Dönemi:', $this->startDate->format('d.m.Y') . ' - ' . $this->endDate->format('d.m.Y')],
            [''],
            ['GENEL İSTATİSTİKLER'],
            [''],
            ['Toplam Stok Sayısı:', $this->overallStats['total_stocks']],
            ['Toplam Barkod Sayısı:', $this->overallStats['total_barcodes']],
            ['Kabul Edilen Barkod:', $this->overallStats['total_accepted']],
            ['Reddedilen Barkod:', $this->overallStats['total_rejected']],
            [''],
            ['Toplam Miktar (KG):', number_format($this->overallStats['total_kg'], 2)],
            ['Kabul Edilen Miktar (KG):', number_format($this->overallStats['total_accepted_kg'], 2)],
            ['Reddedilen Miktar (KG):', number_format($this->overallStats['total_rejected_kg'], 2)],
            [''],
            ['Genel Kabul Oranı (%):', number_format($this->overallStats['overall_acceptance_rate'], 2)],
            ['Genel Red Oranı (%):', number_format($this->overallStats['overall_rejection_rate'], 2)],
        ];

        return $data;
    }

    public function title(): string
    {
        return 'Genel Özet';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A15')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A17')->getFont()->setBold(true)->setSize(14);
    }
}

class StockDetailsSheet implements FromArray, WithTitle, WithStyles
{
    protected $stockQualityData;

    public function __construct($stockQualityData)
    {
        $this->stockQualityData = $stockQualityData;
    }

    public function array(): array
    {
        $data = [
            ['STOK BAZINDA KALİTE DETAYLARI'],
            [''],
            ['Stok Adı', 'Stok Kodu', 'Toplam Barkod', 'Kabul Edilen', 'Reddedilen', 'Kontrol Tekrarı', 'Toplam KG', 'Kabul KG', 'Red KG', 'Kabul Oranı (%)', 'Red Oranı (%)', 'Ana Red Sebebi', 'Red Sebebi Tekrar Sayısı'],
        ];

        foreach ($this->stockQualityData as $stockData) {
            $data[] = [
                $stockData['stock']->name,
                $stockData['stock']->code,
                $stockData['total_barcodes'],
                $stockData['accepted_barcodes'],
                $stockData['rejected_barcodes'],
                $stockData['control_repeat_barcodes'],
                number_format($stockData['total_kg'], 2),
                number_format($stockData['accepted_kg'], 2),
                number_format($stockData['rejected_kg'], 2),
                number_format($stockData['acceptance_rate'], 2),
                number_format($stockData['rejection_rate'], 2),
                $stockData['top_rejection_reason'] ?? '-',
                $stockData['top_rejection_count'] ?? 0,
            ];
        }

        return $data;
    }

    public function title(): string
    {
        return 'Stok Detayları';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A3:M3')->getFont()->setBold(true);
        $sheet->getStyle('A3:M3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E9ECEF');
    }
}

class RejectionReasonsSheet implements FromArray, WithTitle, WithStyles
{
    protected $stockQualityData;

    public function __construct($stockQualityData)
    {
        $this->stockQualityData = $stockQualityData;
    }

    public function array(): array
    {
        $data = [
            ['RED SEBEPLERİ DETAYLI ANALİZİ'],
            [''],
            ['Stok Adı', 'Stok Kodu', 'Red Sebebi', 'Tekrar Sayısı', 'Toplam KG', 'Red Oranı (%)'],
        ];

        foreach ($this->stockQualityData as $stockData) {
            if (count($stockData['rejection_reasons']) > 0) {
                foreach ($stockData['rejection_reasons'] as $reasonName => $reasonStats) {
                    $data[] = [
                        $stockData['stock']->name,
                        $stockData['stock']->code,
                        $reasonName,
                        $reasonStats['count'],
                        number_format($reasonStats['kg'], 2),
                        number_format(($reasonStats['count'] / $stockData['total_barcodes']) * 100, 2),
                    ];
                }
            } else {
                $data[] = [
                    $stockData['stock']->name,
                    $stockData['stock']->code,
                    'Red yok',
                    0,
                    0,
                    0,
                ];
            }
        }

        return $data;
    }

    public function title(): string
    {
        return 'Red Sebepleri';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A3:F3')->getFont()->setBold(true);
        $sheet->getStyle('A3:F3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E9ECEF');
    }
}
