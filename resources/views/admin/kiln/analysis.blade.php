@extends('layouts.app')

@section('styles')
<style>
    body, .main-content, .modern-kiln-analysis {
        background: #f8f9fa !important;
    }
    .modern-kiln-analysis {
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
    
    .card-subtitle-modern {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0;
        opacity: 0.8;
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
    
    /* Pagination Styles */
    .pagination {
        margin-bottom: 0;
    }
    
    .page-link {
        color: #667eea;
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        background-color: #fff;
        border-radius: 0.25rem;
        transition: all 0.3s ease;
    }
    
    .page-link:hover {
        color: #fff;
        background-color: #667eea;
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
    }
    
    .page-item.active .page-link {
        background-color: #667eea;
        border-color: #667eea;
        color: #fff;
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
    }
    
    .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
        cursor: not-allowed;
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
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .table-modern tbody tr {
        transition: all 0.3s ease;
    }
    
    /* Loading Animation */
    .loading-overlay {
        position: relative;
        opacity: 0.7;
        pointer-events: none;
    }
    
    .loading-overlay::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }
    
    /* Smooth transitions for pagination */
    .pagination .page-link {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .pagination .page-item.active .page-link {
        transform: scale(1.1);
        box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
    }
    
    /* Table fade effects */
    .table-fade-enter {
        opacity: 0;
        transform: translateY(10px);
    }
    
    .table-fade-enter-active {
        opacity: 1;
        transform: translateY(0);
        transition: all 0.3s ease;
    }
    
    /* Error state styling */
    .error-state {
        background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
        border: 1px solid #feb2b2;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
    }
    
    .error-state i {
        color: #e53e3e;
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-waiting { background: #ffc107; color: #212529; }
    .status-control-repeat { background: #fd7e14; color: white; }
    .status-pre-approved { background: #28a745; color: white; }
    .status-shipment-approved { background: #17a2b8; color: white; }
    .status-customer-transfer { background: #6f42c1; color: white; }
    .status-delivered { background: #20c997; color: white; }
    .status-rejected { background: #dc3545; color: white; }
    .status-merged { background: #6c757d; color: white; }
    
    /* Form Styling */
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    .form-control {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: #ffffff;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
    }
    
    .form-control:hover {
        border-color: #667eea;
    }
    
    /* Progress Bar Styling */
    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .progress-bar {
        transition: width 0.6s ease;
        border-radius: 10px;
    }
    
    /* Badge Styling */
    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }
    
    .badge-light {
        background-color: #f8f9fa;
        color: #6c757d;
        border: 1px solid #e9ecef;
    }
    
    /* Text Color Utilities */
    .text-success { color: #28a745 !important; }
    .text-warning { color: #ffc107 !important; }
    .text-danger { color: #dc3545 !important; }
    .text-info { color: #17a2b8 !important; }
    
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
<div class="modern-kiln-analysis">
    <div class="container-fluid">
        <!-- Modern Page Header -->
        <div class="page-header-modern">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title-modern">
                        <i class="fas fa-chart-line"></i> {{ $kiln->name }} - Detaylı Analiz
                    </h1>
                    <p class="page-subtitle-modern">Fırın performans analizi, üretim trendleri ve kalite kontrol raporları</p>
                </div>
                <div class="col-md-4 text-right">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('kiln.download.report', ['firin' => $kiln->id]) }}" class="btn-modern btn-warning-modern mr-2">
                            <i class="fas fa-file-excel"></i> Detay Rapor
                        </a>
                        <a href="{{ route('kiln.index') }}" class="btn-modern btn-primary-modern">
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
                <p class="card-subtitle-modern">Belirli tarih aralığındaki fırın performansını analiz edin</p>
            </div>
            <div class="card-body-modern">
                <!-- Hızlı Filtre Butonları -->
                <div class="quick-filters mb-3">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('kiln.analysis', ['firin' => $kiln->id]) }}" class="btn-modern btn-sm {{ !request('period') ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                            <i class="fas fa-calendar-day"></i> Günlük
                        </a>
                        <a href="{{ route('kiln.analysis', ['firin' => $kiln->id, 'period' => 'monthly']) }}" class="btn-modern btn-sm {{ request('period') == 'monthly' ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                            <i class="fas fa-calendar-alt"></i> Aylık
                        </a>
                        <a href="{{ route('kiln.analysis', ['firin' => $kiln->id, 'period' => 'quarterly']) }}" class="btn-modern btn-sm {{ request('period') == 'quarterly' ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                            <i class="fas fa-calendar-week"></i> 3 Aylık
                        </a>
                        <a href="{{ route('kiln.analysis', ['firin' => $kiln->id, 'period' => 'yearly']) }}" class="btn-modern btn-sm {{ request('period') == 'yearly' ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                            <i class="fas fa-calendar"></i> Yıllık
                        </a>
                        <a href="{{ route('kiln.analysis', ['firin' => $kiln->id, 'period' => 'all']) }}" class="btn-modern btn-sm {{ request('period') == 'all' ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                            <i class="fas fa-infinity"></i> Tüm Zamanlar
                        </a>
                    </div>
                </div>

                <form method="GET" action="{{ route('kiln.analysis', ['firin' => $kiln->id]) }}" class="row align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Başlangıç Tarihi</label>
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ request('start_date') }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Bitiş Tarihi</label>
                        <input type="date" name="end_date" class="form-control" 
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
                        <a href="{{ route('kiln.analysis', ['firin' => $kiln->id]) }}" class="btn-modern btn-secondary-modern">
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
                        <div class="stat-value">{{ number_format($kiln->total_production, 0) }}</div>
                        <div class="stat-label">Toplam Üretim (Ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($kiln->daily_production_average ?? 0, 2) }}</div>
                        <div class="stat-label">Günlük Ortalama (Ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $kiln->total_barcodes }}</div>
                        <div class="stat-label">Toplam Barkod</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">
                            @php
                                $rejectionRate = $kiln->total_barcodes > 0 ? 
                                    round(($statusDistribution[\App\Models\Barcode::STATUS_REJECTED] ?? 0) / $kiln->total_barcodes * 100, 2) : 0;
                            @endphp
                            {{ $rejectionRate }}%
                        </div>
                        <div class="stat-label">Red Oranı</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">
                            @php
                                $deliveryRate = $kiln->total_barcodes > 0 ? 
                                    round(($statusDistribution[\App\Models\Barcode::STATUS_DELIVERED] ?? 0) / $kiln->total_barcodes * 100, 2) : 0;
                            @endphp
                            {{ $deliveryRate }}%
                        </div>
                        <div class="stat-label">Teslim Oranı</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">
                            @php
                                $exceptionallyApprovedRate = $kiln->total_barcodes > 0 ? 
                                    round(($exceptionallyApprovedStats->exceptionally_approved_count ?? 0) / $kiln->total_barcodes * 100, 2) : 0;
                            @endphp
                            {{ $exceptionallyApprovedRate }}%
                        </div>
                        <div class="stat-label">İstisnai Onay Oranı</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $exceptionallyApprovedStats->exceptionally_approved_count ?? 0 }}</div>
                        <div class="stat-label">İstisnai Onaylı Ürün</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Günlük Üretim Ortalaması Analizi -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-calculator"></i> Günlük Üretim Ortalaması Analizi
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="row">
                    <div class="col-md-6">
                        <div class="stat-card">
                            <div class="stat-value">{{ number_format($kiln->daily_production_average ?? 0, 2) }}</div>
                            <div class="stat-label">Hedef Günlük Üretim (Ton)</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card">
                            <div class="stat-value">
                                @php
                                    $actualDailyAverage = $kiln->total_barcodes > 0 ? 
                                        round($kiln->total_production / max(1, $kiln->barcodes->groupBy(function($item) {
                                            return $item->created_at->format('Y-m-d');
                                        })->count()), 2) : 0;
                                @endphp
                                {{ number_format($actualDailyAverage, 2) }}
                            </div>
                            <div class="stat-label">Gerçek Günlük Ortalama (Ton)</div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="stat-card">
                            <div class="stat-value">
                                @php
                                    $efficiencyRate = $kiln->daily_production_average > 0 ? 
                                        round(($actualDailyAverage / $kiln->daily_production_average) * 100, 2) : 0;
                                @endphp
                                {{ $efficiencyRate }}%
                            </div>
                            <div class="stat-label">Verimlilik Oranı</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card">
                            <div class="stat-value">
                                @php
                                    $activeDays = $kiln->barcodes->groupBy(function($item) {
                                        return $item->created_at->format('Y-m-d');
                                    })->count();
                                @endphp
                                {{ $activeDays }}
                            </div>
                            <div class="stat-label">Aktif Çalışma Günü</div>
                        </div>
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
                <p class="card-subtitle-modern">Fırın üretim sürecindeki her aşamada bulunan ürünlerin dağılımı</p>
            </div>
            <div class="card-body-modern">
                <!-- Özet Kartlar -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card text-center">
                            <div class="stat-value text-success">{{ $statusDistribution[\App\Models\Barcode::STATUS_DELIVERED] ?? 0 }}</div>
                            <div class="stat-label">Teslim Edilen</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-center">
                            <div class="stat-value text-warning">{{ $statusDistribution[\App\Models\Barcode::STATUS_WAITING] ?? 0 }}</div>
                            <div class="stat-label">Bekleyen</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-center">
                            <div class="stat-value text-danger">{{ $statusDistribution[\App\Models\Barcode::STATUS_REJECTED] ?? 0 }}</div>
                            <div class="stat-label">Reddedilen</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-center">
                            <div class="stat-value text-info">{{ $statusDistribution[\App\Models\Barcode::STATUS_PRE_APPROVED] ?? 0 }}</div>
                            <div class="stat-label">Ön Onaylı</div>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Durum</th>
                                <th>Barkod Sayısı</th>
                                <th>Oran</th>
                                <th>Toplam Miktar (Ton)</th>
                                <th>Durum Açıklaması</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Barcode::STATUSES as $statusId => $statusName)
                            <tr>
                                <td>
                                    <span class="status-badge 
                                        @if($statusId == \App\Models\Barcode::STATUS_WAITING) status-waiting
                                        @elseif($statusId == \App\Models\Barcode::STATUS_CONTROL_REPEAT) status-control-repeat
                                        @elseif($statusId == \App\Models\Barcode::STATUS_PRE_APPROVED) status-pre-approved
                                        @elseif($statusId == \App\Models\Barcode::STATUS_SHIPMENT_APPROVED) status-shipment-approved
                                        @elseif($statusId == \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER) status-customer-transfer
                                        @elseif($statusId == \App\Models\Barcode::STATUS_DELIVERED) status-delivered
                                        @elseif($statusId == \App\Models\Barcode::STATUS_REJECTED) status-rejected
                                        @elseif($statusId == \App\Models\Barcode::STATUS_MERGED) status-merged
                                        @endif">
                                        {{ $statusName }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusCount = $statusDistribution[$statusId] ?? 0;
                                        $totalBarcodes = max(1, $kiln->total_barcodes);
                                    @endphp
                                    <strong>{{ $statusCount }}</strong>
                                </td>
                                <td>
                                    @php
                                        $percentage = round(($statusCount / $totalBarcodes) * 100, 2);
                                    @endphp
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 mr-2" style="height: 8px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $percentage }}%; background: 
                                                 @if($statusId == \App\Models\Barcode::STATUS_DELIVERED) #20c997
                                                 @elseif($statusId == \App\Models\Barcode::STATUS_REJECTED) #dc3545
                                                 @elseif($statusId == \App\Models\Barcode::STATUS_WAITING) #ffc107
                                                 @elseif($statusId == \App\Models\Barcode::STATUS_PRE_APPROVED) #17a2b8
                                                 @else #6c757d
                                                 @endif;">
                                            </div>
                                        </div>
                                        <span class="badge badge-light">{{ $percentage }}%</span>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $totalQuantity = $kiln->barcodes->where('status', $statusId)->sum(function($barcode) {
                                            return $barcode->quantity ? $barcode->quantity->quantity : 0;
                                        });
                                    @endphp
                                    <strong>{{ number_format($totalQuantity, 0) }}</strong>
                                </td>
                                <td>
                                    @php
                                        $statusDescriptions = [
                                            \App\Models\Barcode::STATUS_WAITING => 'Üretim tamamlandı, kalite kontrol bekleniyor',
                                            \App\Models\Barcode::STATUS_CONTROL_REPEAT => 'Kalite kontrol tekrarı gerekli',
                                            \App\Models\Barcode::STATUS_PRE_APPROVED => 'Kalite kontrol geçti, ön onay verildi',
                                            \App\Models\Barcode::STATUS_SHIPMENT_APPROVED => 'Sevkiyat onayı verildi',
                                            \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER => 'Müşteriye transfer edildi',
                                            \App\Models\Barcode::STATUS_DELIVERED => 'Müşteriye teslim edildi',
                                            \App\Models\Barcode::STATUS_REJECTED => 'Kalite kontrol başarısız',
                                            \App\Models\Barcode::STATUS_MERGED => 'Diğer barkodlarla birleştirildi'
                                        ];
                                    @endphp
                                    <small class="text-muted">{{ $statusDescriptions[$statusId] ?? 'Durum açıklaması bulunamadı' }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- En Çok Üretilen Stoklar -->
        @if($topStocksPagination && isset($topStocksPagination['data']) && count($topStocksPagination['data']) > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-boxes"></i> En Çok Üretilen Stoklar
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table id="top-stocks-table" class="table table-modern">
                        <thead>
                            <tr>
                                <th>Stok Adı</th>
                                <th>Toplam Miktar (Ton)</th>
                                <th>Barkod Sayısı</th>
                                <th>Ortalama Miktar (Ton)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topStocksPagination['data'] as $stock)
                            <tr>
                                <td>{{ $stock->stock ? $stock->stock->name : 'Bilinmeyen Stok' }}</td>
                                <td>{{ number_format($stock->total_quantity, 0) }}</td>
                                <td>{{ $stock->total_barcodes }}</td>
                                <td>{{ number_format($stock->total_quantity / $stock->total_barcodes, 0) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-info-circle mb-2" style="font-size: 1.5rem;"></i>
                                        <p class="mb-0">Bu fırın için stok verisi bulunamadı.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    @if($topStocksPagination['last_page'] > 1)
                    <div id="top-stocks-pagination" class="d-flex justify-content-center mt-3">
                        <nav aria-label="En çok üretilen stoklar sayfaları">
                            <ul class="pagination" data-table="top-stocks">
                                @if($topStocksPagination['current_page'] > 1)
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $topStocksPagination['current_page'] - 1 }}" data-table="top-stocks" href="javascript:void(0)">
                                            <i class="fas fa-chevron-left"></i> Önceki
                                        </a>
                                    </li>
                                @endif
                                
                                @php
                                    $start = max(1, $topStocksPagination['current_page'] - 2);
                                    $end = min($topStocksPagination['last_page'], $topStocksPagination['current_page'] + 2);
                                @endphp
                                
                                @if($start > 1)
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="1" data-table="top-stocks" href="javascript:void(0)">1</a>
                                    </li>
                                    @if($start > 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif
                                
                                @for($i = $start; $i <= $end; $i++)
                                    <li class="page-item {{ $i == $topStocksPagination['current_page'] ? 'active' : '' }}">
                                        <a class="page-link pagination-link" data-page="{{ $i }}" data-table="top-stocks" href="javascript:void(0)">{{ $i }}</a>
                                    </li>
                                @endfor
                                
                                @if($end < $topStocksPagination['last_page'])
                                    @if($end < $topStocksPagination['last_page'] - 1)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $topStocksPagination['last_page'] }}" data-table="top-stocks" href="javascript:void(0)">{{ $topStocksPagination['last_page'] }}</a>
                                    </li>
                                @endif
                                
                                @if($topStocksPagination['current_page'] < $topStocksPagination['last_page'])
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $topStocksPagination['current_page'] + 1 }}" data-table="top-stocks" href="javascript:void(0)">
                                            Sonraki <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- En Çok Çalışılan Müşteriler -->
        @if($topCompaniesPagination && isset($topCompaniesPagination['data']) && count($topCompaniesPagination['data']) > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-users"></i> En Çok Çalışılan Müşteriler
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table id="top-companies-table" class="table table-modern">
                        <thead>
                            <tr>
                                <th>Müşteri Adı</th>
                                <th>Toplam Miktar (Ton)</th>
                                <th>Barkod Sayısı</th>
                                <th>Ortalama Miktar (Ton)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topCompaniesPagination['data'] as $company)
                            <tr>
                                <td>{{ $company->company ? $company->company->name : 'Bilinmeyen Müşteri' }}</td>
                                <td>{{ number_format($company->total_quantity, 0) }}</td>
                                <td>{{ $company->total_barcodes }}</td>
                                <td>{{ number_format($company->total_quantity / $company->total_barcodes, 0) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-info-circle mb-2" style="font-size: 1.5rem;"></i>
                                        <p class="mb-0">Bu fırın için müşteri verisi bulunamadı.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    @if($topCompaniesPagination['last_page'] > 1)
                    <div id="top-companies-pagination" class="d-flex justify-content-center mt-3">
                        <nav aria-label="En çok çalışılan müşteriler sayfaları">
                            <ul class="pagination" data-table="top-companies">
                                @if($topCompaniesPagination['current_page'] > 1)
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $topCompaniesPagination['current_page'] - 1 }}" data-table="top-companies" href="javascript:void(0)">
                                            <i class="fas fa-chevron-left"></i> Önceki
                                        </a>
                                    </li>
                                @endif
                                
                                @php
                                    $start = max(1, $topCompaniesPagination['current_page'] - 2);
                                    $end = min($topCompaniesPagination['last_page'], $topCompaniesPagination['current_page'] + 2);
                                @endphp
                                
                                @if($start > 1)
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="1" data-table="top-companies" href="javascript:void(0)">1</a>
                                    </li>
                                    @if($start > 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif
                                
                                @for($i = $start; $i <= $end; $i++)
                                    <li class="page-item {{ $i == $topCompaniesPagination['current_page'] ? 'active' : '' }}">
                                        <a class="page-link pagination-link" data-page="{{ $i }}" data-table="top-companies" href="javascript:void(0)">{{ $i }}</a>
                                    </li>
                                @endfor
                                
                                @if($end < $topCompaniesPagination['last_page'])
                                    @if($end < $topCompaniesPagination['last_page'] - 1)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $topCompaniesPagination['last_page'] }}" data-table="top-companies" href="javascript:void(0)">{{ $topCompaniesPagination['last_page'] }}</a>
                                    </li>
                                @endif
                                
                                @if($topCompaniesPagination['current_page'] < $topCompaniesPagination['last_page'])
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $topCompaniesPagination['current_page'] + 1 }}" data-table="top-companies" href="javascript:void(0)">
                                            Sonraki <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Aylık Üretim Trendi -->
        @if($monthlyProduction->count() > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-chart-line"></i> Son 12 Aylık Üretim Trendi
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="chart-container">
                    <canvas id="productionChart"></canvas>
                </div>
            </div>
        </div>
        @endif

        <!-- Üretim Trendi Detaylı Tablosu -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-table"></i> Üretim Trendi Detaylı Analizi
                </h3>
                <p class="card-subtitle-modern">Aylık bazda üretim miktarları ve barkod sayıları</p>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Ay</th>
                                <th>Toplam Barkod</th>
                                <th>Toplam Üretim (KG)</th>
                                <th>Toplam Üretim (Ton)</th>
                                <th>Ortalama Üretim/Barkod (KG)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyProduction as $data)
                                @php
                                    $monthName = \Carbon\Carbon::createFromDate($data->year, $data->month, 1)->format('M Y');
                                    $totalBarcodes = $data->total_barcodes ?? 0;
                                    $totalQuantity = $data->total_quantity ?? 0;
                                    $totalTons = round($totalQuantity / 1000, 2);
                                    $avgPerBarcode = $totalBarcodes > 0 ? round($totalQuantity / $totalBarcodes, 2) : 0;
                                @endphp
                                <tr>
                                    <td><strong>{{ $monthName }}</strong></td>
                                    <td class="text-center">{{ $totalBarcodes }}</td>
                                    <td class="text-center">{{ number_format($totalQuantity, 0) }}</td>
                                    <td class="text-center">{{ number_format($totalTons, 2) }}</td>
                                    <td class="text-center">{{ number_format($avgPerBarcode, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-info">
                            <tr>
                                <th>TOPLAM</th>
                                <th class="text-center">{{ $monthlyProduction->sum('total_barcodes') }}</th>
                                <th class="text-center">{{ number_format($monthlyProduction->sum('total_quantity'), 0) }}</th>
                                <th class="text-center">{{ number_format($monthlyProduction->sum('total_quantity') / 1000, 2) }}</th>
                                <th class="text-center">
                                    {{ $monthlyProduction->sum('total_barcodes') > 0 ? 
                                       number_format($monthlyProduction->sum('total_quantity') / $monthlyProduction->sum('total_barcodes'), 2) : 0 }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Red Oranı Trendi -->
        @if($rejectionTrend->count() > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-chart-area"></i> Son 12 Aylık Red Oranı Trendi
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="chart-container">
                    <canvas id="rejectionChart"></canvas>
                </div>
            </div>
        </div>
        @endif

        <!-- Red Oranı Detaylı Tablosu -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-table"></i> Red Oranı Detaylı Analizi
                </h3>
                <p class="card-subtitle-modern">Aylık bazda red oranları ve sayıları</p>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Ay</th>
                                <th>Toplam Ürün</th>
                                <th>Reddedilen</th>
                                <th>Red Oranı</th>
                                <th>Kabul Edilen</th>
                                <th>Kabul Oranı</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rejectionTrend as $data)
                                @php
                                    $monthName = \Carbon\Carbon::createFromDate($data->year, $data->month, 1)->format('M Y');
                                    $rejectedCount = $data->rejected_count ?? 0;
                                    $totalBarcodes = $data->total_barcodes ?? 0;
                                    $acceptedCount = $totalBarcodes - $rejectedCount;
                                    $rejectionRate = $totalBarcodes > 0 ? round(($rejectedCount / $totalBarcodes) * 100, 2) : 0;
                                    $acceptanceRate = $totalBarcodes > 0 ? round(($acceptedCount / $totalBarcodes) * 100, 2) : 0;
                                @endphp
                                <tr>
                                    <td><strong>{{ $monthName }}</strong></td>
                                    <td class="text-center">{{ $totalBarcodes }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-danger">{{ $rejectedCount }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-danger">{{ $rejectionRate }}%</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-success">{{ $acceptedCount }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-success">{{ $acceptanceRate }}%</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-info">
                            <tr>
                                <th>TOPLAM</th>
                                <th class="text-center">{{ $rejectionTrend->sum('total_barcodes') }}</th>
                                <th class="text-center">
                                    <span class="badge badge-danger">{{ $rejectionTrend->sum('rejected_count') }}</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-danger">
                                        {{ $rejectionTrend->sum('total_barcodes') > 0 ? 
                                           round(($rejectionTrend->sum('rejected_count') / $rejectionTrend->sum('total_barcodes')) * 100, 2) : 0 }}%
                                    </span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-success">
                                        {{ $rejectionTrend->sum('total_barcodes') - $rejectionTrend->sum('rejected_count') }}
                                    </span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-success">
                                        {{ $rejectionTrend->sum('total_barcodes') > 0 ? 
                                           round((($rejectionTrend->sum('total_barcodes') - $rejectionTrend->sum('rejected_count')) / $rejectionTrend->sum('total_barcodes')) * 100, 2) : 0 }}%
                                    </span>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        @if($exceptionallyApprovedTrend->count() > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-exclamation-triangle"></i> Son 12 Aylık İstisnai Onay Trendi
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="chart-container">
                    <canvas id="exceptionallyApprovedChart"></canvas>
                </div>
            </div>
        </div>
        @endif

        <!-- İstisnai Onay Detaylı Tablosu -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-table"></i> İstisnai Onay Detaylı Analizi
                </h3>
                <p class="card-subtitle-modern">Aylık bazda istisnai onay oranları ve sayıları</p>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Ay</th>
                                <th>Toplam Ürün</th>
                                <th>İstisnai Onaylı</th>
                                <th>İstisnai Onay Oranı</th>
                                <th>Normal Onaylı</th>
                                <th>Normal Onay Oranı</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($exceptionallyApprovedTrend as $data)
                                @php
                                    $monthName = \Carbon\Carbon::createFromDate($data->year, $data->month, 1)->format('M Y');
                                    $exceptionallyApprovedCount = $data->exceptionally_approved_count ?? 0;
                                    $totalBarcodes = $data->total_barcodes ?? 0;
                                    $normalApprovedCount = $totalBarcodes - $exceptionallyApprovedCount;
                                    $exceptionallyApprovedRate = $totalBarcodes > 0 ? round(($exceptionallyApprovedCount / $totalBarcodes) * 100, 2) : 0;
                                    $normalApprovedRate = $totalBarcodes > 0 ? round(($normalApprovedCount / $totalBarcodes) * 100, 2) : 0;
                                @endphp
                                <tr>
                                    <td><strong>{{ $monthName }}</strong></td>
                                    <td class="text-center">{{ $totalBarcodes }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-warning">{{ $exceptionallyApprovedCount }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-warning">{{ $exceptionallyApprovedRate }}%</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-success">{{ $normalApprovedCount }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-success">{{ $normalApprovedRate }}%</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-info">
                            <tr>
                                <th>TOPLAM</th>
                                <th class="text-center">{{ $exceptionallyApprovedTrend->sum('total_barcodes') }}</th>
                                <th class="text-center">
                                    <span class="badge badge-warning">{{ $exceptionallyApprovedTrend->sum('exceptionally_approved_count') }}</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-warning">
                                        {{ $exceptionallyApprovedTrend->sum('total_barcodes') > 0 ? 
                                           round(($exceptionallyApprovedTrend->sum('exceptionally_approved_count') / $exceptionallyApprovedTrend->sum('total_barcodes')) * 100, 2) : 0 }}%
                                    </span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-success">
                                        {{ $exceptionallyApprovedTrend->sum('total_barcodes') - $exceptionallyApprovedTrend->sum('exceptionally_approved_count') }}
                                    </span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-success">
                                        {{ $exceptionallyApprovedTrend->sum('total_barcodes') > 0 ? 
                                           round((($exceptionallyApprovedTrend->sum('total_barcodes') - $exceptionallyApprovedTrend->sum('exceptionally_approved_count')) / $exceptionallyApprovedTrend->sum('total_barcodes')) * 100, 2) : 0 }}%
                                    </span>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Aylık üretim grafiği
    @if($monthlyProduction->count() > 0)
    const productionCtx = document.getElementById('productionChart').getContext('2d');
    new Chart(productionCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($monthlyProduction as $data)
                    '{{ \Carbon\Carbon::createFromDate($data->year, $data->month, 1)->format('M Y') }}',
                @endforeach
            ],
            datasets: [{
                label: 'Üretim Miktarı (Ton)',
                data: [
                    @foreach($monthlyProduction as $data)
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

    // Red oranı grafiği
    @if($rejectionTrend->count() > 0)
    const rejectionCtx = document.getElementById('rejectionChart').getContext('2d');
    new Chart(rejectionCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($rejectionTrend as $data)
                    '{{ \Carbon\Carbon::createFromDate($data->year, $data->month, 1)->format('M Y') }}',
                @endforeach
            ],
            datasets: [{
                label: 'Red Oranı (%)',
                data: [
                    @foreach($rejectionTrend as $data)
                        {{ $data->total_barcodes > 0 ? round(($data->rejected_count / $data->total_barcodes) * 100, 2) : 0 }},
                    @endforeach
                ],
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
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
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
    @endif

    // İstisnai onay trendi grafiği
    @if($exceptionallyApprovedTrend->count() > 0)
    const exceptionallyApprovedCtx = document.getElementById('exceptionallyApprovedChart').getContext('2d');
    new Chart(exceptionallyApprovedCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($exceptionallyApprovedTrend as $data)
                    '{{ \Carbon\Carbon::createFromDate($data->year, $data->month, 1)->format('M Y') }}',
                @endforeach
            ],
            datasets: [{
                label: 'İstisnai Onay Oranı (%)',
                data: [
                    @foreach($exceptionallyApprovedTrend as $data)
                        {{ $data->total_barcodes > 0 ? round(($data->exceptionally_approved_count / $data->total_barcodes) * 100, 2) : 0 }},
                    @endforeach
                ],
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
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
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
    @endif
</script>

<script>
// Enhanced AJAX Pagination with Smooth Transitions
document.addEventListener('DOMContentLoaded', function() {
    let isLoading = false;
    
    // Pagination linklerine click event ekle
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('pagination-link') && !isLoading) {
            e.preventDefault();
            
            const page = e.target.getAttribute('data-page');
            const table = e.target.getAttribute('data-table');
            
            if (page && table) {
                loadTableData(table, page);
            }
        }
    });
    
    // Tablo verilerini AJAX ile yükle
    async function loadTableData(table, page) {
        if (isLoading) return;
        
        isLoading = true;
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('page', page);
        
        // Loading göster
        showLoading(table);
        
        try {
            const response = await fetch(currentUrl.toString());
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const html = await response.text();
            
            // HTML'i parse et
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // İlgili tabloyu güncelle
            await updateTable(table, doc, page);
            
            // URL'i güncelle (sayfa yenilenmeden)
            window.history.pushState({}, '', currentUrl.toString());
            
            // Smooth scroll to table
            smoothScrollToTable(table);
            
        } catch (error) {
            console.error('Veri yüklenirken hata:', error);
            showErrorMessage(table, 'Veri yüklenirken bir hata oluştu. Lütfen tekrar deneyin.');
        } finally {
            isLoading = false;
            hideLoading(table);
        }
    }
    
    // Tabloyu güncelle
    async function updateTable(table, doc, page) {
        let tableBody, pagination;
        
        switch(table) {
            case 'top-stocks':
                tableBody = document.querySelector('#top-stocks-table tbody');
                pagination = document.querySelector('#top-stocks-pagination');
                break;
            case 'top-companies':
                tableBody = document.querySelector('#top-companies-table tbody');
                pagination = document.querySelector('#top-companies-pagination');
                break;
        }
        
        if (tableBody && pagination) {
            // Fade out effect
            tableBody.style.opacity = '0.5';
            tableBody.style.transition = 'opacity 0.3s ease';
            
            // Tablo verilerini güncelle
            const newTableBody = doc.querySelector(`#${table}-table tbody`);
            if (newTableBody) {
                tableBody.innerHTML = newTableBody.innerHTML;
            }
            
            // Pagination'ı güncelle
            const newPagination = doc.querySelector(`#${table}-pagination`);
            if (newPagination) {
                pagination.innerHTML = newPagination.innerHTML;
            }
            
            // Aktif sayfa linkini güncelle
            updateActivePage(table, page);
            
            // Fade in effect
            setTimeout(() => {
                tableBody.style.opacity = '1';
            }, 100);
        }
    }
    
    // Aktif sayfa linkini güncelle
    function updateActivePage(table, page) {
        const pagination = document.querySelector(`#${table}-pagination`);
        if (pagination) {
            // Tüm linklerden active class'ını kaldır
            pagination.querySelectorAll('.page-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Yeni sayfayı active yap
            const activeLink = pagination.querySelector(`[data-page="${page}"]`);
            if (activeLink) {
                activeLink.closest('.page-item').classList.add('active');
            }
        }
    }
    
    // Loading göster
    function showLoading(table) {
        const tableBody = document.querySelector(`#${table}-table tbody`);
        const tableElement = document.querySelector(`#${table}-table`);
        
        if (tableBody) {
            // Loading overlay ekle
            if (tableElement) {
                tableElement.classList.add('loading-overlay');
            }
            
            // Tablo sütun sayısını al
            const columnCount = tableElement ? tableElement.querySelectorAll('thead th').length : 4;
            
            const loadingHtml = `
                <tr>
                    <td colspan="${columnCount}" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                            <div class="spinner-border text-primary mb-2" role="status">
                                <span class="visually-hidden">Yükleniyor...</span>
                            </div>
                            <small class="text-muted">Veriler yükleniyor...</small>
                        </div>
                    </td>
                </tr>
            `;
            tableBody.innerHTML = loadingHtml;
        }
    }
    
    // Loading gizle
    function hideLoading(table) {
        const tableElement = document.querySelector(`#${table}-table`);
        if (tableElement) {
            tableElement.classList.remove('loading-overlay');
        }
    }
    
    // Hata mesajı göster
    function showErrorMessage(table, message) {
        const tableBody = document.querySelector(`#${table}-table tbody`);
        const tableElement = document.querySelector(`#${table}-table`);
        
        if (tableBody) {
            // Tablo sütun sayısını al
            const columnCount = tableElement ? tableElement.querySelectorAll('thead th').length : 4;
            
            const errorHtml = `
                <tr>
                    <td colspan="${columnCount}" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-exclamation-triangle text-warning mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-2">${message}</p>
                            <button class="btn btn-sm btn-outline-primary" onclick="location.reload()">
                                <i class="fas fa-redo"></i> Sayfayı Yenile
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tableBody.innerHTML = errorHtml;
        }
    }
    
    // Smooth scroll to table
    function smoothScrollToTable(table) {
        const tableElement = document.querySelector(`#${table}-table`);
        if (tableElement) {
            tableElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }
    
    // Keyboard navigation support
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
            const activePagination = document.querySelector('.pagination .page-item.active');
            if (activePagination) {
                const currentPage = parseInt(activePagination.querySelector('.page-link').getAttribute('data-page'));
                const paginationContainer = activePagination.closest('.pagination');
                const table = paginationContainer ? paginationContainer.getAttribute('data-table') : null;
                
                if (table && currentPage) {
                    if (e.key === 'ArrowLeft' && currentPage > 1) {
                        loadTableData(table, currentPage - 1);
                    } else if (e.key === 'ArrowRight') {
                        const paginationLinks = paginationContainer.querySelectorAll('.page-link[data-page]');
                        const pageNumbers = Array.from(paginationLinks).map(link => parseInt(link.getAttribute('data-page')));
                        const maxPage = Math.max(...pageNumbers);
                        
                        if (currentPage < maxPage) {
                            loadTableData(table, currentPage + 1);
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
