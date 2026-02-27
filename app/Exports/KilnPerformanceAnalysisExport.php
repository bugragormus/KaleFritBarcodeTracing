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

class KilnPerformanceAnalysisExport implements WithMultipleSheets
{
    protected $kilnPerformanceData;
    protected $overallStats;
    protected $startDate;
    protected $endDate;

    public function __construct($kilnPerformanceData, $overallStats, $startDate, $endDate)
    {
        $this->kilnPerformanceData = $kilnPerformanceData;
        $this->overallStats = $overallStats;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheets(): array
    {
        return [
            'Genel Özet' => new GeneralSummarySheet($this->overallStats, $this->startDate, $this->endDate),
            'Fırın Detayları' => new KilnDetailsSheet($this->kilnPerformanceData),
            'Red Sebepleri' => new RejectionReasonsSheet($this->kilnPerformanceData),
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
            ['FIRIN PERFORMANS ANALİZİ RAPORU'],
            [''],
            ['Rapor Tarihi:', now()->format('d.m.Y H:i')],
            ['Analiz Dönemi:', $this->startDate->format('d.m.Y') . ' - ' . $this->endDate->format('d.m.Y')],
            [''],
            ['GENEL İSTATİSTİKLER'],
            [''],
            ['Toplam Fırın Sayısı:', $this->overallStats['total_kilns']],
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
            ['Genel Verimlilik Oranı (%):', number_format($this->overallStats['overall_efficiency_rate'], 2)],
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

class KilnDetailsSheet implements FromArray, WithTitle, WithStyles
{
    protected $kilnPerformanceData;

    public function __construct($kilnPerformanceData)
    {
        $this->kilnPerformanceData = $kilnPerformanceData;
    }

    public function array(): array
    {
        $data = [
            ['FIRIN BAZINDA PERFORMANS DETAYLARI'],
            [''],
            ['Fırın Adı', 'Şarj No', 'Toplam Barkod', 'Kabul Edilen', 'Reddedilen', 'Kontrol Tekrarı', 'Toplam KG', 'Kabul KG', 'Red KG', 'Kabul Oranı (%)', 'Red Oranı (%)', 'Verimlilik (%)', 'Günlük Ortalama', 'Ana Red Sebebi', 'Red Sebebi Tekrar Sayısı'],
        ];

        foreach ($this->kilnPerformanceData as $kilnData) {
            $data[] = [
                $kilnData['kiln']->name,
                $kilnData['last_load_number'] ?? 'N/A',
                $kilnData['total_barcodes'],
                $kilnData['accepted_barcodes'],
                $kilnData['rejected_barcodes'],
                $kilnData['control_repeat_barcodes'],
                number_format($kilnData['total_kg'], 2),
                number_format($kilnData['accepted_kg'], 2),
                number_format($kilnData['rejected_kg'], 2),
                number_format($kilnData['acceptance_rate'], 2),
                number_format($kilnData['rejection_rate'], 2),
                number_format($kilnData['efficiency_rate'], 2),
                number_format($kilnData['daily_average'], 2),
                $kilnData['top_rejection_reason'] ?? '-',
                $kilnData['top_rejection_count'] ?? 0,
            ];
        }

        return $data;
    }

    public function title(): string
    {
        return 'Fırın Detayları';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A3:O3')->getFont()->setBold(true);
        $sheet->getStyle('A3:O3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E9ECEF');
    }
}

class RejectionReasonsSheet implements FromArray, WithTitle, WithStyles
{
    protected $kilnPerformanceData;

    public function __construct($kilnPerformanceData)
    {
        $this->kilnPerformanceData = $kilnPerformanceData;
    }

    public function array(): array
    {
        $data = [
            ['RED SEBEPLERİ DETAYLI ANALİZİ'],
            [''],
            ['Fırın Adı', 'Şarj No', 'Red Sebebi', 'Tekrar Sayısı', 'Toplam KG', 'Red Oranı (%)'],
        ];

        foreach ($this->kilnPerformanceData as $kilnData) {
            if (count($kilnData['rejection_reasons']) > 0) {
                foreach ($kilnData['rejection_reasons'] as $reasonName => $reasonStats) {
                    $data[] = [
                        $kilnData['kiln']->name,
                        $kilnData['last_load_number'] ?? 'N/A',
                        $reasonName,
                        $reasonStats['count'],
                        number_format($reasonStats['kg'], 2),
                        number_format($kilnData['total_kg'] > 0 ? ($reasonStats['kg'] / $kilnData['total_kg']) * 100 : 0, 2),
                    ];
                }
            } else {
                $data[] = [
                    $kilnData['kiln']->name,
                    $kilnData['last_load_number'] ?? 'N/A',
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
