@extends('layouts.app')

@section('styles')
<style>
    body, .main-content, .modern-company-analysis {
        background: #f8f9fa !important;
    }
    .modern-company-analysis {
        background: #ffffff;
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .page-header-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
    .page-title-modern {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }
    
    .page-title-modern i {
        margin-right: 1rem;
        font-size: 2rem;
    }
    
    .page-subtitle-modern {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    .card-modern {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .card-header-modern {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .card-title-modern {
        font-size: 1.3rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }
    
    .card-title-modern i {
        margin-right: 0.5rem;
        color: #667eea;
    }
    
    .card-body-modern {
        padding: 2rem;
    }
    
    .btn-modern {
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .btn-primary-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
            .btn-warning-modern {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 8px;
        }
        
        .quick-filters {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 1rem;
        }
        
        .quick-filters .btn-modern {
            margin-bottom: 0.25rem;
        }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #e9ecef;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .chart-container {
        height: 400px;
        margin-bottom: 2rem;
    }
    
    .table-modern {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }
    
    .table-modern thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 1rem;
        font-weight: 600;
        text-align: center;
        font-size: 0.875rem;
    }
    
    .table-modern tbody td {
        padding: 1rem;
        border: none;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
        font-size: 0.875rem;
    }
    
    .table-modern tbody tr:hover {
        background: #f8f9fa;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-customer-transfer { background: #6f42c1; color: white; }
    .status-delivered { background: #20c997; color: white; }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .page-title-modern {
            font-size: 2rem;
        }
    }
</style>
@endsection

@section('content')
<div class="modern-company-analysis">
    <div class="container-fluid">
        <!-- Modern Page Header -->
        <div class="page-header-modern">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title-modern">
                        <i class="fas fa-chart-line"></i> {{ $company->name }} - Detaylı Analiz
                    </h1>
                    <p class="page-subtitle-modern">Firma alım analizi, trendler ve performans raporları</p>
                </div>
                <div class="col-md-4 text-right">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('company.download.report', ['firma' => $company->id]) }}" class="btn-modern btn-warning-modern mr-3">
                            <i class="fas fa-file-excel"></i> Excel İndir
                        </a>
                        <a href="{{ route('company.index') }}" class="btn-modern btn-primary-modern">
                            <i class="fas fa-arrow-left"></i> Geri Dön
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarih Filtreleme -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-calendar-alt"></i> Tarih Filtreleme
                </h3>
                <p class="card-subtitle-modern">Belirli tarih aralığındaki firma performansını analiz edin</p>
            </div>
            <div class="card-body-modern">
                <!-- Hızlı Filtre Butonları -->
                <div class="quick-filters mb-3">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('company.analysis', ['firma' => $company->id]) }}" class="btn-modern btn-sm {{ !request('period') ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                            <i class="fas fa-calendar-day"></i> Günlük
                        </a>
                        <a href="{{ route('company.analysis', ['firma' => $company->id, 'period' => 'monthly']) }}" class="btn-modern btn-sm {{ request('period') == 'monthly' ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                            <i class="fas fa-calendar-alt"></i> Aylık
                        </a>
                        <a href="{{ route('company.analysis', ['firma' => $company->id, 'period' => 'quarterly']) }}" class="btn-modern btn-sm {{ request('period') == 'quarterly' ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                            <i class="fas fa-calendar-week"></i> 3 Aylık
                        </a>
                        <a href="{{ route('company.analysis', ['firma' => $company->id, 'period' => 'yearly']) }}" class="btn-modern btn-sm {{ request('period') == 'yearly' ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                            <i class="fas fa-calendar"></i> Yıllık
                        </a>
                        <a href="{{ route('company.analysis', ['firma' => $company->id, 'period' => 'all']) }}" class="btn-modern btn-sm {{ request('period') == 'all' ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                            <i class="fas fa-infinity"></i> Tüm Zamanlar
                        </a>
                    </div>
                </div>

                <form method="GET" action="{{ route('company.analysis', ['firma' => $company->id]) }}" class="row align-items-end">
                    <div class="col-md-4">
                        <label class="form-label-modern">Başlangıç Tarihi</label>
                        <input type="date" name="start_date" class="form-control-modern" 
                               value="{{ request('start_date') }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-modern">Bitiş Tarihi</label>
                        <input type="date" name="end_date" class="form-control-modern" 
                               value="{{ request('end_date') }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn-modern btn-primary-modern w-100">
                            <i class="fas fa-filter"></i> Filtrele
                        </button>
                    </div>
                </form>
                @if(request('start_date') || request('end_date') || request('period'))
                    <div class="mt-3">
                        <a href="{{ route('company.analysis', ['firma' => $company->id]) }}" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-times"></i> Filtreleri Temizle
                        </a>
                        <span class="ml-3 text-muted">
                            <i class="fas fa-info-circle"></i>
                            @if(request('period'))
                                @php
                                    $periodNames = [
                                        'monthly' => 'Aylık',
                                        'quarterly' => '3 Aylık',
                                        'yearly' => 'Yıllık',
                                        'all' => 'Tüm Zamanlar'
                                    ];
                                @endphp
                                {{ $periodNames[request('period')] ?? 'Günlük' }} görünüm
                            @endif
                            @if(request('start_date') && request('end_date'))
                                - {{ request('start_date') }} - {{ request('end_date') }} tarihleri arası
                            @endif
                            için filtrelenmiş sonuçlar
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Genel İstatistikler -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-chart-bar"></i> Genel İstatistikler
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($company->total_purchase, 0) }}</div>
                        <div class="stat-label">Toplam Satış (Ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $company->total_barcodes }}</div>
                        <div class="stat-label">Toplam Sipariş</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">
                            @php
                                $deliveryRate = $company->total_barcodes > 0 ? 
                                    round(($statusDistribution[\App\Models\Barcode::STATUS_DELIVERED] ?? 0) / $company->total_barcodes * 100, 2) : 0;
                            @endphp
                            {{ $deliveryRate }}%
                        </div>
                        <div class="stat-label">Teslim Oranı</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">
                            @if($company->barcodes->count() > 0)
                                {{ \Carbon\Carbon::parse($company->barcodes->first()->created_at)->format('d.m.Y') }}
                            @else
                                -
                            @endif
                        </div>
                        <div class="stat-label">İlk Satış Tarihi</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">
                            @if($company->barcodes->count() > 0)
                                {{ \Carbon\Carbon::parse($company->barcodes->last()->created_at)->format('d.m.Y') }}
                            @else
                                -
                            @endif
                        </div>
                        <div class="stat-label">Son Satış Tarihi</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">
                            @if($company->total_barcodes > 0)
                                {{ number_format($company->total_purchase / $company->total_barcodes, 0) }}
                            @else
                                0
                            @endif
                        </div>
                        <div class="stat-label">Ortalama Sipariş (Ton)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Durum Dağılımı -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-tasks"></i> Durum Bazında Dağılım
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Durum</th>
                                <th>Sipariş Sayısı</th>
                                <th>Oran</th>
                                <th>Toplam Miktar (Ton)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach([\App\Models\Barcode::STATUS_CUSTOMER_TRANSFER, \App\Models\Barcode::STATUS_DELIVERED] as $statusId)
                            <tr>
                                <td>
                                    <span class="status-badge 
                                        @if($statusId == \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER) status-customer-transfer
                                        @elseif($statusId == \App\Models\Barcode::STATUS_DELIVERED) status-delivered
                                        @endif">
                                        {{ \App\Models\Barcode::STATUSES[$statusId] }}
                                    </span>
                                </td>
                                <td>{{ $statusDistribution[$statusId] ?? 0 }}</td>
                                <td>
                                    @php
                                        $percentage = $company->total_barcodes > 0 ? 
                                            round(($statusDistribution[$statusId] ?? 0) / $company->total_barcodes * 100, 2) : 0;
                                    @endphp
                                    {{ $percentage }}%
                                </td>
                                <td>
                                    @php
                                        $totalQuantity = $company->barcodes->where('status', $statusId)->sum('quantity_id');
                                    @endphp
                                    {{ number_format($totalQuantity, 0) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- En Çok Alınan Stoklar -->
        @if($topStocks->count() > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-boxes"></i> En Çok Alınan Stoklar
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Stok Adı</th>
                                <th>Toplam Miktar (Ton)</th>
                                <th>Sipariş Sayısı</th>
                                <th>Ortalama Miktar (Ton)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topStocks as $stock)
                            <tr>
                                <td>{{ $stock->stock ? $stock->stock->name : 'Bilinmeyen Stok' }}</td>
                                <td>{{ number_format($stock->total_quantity, 0) }}</td>
                                <td>{{ $stock->total_barcodes }}</td>
                                <td>{{ number_format($stock->total_quantity / $stock->total_barcodes, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- En Çok Çalışılan Fırınlar -->
        @if($topKilns->count() > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-fire"></i> En Çok Çalışılan Fırınlar
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Fırın Adı</th>
                                <th>Toplam Miktar (Ton)</th>
                                <th>Sipariş Sayısı</th>
                                <th>Ortalama Miktar (Ton)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topKilns as $kiln)
                            <tr>
                                <td>{{ $kiln->kiln ? $kiln->kiln->name : 'Bilinmeyen Fırın' }}</td>
                                <td>{{ number_format($kiln->total_quantity, 0) }}</td>
                                <td>{{ $kiln->total_barcodes }}</td>
                                <td>{{ number_format($kiln->total_quantity / $kiln->total_barcodes, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Aylık Alım Trendi -->
        @if($monthlyPurchase->count() > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-chart-line"></i> Son 12 Aylık Alım Trendi
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="chart-container">
                    <canvas id="purchaseChart"></canvas>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Aylık alım grafiği
    @if($monthlyPurchase->count() > 0)
    const purchaseCtx = document.getElementById('purchaseChart').getContext('2d');
    new Chart(purchaseCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($monthlyPurchase as $data)
                    '{{ \Carbon\Carbon::createFromDate($data->year, $data->month, 1)->format('M Y') }}',
                @endforeach
            ],
            datasets: [{
                label: 'Alım Miktarı (Ton)',
                data: [
                    @foreach($monthlyPurchase as $data)
                        {{ $data->total_quantity }},
                    @endforeach
                ],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    @endif
</script>
@endsection
