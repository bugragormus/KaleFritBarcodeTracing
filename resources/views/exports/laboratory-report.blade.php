<table>
    <tr>
        <td colspan="4"><strong>Laboratuvar Raporu</strong></td>
    </tr>
    <tr>
        <td>Başlangıç Tarihi</td>
        <td>{{ $startDate->format('d.m.Y H:i') }}</td>
        <td>Bitiş Tarihi</td>
        <td>{{ $endDate->format('d.m.Y H:i') }}</td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th>Özet</th>
            <th>Değer</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Toplam İşlenen</td>
            <td>{{ $summary['total_processed'] }}</td>
        </tr>
        <tr>
            <td>Beklemede</td>
            <td>{{ $summary['waiting'] }}</td>
        </tr>
        <tr>
            <td>Ön Onaylı</td>
            <td>{{ $summary['pre_approved'] }}</td>
        </tr>
        <tr>
            <td>Kontrol Tekrarı</td>
            <td>{{ $summary['control_repeat'] }}</td>
        </tr>
        <tr>
            <td>Sevk Onaylı</td>
            <td>{{ $summary['shipment_approved'] }}</td>
        </tr>
        <tr>
            <td>Reddedildi</td>
            <td>{{ $summary['rejected'] }}</td>
        </tr>
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th>Red Sebepleri (Genel)</th>
            <th>Adet</th>
            <th>Toplam KG</th>
        </tr>
    </thead>
    <tbody>
        @foreach($generalRejectionStats as $reasonName => $stats)
            <tr>
                <td>{{ $reasonName }}</td>
                <td>{{ $stats['count'] }}</td>
                <td>{{ number_format($stats['total_kg'], 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th colspan="3">Stok Bazında Red Özeti</th>
        </tr>
        <tr>
            <th>Stok</th>
            <th>Toplam Red (Adet)</th>
            <th>Toplam Red (KG)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rejectionReasonsAnalysis as $analysis)
            <tr>
                <td>{{ $analysis['stock']->name }} ({{ $analysis['stock']->code }})</td>
                <td>{{ $analysis['total_rejected'] }}</td>
                <td>{{ number_format($analysis['total_rejected_kg'], 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
