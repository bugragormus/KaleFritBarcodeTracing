<table>
    <thead>
        <tr>
            <th colspan="6" style="font-weight: bold; text-align: center;">FRİT HAMMADDE AKTARIM RAPORU</th>
        </tr>
        @if($startDate && $endDate)
        <tr>
            <th colspan="6" style="text-align: center;">Tarih Aralığı: {{ $startDate }} - {{ $endDate }}</th>
        </tr>
        @endif
        <tr>
            <th>Hammadde (Frit) Adı</th>
            <th>Şarj No</th>
            <th>Toplam Gelen (KG)</th>
            <th>Üretimde Kullanılan (KG)</th>
            <th>Elek Altı (KG)</th>
            <th>Kalan Stok (KG)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row['stock_name'] }}</td>
                <td>{{ $row['load_number'] }}</td>
                <td>{{ number_format($row['total_quantity'], 2, ',', '.') }}</td>
                <td>{{ number_format($row['used_quantity'], 2, ',', '.') }}</td>
                <td>{{ number_format($row['sieve_residue_quantity'], 2, ',', '.') }}</td>
                <td>{{ number_format($row['remaining_quantity'], 2, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
