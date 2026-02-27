/**
 * Laboratory Barcode List JavaScript Module
 */

(function($) {
    'use strict';

    var dataTable;

    $(function() {
        // Initialize DataTable
        if ($('#barcodeTable').length > 0) {
            initializeDataTable();
        }

        // Initialize Tooltips
        initializeTooltips();

        // AJAX CSRF Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': window.LabConfig.csrfToken
            }
        });
    });

    /**
     * Initialize DataTable
     */
    function initializeDataTable() {
        dataTable = $('#barcodeTable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            autoWidth: false,
            ajax: {
                url: window.LabConfig.listRoute,
                data: function(d) {
                    // Possible future filters
                }
            },
            columns: [
                {data: 'id', name: 'id', width: '80px'},
                {data: 'stock_info', name: 'stock_info', width: '200px'},
                {data: 'load_info', name: 'load_info', width: '180px'},
                {data: 'quantity_info', name: 'quantity_info', width: '100px'},
                {data: 'status_badge', name: 'status_badge', width: '120px'},
                {data: 'rejection_reasons', name: 'rejection_reasons', orderable: false, searchable: false, width: '150px'},
                {data: 'created_info', name: 'created_info', width: '140px'},
                {data: 'lab_info', name: 'lab_info', width: '120px'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false, width: '200px'}
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

        // DataTable draw event'inde tooltip'leri yeniden başlat
        dataTable.on('draw', function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    }

    /**
     * Initialize Tooltips
     */
    function initializeTooltips() {
        $('[data-toggle="tooltip"]').tooltip();
    }

    /**
     * Helper to get button text/icon
     */
    function getButtonText(action) {
        var actionText = '';
        var actionIcon = '';
        
        switch(action) {
            case 'pre_approved':
                actionText = 'Ön Onaylı';
                actionIcon = 'check';
                break;
            case 'control_repeat':
                actionText = 'Kontrol Tekrarı';
                actionIcon = 'redo';
                break;
            case 'shipment_approved':
                actionText = 'Sevk Onaylı';
                actionIcon = 'shipping-fast';
                break;
            case 'reject':
                actionText = 'Reddet';
                actionIcon = 'times';
                break;
        }
        
        return '<i class="fas fa-' + actionIcon + '"></i> ' + actionText;
    }

    /**
     * Open Modal for Barcode Processing
     */
    window.processBarcode = function(barcodeId, action) {
        $.get(window.LabConfig.detailRoute.replace(':id', barcodeId), function(response) {
            if (response.success) {
                var barcode = response.barcode;
                
                // Process check
                let canProcess = true;
                let errorMessage = '';
                
                // Check statuses (hardcoded IDs as per existing logic)
                if (barcode.status === 1) { // Waiting
                    if (action === 'shipment_approved') {
                        canProcess = false;
                        errorMessage = 'Beklemede durumundaki barkodlar direkt sevk onaylı yapılamaz!';
                    }
                } else if (barcode.status === 3) { // Pre-approved
                    if (action === 'pre_approved') {
                        canProcess = false;
                        errorMessage = 'Ön onaylı durumundaki barkodlar tekrar ön onaylı yapılamaz!';
                    }
                } else if (barcode.status === 2) { // Control Repeat
                    if (action === 'shipment_approved' || action === 'control_repeat') {
                        canProcess = false;
                        errorMessage = 'Kontrol tekrarı durumundaki barkodlar direkt sevk onaylı veya tekrar kontrol tekrarı yapılamaz!';
                    }
                } else if (barcode.status === 4 || barcode.status === 5) { // Shipment Approved or Rejected
                    canProcess = false;
                    errorMessage = 'Sevk onaylı veya reddedildi durumundaki barkodlar işlenemez!';
                }
                
                if (!canProcess) {
                    toastr.error(errorMessage, 'İşlem Hatası', {
                        timeOut: 5000,
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
                    case 'pre_approved': actionText = 'Ön Onaylı'; actionClass = 'success'; actionIcon = 'check'; break;
                    case 'control_repeat': actionText = 'Kontrol Tekrarı'; actionClass = 'info'; actionIcon = 'redo'; break;
                    case 'shipment_approved': actionText = 'Sevk Onaylı'; actionClass = 'primary'; actionIcon = 'shipping-fast'; break;
                    case 'reject': actionText = 'Reddet'; actionClass = 'danger'; actionIcon = 'times'; break;
                }
                
                // Rejection Reasons HTML
                var rejectionReasonsHtml = '';
                if (action === 'reject') {
                    rejectionReasonsHtml = `
                    <div class="row mt-3" id="rejection-reasons-row-${barcodeId}">
                        <div class="col-md-12">
                            <h6><i class="fas fa-exclamation-triangle"></i> Red Sebepleri <span class="text-danger">*</span></h6>
                            <div class="row">
                                ${window.LabConfig.rejectionReasons.map(reason => `
                                <div class="col-md-4 mb-2">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input rejection-reason-checkbox" 
                                               id="reason_${barcodeId}_${reason.id}" value="${reason.id}">
                                        <label class="custom-control-label" for="reason_${barcodeId}_${reason.id}">
                                            ${reason.name}
                                        </label>
                                    </div>
                                </div>`).join('')}
                            </div>
                            <div class="alert alert-danger py-2 mt-2 mb-0">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Zorunlu:</strong> Red işlemi için en az bir red sebebi seçmelisiniz!
                            </div>
                        </div>
                    </div>`;
                }
                
                var html = `
                <div class="modal fade" id="processBarcodeModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-${actionClass} text-white">
                                <h5 class="modal-title"><i class="fas fa-${actionIcon}"></i> Barkod ${actionText}</h5>
                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
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
                                ${rejectionReasonsHtml}
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle"></i> <strong>Bilgi:</strong> Bu barkodu ${actionText} olarak işaretlemek üzeresiniz. İşlem geri alınamaz.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> İptal</button>
                                <button type="button" class="btn btn-${actionClass} process-confirm-btn" data-id="${barcodeId}" data-action="${action}">
                                    <i class="fas fa-${actionIcon}"></i> ${actionText}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`;
                
                $('#processBarcodeModal').remove();
                $('body').append(html);
                $('#processBarcodeModal').modal('show');
                
                $('#processBarcodeModal').on('hidden.bs.modal', function() {
                    $(this).remove();
                });
            } else {
                toastr.error('Barkod bilgileri alınamadı!');
            }
        }).fail(function() {
            toastr.error('Barkod bilgileri alınırken hata oluştu!');
        });
    };

    /**
     * Confirm Barcode Process
     */
    $(document).on('click', '.process-confirm-btn', function() {
        var barcodeId = $(this).data('id');
        var action = $(this).data('action');
        var note = $('#process-note-' + barcodeId).val();
        
        var rejectionReasons = [];
        if (action === 'reject') {
            rejectionReasons = $('.rejection-reason-checkbox[id^="reason_' + barcodeId + '_"]:checked').map(function() {
                return $(this).val();
            }).get();

            if (rejectionReasons.length === 0) {
                toastr.error('Red işlemi için en az bir red sebebi seçmelisiniz!', 'Hata', {
                    timeOut: 5000,
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-center'
                });
                return;
            }
        }
        
        var $btn = $(this);
        var $modal = $('#processBarcodeModal');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> İşleniyor...');
        
        $.ajax({
            url: window.LabConfig.processRoute,
            type: 'POST',
            data: {
                barcode_id: barcodeId,
                action: action,
                note: note,
                rejection_reasons: rejectionReasons,
                _token: window.LabConfig.csrfToken
            },
            success: function(response) {
                if (response.success) {
                    $modal.modal('hide');
                    $('#barcodeTable').DataTable().ajax.reload();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                    $btn.prop('disabled', false).html(getButtonText(action));
                }
            },
            error: function() {
                toastr.error('İşlem sırasında hata oluştu!');
                $btn.prop('disabled', false).html(getButtonText(action));
            }
        });
    });

    /**
     * View Barcode Details
     */
    window.viewBarcode = function(barcodeId) {
        $.get(window.LabConfig.detailRoute.replace(':id', barcodeId), function(response) {
            if (response.success) {
                var barcode = response.barcode;
                var statusText = response.status_text;
                
                var statusClass = 'badge-secondary';
                if (barcode.status == 1) statusClass = 'badge-warning';
                else if (barcode.status == 2) statusClass = 'badge-success';
                else if (barcode.status == 3) statusClass = 'badge-danger';
                
                var html = `
                <div class="modal fade" id="barcodeDetailModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title"><i class="fas fa-eye"></i> Barkod Detayı</h5>
                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
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
                                ${barcode.status === 5 ? `
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6><i class="fas fa-exclamation-triangle"></i> Red Sebepleri</h6>
                                        <div class="alert alert-danger">
                                            ${barcode.rejection_reasons && barcode.rejection_reasons.length > 0 ? 
                                                barcode.rejection_reasons.map(reason => 
                                                    `<span class="badge badge-danger mr-2">${reason.name}</span>`
                                                ).join('') : 
                                                '<span class="text-muted">Red sebebi belirtilmemiş</span>'
                                            }
                                        </div>
                                    </div>
                                </div>` : ''}
                                ${barcode.lab_note ? `
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6><i class="fas fa-sticky-note"></i> İşlem Notu</h6>
                                        <div class="alert alert-info">${barcode.lab_note}</div>
                                    </div>
                                </div>` : ''}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Kapat</button>
                            </div>
                        </div>
                    </div>
                </div>`;
                
                $('#barcodeDetailModal').remove();
                $('body').append(html);
                $('#barcodeDetailModal').modal('show');
                
                $('#barcodeDetailModal').on('hidden.bs.modal', function() {
                    $(this).remove();
                });
            } else {
                toastr.error('Barkod bilgileri alınamadı!');
            }
        }).fail(function() {
            toastr.error('Barkod bilgileri alınırken hata oluştu!');
        });
    };

})(jQuery);
