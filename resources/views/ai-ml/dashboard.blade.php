@extends('layouts.app')

@section('styles')
    <style>
        .ai-dashboard {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .ai-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            text-align: center;
        }

        .ai-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .ai-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .ai-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .ai-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .ai-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f8f9fa;
        }

        .ai-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.5rem;
            color: white;
        }

        .ai-card-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .ai-card-subtitle {
            font-size: 0.9rem;
            color: #6c757d;
            margin: 0;
        }

        .forecast-chart {
            height: 300px;
            margin: 1rem 0;
        }

        .metric-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .metric-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            border-left: 4px solid #667eea;
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .metric-label {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 500;
        }

        .metric-trend {
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }

        .trend-up {
            color: #28a745;
        }

        .trend-down {
            color: #dc3545;
        }

        .trend-stable {
            color: #6c757d;
        }

        .anomaly-item {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
        }

        .anomaly-critical {
            background: #f8d7da;
            border-color: #f5c6cb;
        }

        .anomaly-warning {
            background: #fff3cd;
            border-color: #ffeaa7;
        }

        .anomaly-info {
            background: #d1ecf1;
            border-color: #bee5eb;
        }

        .quality-prediction {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .quality-info {
            flex: 1;
        }

        .quality-stock {
            font-weight: 600;
            color: #333;
        }

        .quality-rate {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .quality-risk {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .risk-high {
            background: #f8d7da;
            color: #721c24;
        }

        .risk-medium {
            background: #fff3cd;
            color: #856404;
        }

        .risk-low {
            background: #d4edda;
            color: #155724;
        }

        .maintenance-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-left: 4px solid #667eea;
        }

        .maintenance-urgent {
            border-left-color: #dc3545;
            background: #f8d7da;
        }

        .maintenance-high {
            border-left-color: #fd7e14;
            background: #fff3cd;
        }

        .maintenance-medium {
            border-left-color: #ffc107;
            background: #fff3cd;
        }

        .maintenance-low {
            border-left-color: #28a745;
            background: #d4edda;
        }

        .customer-segment {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .segment-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .segment-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .segment-stat {
            text-align: center;
        }

        .segment-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .segment-label {
            font-size: 0.8rem;
            opacity: 0.9;
        }

        .refresh-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .refresh-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .loading {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }

        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .tab-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .tab-nav {
            display: flex;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .tab-btn {
            flex: 1;
            padding: 1rem;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            background: white;
            color: #667eea;
            border-bottom: 3px solid #667eea;
        }

        .tab-content {
            padding: 2rem;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }
    </style>
@endsection

@section('content')
<div class="ai-dashboard">
    <div class="container">
        <!-- AI/ML Header -->
        <div class="ai-header">
            <h1 class="ai-title">
                <i class="fas fa-brain"></i> AI/ML Analytics Dashboard
            </h1>
            <p class="ai-subtitle">
                Yapay zeka ve makine öğrenmesi ile güçlendirilmiş analitik ve tahminler
            </p>
            <button class="refresh-btn" onclick="refreshData()">
                <i class="fas fa-sync-alt"></i> Verileri Yenile
            </button>
        </div>

        <!-- Tab Navigation -->
        <div class="tab-container">
            <div class="tab-nav">
                <button class="tab-btn active" onclick="showTab('overview')">
                    <i class="fas fa-chart-line"></i> Genel Bakış
                </button>
                <button class="tab-btn" onclick="showTab('forecasting')">
                    <i class="fas fa-crystal-ball"></i> Üretim Tahmini
                </button>
                <button class="tab-btn" onclick="showTab('quality')">
                    <i class="fas fa-award"></i> Kalite Analizi
                </button>
                <button class="tab-btn" onclick="showTab('anomalies')">
                    <i class="fas fa-exclamation-triangle"></i> Anomali Tespiti
                </button>
                <button class="tab-btn" onclick="showTab('warehouse')">
                    <i class="fas fa-warehouse"></i> Depo Optimizasyonu
                </button>
                <button class="tab-btn" onclick="showTab('maintenance')">
                    <i class="fas fa-tools"></i> Bakım Tahmini
                </button>
                <button class="tab-btn" onclick="showTab('customers')">
                    <i class="fas fa-users"></i> Müşteri Analizi
                </button>
            </div>

            <!-- Overview Tab -->
            <div class="tab-content">
                <div id="overview" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="ai-card">
                                <div class="ai-card-header">
                                    <div class="ai-card-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <div>
                                        <h3 class="ai-card-title">Üretim Tahmini</h3>
                                        <p class="ai-card-subtitle">Gelecek 30 günlük üretim tahmini</p>
                                    </div>
                                </div>
                                <div id="production-forecast-chart" class="forecast-chart"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ai-card">
                                <div class="ai-card-header">
                                    <div class="ai-card-icon" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                        <i class="fas fa-award"></i>
                                    </div>
                                    <div>
                                        <h3 class="ai-card-title">Kalite Metrikleri</h3>
                                        <p class="ai-card-subtitle">Genel kalite performansı</p>
                                    </div>
                                </div>
                                <div class="metric-grid">
                                    <div class="metric-card">
                                        <div class="metric-value" id="approval-rate">-</div>
                                        <div class="metric-label">Onay Oranı</div>
                                        <div class="metric-trend" id="approval-trend">-</div>
                                    </div>
                                    <div class="metric-card">
                                        <div class="metric-value" id="rejection-rate">-</div>
                                        <div class="metric-label">Red Oranı</div>
                                        <div class="metric-trend" id="rejection-trend">-</div>
                                    </div>
                                    <div class="metric-card">
                                        <div class="metric-value" id="delivery-rate">-</div>
                                        <div class="metric-label">Teslimat Oranı</div>
                                        <div class="metric-trend" id="delivery-trend">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="ai-card">
                                <div class="ai-card-header">
                                    <div class="ai-card-icon" style="background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div>
                                        <h3 class="ai-card-title">Kritik Uyarılar</h3>
                                        <p class="ai-card-subtitle">Acil müdahale gerektiren durumlar</p>
                                    </div>
                                </div>
                                <div id="critical-alerts"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ai-card">
                                <div class="ai-card-header">
                                    <div class="ai-card-icon" style="background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div>
                                        <h3 class="ai-card-title">Müşteri Segmentasyonu</h3>
                                        <p class="ai-card-subtitle">Müşteri değer analizi</p>
                                    </div>
                                </div>
                                <div id="customer-segments"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Forecasting Tab -->
                <div id="forecasting" class="tab-pane">
                    <div class="ai-card">
                        <div class="ai-card-header">
                            <div class="ai-card-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fas fa-crystal-ball"></i>
                            </div>
                            <div>
                                <h3 class="ai-card-title">Üretim Tahmini</h3>
                                <p class="ai-card-subtitle">Zaman serisi analizi ile gelecek üretim tahmini</p>
                            </div>
                        </div>
                        <div class="metric-grid">
                            <div class="metric-card">
                                <div class="metric-value" id="forecast-accuracy">-</div>
                                <div class="metric-label">Tahmin Doğruluğu</div>
                            </div>
                            <div class="metric-card">
                                <div class="metric-value" id="trend-direction">-</div>
                                <div class="metric-label">Trend Yönü</div>
                            </div>
                            <div class="metric-card">
                                <div class="metric-value" id="seasonality-strength">-</div>
                                <div class="metric-label">Mevsimsellik</div>
                            </div>
                        </div>
                        <div id="forecast-chart" class="forecast-chart"></div>
                    </div>
                </div>

                <!-- Quality Tab -->
                <div id="quality" class="tab-pane">
                    <div class="ai-card">
                        <div class="ai-card-header">
                            <div class="ai-card-icon" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                <i class="fas fa-award"></i>
                            </div>
                            <div>
                                <h3 class="ai-card-title">Kalite Tahmini ve Risk Analizi</h3>
                                <p class="ai-card-subtitle">Stok bazlı kalite performansı ve risk değerlendirmesi</p>
                            </div>
                        </div>
                        <div id="quality-predictions"></div>
                    </div>
                </div>

                <!-- Anomalies Tab -->
                <div id="anomalies" class="tab-pane">
                    <div class="ai-card">
                        <div class="ai-card-header">
                            <div class="ai-card-icon" style="background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <h3 class="ai-card-title">Anomali Tespiti</h3>
                                <p class="ai-card-subtitle">Sistem, üretim ve kalite anomalileri</p>
                            </div>
                        </div>
                        <div id="anomalies-list"></div>
                    </div>
                </div>

                <!-- Warehouse Tab -->
                <div id="warehouse" class="tab-pane">
                    <div class="ai-card">
                        <div class="ai-card-header">
                            <div class="ai-card-icon" style="background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);">
                                <i class="fas fa-warehouse"></i>
                            </div>
                            <div>
                                <h3 class="ai-card-title">Depo Optimizasyonu</h3>
                                <p class="ai-card-subtitle">Depo kullanım verimliliği ve optimizasyon önerileri</p>
                            </div>
                        </div>
                        <div id="warehouse-optimization"></div>
                    </div>
                </div>

                <!-- Maintenance Tab -->
                <div id="maintenance" class="tab-pane">
                    <div class="ai-card">
                        <div class="ai-card-header">
                            <div class="ai-card-icon" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
                                <i class="fas fa-tools"></i>
                            </div>
                            <div>
                                <h3 class="ai-card-title">Öngörülü Bakım</h3>
                                <p class="ai-card-subtitle">Fırın bakım zamanlaması ve sağlık durumu</p>
                            </div>
                        </div>
                        <div id="maintenance-predictions"></div>
                    </div>
                </div>

                <!-- Customers Tab -->
                <div id="customers" class="tab-pane">
                    <div class="ai-card">
                        <div class="ai-card-header">
                            <div class="ai-card-icon" style="background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h3 class="ai-card-title">Müşteri Davranış Analizi</h3>
                                <p class="ai-card-subtitle">Müşteri segmentasyonu ve churn analizi</p>
                            </div>
                        </div>
                        <div id="customer-analysis"></div>
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
let currentTab = 'overview';
let charts = {};

function showTab(tabName) {
    // Hide all tab panes
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('active');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab pane
    document.getElementById(tabName).classList.add('active');
    
    // Add active class to clicked button
    event.target.classList.add('active');
    
    currentTab = tabName;
    
    // Load data for the selected tab
    loadTabData(tabName);
}

function loadTabData(tabName) {
    const loadingHtml = `
        <div class="loading">
            <div class="loading-spinner"></div>
            <p>Veriler yükleniyor...</p>
        </div>
    `;
    
    switch(tabName) {
        case 'overview':
            document.getElementById('production-forecast-chart').innerHTML = loadingHtml;
            loadOverviewData();
            break;
        case 'forecasting':
            document.getElementById('forecast-chart').innerHTML = loadingHtml;
            loadForecastingData();
            break;
        case 'quality':
            document.getElementById('quality-predictions').innerHTML = loadingHtml;
            loadQualityData();
            break;
        case 'anomalies':
            document.getElementById('anomalies-list').innerHTML = loadingHtml;
            loadAnomaliesData();
            break;
        case 'warehouse':
            document.getElementById('warehouse-optimization').innerHTML = loadingHtml;
            loadWarehouseData();
            break;
        case 'maintenance':
            document.getElementById('maintenance-predictions').innerHTML = loadingHtml;
            loadMaintenanceData();
            break;
        case 'customers':
            document.getElementById('customer-analysis').innerHTML = loadingHtml;
            loadCustomerData();
            break;
    }
}

function loadOverviewData() {
    // Load production forecast
    fetch('/api/ai-ml/forecast')
        .then(response => response.json())
        .then(data => {
            createForecastChart('production-forecast-chart', data.forecast);
        });
    
    // Load quality metrics
    fetch('/api/ai-ml/quality-metrics')
        .then(response => response.json())
        .then(data => {
            document.getElementById('approval-rate').textContent = data.approval_rate + '%';
            document.getElementById('rejection-rate').textContent = data.rejection_rate + '%';
            document.getElementById('delivery-rate').textContent = data.delivery_rate + '%';
            
            // Set trends
            document.getElementById('approval-trend').textContent = '↑ İyileşiyor';
            document.getElementById('approval-trend').className = 'metric-trend trend-up';
        });
    
    // Load critical alerts
    fetch('/api/ai-ml/anomalies')
        .then(response => response.json())
        .then(data => {
            displayCriticalAlerts(data);
        });
    
    // Load customer segments
    fetch('/api/ai-ml/customer-segments')
        .then(response => response.json())
        .then(data => {
            displayCustomerSegments(data);
        });
}

function loadForecastingData() {
    fetch('/api/ai-ml/forecast')
        .then(response => response.json())
        .then(data => {
            document.getElementById('forecast-accuracy').textContent = data.accuracy + '%';
            document.getElementById('trend-direction').textContent = data.trend.direction;
            document.getElementById('seasonality-strength').textContent = 'Güçlü';
            
            createForecastChart('forecast-chart', data.forecast);
        });
}

function loadQualityData() {
    fetch('/api/ai-ml/quality-predictions')
        .then(response => response.json())
        .then(data => {
            let html = '';
            data.predictions.forEach(prediction => {
                html += `
                    <div class="quality-prediction">
                        <div class="quality-info">
                            <div class="quality-stock">${prediction.stock_name}</div>
                            <div class="quality-rate">
                                Mevcut: ${prediction.current_approval_rate}% → 
                                Tahmin: ${prediction.predicted_approval_rate}%
                            </div>
                        </div>
                        <div class="quality-risk risk-${prediction.risk_level}">
                            ${prediction.risk_level.toUpperCase()}
                        </div>
                    </div>
                `;
            });
            document.getElementById('quality-predictions').innerHTML = html;
        });
}

function loadAnomaliesData() {
    fetch('/api/ai-ml/anomalies')
        .then(response => response.json())
        .then(data => {
            let html = '';
            
            // Production anomalies
            if (data.production && data.production.length > 0) {
                html += '<h4><i class="fas fa-industry"></i> Üretim Anomalileri</h4>';
                data.production.forEach(anomaly => {
                    html += `
                        <div class="anomaly-item anomaly-warning">
                            <strong>${anomaly.date}</strong>: ${anomaly.production} üretim
                            (Beklenen: ${anomaly.expected_range.min}-${anomaly.expected_range.max})
                            <br><small>Sapma: ${anomaly.deviation} standart sapma</small>
                        </div>
                    `;
                });
            }
            
            // Quality anomalies
            if (data.quality && data.quality.length > 0) {
                html += '<h4><i class="fas fa-award"></i> Kalite Anomalileri</h4>';
                data.quality.forEach(anomaly => {
                    html += `
                        <div class="anomaly-item anomaly-critical">
                            <strong>${anomaly.stock_name}</strong>: 
                            Son oran: ${anomaly.recent_approval_rate}% 
                            (Geçmiş: ${anomaly.historical_approval_rate}%)
                            <br><small>Düşüş: ${anomaly.drop_percentage}%</small>
                        </div>
                    `;
                });
            }
            
            // System anomalies
            if (data.system && data.system.length > 0) {
                html += '<h4><i class="fas fa-server"></i> Sistem Anomalileri</h4>';
                data.system.forEach(anomaly => {
                    html += `
                        <div class="anomaly-item anomaly-info">
                            <strong>${anomaly.type}</strong>: ${anomaly.message}
                        </div>
                    `;
                });
            }
            
            if (html === '') {
                html = '<p class="text-success"><i class="fas fa-check-circle"></i> Anomali tespit edilmedi.</p>';
            }
            
            document.getElementById('anomalies-list').innerHTML = html;
        });
}

function loadWarehouseData() {
    fetch('/api/ai-ml/warehouse-optimization')
        .then(response => response.json())
        .then(data => {
            let html = '';
            data.optimization_data.forEach(warehouse => {
                html += `
                    <div class="maintenance-item">
                        <h5>${warehouse.warehouse_name}</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Kullanım:</strong> ${warehouse.current_utilization}%
                            </div>
                            <div class="col-md-3">
                                <strong>Verimlilik:</strong> ${warehouse.efficiency_score}%
                            </div>
                            <div class="col-md-3">
                                <strong>Tahmini Talep:</strong> ${warehouse.predicted_demand.daily_average}/gün
                            </div>
                            <div class="col-md-3">
                                <strong>Durum:</strong> 
                                <span class="badge badge-${warehouse.efficiency_score > 70 ? 'success' : 'warning'}">
                                    ${warehouse.efficiency_score > 70 ? 'İyi' : 'İyileştirme Gerekli'}
                                </span>
                            </div>
                        </div>
                        ${warehouse.recommendations.length > 0 ? `
                            <div class="mt-2">
                                <strong>Öneriler:</strong>
                                <ul class="mb-0">
                                    ${warehouse.recommendations.map(rec => `<li>${rec}</li>`).join('')}
                                </ul>
                            </div>
                        ` : ''}
                    </div>
                `;
            });
            document.getElementById('warehouse-optimization').innerHTML = html;
        });
}

function loadMaintenanceData() {
    fetch('/api/ai-ml/maintenance-predictions')
        .then(response => response.json())
        .then(data => {
            let html = '';
            data.maintenance_predictions.forEach(maintenance => {
                const urgencyClass = maintenance.maintenance_urgency === 'critical' ? 'urgent' :
                                   maintenance.maintenance_urgency === 'high' ? 'high' :
                                   maintenance.maintenance_urgency === 'medium' ? 'medium' : 'low';
                
                html += `
                    <div class="maintenance-item maintenance-${urgencyClass}">
                        <h5>${maintenance.kiln_name}</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Sağlık Skoru:</strong> ${maintenance.current_health_score}%
                            </div>
                            <div class="col-md-3">
                                <strong>Sonraki Bakım:</strong> ${maintenance.next_maintenance_date}
                            </div>
                            <div class="col-md-3">
                                <strong>Arıza Riski:</strong> ${maintenance.failure_probability}%
                            </div>
                            <div class="col-md-3">
                                <strong>Aciliyet:</strong> 
                                <span class="badge badge-${maintenance.maintenance_urgency === 'critical' ? 'danger' : 
                                                       maintenance.maintenance_urgency === 'high' ? 'warning' : 'info'}">
                                    ${maintenance.maintenance_urgency.toUpperCase()}
                                </span>
                            </div>
                        </div>
                        ${maintenance.recommended_actions.length > 0 ? `
                            <div class="mt-2">
                                <strong>Önerilen Aksiyonlar:</strong>
                                <ul class="mb-0">
                                    ${maintenance.recommended_actions.map(action => `<li>${action}</li>`).join('')}
                                </ul>
                            </div>
                        ` : ''}
                    </div>
                `;
            });
            document.getElementById('maintenance-predictions').innerHTML = html;
        });
}

function loadCustomerData() {
    fetch('/api/ai-ml/customer-analysis')
        .then(response => response.json())
        .then(data => {
            let html = '';
            
            // Customer segments
            Object.keys(data.segmentation).forEach(segment => {
                const customers = data.segmentation[segment];
                if (customers.length > 0) {
                    html += `
                        <div class="customer-segment">
                            <div class="segment-title">${getSegmentTitle(segment)}</div>
                            <div class="segment-stats">
                                <div class="segment-stat">
                                    <div class="segment-value">${customers.length}</div>
                                    <div class="segment-label">Müşteri Sayısı</div>
                                </div>
                                <div class="segment-stat">
                                    <div class="segment-value">${calculateAverageCLV(customers)}</div>
                                    <div class="segment-label">Ortalama CLV</div>
                                </div>
                                <div class="segment-stat">
                                    <div class="segment-value">${calculateChurnRate(customers)}%</div>
                                    <div class="segment-label">Churn Riski</div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            });
            
            // Retention strategies
            if (data.retention_strategies.length > 0) {
                html += '<h4 class="mt-4">Müşteri Tutma Stratejileri</h4>';
                data.retention_strategies.forEach(strategy => {
                    html += `
                        <div class="maintenance-item">
                            <h5>${strategy.type}</h5>
                            <p>Hedef: ${strategy.target} (${strategy.count} müşteri)</p>
                            <strong>Aksiyonlar:</strong>
                            <ul>
                                ${strategy.actions.map(action => `<li>${action}</li>`).join('')}
                            </ul>
                        </div>
                    `;
                });
            }
            
            document.getElementById('customer-analysis').innerHTML = html;
        });
}

function displayCriticalAlerts(data) {
    let html = '';
    let criticalCount = 0;
    
    // Count critical alerts
    if (data.production) criticalCount += data.production.length;
    if (data.quality) criticalCount += data.quality.length;
    if (data.system) criticalCount += data.system.length;
    
    if (criticalCount === 0) {
        html = '<p class="text-success"><i class="fas fa-check-circle"></i> Kritik uyarı bulunmuyor.</p>';
    } else {
        html = `<p class="text-warning"><i class="fas fa-exclamation-triangle"></i> ${criticalCount} kritik uyarı tespit edildi.</p>`;
    }
    
    document.getElementById('critical-alerts').innerHTML = html;
}

function displayCustomerSegments(data) {
    let html = '';
    Object.keys(data).forEach(segment => {
        const customers = data[segment];
        if (customers.length > 0) {
            html += `
                <div class="customer-segment">
                    <div class="segment-title">${getSegmentTitle(segment)}</div>
                    <div class="segment-stats">
                        <div class="segment-stat">
                            <div class="segment-value">${customers.length}</div>
                            <div class="segment-label">Müşteri</div>
                        </div>
                        <div class="segment-stat">
                            <div class="segment-value">${calculateAverageCLV(customers)}</div>
                            <div class="segment-label">Ort. CLV</div>
                        </div>
                    </div>
                </div>
            `;
        }
    });
    document.getElementById('customer-segments').innerHTML = html;
}

function createForecastChart(elementId, forecastData) {
    const ctx = document.getElementById(elementId);
    if (!ctx) return;
    
    // Destroy existing chart if it exists
    if (charts[elementId]) {
        charts[elementId].destroy();
    }
    
    const labels = forecastData.map(item => item.date);
    const data = forecastData.map(item => item.predicted_production);
    const lowerBound = forecastData.map(item => item.confidence_interval.lower);
    const upperBound = forecastData.map(item => item.confidence_interval.upper);
    
    charts[elementId] = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Tahmin',
                data: data,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                fill: true,
                tension: 0.4
            }, {
                label: 'Güven Aralığı (Alt)',
                data: lowerBound,
                borderColor: 'rgba(102, 126, 234, 0.3)',
                backgroundColor: 'transparent',
                borderDash: [5, 5],
                fill: false
            }, {
                label: 'Güven Aralığı (Üst)',
                data: upperBound,
                borderColor: 'rgba(102, 126, 234, 0.3)',
                backgroundColor: 'transparent',
                borderDash: [5, 5],
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Üretim Tahmini (30 Gün)'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Üretim Miktarı'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tarih'
                    }
                }
            }
        }
    });
}

function getSegmentTitle(segment) {
    const titles = {
        'high_value': 'Yüksek Değerli Müşteriler',
        'medium_value': 'Orta Değerli Müşteriler',
        'low_value': 'Düşük Değerli Müşteriler',
        'at_risk': 'Risk Altındaki Müşteriler'
    };
    return titles[segment] || segment;
}

function calculateAverageCLV(customers) {
    if (customers.length === 0) return 0;
    const totalCLV = customers.reduce((sum, customer) => sum + customer.lifetime_value, 0);
    return Math.round(totalCLV / customers.length);
}

function calculateChurnRate(customers) {
    if (customers.length === 0) return 0;
    const highRiskCount = customers.filter(customer => customer.churn_risk === 'high').length;
    return Math.round((highRiskCount / customers.length) * 100);
}

function refreshData() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Yenileniyor...';
    button.disabled = true;
    
    // Reload current tab data
    loadTabData(currentTab);
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    }, 2000);
}

// Load initial data when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadOverviewData();
});
</script>
@endsection
