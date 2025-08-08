@extends('layouts.app')

@section('styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <style>
        body, .main-content, .modern-barcode-history-index {
            background: #f8f9fa !important;
        }
        .modern-barcode-history-index {
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
        
        .btn-modern {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }
        
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-success-modern {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-secondary-modern {
            background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%);
            color: white;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
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
        
        .form-group-modern {
            margin-bottom: 1.5rem;
        }
        
        .form-label-modern {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .form-control-modern {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
        }
        
        .form-control-modern:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .custom-select-modern {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
            cursor: pointer;
        }
        
        .custom-select-modern:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .table-modern {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .table-modern thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-align: center;
        }
        
        .table-modern tbody td {
            padding: 1rem;
            border: none;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }
        
        .table-modern tbody tr:hover {
            background: #f8f9fa;
        }
        
        /* Status Badges */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
        
        /* Changes Display */
        .changes-container {
            max-width: 400px;
            min-width: 350px;
        }
        
        .change-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 12px;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .change-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .change-field {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-right: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .change-value {
            color: #28a745;
            font-weight: 600;
            font-size: 13px;
            padding: 2px 0;
        }
        
        .change-arrow {
            color: #667eea;
            margin: 0 5px;
        }
        
        .badge-modern {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-info-modern {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
        }
        
        .badge-success-modern {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .badge-danger-modern {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }
        
        .text-success-modern {
            color: #28a745;
            font-weight: 600;
        }
        
        .text-muted-modern {
            color: #6c757d;
        }
        
        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
            
            .action-buttons {
                flex-direction: column;
                width: 100%;
            }
            
            .action-buttons .btn {
                width: 100%;
            }
            
            .card-body-modern {
                padding: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="modern-barcode-history-index">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-history"></i> Barkod Hareket Geçmişi
                        </h1>
                        <p class="page-subtitle-modern">Sistemdeki tüm barkod işlemlerinin detaylı geçmişini görüntüleyin</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="action-buttons justify-content-end">
                            <button class="btn-modern btn-success-modern" type="button" id="search">
                                <i class="fas fa-search"></i> Arama
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Card -->
            <div class="card-modern" id="search-area" style="display: none;">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-filter"></i> Arama Filtreleri
                    </h3>
                    <p class="card-subtitle-modern">Barkod hareket geçmişini filtrelemek için aşağıdaki kriterleri kullanın</p>
                </div>
                <div class="card-body-modern">
                    <form class="form" method="GET" action="{{ route('barcode.historyIndex') }}">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Stok Adı</label>
                                    <select class="custom-select-modern" name="stock_id">
                                        <option disabled {{old('stock_id') == '' ? 'selected' : ''}}>Stok seçiniz</option>
                                        @foreach($stocks as $stock)
                                            <option {{old('stock_id') == $stock->id ? 'selected' : ''}} value="{{$stock->id}}">{{$stock->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Fırın Adı</label>
                                    <select class="custom-select-modern" name="kiln_id">
                                        <option disabled {{old('kiln_id') == '' ? 'selected' : ''}}>Fırın seçiniz</option>
                                        @foreach($kilns as $kiln)
                                            <option {{old('kiln_id') == $kiln->id ? 'selected' : ''}} value="{{$kiln->id}}">{{$kiln->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Parti Numarası</label>
                                    <input type="text" name="party_number" id="party_number" class="form-control-modern" placeholder="Parti numarası giriniz" value="{{ old('party_number') }}"/>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Miktar</label>
                                    <select class="custom-select-modern" name="quantity_id">
                                        <option disabled {{old('quantity_id') == '' ? 'selected' : ''}}>Miktar seçiniz</option>
                                        @foreach($quantities as $quantity)
                                            <option {{old('quantity_id') == $quantity->id ? 'selected' : ''}} value="{{$quantity->id}}">{{$quantity->quantity . " KG"}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Firma</label>
                                    <select class="custom-select-modern" name="company_id">
                                        <option disabled {{old('company_id') == '' ? 'selected' : ''}}>Firma seçiniz</option>
                                        @foreach($companies as $company)
                                            <option {{old('company_id') == $company->id ? 'selected' : ''}} value="{{$company->id}}">{{$company->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Depo</label>
                                    <select class="custom-select-modern" name="warehouse_id">
                                        <option disabled {{old('warehouse_id') == '' ? 'selected' : ''}}>Depo seçiniz</option>
                                        @foreach($wareHouses as $wareHouse)
                                            <option {{(old('warehouse_id') == $wareHouse->id) ? 'selected' : ''}} value="{{$wareHouse->id}}">{{$wareHouse->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Oluşturan Personel</label>
                                    <select class="custom-select-modern" name="created_by_id">
                                        <option disabled {{old('created_by_id') == '' ? 'selected' : ''}}>Personel seçiniz</option>
                                        @foreach($users as $user)
                                            <option {{(old('created_by_id') == $user->id) ? 'selected' : ''}} value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Açıklama</label>
                                    <select class="custom-select-modern" name="description_id">
                                        <option disabled {{old('description_id') == '' ? 'selected' : ''}}>Açıklama seçiniz</option>
                                        @foreach($descriptions as $key => $value)
                                            <option {{(old('description_id') == $key) ? 'selected' : ''}} value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Oluşturulma Tarihi</label>
                                    <div>
                                        <div class="input-daterange input-group" id="date-range">
                                            <input type="text" class="form-control-modern" name="start_created_at" placeholder="Başlangıç Tarihi" />
                                            <input type="text" class="form-control-modern" name="end_created_at" placeholder="Bitiş Tarihi" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Lab İşlem Tarihi</label>
                                    <div>
                                        <div class="input-daterange input-group" id="date-range2">
                                            <input type="text" class="form-control-modern" name="start_lab_at" placeholder="Başlangıç Tarihi" />
                                            <input type="text" class="form-control-modern" name="end_lab_at" placeholder="Bitiş Tarihi" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <button type="submit" class="btn-modern btn-primary-modern">
                                <i class="fas fa-search"></i> Sonuçları Getir
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Card -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-history"></i> Barkod Hareket Geçmişi
                    </h3>
                    <p class="card-subtitle-modern">
                        Bu sayfada tüm barkod işlemlerinin detaylı geçmişini görebilirsiniz. Oluşturma, güncelleme, silme ve durum değişiklikleri burada takip edilir.
                    </p>
                </div>

                <div class="card-body-modern">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Stok</th>
                            <th>Parti Numarası</th>
                            <th>Şarj Numarası</th>
                            <th>Açıklama</th>
                            <th>Kullanıcı</th>
                            <th>Durumu</th>
                            <th>Değişiklikler</th>
                            <th>İşlem Tarihi</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($histories as $history)
                            <tr>
                                <td>{{ $history->id}}</td>
                                <td>{{ $history->barcode->stock->name}}</td>
                                <td>{{ $history->barcode->party_number}}</td>
                                <td>{{ $history->barcode->load_number}}</td>
                                <td>{{ \App\Models\Barcode::EVENTS[$history->description] ?? 'Bilinmiyor' }}</td>
                                <td>{{ $history->user->name}}</td>
                                <td>
                                    @php
                                        $statusClass = '';
                                        switch($history->status) {
                                            case \App\Models\Barcode::STATUS_WAITING: 
                                                $statusClass = 'status-waiting'; 
                                                break;
                                            case \App\Models\Barcode::STATUS_CONTROL_REPEAT: 
                                                $statusClass = 'status-control-repeat'; 
                                                break;
                                            case \App\Models\Barcode::STATUS_PRE_APPROVED: 
                                                $statusClass = 'status-pre-approved'; 
                                                break;
                                            case \App\Models\Barcode::STATUS_SHIPMENT_APPROVED: 
                                                $statusClass = 'status-shipment-approved'; 
                                                break;
                                            case \App\Models\Barcode::STATUS_REJECTED: 
                                                $statusClass = 'status-rejected'; 
                                                break;
                                            case \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER: 
                                                $statusClass = 'status-customer-transfer'; 
                                                break;
                                            case \App\Models\Barcode::STATUS_DELIVERED: 
                                                $statusClass = 'status-delivered'; 
                                                break;
                                            case \App\Models\Barcode::STATUS_MERGED: 
                                                $statusClass = 'status-merged'; 
                                                break;
                                            default: 
                                                $statusClass = 'status-waiting';
                                        }
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ \App\Models\Barcode::STATUSES[$history->status] ?? 'Bilinmiyor' }}</span>
                                </td>
                                <td> 
                                    @if(!is_null($history->changes) && is_array($history->changes) && count($history->changes) > 0)
                                        <div class="changes-container">
                                            @foreach($history->changes as $key => $value)
                                                @if(is_string($key) && !is_null($value))
                                                @php
                                                    $fieldNames = [
                                                        'status' => 'Durum',
                                                        // 'transfer_status' => 'Transfer Durumu', // Artık kullanılmıyor
                                                        'lab_at' => 'Lab İşlem Tarihi',
                                                        'lab_by' => 'Lab Personeli',
                                                        'warehouse_id' => 'Depo',
                                                        'warehouse_transferred_at' => 'Depo Transfer Tarihi',
                                                        'warehouse_transferred_by' => 'Depo Transfer Personeli',
                                                        'company_id' => 'Müşteri',
                                                        'company_transferred_at' => 'Müşteri Transfer Tarihi',
                                                        'delivered_at' => 'Teslim Tarihi',
                                                        'delivered_by' => 'Teslim Eden',
                                                        'lab_note' => 'Lab Notu',
                                                        'note' => 'Not',
                                                        'updated_at' => 'Güncelleme Tarihi',
                                                        'stock_id' => 'Stok',
                                                        'party_number' => 'Parti No',
                                                        'load_number' => 'Şarj No',
                                                        'quantity_id' => 'Miktar',
                                                        'kiln_id' => 'Fırın'
                                                    ];
                                                    
                                                    $fieldName = $fieldNames[$key] ?? $key;
                                                    
                                                    // Değerleri kullanıcı dostu hale getir
                                                    $displayValue = $value;
                                                    if ($key === 'status') {
                                                        $displayValue = \App\Models\Barcode::STATUSES[$value] ?? $value;
                                                    } elseif ($key === 'transfer_status') {
    // Transfer status artık kullanılmıyor
    $displayValue = 'Transfer Durumu (Kaldırıldı)';
                                                    } elseif (in_array($key, ['lab_by', 'warehouse_transferred_by', 'delivered_by'])) {
                                                        $user = \App\Models\User::find($value);
                                                        $displayValue = $user ? $user->name : $value;
                                                    } elseif (in_array($key, ['warehouse_id', 'company_id', 'stock_id', 'kiln_id', 'quantity_id'])) {
                                                        if ($key === 'warehouse_id') {
                                                            $warehouse = \App\Models\Warehouse::find($value);
                                                            $displayValue = $warehouse ? $warehouse->name : $value;
                                                        } elseif ($key === 'company_id') {
                                                            $company = \App\Models\Company::find($value);
                                                            $displayValue = $company ? $company->name : $value;
                                                        } elseif ($key === 'stock_id') {
                                                            $stock = \App\Models\Stock::find($value);
                                                            $displayValue = $stock ? $stock->name : $value;
                                                        } elseif ($key === 'kiln_id') {
                                                            $kiln = \App\Models\Kiln::find($value);
                                                            $displayValue = $kiln ? $kiln->name : $value;
                                                        } elseif ($key === 'quantity_id') {
                                                            $quantity = \App\Models\Quantity::find($value);
                                                            $displayValue = $quantity ? $quantity->quantity . ' KG' : $value;
                                                        }
                                                    } elseif (in_array($key, ['lab_at', 'warehouse_transferred_at', 'company_transferred_at', 'delivered_at', 'updated_at'])) {
                                                        $displayValue = $value ? \Carbon\Carbon::parse($value)->format('d.m.Y H:i') : '-';
                                                    }
                                                @endphp
                                                <div class="change-item">
                                                    <span class="change-field">{{ $fieldName }}</span>
                                                    <i class="mdi mdi-arrow-right change-arrow"></i>
                                                    <span class="change-value">{{ $displayValue }}</span>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">Değişiklik bilgisi yok</span>
                                    @endif
                                </td>
                                <td>{{ $history->created_at->tz('Europe/Istanbul')->toDateTimeString() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div> {!! $histories->links('vendor.pagination.bootstrap-4') !!}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $('#datatable').dataTable( {
            order: {
                order: [[0, "desc"]]
            },
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            pageLength: 25
        } );
    </script>

    <script>
        $("#search-area").hide();

        $("#search").click(function(){
            $("#search-area").toggle();
        });

        $('#date-range').datepicker();
        $('#date-range2').datepicker();
    </script>
@endsection
