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
                <div class="table-responsive">
                    <table id="barcodeTable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Stok Bilgisi</th>
                                <th>Fırın/Şarj</th>
                                <th>Miktar</th>
                                <th>Durum</th>
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
// CSRF Token setup
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



let dataTable;

$(document).ready(function() {
    dataTable = $('#barcodeTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("laboratory.barcode-list") }}',
            data: function(d) {
                // Filtreler kaldırıldı, ek veri gönderilmiyor
            }
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'stock_info', name: 'stock_info'},
            {data: 'load_info', name: 'load_info'},
            {data: 'quantity_info', name: 'quantity_info'},
            {data: 'status_badge', name: 'status_badge'},
            {data: 'created_info', name: 'created_info'},
            {data: 'lab_info', name: 'lab_info'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json'
        }
    });

    // Global processBarcode fonksiyonu - modal açmak için
    window.processBarcode = function(barcodeId, action) {
        // Barkod bilgilerini AJAX ile getir
        $.get('{{ route("laboratory.barcode-detail", ":id") }}'.replace(':id', barcodeId), function(response) {
            if (response.success) {
                var barcode = response.barcode;
                
                // İşlem kontrolü
                let canProcess = true;
                let errorMessage = '';
                
                // Beklemede (1) durumundaki barkodlar için
                if (barcode.status === 1) {
                    if (action === 'shipment_approved') {
                        canProcess = false;
                        errorMessage = 'Beklemede durumundaki barkodlar direkt sevk onaylı yapılamaz!';
                    }
                }
                // Ön onaylı (3) durumundaki barkodlar için
                else if (barcode.status === 3) {
                    if (action === 'pre_approved') {
                        canProcess = false;
                        errorMessage = 'Ön onaylı durumundaki barkodlar tekrar ön onaylı yapılamaz!';
                    }
                }
                // Kontrol tekrarı (2) durumundaki barkodlar için
                else if (barcode.status === 2) {
                    if (action === 'shipment_approved' || action === 'control_repeat') {
                        canProcess = false;
                        errorMessage = 'Kontrol tekrarı durumundaki barkodlar direkt sevk onaylı veya tekrar kontrol tekrarı yapılamaz!';
                    }
                }
                // Sevk onaylı (4) veya reddedildi (5) durumundaki barkodlar için
                else if (barcode.status === 4 || barcode.status === 5) {
                    canProcess = false;
                    errorMessage = 'Sevk onaylı veya reddedildi durumundaki barkodlar işlenemez!';
                }
                
                if (!canProcess) {
                    toastr.error(errorMessage, 'İşlem Hatası', {
                        timeOut: 5000,
                        extendedTimeOut: 2000,
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-top-center'
                    });
                    return;
                }
                
                var actionText = '';
                var actionClass = '';
                var actionIcon = '';
                
                switch(action) {
                    case 'pre_approved':
                        actionText = 'Ön Onaylı';
                        actionClass = 'success';
                        actionIcon = 'check';
                        break;
                    case 'control_repeat':
                        actionText = 'Kontrol Tekrarı';
                        actionClass = 'info';
                        actionIcon = 'redo';
                        break;
                    case 'shipment_approved':
                        actionText = 'Sevk Onaylı';
                        actionClass = 'primary';
                        actionIcon = 'shipping-fast';
                        break;
                    case 'reject':
                        actionText = 'Reddet';
                        actionClass = 'danger';
                        actionIcon = 'times';
                        break;
                }
                
                var html = `
                <div class="modal fade" id="processBarcodeModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-${actionClass} text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-${actionIcon}"></i> Barkod ${actionText}
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-box"></i> Stok Bilgileri</h6>
                                        <p><strong>Kod:</strong> ${barcode.stock.code}</p>
                                        <p><strong>Ad:</strong> ${barcode.stock.name}</p>
                                        <p><strong>Miktar:</strong> ${barcode.quantity.quantity} KG</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-fire"></i> Fırın Bilgileri</h6>
                                        <p><strong>Fırın:</strong> ${barcode.kiln.name}</p>
                                        <p><strong>Şarj:</strong> ${barcode.load_number}</p>
                                        <p><strong>Durum:</strong> <span class="badge badge-warning">Bekliyor</span></p>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-user"></i> Oluşturma Bilgileri</h6>
                                        <p><strong>Oluşturan:</strong> ${barcode.created_by ? barcode.created_by.name : '-'}</p>
                                        <p><strong>Tarih:</strong> ${barcode.created_at}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-edit"></i> İşlem Notu</h6>
                                        <textarea class="form-control" id="process-note-${barcodeId}" rows="3" placeholder="İşlem notu ekleyin (opsiyonel)..."></textarea>
                                    </div>
                                </div>
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Bilgi:</strong> Bu barkodu ${actionText} olarak işaretlemek üzeresiniz. İşlem geri alınamaz.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <i class="fas fa-times"></i> İptal
                                </button>
                                <button type="button" class="btn btn-${actionClass} process-confirm-btn" data-id="${barcodeId}" data-action="${action}">
                                    <i class="fas fa-${actionIcon}"></i> ${actionText}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`;
                
                // Eski modal varsa kaldır
                $('#processBarcodeModal').remove();
                
                // Yeni modal ekle ve göster
                $('body').append(html);
                $('#processBarcodeModal').modal('show');
                
                // Modal kapandığında DOM'dan kaldır
                $('#processBarcodeModal').on('hidden.bs.modal', function() {
                    $(this).remove();
                });
            } else {
                toastr.error('Barkod bilgileri alınamadı!');
            }
        }).fail(function(xhr, status, error) {
            toastr.error('Barkod bilgileri alınırken hata oluştu!');
        });
    };
    
    // Modal içindeki onay butonuna tıklandığında
    $(document).on('click', '.process-confirm-btn', function() {
        var barcodeId = $(this).data('id');
        var action = $(this).data('action');
        var note = $('#process-note-' + barcodeId).val();
        var $btn = $(this);
        var $modal = $('#processBarcodeModal');
        
        // Butonu devre dışı bırak
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> İşleniyor...');
        
        $.ajax({
            url: '{{ route('laboratory.process-barcode') }}',
            type: 'POST',
            data: {
                barcode_id: barcodeId,
                action: action,
                note: note,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Modal'ı kapat
                    $modal.modal('hide');
                    
                    // DataTable'ı yenile
                    $('#barcodeTable').DataTable().ajax.reload();
                    
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                    $btn.prop('disabled', false).html('<i class="fas fa-' + actionIcon + '"></i> ' + actionText);
                }
            },
            error: function() {
                toastr.error('İşlem sırasında hata oluştu!');
                $btn.prop('disabled', false).html('<i class="fas fa-' + actionIcon + '"></i> ' + actionText);
            }
        });
    });
    // Global viewBarcode fonksiyonu - barkod detayını göstermek için
    window.viewBarcode = function(barcodeId) {
        $.get('{{ route("laboratory.barcode-detail", ":id") }}'.replace(':id', barcodeId), function(response) {
            if (response.success) {
                var barcode = response.barcode;
                var statusText = response.status_text;
                
                // Durum badge rengini belirle
                var statusClass = 'badge-secondary';
                if (barcode.status == 1) statusClass = 'badge-warning';
                else if (barcode.status == 2) statusClass = 'badge-success';
                else if (barcode.status == 3) statusClass = 'badge-danger';
                
                var html = `
                <div class="modal fade" id="barcodeDetailModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-eye"></i> Barkod Detayı
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-box"></i> Stok Bilgileri</h6>
                                        <p><strong>Kod:</strong> ${barcode.stock.code}</p>
                                        <p><strong>Ad:</strong> ${barcode.stock.name}</p>
                                        <p><strong>Miktar:</strong> ${barcode.quantity.quantity} KG</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-fire"></i> Fırın Bilgileri</h6>
                                        <p><strong>Fırın:</strong> ${barcode.kiln.name}</p>
                                        <p><strong>Şarj:</strong> ${barcode.load_number}</p>
                                        <p><strong>Durum:</strong> <span class="badge ${statusClass}">${statusText}</span></p>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-user"></i> Oluşturma Bilgileri</h6>
                                        <p><strong>Oluşturan:</strong> ${barcode.created_by ? barcode.created_by.name : '-'}</p>
                                        <p><strong>Tarih:</strong> ${barcode.created_at}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-flask"></i> Laboratuvar Bilgileri</h6>
                                        <p><strong>İşleyen:</strong> ${barcode.lab_by ? barcode.lab_by.name : '-'}</p>
                                        <p><strong>Tarih:</strong> ${barcode.lab_at ? barcode.lab_at : '-'}</p>
                                        <p><strong>Not:</strong> ${barcode.lab_note || '-'}</p>
                                    </div>
                                </div>
                                ${barcode.lab_note ? `
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6><i class="fas fa-sticky-note"></i> İşlem Notu</h6>
                                        <div class="alert alert-info">
                                            ${barcode.lab_note}
                                        </div>
                                    </div>
                                </div>
                                ` : ''}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <i class="fas fa-times"></i> Kapat
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`;
                
                // Eski modal varsa kaldır
                $('#barcodeDetailModal').remove();
                
                // Yeni modal ekle ve göster
                $('body').append(html);
                $('#barcodeDetailModal').modal('show');
                
                // Modal kapandığında DOM'dan kaldır
                $('#barcodeDetailModal').on('hidden.bs.modal', function() {
                    $(this).remove();
                });
            } else {
                toastr.error('Barkod bilgileri alınamadı!');
            }
        }).fail(function(xhr, status, error) {
            toastr.error('Barkod bilgileri alınırken hata oluştu!');
        });
    };
});
</script>
@endsection 