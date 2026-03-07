<table>
    <thead>
        <tr>
            <th colspan="5" style="font-weight: bold; text-align: center;">GRANİLYA KIRICI PERFORMANS ANALİZİ</th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center;">Tarih Aralığı: {{ $startDate->format('d.m.Y') }} - {{ $endDate->format('d.m.Y') }}</th>
        </tr>
        <tr>
            <th>Kırıcı Adı</th>
            <th>Toplam İşlem</th>
            <th>Kabul</th>
            <th>Red</th>
            <th>Kabul Oranı (%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row['name'] }}</td>
                <td>{{ $row['total'] }}</td>
                <td>{{ $row['accepted'] }}</td>
                <td>{{ $row['rejected'] }}</td>
                <td>{{ $row['rate'] }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>
