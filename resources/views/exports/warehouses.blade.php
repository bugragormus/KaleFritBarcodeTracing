<table>
    <thead>
        <tr>
            <th>Depo Adı</th>
            <th>Adres</th>
            <th>Toplam Stok (KG)</th>
            <th>Depodaki Barkod</th>
            <th>Beklemede (KG)</th>
            <th>Kontrol Tekrarı (KG)</th>
            <th>Ön Onaylı (KG)</th>
            <th>Sevk Onaylı (KG)</th>
            <th>Reddedildi (KG)</th>
            <th>Red Oranı (%)</th>
            <th>Sevk Onayı Oranı (%)</th>
            <th>Son Giriş</th>
            <th>Durum</th>
        </tr>
    </thead>
    <tbody>
        @foreach($warehouses as $warehouse)
            <tr>
                <td>{{ $warehouse->name }}</td>
                <td>{{ $warehouse->address }}</td>
                <td>{{ number_format($warehouse->current_stock_kg, 0) }}</td>
                <td>{{ $warehouse->current_barcodes }}</td>
                <td>{{ number_format($warehouse->waiting_kg ?? 0, 0) }}</td>
                <td>{{ number_format($warehouse->control_repeat_kg ?? 0, 0) }}</td>
                <td>{{ number_format($warehouse->pre_approved_kg ?? 0, 0) }}</td>
                <td>{{ number_format($warehouse->shipment_approved_kg ?? 0, 0) }}</td>
                <td>{{ number_format($warehouse->rejected_kg ?? 0, 0) }}</td>
                <td>{{ $warehouse->rejection_rate }}%</td>
                <td>{{ $warehouse->shipment_approval_rate }}%</td>
                <td>
                    @if($warehouse->last_activity_date)
                        {{ \Carbon\Carbon::parse($warehouse->last_activity_date)->format('d.m.Y') }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($warehouse->is_active)
                        <span style="color: #28a745;">Aktif</span>
                    @else
                        <span style="color: #6c757d;">Pasif</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
