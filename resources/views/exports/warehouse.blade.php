<h2>Depo Raporu: {{ $warehouse->name }}</h2>
<p><strong>Adres:</strong> {{ $warehouse->address }}</p>
<p><strong>Rapor Tarihi:</strong> {{ date('d.m.Y H:i') }}</p>

<h3>Stok Bazında Detaylı Durum Dağılımı</h3>
<table>
    <thead>
        <tr>
            <th>Stok Adı</th>
            <th>Stok ID</th>
            <th>Beklemede (KG)</th>
            <th>Kontrol Tekrarı (KG)</th>
            <th>Ön Onaylı (KG)</th>
            <th>Sevk Onaylı (KG)</th>
            <th>Reddedildi (KG)</th>
            <th>Toplam (KG)</th>
        </tr>
    </thead>
    <tbody>
        @if($stockDetails)
            @foreach($stockDetails as $stock)
                @php
                    $totalQuantity = ($stock->waiting_quantity ?? 0) + 
                                   ($stock->control_repeat_quantity ?? 0) + 
                                   ($stock->pre_approved_quantity ?? 0) + 
                                   ($stock->shipment_approved_quantity ?? 0) + 
                                   ($stock->rejected_quantity ?? 0);
                @endphp
                <tr>
                    <td>{{ $stock->name }}</td>
                    <td>{{ $stock->id }}</td>
                    <td>{{ number_format($stock->waiting_quantity ?? 0, 0) }}</td>
                    <td>{{ number_format($stock->control_repeat_quantity ?? 0, 0) }}</td>
                    <td>{{ number_format($stock->pre_approved_quantity ?? 0, 0) }}</td>
                    <td>{{ number_format($stock->shipment_approved_quantity ?? 0, 0) }}</td>
                    <td>{{ number_format($stock->rejected_quantity ?? 0, 0) }}</td>
                    <td><strong>{{ number_format($totalQuantity, 0) }}</strong></td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<h3>Genel Özet</h3>
<table>
    <thead>
        <tr>
            <th>Durum</th>
            <th>Toplam Miktar (KG)</th>
        </tr>
    </thead>
    <tbody>
        @if($stockDetails)
            @php
                $totalWaiting = collect($stockDetails)->sum('waiting_quantity');
                $totalControlRepeat = collect($stockDetails)->sum('control_repeat_quantity');
                $totalPreApproved = collect($stockDetails)->sum('pre_approved_quantity');
                $totalShipmentApproved = collect($stockDetails)->sum('shipment_approved_quantity');
                $totalRejected = collect($stockDetails)->sum('rejected_quantity');
                $grandTotal = $totalWaiting + $totalControlRepeat + $totalPreApproved + $totalShipmentApproved + $totalRejected;
            @endphp
            <tr>
                <td>Beklemede</td>
                <td>{{ number_format($totalWaiting, 0) }}</td>
            </tr>
            <tr>
                <td>Kontrol Tekrarı</td>
                <td>{{ number_format($totalControlRepeat, 0) }}</td>
            </tr>
            <tr>
                <td>Ön Onaylı</td>
                <td>{{ number_format($totalPreApproved, 0) }}</td>
            </tr>
            <tr>
                <td>Sevk Onaylı</td>
                <td>{{ number_format($totalShipmentApproved, 0) }}</td>
            </tr>
            <tr>
                <td>Reddedildi</td>
                <td>{{ number_format($totalRejected, 0) }}</td>
            </tr>
            <tr style="font-weight: bold; background-color: #f8f9fa;">
                <td><strong>TOPLAM</strong></td>
                <td><strong>{{ number_format($grandTotal, 0) }}</strong></td>
            </tr>
        @endif
    </tbody>
</table>

@if(empty($stockDetails))
    <p>Bu depo için henüz stok verisi bulunmuyor.</p>
@endif


