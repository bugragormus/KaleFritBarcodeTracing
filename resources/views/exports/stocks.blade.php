<table>
    <thead>
    <tr>
        <th>Stok Kodu</th>
        <th>Stok Adı</th>
        <th>{{ \App\Models\Barcode::getStatusName(\App\Models\Barcode::STATUS_WAITING) }}</th>
        <th>{{ \App\Models\Barcode::getStatusName(\App\Models\Barcode::STATUS_CONTROL_REPEAT) }}</th>
        <th>{{ \App\Models\Barcode::getStatusName(\App\Models\Barcode::STATUS_PRE_APPROVED) }}</th>
        <th>{{ \App\Models\Barcode::getStatusName(\App\Models\Barcode::STATUS_REJECTED) }}</th>
        <th>{{ \App\Models\Barcode::getStatusName(\App\Models\Barcode::STATUS_SHIPMENT_APPROVED) }}</th>
        <th>{{ \App\Models\Barcode::getStatusName(\App\Models\Barcode::STATUS_CUSTOMER_TRANSFER) }}</th>
        <th>{{ \App\Models\Barcode::getStatusName(\App\Models\Barcode::STATUS_DELIVERED) }}</th>
        <th>{{ \App\Models\Barcode::getStatusName(\App\Models\Barcode::STATUS_MERGED) }}</th>
        <th>Toplam Üretim</th>
        <th>Kalan Stok</th>
        <th>Red Oranı (%)</th>
        <th>Teslim Oranı (%)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($stocks as $stock)
        <tr>
            <td>{{ $stock->code }}</td>
            <td>{{ $stock->name }}</td>
            <td>{{ number_format($stock->waiting_quantity ?? 0, 0) }} KG</td>
            <td>{{ number_format($stock->control_repeat_quantity ?? 0, 0) }} KG</td>
            <td>{{ number_format($stock->pre_approved_quantity ?? 0, 0) }} KG</td>
            <td>{{ number_format($stock->rejected_quantity ?? 0, 0) }} KG</td>
            <td>{{ number_format($stock->shipment_approved_quantity ?? 0, 0) }} KG</td>
            <td>{{ number_format($stock->customer_transfer_quantity ?? 0, 0) }} KG</td>
            <td>{{ number_format($stock->delivered_quantity ?? 0, 0) }} KG</td>
            <td>{{ number_format($stock->merged_quantity ?? 0, 0) }} KG</td>
            <td>{{ number_format($stock->total_production ?? 0, 0) }} KG</td>
            <td>{{ number_format($stock->remaining_stock ?? 0, 0) }} KG</td>
            <td>%{{ $stock->rejection_rate ?? 0 }}</td>
            <td>%{{ $stock->delivery_rate ?? 0 }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
