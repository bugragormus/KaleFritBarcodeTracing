@extends('layouts.granilya')

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
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        color: white;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
    }
    
    .page-title-section {
        flex: 1;
    }
    
    .page-title-modern {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.4rem;
        display: flex;
        align-items: center;
    }
    
    .page-title-modern i {
        margin-right: 0.8rem;
        font-size: 1.6rem;
    }
    
    .page-subtitle-modern {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    /* Filtreler Bölümü Stilleri */
    .filters-section {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 1.25rem;
        margin: 1.25rem 0;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        overflow: visible;
    }

    .filters-container {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        overflow: visible;
    }

    .filter-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-start;
        overflow: visible;
    }

    .filter-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        min-height: 50px;
        padding: 0.25rem 0;
    }

    .filter-item label {
        color: #1a1a1a;
        font-weight: 700;
        font-size: 1rem;
        white-space: nowrap;
        line-height: 1.2;
        display: flex;
        align-items: center;
        height: 100%;
        margin: 0;
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
        background: rgba(255, 255, 255, 0.9);
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .filter-item input[type="date"],
    .filter-item select {
        background: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 10px;
        padding: 0.6rem 0.8rem;
        color: #333;
        font-weight: 500;
        font-size: 0.95rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        min-width: 130px;
        height: 40px;
        line-height: 1.2;
        vertical-align: middle;
    }

    .filter-item input[type="date"]:focus,
    .filter-item select:focus {
        outline: none;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .filter-item input[type="date"]:hover,
    .filter-item select:hover {
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.12);
    }
    
    .filter-item select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 40px;
        cursor: pointer;
    }
    
    .filter-item select option {
        padding: 8px 12px;
        font-size: 0.95rem;
        line-height: 1.4;
        background: white;
        color: #333;
    }

    .custom-date-filter {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .custom-date-filter .btn-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        white-space: nowrap;
    }

    .custom-date-filter .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    }

    .filter-info {
        text-align: center;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .filter-info small {
        color: #2c3e50;
        font-size: 0.9rem;
        line-height: 1.4;
        font-weight: 500;
        background: rgba(255, 255, 255, 0.95);
        padding: 0.6rem 1rem;
        border-radius: 10px;
        display: inline-block;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .filter-info small:hover {
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }

    .btn-warning { background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); border: none; border-radius: 10px; padding: 0.5rem 1rem; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3); color: white; }
    .btn-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border: none; border-radius: 10px; padding: 0.5rem 1rem; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3); color: white; }
    .btn-success:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4); background: linear-gradient(135deg, #218838 0%, #1ea085 100%); }
    .btn-info { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); border: none; border-radius: 10px; padding: 0.5rem 1rem; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3); color: white; }

    .card-modern {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .card-header-modern {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e9ecef;
        position: relative;
    }

    .card-header-modern::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
        opacity: 0.7;
    }
    
    .card-title-modern {
        font-size: 1.3rem;
        font-weight: 700;
        color: #495057;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }
    
    .card-title-modern i {
        margin-right: 0.8rem;
        color: #667eea;
        font-size: 1.2rem;
        background: rgba(102, 126, 234, 0.1);
        padding: 0.8rem;
        border-radius: 12px;
        border: 2px solid rgba(102, 126, 234, 0.2);
    }
    
    .card-body-modern {
        padding: 1.5rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .shift-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .shift-card {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 5px 15px rgba(44, 62, 80, 0.2);
        position: relative;
        overflow: hidden;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .shift-summary {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        border: 1px solid #dee2e6;
    }

    .summary-item {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        padding: 1rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e9ecef;
    }

    .summary-item i {
        font-size: 1.2rem;
        color: #667eea;
        background: rgba(102, 126, 234, 0.1);
        padding: 0.8rem;
        border-radius: 10px;
        border: 2px solid rgba(102, 126, 234, 0.2);
    }

    .summary-item span {
        font-weight: 600;
        color: #495057;
        font-size: 0.95rem;
    }

    .shift-name {
        font-size: 1.3rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        background: rgba(255, 255, 255, 0.15);
        padding: 1rem 1.5rem;
        border-radius: 15px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        position: relative;
        margin-bottom: 1.5rem;
        width: 100%;
        text-align: center;
    }
    
    .shift-name i { margin-right: 0.5rem; }

    .shift-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        width: 100%;
        align-content: center;
    }

    .shift-stat {
        background: rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        padding: 1.2rem 0.8rem;
        border: 1px solid rgba(255, 255, 255, 0.25);
        position: relative;
        overflow: hidden;
        min-height: 90px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-width: 100px;
    }

    .shift-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        color: #ffffff;
        line-height: 1;
    }

    .shift-stat-label {
        font-size: 0.75rem;
        opacity: 0.9;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: rgba(255, 255, 255, 0.95);
        text-align: center;
        line-height: 1.2;
    }

    .table-modern {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
        width: 100%;
    }

    .table-modern th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: none;
        padding: 1.5rem 1.5rem;
        font-weight: 600;
        color: #495057;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #dee2e6;
    }

    .table-modern td {
        border: none;
        padding: 1.5rem;
        border-bottom: 1px solid #f1f3f4;
        font-size: 0.95rem;
        vertical-align: middle;
        transition: all 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .chart-container {
        height: 300px;
        margin: 1rem 0;
    }

    .badge-modern { padding: 0.6rem 1.2rem; border-radius: 25px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s ease; }
    .badge-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: 1px solid rgba(255,255,255,0.2); }
    .badge-danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; border: 1px solid rgba(255,255,255,0.2); }
    .badge-warning { background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); color: #212529; border: 1px solid rgba(255,255,255,0.2); }
    .badge-info { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; border: 1px solid rgba(255,255,255,0.2); }

    /* Monthly Comparison Styles */
    .monthly-comparison-overview { margin-bottom: 2rem; }
    .month-card { background: white; border-radius: 15px; padding: 1.5rem; display: flex; align-items: center; justify-content: space-between; border: 1px solid #e9ecef; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: all 0.3s ease; height: 100%; }
    .month-card.current-month { border-left: 5px solid #667eea; }
    .month-card.previous-month { border-left: 5px solid #6c757d; }
    .month-header { display: flex; align-items: center; gap: 1rem; }
    .month-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 1.5rem; }
    .current-month .month-icon { background: rgba(102, 126, 234, 0.1); color: #667eea; }
    .previous-month .month-icon { background: rgba(108, 117, 125, 0.1); color: #6c757d; }
    .month-title { font-size: 1.2rem; font-weight: 700; margin: 0; color: #495057; }
    .month-period { font-size: 0.9rem; color: #6c757d; margin: 0; }
    .month-stats { display: flex; gap: 1.5rem; }
    .month-stat-item { text-align: right; }
    .month-stat-value { font-size: 1.5rem; font-weight: 800; color: #343a40; line-height: 1.2; }
    .month-stat-label { font-size: 0.8rem; color: #6c757d; text-transform: uppercase; font-weight: 600; }
    .change-analysis-section { background: #f8f9fa; border-radius: 15px; border: 1px solid #e9ecef; padding: 1.5rem; }
    .change-metric-card { background: white; border-radius: 12px; padding: 1.2rem; height: 100%; display: flex; flex-direction: column; justify-content: space-between; border: 1px solid #e9ecef; box-shadow: 0 2px 8px rgba(0,0,0,0.03); }
    .metric-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; }
    .metric-icon { width: 45px; height: 45px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 1.2rem; }
    .metric-icon.production { background: rgba(102, 126, 234, 0.1); color: #667eea; }
    .metric-title { font-size: 1.1rem; font-weight: 700; color: #495057; margin: 0; }
    .metric-subtitle { font-size: 0.85rem; color: #6c757d; margin: 0; }
    .metric-body { flex: 1; display: flex; flex-direction: column; justify-content: center; }
    .change-value { display: flex; align-items: baseline; justify-content: center; gap: 0.3rem; margin-bottom: 0.5rem; }
    .change-sign { font-size: 1.5rem; font-weight: 700; }
    .change-number { font-size: 3rem; font-weight: 800; line-height: 1; }
    .change-unit { font-size: 1.5rem; font-weight: 700; }
    .positive-change { color: #28a745; }
    .negative-change { color: #dc3545; }
    .neutral-change { color: #6c757d; }
</style>
@endsection

@section('content')
<div class="modern-dashboard">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <div class="d-flex justify-content-between align-items-start">
                <div class="page-title-section">
                    <h1 class="page-title-modern">
                        <i class="fas fa-chart-bar"></i>
                        Granilya Üretim Raporu
                    </h1>
                    <p class="page-subtitle-modern">Granilya hattı geneli istatistikler ve Kırıcı (Crusher) bazlı analizler</p>
                </div>
            </div>
        </div>
        
        <!-- Date and Period Filters -->
        <div class="filters-section">
            <div class="filters-container">
                <div class="filter-group">
                    <div class="filter-item">
                        <form method="GET" action="{{ route('granilya.report') }}" class="d-flex align-items-center">
                            <label for="date">📅 Rapor Tarihi:</label>
                            <input type="date" id="date" name="date" value="{{ $selectedDate }}" 
                                   class="form-control ml-2" onchange="this.form.submit()">
                        </form>
                    </div>
                    
                    <div class="filter-item">
                        <form method="GET" action="{{ route('granilya.report') }}" class="d-flex align-items-center">
                            <label for="period">📊 Periyot:</label>
                            <select id="period" name="period" class="form-control ml-2" onchange="this.form.submit()">
                                <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Günlük</option>
                                <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Haftalık</option>
                                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Aylık</option>
                                <option value="quarterly" {{ $period === 'quarterly' ? 'selected' : '' }}>3 Aylık</option>
                                <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Yıllık</option>
                                <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Özel Tarih Aralığı</option>
                            </select>
                        </form>
                    </div>
                    
                    <!-- Custom Date Range Selector -->
                    <div class="filter-item custom-date-filter" id="customDateSelector" style="display: {{ $period === 'custom' ? 'block' : 'none' }};">
                        <form method="GET" action="{{ route('granilya.report') }}" class="d-flex align-items-center">
                            <label for="start_date">📅 Başlangıç:</label>
                            <input type="date" id="start_date" name="start_date" value="{{ $startDateStr ?? '' }}" 
                                   class="form-control ml-1 mr-1" max="{{ date('Y-m-d') }}">
                            <label for="end_date" class="ms-3">📅 Bitiş:</label>
                            <input type="date" id="end_date" name="end_date" value="{{ $endDateStr ?? '' }}" 
                                   class="form-control ml-1" max="{{ date('Y-m-d') }}">
                            <input type="hidden" name="period" value="custom">
                            <button type="submit" class="btn btn-primary btn-sm ms-3 ml-3">
                                <i class="fas fa-search"></i> Uygula
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="filter-info">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Seçilen periyoda göre veriler toplanır
                        @if($period === 'custom')
                            • Gelecekteki tarihler otomatik olarak bugüne sınırlanır
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <!-- Production Stats -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-calendar-day"></i>
                    {{ $periodInfo['range'] }} {{ $periodInfo['name'] }} Üretim Özeti
                </h3>
                <small class="text-muted mt-2 d-block">
                    <i class="fas fa-info-circle"></i> 
                    Periyot: {{ $periodInfo['start_date_formatted'] }} - {{ $periodInfo['end_date_formatted'] }}
                </small>
            </div>
            <div class="card-body-modern">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($productionData['total_barcodes']) }}</div>
                        <div class="stat-label">Toplam Palet</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($productionData['total_quantity'], 1) }}</div>
                        <div class="stat-label">Toplam Miktar (ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($productionData['accepted_quantity'], 1) }}</div>
                        <div class="stat-label">Kabul Edilen (ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($productionData['testing_quantity'], 1) }}</div>
                        <div class="stat-label">Test Sürecinde (ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($productionData['delivery_quantity'], 1) }}</div>
                        <div class="stat-label">Teslimat Sürecinde (ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($productionData['rejected_quantity'], 1) }}</div>
                        <div class="stat-label">Reddedilen (ton)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Red Sebepleri Analizi (Simplified vs DashboardController) -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title-modern">
                        <i class="fas fa-exclamation-triangle"></i>
                        Red Sebepleri Analizi
                    </h3>
                </div>
            </div>
            <div class="card-body-modern">
                <div class="row">
                    @forelse($rejectionReasonsAnalysis as $reasonData)
                        <div class="col-md-4 mb-3">
                            <div class="d-flex justify-content-between align-items-center p-3 border rounded">
                                <div>
                                    <span class="badge badge-danger">{{ $reasonData->reason_name }}</span>
                                    <div class="small text-muted mt-1">
                                        {{ $reasonData->count }} ürün ({{ number_format($reasonData->total_quantity, 1) }} KG)
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-danger font-weight-bold">
                                        {{ number_format($reasonData->percentage, 1) }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-success m-3">
                            <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                            Bu periyotta reddedilen ürün bulunmamaktadır.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Shift Report - Only for Daily Period -->
        @if($period === 'daily')
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title-modern">
                        <i class="fas fa-clock"></i>
                        Vardiya Raporu
                    </h3>
                </div>
                <small class="text-muted mt-2 d-block">
                    <i class="fas fa-info-circle"></i> 
                    Vardiya raporu sadece günlük periyotta mevcuttur
                </small>
            </div>
            <div class="card-body-modern">
                <!-- Shift Summary -->
                <div class="shift-summary">
                    <div class="summary-item">
                        <i class="fas fa-calendar-day"></i>
                        <span>Tarih: {{ \Carbon\Carbon::parse($selectedDate)->format('d.m.Y') }}</span>
                    </div>
                    <div class="summary-item">
                        <i class="fas fa-clock"></i>
                        <span>Toplam Vardiya: {{ count($shiftReport) }}</span>
                    </div>
                    <div class="summary-item">
                        <i class="fas fa-barcode"></i>
                        <span>Toplam Palet: {{ array_sum(array_column($shiftReport, 'barcode_count')) ?? 0 }}</span>
                    </div>
                    <div class="summary-item">
                        <i class="fas fa-weight-hanging"></i>
                        <span>Toplam Miktar: {{ number_format(array_sum(array_column($shiftReport, 'total_quantity')) ?? 0, 1) }} ton</span>
                    </div>
                </div>
                
                <div class="shift-grid">
                    @forelse($shiftReport as $shiftName => $shiftData)
                        <div class="shift-card {{ $shiftName }}" title="{{ ucfirst($shiftName) }} Vardiyası">
                            <div class="shift-name">
                                @if($shiftName === 'gece')
                                    <i class="fas fa-moon"></i> 00:00 - 08:00
                                @elseif($shiftName === 'gündüz')
                                    <i class="fas fa-sun"></i> 08:00 - 16:00
                                @elseif($shiftName === 'akşam')
                                    <i class="fas fa-cloud-sun"></i> 16:00 - 24:00
                                @else
                                    <i class="fas fa-clock"></i> {{ ucfirst($shiftName) }}
                                @endif
                            </div>
                            <div class="shift-stats">
                                <div class="shift-stat" title="Toplam Palet Sayısı">
                                    <div class="shift-stat-value">{{ number_format($shiftData['barcode_count'] ?? 0) }}</div>
                                    <div class="shift-stat-label">Palet</div>
                                </div>
                                <div class="shift-stat" title="Toplam Miktar (ton)">
                                    <div class="shift-stat-value">{{ number_format($shiftData['total_quantity'] ?? 0, 1) }}</div>
                                    <div class="shift-stat-label">Toplam (ton)</div>
                                </div>
                                <div class="shift-stat" title="Kabul Edilen Miktar (ton)">
                                    <div class="shift-stat-value">{{ number_format($shiftData['accepted_quantity'] ?? 0, 1) }}</div>
                                    <div class="shift-stat-label">Kabul (ton)</div>
                                </div>
                                <div class="shift-stat" title="Reddedilen Miktar (ton)">
                                    <div class="shift-stat-value">{{ number_format($shiftData['rejected_quantity'] ?? 0, 1) }}</div>
                                    <div class="shift-stat-label">Red (ton)</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="shift-card no-data" style="grid-column: 1 / -1; text-align: center;">
                            <div class="shift-name">
                                <i class="fas fa-info-circle"></i> Veri Bulunamadı
                            </div>
                            <div class="shift-stats">
                                <p style="color: rgba(255, 255, 255, 0.8); margin: 0;">Seçilen tarih için vardiya verisi bulunamadı.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif

        <!-- Crusher Performance -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title-modern">
                        <i class="fas fa-cogs"></i>
                        Kırıcı Performans Analizi
                    </h3>
                </div>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Kırıcı Adı</th>
                                <th>Palet Sayısı</th>
                                <th>Toplam Miktar (ton)</th>
                                <th>Kabul Edilen (ton)</th>
                                <th>Reddedilen (ton)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($crusherPerformance as $crusher)
                            <tr>
                                <td><strong>{{ $crusher->crusher_name }}</strong></td>
                                <td>{{ number_format($crusher->barcode_count) }}</td>
                                <td>{{ number_format($crusher->total_quantity, 1) }}</td>
                                <td><span class="badge badge-success">{{ number_format($crusher->accepted_quantity, 1) }}</span></td>
                                <td><span class="badge badge-danger">{{ number_format($crusher->rejected_quantity, 1) }}</span></td>
                            </tr>
                            @endforeach
                            
                            @if(count($crusherPerformance) === 0)
                            <tr>
                                <td colspan="5" class="text-center">Bu periyotta veri bulunmamaktadır.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Crusher Rejection Rates -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-exclamation-triangle"></i>
                    Kırıcı Red Oranları
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Kırıcı Adı</th>
                                <th>Toplam Miktar (ton)</th>
                                <th>Reddedilen (ton)</th>
                                <th>Red Oranı (%)</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($crusherRejectionRates as $crusher)
                            <tr>
                                <td><strong>{{ $crusher->crusher_name }}</strong></td>
                                <td>{{ number_format($crusher->total_quantity, 1) }}</td>
                                <td>{{ number_format($crusher->rejected_quantity, 1) }}</td>
                                <td><strong>{{ number_format($crusher->rejection_rate, 1) }}%</strong></td>
                                <td>
                                    @if($crusher->rejection_rate <= 5)
                                        <span class="badge badge-success">Düşük</span>
                                    @elseif($crusher->rejection_rate <= 15)
                                        <span class="badge badge-warning">Orta</span>
                                    @else
                                        <span class="badge badge-danger">Yüksek</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @if(count($crusherRejectionRates) === 0)
                            <tr>
                                <td colspan="5" class="text-center">Bu periyotta veri bulunmamaktadır.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @include('granilya.production.partials_stock_age')
        @include('granilya.production.partials_ai_insights')

        <!-- Monthly Comparison -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title-modern">
                        <i class="fas fa-chart-line"></i>
                        Aylık Karşılaştırma Analizi
                    </h3>
                    <div class="d-flex align-items-center gap-5">
                        <span class="monthly-comparison-badge">
                            <i class="fas fa-calendar-alt"></i> 
                            {{ $monthlyComparison['current_month_name'] }} vs {{ $monthlyComparison['previous_month_name'] }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body-modern">
                <!-- Main Comparison Cards -->
                <div class="monthly-comparison-overview">
                    <div class="row">
                        @php
                            $currTotal = array_sum($monthlyComparison['current']);
                            $prevTotal = array_sum($monthlyComparison['previous']);
                            $diff = $currTotal - $prevTotal;
                            $percentDiff = $prevTotal > 0 ? ($diff / $prevTotal) * 100 : ($currTotal > 0 ? 100 : 0);
                        @endphp
                        
                        <!-- Current Month -->
                        <div class="col-lg-6 mb-3">
                            <div class="month-card current-month">
                                <div class="month-header">
                                    <div class="month-icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="month-info">
                                        <h4 class="month-title">Bu Ay</h4>
                                        <p class="month-period">{{ $monthlyComparison['current_month_name'] }}</p>
                                    </div>
                                </div>
                                <div class="month-stats">
                                    <div class="month-stat-item">
                                        <div class="month-stat-value">{{ number_format($currTotal, 1) }}</div>
                                        <div class="month-stat-label">Toplam Tonaj</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Previous Month -->
                        <div class="col-lg-6 mb-3">
                            <div class="month-card previous-month">
                                <div class="month-header">
                                    <div class="month-icon">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <div class="month-info">
                                        <h4 class="month-title">Geçen Ay</h4>
                                        <p class="month-period">{{ $monthlyComparison['previous_month_name'] }}</p>
                                    </div>
                                </div>
                                <div class="month-stats">
                                    <div class="month-stat-item">
                                        <div class="month-stat-value">{{ number_format($prevTotal, 1) }}</div>
                                        <div class="month-stat-label">Toplam Tonaj</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="chart-container">
                    <canvas id="monthlyComparisonChart"></canvas>
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
        // Toggle Custom Date Selector
        const periodSelect = document.getElementById('period');
        const customDateSelector = document.getElementById('customDateSelector');
        
        periodSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDateSelector.style.display = 'block';
            } else {
                customDateSelector.style.display = 'none';
            }
        });
        
        // Monthly Comparison Line Chart
        const ctxMonthly = document.getElementById('monthlyComparisonChart').getContext('2d');
        const monthlyData = @json($monthlyComparison);
        
        new Chart(ctxMonthly, {
            type: 'line',
            data: {
                labels: monthlyData.labels,
                datasets: [
                    {
                        label: monthlyData.current_month_name,
                        data: monthlyData.current,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#667eea',
                        pointHoverBackgroundColor: '#667eea',
                        pointHoverBorderColor: '#ffffff',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: monthlyData.previous_month_name,
                        data: monthlyData.previous,
                        borderColor: '#adb5bd',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#adb5bd',
                        pointHoverBackgroundColor: '#adb5bd',
                        pointHoverBorderColor: '#ffffff',
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        fill: false,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { family: "'Inter', sans-serif", size: 12, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#343a40',
                        bodyColor: '#495057',
                        borderColor: '#e9ecef',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        callbacks: {
                            title: function(context) { return 'Gün ' + context[0].label; },
                            label: function(context) { return context.dataset.label + ': ' + context.raw.toFixed(1) + ' Ton'; }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: "'Inter', sans-serif" } },
                        title: { display: true, text: 'Ayın Günleri', font: { weight: 'bold' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 4], color: '#f1f3f5' },
                        ticks: {
                            font: { family: "'Inter', sans-serif" },
                            callback: function(value) { return value + ' T'; }
                        },
                        title: { display: true, text: 'Üretim (Ton)', font: { weight: 'bold' } }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    });
</script>
@endsection
