/**
 * Laboratory Bulk Process JavaScript Module
 */

(function($) {
    'use strict';

    let selectedBarcodes = [];
    let currentAction = '';
    let barcodeStatuses = {};

    $(function() {
        initializeCheckboxes();
        updateSelectedCount();
        loadBarcodeStatuses();
        initializeFilters();
        
        // AJAX CSRF Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': window.LabBulkConfig.csrfToken
            }
        });

        // Initialize Select2
        $('.form-control-modern').select2({
            theme: 'default',
            width: '100%'
        });
    });

    /**
     * Initialize Checkboxes and Click Events
     */
    function initializeCheckboxes() {
        // Select All
        $('#selectAll').change(function() {
            const isChecked = $(this).is(':checked');
            $('.barcode-item-modern').each(function() {
                const $container = $(this).closest('.col-md-6, .col-lg-4');
                if ($container.is(':visible')) {
                    $(this).find('.barcode-checkbox').prop('checked', isChecked);
                }
            });
            updateSelectedBarcodes();
            updateSelectedCount();
            updateBulkButtons();
        });

        // Individual Checkbox
        $(document).on('change', '.barcode-checkbox', function() {
            updateSelectedBarcodes();
            updateSelectedCount();
            updateBulkButtons();
            updateSelectAllState();
        });

        // Clickable Card
        $(document).on('click', '.clickable-card', function(e) {
            if ($(e.target).closest('.custom-control').length > 0) return;
            const $checkbox = $(this).find('.barcode-checkbox');
            const isChecked = $checkbox.is(':checked');
            $checkbox.prop('checked', !isChecked).trigger('change');
            $(this).toggleClass('selected', !isChecked);
        });
    }

    /**
     * Initialize Filters
     */
    function initializeFilters() {
        $('#stockFilter, #loadNumberFilter, #partyNumberFilter, #statusFilter').on('change', function() {
            filterBarcodes();
        });

        $('#clearFilter').click(function() {
            $('#stockFilter, #loadNumberFilter, #partyNumberFilter, #statusFilter').val('').trigger('change');
            showAllBarcodes();
            resetCheckboxes();
        });
    }

    /**
     * Filter Barcodes based on selections
     */
    function filterBarcodes() {
        const selectedStock = $('#stockFilter').val();
        const selectedLoadNumber = $('#loadNumberFilter').val();
        const selectedPartyNumber = $('#partyNumberFilter').val();
        const selectedStatus = $('#statusFilter').val();
        let visibleCount = 0;

        $('.barcode-item-modern').each(function() {
            const $item = $(this);
            const $container = $item.closest('.col-md-6, .col-lg-4');
            const stockName = $item.find('h6 strong').text().trim();
            
            const loadNumberText = $item.find('.stock-info').text().split('Şarj No:')[1].split('-')[0].trim();
            const loadNumber = loadNumberText.replace('#', '');
            
            const partyNumberText = $item.find('.stock-info').text().split('Parti No:')[1];
            const partyNumber = partyNumberText ? partyNumberText.trim().replace('#', '') : '';
            
            const statusBadge = $item.find('.status-badge-container .badge').text().trim();
            const statusKey = getStatusKeyFromText(statusBadge);

            const stockMatch = !selectedStock || stockName === selectedStock;
            const loadNumberMatch = !selectedLoadNumber || loadNumber === selectedLoadNumber;
            const partyNumberMatch = !selectedPartyNumber || partyNumber === selectedPartyNumber;
            const statusMatch = !selectedStatus || statusKey === selectedStatus;

            if (stockMatch && loadNumberMatch && partyNumberMatch && statusMatch) {
                $container.fadeIn(300);
                visibleCount++;
            } else {
                $container.fadeOut(300);
            }
        });

        updateFilteredCount(visibleCount);
        resetCheckboxes();
    }

    function getStatusKeyFromText(statusText) {
        const statusMap = {
            'Beklemede': '1',
            'Kontrol Tekrarı': '2',
            'Ön Onaylı': '3',
            'Sevk Onaylı': '4',
            'Reddedildi': '5'
        };
        return statusMap[statusText] || '';
    }

    function showAllBarcodes() {
        $('.barcode-item-modern').closest('.col-md-6, .col-lg-4').fadeIn(300);
        updateFilteredCount($('.barcode-item-modern').length);
    }

    function updateFilteredCount(visibleCount) {
        const totalCount = $('.barcode-item-modern').length;
        const $title = $('.card-title-modern');
        const selectedStock = $('#stockFilter').val();
        const selectedLoadNumber = $('#loadNumberFilter').val();
        const selectedPartyNumber = $('#partyNumberFilter').val();
        const selectedStatus = $('#statusFilter').val();

        if (selectedStock || selectedLoadNumber || selectedPartyNumber || selectedStatus) {
            let filters = [];
            if (selectedStock) filters.push(`"${selectedStock}" stok adı`);
            if (selectedLoadNumber) filters.push(`"#${selectedLoadNumber}" şarj no`);
            if (selectedPartyNumber) filters.push(`"#${selectedPartyNumber}" parti no`);
            if (selectedStatus) filters.push(`"${$('#statusFilter option:selected').text()}" durumu`);
            
            const filterText = filters.join(' ve ');
            $title.html(`<i class="fas fa-list mr-2"></i>Laboratuvar İşlemleri (${visibleCount}/${totalCount} adet) - <span class="text-primary filter-highlight">${filterText}</span> filtrelendi`);
        } else {
            $title.html(`<i class="fas fa-list mr-2"></i>Laboratuvar İşlemleri (${totalCount} adet)`);
        }
    }

    function resetCheckboxes() {
        $('.barcode-checkbox').prop('checked', false);
        $('#selectAll').prop('checked', false).prop('indeterminate', false);
        selectedBarcodes = [];
        updateSelectedCount();
        updateBulkButtons();
        $('.barcode-item-modern').removeClass('selected');
    }

    function updateSelectedBarcodes() {
        selectedBarcodes = $('.barcode-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
        if (selectedBarcodes.length > 0) loadBarcodeStatuses();
    }

    function updateSelectedCount() {
        $('#selectedCount').text(selectedBarcodes.length);
        let visibleCount = $('.barcode-item-modern:visible').length;
        $('#totalCount').text(visibleCount);
    }

    function loadBarcodeStatuses() {
        if (selectedBarcodes.length === 0) return;
        $.ajax({
            url: window.LabBulkConfig.statusRoute,
            type: 'POST',
            data: { barcode_ids: selectedBarcodes },
            success: function(response) {
                if (response.success) {
                    barcodeStatuses = { ...barcodeStatuses, ...response.statuses };
                    updateBulkButtons();
                }
            }
        });
    }

    function updateBulkButtons() {
        const hasSelection = selectedBarcodes.length > 0;
        let canPreApprove = hasSelection, canControlRepeat = hasSelection, canShipmentApprove = hasSelection, canReject = hasSelection;

        if (hasSelection) {
            for (let id of selectedBarcodes) {
                const status = barcodeStatuses[id];
                if (status === undefined) continue;
                if (status === 1) canShipmentApprove = false;
                else if (status === 3) canPreApprove = false;
                else if (status === 2) { canShipmentApprove = false; canControlRepeat = false; }
                else if (status === 4 || status === 5) { canPreApprove = false; canControlRepeat = false; canShipmentApprove = false; canReject = false; }
            }
        }

        $('#preApprovedBtn').prop('disabled', !canPreApprove);
        $('#controlRepeatBtn').prop('disabled', !canControlRepeat);
        $('#shipmentApprovedBtn').prop('disabled', !canShipmentApprove);
        $('#rejectBtn').prop('disabled', !canReject);

        $('#rejectionReasonsSection').toggle(canReject && hasSelection);
        
        if (!hasSelection) $('#selectionWarning').show(); else $('#selectionWarning').hide();
    }

    function updateSelectAllState() {
        let visible = $('.barcode-item-modern:visible').length;
        let checked = $('.barcode-item-modern:visible .barcode-checkbox:checked').length;
        
        if (checked === 0) $('#selectAll').prop('indeterminate', false).prop('checked', false);
        else if (checked === visible) $('#selectAll').prop('indeterminate', false).prop('checked', true);
        else $('#selectAll').prop('indeterminate', true);
    }

    /**
     * Confirmation Flow
     */
    window.showConfirmation = function(action) {
        if (selectedBarcodes.length === 0) {
            toastr.warning('Hiçbir barkod seçili değil!');
            return;
        }

        if (action === 'reject' && $('.rejection-reason-checkbox:checked').length === 0) {
            toastr.error('Red işlemi için en az bir red sebebi seçmelisiniz!');
            return;
        }

        currentAction = action;
        let actionText = '';
        switch(action) {
            case 'pre_approved': actionText = 'Ön Onaylı'; break;
            case 'control_repeat': actionText = 'Kontrol Tekrarı'; break;
            case 'shipment_approved': actionText = 'Sevk Onaylı'; break;
            case 'reject': actionText = 'Reddet'; break;
        }

        $('#confirmAction').text(actionText);
        $('#confirmCount').text(selectedBarcodes.length);
        
        let stockInfo = [];
        $('.barcode-item-modern').each(function() {
            if ($(this).find('.barcode-checkbox').is(':checked')) {
                const name = $(this).find('.stock-info strong').text().trim();
                const load = $(this).find('.stock-info').text().split('Şarj No:')[1].split('-')[0].trim();
                stockInfo.push(`${name} [${load}]`);
            }
        });
        $('#confirmStockInfo').text([...new Set(stockInfo)].join(' - '));
        $('#confirmNote').text($('#bulkNote').val() || 'Not yok');
        
        if (action === 'reject') {
            const reasons = $('.rejection-reason-checkbox:checked').map(function() { return $(this).next('label').text(); }).get();
            $('#confirmRejectionReasons').text(reasons.join(', '));
            $('#rejectionReasonsRow').show();
        } else {
            $('#rejectionReasonsRow').hide();
        }

        $('#resultDisplay').removeClass('show');
        $('#inlineConfirmation').addClass('show');
        $('html, body').animate({ scrollTop: $('#inlineConfirmation').offset().top - 100 }, 500);
    };

    window.hideConfirmation = function() { $('#inlineConfirmation').removeClass('show'); };
    window.hideResult = function() { $('#resultDisplay').removeClass('show'); };

    $('#confirmProcessBtn').click(function() {
        hideConfirmation();
        const note = $('#bulkNote').val();
        $('#resultContent').html('<div class="text-center"><div class="spinner-border text-primary"></div><p class="mt-2">İşleniyor...</p></div>');
        $('#resultDisplay').addClass('show');
        $('html, body').animate({ scrollTop: $('#resultDisplay').offset().top - 100 }, 500);

        const reasons = $('.rejection-reason-checkbox:checked').map(function() { return $(this).val(); }).get();

        $.ajax({
            url: window.LabBulkConfig.processRoute,
            type: 'POST',
            data: {
                barcode_ids: selectedBarcodes,
                action: currentAction,
                note: note,
                rejection_reasons: reasons
            },
            success: function(response) {
                if (response.success) showSuccessResult(response);
                else showErrorResult(response);
            },
            error: function() { showErrorResult({ message: 'Sistem hatası!' }); }
        });
    });

    function showSuccessResult(response) {
        $('#resultContent').html(`
            <div class="alert alert-success"><h6>İşlem Başarılı!</h6><p>${response.message}</p></div>
            <div class="result-stats">
                <div class="result-stat"><h4>${response.processed}</h4><p>Başarılı</p></div>
                <div class="result-stat"><h4>${response.errors.length}</h4><p>Hata</p></div>
            </div>
            ${response.errors.length > 0 ? `<div class="error-list"><h6>Hatalar:</h6><ul>${response.errors.map(e => `<li>${e}</li>`).join('')}</ul></div>` : ''}
        `);
    }

    function showErrorResult(response) {
        $('#resultDisplay').addClass('error');
        $('#resultContent').html(`<div class="alert alert-danger"><h6>Hata!</h6><p>${response.message}</p></div>`);
    }

})(jQuery);
