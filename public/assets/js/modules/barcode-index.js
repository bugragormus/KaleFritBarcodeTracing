/**
 * Barcode Index Page JavaScript Module
 */

(function($) {
    'use strict';

    // Global variables for date filters
    var globalLabStart = '';
    var globalLabEnd = '';
    var globalCreatedStart = '';
    var globalCreatedEnd = '';
    var table;

    $(function() {
        // Initialize DataTable
        initializeDataTable();
        
        // Initialize Datepickers
        initializeDatepickers();
        
        // Initialize Select2
        initializeSelect2();
        
        // Initialize Event Listeners
        initializeEventListeners();
    });

    /**
     * Initialize Yajra DataTable
     */
    function initializeDataTable() {
        table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            order: [[7, "desc"], [8, "desc"]],
            columnDefs: [
                {
                    targets: 2,
                    className: 'text-center'
                },
                {
                    targets: [10, 11, 12, 13, 14, 16, 17, 18, 19, 20, 21],
                    className: 'text-center'
                }
            ],
            ajax: {
                url: window.BarcodeConfig.indexRoute,
                data: function(d) {
                    // Add date filters
                    if (globalLabStart) d.lab_start = globalLabStart;
                    if (globalLabEnd) d.lab_end = globalLabEnd;
                    if (globalCreatedStart) d.created_start = globalCreatedStart;
                    if (globalCreatedEnd) d.created_end = globalCreatedEnd;
                    
                    // Add dropdown filters
                    var stockFilter = $('.filter-select[data-column="0"]').val();
                    var partyFilter = $('#party-number-filter').val();
                    var statusFilter = $('.filter-select[data-column="2"]').val();
                    var exceptionallyApprovedFilter = $('#exceptionally-approved-filter').val();
                    var kilnFilter = $('.filter-select[data-column="14"]').val();
                    var warehouseFilter = $('.filter-select[data-column="4"]').val();
                    var companyFilter = $('.filter-select[data-column="5"]').val();
                    var createdByFilter = $('.filter-select[data-column="9"]').val();
                    var returnedFilter = $('#returned-filter').val();
                    var barcodeIdFilter = $('#barcode-id-filter').val();
                    var loadNumberFilter = $('#load-number-filter').val();
                    
                    if (stockFilter) d.stock = stockFilter;
                    if (partyFilter) d.party_number = partyFilter;
                    if (statusFilter) d.status = statusFilter;
                    if (exceptionallyApprovedFilter) d.exceptionally_approved = exceptionallyApprovedFilter;
                    if (kilnFilter) d.kiln = kilnFilter;
                    if (warehouseFilter) d.warehouse = warehouseFilter;
                    if (companyFilter) d.company = companyFilter;
                    if (createdByFilter) d.createdBy = createdByFilter;
                    if (returnedFilter) d.returned = returnedFilter;
                    if (barcodeIdFilter) d.barcode_id = barcodeIdFilter;
                    if (loadNumberFilter) d.load_number = loadNumberFilter;
                    
                    return d;
                }
            },
            columns: [
                {data: 'stock', name: 'stock.name', searchable: false},
                {data: 'loadNumber', name: 'load_number', searchable: false},
                {data: 'status', name: 'status', searchable: false},
                {data: 'quantity', name: 'quantity.quantity', searchable: false},
                {data: 'warehouse', name: 'warehouse.name', searchable: false},
                {data: 'company', name: 'company.name', searchable: false},
                {data: 'lab_at', name: 'lab_at', searchable: false},
                {data: 'createdAt', name: 'created_at', searchable: false},
                {data: 'action', name: 'action', searchable: false},
                {data: 'createdBy', name: 'createdBy.name', searchable: false},
                {data: 'labBy', name: 'labBy.name', searchable: false},
                {data: 'warehouseTransferredBy', name: 'warehouseTransferredBy.name', searchable: false},
                {data: 'deliveredBy', name: 'deliveredBy.name', searchable: false},
                {data: 'warehouseTransferredAt', name: 'warehouse_transferred_at', searchable: false},
                {data: 'kiln', name: 'kiln.name', searchable: false},
                {data: 'companyTransferredAt', name: 'company_transferred_at', searchable: false},
                {data: 'party_number', name: 'party_number'},
                {data: 'barcodeId', name: 'id', searchable: false},
                {data: 'note', name: 'note', searchable: false},
                {data: 'labNote', name: 'lab_note', searchable: false},
                {data: 'isMerged', name: 'is_merged', searchable: false},
                {data: 'isCorrection', name: 'is_correction', searchable: false},
                {data: 'processingTime', name: 'processing_time', searchable: false},
            ]
        });
    }

    /**
     * Initialize Bootstrap Datepickers
     */
    function initializeDatepickers() {
        $('.filter-date').datepicker({
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

    /**
     * Initialize Select2
     */
    function initializeSelect2() {
        $('.filter-select').select2({
            placeholder: 'Seçiniz...',
            allowClear: true,
            width: '100%',
            theme: 'default'
        });
    }

    /**
     * Initialize Event Listeners
     */
    function initializeEventListeners() {
        $(document).on('click', '.btn-info-modern', function(e) {
            // Sadece filtreleri uygulayan butonlar için devreye girsin,
            // detay linkleri (anchor) normal şekilde çalışsın.
            if ($(this).is('button') && $(this).closest('.column-filters').length) {
                e.preventDefault();
                applyFilters();
            }
        });
        
        $(document).on('click', '.btn-secondary-modern', function(e) {
            e.preventDefault();
            clearFilters();
        });

        // Ajax CSRF Setup
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
        });
    }

    /**
     * Apply all filters and reload table
     */
    window.applyFilters = function() {
        globalLabStart = $('#lab-date-start').val();
        globalLabEnd = $('#lab-date-end').val();
        globalCreatedStart = $('#created-date-start').val();
        globalCreatedEnd = $('#created-date-end').val();
        
        table.ajax.reload();
    };

    /**
     * Clear all filters and reload table
     */
    window.clearFilters = function() {
        globalLabStart = '';
        globalLabEnd = '';
        globalCreatedStart = '';
        globalCreatedEnd = '';
        
        $('.filter-select').val('').trigger('change');
        $('#exceptionally-approved-filter').val('').trigger('change');
        $('.filter-date').val('');
        $('#barcode-id-filter').val('');
        $('#load-number-filter').val('');
        $('#party-number-filter').val('').trigger('change');
        
        table.ajax.reload();
    };

    /**
     * Confirm and delete barcode
     * @param {number} id 
     */
    window.deleteConfirmation = function(id) {
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
                $.ajax({
                    type: 'DELETE',
                    url: window.BarcodeConfig.deleteRoutePrefix + "/" + id,
                    data: {
                        "_token": $('input[name="_token"]').val(),
                        "id": id
                    },
                    success: function (results) {
                        if (results) {
                            swal("Başarılı!", results.message, "success");
                            table.ajax.reload(); // Refresh table instead of full page reload
                        } else {
                            swal("Hata!", "Lütfen tekrar deneyin!", "error");
                        }
                    }
                });
            }
        });
    };

})(jQuery);
