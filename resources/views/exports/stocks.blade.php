<table>
    <thead>
    <tr>
        <th>Stok Kodu</th>
        <th>Stok Adı</th>
        <th>{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_WAITING] }}</th>
        <th>{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_PRE_APPROVED] }}</th>
        <th>{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_REJECTED] }}</th>
                <th>{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_SHIPMENT_APPROVED] }}</th>
        <th>{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_CUSTOMER_TRANSFER] }}</th>
        <th>{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_DELIVERED] }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($stocks as $stock)
        <tr>
            <td>{{ $stock->code }}</td>
            <td>{{ $stock->name }}</td>
            <td>{{ $stock->waiting ? $stock->waiting . " KG" : '-' }}</td>
            <td>{{ $stock->accepted ? $stock->accepted . " KG" : '-' }}</td>
            <td>{{ $stock->rejected ? $stock->rejected . " KG" : '-' }}</td>
            <td>{{ $stock->in_warehouse ? $stock->in_warehouse . " KG" : '-' }}</td>
            <td>{{ $stock->on_delivery_in_warehouse ? $stock->on_delivery_in_warehouse . " KG" : '-' }}</td>
            <td>{{ $stock->on_delivery ? $stock->on_delivery . " KG" : '-' }}</td>
            <td>{{ $stock->delivered ? $stock->delivered . " KG" : '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
