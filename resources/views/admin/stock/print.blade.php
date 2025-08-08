<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stok Detay Raporu - {{ $stock->name }}</title>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #667eea;
            font-size: 24px;
            margin: 0;
        }
        
        .header p {
            color: #666;
            margin: 5px 0;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            background: #667eea;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-cell {
            display: table-cell;
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        
        .info-label {
            font-weight: bold;
            width: 40%;
            background: #f8f9fa;
        }
        
        .info-value {
            width: 60%;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
        }
        
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background: #667eea;
            color: white;
            font-weight: bold;
        }
        
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .print-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: white;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .print-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 5px;
            font-size: 12px;
        }
        
        .print-btn:hover {
            background: #5a6fd8;
        }
        
        @media print {
            .print-buttons {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="print-buttons no-print">
        <button class="print-btn" onclick="window.print()">Yazdır</button>
        <button class="print-btn" onclick="window.close()">Kapat</button>
    </div>

    <div class="header">
        <h1>STOK DETAY RAPORU</h1>
        <p><strong>Stok Adı:</strong> {{ $stock->name }}</p>
        <p><strong>Stok Kodu:</strong> {{ $stock->code }}</p>
        <p><strong>Rapor Tarihi:</strong> {{ date('d.m.Y H:i') }}</p>
    </div>

    @if($stockDetails)
    <!-- Genel İstatistikler -->
    <div class="section">
        <div class="section-title">GENEL İSTATİSTİKLER</div>
        
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell info-label">Toplam Miktar (KG):</div>
                <div class="info-cell info-value">{{ number_format($stockDetails->total_quantity, 0) }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">Toplam Barkod:</div>
                <div class="info-cell info-value">{{ $stockDetails->total_barcodes }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">Kullanılan Fırın:</div>
                <div class="info-cell info-value">{{ $stockDetails->total_kilns }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">Müşteri Sayısı:</div>
                <div class="info-cell info-value">{{ $stockDetails->total_companies }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">İlk Üretim:</div>
                <div class="info-cell info-value">{{ $stockDetails->first_production_date ? \Carbon\Carbon::parse($stockDetails->first_production_date)->format('d.m.Y') : '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">Son Üretim:</div>
                <div class="info-cell info-value">{{ $stockDetails->last_production_date ? \Carbon\Carbon::parse($stockDetails->last_production_date)->format('d.m.Y') : '-' }}</div>
            </div>
        </div>
    </div>

    <!-- Durum Bazında Dağılım -->
    <div class="section">
        <div class="section-title">DURUM BAZINDA DAĞILIM</div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ number_format($stockDetails->waiting_quantity, 0) }}</div>
                <div class="stat-label">{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_WAITING] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($stockDetails->pre_approved_quantity, 0) }}</div>
                <div class="stat-label">{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_PRE_APPROVED] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($stockDetails->shipment_approved_quantity, 0) }}</div>
                <div class="stat-label">{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_SHIPMENT_APPROVED] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($stockDetails->customer_transfer_quantity, 0) }}</div>
                <div class="stat-label">{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_CUSTOMER_TRANSFER] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($stockDetails->delivered_quantity, 0) }}</div>
                <div class="stat-label">{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_DELIVERED] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($stockDetails->rejected_quantity, 0) }}</div>
                <div class="stat-label">{{ \App\Models\Barcode::STATUSES[\App\Models\Barcode::STATUS_REJECTED] }}</div>
            </div>
        </div>
    </div>

    <!-- Fırın Bazında Üretim -->
    @if($productionByKiln && count($productionByKiln) > 0)
    <div class="section">
        <div class="section-title">FIRIN BAZINDA ÜRETİM</div>
        
        <table>
            <thead>
                <tr>
                    <th>Fırın Adı</th>
                    <th>Barkod Sayısı</th>
                    <th>Toplam Miktar (KG)</th>
                    <th>Ortalama Miktar (KG)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productionByKiln as $kiln)
                <tr>
                    <td>{{ $kiln->kiln_name ?? 'Belirtilmemiş' }}</td>
                    <td>{{ $kiln->barcode_count }}</td>
                    <td>{{ number_format($kiln->total_quantity, 0) }}</td>
                    <td>{{ number_format($kiln->avg_quantity, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Müşteri Bazında Satış -->
    @if($salesByCompany && count($salesByCompany) > 0)
    <div class="section">
        <div class="section-title">MÜŞTERİ BAZINDA SATIŞ</div>
        
        <table>
            <thead>
                <tr>
                    <th>Müşteri Adı</th>
                    <th>Barkod Sayısı</th>
                    <th>Toplam Miktar (KG)</th>
                    <th>İlk Satış</th>
                    <th>Son Satış</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salesByCompany as $company)
                <tr>
                    <td>{{ $company->company_name ?? 'Belirtilmemiş' }}</td>
                    <td>{{ $company->barcode_count }}</td>
                    <td>{{ number_format($company->total_quantity, 0) }}</td>
                    <td>{{ $company->first_sale_date ? \Carbon\Carbon::parse($company->first_sale_date)->format('d.m.Y') : '-' }}</td>
                    <td>{{ $company->last_sale_date ? \Carbon\Carbon::parse($company->last_sale_date)->format('d.m.Y') : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Aylık Üretim Trendi -->
    @if($monthlyTrend && count($monthlyTrend) > 0)
    <div class="section">
        <div class="section-title">AYLIK ÜRETİM TRENDİ</div>
        
        <table>
            <thead>
                <tr>
                    <th>Dönem</th>
                    <th>Barkod Sayısı</th>
                    <th>Toplam Miktar (KG)</th>
                    <th>Ortalama Miktar (KG)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyTrend as $trend)
                <tr>
                    <td>{{ $trend->month }}/{{ $trend->year }}</td>
                    <td>{{ $trend->barcode_count }}</td>
                    <td>{{ number_format($trend->total_quantity, 0) }}</td>
                    <td>{{ $trend->barcode_count > 0 ? number_format($trend->total_quantity / $trend->barcode_count, 0) : 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Günlük Üretim Verileri -->
    @if($productionData && count($productionData) > 0)
    <div class="section">
        <div class="section-title">GÜNLÜK ÜRETİM VERİLERİ (Son 30 Gün)</div>
        
        <table>
            <thead>
                <tr>
                    <th>Tarih</th>
                    <th>Barkod Sayısı</th>
                    <th>Toplam Miktar (KG)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productionData as $day)
                <tr>
                    <td>{{ $day->date }}</td>
                    <td>{{ $day->barcode_count }}</td>
                    <td>{{ number_format($day->total_quantity, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @else
    <div class="section">
        <div class="section-title">BİLGİ</div>
        <p>Bu stok için henüz veri bulunmuyor. Stok detayları görüntülenebilmesi için önce bu stoktan üretim yapılması gerekiyor.</p>
    </div>
    @endif

    <div class="footer">
        <p>Bu rapor {{ date('d.m.Y H:i') }} tarihinde oluşturulmuştur.</p>
    </div>
</body>
</html>
