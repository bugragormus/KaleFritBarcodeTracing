@extends('layouts.app')

@section('styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <style>
        body, .main-content, .modern-barcode-management {
            background: #f8f9fa !important;
        }
        .modern-barcode-management {
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
        
        .btn-success-modern {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-info-modern {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        
        .btn-danger-modern {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        
        .btn-secondary-modern {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
        }
        
        .btn-warning-modern {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
        }
        
        .btn-primary-modern {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
        }
        
        /* Küçük butonlar için */
        .btn-xs-modern {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            border-radius: 8px;
        }
        
        /* Buton grubu */
        .btn-group-modern {
            display: inline-flex;
            gap: 0.25rem;
            flex-wrap: wrap;
        }
        
        .btn-group-modern .btn-modern {
            margin: 0;
        }
        
        /* Hover efektleri */
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }
        
        .btn-xs-modern:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        }
        
        /* Disabled durumu */
        .btn-modern:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }
        
        .btn-modern:disabled:hover {
            transform: none !important;
            box-shadow: none !important;
        }
        
        /* Responsive tasarım */
        @media (max-width: 768px) {
            .btn-group-modern {
                flex-direction: column;
                gap: 0.5rem;
                width: 100%;
            }
            
            .btn-group-modern .btn-modern {
                width: 100%;
                justify-content: center;
            }
            
            .btn-xs-modern {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 576px) {
            .btn-modern {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }
            
            .btn-xs-modern {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }
        }
        
        .btn-info-modern {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
            color: white;
        }
        
        .btn-warning-modern {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
        }
        
        .btn-danger-modern {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }
        
        .btn-secondary-modern {
            background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%);
            color: white;
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
        
        /* DataTable Styling */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            margin: 1rem 0;
        }
        
        .dataTables_wrapper .dataTables_length select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.5rem 0.75rem;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }
        
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 10px;
            margin: 0 2px;
            border: 2px solid #e9ecef;
            background: white;
            color: #495057;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            border-color: #5a6fd8;
            color: white;
        }
        
        /* Status Badges */
        .status-badge {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            text-align: center;
            min-width: 100px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            border: none;
            transition: all 0.3s ease;
        }
        
        .status-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
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
        
        /* Badge styles for merge status */
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .badge-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
        }
        
        /* Table styling for better status badge display */
        .table td {
            vertical-align: middle;
        }
        
        .table th {
            vertical-align: middle;
            font-weight: 600;
            color: #495057;
        }
        
        /* DataTable specific styling */
        .yajra-datatable td {
            vertical-align: middle;
        }
        
        .yajra-datatable th {
            vertical-align: middle;
            font-weight: 600;
            color: #495057;
        }
        
        /* Modern Action Buttons */
        .action-buttons-container {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: none;
            min-width: 80px;
            position: relative;
            overflow: hidden;
        }
        
        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .action-btn:hover::before {
            left: 100%;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .action-btn i {
            margin-right: 0.25rem;
            font-size: 0.875rem;
        }
        
        /* Hareketler Butonu */
        .btn-history {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
        }
        
        .btn-history:hover {
            background: linear-gradient(135deg, #138496, #117a8b);
            color: white;
        }
        
        /* Düzenle Butonu */
        .btn-edit {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: white;
        }
        
        .btn-edit:hover {
            background: linear-gradient(135deg, #e0a800, #d39e00);
            color: white;
        }
        
        /* Sil Butonu */
        .btn-delete {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }
        
        .btn-delete:hover {
            background: linear-gradient(135deg, #c82333, #bd2130);
            color: white;
        }
        
        /* Görüntüle Butonu */
        .btn-view {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .btn-view:hover {
            background: linear-gradient(135deg, #218838, #1ea085);
            color: white;
        }
        
        /* Yazdır Butonu */
        .btn-print {
            background: linear-gradient(135deg, #6f42c1, #5a32a3);
            color: white;
        }
        
        .btn-print:hover {
            background: linear-gradient(135deg, #5a32a3, #4a2a8a);
            color: white;
        }
        
        /* Responsive action buttons */
        @media (max-width: 768px) {
            .action-buttons-container {
                flex-direction: column;
                gap: 0.25rem;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
            }
            
            .page-title-modern {
                font-size: 2rem;
            }
            
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
    </style>
@endsection

@section('content')
    <div class="modern-barcode-management">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-barcode"></i> Barkod Yönetimi
                        </h1>
                        <p class="page-subtitle-modern">Sistemdeki tüm barkodları görüntüleyin, arayın ve yönetin</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{ route('barcode.create') }}" class="btn-modern btn-success-modern">
                            <i class="fas fa-plus"></i> Yeni Barkod
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modern Card -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-list"></i> Barkod Listesi
                    </h3>
                    <p class="card-subtitle-modern">
                        Aşağıdaki listede sisteme kayıtlı tüm barkodları görebilir, düzenleyebilir ve silebilirsiniz
                    </p>
                </div>

                <!-- Column Filters -->
                <div class="column-filters">
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
                            <select class="filter-select" data-column="1">
                                <option value="">Tüm Partiler</option>
                                @foreach($barcodes->pluck('party_number')->unique() as $party)
                                    @if($party)
                                        <option value="{{ $party }}">{{ $party }}</option>
                                    @endif
                                @endforeach 
                            </select>
                        </div>
                            <div class="filter-item">
                                <label class="filter-label">Durum</label>
                                <select class="filter-select" data-column="2">
                                    <option value="">Tüm Durumlar</option>
                                    <option value="Beklemede">Beklemede</option>
                                    <option value="Kontrol Tekrarı">Kontrol Tekrarı</option>
                                    <option value="Ön Onaylı">Ön Onaylı</option>
                                    <option value="Sevk Onaylı">Sevk Onaylı</option>
                                    <option value="Reddedildi">Reddedildi</option>
                                    <option value="Düzeltme Faaliyetinde Kullanıldı">Düzeltme Faaliyetinde Kullanıldı</option>
                                    <option value="Müşteri Transfer">Müşteri Transfer</option>
                                    <option value="Teslim Edildi">Teslim Edildi</option>
                                    <option value="Birleştirildi">Birleştirildi</option>
                                </select>
                            </div>

                            <div class="filter-item">
                                <label class="filter-label">İstisnai Onay</label>
                                <select class="filter-select" id="exceptionally-approved-filter">
                                    <option value="">Tüm Ürünler</option>
                                    <option value="1">İstisnai Onaylı</option>
                                    <option value="0">Normal Onaylı</option>
                                </select>
                            </div>

                        <div class="filter-item">
                            <label class="filter-label">Fırın</label>
                            <select class="filter-select" data-column="14">
                                <option value="">Tüm Fırınlar</option>
                                @foreach($kilns as $kiln)
                                    <option value="{{ $kiln->name }}">{{ $kiln->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Depo</label>
                            <select class="filter-select" data-column="4">
                                <option value="">Tüm Depolar</option>
                                @foreach($wareHouses as $wareHouse)
                                    <option value="{{ $wareHouse->name }}">{{ $wareHouse->name }}</option>
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
                            <label class="filter-label">Lab Tarihi Bşl.</label>
                            <input type="text" class="filter-date" id="lab-date-start" placeholder="Başlangıç Tarihi" autocomplete="off">
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Lab Tarihi Bitiş</label>
                            <input type="text" class="filter-date" id="lab-date-end" placeholder="Bitiş Tarihi" autocomplete="off">
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Oluşturan</label>
                            <select class="filter-select" data-column="9">
                                <option value="">Tüm Kullanıcılar</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->name }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Oluşturulma Tarihi Bşl.</label>
                            <input type="text" class="filter-date" id="created-date-start" placeholder="Başlangıç Tarihi" autocomplete="off">
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Oluşturulma Tarihi Bitiş</label>
                            <input type="text" class="filter-date" id="created-date-end" placeholder="Bitiş Tarihi" autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="card-body-modern">
                    <table class="table table-bordered dt-responsive yajra-datatable nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Stok</th>
                                <th>Parti No</th>
                                <th class="text-center">Durum</th>
                                <th>Miktar</th>
                                <th>Depo</th>
                                <th>Firma</th>
                                <th>Lab Tarihi</th>
                                <th>Oluşturulma Tarihi</th>
                                <th>İşlemler</th>
                                <th>Oluşturan</th>
                                <th>Lab Personeli</th>
                                <th>Depo Transfer Eden</th>
                                <th>Teslim Eden</th>
                                <th>Depo Transfer Tarihi</th>
                                <th>Fırın No</th>
                                <th>Müşteri Transfer Tarihi</th>
                                <th>Şarj No</th>
                                <th>Barkod No</th>
                                <th>Genel Not</th>
                                <th>Lab Notu</th>
                                <th>Birleştirilme Durumu</th>
                                <th>Düzeltme Durumu</th>
                                <th>İşlem Süresi</th>
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
    <script type="text/javascript">
        // Global değişkenler - tarih filtreleri için
        var globalLabStart = '';
        var globalLabEnd = '';
        var globalCreatedStart = '';
        var globalCreatedEnd = '';
        
        $(function () {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                order: [[ 7, "desc"], [8, "desc"]],
                columnDefs: [
                    {
                        targets: 2, // Durum sütunu (0-based index)
                        className: 'text-center'
                    },
                    {
                        targets: [10, 11, 12, 13, 14, 16, 17, 18, 19, 20, 21], // Yeni eklenen sütunlar
                        className: 'text-center'
                    }
                ],
                ajax: {
                    url: "{{ route('barcode.index') }}",
                    data: function (d) {
                        // Tarih filtrelerini ekle
                        if (globalLabStart) d.lab_start = globalLabStart;
                        if (globalLabEnd) d.lab_end = globalLabEnd;
                        if (globalCreatedStart) d.created_start = globalCreatedStart;
                        if (globalCreatedEnd) d.created_end = globalCreatedEnd;
                        
                        // Dropdown filtreleri ekle
                        var stockFilter = $('.filter-select[data-column="0"]').val();
                        var partyFilter = $('.filter-select[data-column="1"]').val();
                        var statusFilter = $('.filter-select[data-column="2"]').val();
                        var exceptionallyApprovedFilter = $('#exceptionally-approved-filter').val();
                        var kilnFilter = $('.filter-select[data-column="14"]').val();
                        var warehouseFilter = $('.filter-select[data-column="4"]').val();
                        var companyFilter = $('.filter-select[data-column="5"]').val();
                        var createdByFilter = $('.filter-select[data-column="9"]').val();
                        
                        if (stockFilter) d.stock = stockFilter;
                        if (partyFilter) d.party_number = partyFilter;
                        if (statusFilter) d.status = statusFilter;
                        if (exceptionallyApprovedFilter) d.exceptionally_approved = exceptionallyApprovedFilter;
                        if (kilnFilter) d.kiln = kilnFilter;
                        if (warehouseFilter) d.warehouse = warehouseFilter;
                        if (companyFilter) d.company = companyFilter;
                        if (createdByFilter) d.createdBy = createdByFilter;
                        
                        console.log('AJAX data gönderiliyor:', d);
                        return d;
                    }
                },
                columns: [
                    {data: 'stock', name: 'stock.name'},
                    {data: 'party_number', name: 'party_number'},
                    {data: 'status', name: 'status'},
                    {data: 'quantity', name: 'quantity.quantity'},
                    {data: 'warehouse', name: 'warehouse.name'},
                    {data: 'company', name: 'company.name'},
                    {data: 'lab_at', name: 'lab_at'},
                    {data: 'createdAt', name: 'created_at'},
                    {data: 'action', name: 'action'},
                    {data: 'createdBy', name: 'createdBy.name'},
                    {data: 'labBy', name: 'labBy.name'},
                    {data: 'warehouseTransferredBy', name: 'warehouseTransferredBy.name'},
                    {data: 'deliveredBy', name: 'deliveredBy.name'},
                    {data: 'warehouseTransferredAt', name: 'warehouse_transferred_at'},
                    {data: 'kiln', name: 'kiln.name'},
                    {data: 'companyTransferredAt', name: 'company_transferred_at'},
                    {data: 'loadNumber', name: 'load_number'},
                    {data: 'barcodeId', name: 'id'},
                    {data: 'note', name: 'note'},
                    {data: 'labNote', name: 'lab_note'},
                    {data: 'isMerged', name: 'is_merged'},
                    {data: 'isCorrection', name: 'is_correction'},
                    {data: 'processingTime', name: 'processing_time'},
                ]
            });

            // Datepicker initialization
            $('.filter-date').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: 'bottom auto',
                clearBtn: true,
                todayBtn: 'linked',
                keyboardNavigation: true,
                forceParse: false,
                templates: {
                    leftArrow: '&laquo;',
                    rightArrow: '&raquo;'
                }
            });

            // Sütun filtreleri için event listener'lar - sadece "Filtreleri Uygula" butonuna basıldığında çalışacak
            // $('.filter-select').on('change', function () {
            //     var columnIndex = $(this).data('column');
            //     table.column(columnIndex).search(this.value).draw();
            // });

        });
        
        // Buton event listener'ları
        $(document).on('click', '[onclick="applyFilters()"]', function(e) {
            e.preventDefault();
            applyFilters();
        });
        
        $(document).on('click', '[onclick="clearFilters()"]', function(e) {
            e.preventDefault();
            clearFilters();
        });
    </script>

    <script>
        // Global fonksiyonlar - butonlar tarafından çağrılabilir
        function applyFilters() {
            console.log('applyFilters fonksiyonu çağrıldı');
            var table = $('.yajra-datatable').DataTable();
            
            // Tarih filtrelerini global değişkenlere set et
            globalLabStart = $('#lab-date-start').val();
            globalLabEnd = $('#lab-date-end').val();
            globalCreatedStart = $('#created-date-start').val();
            globalCreatedEnd = $('#created-date-end').val();
            
            console.log('Tarih filtreleri global değişkenlere set edildi:');
            console.log('Lab Başlangıç:', globalLabStart);
            console.log('Lab Bitiş:', globalLabEnd);
            console.log('Oluşturulma Başlangıç:', globalCreatedStart);
            console.log('Oluşturulma Bitiş:', globalCreatedEnd);
            
            // Tüm filtreler için DataTable'ı yeniden yükle (server-side filtering)
            console.log('Tüm filtreler için DataTable yeniden yükleniyor...');
            table.ajax.reload();
        }

        function clearFilters() {
            console.log('clearFilters fonksiyonu çağrıldı');
            var table = $('.yajra-datatable').DataTable();
            
            // Global değişkenleri temizle
            globalLabStart = '';
            globalLabEnd = '';
            globalCreatedStart = '';
            globalCreatedEnd = '';
            
            // Tüm filtreleri temizle
            $('.filter-select').val('');
            $('#exceptionally-approved-filter').val('');
            $('.filter-date').val('');
            
            // DataTable'ı yeniden yükle (filtresiz)
            table.ajax.reload();
            console.log('Filtreler temizlendi ve tablo yeniden yüklendi');
        }
    </script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
        });
        function deleteConfirmation(id) {

            swal({
                title: "Silmek istediğinize emin misiniz?",
                text: "Silme işlemi geri alınamaz!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn btn-danger btn-lg',
                confirmButtonText: "Sil",
                cancelButtonClass: 'btn btn-primary btn-lg m-l-10',
                cancelButtonText: "Vazgeç",
                buttonsStyling: false
            }).then(function (e) {
                if (e.value === true) {
                    var data = {
                        "_token": $('input[name="_token"]').val(),
                        "id": id
                    }

                    $.ajax({
                        type: 'DELETE',
                        url: "{{url('/barkod')}}/" + id,
                        data: data,
                        success: function (results) {
                            if (results) {
                                swal("Başarılı!", results.message, "success");
                                location.reload();
                            } else {
                                swal("Hata!", "Lütfen tekrar deneyin!", "error");
                            }
                        }
                    });

                } else {
                    e.dismiss;
                }

            }, function (dismiss) {
                return false;
            })
        }
    </script>
@endsection
