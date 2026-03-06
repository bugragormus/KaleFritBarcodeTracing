@extends('layouts.app')

@section('styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
    <style>
        .status-badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            min-width: 100px;
            text-align: center;
        }
        .status-shipment-approved { background: #e0f2fe; color: #0369a1; }
        .status-rejected { background: #fee2e2; color: #b91c1c; }
        .status-customer-transfer { background: #e0e7ff; color: #3730a3; }
        
        .card-modern {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
            margin-bottom: 2rem;
        }
        .card-header-modern {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
        }
        .btn-modern {
            border-radius: 10px;
            padding: 0.6rem 1.2rem;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-modern:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }

        .step-num {
            width: 35px;
            height: 35px;
            background: #3b82f6;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        @media (min-width: 992px) {
            .border-right-lg { border-right: 1px solid #e9ecef; }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="page-header mb-4" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 2rem; border-radius: 20px; color: white;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 font-weight-bold mb-1"><i class="fas fa-barcode mr-2"></i> Frit Toplu Satış Ekranı</h1>
                <p class="mb-0 opacity-75">Sevk Onaylı ve Reddedildi durumundaki ürünleri firmalara toplu olarak satabilirsiniz.</p>
            </div>
            <a href="{{ route('barcode.sales.history') }}" class="btn btn-light btn-modern">
                <i class="fas fa-history"></i> Satış Geçmişi
            </a>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="card-modern">
        <div class="card-header-modern">
            <h5 class="mb-0"><i class="fas fa-filter mr-2 text-primary"></i> Filtreleme</h5>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label>Stok Kodu/Adı</label>
                    <select id="filter-stock" class="form-control select2" multiple data-placeholder="Tümü">
                        @foreach($stocks as $stock)
                            <option value="{{ $stock->id }}">{{ $stock->code }} - {{ $stock->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Barkod No (ID)</label>
                    <select id="filter-barcode-id" class="form-control select2" multiple data-placeholder="Tümü (ID Girip Enter'a Basın)">
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Şarj No</label>
                    <select id="filter-load-number" class="form-control select2" multiple data-placeholder="Tümü (Şarj No Girip Enter'a Basın)">
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Durum</label>
                    <select id="filter-status" class="form-control select2" multiple data-placeholder="Tümü (Satışa Uygun)">
                        <option value="{{ \App\Models\Barcode::STATUS_SHIPMENT_APPROVED }}">Sevk Onaylı</option>
                        <option value="{{ \App\Models\Barcode::STATUS_REJECTED }}">Reddedildi</option>
                        <option value="{{ \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER }}">Müşteri Transfer (Yolda)</option>
                    </select>
                </div>
                <div class="col-md-12 text-right">
                    <button id="btn-filter" class="btn btn-primary btn-modern">
                        <i class="fas fa-search"></i> Filtrele
                    </button>
                    <button id="btn-reset" class="btn btn-secondary btn-modern">
                        <i class="fas fa-undo"></i> Sıfırla
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card-modern shadow-sm" style="border-left: 5px solid #3b82f6; background: #fdfdfe;">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0 border-right-lg">
                    <div class="d-flex align-items-start">
                        <div class="step-num mr-3 shadow-sm">1</div>
                        <div class="flex-grow-1">
                            <h6 class="font-weight-bold mb-1" style="color: #1e293b;">Firma Seçimi</h6>
                            <p class="text-muted small mb-3">Satış yapılacak müşteriyi listeden belirleyin</p>
                            <select id="sale-company" class="form-control select2">
                                <option value="">Firma Seçiniz...</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="d-flex align-items-start pl-lg-4">
                        <div class="step-num mr-3 shadow-sm" style="background: #10b981;">2</div>
                        <div class="flex-grow-1">
                            <h6 class="font-weight-bold mb-1" style="color: #1e293b;">İşlem Seçimi</h6>
                            <p class="text-muted small mb-3">Seçili olan barkodların durumunu güncelleyin</p>
                            <div class="d-flex gap-3 flex-wrap">
                                <button type="button" class="btn btn-warning btn-modern btn-sale shadow-sm px-4" data-status="{{ \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER }}" style="background: #f59e0b; border: none;">
                                    <i class="fas fa-truck mr-2"></i> Müşteri Transfer
                                </button>
                                <button type="button" class="btn btn-success btn-modern btn-sale shadow-sm px-4" data-status="{{ \App\Models\Barcode::STATUS_DELIVERED }}" style="background: #10b981; border: none;">
                                    <i class="fas fa-check-double mr-2"></i> Teslim Edildi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DATA TABLE --}}
    <div class="card-modern">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="sales-table" style="width:100%">
                    <thead class="bg-light">
                        <tr>
                            <th width="40"><input type="checkbox" id="check-all"></th>
                            <th>ID</th>
                            <th>Şarj No</th>
                            <th>Ürün</th>
                            <th>Fırın</th>
                            <th>Miktar</th>
                            <th>Durum</th>
                            <th>Tarih</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Standart Select2
            $('.select2').not('#filter-barcode-id, #filter-load-number').select2({ 
                width: '100%',
                placeholder: function() { return $(this).data('placeholder'); }
            });

            // Tagging Select2
            $('#filter-barcode-id, #filter-load-number').select2({
                tags: true,
                tokenSeparators: [',', ' '],
                width: '100%',
                placeholder: function() { return $(this).data('placeholder'); }
            });

            const table = $('#sales-table').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ route('barcode.sales.index') }}",
                    data: function(d) {
                        d.stock_id = $('#filter-stock').val();
                        d.barcode_id = $('#filter-barcode-id').val();
                        d.load_number = $('#filter-load-number').val();
                        d.status = $('#filter-status').val();
                    }
                },
                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                    { data: 'id', name: 'id' },
                    { data: 'load_number', name: 'load_number' },
                    { data: 'stock_name', name: 'stock.name' },
                    { data: 'kiln_name', name: 'kiln.name' },
                    { data: 'quantity_kg', name: 'quantity.quantity' },
                    { data: 'status_label', name: 'status' },
                    { data: 'created_at', name: 'created_at', render: function(data) {
                        return moment(data).format('DD.MM.Y HH:mm');
                    }}
                ],
                order: [[1, 'desc']],
                pageLength: 25,
                language: { url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json" }
            });

            $('#btn-filter').click(() => table.draw());
            $('#btn-reset').click(() => {
                $('#filter-stock, #filter-status, #filter-barcode-id, #filter-load-number').val([]).trigger('change');
                table.draw();
            });

            $('#check-all').click(function() {
                $('.barcode-checkbox').prop('checked', this.checked);
            });

            $('.btn-sale').click(function() {
                const targetStatus = $(this).data('status');
                const companyId = $('#sale-company').val();
                const selectedIds = $('.barcode-checkbox:checked').map(function() { return $(this).val(); }).get();

                if (selectedIds.length === 0) {
                    Swal.fire('Uyarı', 'Lütfen en az bir barkod seçin.', 'warning');
                    return;
                }

                if (!companyId) {
                    Swal.fire('Uyarı', 'Lütfen bir firma seçin.', 'warning');
                    return;
                }

                const statusText = targetStatus == {{ \App\Models\Barcode::STATUS_DELIVERED }} ? 'Teslim Edildi' : 'Müşteri Transfer';

                Swal.fire({
                    title: 'Emin misiniz?',
                    text: `${selectedIds.length} adet barkod "${statusText}" durumuna getirilecek.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Evet, Onayla',
                    cancelButtonText: 'İptal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('barcode.sales.store') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                barcode_ids: selectedIds,
                                company_id: companyId,
                                status: targetStatus
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Başarılı', response.message, 'success');
                                    table.draw();
                                    $('#check-all').prop('checked', false);
                                } else {
                                    Swal.fire('Hata', response.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Hata', 'İşlem sırasında bir hata oluştu.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
