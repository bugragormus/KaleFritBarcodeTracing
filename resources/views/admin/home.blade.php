@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item active">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </li>
@endsection

@section('styles')
<style>
    /* Modern Dashboard Styles */
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }
    
    .wrapper {
        background: transparent;
    }
    
    /* Page Title Modernization */
    .page-title-box {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 30px;
        padding: 25px;
    }
    
    .welcome-section {
        text-align: left;
    }
    
    .page-title {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(45deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 10px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .welcome-text {
        font-size: 1rem;
        color: #6c757d;
        font-weight: 500;
        margin: 0;
    }
    
    .welcome-text strong {
        color: #667eea;
        font-weight: 600;
    }
    
    /* Modern Button Styles */
    .btn-refresh {
        background: linear-gradient(45deg, #667eea, #764ba2);
        border: none;
        border-radius: 25px;
        padding: 12px 25px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
    }
    
    .btn-refresh:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        background: linear-gradient(45deg, #5a6fd8, #6a4190);
    }
    
    /* Quick Actions Card */
    .quick-actions-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border: none;
        transition: all 0.3s ease;
    }
    
    .quick-actions-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }
    
    .quick-action-item {
        display: flex;
        align-items: center;
        padding: 20px;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 15px;
        text-decoration: none;
        color: #333;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .quick-action-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: #333;
        background: rgba(255, 255, 255, 0.95);
    }
    
    .quick-action-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 1.5rem;
        color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    .quick-action-text h6 {
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .quick-action-text small {
        color: #666;
        font-size: 0.9rem;
    }

    /* Section Titles */
    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 20px;
        padding: 15px 20px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 10px;
        border-left: 4px solid #667eea;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Modern KPI Cards */
    .kpi-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        border: none;
        overflow: hidden;
        position: relative;
        min-height: 180px;
        display: flex;
        align-items: center;
    }
    
    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, rgba(255,255,255,0.3), rgba(255,255,255,0.1));
    }
    
    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }
    
    .kpi-card .card-body {
        padding: 30px 20px;
        width: 100%;
    }
    
    .kpi-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 15px 0 10px 0;
        line-height: 1;
    }
    
    .kpi-label {
        font-size: 1rem;
        color: #7f8c8d;
        font-weight: 500;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .kpi-icon {
        opacity: 0.9;
        transition: all 0.3s ease;
        margin-bottom: 15px;
    }
    
    .kpi-card:hover .kpi-icon {
        opacity: 1;
        transform: scale(1.1);
    }
    
    .kpi-icon i {
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
    }
    
    /* Modern Color Schemes */
    .bg-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    
    .bg-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
    }
    
    .bg-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
    }
    
    .bg-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
    }
    
    .bg-secondary {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%) !important;
    }
    
    .bg-dark {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
    }
    
    .bg-danger {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%) !important;
    }
    
    .bg-purple {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    
    .bg-teal {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
    }
    
    .bg-indigo {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .page-title {
            font-size: 2rem;
        }
        
        .welcome-text {
            font-size: 1rem;
        }
        
        .kpi-number {
            font-size: 2rem;
        }
        
        .kpi-label {
            font-size: 0.9rem;
        }
        
        .kpi-icon i {
            font-size: 2.5rem !important;
        }
        
        .section-title {
            font-size: 1.3rem;
        }
    }
    
    @media (max-width: 576px) {
        .page-title {
            font-size: 1.8rem;
        }
        
        .kpi-card {
            min-height: 150px;
        }
        
        .kpi-number {
            font-size: 1.8rem;
        }
        
        .kpi-icon i {
            font-size: 2rem !important;
        }
    }

    /* Modern Chart Cards */
    .chart-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        border: none;
        overflow: hidden;
    }
    
    .chart-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }
    
    .chart-card .card-body {
        padding: 25px;
    }
    
    .chart-card .card-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 20px;
    }

    /* Chart Styles */
    .chart-container {
        position: relative;
        height: 300px;
        margin: 20px 0;
    }

    /* Widget Settings Modal Styles */
    .widget-order-item {
        background: #f8f9fa;
        border: 1px solid #dee2e6 !important;
        transition: all 0.3s ease;
    }
    
    .widget-order-item:hover {
        background: #e9ecef;
        border-color: #adb5bd !important;
    }
    
    .widget-order-item .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .widget-loading {
        padding: 40px 20px;
        text-align: center;
        color: #6c757d;
    }
    
    .widget-loading i {
        color: #007bff;
        margin-bottom: 15px;
    }
    
    /* Button loading state */
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .btn .fa-spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="welcome-section">
                            <h1 class="page-title m-0">🎯 YÖNETİM PANELİ</h1>
                            <p class="welcome-text">Hoş geldiniz, <strong>{{ auth()->user()->name }}</strong>!</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="float-right">
                            <button class="btn btn-primary btn-refresh" onclick="loadAllWidgets()">
                                <i class="fas fa-sync-alt mr-1"></i> Yenile
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Widget -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card quick-actions-card">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-bolt text-warning mr-2"></i>
                        Hızlı İşlemler
                    </h5>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('barcode.create') }}" class="quick-action-item">
                                <div class="quick-action-icon bg-primary">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <div class="quick-action-text">
                                    <h6>Barkod Oluştur</h6>
                                    <small>Yeni barkod ekle</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('barcode.historyIndex') }}" class="quick-action-item">
                                <div class="quick-action-icon bg-success">
                                    <i class="fas fa-history"></i>
                                </div>
                                <div class="quick-action-text">
                                    <h6>Barkod Hareketleri</h6>
                                    <small>Hareket geçmişi</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="javascript:void(0)" onclick="checkLabPermission()" class="quick-action-item">
                                <div class="quick-action-icon bg-info">
                                    <i class="fas fa-flask"></i>
                                </div>
                                <div class="quick-action-text">
                                    <h6>Laboratuvar</h6>
                                    <small>Test işlemleri</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('stock.index') }}" class="quick-action-item">
                                <div class="quick-action-icon bg-warning">
                                    <i class="fas fa-boxes"></i>
                                </div>
                                <div class="quick-action-text">
                                    <h6>Stok Yönetimi</h6>
                                    <small>Stok işlemleri</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="section-title mb-4">
                <i class="fas fa-chart-bar text-primary mr-2"></i>
                Sistem Metrikleri
            </h5>
        </div>
        
        <!-- First Row - 3 Cards -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card kpi-card">
                <div class="card-body text-center">
                    <div class="kpi-icon mb-3">
                        <i class="fas fa-barcode fa-3x text-primary"></i>
                    </div>
                    <h3 class="kpi-number" id="totalBarcodes">-</h3>
                    <p class="kpi-label">Toplam Barkod</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card kpi-card">
                <div class="card-body text-center">
                    <div class="kpi-icon mb-3">
                        <i class="fas fa-check-circle fa-3x text-success"></i>
                    </div>
                    <h3 class="kpi-number" id="processedToday">-</h3>
                    <p class="kpi-label">Bugün İşlenen</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card kpi-card">
                <div class="card-body text-center">
                    <div class="kpi-icon mb-3">
                        <i class="fas fa-clock fa-3x text-warning"></i>
                    </div>
                    <h3 class="kpi-number" id="pendingBarcodes">-</h3>
                    <p class="kpi-label">Bekleyen</p>
                </div>
            </div>
        </div>
        
        <!-- Second Row - 3 Cards -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card kpi-card">
                <div class="card-body text-center">
                    <div class="kpi-icon mb-3">
                        <i class="fas fa-truck fa-3x text-info"></i>
                    </div>
                    <h3 class="kpi-number" id="deliveryRate">-</h3>
                    <p class="kpi-label">Teslim Oranı</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card kpi-card">
                <div class="card-body text-center">
                    <div class="kpi-icon mb-3">
                        <i class="fas fa-users fa-3x text-secondary"></i>
                    </div>
                    <h3 class="kpi-number" id="totalUsers">-</h3>
                    <p class="kpi-label">Toplam Kullanıcı</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card kpi-card">
                <div class="card-body text-center">
                    <div class="kpi-icon mb-3">
                        <i class="fas fa-warehouse fa-3x text-dark"></i>
                    </div>
                    <h3 class="kpi-number" id="totalWarehouses">-</h3>
                    <p class="kpi-label">Toplam Depo</p>
                </div>
            </div>
        </div>
        
        <!-- Third Row - 3 Cards -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card kpi-card">
                <div class="card-body text-center">
                    <div class="kpi-icon mb-3">
                        <i class="fas fa-building fa-3x text-danger"></i>
                    </div>
                    <h3 class="kpi-number" id="totalCompanies">-</h3>
                    <p class="kpi-label">Toplam Firma</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card kpi-card">
                <div class="card-body text-center">
                    <div class="kpi-icon mb-3">
                        <i class="fas fa-fire fa-3x text-purple"></i>
                    </div>
                    <h3 class="kpi-number" id="totalKilns">-</h3>
                    <p class="kpi-label">Toplam Fırın</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card kpi-card">
                <div class="card-body text-center">
                    <div class="kpi-icon mb-3">
                        <i class="fas fa-cubes fa-3x text-indigo"></i>
                    </div>
                    <h3 class="kpi-number" id="totalQuantity">-</h3>
                    <p class="kpi-label">Toplam Stok Miktarı (KG)</p>
                    
                    <!-- Dinamik Stok Giriş Alanları -->
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label class="small text-muted">Dinamik Stok 1 (KG)</label>
                                    <input type="number" class="form-control form-control-sm" id="dynamicQuantity1" 
                                           placeholder="0" step="0.01" min="0">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label class="small text-muted">Dinamik Stok 2 (KG)</label>
                                    <input type="number" class="form-control form-control-sm" id="dynamicQuantity2" 
                                           placeholder="0" step="0.01" min="0">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-primary" onclick="updateDynamicStock()">
                            <i class="fas fa-save"></i> Güncelle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Charts Section -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card chart-card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-chart-pie text-primary mr-2"></i>
                        Barkod Durumları
                    </h5>
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="barcodeStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card chart-card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-chart-line text-success mr-2"></i>
                        Günlük İşlem Trendi
                    </h5>
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="dailyTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    initializeCharts();
    loadKPIData();
    loadDynamicStockData();
    
    // Her 5 dakikada bir verileri güncelle
    setInterval(function() {
        loadKPIData();
        updateCharts(); 
    }, 300000);
});

function initializeCharts() {
    // Barkod Durumları Pasta Grafiği
    const statusCtx = document.getElementById('barcodeStatusChart').getContext('2d');
    window.barcodeStatusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Beklemede', 'Kontrol Tekrarı', 'Ön Onaylı', 'Sevk Onaylı', 'Reddedildi', 'Müşteri Transfer', 'Teslim Edildi'],
            datasets: [{
                data: [0, 0, 0, 0, 0, 0, 0],
                backgroundColor: [
                    '#ffc107', // Beklemede - Sarı
                    '#fd7e14', // Kontrol Tekrarı - Turuncu
                    '#28a745', // Ön Onaylı - Yeşil
                    '#17a2b8', // Sevk Onaylı - Mavi
                    '#dc3545', // Reddedildi - Kırmızı
                    '#6f42c1', // Müşteri Transfer - Mor
                    '#20c997'  // Teslim Edildi - Teal
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            onClick: function(event, elements) {
                if (elements.length > 0) {
                    const index = elements[0].index;
                    const labels = ['Beklemede', 'Kontrol Tekrarı', 'Ön Onaylı', 'Sevk Onaylı', 'Reddedildi', 'Müşteri Transfer', 'Teslim Edildi'];
                    showStatusDetails(labels[index]);
                }
            }
        }
    });

    // Günlük Trend Çizgi Grafiği
    const trendCtx = document.getElementById('dailyTrendChart').getContext('2d');
    window.dailyTrendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Oluşturulan',
                data: [],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            }, {
                label: 'İşlenen',
                data: [],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function loadKPIData() {
    return new Promise((resolve, reject) => {
    $.ajax({
        url: '{{ route("widget.all") }}',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                updateKPICards(response.data);
                updateCharts(response.data);
                    resolve(response.data);
                } else {
                    reject(new Error('Widget verileri alınamadı'));
            }
        },
            error: function(xhr, status, error) {
                console.error('Widget data error:', error);
            showError('KPI verileri yüklenirken hata oluştu!');
                reject(error);
        }
        });
    });
}

function updateKPICards(data) {
    $('#totalBarcodes').text(data.total_barcodes || 0);
    $('#processedToday').text(data.processed_today || 0);
    $('#pendingBarcodes').text(data.pending_barcodes || 0);
    $('#deliveryRate').text((data.delivery_rate || 0) + '%');
    $('#totalUsers').text(data.total_users || 0);
    $('#totalWarehouses').text(data.total_warehouses || 0);
    $('#totalCompanies').text(data.total_companies || 0);
    $('#totalKilns').text(data.total_kilns || 0);
    $('#totalQuantity').text(data.total_quantity || 0);
}

function updateCharts(data) {
    // Pasta grafiği güncelle
    if (window.barcodeStatusChart && data.status_distribution) {
        window.barcodeStatusChart.data.datasets[0].data = [
            data.status_distribution.waiting || 0,
            data.status_distribution.control_repeat || 0,
            data.status_distribution.pre_approved || 0,
            data.status_distribution.shipment_approved || 0,
            data.status_distribution.rejected || 0,
            data.status_distribution.customer_transfer || 0,
            data.status_distribution.delivered || 0
        ];
        window.barcodeStatusChart.update();
    }

    // Trend grafiği güncelle
    if (window.dailyTrendChart && data.daily_trend) {
        window.dailyTrendChart.data.labels = data.daily_trend.labels || [];
        window.dailyTrendChart.data.datasets[0].data = data.daily_trend.created || [];
        window.dailyTrendChart.data.datasets[1].data = data.daily_trend.processed || [];
        window.dailyTrendChart.update();
    }
}

function showStatusDetails(status) {
    showInfo(`${status} durumundaki barkodların detaylarını görmek için ilgili sayfaya yönlendiriliyorsunuz.`);
    // Burada ilgili sayfaya yönlendirme yapılabilir
    setTimeout(() => {
        if (status === 'Beklemede') {
            window.location.href = '{{ route("laboratory.barcode-list") }}?status=1';
        } else if (status === 'Kontrol Tekrarı') {
            window.location.href = '{{ route("laboratory.barcode-list") }}?status=2';
        } else if (status === 'Ön Onaylı') {
            window.location.href = '{{ route("laboratory.barcode-list") }}?status=3';
        } else if (status === 'Sevk Onaylı') {
            window.location.href = '{{ route("warehouse.index") }}';
        } else if (status === 'Müşteri Transfer') {
            window.location.href = '{{ route("barcode.index") }}?status=6';
        }
        // Diğer durumlar için de yönlendirmeler eklenebilir
    }, 2000);
}

// Laboratuvar yetki kontrolü
function checkLabPermission() {
    // Önce AJAX ile yetki kontrolü yap
    $.ajax({
        url: '{{ route("laboratory.dashboard") }}',
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            // Başarılı ise sayfaya yönlendir
            window.location.href = '{{ route("laboratory.dashboard") }}';
        },
        error: function(xhr) {
            if (xhr.status === 403) {
                // Yetki yoksa uyarı göster
                showError('Laboratuvar ekranına erişim yetkiniz bulunmamaktadır.');
            } else {
                // Diğer hatalar için genel mesaj
                showError('Bir hata oluştu. Lütfen tekrar deneyin.');
            }
        }
    });
}

// Veri yenileme fonksiyonu
function loadAllWidgets() {
    // Yenile butonunu devre dışı bırak
    const refreshBtn = event.target;
    const originalText = refreshBtn.innerHTML;
    refreshBtn.disabled = true;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Yenileniyor...';
    
    // KPI verilerini yenile
    loadKPIData().then(() => {
        showSuccess('Veriler başarıyla yenilendi!');
    }).catch((error) => {
        showError('Veriler yenilenirken hata oluştu!');
        console.error('Data refresh error:', error);
    }).finally(() => {
        // Butonu eski haline getir
        refreshBtn.disabled = false;
        refreshBtn.innerHTML = originalText;
    });
}

// Başarı mesajı gösterme
function showSuccess(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Başarılı!',
            text: message,
            timer: 2000,
            showConfirmButton: false
        });
    } else {
        alert('Başarılı: ' + message);
    }
}

// Hata mesajı gösterme
function showError(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Hata!',
            text: message
        });
    } else {
        alert('Hata: ' + message);
    }
}

// Bilgi mesajı gösterme
function showInfo(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'info',
            title: 'Bilgi',
            text: message,
            timer: 3000,
            showConfirmButton: false
        });
    } else {
        alert('Bilgi: ' + message);
    }
}

// Dinamik stok verilerini yükle
function loadDynamicStockData() {
    $.ajax({
        url: '{{ route("dynamic-stock.index") }}',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#dynamicQuantity1').val(response.data.quantity_1);
                $('#dynamicQuantity2').val(response.data.quantity_2);
            }
        },
        error: function(xhr, status, error) {
            console.error('Dinamik stok verileri yüklenirken hata:', error);
        }
    });
}

// Dinamik stok güncelle
function updateDynamicStock() {
    const quantity1 = parseFloat($('#dynamicQuantity1').val()) || 0;
    const quantity2 = parseFloat($('#dynamicQuantity2').val()) || 0;
    
    // Butonu devre dışı bırak
    const updateBtn = event.target;
    const originalText = updateBtn.innerHTML;
    updateBtn.disabled = true;
    updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Güncelleniyor...';
    
    $.ajax({
        url: '{{ route("dynamic-stock.update") }}',
        type: 'PUT',
        data: {
            quantity_1: quantity1,
            quantity_2: quantity2,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                showSuccess('Dinamik stok miktarları başarıyla güncellendi!');
                // KPI verilerini yenile
                loadKPIData();
            } else {
                showError('Dinamik stok güncellenirken hata: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            let errorMessage = 'Dinamik stok güncellenirken hata oluştu!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showError(errorMessage);
        },
        complete: function() {
            // Butonu eski haline getir
            updateBtn.disabled = false;
            updateBtn.innerHTML = originalText;
        }
    });
}

// Enter tuşu ile güncelleme
$('#dynamicQuantity1, #dynamicQuantity2').on('keypress', function(e) {
    if (e.which === 13) { // Enter tuşu
        updateDynamicStock();
    }
});


</script>
@endsection

