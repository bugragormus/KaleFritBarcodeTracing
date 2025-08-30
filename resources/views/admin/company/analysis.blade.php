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
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .table-modern tbody tr {
        transition: all 0.3s ease;
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
                    <a href="{{ route('company.excel.download', request()->query()) }}" class="btn-modern btn-warning-modern mr-3">
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
                                        $totalQuantity = $company->barcodes->where('status', $statusId)->sum(function($barcode) {
                                            return $barcode->quantity ? $barcode->quantity->quantity : 0;
                                        });
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
                    <table id="top-stocks-table" class="table table-modern">
                        <thead>
                            <tr>
                                <th>Stok Adı</th>
                                <th>Toplam Miktar (Ton)</th>
                                <th>Sipariş Sayısı</th>
                                <th>Ortalama Miktar (Ton)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topStocks as $stock)
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
                                        <p class="mb-0">Bu firma için stok verisi bulunamadı.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    @if($topStocksPagination['last_page'] > 1)
                    <div id="top-stocks-pagination" class="d-flex justify-content-center mt-3">
                        <nav aria-label="En çok alınan stoklar sayfaları">
                            <ul class="pagination" data-table="top-stocks">
                                @if($topStocksPagination['current_page'] > 1)
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $topStocksPagination['current_page'] - 1 }}" data-table="top-stocks" href="javascript:void(0)">
                                            <i class="fas fa-chevron-left"></i> Önceki
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="fas fa-chevron-left"></i> Önceki
                                        </span>
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
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            Sonraki <i class="fas fa-chevron-right"></i>
                                        </span>
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
                    <table id="top-kilns-table" class="table table-modern">
                        <thead>
                            <tr>
                                <th>Fırın Adı</th>
                                <th>Toplam Miktar (Ton)</th>
                                <th>Sipariş Sayısı</th>
                                <th>Ortalama Miktar (Ton)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topKilns as $kiln)
                            <tr>
                                <td>{{ $kiln->kiln ? $kiln->kiln->name : 'Bilinmeyen Fırın' }}</td>
                                <td>{{ number_format($kiln->total_quantity, 0) }}</td>
                                <td>{{ $kiln->total_barcodes }}</td>
                                <td>{{ number_format($kiln->total_quantity / $kiln->total_barcodes, 0) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-info-circle mb-2" style="font-size: 1.5rem;"></i>
                                        <p class="mb-0">Bu firma için fırın verisi bulunamadı.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    @if($topKilnsPagination['last_page'] > 1)
                    <div id="top-kilns-pagination" class="d-flex justify-content-center mt-3">
                        <nav aria-label="En çok çalışılan fırınlar sayfaları">
                            <ul class="pagination" data-table="top-kilns">
                                @if($topKilnsPagination['current_page'] > 1)
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $topKilnsPagination['current_page'] - 1 }}" data-table="top-kilns" href="javascript:void(0)">
                                            <i class="fas fa-chevron-left"></i> Önceki
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="fas fa-chevron-left"></i> Önceki
                                        </span>
                                    </li>
                                @endif
                                
                                @php
                                    $start = max(1, $topKilnsPagination['current_page'] - 2);
                                    $end = min($topKilnsPagination['last_page'], $topKilnsPagination['current_page'] + 2);
                                @endphp
                                
                                @if($start > 1)
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="1" data-table="top-kilns" href="javascript:void(0)">1</a>
                                    </li>
                                    @if($start > 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif
                                
                                @for($i = $start; $i <= $end; $i++)
                                    <li class="page-item {{ $i == $topKilnsPagination['current_page'] ? 'active' : '' }}">
                                        <a class="page-link pagination-link" data-page="{{ $i }}" data-table="top-kilns" href="javascript:void(0)">{{ $i }}</a>
                                    </li>
                                @endfor
                                
                                @if($end < $topKilnsPagination['last_page'])
                                    @if($end < $topKilnsPagination['last_page'] - 1)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $topKilnsPagination['last_page'] }}" data-table="top-kilns" href="javascript:void(0)">{{ $topKilnsPagination['last_page'] }}</a>
                                    </li>
                                @endif
                                
                                @if($topKilnsPagination['current_page'] < $topKilnsPagination['last_page'])
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $topKilnsPagination['current_page'] + 1 }}" data-table="top-kilns" href="javascript:void(0)">
                                            Sonraki <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            Sonraki <i class="fas fa-chevron-right"></i>
                                        </span>
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
                        {{ $data->total_quantity ?? 0 }},
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
            case 'top-kilns':
                tableBody = document.querySelector('#top-kilns-table tbody');
                pagination = document.querySelector('#top-kilns-pagination');
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
