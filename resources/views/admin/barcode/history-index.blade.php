@extends('layouts.app')

@section('styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <style>
        body, .main-content, .modern-barcode-history-index {
            background: #f8f9fa !important;
        }
        .modern-barcode-history-index {
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
        
        .btn-modern {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }
        
        .btn-info-modern {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
            color: white;
        }
        
        .btn-info-modern:hover {
            background: linear-gradient(135deg, #138496 0%, #5a32a3 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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
        
        .btn-secondary-modern {
            background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%);
            color: white;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        /* Column Filters */
        .column-filters {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
        }
        
        .filter-actions {
            display: flex;
            gap: 0.75rem;
        }
        
        .filter-header h6 {
            margin: 0;
            font-weight: 600;
            color: #495057;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }
        
        .filter-header h6 i {
            margin-right: 0.5rem;
            color: #667eea;
            font-size: 1.2rem;
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .filter-item {
            display: flex;
            flex-direction: column;
        }
        
        .filter-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .filter-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
            white-space: normal;
            word-wrap: break-word;
            height: auto;
            min-height: 45px;
            cursor: pointer;
        }
        
        .filter-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .filter-select option {
            font-size: 0.875rem;
            padding: 0.5rem;
            white-space: normal;
            word-wrap: break-word;
        }
        
        .filter-date {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
            cursor: pointer;
        }
        
        .filter-date:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .filter-date::placeholder {
            color: #adb5bd;
            font-size: 0.875rem;
        }
        
        /* Datepicker Styling */
        .datepicker {
            border-radius: 10px !important;
            border: 2px solid #e9ecef !important;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
            background: white !important;
            color: #495057 !important;
            z-index: 9999 !important;
            padding: 1rem !important;
            font-size: 0.875rem !important;
            max-width: 300px !important;
        }
        
        .datepicker table {
            background: white !important;
            width: 100% !important;
            font-size: 0.875rem !important;
        }
        
        .datepicker table tr td,
        .datepicker table tr th {
            border-radius: 8px !important;
            margin: 2px !important;
            color: #495057 !important;
            background: white !important;
            text-align: center !important;
            padding: 0.5rem !important;
            font-size: 0.875rem !important;
            width: 35px !important;
            height: 35px !important;
            line-height: 25px !important;
        }
        
        .datepicker table tr td.active,
        .datepicker table tr td.active:hover,
        .datepicker table tr td.active.disabled,
        .datepicker table tr td.active.disabled:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-color: #667eea !important;
            color: white !important;
        }
        
        .datepicker table tr td.today {
            background: linear-gradient(135deg, #ffc107, #e0a800) !important;
            border-color: #ffc107 !important;
            color: white !important;
        }
        
        .datepicker table tr td:hover {
            background: #f8f9fa !important;
        }
        
        .datepicker table tr td.old,
        .datepicker table tr td.new {
            color: #adb5bd !important;
        }
        
        .datepicker .datepicker-switch {
            font-weight: 600 !important;
            color: #495057 !important;
            background: #f8f9fa !important;
            padding: 0.5rem !important;
            font-size: 0.875rem !important;
            height: 35px !important;
            line-height: 25px !important;
        }
        
        .datepicker .prev,
        .datepicker .next {
            color: #667eea !important;
            background: #f8f9fa !important;
            padding: 0.5rem !important;
            font-size: 0.875rem !important;
            height: 35px !important;
            line-height: 25px !important;
        }
        
        .datepicker .prev:hover,
        .datepicker .next:hover {
            background: #e9ecef !important;
            color: #5a6fd8 !important;
        }
        
        .datepicker .dow {
            font-weight: 600 !important;
            color: #495057 !important;
            background: #f8f9fa !important;
            padding: 0.5rem !important;
            font-size: 0.75rem !important;
            height: 30px !important;
            line-height: 20px !important;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .filter-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
            
            .filter-header {
                flex-direction: column;
                gap: 0.75rem;
                text-align: center;
            }
            
            .filter-actions {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .filter-actions .btn {
                width: 100%;
            }
            
            .column-filters {
                padding: 1rem;
            }
        }
        
        .card-modern {
            background: #ffffff;
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
            color: #6c757d;
            margin-bottom: 0;
        }
        
        .card-body-modern {
            padding: 2rem;
        }
        
        .form-group-modern {
            margin-bottom: 1.5rem;
        }
        
        .form-label-modern {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .form-control-modern {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
        }
        
        .form-control-modern:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .custom-select-modern {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
            cursor: pointer;
        }
        
        .custom-select-modern:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
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
        }
        
        .table-modern tbody td {
            padding: 1rem;
            border: none;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }
        
        .table-modern tbody tr:hover {
            background: #f8f9fa;
        }
        
        /* Status Badges */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-waiting {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: #212529;
        }
        
        .status-control-repeat {
            background: linear-gradient(135deg, #fd7e14, #e55a00);
            color: white;
        }
        
        .status-pre-approved {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .status-shipment-approved {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
        }
        
        .status-rejected {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }
        
        .status-customer-transfer {
            background: linear-gradient(135deg, #6f42c1, #5a32a3);
            color: white;
        }
        
        .status-delivered {
            background: linear-gradient(135deg, #20c997, #17a2b8);
            color: white;
        }
        
        .status-merged {
            background: linear-gradient(135deg, #6f42c1, #5a32a3);
            color: white;
        }

        .status-unknown {
            color: #6c757d;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        
        /* Changes Display */
        .changes-container {
            max-width: 400px;
            min-width: 350px;
        }
        
        .change-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 12px;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .change-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .change-field {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-right: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .change-value {
            color: #28a745;
            font-weight: 600;
            font-size: 13px;
            padding: 2px 0;
        }
        
        .change-arrow {
            color: #667eea;
            margin: 0 5px;
        }
        
        .badge-modern {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-info-modern {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
        }
        
        .badge-success-modern {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .badge-danger-modern {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }
        
        .text-success-modern {
            color: #28a745;
            font-weight: 600;
        }
        
        .text-muted-modern {
            color: #6c757d;
        }
        
        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
            
            .action-buttons {
                flex-direction: column;
                width: 100%;
            }
            
            .action-buttons .btn {
                width: 100%;
            }
            
            .card-body-modern {
                padding: 1rem;
            }
        }
        
        /* DataTables Styling */
        .dataTables_wrapper .dataTables_processing {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 1rem;
            font-weight: 600;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 8px;
            margin: 0 2px;
            transition: all 0.3s ease;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            border-color: #667eea;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            border-color: #667eea;
        }
        
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
    </style>
@endsection

@section('content')
    <div class="modern-barcode-history-index">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-history"></i> Barkod Hareket Geçmişi
                        </h1>
                        <p class="page-subtitle-modern">Sistemdeki tüm barkod işlemlerinin detaylı geçmişini görüntüleyin</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="action-buttons justify-content-end">
                            <!-- Filtre butonu kaldırıldı -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column Filters -->
            <div class="column-filters" id="filter-area">
                <div class="filter-header">
                    <h6><i class="fas fa-filter"></i> Sütun Filtreleri</h6>
                    <div class="filter-actions">
                        <button class="btn-modern btn-info-modern" onclick="applyFilters()">
                            <i class="fas fa-check"></i> Filtreleri Uygula
                        </button>
                        <button class="btn-modern btn-secondary-modern" onclick="clearFilters()">
                            <i class="fas fa-refresh"></i> Filtreleri Temizle
                        </button>
                    </div>
                </div>
                <div class="filter-grid">
                    <div class="filter-item">
                        <label class="filter-label">Stok</label>
                        <select class="filter-select" data-column="0">
                            <option value="">Tüm Stoklar</option>
                            @foreach($stocks as $stock)
                                <option value="{{ $stock->name }}">{{ $stock->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-item">
                        <label class="filter-label">Parti No</label>
                        <select class="filter-select" data-column="2">
                            <option value="">Tüm Partiler</option>
                            @foreach($barcodeHistories->pluck('party_number')->unique() as $party)
                                @if($party)
                                    <option value="{{ $party }}">{{ $party }}</option>
                                @endif
                            @endforeach 
                        </select>
                    </div>
                    <div class="filter-item">
                        <label class="filter-label">Durum</label>
                        <select class="filter-select" data-column="3">
                            <option value="">Tüm Durumlar</option>
                            <option value="Beklemede">Beklemede</option>
                            <option value="Kontrol Tekrarı">Kontrol Tekrarı</option>
                            <option value="Ön Onaylı">Ön Onaylı</option>
                            <option value="Sevk Onaylı">Sevk Onaylı</option>
                            <option value="Reddedildi">Reddedildi</option>
                            <option value="Müşteri Transfer">Müşteri Transfer</option>
                            <option value="Teslim Edildi">Teslim Edildi</option>
                            <option value="Birleştirildi">Birleştirildi</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label class="filter-label">Miktar</label>
                        <select class="filter-select" data-column="4">
                            <option value="">Tüm Miktarlar</option>
                            @foreach($quantities as $quantity)
                                <option value="{{ $quantity->quantity }} KG">{{ $quantity->quantity }} KG</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-item">
                        <label class="filter-label">Firma</label>
                        <select class="filter-select" data-column="5">
                            <option value="">Tüm Firmalar</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->name }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-item">
                        <label class="filter-label">Depo</label>
                        <select class="filter-select" data-column="6">
                            <option value="">Tüm Depolar</option>
                            @foreach($wareHouses as $wareHouse)
                                <option value="{{ $wareHouse->name }}">{{ $wareHouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-item">
                        <label class="filter-label">Oluşturan</label>
                        <select class="filter-select" data-column="7">
                            <option value="">Tüm Kullanıcılar</option>
                            @foreach($users as $user)
                                <option value="{{ $user->name }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-item">
                        <label class="filter-label">Lab Personeli</label>
                        <select class="filter-select" data-column="8">
                            <option value="">Tüm Lab Personeli</option>
                            @foreach($users as $user)
                                <option value="{{ $user->name }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-item">
                        <label class="filter-label">Başlangıç Tarihi</label>
                        <input type="text" class="filter-date" id="start-date" placeholder="Başlangıç Tarihi" autocomplete="off">
                    </div>
                    <div class="filter-item">
                        <label class="filter-label">Bitiş Tarihi</label>
                        <input type="text" class="filter-date" id="end-date" placeholder="Bitiş Tarihi" autocomplete="off">
                    </div>
                    <div class="filter-item">
                        <label class="filter-label">Lab Başlangıç</label>
                        <input type="text" class="filter-date" id="lab-start-date" placeholder="Lab Başlangıç" autocomplete="off">
                    </div>
                    <div class="filter-item">
                        <label class="filter-label">Lab Bitiş</label>
                        <input type="text" class="filter-date" id="lab-end-date" placeholder="Lab Bitiş" autocomplete="off">
                    </div>
                </div>
            </div>

            <!-- Results Card -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-history"></i> Barkod Hareket Geçmişi
                    </h3>
                    <p class="card-subtitle-modern">
                        Bu sayfada tüm barkod işlemlerinin detaylı geçmişini görebilirsiniz. Oluşturma, güncelleme, silme ve durum değişiklikleri burada takip edilir.
                    </p>
                </div>

                <div class="card-body-modern">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap yajra-datatable" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Stok</th>
                            <th>Parti Numarası</th>
                            <th>Şarj Numarası</th>
                            <th>Açıklama</th>
                            <th>Kullanıcı</th>
                            <th>Durumu</th>
                            <th>Değişiklikler</th>
                            <th>İşlem Tarihi</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        // Global değişkenler
        var table;
        var dataTableInitialized = false;
        
        // Sayfa yüklendiğinde çalışacak fonksiyon
        $(document).ready(function() {
            console.log('Sayfa yüklendi, JavaScript başlatılıyor...');
            
            // Morris chart hatalarını önle
            preventMorrisErrors();
            
            // Toastr güvenlik kontrolü
            setupToastrFallback();
            
            // DataTables'ı başlat (sadece bir kez)
            if (!dataTableInitialized) {
                initializeDataTable();
            }
            
            // Datepicker'ları başlat
            initializeDatepickers();
        });
        
        // Sayfa kapatılırken DataTable'ı temizle
        $(window).on('beforeunload', function() {
            if (typeof table !== 'undefined' && table) {
                try {
                    table.destroy();
                } catch (e) {
                    console.log('Table cleanup hatası:', e);
                }
                table = null;
                dataTableInitialized = false;
            }
        });
        
        // Morris chart hatalarını önle
        function preventMorrisErrors() {
            if (typeof Morris !== 'undefined') {
                // Morris chart elementlerini kontrol et
                if (document.getElementById('morris-line-example') || document.getElementById('morris-donut-example')) {
                    // Chart elementleri varsa normal şekilde çalıştır
                    if (typeof $.Dashboard !== 'undefined') {
                        try {
                            $.Dashboard.init();
                        } catch (e) {
                            console.log('Dashboard init hatası:', e);
                        }
                    }
                }
            }
        }
        
        // DataTable'ı güvenli şekilde yeniden başlat
        function reinitializeDataTable() {
            console.log('DataTable yeniden başlatılıyor...');
            
            // Mevcut DataTable'ı yok et
            if (typeof table !== 'undefined' && table) {
                try {
                    table.destroy();
                } catch (e) {
                    console.log('Table destroy hatası:', e);
                }
                table = null;
            }
            
            // Kısa bir gecikme ile yeniden başlat
            setTimeout(function() {
                initializeDataTable();
            }, 100);
        }
        
        // Toastr fallback kurulumu
        function setupToastrFallback() {
            if (typeof toastr === 'undefined') {
                window.toastr = {
                    success: function(msg, title) { console.log('Toastr Success:', msg, title); },
                    error: function(msg, title) { console.log('Toastr Error:', msg, title); },
                    warning: function(msg, title) { console.log('Toastr Warning:', msg, title); },
                    info: function(msg, title) { console.log('Toastr Info:', msg, title); }
                };
            }
        }
        
        // DataTables başlatma
        function initializeDataTable() {
            console.log('DataTables initialization başlatılıyor...');
            
            // DataTables element kontrolü
            if ($('.yajra-datatable').length === 0) {
                console.error('DataTables element bulunamadı!');
                return;
            }
            
            // Eğer DataTable zaten başlatılmışsa, önce onu yok et
            if ($.fn.DataTable.isDataTable('.yajra-datatable')) {
                console.log('Mevcut DataTable yok ediliyor...');
                try {
                    $('.yajra-datatable').DataTable().destroy();
                } catch (e) {
                    console.log('DataTable destroy hatası:', e);
                }
            }
            
            // Global table değişkenini temizle
            if (typeof table !== 'undefined' && table) {
                try {
                    table.destroy();
                } catch (e) {
                    console.log('Table destroy hatası:', e);
                }
                table = null;
            }
            
            try {
                table = $('.yajra-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [[ 0, "desc" ]],
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    searching: true,
                    info: true,
                    responsive: true,
                    deferRender: true,
                    language: {
                        processing: 'Veriler yükleniyor...',
                        search: 'Ara:',
                        lengthMenu: '_MENU_ kayıt göster',
                        info: '_TOTAL_ kayıttan _START_ - _END_ arası gösteriliyor',
                        infoEmpty: 'Kayıt bulunamadı',
                        infoFiltered: '(_MAX_ kayıt arasından filtrelendi)',
                        emptyTable: 'Tabloda veri bulunamadı',
                        zeroRecords: 'Eşleşen kayıt bulunamadı',
                        paginate: {
                            first: 'İlk',
                            previous: 'Önceki',
                            next: 'Sonraki',
                            last: 'Son'
                        }
                    },
                    ajax: {
                        url: "{{ route('barcode.historyIndex') }}",
                        type: 'GET',
                        timeout: 30000,
                        data: function (d) {
                            // Filtreleri AJAX data'ya ekle
                            var startDate = $('#start-date').val();
                            var endDate = $('#end-date').val();
                            var labStartDate = $('#lab-start-date').val();
                            var labEndDate = $('#lab-end-date').val();
                            
                            if (startDate) d.start_date = startDate;
                            if (endDate) d.end_date = endDate;
                            if (labStartDate) d.lab_start_date = labStartDate;
                            if (labEndDate) d.lab_end_date = labEndDate;
                            
                            // Dropdown filtreleri
                            $('.filter-select').each(function() {
                                var value = $(this).val();
                                var column = $(this).data('column');
                                
                                if (value && value !== 'Tüm Stoklar' && value !== 'Tüm Partiler' && value !== 'Tüm Durumlar' && value !== 'Tüm Miktarlar' && value !== 'Tüm Firmalar' && value !== 'Tüm Depolar' && value !== 'Tüm Kullanıcılar' && value !== 'Tüm Lab Personeli') {
                                    // Column index'e göre filtreleme
                                    if (column === 0) d.stock_name = value;
                                    else if (column === 2) d.party_number = value;
                                    else if (column === 3) d.status = value;
                                    else if (column === 4) d.quantity = value;
                                    else if (column === 5) d.company_name = value;
                                    else if (column === 6) d.warehouse_name = value;
                                    else if (column === 7) d.created_by = value;
                                    else if (column === 8) d.lab_by = value;
                                }
                            });
                            
                            console.log('AJAX data gönderiliyor:', d);
                            return d;
                        },
                        beforeSend: function() {
                            console.log('AJAX isteği başlatılıyor...');
                        },
                        success: function(data, textStatus, xhr) {
                            console.log('AJAX başarılı:', data);
                            console.log('Veri sayısı:', data.data ? data.data.length : 'Veri yok');
                            console.log('Toplam kayıt:', data.recordsTotal);
                            console.log('Filtrelenmiş kayıt:', data.recordsFiltered);
                            
                            // Veri yoksa uyarı ver
                            if (!data.data || data.data.length === 0) {
                                console.warn('DataTables: Hiç veri döndürülmedi!');
                            }
                        },
                        error: function (xhr, error, thrown) {
                            console.error('DataTables AJAX Error:', error);
                            console.error('Response:', xhr.responseText);
                            console.error('Status:', xhr.status);
                            console.error('StatusText:', xhr.statusText);
                            
                            // Hata durumunda kullanıcıya bilgi ver
                            if (xhr.status === 404) {
                                alert('Sayfa bulunamadı. Lütfen sayfayı yenileyin.');
                            } else if (xhr.status === 500) {
                                alert('Sunucu hatası oluştu. Lütfen daha sonra tekrar deneyin.');
                            } else {
                                alert('Veri yüklenirken hata oluştu: ' + error);
                            }
                        },
                        complete: function() {
                            console.log('AJAX isteği tamamlandı');
                        }
                    },
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'stock', name: 'stock', orderable: false},
                        {data: 'party_number', name: 'party_number', orderable: false},
                        {data: 'load_number', name: 'load_number', orderable: false},
                        {data: 'description', name: 'description', orderable: false},
                        {data: 'user', name: 'user', orderable: false},
                        {data: 'status', name: 'status', orderable: false},
                        {data: 'changes', name: 'changes', orderable: false, searchable: false},
                        {data: 'created_at', name: 'created_at', orderable: true},
                    ],
                    columnDefs: [
                        {
                            targets: [7], // changes column
                            width: '300px'
                        }
                    ],
                    initComplete: function(settings, json) {
                        console.log('DataTables başarıyla yüklendi!');
                        console.log('Yüklenen veri:', json);
                    },
                    drawCallback: function(settings) {
                        console.log('DataTables draw callback çalıştı');
                        console.log('Gösterilen satır sayısı:', settings._iDisplayLength);
                        console.log('Toplam satır sayısı:', settings._iRecordsTotal);
                    },
                    preDrawCallback: function(settings) {
                        console.log('DataTables pre-draw callback çalıştı');
                    }
                });
                
                console.log('DataTables başarıyla oluşturuldu');
                dataTableInitialized = true;
                
            } catch (error) {
                console.error('DataTables oluşturulurken hata:', error);
                alert('Tablo yüklenirken hata oluştu: ' + error.message);
                dataTableInitialized = false;
            }
        }
        
        // Datepicker'ları başlat
        function initializeDatepickers() {
            $('#start-date').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: 'bottom auto',
                clearBtn: true,
                todayBtn: 'linked',
                keyboardNavigation: true,
                forceParse: false
            });
            
            $('#end-date').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: 'bottom auto',
                clearBtn: true,
                todayBtn: 'linked',
                keyboardNavigation: true,
                forceParse: false
            });
            
            $('#lab-start-date').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: 'bottom auto',
                clearBtn: true,
                todayBtn: 'linked',
                keyboardNavigation: true,
                forceParse: false
            });
            
            $('#lab-end-date').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: 'bottom auto',
                clearBtn: true,
                todayBtn: 'linked',
                keyboardNavigation: true,
                forceParse: false
            });
        }
        
        // Filtreleri uygula
        function applyFilters() {
            console.log('applyFilters fonksiyonu çağrıldı');
            
            if (table) {
                table.ajax.reload();
            } else {
                console.error('DataTable henüz başlatılmamış!');
            }
        }
        
        // Filtreleri temizle
        function clearFilters() {
            console.log('clearFilters fonksiyonu çağrıldı');
            
            // Tüm filtreleri temizle
            $('.filter-select').val('');
            $('.filter-date').val('');
            
            if (table) {
                table.ajax.reload();
            } else {
                console.error('DataTable henüz başlatılmamış!');
            }
        }
    </script>
@endsection
