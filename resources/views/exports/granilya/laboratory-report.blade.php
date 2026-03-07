<table>
    <thead>
        <tr>
            <th colspan="9" style="font-weight: bold; text-align: center;">GRANİLYA LABORATUVAR RAPORU</th>
        </tr>
        <tr>
            <th colspan="9" style="text-align: center;">Tarih Aralığı: {{ $startDate->format('d.m.Y') }} - {{ $endDate->format('d.m.Y') }}</th>
        </tr>
        <tr>
            <th>Tarih</th>
            <th>Palet No</th>
            <th>Ürün</th>
            <th>Miktar (KG)</th>
            <th>Elek Testi</th>
            <th>Yüzey Testi</th>
            <th>Arge Testi</th>
            <th>Durum</th>
            <th>İşlem Yapan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $p)
            <tr>
                <td>{{ $p->updated_at->format('d.m.Y H:i') }}</td>
                <td>{{ $p->pallet_number }}</td>
                <td>{{ $p->stock->name ?? '-' }}</td>
                <td>{{ number_format($p->used_quantity, 0, ',', '.') }}</td>
                <td>{{ $p->sieve_test_result }}</td>
                <td>{{ $p->surface_test_result }}</td>
                <td>{{ $p->arge_test_result }}</td>
                <td>{{ $p->status_label }}</td>
                <td>{{ $p->user->name ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
