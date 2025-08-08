@extends('layouts.app')

@section('styles')
<style>
    body, .main-content, .modern-dashboard {
        background: #f8f9fa !important;
    }
    .modern-dashboard {
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
    
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .quick-action-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .quick-action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .quick-action-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
    }
    
    .quick-action-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .quick-action-desc {
        font-size: 0.875rem;
        opacity: 0.9;
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .quick-actions {
            grid-template-columns: 1fr;
        }
        
        .page-title-modern {
            font-size: 2rem;
        }
    }
</style>
@endsection

@section('content')
<div class="modern-dashboard">
    <div class="container-fluid">
        <!-- Modern Page Header -->
        <div class="page-header-modern">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title-modern">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </h1>
                    <p class="page-subtitle-modern">Sistem geneli istatistikler ve performans göstergeleri</p>
                </div>
                <div class="col-md-4 text-right">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('barcode.create') }}" class="btn-modern btn-success-modern">
                            <i class="fas fa-plus"></i> Yeni Barkod
                        </a>
                        <a href="{{ route('stock.create') }}" class="btn-modern btn-warning-modern">
                            <i class="fas fa-boxes"></i> Yeni Stok
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hızlı Erişim -->
        <div class="quick-actions">
            <a href="{{ route('barcode.index') }}" class="quick-action-card">
                <div class="quick-action-icon">
                    <i class="fas fa-barcode"></i>
                </div>
                <div class="quick-action-title">Barkod Yönetimi</div>
                <div class="quick-action-desc">Barkodları görüntüle ve yönet</div>
            </a>
            
            <a href="{{ route('stock.index') }}" class="quick-action-card">
                <div class="quick-action-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="quick-action-title">Stok Yönetimi</div>
                <div class="quick-action-desc">Stokları analiz et ve raporla</div>
            </a>
            
            <a href="{{ route('company.index') }}" class="quick-action-card">
                <div class="quick-action-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="quick-action-title">Müşteri Yönetimi</div>
                <div class="quick-action-desc">Müşteri bilgilerini yönet</div>
            </a>
            
            <a href="{{ route('warehouse.index') }}" class="quick-action-card">
                <div class="quick-action-icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="quick-action-title">Depo Yönetimi</div>
                <div class="quick-action-desc">Depo stoklarını kontrol et</div>
            </a>
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
                        <div class="stat-value">{{ number_format($generalStats['total_stocks']) }}</div>
                        <div class="stat-label">Toplam Stok</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($generalStats['total_barcodes']) }}</div>
                        <div class="stat-label">Toplam Barkod</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($generalStats['total_companies']) }}</div>
                        <div class="stat-label">Toplam Müşteri</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($generalStats['total_warehouses']) }}</div>
                        <div class="stat-label">Toplam Depo</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($generalStats['today_production']) }}</div>
                        <div class="stat-label">Bugün Üretim</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($generalStats['month_production']) }}</div>
                        <div class="stat-label">Bu Ay Üretim</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($generalStats['total_delivered']) }}</div>
                        <div class="stat-label">Teslim Edilen</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($generalStats['total_rejected']) }}</div>
                        <div class="stat-label">Reddedilen</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Son 7 Günlük Üretim Grafiği -->
        @if($weeklyProduction && count($weeklyProduction) > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-chart-line"></i> Son 7 Günlük Üretim Trendi
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="chart-container">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>
        </div>
        @endif

        <!-- En Çok Üretilen Stoklar -->
        @if($topStocks && count($topStocks) > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-trophy"></i> En Çok Üretilen Stoklar
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Stok Adı</th>
                                <th>Stok Kodu</th>
                                <th>Barkod Sayısı</th>
                                <th>Toplam Miktar (KG)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topStocks as $stock)
                            <tr>
                                <td>{{ $stock->name }}</td>
                                <td>{{ $stock->code }}</td>
                                <td>{{ number_format($stock->barcode_count) }}</td>
                                <td>{{ number_format($stock->total_quantity, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- En Aktif Müşteriler -->
        @if($topCustomers && count($topCustomers) > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-users"></i> En Aktif Müşteriler
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topCustomers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ number_format($customer->barcode_count) }}</td>
                                <td>{{ number_format($customer->total_quantity, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Fırın Performansı -->
        @if($kilnPerformance && count($kilnPerformance) > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-fire"></i> Fırın Performansı
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
                            @foreach($kilnPerformance as $kiln)
                            <tr>
                                <td>{{ $kiln->name ?? 'Belirtilmemiş' }}</td>
                                <td>{{ number_format($kiln->barcode_count) }}</td>
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

        <!-- Durum Dağılımı -->
        @if($statusDistribution && count($statusDistribution) > 0)
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-tasks"></i> Durum Dağılımı
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="stats-grid">
                    @foreach($statusDistribution as $status)
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($status->count) }}</div>
                        <div class="stat-label">
                            @if(isset(\App\Models\Barcode::STATUSES[$status->status]))
                                {{ \App\Models\Barcode::STATUSES[$status->status] }}
                            @else
                                Bilinmeyen Durum
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
@if($weeklyProduction && count($weeklyProduction) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('weeklyChart').getContext('2d');
    
    const weeklyData = @json($weeklyProduction);
    
    const labels = weeklyData.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('tr-TR', { weekday: 'short', month: 'short', day: 'numeric' });
    });
    const quantities = weeklyData.map(item => item.total_quantity);
    const counts = weeklyData.map(item => item.barcode_count);
    
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
                    text: 'Haftalık Üretim Trendi'
                }
            }
        }
    });
});
</script>
@endif
@endsection
