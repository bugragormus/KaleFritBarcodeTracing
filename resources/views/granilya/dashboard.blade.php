@extends('layouts.granilya')

@section('breadcrumb')
    <li class="breadcrumb-item active">
        <i class="fas fa-tachometer-alt"></i> Ana Sayfa
    </li>
@endsection

@section('styles')
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
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
        height: 250px;
        display: flex;
        align-items: stretch;
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
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
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

    /* Select2 Custom Styling for Multiple Select */
    .select2-container--default .select2-selection--multiple {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        min-height: 45px;
        padding: 4px 8px;
        transition: all 0.3s ease;
        background: #ffffff;
    }
    
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 5px 10px;
        margin-top: 6px;
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: rgba(255,255,255,0.8);
        margin-right: 5px;
        font-weight: bold;
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: white;
        background: transparent;
    }

    .select2-dropdown {
        border: 2px solid #667eea;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        z-index: 9999;
    }
    
    .select2-results__option--highlighted[aria-selected] {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
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
                            <h1 class="page-title m-0">🎯 GRANİLYA SİSTEMİ</h1>
                            <p class="welcome-text">Hoş geldiniz, <strong>{{ auth()->user()->name }}</strong>!</p>
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
                        Hızlı Menü
                    </h5>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('granilya.production') }}" class="quick-action-item">
                                <div class="quick-action-icon bg-primary">
                                    <i class="fas fa-industry"></i>
                                </div>
                                <div class="quick-action-text">
                                    <h6>Üretim Girişi</h6>
                                    <small>Yeni üretim kaydı</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('granilya.stock') }}" class="quick-action-item">
                                <div class="quick-action-icon bg-success">
                                    <i class="fas fa-boxes"></i>
                                </div>
                                <div class="quick-action-text">
                                    <h6>Stok Durumu</h6>
                                    <small>Güncel stokları izle</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('granilya.laboratory') }}" class="quick-action-item">
                                <div class="quick-action-icon bg-info">
                                    <i class="fas fa-flask"></i>
                                </div>
                                <div class="quick-action-text">
                                    <h6>Laboratuvar</h6>
                                    <small>Analiz verileri</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('granilya.sales') }}" class="quick-action-item">
                                <div class="quick-action-icon bg-warning">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="quick-action-text">
                                    <h6>Satış Ekranı</h6>
                                    <small>Satış işlemlerini yönet</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Raw Material Stocks Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="section-title mb-4">
                <i class="fas fa-cubes text-info mr-2"></i>
                Frit Üretimden Aktarılan Hammaddeler
            </h5>
            
            <div class="card quick-actions-card">
                <div class="card-body">
                    @if($rawMaterialStocks->count() > 0)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="fritFilter" style="font-weight: 600; color: #495057;">
                                    <i class="fas fa-filter text-info mr-1"></i> Frit Adına Göre Çoklu Filtreleme
                                </label>
                                <select id="fritFilter" class="form-control" multiple="multiple" data-placeholder="Filtrelemek için frit seçin...">
                                    @foreach($rawMaterialStocks as $stock)
                                        <option value="{{ $stock->stock_name }}">{{ $stock->stock_name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted mt-2"><i class="fas fa-info-circle text-primary"></i> Seçim yapmak için kutuya tıklayın. Birden fazla seçim yapabilirsiniz.</small>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="rawMaterialTable" class="table table-hover table-bordered mb-0">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th style="font-weight: 600; color: #495057;">Hammadde (Frit) Adı</th>
                                        <th style="font-weight: 600; color: #495057;">Şarj No</th>
                                        <th class="text-center" style="font-weight: 600; color: #495057;">Aktarılan Barkod Sayısı</th>
                                        <th class="text-right" style="font-weight: 600; color: #495057;">Toplam Miktar (KG)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rawMaterialStocks as $stock)
                                    <tr>
                                        <td class="align-middle"><strong>{{ $stock->stock_name }}</strong></td>
                                        <td class="align-middle">{{ $stock->load_number ?? '-' }}</td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-info badge-pill py-1 px-3" style="font-size: 0.9rem;">
                                                {{ $stock->barcode_count }} Adet
                                            </span>
                                        </td>
                                        <td class="text-right align-middle">
                                            <span class="text-success font-weight-bold" style="font-size: 1.1rem;">
                                                {{ number_format($stock->total_quantity, 2, ',', '.') }} KG
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-soft-info text-center m-0" role="alert" style="background-color: #e3f2fd; color: #0c5460; border-color: #bee5eb;">
                            <i class="fas fa-info-circle mr-2 text-info"></i>
                            Henüz Frit üretiminden aktarılmış bir hammadde stoğu bulunmamaktadır.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="section-title mb-4">
                <i class="fas fa-chart-bar text-primary mr-2"></i>
                Sistem Özet Verileri
            </h5>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card kpi-card">
                <div class="card-body text-center">
                    <div class="kpi-icon mb-3">
                        <i class="fas fa-industry fa-3x text-primary"></i>
                    </div>
                    <h3 class="kpi-number">-</h3>
                    <p class="kpi-label">Günlük Üretim</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card kpi-card">
                <div class="card-body text-center">
                    <div class="kpi-icon mb-3">
                        <i class="fas fa-boxes fa-3x text-success"></i>
                    </div>
                    <h3 class="kpi-number">-</h3>
                    <p class="kpi-label">Toplam Stok (KG)</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card kpi-card">
                <div class="card-body text-center">
                    <div class="kpi-icon mb-3">
                        <i class="fas fa-truck fa-3x text-info"></i>
                    </div>
                    <h3 class="kpi-number">-</h3>
                    <p class="kpi-label">Satış Hacmi (Aylık)</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card kpi-card">
                <div class="card-body text-center">
                    <div class="kpi-icon mb-3">
                        <i class="fas fa-flask fa-3x text-warning"></i>
                    </div>
                    <h3 class="kpi-number">-</h3>
                    <p class="kpi-label">Bekleyen Analiz</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card kpi-card">
                <div class="card-body text-center">
                    <div class="kpi-icon mb-3">
                        <i class="fas fa-check-circle fa-3x text-success"></i>
                    </div>
                    <h3 class="kpi-number">-</h3>
                    <p class="kpi-label">Onaylı Ürünler</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card kpi-card">
                <div class="card-body text-center">
                    <div class="kpi-icon mb-3">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
                    </div>
                    <h3 class="kpi-number">-</h3>
                    <p class="kpi-label">Reddedilen Ürünler</p>
                </div>
            </div>
        </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        if ($('#rawMaterialTable').length > 0) {
            var table = $('#rawMaterialTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json"
                },
                "pageLength": 10,
                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tümü"]],
                "order": [[ 0, "asc" ]],
                "responsive": true,
                "columnDefs": [
                    { "orderable": false, "targets": [2, 3] }
                ]
            });

            // Initialize Select2
            $('#fritFilter').select2({
                width: '100%',
                allowClear: true
            });

            // Özel çoklu filtreleme işlemi
            $('#fritFilter').on('change', function() {
                var selectedValues = $(this).val();
                
                if (selectedValues && selectedValues.length > 0) {
                    // Seçilen değerleri regex 'veya' (|) formatında birleştiriyoruz
                    var regexStr = selectedValues.map(function(val) {
                        return '^' + $.fn.dataTable.util.escapeRegex(val) + '$';
                    }).join('|');
                    
                    table.column(0).search(regexStr, true, false).draw();
                } else {
                    // Hiçbir şey seçilmediyse filtreyi temizle
                    table.column(0).search('').draw();
                }
            });
        }
    });
</script>
@endsection
