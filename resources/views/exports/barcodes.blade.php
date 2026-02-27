<table>
    <thead>
    <tr>
        <th>Stok</th>
        <th>Parti No</th>
        <th>Şarj No</th>
        <th>Durum</th>
        <th>Miktar</th>
        <th>Fırın No</th>
        <th>Depo</th>
        <th>Firma</th>
        <th>Not</th>
        <th>Lab</th>
        <th>Lab Tarihi</th> 
        <th>Oluşturan</th>
        <th>Oluşturulma Tarihi</th>
    </tr>
    </thead>
    <tbody>
    @foreach($barcodes as $barcode)
        <tr>
            <td>{{ $barcode->stock->code . ' --- ' . $barcode->stock->name}}</td>
            <td>{{ $barcode->party_number }}</td>
            <td>{{ $barcode->load_number }} {{ !is_null($barcode->rejected_load_number) ? ' + ' . $barcode->rejected_load_number : '' }}</td>
                    <td>{{ \App\Models\Barcode::getStatusName($barcode->status) }}</td>
            <td>{{ $barcode->quantity->quantity . " KG" }}</td>
            <td>{{ $barcode->kiln->name }}</td>
            <td>{{ $barcode->warehouse->name}}</td>
            <td>{{ $barcode->company->name}}</td>
            <td>{{ $barcode->note }}</td>
            <td>{{ $barcode->labBy->name ?? '-' }}</td>
            <td>{{ $barcode->lab_at ? $barcode->lab_at->tz('Europe/Istanbul')->format('d.m.Y H:i:s') : '-' }}</td>
            <td>{{ $barcode->createdBy->name ?? '-' }}</td>
            <td>{{ $barcode->created_at ? $barcode->created_at->tz('Europe/Istanbul')->format('d.m.Y H:i:s') : '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
