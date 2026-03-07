@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        .status-customer-transfer { background: #e0e7ff; color: #3730a3; }
        .status-delivered { background: #dcfce7; color: #166534; }
        
        .card-modern {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
            margin-bottom: 2rem;
            /* overflow: hidden kaldırıldı çünkü datepicker taşıyor */
        }
        .card-header-modern {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        /* KPI Cards Sync */
        .stat-card-frit {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.07);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .stat-card-frit:hover { transform: translateY(-5px); box-shadow: 0 12px 40px rgba(0,0,0,0.12); }
        .stat-card-frit::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 100px; height: 100px;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            border-radius: 0 0 0 100%;
        }
        .stat-frit-icon { font-size: 2.2rem; margin-bottom: 0.8rem; opacity: 0.9; }
        .stat-frit-number { font-size: 1.8rem; font-weight: 800; color: #1e293b; margin-bottom: 0.2rem; line-height: 1.2; }
        .stat-frit-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }
        
        .icon-blue { color: #3b82f6; }
        .icon-purple { color: #8b5cf6; }
        .icon-orange { color: #f59e0b; }
        .icon-emerald { color: #10b981; }
        
            z-index: 9999 !important;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="page-header mb-4" style="background: linear-gradient(135deg, #1e293b 0%, #475569 100%); padding: 2rem; border-radius: 20px; color: white;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 font-weight-bold mb-1"><i class="fas fa-history mr-2"></i> Frit Satış Geçmişi</h1>
                <p class="mb-0 opacity-75">Yapılan tüm satış ve sevkiyat kayıtlarını buradan inceleyebilirsiniz.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('barcode.sales.history.export', request()->all()) }}" class="btn btn-success btn-modern mr-2">
                    <i class="fas fa-file-excel"></i> Excel Olarak İndir
                </a>
                <a href="{{ route('barcode.sales.index') }}" class="btn btn-light btn-modern">
                    <i class="fas fa-arrow-left"></i> Satış Ekranına Dön
                </a>
            </div>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card-frit">
                <div class="stat-frit-icon icon-blue"><i class="fas fa-weight-hanging"></i></div>
                <div class="stat-frit-number">{{ number_format($totalWeight, 0, ',', '.') }} KG</div>
                <div class="stat-frit-label">Toplam Satış (KG)</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card-frit">
                <div class="stat-frit-icon icon-purple"><i class="fas fa-boxes"></i></div>
                <div class="stat-frit-number">{{ $totalPallets }} Adet</div>
                <div class="stat-frit-label">Toplam Satış (Palet)</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card-frit">
                <div class="stat-frit-icon icon-emerald"><i class="fas fa-building"></i></div>
                <div class="stat-frit-number" style="font-size: 1.1rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    {{ $topCustomer->company->name ?? '-' }}
                </div>
                <div class="stat-frit-label">En Çok Alım Yapan Firma</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card-frit">
                <div class="stat-frit-icon icon-orange"><i class="fas fa-cube"></i></div>
                <div class="stat-frit-number" style="font-size: 1.1rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    {{ $topProduct->stock->name ?? '-' }}
                </div>
                <div class="stat-frit-label">En Çok Satılan Ürün</div>
            </div>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="card-modern">
        <div class="card-header-modern">
            <h5 class="mb-0"><i class="fas fa-filter mr-2 text-primary"></i> Gelişmiş Filtreleme</h5>
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
                    <label>Firma</label>
                    <select id="filter-company" class="form-control select2" multiple data-placeholder="Tümü">
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Personel</label>
                    <select id="filter-user" class="form-control select2" multiple data-placeholder="Tümü">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Başlangıç Tarihi</label>
                    <input type="text" id="filter-start-date" class="form-control datepicker" placeholder="GG/AA/YYYY">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Bitiş Tarihi</label>
                    <input type="text" id="filter-end-date" class="form-control datepicker" placeholder="GG/AA/YYYY">
                </div>
                <div class="col-md-3 d-flex align-items-end mb-3">
                    <button id="btn-filter" class="btn btn-primary btn-modern mr-2">
                        <i class="fas fa-search"></i> Filtrele
                    </button>
                    <button id="btn-reset" class="btn btn-secondary btn-modern">
                        <i class="fas fa-undo"></i> Sıfırla
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- DATA TABLE --}}
    <div class="card-modern">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="history-table" style="width:100%">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Şarj No</th>
                            <th>Ürün</th>
                            <th>Firma</th>
                            <th>Personel</th>
                            <th>Miktar</th>
                            <th>Durum</th>
                            <th>İşlem Tarihi</th>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/tr.js"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Standart Select2 (Multiple support included)
            $('.select2').not('#filter-barcode-id, #filter-load-number').select2({ 
                width: '100%',
                placeholder: function() { return $(this).data('placeholder'); }
            });

            // Tagging Select2 for IDs and Load Numbers
            $('#filter-barcode-id, #filter-load-number').select2({
                tags: true,
                tokenSeparators: [',', ' '],
                width: '100%',
                placeholder: function() { return $(this).data('placeholder'); }
            });

            flatpickr(".datepicker", {
                dateFormat: "d/m/Y",
                locale: "tr",
                allowInput: true,
                disableMobile: true
            });

            const table = $('#history-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('barcode.sales.history') }}",
                    data: function(d) {
                        d.stock_id = $('#filter-stock').val();
                        d.barcode_id = $('#filter-barcode-id').val();
                        d.load_number = $('#filter-load-number').val();
                        d.company_id = $('#filter-company').val();
                        d.user_id = $('#filter-user').val();
                        d.start_date = $('#filter-start-date').val();
                        d.end_date = $('#filter-end-date').val();
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'load_number', name: 'load_number' },
                    { data: 'stock_name', name: 'stock.name' },
                    { data: 'company_name', name: 'company.name' },
                    { data: 'user_name', name: 'deliveredBy.name' },
                    { data: 'quantity_kg', name: 'quantity.quantity' },
                    { data: 'status_label', name: 'status', orderable: false, searchable: false },
                    { data: 'delivered_at', name: 'delivered_at', render: function(data) {
                        return data ? moment(data).format('DD.MM.Y HH:mm') : '-';
                    }}
                ],
                order: [[7, 'desc']],
                pageLength: 25,
                language: { url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json" }
            });


            $('#btn-filter').click(() => table.draw());
            $('#btn-reset').click(() => {
                $('#filter-stock, #filter-company, #filter-user, #filter-barcode-id, #filter-load-number').val([]).trigger('change');
                $('#filter-start-date, #filter-end-date').val('');
                table.draw();
            });
        });
    </script>
@endsection
