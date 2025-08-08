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
                    <td>{{ \App\Models\Barcode::STATUSES[$barcode->status] ?? 'Bilinmeyen Durum' }}</td>
            <td>{{ $barcode->quantity->quantity . " KG" }}</td>
            <td>{{ $barcode->kiln->name }}</td>
            <td>{{ $barcode->warehouse->name}}</td>
            <td>{{ $barcode->company->name}}</td>
            <td>{{ $barcode->note }}</td>
            <td>{{ $barcode->lab_by }}</td>
            <td>{{ $barcode->lab_at }}</td>
            <td>{{ $barcode->createdBy->name }}</td>
            <td>{{ $barcode->created_at->tz('Europe/Istanbul')->toDateTimeString() }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
