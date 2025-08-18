<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExceptionallyApprovedReportExport implements FromArray, WithHeadings, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Ay',
            'Toplam Ürün',
            'İstisnai Onaylı',
            'İstisnai Onay Oranı (%)',
            'Normal Onaylı',
            'Normal Onay Oranı (%)',
            'Reddedilen',
            'Red Oranı (%)',
            'Kabul Edilen',
            'Kabul Oranı (%)',
            'Toplam Üretim (KG)',
            'Toplam Üretim (Ton)',
            'Ortalama Üretim/Barkod (KG)'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '667eea']
                ],
                'font' => ['color' => ['rgb' => 'ffffff']]
            ],
        ];
    }
}
