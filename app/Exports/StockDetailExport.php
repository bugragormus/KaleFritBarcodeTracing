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

class StockDetailExport implements WithMultipleSheets
{
    protected $stock;
    protected $stockDetails;
    protected $productionData;
    protected $barcodesByStatus;
    protected $productionByKiln;
    protected $salesByCompany;
    protected $monthlyTrend;

    public function __construct($stock, $stockDetails, $productionData, $barcodesByStatus, $productionByKiln, $salesByCompany, $monthlyTrend)
    {
        $this->stock = $stock;
        $this->stockDetails = $stockDetails;
        $this->productionData = $productionData;
        $this->barcodesByStatus = $barcodesByStatus;
        $this->productionByKiln = $productionByKiln;
        $this->salesByCompany = $salesByCompany;
        $this->monthlyTrend = $monthlyTrend;
    }

    public function sheets(): array
    {
        return [
            'Genel Bilgiler' => new GeneralInfoSheet($this->stock, $this->stockDetails),
            'Durum Dağılımı' => new StatusDistributionSheet($this->barcodesByStatus),
            'Fırın Üretimi' => new KilnProductionSheet($this->productionByKiln),
            'Müşteri Satışları' => new CustomerSalesSheet($this->salesByCompany),
            'Aylık Trend' => new MonthlyTrendSheet($this->monthlyTrend),
            'Günlük Üretim' => new DailyProductionSheet($this->productionData),
        ];
    }
}

class GeneralInfoSheet implements FromArray, WithTitle, WithStyles
{
    protected $stock;
    protected $stockDetails;

    public function __construct($stock, $stockDetails)
    {
        $this->stock = $stock;
        $this->stockDetails = $stockDetails;
    }

    public function array(): array
    {
        $data = [
            ['STOK DETAY RAPORU'],
            [''],
            ['Stok Adı:', $this->stock->name],
            ['Stok Kodu:', $this->stock->code],
            [''],
            ['GENEL İSTATİSTİKLER'],
            [''],
            ['Toplam Miktar (KG):', $this->stockDetails ? number_format($this->stockDetails->total_quantity, 0) : '0'],
            ['Toplam Barkod:', $this->stockDetails ? $this->stockDetails->total_barcodes : '0'],
            ['Kullanılan Fırın:', $this->stockDetails ? $this->stockDetails->total_kilns : '0'],
            ['Müşteri Sayısı:', $this->stockDetails ? $this->stockDetails->total_companies : '0'],
            ['İlk Üretim:', $this->stockDetails && $this->stockDetails->first_production_date ? \Carbon\Carbon::parse($this->stockDetails->first_production_date)->format('d.m.Y') : '-'],
            ['Son Üretim:', $this->stockDetails && $this->stockDetails->last_production_date ? \Carbon\Carbon::parse($this->stockDetails->last_production_date)->format('d.m.Y') : '-'],
            [''],
            ['DURUM BAZINDA DAĞILIM'],
            [''],
            ['Durum', 'Miktar (KG)'],
        ];

        if ($this->stockDetails) {
            $data[] = ['Beklemede', number_format($this->stockDetails->waiting_quantity, 0)];
            $data[] = ['Ön Onaylı', number_format($this->stockDetails->pre_approved_quantity, 0)];
            $data[] = ['Sevk Onaylı', number_format($this->stockDetails->shipment_approved_quantity, 0)];
            $data[] = ['Müşteri Transfer', number_format($this->stockDetails->customer_transfer_quantity, 0)];
            $data[] = ['Teslim Edildi', number_format($this->stockDetails->delivered_quantity, 0)];
            $data[] = ['Reddedildi', number_format($this->stockDetails->rejected_quantity, 0)];
        }

        return $data;
    }

    public function title(): string
    {
        return 'Genel Bilgiler';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A15')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A17:B17')->getFont()->setBold(true);
        $sheet->getStyle('A17:B17')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E9ECEF');
    }
}

class StatusDistributionSheet implements FromArray, WithTitle, WithStyles
{
    protected $barcodesByStatus;

    public function __construct($barcodesByStatus)
    {
        $this->barcodesByStatus = $barcodesByStatus;
    }

    public function array(): array
    {
        $data = [
            ['DURUM BAZINDA BARKOD DAĞILIMI'],
            [''],
            ['Durum', 'Barkod Sayısı', 'Toplam Miktar (KG)'],
        ];

        if ($this->barcodesByStatus) {
            foreach ($this->barcodesByStatus as $status) {
                $statusName = \App\Models\Barcode::STATUSES[$status->status] ?? 'Bilinmeyen Durum';
                $data[] = [$statusName, $status->count, number_format($status->total_quantity, 0)];
            }
        }

        return $data;
    }

    public function title(): string
    {
        return 'Durum Dağılımı';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A3:C3')->getFont()->setBold(true);
        $sheet->getStyle('A3:C3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E9ECEF');
    }
}

class KilnProductionSheet implements FromArray, WithTitle, WithStyles
{
    protected $productionByKiln;

    public function __construct($productionByKiln)
    {
        $this->productionByKiln = $productionByKiln;
    }

    public function array(): array
    {
        $data = [
            ['FIRIN BAZINDA ÜRETİM'],
            [''],
            ['Fırın Adı', 'Barkod Sayısı', 'Toplam Miktar (KG)', 'Ortalama Miktar (KG)'],
        ];

        if ($this->productionByKiln) {
            foreach ($this->productionByKiln as $kiln) {
                $data[] = [
                    $kiln->kiln_name ?? 'Belirtilmemiş',
                    $kiln->barcode_count,
                    number_format($kiln->total_quantity, 0),
                    number_format($kiln->avg_quantity, 0)
                ];
            }
        }

        return $data;
    }

    public function title(): string
    {
        return 'Fırın Üretimi';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A3:D3')->getFont()->setBold(true);
        $sheet->getStyle('A3:D3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E9ECEF');
    }
}

class CustomerSalesSheet implements FromArray, WithTitle, WithStyles
{
    protected $salesByCompany;

    public function __construct($salesByCompany)
    {
        $this->salesByCompany = $salesByCompany;
    }

    public function array(): array
    {
        $data = [
            ['MÜŞTERİ BAZINDA SATIŞ'],
            [''],
            ['Müşteri Adı', 'Barkod Sayısı', 'Toplam Miktar (KG)', 'İlk Satış', 'Son Satış'],
        ];

        if ($this->salesByCompany) {
            foreach ($this->salesByCompany as $company) {
                $data[] = [
                    $company->company_name ?? 'Belirtilmemiş',
                    $company->barcode_count,
                    number_format($company->total_quantity, 0),
                    $company->first_sale_date ? \Carbon\Carbon::parse($company->first_sale_date)->format('d.m.Y') : '-',
                    $company->last_sale_date ? \Carbon\Carbon::parse($company->last_sale_date)->format('d.m.Y') : '-'
                ];
            }
        }

        return $data;
    }

    public function title(): string
    {
        return 'Müşteri Satışları';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A3:E3')->getFont()->setBold(true);
        $sheet->getStyle('A3:E3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E9ECEF');
    }
}

class MonthlyTrendSheet implements FromArray, WithTitle, WithStyles
{
    protected $monthlyTrend;

    public function __construct($monthlyTrend)
    {
        $this->monthlyTrend = $monthlyTrend;
    }

    public function array(): array
    {
        $data = [
            ['AYLIK ÜRETİM TRENDİ'],
            [''],
            ['Dönem', 'Barkod Sayısı', 'Toplam Miktar (KG)', 'Ortalama Miktar (KG)'],
        ];

        if ($this->monthlyTrend) {
            foreach ($this->monthlyTrend as $trend) {
                $data[] = [
                    $trend->month . '/' . $trend->year,
                    $trend->barcode_count,
                    number_format($trend->total_quantity, 0),
                    $trend->barcode_count > 0 ? number_format($trend->total_quantity / $trend->barcode_count, 0) : 0
                ];
            }
        }

        return $data;
    }

    public function title(): string
    {
        return 'Aylık Trend';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A3:D3')->getFont()->setBold(true);
        $sheet->getStyle('A3:D3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E9ECEF');
    }
}

class DailyProductionSheet implements FromArray, WithTitle, WithStyles
{
    protected $productionData;

    public function __construct($productionData)
    {
        $this->productionData = $productionData;
    }

    public function array(): array
    {
        $data = [
            ['GÜNLÜK ÜRETİM VERİLERİ (Son 30 Gün)'],
            [''],
            ['Tarih', 'Barkod Sayısı', 'Toplam Miktar (KG)'],
        ];

        if ($this->productionData) {
            foreach ($this->productionData as $day) {
                $data[] = [
                    $day->date,
                    $day->barcode_count,
                    number_format($day->total_quantity, 0)
                ];
            }
        }

        return $data;
    }

    public function title(): string
    {
        return 'Günlük Üretim';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A3:C3')->getFont()->setBold(true);
        $sheet->getStyle('A3:C3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E9ECEF');
    }
}
