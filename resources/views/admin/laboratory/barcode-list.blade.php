@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('laboratory.dashboard') }}">
            <i class="fas fa-flask"></i> Laboratuvar
        </a>
    </li>
    <li class="breadcrumb-item active">
        <i class="fas fa-list"></i> Barkod Listesi
    </li>
@endsection

@section('styles')
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        body, .main-content, .modern-lab-barcode-list {
            background: #f8f9fa !important;
        }
        .modern-lab-barcode-list {
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
        .card-subtitle-modern {
            color: #6c757d;
            margin-bottom: 0;
        }
        .card-body-modern {
            padding: 2rem;
        }
        .quick-stats-modern {
            display: flex;
            justify-content: space-around;
            margin-bottom: 2rem;
            gap: 1rem;
        }
        .quick-stat-item-modern {
            text-align: center;
            padding: 1.5rem;
            border-radius: 15px;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            flex: 1;
            transition: transform 0.3s ease;
        }
        .quick-stat-item-modern:hover {
            transform: translateY(-5px);
        }
        .quick-stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .quick-stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        .action-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
            justify-content: flex-end;
        }
        .action-buttons .btn-modern,
        .action-buttons .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%);
            color: white !important;
            transition: all 0.3s ease;
            text-decoration: none;
            gap: 0.5rem;
        }
        .action-buttons .btn-modern:hover,
        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white !important;
            text-decoration: none;
        }
        /* DataTable görünümünü iyileştir */
        .dataTables_wrapper {
            margin-top: 1rem;
        }
        .dataTables_length, .dataTables_filter {
            margin-bottom: 1rem;
        }
        .dataTables_info, .dataTables_paginate {
            margin-top: 1rem;
        }
        .page-link {
            border-radius: 8px;
            margin: 0 2px;
            border: none;
            color: #667eea;
        }
        .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
        }
        /* Modal stilleri */
        .modal-header.bg-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        }
        .modal-header.bg-danger {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%) !important;
        }
        .modal-header.bg-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
        }
        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
            .card-body-modern {
                padding: 1.2rem 1rem;
            }
            .quick-stats-modern {
                flex-direction: column;
            }
            .action-buttons {
                flex-direction: column;
                width: 100%;
                gap: 0.5rem;
            }
            .action-buttons .btn-modern,
                    .action-buttons .btn {
            width: 100%;
            justify-content: center;
        }
    }
    
    /* Red Sebepleri Sütunu İyileştirmeleri */
    .badge-danger {
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    /* DataTables Tasarımı */
    .dataTables_wrapper {
        margin-top: 1rem;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    /* Tablo başlığı */
    .dataTables_scrollHead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .dataTables_scrollHead th {
        background: transparent !important;
        color: white !important;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 12px 15px !important;
        border: none !important;
        text-align: center;
    }
    
    /* Tablo gövdesi */
    .dataTables_scrollBody {
        background: #ffffff;
    }
    
    /* Tablo genişliği kontrolü */
    #barcodeTable {
        width: 100% !important;
        min-width: 1200px;
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
    }
    
    /* Tablo satırları */
    #barcodeTable tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
    }
    
    #barcodeTable tbody tr:hover {
        background: #f8f9ff;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
    }
    
    #barcodeTable tbody tr:nth-child(even) {
        background: #fafbfc;
    }
    
    #barcodeTable tbody tr:nth-child(even):hover {
        background: #f8f9ff;
    }
    
    /* Tablo hücreleri */
    #barcodeTable th,
    #barcodeTable td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 10px 15px;
        vertical-align: middle;
        border: none;
        font-size: 0.9rem;
        line-height: 1.4;
    }
    
    #barcodeTable td {
        color: #495057;
        border-bottom: 1px solid #f0f0f0;
    }
    
    /* İşlemler sütunu için özel stil */
    #barcodeTable td:last-child {
        white-space: nowrap;
        min-width: 200px;
        background: #f8f9fa;
    }
    
    /* Red sebepleri sütunu için özel stil */
    #barcodeTable td:nth-child(6) {
        max-width: 150px;
        min-width: 150px;
    }
    
    /* Badge'ler için stil */
    .badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
        white-space: nowrap;
        border-radius: 15px;
        font-weight: 600;
    }
    
    .badge-warning {
        background: #ffc107;
        color: #212529;
    }
    
    .badge-success {
        background: #28a745;
        color: white;
    }
    
    .badge-info {
        background: #17a2b8;
        color: white;
    }
    
    .badge-primary {
        background: #007bff;
        color: white;
    }
    
    .badge-danger {
        background: #dc3545;
        color: white;
    }
    
    .badge-secondary {
        background: #6c757d;
        color: white;
    }
    
    /* İşlem butonları için stil */
    .btn-group .btn {
        border-radius: 6px;
        margin: 0 2px;
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
        border: none;
        transition: all 0.2s ease;
    }
    
    .btn-group .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }
    
    .btn-success {
        background: #28a745;
        color: white;
    }
    
    .btn-info {
        background: #17a2b8;
        color: white;
    }
    
    .btn-primary {
        background: #007bff;
        color: white;
    }
    
    .btn-danger {
        background: #dc3545;
        color: white;
    }
    
    /* Tablo gövdesi scroll yok */
    .dataTables_scrollBody {
        overflow-y: visible;
    }
    
    /* Horizontal scroll */
    .dataTables_wrapper .dataTables_scroll {
        overflow-x: auto;
        border: 1px solid #dee2e6;
        border-radius: 10px;
    }
    
    .dataTables_wrapper .dataTables_scroll::-webkit-scrollbar {
        height: 8px;
    }
    
    .dataTables_wrapper .dataTables_scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .dataTables_wrapper .dataTables_scroll::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    .dataTables_wrapper .dataTables_scroll::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    </style>
@endsection

@section('content')
<div class="modern-lab-barcode-list">
    <div class="container-fluid">
        <!-- Modern Page Header -->
        <div class="page-header-modern">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title-modern">
                        <i class="fas fa-list"></i> Barkod Listesi
                    </h1>
                    <p class="page-subtitle-modern">Laboratuvar işlemleri için barkod yönetimi</p>
                </div>
                <div class="col-md-4 text-right">
                    <div class="action-buttons justify-content-end">
                        <a href="{{ route('laboratory.dashboard') }}" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i> Geri Dön
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- İşlem Butonları Açıklamaları -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-info-circle"></i> İşlem Butonları Açıklamaları
                </h3>
                <p class="card-subtitle-modern">
                    Laboratuvar işlemleri için kullanabileceğiniz butonlar ve açıklamaları
                </p>
            </div>
            <div class="card-body-modern">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="d-flex align-items-center p-3 border rounded bg-light">
                            <button class="btn btn-success btn-sm mr-3" disabled>
                                <i class="fas fa-check"></i>
                            </button>
                            <div>
                                <strong>Ön Onaylı</strong><br>
                                <small class="text-muted">Kontrol geçti, sevk onayı için hazır</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="d-flex align-items-center p-3 border rounded bg-light">
                            <button class="btn btn-info btn-sm mr-3" disabled>
                                <i class="fas fa-redo"></i>
                            </button>
                            <div>
                                <strong>Kontrol Tekrarı</strong><br>
                                <small class="text-muted">Tekrar kontrol gerekli</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="d-flex align-items-center p-3 border rounded bg-light">
                            <button class="btn btn-primary btn-sm mr-3" disabled>
                                <i class="fas fa-shipping-fast"></i>
                            </button>
                            <div>
                                <strong>Sevk Onaylı</strong><br>
                                <small class="text-muted">Sadece ön onaylı ürünler için</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="d-flex align-items-center p-3 border rounded bg-light">
                            <button class="btn btn-danger btn-sm mr-3" disabled>
                                <i class="fas fa-times"></i>
                            </button>
                            <div>
                                <strong>Reddet</strong><br>
                                <small class="text-muted">Kontrol geçemedi, işlem durduruldu</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barkod Tablosu -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-table"></i> Barkod Tablosu
                </h3>
                <p class="card-subtitle-modern">
                    Laboratuvar işlemleri için barkod verilerini görüntüleyin ve yönetin
                </p>
            </div>
            <div class="card-body-modern">
                <table id="barcodeTable" class="table table-bordered" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Stok Bilgisi</th>
                            <th>Fırın/Şarj</th>
                            <th>Miktar</th>
                            <th>Durum</th>
                            <th>Red Sebepleri</th>
                            <th>Oluşturan</th>
                            <th>Lab Personeli</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/buttons.print.min.js') }}"></script>

<script>
    window.LabConfig = {
        listRoute: '{{ route("laboratory.barcode-list") }}',
        detailRoute: '{{ route("laboratory.barcode-detail", ":id") }}',
        processRoute: '{{ route("laboratory.process-barcode") }}',
        csrfToken: '{{ csrf_token() }}',
        rejectionReasons: @json(\App\Models\RejectionReason::active()->get())
    };
</script>
<script src="{{ asset('assets/js/modules/lab-barcode-list.js') }}"></script>
@endsection
@endsection 