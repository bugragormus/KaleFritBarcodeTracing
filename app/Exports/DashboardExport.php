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
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class DashboardExport implements WithMultipleSheets
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            'Üretim Özeti' => new DailyProductionSheet($this->data),
            'Vardiya Raporu' => new ShiftReportSheet($this->data),
            'Fırın Performansı' => new KilnPerformanceSheet($this->data),
            'Red Sebepleri' => new RejectionReasonsSheet($this->data),
            'Stok Yaşı Analizi' => new StockAgeSheet($this->data),
            'Aylık Karşılaştırma' => new MonthlyComparisonSheet($this->data),
            'OEE Analizi' => new OEESheet($this->data),
            'AI/ML İçgörüler' => new AIMLSheet($this->data),
        ];
    }
}

class DailyProductionSheet implements FromArray, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $productionData = $this->data['productionData'] ?? [];
        $selectedDate = $this->data['selectedDate'] ?? now()->format('d.m.Y');
        $period = $this->data['period'] ?? 'daily';
        $periodInfo = $this->data['periodInfo'] ?? [];
        
        return [
            ['ÜRETİM RAPORU'],
            [''],
            ['Periyot:', $periodInfo['name'] ?? 'Günlük'],
            ['Başlangıç Tarihi:', $periodInfo['start_date_formatted'] ?? $selectedDate],
            ['Bitiş Tarihi:', $periodInfo['end_date_formatted'] ?? $selectedDate],
            ['Oluşturulma Tarihi:', now()->format('d.m.Y H:i:s')],
            [''],
            ['ÜRETİM İSTATİSTİKLERİ'],
            [''],
            ['Metrik', 'Değer', 'Birim'],
            ['Toplam Barkod', number_format($productionData['total_barcodes'] ?? 0), 'adet'],
            ['Toplam Miktar', number_format($productionData['total_quantity'] ?? 0, 1), 'ton'],
            ['Kabul Edilen', number_format($productionData['accepted_quantity'] ?? 0, 1), 'ton'],
            ['Test Sürecinde', number_format($productionData['testing_quantity'] ?? 0, 1), 'ton'],
            ['Teslimat Sürecinde', number_format($productionData['delivery_quantity'] ?? 0, 1), 'ton'],
            ['Reddedilen', number_format($productionData['rejected_quantity'] ?? 0, 1), 'ton'],
            [''],
            ['DÜZELTME FAALİYETİ'],
            [''],
            ['Düzeltmeli Üretim', number_format($productionData['with_correction_output'] ?? 0, 1), 'ton'],
            ['Düzeltmesiz Üretim', number_format($productionData['without_correction_output'] ?? 0, 1), 'ton'],
            ['Düzeltmede Kullanılan Red', number_format($productionData['correction_input_used'] ?? 0, 1), 'ton'],
            ['Toplam Hammadde Kullanımı', number_format($productionData['raw_material_used'] ?? 0, 1), 'ton'],
        ];
    }

    public function title(): string
    {
        return 'Günlük Üretim Özeti';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A1:C1');
        
        $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A6:C6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A6:C6');
        
        $sheet->getStyle('A15')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A15:C15')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A15:C15');
        
        $sheet->getStyle('A8:C8')->getFont()->setBold(true);
        $sheet->getStyle('A8:C8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
        
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);
    }
}

class ShiftReportSheet implements FromArray, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $shiftReport = $this->data['shiftReport'] ?? [];
        $selectedDate = $this->data['selectedDate'] ?? now()->format('d.m.Y');
        $periodInfo = $this->data['periodInfo'] ?? [];
        
        $rows = [
            ['VARDİYA RAPORU'],
            [''],
            ['Periyot:', $periodInfo['name'] ?? 'Günlük'],
            ['Başlangıç Tarihi:', $periodInfo['start_date_formatted'] ?? $selectedDate],
            ['Bitiş Tarihi:', $periodInfo['end_date_formatted'] ?? $selectedDate],
            ['Oluşturulma Tarihi:', now()->format('d.m.Y H:i:s')],
            [''],
            ['VARDİYA BAZINDA ÜRETİM'],
            [''],
            ['Vardiya', 'Barkod Sayısı', 'Toplam Miktar (ton)', 'Kabul Edilen (ton)', 'Test Sürecinde (ton)', 'Teslimat Sürecinde (ton)', 'Reddedilen (ton)'],
        ];
        
        if (count($shiftReport) > 0) {
            if (is_array($shiftReport)) {
                foreach ($shiftReport as $shiftName => $shiftData) {
                    $shiftDisplayName = match($shiftName) {
                        'gece' => 'Gece (00:00-08:00)',
                        'gündüz' => 'Gündüz (08:00-16:00)',
                        'akşam' => 'Akşam (16:00-24:00)',
                        default => ucfirst($shiftName)
                    };
                    
                    $rows[] = [
                        $shiftDisplayName,
                        number_format($shiftData['barcode_count'] ?? 0),
                        number_format($shiftData['total_quantity'] ?? 0, 1),
                        number_format($shiftData['accepted_quantity'] ?? 0, 1),
                        number_format($shiftData['testing_quantity'] ?? 0, 1),
                        number_format($shiftData['delivery_quantity'] ?? 0, 1),
                        number_format($shiftData['rejected_quantity'] ?? 0, 1),
                    ];
                }
            } else {
                foreach ($shiftReport as $shiftName => $shiftData) {
                    $shiftDisplayName = match($shiftName) {
                        'gece' => 'Gece (00:00-08:00)',
                        'gündüz' => 'Gündüz (08:00-16:00)',
                        'akşam' => 'Akşam (16:00-24:00)',
                        default => ucfirst($shiftName)
                    };
                    
                    $rows[] = [
                        $shiftDisplayName,
                        number_format($shiftData['barcode_count'] ?? 0),
                        number_format($shiftData['total_quantity'] ?? 0, 1),
                        number_format($shiftData['accepted_quantity'] ?? 0, 1),
                        number_format($shiftData['testing_quantity'] ?? 0, 1),
                        number_format($shiftData['delivery_quantity'] ?? 0, 1),
                        number_format($shiftData['rejected_quantity'] ?? 0, 1),
                    ];
                }
            }
        } else {
            $rows[] = ['Vardiya raporu sadece günlük periyotta mevcuttur', '', '', '', '', '', ''];
        }
        
        // Toplam satırı
        $totalBarcodes = array_sum(array_column($shiftReport, 'barcode_count'));
        $totalQuantity = array_sum(array_column($shiftReport, 'total_quantity'));
        $totalAccepted = array_sum(array_column($shiftReport, 'accepted_quantity'));
        $totalTesting = array_sum(array_column($shiftReport, 'testing_quantity'));
        $totalDelivery = array_sum(array_column($shiftReport, 'delivery_quantity'));
        $totalRejected = array_sum(array_column($shiftReport, 'rejected_quantity'));
        
        $rows[] = [
            'TOPLAM',
            number_format($totalBarcodes),
            number_format($totalQuantity, 1),
            number_format($totalAccepted, 1),
            number_format($totalTesting, 1),
            number_format($totalDelivery, 1),
            number_format($totalRejected, 1),
        ];
        
        return $rows;
    }

    public function title(): string
    {
        return 'Vardiya Raporu';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A1:G1');
        
        $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A6:G6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A6:G6');
        
        $sheet->getStyle('A8:G8')->getFont()->setBold(true);
        $sheet->getStyle('A8:G8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
        
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A{$lastRow}:G{$lastRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$lastRow}:G{$lastRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFF2CC');
        
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}

class KilnPerformanceSheet implements FromArray, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $kilnPerformance = $this->data['kilnPerformance'] ?? [];
        $kilnRejectionRates = $this->data['kilnRejectionRates'] ?? [];
        $productKilnAnalysis = $this->data['productKilnAnalysis'] ?? [];
        
        $rows = [
            ['FIRIN PERFORMANS ANALİZİ'],
            [''],
            ['Oluşturulma Tarihi:', now()->format('d.m.Y H:i:s')],
            [''],
            ['FIRIN BAZINDA ÜRETİM'],
            [''],
            ['Fırın Adı', 'Barkod Sayısı', 'Toplam Miktar (ton)', 'Ortalama Miktar (ton)', 'Kabul Edilen (ton)', 'Test Sürecinde (ton)', 'Teslimat Sürecinde (ton)', 'Reddedilen (ton)'],
        ];
        
        foreach ($kilnPerformance as $kiln) {
            $rows[] = [
                $kiln->kiln_name,
                number_format($kiln->barcode_count),
                number_format($kiln->total_quantity, 1),
                number_format($kiln->avg_quantity, 1),
                number_format($kiln->accepted_quantity, 1),
                number_format($kiln->testing_quantity ?? 0, 1),
                number_format($kiln->delivery_quantity ?? 0, 1),
                number_format($kiln->rejected_quantity, 1),
            ];
        }
        
        $rows[] = ['', '', '', '', '', '', '', ''];
        $rows[] = ['FIRIN RED ORANLARI'];
        $rows[] = ['Fırın Adı', 'Toplam Barkod', 'Reddedilen (ton)', 'Red Oranı (%)', 'Durum'];
        
        foreach ($kilnRejectionRates as $kiln) {
            $status = match(true) {
                $kiln->rejection_rate <= 5 => 'Düşük',
                $kiln->rejection_rate <= 15 => 'Orta',
                default => 'Yüksek'
            };
            
            $rows[] = [
                $kiln->kiln_name,
                number_format($kiln->total_barcodes),
                number_format($kiln->rejected_quantity, 1),
                $kiln->rejection_rate,
                $status,
            ];
        }
        
        $rows[] = ['', '', '', '', ''];
        $rows[] = ['ÜRÜN ÖZELİNDE FIRIN KAPASİTE ANALİZİ'];
        $rows[] = ['Ürün', 'Fırın', 'Barkod Sayısı', 'Toplam Miktar (ton)', 'Kabul Edilen (ton)', 'Test Sürecinde (ton)', 'Teslimat Sürecinde (ton)', 'Reddedilen (ton)', 'Kabul Oranı (%)'];
        
        foreach ($productKilnAnalysis as $analysis) {
            $rows[] = [
                $analysis->stock_name . ' (' . $analysis->stock_code . ')',
                $analysis->kiln_name,
                number_format($analysis->barcode_count),
                number_format($analysis->total_quantity, 1),
                number_format($analysis->accepted_quantity, 1),
                number_format($analysis->testing_quantity ?? 0, 1),
                number_format($analysis->delivery_quantity ?? 0, 1),
                number_format($analysis->rejected_quantity, 1),
                $analysis->acceptance_rate,
            ];
        }
        
        return $rows;
    }

    public function title(): string
    {
        return 'Fırın Performansı';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A1:H1');
        
        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A5:H5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A5:H5');
        
        $sheet->getStyle('A7:H7')->getFont()->setBold(true);
        $sheet->getStyle('A7:H7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
        
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}

class RejectionReasonsSheet implements FromArray, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rejectionReasons = $this->data['rejectionReasonsAnalysis'] ?? [];
        
        $rows = [
            ['RED SEBEPLERİ ANALİZİ'],
            [''],
            ['Oluşturulma Tarihi:', now()->format('d.m.Y H:i:s')],
            [''],
            ['GENEL İSTATİSTİKLER'],
            [''],
            ['Metrik', 'Değer', 'Birim'],
            ['Bugün Reddedilen', number_format($rejectionReasons['total_rejected_today'] ?? 0), 'adet'],
            ['Red KG (Bugün)', number_format($rejectionReasons['total_rejected_kg_today'] ?? 0, 1), 'KG'],
            ['Farklı Red Sebebi', count($rejectionReasons['daily_reasons'] ?? []), 'adet'],
            ['En Çok Tekrarlanan', count($rejectionReasons['daily_reasons']) > 0 ? (is_array($rejectionReasons['daily_reasons']) ? array_keys($rejectionReasons['daily_reasons'])[0] : $rejectionReasons['daily_reasons']->keys()->first()) : 'Yok', ''],
            [''],
            ['RED SEBEPLERİ DAĞILIMI'],
            [''],
            ['Red Sebebi', 'Barkod Sayısı', 'Toplam KG', 'Yüzde (%)'],
        ];
        
        if (isset($rejectionReasons['daily_reasons']) && count($rejectionReasons['daily_reasons']) > 0) {
            $totalRejectedCount = $rejectionReasons['total_rejected_today'];
            
            foreach ($rejectionReasons['daily_reasons'] as $reasonName => $data) {
                $percentage = $totalRejectedCount > 0 ? ($data['count'] / $totalRejectedCount) * 100 : 0;
                
                $rows[] = [
                    $reasonName,
                    number_format($data['count']),
                    number_format($data['total_kg'], 1),
                    number_format($percentage, 1),
                ];
            }
        }
        
        return $rows;
    }

    public function title(): string
    {
        return 'Red Sebepleri';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A1:D1');
        
        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A5:D5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A5:D5');
        
        $sheet->getStyle('A7:D7')->getFont()->setBold(true);
        $sheet->getStyle('A7:D7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
        
        $sheet->getStyle('A14:D14')->getFont()->setBold(true);
        $sheet->getStyle('A14:D14')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
        
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}

class StockAgeSheet implements FromArray, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $stockAgeAnalysis = $this->data['stockAgeAnalysis'] ?? [];
        
        $rows = [
            ['STOK YAŞI ANALİZİ'],
            [''],
            ['Oluşturulma Tarihi:', now()->format('d.m.Y H:i:s')],
            [''],
            ['ÖZET KARTLAR'],
            [''],
            ['Kategori', 'Barkod Sayısı', 'Miktar (ton)', 'Açıklama'],
            ['Kritik Stok (30+ gün)', number_format($stockAgeAnalysis['summary']['critical_count'] ?? 0), number_format($stockAgeAnalysis['summary']['critical_quantity'] ?? 0, 1), 'Acil müdahale gerekli'],
            ['Uyarı Stok (15-29 gün)', number_format($stockAgeAnalysis['summary']['warning_count'] ?? 0), number_format($stockAgeAnalysis['summary']['warning_quantity'] ?? 0, 1), 'Dikkat gerektiriyor'],
            ['Dikkat Stok (7-14 gün)', number_format($stockAgeAnalysis['summary']['attention_count'] ?? 0), number_format($stockAgeAnalysis['summary']['attention_quantity'] ?? 0, 1), 'Yakından takip gerekli'],
            ['Normal Stok (0-6 gün)', number_format($stockAgeAnalysis['summary']['normal_count'] ?? 0), number_format($stockAgeAnalysis['summary']['normal_quantity'] ?? 0, 1), 'Güncel ve iyi yönetiliyor'],
            [''],
            ['DURUM BAZINDA STOK YAŞI ANALİZİ'],
            [''],
            ['Durum', 'Barkod Sayısı', 'Toplam Miktar (KG)', 'Ortalama Yaş (gün)', 'En Eski (gün)'],
        ];
        
        if (isset($stockAgeAnalysis['status_analysis'])) {
            foreach ($stockAgeAnalysis['status_analysis'] as $status => $data) {
                $statusDisplay = match($status) {
                    'waiting' => 'Beklemede',
                    'control_repeat' => 'Kontrol Tekrarı',
                    'pre_approved' => 'Ön Onaylı',
                    'shipment_approved' => 'Sevk Onaylı',
                    'rejected' => 'Reddedildi',
                    'customer_transfer' => 'Müşteri Transfer',
                    'delivered' => 'Teslim Edildi',
                    default => $status
                };
                
                $rows[] = [
                    $statusDisplay,
                    number_format($data['count']),
                    number_format($data['quantity'], 1),
                    $data['avg_age'],
                    $data['oldest_age'],
                ];
            }
        }
        
        return $rows;
    }

    public function title(): string
    {
        return 'Stok Yaşı Analizi';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A1:D1');
        
        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A5:D5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A5:D5');
        
        $sheet->getStyle('A7:D7')->getFont()->setBold(true);
        $sheet->getStyle('A7:D7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
        
        $sheet->getStyle('A13:D13')->getFont()->setBold(true);
        $sheet->getStyle('A13:D13')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
        
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}

class MonthlyComparisonSheet implements FromArray, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $monthlyComparison = $this->data['monthlyComparison'] ?? [];
        
        $rows = [
            ['AYLIK KARŞILAŞTIRMA ANALİZİ'],
            [''],
            ['Oluşturulma Tarihi:', now()->format('d.m.Y H:i:s')],
            [''],
            ['BU AY İSTATİSTİKLERİ'],
            [''],
            ['Metrik', 'Değer', 'Birim'],
            ['Toplam Barkod', number_format($monthlyComparison['current_month']['total_barcodes'] ?? 0), 'adet'],
            ['Toplam Miktar', number_format($monthlyComparison['current_month']['total_quantity'] ?? 0, 1), 'ton'],
            [''],
            ['GEÇEN AY İSTATİSTİKLERİ'],
            [''],
            ['Metrik', 'Değer', 'Birim'],
            ['Toplam Barkod', number_format($monthlyComparison['previous_month']['total_barcodes'] ?? 0), 'adet'],
            ['Toplam Miktar', number_format($monthlyComparison['previous_month']['total_quantity'] ?? 0, 1), 'ton'],
            [''],
            ['DEĞİŞİM ANALİZİ'],
            [''],
            ['Metrik', 'Değişim Oranı (%)', 'Trend'],
            ['Barkod Değişimi', $monthlyComparison['change_percentage']['total_barcodes'] ?? 0, $this->getTrendText($monthlyComparison['change_percentage']['total_barcodes'] ?? 0)],
            ['Miktar Değişimi', $monthlyComparison['change_percentage']['total_quantity'] ?? 0, $this->getTrendText($monthlyComparison['change_percentage']['total_quantity'] ?? 0)],
        ];
        
        return $rows;
    }

    private function getTrendText($percentage)
    {
        if ($percentage == 100) return 'Yeni üretim başladı';
        if ($percentage == -100) return 'Üretim durdu';
        if ($percentage >= 20) return 'Güçlü artış';
        if ($percentage >= 10) return 'Orta artış';
        if ($percentage >= 0) return 'Hafif artış';
        if ($percentage >= -10) return 'Hafif düşüş';
        if ($percentage >= -20) return 'Orta düşüş';
        return 'Önemli düşüş';
    }

    public function title(): string
    {
        return 'Aylık Karşılaştırma';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A1:C1');
        
        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A5:C5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A5:C5');
        
        $sheet->getStyle('A7:C7')->getFont()->setBold(true);
        $sheet->getStyle('A7:C7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
        
        $sheet->getStyle('A11:C11')->getFont()->setBold(true);
        $sheet->getStyle('A11:C11')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
        
        $sheet->getStyle('A17:C17')->getFont()->setBold(true);
        $sheet->getStyle('A17:C17')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
        
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}

class OEESheet implements FromArray, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $aiInsights = $this->data['aiInsights'] ?? [];
        $productionEfficiency = $aiInsights['production_efficiency'] ?? [];
        
        $rows = [
            ['ÜRETİM VERİMLİLİĞİ ANALİZİ (OEE)'],
            [''],
            ['Oluşturulma Tarihi:', now()->format('d.m.Y H:i:s')],
            [''],
            ['OEE METRİKLERİ'],
            [''],
            ['Metrik', 'Değer', 'Seviye', 'Açıklama'],
            ['Genel Verimlilik', '%' . ($productionEfficiency['oee_score'] ?? 0), ucfirst($productionEfficiency['level'] ?? 'average'), 'OEE skoru'],
            ['Erişilebilirlik', '%' . ($productionEfficiency['availability'] ?? 0), '', 'Makine çalışma süresi'],
            ['Performans', '%' . ($productionEfficiency['performance'] ?? 0), '', 'Üretim hızı'],
            ['Kalite', '%' . ($productionEfficiency['quality_rate'] ?? 0), '', 'Kabul oranı'],
            [''],
            ['VERİMLİLİK DETAYLARI'],
            [''],
            ['Metrik', 'Değer', 'Birim'],
            ['Toplam Barkod', number_format($productionEfficiency['total_barcodes'] ?? 0), 'adet'],
            ['Aktif Barkod', number_format($productionEfficiency['active_barcodes'] ?? 0), 'adet'],
            ['Reddedilen', number_format($productionEfficiency['rejected_barcodes'] ?? 0), 'adet'],
            ['Birleştirilen', number_format($productionEfficiency['merged_barcodes'] ?? 0), 'adet'],
            ['Ortalama Miktar', number_format($productionEfficiency['avg_quantity'] ?? 0, 1), 'KG'],
        ];
        
        return $rows;
    }

    public function title(): string
    {
        return 'OEE Analizi';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A1:D1');
        
        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A5:D5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A5:D5');
        
        $sheet->getStyle('A7:D7')->getFont()->setBold(true);
        $sheet->getStyle('A7:D7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
        
        $sheet->getStyle('A13:D13')->getFont()->setBold(true);
        $sheet->getStyle('A13:D13')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
        
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}

class AIMLSheet implements FromArray, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $aiInsights = $this->data['aiInsights'] ?? [];
        
        $rows = [
            ['AI/ML İÇGÖRÜLER & TAHMİN ANALİZİ'],
            [''],
            ['Oluşturulma Tarihi:', now()->format('d.m.Y H:i:s')],
            [''],
            ['ÜRETİM TAHMİNİ'],
            [''],
            ['Metrik', 'Değer', 'Birim'],
            ['Beklenen Üretim (7 gün)', number_format($aiInsights['production_forecast'] ?? 0, 1), 'ton'],
            ['Güven Seviyesi', ($aiInsights['confidence_level'] ?? 0) . '%', ''],
            ['Trend Yönü', ucfirst($aiInsights['trend_direction'] ?? 'up'), ''],
            ['Trend Yüzdesi', '%' . ($aiInsights['trend_percentage'] ?? 0), ''],
            [''],
            ['KALİTE RİSK DEĞERLENDİRMESİ'],
            [''],
            ['Metrik', 'Değer', 'Açıklama'],
            ['Risk Seviyesi', ucfirst($aiInsights['quality_risk_level'] ?? 'low'), ''],
            ['Beklenen Red Oranı', '%' . ($aiInsights['expected_rejection_rate'] ?? 0), ''],
            [''],
            ['MODEL DURUMU'],
            [''],
            ['Model', 'Durum', 'Doğruluk (%)', 'Açıklama'],
            ['Üretim Modeli', ucfirst($aiInsights['model_status']['production'] ?? 'active'), $aiInsights['model_status']['accuracy']['production'] ?? 0, 'Üretim tahminleri'],
            ['Kalite Modeli', ucfirst($aiInsights['model_status']['quality'] ?? 'active'), $aiInsights['model_status']['accuracy']['quality'] ?? 0, 'Kalite tahminleri'],
            ['Anomali Modeli', ucfirst($aiInsights['model_status']['anomaly'] ?? 'active'), $aiInsights['model_status']['accuracy']['anomaly'] ?? 0, 'Anomali tespiti'],
        ];
        
        return $rows;
    }

    public function title(): string
    {
        return 'AI/ML İçgörüler';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A1:C1');
        
        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A5:C5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A5:C5');
        
        $sheet->getStyle('A7:C7')->getFont()->setBold(true);
        $sheet->getStyle('A7:C7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
        
        $sheet->getStyle('A13:C13')->getFont()->setBold(true);
        $sheet->getStyle('A13:C13')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
        
        $sheet->getStyle('A19:C19')->getFont()->setBold(true);
        $sheet->getStyle('A19:C19')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
        
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}
