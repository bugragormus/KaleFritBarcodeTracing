/**
 * Laboratory Dashboard JavaScript Module
 */

(function($) {
    'use strict';

    $(function() {
        initializeDateFilters();
        initializeBarcodeActions();
        
        // AJAX CSRF Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': window.LabDashConfig.csrfToken
            }
        });

        // Auto Refresh
        setInterval(function() {
            if (window.location.pathname === '/laboratuvar') {
                location.reload();
            }
        }, 300000); // 5 minutes
    });

    /**
     * Date Filter Initialization
     */
    function initializeDateFilters() {
        const dateFilterForm = document.getElementById('kpi-date-filter');
        if (dateFilterForm) {
            dateFilterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                
                if (!startDate || !endDate) {
                    toastr.warning('Lütfen başlangıç ve bitiş tarihlerini seçin.');
                    return;
                }
                if (startDate > endDate) {
                    toastr.error('Başlangıç tarihi bitiş tarihinden büyük olamaz.');
                    return;
                }
                
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.set('start_date', startDate);
                currentUrl.searchParams.set('end_date', endDate);
                window.location.href = currentUrl.toString();
            });
        }
    }

    /**
     * Date Range Shortcuts
     */
    window.setDateRange = function(range) {
        const today = new Date();
        const turkeyTime = new Date(today.toLocaleString("en-US", {timeZone: "Europe/Istanbul"}));
        let startDate, endDate;
        
        switch(range) {
            case 'today':
                startDate = turkeyTime.toISOString().split('T')[0];
                endDate = turkeyTime.toISOString().split('T')[0];
                break;
            case 'yesterday':
                const yesterday = new Date(turkeyTime);
                yesterday.setDate(turkeyTime.getDate() - 1);
                startDate = yesterday.toISOString().split('T')[0];
                endDate = yesterday.toISOString().split('T')[0];
                break;
            case 'week':
                const weekStart = new Date(turkeyTime);
                weekStart.setDate(turkeyTime.getDate() - turkeyTime.getDay());
                startDate = weekStart.toISOString().split('T')[0];
                endDate = turkeyTime.toISOString().split('T')[0];
                break;
            case 'month':
                startDate = new Date(turkeyTime.getFullYear(), turkeyTime.getMonth(), 1).toISOString().split('T')[0];
                endDate = turkeyTime.toISOString().split('T')[0];
                break;
        }
        
        document.getElementById('start_date').value = startDate;
        document.getElementById('end_date').value = endDate;
        document.getElementById('kpi-date-filter').submit();
    };

    window.resetDateFilter = function() {
        const today = new Date();
        const turkeyTime = new Date(today.toLocaleString("en-US", {timeZone: "Europe/Istanbul"}));
        const thirtyDaysAgo = new Date(turkeyTime);
        thirtyDaysAgo.setDate(turkeyTime.getDate() - 30);
        
        document.getElementById('start_date').value = thirtyDaysAgo.toISOString().split('T')[0];
        document.getElementById('end_date').value = turkeyTime.toISOString().split('T')[0];
        document.getElementById('kpi-date-filter').submit();
    };

    /**
     * Barcode Processing Actions
     */
    function initializeBarcodeActions() {
        $(document).on('click', '.process-barcode-btn', function() {
            const barcodeId = $(this).data('id');
            const action = $(this).data('action');
            
            $.get(window.LabDashConfig.detailRoute.replace(':id', barcodeId), function(response) {
                if (response.success) {
                    const barcode = response.barcode;
                    if (!validateProcess(barcode, action)) return;
                    showProcessModal(barcode, action);
                } else {
                    toastr.error('Barkod bilgileri alınamadı!');
                }
            }).fail(function() {
                toastr.error('Barkod bilgileri alınırken hata oluştu!');
            });
        });

        $(document).on('click', '.process-confirm-btn', function() {
            handleProcessConfirm($(this));
        });
    }

    function validateProcess(barcode, action) {
        let canProcess = true, errorMessage = '';
        if (barcode.status === 1 && action === 'shipment_approved') {
            canProcess = false; errorMessage = 'Beklemede durumundaki barkodlar direkt sevk onaylı yapılamaz!';
        } else if (barcode.status === 3 && action === 'pre_approved') {
            canProcess = false; errorMessage = 'Ön onaylı durumundaki barkodlar tekrar ön onaylı yapılamaz!';
        } else if (barcode.status === 2 && (action === 'shipment_approved' || action === 'control_repeat')) {
            canProcess = false; errorMessage = 'Kontrol tekrarı durumundaki barkodlar direkt sevk onaylı veya tekrar kontrol tekrarı yapılamaz!';
        } else if (barcode.status === 4 || barcode.status === 5) {
            canProcess = false; errorMessage = 'Sevk onaylı veya reddedildi durumundaki barkodlar işlenemez!';
        }

        if (!canProcess) {
            toastr.error(errorMessage, 'İşlem Hatası');
            return false;
        }
        return true;
    }

    function showProcessModal(barcode, action) {
        let actionText = '', actionClass = '', actionIcon = '';
        switch(action) {
            case 'pre_approved': actionText = 'Ön Onaylı'; actionClass = 'success'; actionIcon = 'check'; break;
            case 'control_repeat': actionText = 'Kontrol Tekrarı'; actionClass = 'info'; actionIcon = 'redo'; break;
            case 'shipment_approved': actionText = 'Sevk Onaylı'; actionClass = 'primary'; actionIcon = 'shipping-fast'; break;
            case 'reject': actionText = 'Reddet'; actionClass = 'danger'; actionIcon = 'times'; break;
        }

        let rejectionHtml = '';
        if (action === 'reject') {
            rejectionHtml = `
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h6><i class="fas fa-exclamation-triangle"></i> Red Sebepleri <span class="text-danger">*</span></h6>
                        <div class="row">
                            ${window.LabDashConfig.rejectionReasons.map(reason => `
                                <div class="col-md-4 mb-2">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input rejection-reason-checkbox" 
                                               id="reason_${barcode.id}_${reason.id}" value="${reason.id}">
                                        <label class="custom-control-label" for="reason_${barcode.id}_${reason.id}">${reason.name}</label>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                        <div class="alert alert-danger py-2 mt-2 mb-0">Zorunlu: Red işlemi için en az bir red sebebi seçmelisiniz!</div>
                    </div>
                </div>`;
        }

        const html = `
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
                                    <p><strong>Durum:</strong> <span class="badge badge-${barcode.status === 1 ? 'warning' : (barcode.status === 3 ? 'success' : 'info')}">${barcode.status_name}</span></p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6"><h6><i class="fas fa-user"></i> Oluşturma Bilgileri</h6><p><strong>Oluşturan:</strong> ${barcode.created_by ? barcode.created_by.name : '-'}</p><p><strong>Tarih:</strong> ${barcode.created_at}</p></div>
                                <div class="col-md-6"><h6><i class="fas fa-edit"></i> İşlem Notu</h6><textarea class="form-control" id="process-note-${barcode.id}" rows="3" placeholder="İşlem notu ekleyin..."></textarea></div>
                            </div>
                            ${rejectionHtml}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                            <button type="button" class="btn btn-${actionClass} process-confirm-btn" data-id="${barcode.id}" data-action="${action}">${actionText}</button>
                        </div>
                    </div>
                </div>
            </div>`;
        
        $('#processBarcodeModal').remove();
        $('body').append(html);
        $('#processBarcodeModal').modal('show');
    }

    function handleProcessConfirm($btn) {
        const id = $btn.data('id');
        const action = $btn.data('action');
        const note = $('#process-note-' + id).val();
        let reasons = [];

        if (action === 'reject') {
            reasons = $('.rejection-reason-checkbox:checked').map(function() { return $(this).val(); }).get();
            if (reasons.length === 0) {
                toastr.error('En az bir red sebebi seçmelisiniz!');
                return;
            }
        }

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> İşleniyor...');
        
        $.post(window.LabDashConfig.processRoute, {
            barcode_id: id,
            action: action,
            note: note,
            rejection_reasons: reasons
        }, function(response) {
            if (response.success) {
                $('#processBarcodeModal').modal('hide');
                $('#barcode-row-' + id).fadeOut(400, function() { $(this).remove(); });
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
                $btn.prop('disabled', false).text('Tekrar Dene');
            }
        }).fail(function() {
            toastr.error('İşlem sırasında hata oluştu!');
            $btn.prop('disabled', false).text('Tekrar Dene');
        });
    }

    window.refreshDashboard = function() { location.reload(); };

})(jQuery);
