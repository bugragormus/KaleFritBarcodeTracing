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

    .date-selector {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 1rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .date-selector label {
        color: white;
        font-weight: 600;
        margin-right: 1rem;
        font-size: 1.1rem;
    }

    .date-selector input {
        background: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 12px;
        padding: 0.75rem 1.25rem;
        color: #333;
        font-weight: 500;
        font-size: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        min-width: 200px;
    }

    .date-selector input:focus {
        outline: none;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .date-selector input:hover {
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.12);
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
    }

    .btn-success i {
        margin-right: 0.5rem;
    }

    /* New Dashboard Components Styles */
    .shift-efficiency-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        transition: all 0.3s ease;
    }

    .shift-efficiency-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
    }

    .shift-header h5 {
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .shift-header small {
        opacity: 0.8;
        font-size: 0.9rem;
    }

    .shift-metrics {
        margin-top: 1rem;
    }

    .metric {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .metric:last-child {
        border-bottom: none;
    }

    .metric-label {
        font-weight: 500;
    }

    .metric-value {
        font-weight: 700;
        font-size: 1.1rem;
    }

    .comparison-grid {
        display: grid;
        gap: 1rem;
    }

    .comparison-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: rgba(102, 126, 234, 0.1);
        border-radius: 10px;
        border-left: 4px solid #667eea;
    }

    .comparison-label {
        font-weight: 600;
        color: #333;
    }

    .comparison-value {
        font-weight: 700;
        font-size: 1.1rem;
    }

    .detailed-numbers {
        display: grid;
        gap: 1rem;
    }

    .number-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: rgba(118, 75, 162, 0.1);
        border-radius: 10px;
        border-left: 4px solid #764ba2;
    }

    .number-label {
        font-weight: 600;
        color: #333;
    }

    .number-value {
        font-weight: 700;
        color: #764ba2;
    }

    .kpi-grid {
        display: grid;
        gap: 1rem;
    }

    .kpi-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: rgba(40, 167, 69, 0.1);
        border-radius: 10px;
        border-left: 4px solid #28a745;
    }

    .kpi-label {
        font-weight: 600;
        color: #333;
    }

    .kpi-value {
        font-weight: 700;
        color: #28a745;
    }

    .kpi-achievement {
        font-weight: 700;
        font-size: 1.1rem;
    }

    .actual-values {
        display: grid;
        gap: 1rem;
    }

    .actual-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: rgba(255, 193, 7, 0.1);
        border-radius: 10px;
        border-left: 4px solid #ffc107;
    }

    .actual-label {
        font-weight: 600;
        color: #333;
    }

    .actual-value {
        font-weight: 700;
        color: #ffc107;
    }

    .activity-summary {
        display: grid;
        gap: 1rem;
    }

    .activity-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: rgba(220, 53, 69, 0.1);
        border-radius: 10px;
        border-left: 4px solid #dc3545;
    }

    .activity-label {
        font-weight: 600;
        color: #333;
    }

    .activity-value {
        font-weight: 700;
        color: #dc3545;
    }

    .recent-activities {
        display: grid;
        gap: 0.75rem;
    }

    .activity-log {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: rgba(23, 162, 184, 0.1);
        border-radius: 10px;
        border-left: 4px solid #17a2b8;
        font-size: 0.9rem;
    }

    .activity-user {
        font-weight: 600;
        color: #17a2b8;
        min-width: 100px;
    }

    .activity-action {
        color: #333;
        flex: 1;
        margin: 0 1rem;
    }

    .activity-time {
        font-weight: 600;
        color: #6c757d;
        min-width: 60px;
        text-align: right;
    }

    /* Progress Bar Enhancements */
    .progress {
        background-color: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar {
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.8rem;
        line-height: 20px;
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
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .shift-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .shift-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    .shift-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
    }

    .shift-name {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.8rem;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .shift-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.2rem;
    }

    .shift-stat {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }

    .shift-stat:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.05);
    }

    .shift-stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .shift-stat-label {
        font-size: 0.9rem;
        opacity: 0.95;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .warning-card {
        border-left: 4px solid;
        margin-bottom: 1rem;
    }

    .warning-critical {
        border-left-color: #dc3545;
        background: #f8d7da;
    }

    .warning-warning {
        border-left-color: #ffc107;
        background: #fff3cd;
    }

    .warning-info {
        border-left-color: #17a2b8;
        background: #d1ecf1;
    }

    .chart-container {
        height: 300px;
        margin: 1rem 0;
    }

    .table-modern {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .table-modern th {
        background: #f8f9fa;
        border: none;
        padding: 1rem;
        font-weight: 600;
        color: #495057;
    }

    .table-modern td {
        border: none;
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    .badge-modern {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-danger {
        background: #f8d7da;
        color: #721c24;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .badge-info {
        background: #d1ecf1;
        color: #0c5460;
    }
</style>
@endsection

@section('content')
<div class="modern-dashboard">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title-modern">
                        <i class="fas fa-chart-bar"></i>
                        Günlük Üretim Raporu
                    </h1>
                    <p class="page-subtitle-modern">Sistem geneli istatistikler ve performans göstergeleri</p>
                </div>
                
                <!-- Date Selector -->
                <div class="date-selector">
                    <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center">
                        <label for="date">📅 Rapor Tarihi (Bugün):</label>
                        <input type="date" id="date" name="date" value="{{ $selectedDate }}" 
                               class="form-control" onchange="this.form.submit()">
                    </form>
                </div>
            </div>
        </div>

        <!-- Daily Production Stats -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-calendar-day"></i>
                    {{ $date->format('d.m.Y') }} Günlük Üretim Özeti
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($dailyProduction['total_barcodes']) }}</div>
                        <div class="stat-label">Toplam Barkod</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($dailyProduction['total_quantity']) }}</div>
                        <div class="stat-label">Toplam Miktar</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($dailyProduction['accepted_barcodes']) }}</div>
                        <div class="stat-label">Kabul Edilen Barkod Sayısı</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($dailyProduction['rejected_barcodes']) }}</div>
                        <div class="stat-label">Reddedilen Barkod Sayısı</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($dailyProduction['pending_barcodes']) }}</div>
                        <div class="stat-label">Bekleyen</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shift Report -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-clock"></i>
                    Vardiya Raporu
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="shift-grid">
                                         @foreach($shiftReport as $shiftName => $shiftData)
                     <div class="shift-card">
                         <div class="shift-name">
                             @if($shiftName === 'gece')
                                 00:00 - 08:00
                             @elseif($shiftName === 'gündüz')
                                 08:00 - 16:00
                             @elseif($shiftName === 'akşam')
                                 16:00 - 00:00
                             @else
                                 {{ ucfirst($shiftName) }}
                             @endif
                         </div>
                        <div class="shift-stats">
                            <div class="shift-stat">
                                <div class="shift-stat-value">{{ number_format($shiftData['barcode_count']) }}</div>
                                <div class="shift-stat-label">Barkod</div>
                            </div>
                            <div class="shift-stat">
                                <div class="shift-stat-value">{{ number_format($shiftData['total_quantity']) }}</div>
                                <div class="shift-stat-label">Miktar</div>
                            </div>
                            <div class="shift-stat">
                                <div class="shift-stat-value">{{ number_format($shiftData['accepted_count']) }}</div>
                                <div class="shift-stat-label">Kabul</div>
                            </div>
                            <div class="shift-stat">
                                <div class="shift-stat-value">{{ number_format($shiftData['rejected_count']) }}</div>
                                <div class="shift-stat-label">Red</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Kiln Performance -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title-modern">
                        <i class="fas fa-fire"></i>
                        Fırın Performans Analizi
                    </h3>
                    <button class="btn btn-success btn-sm" onclick="exportKilnPerformance(event)">
                        <i class="fas fa-file-excel"></i> CSV İndir
                    </button>
                </div>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Fırın Adı</th>
                                <th>Barkod Sayısı</th>
                                <th>Toplam Miktar</th>
                                <th>Ortalama Miktar</th>
                                <th>Kabul Edilen Barkod Sayısı</th>
                                <th>Reddedilen Barkod Sayısı</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kilnPerformance as $kiln)
                            <tr>
                                <td><strong>{{ $kiln->kiln_name }}</strong></td>
                                <td>{{ number_format($kiln->barcode_count) }}</td>
                                <td>{{ number_format($kiln->total_quantity) }}</td>
                                <td>{{ number_format($kiln->avg_quantity, 1) }}</td>
                                <td><span class="badge badge-success">{{ number_format($kiln->accepted_count) }}</span></td>
                                <td><span class="badge badge-danger">{{ number_format($kiln->rejected_count) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kiln Rejection Rates -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-exclamation-triangle"></i>
                    Fırın Red Oranları
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Fırın Adı</th>
                                <th>Toplam Barkod</th>
                                <th>Reddedilen</th>
                                <th>Red Oranı (%)</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kilnRejectionRates as $kiln)
                            <tr>
                                <td><strong>{{ $kiln->kiln_name }}</strong></td>
                                <td>{{ number_format($kiln->total_barcodes) }}</td>
                                <td>{{ number_format($kiln->rejected_count) }}</td>
                                <td><strong>{{ $kiln->rejection_rate }}%</strong></td>
                                <td>
                                    @if($kiln->rejection_rate <= 5)
                                        <span class="badge badge-success">Düşük</span>
                                    @elseif($kiln->rejection_rate <= 15)
                                        <span class="badge badge-warning">Orta</span>
                                    @else
                                        <span class="badge badge-danger">Yüksek</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Stock Age Warnings -->
        @if(!empty($stockAgeWarnings))
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-clock"></i>
                    Stok Yaşı Uyarıları
                </h3>
            </div>
            <div class="card-body-modern">
                @if(isset($stockAgeWarnings['critical']))
                <h5 class="text-danger mb-3">🚨 Kritik Uyarı (30+ gün)</h5>
                @foreach($stockAgeWarnings['critical'] as $stock)
                <div class="warning-card warning-critical p-3">
                    <strong>{{ $stock->name }}</strong> ({{ $stock->code }}) - 
                    Son üretim: {{ $stock->last_production_date ? \Carbon\Carbon::parse($stock->last_production_date)->format('d.m.Y') : 'Hiç üretilmemiş' }}
                    @if($stock->last_production_date)
                        - <span class="text-danger">{{ $stock->days_old }} gün önce</span>
                    @endif
                </div>
                @endforeach
                @endif

                @if(isset($stockAgeWarnings['warning']))
                <h5 class="text-warning mb-3 mt-4">⚠️ Uyarı (15+ gün)</h5>
                @foreach($stockAgeWarnings['warning'] as $stock)
                <div class="warning-card warning-warning p-3">
                    <strong>{{ $stock->name }}</strong> ({{ $stock->code }}) - 
                    Son üretim: {{ \Carbon\Carbon::parse($stock->last_production_date)->format('d.m.Y') }} 
                    - <span class="text-warning">{{ $stock->days_old }} gün önce</span>
                </div>
                @endforeach
                @endif

                @if(isset($stockAgeWarnings['info']))
                <h5 class="text-info mb-3 mt-4">ℹ️ Bilgi (7+ gün)</h5>
                @foreach($stockAgeWarnings['info'] as $stock)
                <div class="warning-card warning-info p-3">
                    <strong>{{ $stock->name }}</strong> ({{ $stock->code }}) - 
                    Son üretim: {{ \Carbon\Carbon::parse($stock->last_production_date)->format('d.m.Y') }} 
                    - <span class="text-info">{{ $stock->days_old }} gün önce</span>
                </div>
                @endforeach
                @endif
            </div>
        </div>
        @endif

        <!-- Product Kiln Analysis -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-chart-line"></i>
                    Ürün Özelinde Fırın Kapasite Analizi
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Ürün</th>
                                <th>Fırın</th>
                                <th>Barkod Sayısı</th>
                                <th>Toplam Miktar</th>
                                <th>Kabul Edilen</th>
                                <th>Reddedilen</th>
                                <th>Kabul Oranı (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productKilnAnalysis as $analysis)
                            <tr>
                                <td><strong>{{ $analysis->stock_name }}</strong><br><small>{{ $analysis->stock_code }}</small></td>
                                <td>{{ $analysis->kiln_name }}</td>
                                <td>{{ number_format($analysis->barcode_count) }}</td>
                                <td>{{ number_format($analysis->total_quantity) }}</td>
                                <td><span class="badge badge-success">{{ number_format($analysis->accepted_count) }}</span></td>
                                <td><span class="badge badge-danger">{{ number_format($analysis->rejected_count) }}</span></td>
                                <td>
                                    <span class="badge badge-{{ $analysis->acceptance_rate >= 90 ? 'success' : ($analysis->acceptance_rate >= 75 ? 'warning' : 'danger') }}">
                                        {{ $analysis->acceptance_rate }}%
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Weekly Trend Chart -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-chart-area"></i>
                    Haftalık Üretim Trendi
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="chart-container">
                    <canvas id="weeklyTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Comparison -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-balance-scale"></i>
                    Aylık Karşılaştırma
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Bu Ay</h5>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-value">{{ number_format($monthlyComparison['current_month']['total_barcodes']) }}</div>
                                <div class="stat-label">Barkod</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-value">{{ number_format($monthlyComparison['current_month']['total_quantity']) }}</div>
                                <div class="stat-label">Miktar</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Geçen Ay</h5>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-value">{{ number_format($monthlyComparison['previous_month']['total_barcodes']) }}</div>
                                <div class="stat-label">Barkod</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-value">{{ number_format($monthlyComparison['previous_month']['total_quantity']) }}</div>
                                <div class="stat-label">Miktar</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6>Değişim Oranları:</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-value {{ $monthlyComparison['change_percentage']['total_barcodes'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $monthlyComparison['change_percentage']['total_barcodes'] >= 0 ? '+' : '' }}{{ $monthlyComparison['change_percentage']['total_barcodes'] }}%
                                </div>
                                <div class="stat-label">Barkod Değişimi</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-value {{ $monthlyComparison['change_percentage']['total_quantity'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $monthlyComparison['change_percentage']['total_quantity'] >= 0 ? '+' : '' }}{{ $monthlyComparison['change_percentage']['total_quantity'] }}%
                                </div>
                                <div class="stat-label">Miktar Değişimi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Production Efficiency - Kiln Capacity Utilization -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-tachometer-alt"></i>
                    Üretim Verimliliği - Fırın Kapasite Kullanımı
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Fırın Adı</th>
                                <th>Teorik Kapasite</th>
                                <th>Gerçek Üretim</th>
                                <th>Kapasite Kullanımı (%)</th>
                                <th>Miktar Kullanımı (%)</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productionEfficiency as $kiln)
                            <tr>
                                <td><strong>{{ $kiln->kiln_name }}</strong></td>
                                <td>{{ number_format($kiln->theoretical_capacity) }}</td>
                                <td>{{ number_format($kiln->actual_production) }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar {{ $kiln->capacity_utilization >= 80 ? 'bg-success' : ($kiln->capacity_utilization >= 60 ? 'bg-warning' : 'bg-danger') }}" 
                                             style="width: {{ min($kiln->capacity_utilization, 100) }}%">
                                            {{ $kiln->capacity_utilization }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar {{ $kiln->quantity_utilization >= 80 ? 'bg-success' : ($kiln->quantity_utilization >= 60 ? 'bg-warning' : 'bg-danger') }}" 
                                             style="width: {{ min($kiln->quantity_utilization, 100) }}%">
                                            {{ $kiln->quantity_utilization }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($kiln->capacity_utilization >= 80)
                                        <span class="badge badge-success">Yüksek</span>
                                    @elseif($kiln->capacity_utilization >= 60)
                                        <span class="badge badge-warning">Orta</span>
                                    @else
                                        <span class="badge badge-danger">Düşük</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Stock ABC Analysis -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-chart-pie"></i>
                    Stok ABC Analizi (Son 30 Gün)
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Ürün</th>
                                <th>Kullanım Sıklığı</th>
                                <th>Toplam Miktar</th>
                                <th>Kullanım Yüzdesi</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockABC as $stock)
                            <tr>
                                <td>
                                    <strong>{{ $stock->name }}</strong><br>
                                    <small>{{ $stock->code }}</small>
                                </td>
                                <td>{{ number_format($stock->usage_frequency) }}</td>
                                <td>{{ number_format($stock->total_quantity) }}</td>
                                <td>{{ $stock->usage_percentage }}%</td>
                                <td>
                                    @if($stock->usage_percentage >= 20)
                                        <span class="badge badge-danger">A (Kritik)</span>
                                    @elseif($stock->usage_percentage >= 5)
                                        <span class="badge badge-warning">B (Önemli)</span>
                                    @else
                                        <span class="badge badge-success">C (Normal)</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Shift Efficiency Analysis -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-users"></i>
                    Vardiya Verimlilik Analizi
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="row">
                    @foreach($shiftEfficiency as $shiftKey => $shift)
                    <div class="col-md-4">
                        <div class="shift-efficiency-card">
                            <div class="shift-header">
                                <h5>{{ $shift['name'] }}</h5>
                                <small>{{ $shift['start'] }} - {{ $shift['end'] }}</small>
                            </div>
                            <div class="shift-metrics">
                                <div class="metric">
                                    <span class="metric-label">Barkod Sayısı:</span>
                                    <span class="metric-value">{{ number_format($shift['total_barcodes']) }}</span>
                                </div>
                                <div class="metric">
                                    <span class="metric-label">Toplam Miktar:</span>
                                    <span class="metric-value">{{ number_format($shift['total_quantity']) }}</span>
                                </div>
                                <div class="metric">
                                    <span class="metric-label">Kabul Oranı:</span>
                                    <span class="metric-value {{ $shift['acceptance_rate'] >= 90 ? 'text-success' : ($shift['acceptance_rate'] >= 75 ? 'text-warning' : 'text-danger') }}">
                                        {{ $shift['acceptance_rate'] }}%
                                    </span>
                                </div>
                                <div class="metric">
                                    <span class="metric-label">Red Oranı:</span>
                                    <span class="metric-value {{ $shift['rejection_rate'] <= 5 ? 'text-success' : ($shift['rejection_rate'] <= 15 ? 'text-warning' : 'text-danger') }}">
                                        {{ $shift['rejection_rate'] }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Time Comparison -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-balance-scale"></i>
                    Günlük Karşılaştırma
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Bugün vs Dün</h6>
                        <div class="comparison-grid">
                            <div class="comparison-item">
                                <span class="comparison-label">Barkod Sayısı:</span>
                                <span class="comparison-value {{ $timeComparison['changes']['barcodes'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $timeComparison['changes']['barcodes'] >= 0 ? '+' : '' }}{{ $timeComparison['changes']['barcodes'] }}%
                                </span>
                            </div>
                            <div class="comparison-item">
                                <span class="comparison-label">Toplam Miktar:</span>
                                <span class="comparison-value {{ $timeComparison['changes']['quantity'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $timeComparison['changes']['quantity'] >= 0 ? '+' : '' }}{{ $timeComparison['changes']['quantity'] }}%
                                </span>
                            </div>
                            <div class="comparison-item">
                                <span class="comparison-label">Kabul Edilen:</span>
                                <span class="comparison-value {{ $timeComparison['changes']['accepted'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $timeComparison['changes']['accepted'] >= 0 ? '+' : '' }}{{ $timeComparison['changes']['accepted'] }}%
                                </span>
                            </div>
                            <div class="comparison-item">
                                <span class="comparison-label">Reddedilen:</span>
                                <span class="comparison-value {{ $timeComparison['changes']['rejected'] <= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $timeComparison['changes']['rejected'] <= 0 ? '' : '+' }}{{ $timeComparison['changes']['rejected'] }}%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Detaylı Sayılar</h6>
                        <div class="detailed-numbers">
                            <div class="number-item">
                                <span class="number-label">Bugün:</span>
                                <span class="number-value">{{ number_format($timeComparison['current']['total_barcodes']) }} barkod, {{ number_format($timeComparison['current']['total_quantity']) }} miktar</span>
                            </div>
                            <div class="number-item">
                                <span class="number-label">Dün:</span>
                                <span class="number-value">{{ number_format($timeComparison['previous']['total_barcodes']) }} barkod, {{ number_format($timeComparison['previous']['total_quantity']) }} miktar</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Metrics -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-bullseye"></i>
                    KPI ve Hedef Takibi
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Aylık Hedefler</h6>
                        <div class="kpi-grid">
                            <div class="kpi-item">
                                <span class="kpi-label">Barkod Hedefi:</span>
                                <span class="kpi-value">{{ number_format($kpiMetrics['targets']['monthly_barcodes']) }}</span>
                                <span class="kpi-achievement {{ $kpiMetrics['achievement']['barcodes'] >= 100 ? 'text-success' : 'text-warning' }}">
                                    {{ $kpiMetrics['achievement']['barcodes'] }}%
                                </span>
                            </div>
                            <div class="kpi-item">
                                <span class="kpi-label">Miktar Hedefi:</span>
                                <span class="kpi-value">{{ number_format($kpiMetrics['targets']['monthly_quantity']) }}</span>
                                <span class="kpi-achievement {{ $kpiMetrics['achievement']['quantity'] >= 100 ? 'text-success' : 'text-warning' }}">
                                    {{ $kpiMetrics['achievement']['quantity'] }}%
                                </span>
                            </div>
                            <div class="kpi-item">
                                <span class="kpi-label">Kabul Oranı Hedefi:</span>
                                <span class="kpi-value">{{ $kpiMetrics['targets']['acceptance_rate'] }}%</span>
                                <span class="kpi-achievement {{ $kpiMetrics['achievement']['acceptance'] >= 100 ? 'text-success' : 'text-warning' }}">
                                    {{ $kpiMetrics['achievement']['acceptance'] }}%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Gerçekleşen Değerler</h6>
                        <div class="actual-values">
                            <div class="actual-item">
                                <span class="actual-label">Aylık Barkod:</span>
                                <span class="actual-value">{{ number_format($kpiMetrics['actual']['monthly_barcodes']) }}</span>
                            </div>
                            <div class="actual-item">
                                <span class="actual-label">Aylık Miktar:</span>
                                <span class="actual-value">{{ number_format($kpiMetrics['actual']['monthly_quantity']) }}</span>
                            </div>
                            <div class="actual-item">
                                <span class="actual-label">Kabul Oranı:</span>
                                <span class="actual-value {{ $kpiMetrics['actual']['acceptance_rate'] >= 95 ? 'text-success' : 'text-warning' }}">
                                    {{ $kpiMetrics['actual']['acceptance_rate'] }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Activity Logs -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-shield-alt"></i>
                    Güvenlik ve Audit - Kullanıcı Aktiviteleri
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Günlük Aktivite Özeti</h6>
                        <div class="activity-summary">
                            <div class="activity-item">
                                <span class="activity-label">Toplam Aktivite:</span>
                                <span class="activity-value">{{ number_format($userActivityLogs['total_activities']) }}</span>
                            </div>
                            <div class="activity-item">
                                <span class="activity-label">Giriş Sayısı:</span>
                                <span class="activity-value">{{ number_format($userActivityLogs['login_count']) }}</span>
                            </div>
                            <div class="activity-item">
                                <span class="activity-label">Veri Değişikliği:</span>
                                <span class="activity-value">{{ number_format($userActivityLogs['data_changes']) }}</span>
                            </div>
                            <div class="activity-item">
                                <span class="activity-label">Export İşlemleri:</span>
                                <span class="activity-value">{{ number_format($userActivityLogs['export_activities']) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Son Aktiviteler</h6>
                        <div class="recent-activities">
                            @foreach($userActivityLogs['recent_activities'] as $activity)
                            <div class="activity-log">
                                <span class="activity-user">{{ $activity['user'] }}</span>
                                <span class="activity-action">{{ $activity['action'] }}</span>
                                <span class="activity-time">{{ $activity['time'] }}</span>
                            </div>
                            @endforeach
                        </div>
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
document.addEventListener('DOMContentLoaded', function() {
    // Weekly Trend Chart
    const weeklyTrendCtx = document.getElementById('weeklyTrendChart').getContext('2d');
    const weeklyTrendData = @json($weeklyTrend);
    
    const labels = weeklyTrendData.map(item => item.date);
    const barcodeData = weeklyTrendData.map(item => item.barcode_count);
    const quantityData = weeklyTrendData.map(item => item.total_quantity);
    
    new Chart(weeklyTrendCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Barkod Sayısı',
                data: barcodeData,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                yAxisID: 'y'
            }, {
                label: 'Toplam Miktar',
                data: quantityData,
                borderColor: '#764ba2',
                backgroundColor: 'rgba(118, 75, 162, 0.1)',
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
                        text: 'Barkod Sayısı'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Miktar'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Haftalık Üretim Trendi'
                }
            }
        }
    });
});

// Excel export function for kiln performance
function exportKilnPerformance() {
    const selectedDate = document.getElementById('date').value;
    
    // Loading göster
    const exportBtn = event.target;
    const originalText = exportBtn.innerHTML;
    exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> İndiriliyor...';
    exportBtn.disabled = true;
    
    // CSRF token ve credentials ekle
    fetch(`{{ route('dashboard.export-kiln-performance') }}`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            date: selectedDate
        })
    })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (!data.success) {
                throw new Error(data.error || 'Unknown error occurred');
            }
            
            if (!data.data || !Array.isArray(data.data)) {
                throw new Error('Invalid data format received');
            }
            
            // Create CSV content with proper encoding
            let csvContent = "data:text/csv;charset=utf-8,\uFEFF";
            
            // Add headers
            csvContent += "Fırın Adı,Barkod Sayısı,Toplam Miktar,Ortalama Miktar,Kabul Edilen,Reddedilen\n";
            
            // Add data rows with proper escaping
            data.data.forEach(kiln => {
                const row = [
                    `"${kiln.kiln_name || ''}"`,
                    kiln.barcode_count || 0,
                    kiln.total_quantity || 0,
                    parseFloat(kiln.avg_quantity || 0).toFixed(1),
                    kiln.accepted_count || 0,
                    kiln.rejected_count || 0
                ].join(',');
                csvContent += row + '\n';
            });
            
            // Create download link
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", data.filename);
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Success message
            alert('Fırın performans raporu başarıyla indirildi!');
        })
        .catch(error => {
            console.error('Export error:', error);
            alert('Export sırasında bir hata oluştu: ' + error.message);
        })
        .finally(() => {
            // Button'u eski haline getir
            exportBtn.innerHTML = originalText;
            exportBtn.disabled = false;
        });
}
</script>
@endsection
