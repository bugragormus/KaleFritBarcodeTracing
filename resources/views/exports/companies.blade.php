<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Firma Raporu</title>
</head>
<body>
    @if($startDate || $endDate || $period)
    <div style="margin-bottom: 20px; padding: 10px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px;">
        <h3 style="margin: 0 0 10px 0; color: #495057;">Filtre Bilgileri</h3>
        @if($period)
            @php
                $periodNames = [
                    'monthly' => 'Aylık',
                    'quarterly' => '3 Aylık',
                    'yearly' => 'Yıllık',
                    'all' => 'Tüm Zamanlar'
                ];
            @endphp
            <p style="margin: 5px 0;"><strong>Periyot:</strong> {{ $periodNames[$period] ?? 'Son 7 Gün' }}</p>
        @endif
        @if($startDate && $endDate)
            <p style="margin: 5px 0;"><strong>Tarih Aralığı:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }}</p>
        @endif
        <p style="margin: 5px 0;"><strong>Rapor Tarihi:</strong> {{ \Carbon\Carbon::now()->format('d.m.Y H:i') }}</p>
    </div>
    @endif
    
    <table>
        <thead>
            <tr>
                <th>Firma Adı</th>
                <th>Adres</th>
                <th>Toplam Sipariş</th>
                <th>Toplam Alım (KG)</th>
                <th>Müşteri Transfer (KG)</th>
                <th>Teslim Edildi (KG)</th>
                <th>Teslim Oranı (%)</th>
                <th>Ortalama Sipariş (KG)</th>
                <th>Son Alım Tarihi</th>
                <th>Durum</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $company)
            <tr>
                <td>{{ $company->name }}</td>
                <td>{{ $company->address }}</td>
                <td>{{ $company->barcodes_count }}</td>
                <td>{{ number_format($company->total_purchase, 0) }}</td>
                <td>{{ number_format($company->customer_transfer_kg ?? 0, 0) }}</td>
                <td>{{ number_format($company->delivered_kg ?? 0, 0) }}</td>
                <td>{{ $company->delivery_rate }}%</td>
                <td>{{ number_format($company->average_order_size, 0) }}</td>
                <td>
                    @if($company->last_purchase_date)
                        {{ \Carbon\Carbon::parse($company->last_purchase_date)->format('d.m.Y') }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($company->delivery_rate >= 90)
                        Mükemmel
                    @elseif($company->delivery_rate >= 75)
                        İyi
                    @elseif($company->delivery_rate >= 50)
                        Orta
                    @else
                        Düşük
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
