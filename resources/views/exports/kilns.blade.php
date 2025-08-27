<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fırın Raporu</title>
</head>
<body>
<table>
    <thead>
    <tr>
        <th>Fırın Adı</th>
        <th>Toplam Üretim (KG)</th>
        <th>Beklemede (KG)</th>
        <th>Kontrol Tekrarı (KG)</th>
        <th>Ön Onaylı (KG)</th>
        <th>Sevk Onaylı (KG)</th>
        <th>Müşteri Transfer (KG)</th>
        <th>Teslim Edildi (KG)</th>
        <th>Reddedildi (KG)</th>
        <th>Birleştirildi (KG)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($kilns as $kiln)
        <tr>
            <td>{{ $kiln->name }}</td>
            <td>{{ number_format($kiln->total_production ?? 0, 0) }}</td>
            <td>{{ number_format($kiln->waiting_kg ?? 0, 0) }}</td>
            <td>{{ number_format($kiln->control_repeat_kg ?? 0, 0) }}</td>
            <td>{{ number_format($kiln->pre_approved_kg ?? 0, 0) }}</td>
            <td>{{ number_format($kiln->shipment_approved_kg ?? 0, 0) }}</td>
            <td>{{ number_format($kiln->customer_transfer_kg ?? 0, 0) }}</td>
            <td>{{ number_format($kiln->delivered_kg ?? 0, 0) }}</td>
            <td>{{ number_format($kiln->rejected_kg ?? 0, 0) }}</td>
            <td>{{ number_format($kiln->merged_kg ?? 0, 0) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
