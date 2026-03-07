<table>
    <thead>
        <tr>
            <th colspan="7" style="font-weight: bold; font-size: 14px; text-align: center;">GRANİLYA SATIŞ GEÇMİŞİ RAPORU</th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center;">Tarih Aralığı: {{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }}</th>
        </tr>
        <tr>
            <th>Satış Tarihi</th>
            <th>Palet No</th>
            <th>Ürün Adı</th>
            <th>Miktar (KG)</th>
            <th>Müşteri / Firma</th>
            <th>İşlemi Yapan</th>
            <th>Durum</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->delivered_at ? $sale->delivered_at->format('d.m.Y H:i') : '-' }}</td>
                <td>#{{ $sale->pallet_number }}</td>
                <td>{{ $sale->stock->name ?? '-' }}</td>
                <td>{{ number_format($sale->used_quantity, 0, ',', '.') }}</td>
                <td>{{ $sale->deliveryCompany->name ?? '-' }}</td>
                <td>{{ $sale->user->name ?? '-' }}</td>
                <td>Teslim Edildi</td>
            </tr>
        @endforeach
    </tbody>
</table>
