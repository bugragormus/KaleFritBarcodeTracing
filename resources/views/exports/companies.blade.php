<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Firma Raporu</title>
</head>
<body>
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
