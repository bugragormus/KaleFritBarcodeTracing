<table>
    <thead>
        <tr>
            <th colspan="5" style="font-weight: bold; font-size: 14px; text-align: center;">GRANİLYA FİRMA SATIN ALMA RAPORU</th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center;">Firma: {{ $company->name }}</th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center;">Rapor Periyodu: {{ $periodInfo['name'] }} ({{ $periodInfo['range'] }})</th>
        </tr>
        <tr>
            <th>Tarih</th>
            <th>Palet No</th>
            <th>Ürün Adı</th>
            <th>Miktar (KG)</th>
            <th>Durum</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row->delivered_at ? $row->delivered_at->format('d.m.Y H:i') : '-' }}</td>
                <td>{{ $row->pallet_number }}</td>
                <td>{{ $row->stock->name ?? '-' }}</td>
                <td>{{ number_format($row->used_quantity, 0, ',', '.') }}</td>
                <td>Teslim Edildi</td>
            </tr>
        @endforeach
    </tbody>
</table>
