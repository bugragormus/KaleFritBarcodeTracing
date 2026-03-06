@extends('layouts.granilya')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
    <style>
        body, .main-content, .modern-barcode-management {
            background: #f8f9fa !important;
        }
        .modern-barcode-management {
            padding: 2rem 0;
        }
        
        .page-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .page-header-modern::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .page-title-modern {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            letter-spacing: -0.5px;
        }
        
        .page-title-modern i {
            margin-right: 1.25rem;
            font-size: 2.25rem;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
        }
        
        .page-subtitle-modern {
            font-size: 1.15rem;
            opacity: 0.9;
            margin-bottom: 0;
            font-weight: 500;
        }
        
        .card-modern {
            background: #ffffff;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(233, 236, 239, 0.5);
            overflow: hidden;
            margin-bottom: 2.5rem;
            transition: transform 0.3s ease;
        }
        
        .card-header-modern {
            background: #ffffff;
            padding: 2rem 2.5rem;
            border-bottom: 1px solid #f1f3f5;
        }
        
        .card-title-modern {
            font-size: 1.5rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 0;
            display: flex;
            align-items: center;
        }
        
        .card-title-modern i {
            margin-right: 0.75rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .column-filters {
            background: #fcfcfd;
            padding: 2rem 2.5rem;
            border-bottom: 1px solid #f1f3f5;
        }
        
        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .filter-header h6 {
            margin: 0;
            font-weight: 700;
            color: #495057;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
        }
        
        .filter-header h6 i {
            margin-right: 0.75rem;
            color: #667eea;
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1.5rem;
        }
        
        .filter-item {
            display: flex;
            flex-direction: column;
        }
        
        .filter-label {
            font-weight: 700;
            color: #adb5bd;
            margin-bottom: 0.75rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .filter-select, .filter-input, .filter-date {
            border: 2px solid #eef2f7;
            border-radius: 12px;
            padding: 0.85rem 1.25rem;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #ffffff;
            color: #495057;
            width: 100%;
        }
        
        .filter-select:focus, .filter-input:focus, .filter-date:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
            background: #ffffff;
        }
        
        .btn-modern {
            border-radius: 12px;
            padding: 0.85rem 1.75rem;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            text-decoration: none !important;
            font-size: 0.95rem;
        }
        
        .btn-info-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary-modern {
            background: #f8f9fa;
            color: #495057;
            border: 2px solid #eef2f7;
        }
        
        .btn-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .btn-info-modern:hover {
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .status-badge {
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        
        .status-waiting { background: #fff4e6; color: #fd7e14; }
        .status-pre-approved { background: #e6fcf5; color: #099268; }
        .status-shipment-approved { background: #e7f5ff; color: #1c7ed6; }
        .status-rejected { background: #fff5f5; color: #fa5252; }
        .status-exceptional { background: #f3f0ff; color: #7950f2; }
        
        .yajra-datatable {
            border: none !important;
        }
        
        .yajra-datatable thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #eef2f7 !important;
            color: #adb5bd;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            padding: 1.5rem 1.25rem !important;
        }
        
        .yajra-datatable tbody td {
            padding: 1.5rem 1.25rem !important;
            vertical-align: middle;
            color: #495057;
            font-weight: 500;
            border-bottom: 1px solid #f1f3f5 !important;
        }
        
        .btn-xs-modern {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            border-radius: 10px;
        }
        
        .table-action-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            margin: 0 0.25rem;
            border: none;
            background: #f8f9fa;
            color: #495057;
        }
        
        .table-action-btn:hover {
            transform: scale(1.1);
            background: #667eea;
            color: white;
        }
        
        .btn-delete-item:hover {
            background: #fa5252;
        }

        @media (max-width: 768px) {
            .page-header-modern {
                padding: 1.5rem;
                border-radius: 15px;
            }
            .page-title-modern {
                font-size: 1.75rem;
            }
            .filter-grid {
                grid-template-columns: 1fr;
            }
            .filter-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            .filter-actions {
                width: 100%;
            }
            .filter-actions .btn-modern {
                flex: 1;
                justify-content: center;
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
                    <div class="col-md-12">
                        <h1 class="page-title-modern">
                            <i class="fas fa-boxes"></i> Granilya Stok Durumu
                        </h1>
                        <p class="page-subtitle-modern">Sistemdeki tüm granilya üretimlerini (paletleri) ve stok durumlarını yönetin</p>
                    </div>
                </div>
            </div>

            <!-- Filters Card -->
            <div class="card-modern">
                <div class="column-filters">
                    <div class="filter-header">
                        <h6><i class="fas fa-filter"></i> SÜTUN FİLTRELERİ</h6>
                        <div class="filter-actions">
                            <button class="btn-modern btn-info-modern" onclick="applyFilters()">
                                <i class="fas fa-check"></i> Filtreleri Uygula
                            </button>
                            <button class="btn-modern btn-secondary-modern" onclick="clearFilters()">
                                <i class="fas fa-times"></i> Filtreleri Temizle
                            </button>
                        </div>
                    </div>
                    <div class="filter-grid">
                        <div class="filter-item">
                            <label class="filter-label">Frit Kodu</label>
                            <select class="filter-select select2" id="stock_idFilter" multiple="multiple" data-placeholder="Tüm Fritler">
                                @foreach($stocks as $stock)
                                    <option value="{{ $stock->id }}">{{ $stock->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Şarj No</label>
                            <input type="text" class="filter-input" id="load_numberFilter" placeholder="Şarj numarası giriniz">
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Durum</label>
                            <select class="filter-select select2" id="statusFilter" multiple="multiple" data-placeholder="Tüm Durumlar">
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Müşteri Tipi</label>
                            <select class="filter-select select2" id="customer_typeFilter" multiple="multiple" data-placeholder="Tüm Tipler">
                                <option value="İç Müşteri">İç Müşteri</option>
                                <option value="Dış Müşteri">Dış Müşteri</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Tane Boyutu</label>
                            <select class="filter-select select2" id="size_idFilter" multiple="multiple" data-placeholder="Tüm Boyutlar">
                                @foreach($sizes as $size)
                                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Kırıcı Makina</label>
                            <select class="filter-select select2" id="crusher_idFilter" multiple="multiple" data-placeholder="Tüm Makinalar">
                                @foreach($crushers as $crusher)
                                    <option value="{{ $crusher->id }}">{{ $crusher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Oluşturan</label>
                            <select class="filter-select select2" id="user_idFilter" multiple="multiple" data-placeholder="Tüm Kullanıcılar">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Palet No</label>
                            <input type="text" class="filter-input" id="pallet_numberFilter" placeholder="Örn: 1, 2, 3">
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Üretim Tarihi Bşl.</label>
                            <input type="text" class="filter-date" id="created_date_startFilter" placeholder="Başlangıç Tarihi" autocomplete="off">
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Üretim Tarihi Bitiş</label>
                            <input type="text" class="filter-date" id="created_date_endFilter" placeholder="Bitiş Tarihi" autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="card-body-modern p-0">
                    <div class="table-responsive">
                        <table class="table yajra-datatable w-100 m-0">
                            <thead>
                                <tr>
                                    <th class="pl-5">PALET NO</th>
                                    <th>FRİT KODU</th>
                                    <th>ŞARJ NO</th>
                                    <th>BOYUT</th>
                                    <th>MİKTAR</th>
                                    <th>OLUŞTURAN</th>
                                    <th class="text-center">DURUM</th>
                                    <th>ÜRETİM TARİHİ</th>
                                    <th class="text-center pr-5">İŞLEMLER</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productions as $prod)
                                <tr>
                                    <td class="pl-5">
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('granilya.production.show', $prod->id) }}" style="color: #667eea; font-weight: 700; font-size: 1.1rem;">
                                                {{ $prod->pallet_number }}
                                            </a>
                                            @if($prod->is_correction)
                                                <span class="badge badge-info ml-2" style="background-color: #f59f00; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem;">
                                                    <i class="fas fa-tools" title="Düzeltme"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $prod->stock ? $prod->stock->name : '-' }}</td>
                                    <td>{{ $prod->load_number }}</td>
                                    <td>{{ $prod->size ? $prod->size->name : '-' }}</td>
                                    <td>{{ number_format($prod->used_quantity, 0, ',', '.') }} KG</td>
                                    <td>{{ $prod->user ? $prod->user->name : '-' }}</td>
                                    <td class="text-center">
                                        {!! $prod->status_badge !!}
                                        @if(!in_array($prod->status, [\App\Models\GranilyaProduction::STATUS_REJECTED, \App\Models\GranilyaProduction::STATUS_CUSTOMER_TRANSFER, \App\Models\GranilyaProduction::STATUS_SHIPPED]))
                                            @php
                                                $baseNum = $prod->base_pallet_number;
                                                $grpWeight = \App\Models\GranilyaProduction::where('pallet_number', 'LIKE', $baseNum . '-%')
                                                    ->whereNotIn('status', [\App\Models\GranilyaProduction::STATUS_REJECTED, \App\Models\GranilyaProduction::STATUS_CORRECTED])
                                                    ->sum('used_quantity');
                                            @endphp
                                            @if($grpWeight < 1000)
                                                <small class="text-muted d-block mt-2" style="font-size: 10px; font-weight: 700;">
                                                    <i class="fas fa-layer-group"></i> {{ round($grpWeight) }}/1000 KG
                                                </small>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ $prod->created_at ? $prod->created_at->format('d.m.Y H:i') : '-' }}</td>
                                    <td class="text-center pr-5">
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('granilya.production.show', $prod->id) }}" class="table-action-btn" title="Detay">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('granilya.production.history', $prod->id) }}" class="table-action-btn" title="Hareketler">
                                                <i class="fas fa-history"></i>
                                            </a>
                                            <button type="button" class="table-action-btn btn-delete-item" onclick="deletePallet({{ $prod->id }})" title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/tr.js"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: function() { return $(this).data('placeholder'); },
                allowClear: true,
                width: '100%'
            });

            flatpickr(".filter-date", {
                dateFormat: "d.m.Y",
                locale: "tr",
                allowInput: true,
                disableMobile: true
            });

            $('.yajra-datatable').DataTable({
                responsive: true,
                language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json' },
                order: [[ 7, "desc" ]],
                pageLength: 25,
                dom: '<"row p-4"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row p-4"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            });

            const urlParams = new URLSearchParams(window.location.search);
            const fields = ['stock_id', 'load_number', 'status', 'customer_type', 'size_id', 'crusher_id', 'user_id', 'created_date_start', 'created_date_end', 'pallet_number'];
            
            fields.forEach(field => {
                const values = urlParams.getAll(field + '[]');
                if(values.length > 0) {
                    $('#' + field + 'Filter').val(values).trigger('change');
                } else {
                    const val = urlParams.get(field);
                    if(val) { $('#' + field + 'Filter').val(val).trigger('change'); }
                }
            });

            $('.filter-input').on('keypress', function(e) {
                if(e.which == 13) applyFilters();
            });
        });

        function applyFilters() {
            let params = new URLSearchParams();
            const multiFields = ['stock_id', 'status', 'customer_type', 'size_id', 'crusher_id', 'user_id'];
            multiFields.forEach(field => {
                let selections = $('#' + field + 'Filter').val();
                if(selections && selections.length > 0) {
                    if(Array.isArray(selections)) {
                        selections.forEach(val => params.append(field + '[]', val));
                    } else { params.append(field, selections); }
                }
            });

            const singleFields = ['load_number', 'created_date_start', 'created_date_end', 'pallet_number'];
            singleFields.forEach(field => {
                let val = $('#' + field + 'Filter').val();
                if(val) params.append(field, val);
            });

            window.location.href = window.location.pathname + '?' + params.toString();
        }

        function clearFilters() {
            window.location.href = window.location.pathname;
        }

        function deletePallet(id) {
            swal({
                title: "Silmek istediğinize emin misiniz?",
                text: "Bu palet kaydı kalıcı olarak silinecektir!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn btn-danger btn-lg',
                confirmButtonText: "Evet, Sil",
                cancelButtonClass: 'btn btn-secondary btn-lg m-l-10',
                cancelButtonText: "Vazgeç",
                buttonsStyling: false
            }).then(function (result) {
                if (result.value === true) {
                    $.ajax({
                        url: '/granilya/palet/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                swal({ title: "Başarılı!", text: response.message, type: "success" }).then(function() { location.reload(); });
                            } else { swal("Hata!", response.message, "error"); }
                        },
                        error: function(xhr) { swal("Hata!", "İşlem sırasında bir hata oluştu.", "error"); }
                    });
                }
            });
        }
    </script>
@endsection