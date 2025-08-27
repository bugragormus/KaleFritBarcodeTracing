@extends('layouts.app')

@section('styles')
<style>
    body, .main-content, .modern-stock-detail {
        background: #f8f9fa !important;
    }
    .modern-stock-detail {
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
    
    .btn-success-modern {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    
    .btn-warning-modern {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: white;
    }
    
    .btn-info-modern {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
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
    
    .button-group {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .button-group .btn-modern {
        margin: 0;
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .page-title-modern {
            font-size: 2rem;
        }
        
        .button-group {
            gap: 0.25rem;
        }
        
        .button-group .btn-modern {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
        }
        
        .col-md-4.text-right {
            margin-top: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="modern-stock-detail">
    <div class="container-fluid">
        <!-- Modern Page Header -->
        <div class="page-header-modern">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title-modern">
                        <i class="fas fa-boxes"></i> {{ $stock->name }}
                    </h1>
                    <p class="page-subtitle-modern">Stok Kodu: {{ $stock->code }} | Detaylı Analiz ve Raporlar</p>
                </div>
                <div class="col-md-4 text-right">
                    <div class="button-group justify-content-end">
                        <a href="{{ route('stock.excel', ['stok' => $stock->id]) }}" class="btn-modern btn-warning-modern">
                            <i class="fas fa-file-excel"></i> Excel İndir
                        </a>
                        <a href="{{ route('stock.print', ['stok' => $stock->id]) }}" class="btn-modern btn-info-modern" target="_blank">
                            <i class="fas fa-print"></i> Yazdır
                        </a>
                        <a href="{{ route('stock.index') }}" class="btn-modern btn-primary-modern">
                            <i class="fas fa-arrow-left"></i> Geri Dön
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if($stockDetails)
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
                        <div class="stat-value">{{ number_format($stockDetails->total_quantity, 0) }}</div>
                        <div class="stat-label">Toplam Miktar (KG)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $stockDetails->total_barcodes }}</div>
                        <div class="stat-label">Toplam Barkod</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $stockDetails->total_kilns }}</div>
                        <div class="stat-label">Kullanılan Fırın</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $stockDetails->total_companies }}</div>
                        <div class="stat-label">Müşteri Sayısı</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $stockDetails->first_production_date ? \Carbon\Carbon::parse($stockDetails->first_production_date)->format('d.m.Y') : '-' }}</div>
                        <div class="stat-label">İlk Üretim</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $stockDetails->last_production_date ? \Carbon\Carbon::parse($stockDetails->last_production_date)->format('d.m.Y') : '-' }}</div>
                        <div class="stat-label">Son Üretim</div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Son 30 Günlük Üretim Grafiği -->
        @if($productionData && count($productionData) > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-chart-line"></i> Son 30 Günlük Üretim Trendi
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="chart-container">
                    <canvas id="productionChart"></canvas>
                </div>
            </div>
        </div>
        @endif

        <!-- Fırın Bazında Üretim -->
        @if($productionByKiln && isset($productionByKiln['data']) && count($productionByKiln['data']) > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-fire"></i> Fırın Bazında Üretim
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table id="production-kiln-table" class="table table-modern">
                        <thead>
                            <tr>
                                <th>Fırın Adı</th>
                                <th>Barkod Sayısı</th>
                                <th>Toplam Miktar (KG)</th>
                                <th>Ortalama Miktar (KG)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productionByKiln['data'] as $kiln)
                            <tr>
                                <td>{{ $kiln->kiln_name ?? 'Belirtilmemiş' }}</td>
                                <td>{{ $kiln->barcode_count }}</td>
                                <td>{{ number_format($kiln->total_quantity, 0) }}</td>
                                <td>{{ number_format($kiln->avg_quantity, 0) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-info-circle mb-2" style="font-size: 1.5rem;"></i>
                                        <p class="mb-0">Bu stok için fırın bazında üretim verisi bulunamadı.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    @if($productionByKiln['last_page'] > 1)
                    <div id="production-kiln-pagination" class="d-flex justify-content-center mt-3">
                        <nav aria-label="Fırın bazında üretim sayfaları">
                            <ul class="pagination" data-table="production-kiln">
                                @if($productionByKiln['current_page'] > 1)
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $productionByKiln['current_page'] - 1 }}" data-table="production-kiln" href="javascript:void(0)">
                                            <i class="fas fa-chevron-left"></i> Önceki
                                        </a>
                                    </li>
                                @endif
                                
                                @php
                                    $start = max(1, $productionByKiln['current_page'] - 2);
                                    $end = min($productionByKiln['last_page'], $productionByKiln['current_page'] + 2);
                                @endphp
                                
                                @if($start > 1)
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="1" data-table="production-kiln" href="javascript:void(0)">1</a>
                                    </li>
                                    @if($start > 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif
                                
                                @for($i = $start; $i <= $end; $i++)
                                    <li class="page-item {{ $i == $productionByKiln['current_page'] ? 'active' : '' }}">
                                        <a class="page-link pagination-link" data-page="{{ $i }}" data-table="production-kiln" href="javascript:void(0)">{{ $i }}</a>
                                    </li>
                                @endfor
                                
                                @if($end < $productionByKiln['last_page'])
                                    @if($end < $productionByKiln['last_page'] - 1)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $productionByKiln['last_page'] }}" data-table="production-kiln" href="javascript:void(0)">{{ $productionByKiln['last_page'] }}</a>
                                    </li>
                                @endif
                                
                                @if($productionByKiln['current_page'] < $productionByKiln['last_page'])
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $productionByKiln['current_page'] + 1 }}" data-table="production-kiln" href="javascript:void(0)">
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

        <!-- Müşteri Bazında Satış -->
        @if($salesByCompany && isset($salesByCompany['data']) && count($salesByCompany['data']) > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-users"></i> Müşteri Bazında Satış
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table id="sales-company-table" class="table table-modern">
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
                            @forelse($salesByCompany['data'] as $company)
                            <tr>
                                <td>{{ $company->company_name ?? 'Belirtilmemiş' }}</td>
                                <td>{{ $company->barcode_count }}</td>
                                <td>{{ number_format($company->total_quantity, 0) }}</td>
                                <td>{{ $company->first_sale_date ? \Carbon\Carbon::parse($company->first_sale_date)->format('d.m.Y') : '-' }}</td>
                                <td>{{ $company->last_sale_date ? \Carbon\Carbon::parse($company->last_sale_date)->format('d.m.Y') : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-info-circle mb-2" style="font-size: 1.5rem;"></i>
                                        <p class="mb-0">Bu stok için müşteri bazında satış verisi bulunamadı.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    @if($salesByCompany['last_page'] > 1)
                    <div id="sales-company-pagination" class="d-flex justify-content-center mt-3">
                        <nav aria-label="Müşteri bazında satış sayfaları">
                            <ul class="pagination" data-table="sales-company">
                                @if($salesByCompany['current_page'] > 1)
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $salesByCompany['current_page'] - 1 }}" data-table="sales-company" href="javascript:void(0)">
                                            <i class="fas fa-chevron-left"></i> Önceki
                                        </a>
                                    </li>
                                @endif
                                
                                @php
                                    $start = max(1, $salesByCompany['current_page'] - 2);
                                    $end = min($salesByCompany['last_page'], $salesByCompany['current_page'] + 2);
                                @endphp
                                
                                @if($start > 1)
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="1" data-table="sales-company" href="javascript:void(0)">1</a>
                                    </li>
                                    @if($start > 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif
                                
                                @for($i = $start; $i <= $end; $i++)
                                    <li class="page-item {{ $i == $salesByCompany['current_page'] ? 'active' : '' }}">
                                        <a class="page-link pagination-link" data-page="{{ $i }}" data-table="sales-company" href="javascript:void(0)">{{ $i }}</a>
                                    </li>
                                @endfor
                                
                                @if($end < $salesByCompany['last_page'])
                                    @if($end < $salesByCompany['last_page'] - 1)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $salesByCompany['last_page'] }}" data-table="sales-company" href="javascript:void(0)">{{ $salesByCompany['last_page'] }}</a>
                                    </li>
                                @endif
                                
                                @if($salesByCompany['current_page'] < $salesByCompany['last_page'])
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $salesByCompany['current_page'] + 1 }}" data-table="sales-company" href="javascript:void(0)">
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
        @if($monthlyTrend && isset($monthlyTrend['data']) && count($monthlyTrend['data']) > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-calendar-alt"></i> Aylık Üretim Trendi
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table id="monthly-trend-table" class="table table-modern">
                        <thead>
                            <tr>
                                <th>Dönem</th>
                                <th>Barkod Sayısı</th>
                                <th>Toplam Miktar (KG)</th>
                                <th>Ortalama Miktar (KG)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monthlyTrend['data'] as $trend)
                            <tr>
                                <td>{{ $trend->month }}/{{ $trend->year }}</td>
                                <td>{{ $trend->barcode_count }}</td>
                                <td>{{ number_format($trend->total_quantity, 0) }}</td>
                                <td>{{ $trend->barcode_count > 0 ? number_format($trend->total_quantity / $trend->barcode_count, 0) : 0 }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-info-circle mb-2" style="font-size: 1.5rem;"></i>
                                        <p class="mb-0">Bu stok için aylık üretim trendi verisi bulunamadı.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    @if($monthlyTrend['last_page'] > 1)
                    <div id="monthly-trend-pagination" class="d-flex justify-content-center mt-3">
                        <nav aria-label="Aylık üretim trendi sayfaları">
                            <ul class="pagination" data-table="monthly-trend">
                                @if($monthlyTrend['current_page'] > 1)
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $monthlyTrend['current_page'] - 1 }}" data-table="monthly-trend" href="javascript:void(0)">
                                            <i class="fas fa-chevron-left"></i> Önceki
                                        </a>
                                    </li>
                                @endif
                                
                                @php
                                    $start = max(1, $monthlyTrend['current_page'] - 2);
                                    $end = min($monthlyTrend['last_page'], $monthlyTrend['current_page'] + 2);
                                @endphp
                                
                                @if($start > 1)
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="1" data-table="monthly-trend" href="javascript:void(0)">1</a>
                                    </li>
                                    @if($start > 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif
                                
                                @for($i = $start; $i <= $end; $i++)
                                    <li class="page-item {{ $i == $monthlyTrend['current_page'] ? 'active' : '' }}">
                                        <a class="page-link pagination-link" data-page="{{ $i }}" data-table="monthly-trend" href="javascript:void(0)">{{ $i }}</a>
                                    </li>
                                @endfor
                                
                                @if($end < $monthlyTrend['last_page'])
                                    @if($end < $monthlyTrend['last_page'] - 1)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $monthlyTrend['last_page'] }}" data-table="monthly-trend" href="javascript:void(0)">{{ $monthlyTrend['last_page'] }}</a>
                                    </li>
                                @endif
                                
                                @if($monthlyTrend['current_page'] < $monthlyTrend['last_page'])
                                    <li class="page-item">
                                        <a class="page-link pagination-link" data-page="{{ $monthlyTrend['current_page'] + 1 }}" data-table="monthly-trend" href="javascript:void(0)">
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
        @else
        <div class="card-modern">
            <div class="card-body-modern text-center">
                <div class="error-state">
                    <i class="fas fa-box-open"></i>
                    <h4 class="text-muted">Bu stok için henüz veri bulunmuyor</h4>
                    <p class="text-muted mb-3">Stok detayları görüntülenebilmesi için önce bu stoktan üretim yapılması gerekiyor.</p>
                    <a href="{{ route('stock.index') }}" class="btn-modern btn-primary-modern">
                        <i class="fas fa-arrow-left"></i> Stok Listesine Dön
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
@if($productionData && count($productionData) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('productionChart').getContext('2d');
    
    const productionData = @json($productionData);
    
    const labels = productionData.map(item => item.date);
    const quantities = productionData.map(item => item.total_quantity);
    const counts = productionData.map(item => item.barcode_count);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Toplam Miktar (KG)',
                data: quantities,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                yAxisID: 'y'
            }, {
                label: 'Barkod Sayısı',
                data: counts,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Tarih'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Miktar (KG)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Barkod Sayısı'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Günlük Üretim Trendi'
                }
            }
        }
    });
});
</script>
@endif

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
            case 'production-kiln':
                tableBody = document.querySelector('#production-kiln-table tbody');
                pagination = document.querySelector('#production-kiln-pagination');
                break;
            case 'sales-company':
                tableBody = document.querySelector('#sales-company-table tbody');
                pagination = document.querySelector('#sales-company-pagination');
                break;
            case 'monthly-trend':
                tableBody = document.querySelector('#monthly-trend-table tbody');
                pagination = document.querySelector('#monthly-trend-pagination');
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
            const columnCount = tableElement ? tableElement.querySelector('thead th').length : 4;
            
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
