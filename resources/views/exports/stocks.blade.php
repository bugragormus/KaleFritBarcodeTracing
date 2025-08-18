<table>
    <thead>
    <tr>
        <th>Stok Kodu</th>
        <th>Stok Adı</th>
        <th>{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_WAITING] }}</th>
        <th>{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_CONTROL_REPEAT] }}</th>
        <th>{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_PRE_APPROVED] }}</th>
        <th>{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_REJECTED] }}</th>
        <th>{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_SHIPMENT_APPROVED] }}</th>
        <th>{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_CUSTOMER_TRANSFER] }}</th>
        <th>{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_DELIVERED] }}</th>
        <th>{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_MERGED] }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($stocks as $stock)
        <tr>
            <td>{{ $stock->code }}</td>
            <td>{{ $stock->name }}</td>
            <td>{{ $stock->waiting_quantity ? $stock->waiting_quantity . " KG" : '-' }}</td>
            <td>{{ $stock->control_repeat_quantity ? $stock->control_repeat_quantity . " KG" : '-' }}</td>
            <td>{{ $stock->accepted_quantity ? $stock->accepted_quantity . " KG" : '-' }}</td>
            <td>{{ $stock->rejected_quantity ? $stock->rejected_quantity . " KG" : '-' }}</td>
            <td>{{ $stock->in_warehouse_quantity ? $stock->in_warehouse_quantity . " KG" : '-' }}</td>
            <td>{{ $stock->on_delivery_in_warehouse_quantity ? $stock->on_delivery_in_warehouse_quantity . " KG" : '-' }}</td>
            <td>{{ $stock->delivered_quantity ? $stock->delivered_quantity . " KG" : '-' }}</td>
            <td>{{ $stock->merged_quantity ? $stock->merged_quantity . " KG" : '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
