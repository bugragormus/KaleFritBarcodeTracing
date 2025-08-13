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
        @if($productionByKiln && count($productionByKiln) > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-fire"></i> Fırın Bazında Üretim
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
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
            </div>
        </div>
        @endif

        <!-- Müşteri Bazında Satış -->
        @if($salesByCompany && count($salesByCompany) > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-users"></i> Müşteri Bazında Satış
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
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
            </div>
        </div>
        @endif

        <!-- Aylık Üretim Trendi -->
        @if($monthlyTrend && count($monthlyTrend) > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-calendar-alt"></i> Aylık Üretim Trendi
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
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
            </div>
        </div>
        @endif
        @else
        <div class="card-modern">
            <div class="card-body-modern text-center">
                <h4>Bu stok için henüz veri bulunmuyor.</h4>
                <p>Stok detayları görüntülenebilmesi için önce bu stoktan üretim yapılması gerekiyor.</p>
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
@endsection
