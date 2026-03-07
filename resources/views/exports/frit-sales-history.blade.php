<table>
    <thead>
        <tr>
            <th colspan="8" style="font-weight: bold; font-size: 14px; text-align: center;">FRİT SATIŞ VE SEVKİYAT GEÇMİŞİ RAPORU</th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center;">Rapor Tarih Aralığı: {{ $startDate }} - {{ $endDate }}</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">ID</th>
            <th style="font-weight: bold;">Şarj No</th>
            <th style="font-weight: bold;">Ürün Adı</th>
            <th style="font-weight: bold;">Firma</th>
            <th style="font-weight: bold;">Miktar (KG)</th>
            <th style="font-weight: bold;">Personel</th>
            <th style="font-weight: bold;">Durum</th>
            <th style="font-weight: bold;">İşlem Tarihi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $barcode)
            <tr>
                <td>{{ $barcode->id }}</td>
                <td>{{ $barcode->load_number }}</td>
                <td>{{ $barcode->stock->name ?? '-' }}</td>
                <td>{{ $barcode->company->name ?? '-' }}</td>
                <td>{{ $barcode->quantity->quantity ?? 0 }}</td>
                <td>{{ $barcode->deliveredBy->name ?? '-' }}</td>
                <td>{{ \App\Models\Barcode::getStatusName($barcode->status) }}</td>
                <td>{{ $barcode->delivered_at ? $barcode->delivered_at->format('d.m.Y H:i') : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
