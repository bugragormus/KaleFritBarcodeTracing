@extends('layouts.app')
@section('styles')
    <!-- bootstrap-select additional library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/css/bootstrap-select.min.css" />
    <style>
        .modern-form {
            background: #ffffff;
            min-height: 100vh;
            padding: 2rem 0;
        }
        
        /* Ana layout ile uyumlu arka plan */
        body {
            background: #ffffff !important;
        }
        
        .main-content {
            background: #ffffff !important;
        }
        
        .form-container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 2.5rem;
            margin: 0 auto;
            max-width: 1200px;
            border: 1px solid #e9ecef;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f8f9fa;
        }
        
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            color: #6c757d;
            font-size: 1.1rem;
            font-weight: 400;
        }
        
        .form-section {
            background: #ffffff;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
        }
        
        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 0.5rem;
            color: #667eea;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            margin-right: 0.5rem;
            color: #667eea;
            font-size: 0.9rem;
        }
        
        .required {
            color: #dc3545;
            margin-left: 0.25rem;
        }
        
        .form-control, .custom-select, .selectpicker {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #ffffff;
        }
        
        .form-control:focus, .custom-select:focus, .selectpicker:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .form-control::placeholder {
            color: #adb5bd;
        }
        
        .selectpicker option:disabled {
            color: #adb5bd;
            font-style: italic;
        }
        
        .bootstrap-select .dropdown-menu li a {
            color: #495057;
        }
        
        .bootstrap-select .dropdown-menu li.disabled a {
            color: #adb5bd !important;
            font-style: italic;
        }
        
        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox-container input[type="checkbox"] {
            margin: 0;
        }

        /* Düzeltme Faaliyeti Stilleri */
        .correction-items {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .correction-item {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1.5rem;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .correction-item:hover {
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
        }

        .correction-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .correction-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .correction-info strong {
            color: #495057;
            font-size: 1.1rem;
        }

        .correction-details {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .correction-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .correction-toggle input[type="checkbox"] {
            margin: 0;
            transform: scale(1.2);
        }

        .correction-toggle label {
            font-weight: 600;
            color: #667eea;
            cursor: pointer;
        }

        .correction-details {
            border-top: 1px solid #dee2e6;
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .correction-quantity {
            max-width: 200px;
        }

        /* Responsive düzenlemeler */
        @media (max-width: 768px) {
            .correction-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .correction-toggle {
                align-self: flex-end;
            }
        }
        
        .btn-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-modern:active {
            transform: translateY(0);
        }
        
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
        }
        
        .error-message i {
            margin-right: 0.25rem;
        }
        
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -0.75rem;
            margin-left: -0.75rem;
        }
        
        .form-col {
            flex: 0 0 25%;
            max-width: 25%;
            padding-right: 0.75rem;
            padding-left: 0.75rem;
        }
        
        @media (max-width: 1200px) {
            .form-col {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }
        
        @media (max-width: 768px) {
            .form-col {
                flex: 0 0 100%;
                max-width: 100%;
            }
            
            .form-container {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .page-title {
                font-size: 2rem;
            }
        }
        
        .info-card {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .info-card h5 {
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .info-card p {
            margin-bottom: 0;
            opacity: 0.9;
        }
        
        /* Pagination Stilleri */
        .pagination-container {
            margin-top: 2rem;
        }
        
        .pagination {
            margin-bottom: 0;
        }
        
        .page-link {
            color: #667eea;
            background-color: #ffffff;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            margin: 0 0.25rem;
            padding: 0.5rem 0.75rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .page-link:hover {
            color: #ffffff;
            background-color: #667eea;
            border-color: #667eea;
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        }
        
        .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        }
        
        .pagination-info {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .pagination-info small {
            font-weight: 500;
        }
    </style>
@endsection
@section('content')
    <div class="modern-form">
        <div class="form-container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-barcode"></i> Yeni Barkod Oluştur
                </h1>
                <p class="page-subtitle">Stok yönetimi için yeni barkod oluşturun</p>
                    </div>

            <!-- Info Card -->
            <div class="info-card">
                <h5><i class="fas fa-info-circle"></i> Bilgilendirme</h5>
                <p>Lütfen tüm zorunlu alanları (*) doldurun. Başlangıç şarj numarasını girin, sistem otomatik olarak her barkod için artırarak devam edecektir. Barkod adedi açılır menüden seçilir. Barkod oluşturulduktan sonra otomatik olarak yazdırılacaktır.</p>
            </div>

            <form method="POST" action="{{ route('barcode.store') }}">
                @csrf
                
                <!-- Temel Bilgiler -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-cube"></i> Temel Bilgiler
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-col">
                                    <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-box"></i> Stok Adı
                                    <span class="required">*</span>
                                </label>
                                        <select class="form-control selectpicker" name="stock_id" data-live-search="true">
                                            <option {{old('stock_id') == '' ? 'selected' : ''}} disabled>Stok seçiniz</option>
                                            @foreach($stocks as $stock)
                                                <option data-tokens="{{$stock->name}}" {{old('stock_id') == $stock->id ? 'selected' : ''}} value="{{$stock->id}}">{{$stock->name}} - {{$stock->code}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('stock_id'))
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                                {{ $errors->first('stock_id') }}
                                    </div>
                                @endif
                                    </div>
                                </div>

                        <div class="form-col">
                                    <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-fire"></i> Fırın Adı
                                    <span class="required">*</span>
                                </label>
                                <select class="form-control selectpicker" name="kiln_id" data-live-search="true">
                                    <option {{old('kiln_id') == '' ? 'selected' : ''}} disabled>Fırın seçiniz</option>
                                            @foreach($kilns as $kiln)
                                        <option data-tokens="{{$kiln->name}}" {{old('kiln_id') == $kiln->id ? 'selected' : ''}} value="{{$kiln->id}}">{{$kiln->name}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('kiln_id'))
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                                {{ $errors->first('kiln_id') }}
                                    </div>
                                @endif
                                    </div>
                                </div>

                        <div class="form-col">
                                    <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-hashtag"></i> Parti Numarası
                                    <span class="required">*</span>
                                </label>
                                        <input type="text" name="party_number" id="party_number" class="form-control" placeholder="Parti numarası giriniz" value="{{ old('party_number') }}"/>
                                        @if($errors->has('party_number'))
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                                {{ $errors->first('party_number') }}
                                    </div>
                                @endif
                                    </div>
                                </div>

                        <div class="form-col">
                                    <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-weight-hanging"></i> Miktar
                                    <span class="required">*</span>
                                </label>
                                <select class="form-control selectpicker" name="quantity_id" data-live-search="true">
                                    <option {{old('quantity_id') == '' ? 'selected' : ''}} disabled>Miktar seçiniz</option>
                                            @foreach($quantities as $quantity)
                                        <option data-tokens="{{$quantity->quantity}}" {{old('quantity_id') == $quantity->id ? 'selected' : ''}} value="{{$quantity->id}}">{{$quantity->quantity . " KG"}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('quantity_id'))
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                                {{ $errors->first('quantity_id') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-col">
                                    <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-hashtag"></i> Başlangıç Şarj Numarası
                                    <span class="required">*</span>
                                </label>
                                <input type="number" name="load_number" id="load_number" class="form-control" placeholder="Başlangıç şarj numarası giriniz" value="{{ old('load_number') }}" min="1"/>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Her barkod için otomatik olarak artacak şarj numarası
                                </small>
                                @if($errors->has('load_number'))
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $errors->first('load_number') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ek Bilgiler -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i> Ek Bilgiler
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-col">
                                    <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-warehouse"></i> Depo
                                    <span class="required">*</span>
                                </label>
                                        <select class="form-control selectpicker" name="warehouse_id" data-live-search="true">
                                            <option {{old('warehouse_id') == '' ? 'selected' : ''}} disabled>Depo seçiniz</option>
                                            @foreach($warehouses as $warehouse)
                                                <option data-tokens="{{$warehouse->name}}" {{old('warehouse_id') == $warehouse->id ? 'selected' : ''}} value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('warehouse_id'))
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $errors->first('warehouse_id') }}
                                    </div>
                                        @endif
                                    </div>
                                </div>

                        <div class="form-col">
                                    <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-sticky-note"></i> Not
                                </label>
                                <input type="text" name="note" id="note" class="form-control" placeholder="Not..." value="{{ old('note') }}"/>
                                @if($errors->has('note'))
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $errors->first('note') }}
                                    </div>
                                @endif
                            </div>
                            </div>

                                                <div class="form-col">
                                    <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-barcode"></i> Barkod Adedi
                                    <span class="required">*</span>
                                </label>
                                <select class="form-control selectpicker" name="quantity" id="quantity" data-live-search="true">
                                    <option {{old('quantity') == '' ? 'selected' : ''}} disabled>Barkod adedi seçiniz</option>
                                    @for($i = 1; $i <= 50; $i++)
                                        <option {{old('quantity') == $i ? 'selected' : ''}} value="{{$i}}">{{$i}} adet</option>
                                    @endfor
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Seçilen adet kadar barkod oluşturulacak
                                </small>
                                @if($errors->has('quantity'))
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $errors->first('quantity') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-print"></i> Yazdırma Seçeneği
                                </label>
                                <div class="checkbox-container">
                                    <input type="checkbox" name="print" id="print" checked="checked"/>
                                    <label for="print">Barkodları otomatik yazdır</label>
                                </div>
                                @if($errors->has('print'))
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $errors->first('print') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Düzeltme Faaliyeti -->
                @if($rejectedBarcodes->count() > 0)
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-tools"></i> Düzeltme Faaliyeti
                    </h3>
                    
                    <div class="info-card" style="margin-bottom: 1.5rem;">
                        <h6><i class="fas fa-info-circle"></i> Düzeltme Faaliyeti Nedir?</h6>
                        <p>Önceki üretimlerden reddedilen malzemeleri, yeni üretim sırasında düzeltme faaliyeti olarak kullanabilirsiniz. Bu sayede hammadde verimliliğinizi artırabilir ve atık miktarını azaltabilirsiniz.</p>
                    </div>

                    <div class="correction-items">
                        @foreach($rejectedBarcodes->forPage(request()->get('correction_page', 1), 5) as $index => $rejectedBarcode)
                        <div class="correction-item">
                            <div class="correction-header">
                                <div class="correction-info">
                                    <strong>Şarj: {{ $rejectedBarcode->load_number }} | Barkod: #{{ $rejectedBarcode->id }}</strong>
                                    <span class="correction-details">
                                        {{ $rejectedBarcode->stock->name }} - 
                                        {{ $rejectedBarcode->quantity->quantity }} KG - 
                                        {{ $rejectedBarcode->created_at->format('d.m.Y') }}
                                    </span>
                                </div>
                                <div class="correction-toggle">
                                    <input type="checkbox" name="use_correction[]" id="use_correction_{{ $index }}" value="{{ $index }}" class="correction-checkbox"/>
                                    <label for="use_correction_{{ $index }}">Düzeltme olarak kullan</label>
                                    <input type="hidden" name="correction_barcodes[]" value="{{ $rejectedBarcode->id }}" class="correction-barcode-hidden" disabled/>
                                    <input type="hidden" name="correction_quantities[]" value="{{ $rejectedBarcode->quantity->quantity }}" class="correction-quantity-hidden" disabled/>
                                    <input type="hidden" name="correction_notes[]" value="Şarj {{ $rejectedBarcode->load_number }} düzeltme faaliyeti" class="correction-note-hidden" disabled/>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($rejectedBarcodes->count() > 5)
                    <div class="pagination-container text-center mt-4">
                        <nav aria-label="Düzeltme faaliyeti sayfaları">
                            <ul class="pagination justify-content-center">
                                @php
                                    $currentPage = request()->get('correction_page', 1);
                                    $totalPages = ceil($rejectedBarcodes->count() / 5);
                                @endphp
                                
                                <!-- Önceki sayfa -->
                                @if($currentPage > 1)
                                <li class="page-item">
                                    <a class="page-link" href="?correction_page={{ $currentPage - 1 }}" aria-label="Önceki">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                @endif
                                
                                <!-- Sayfa numaraları -->
                                @for($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                                <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                    <a class="page-link" href="?correction_page={{ $i }}">{{ $i }}</a>
                                </li>
                                @endfor
                                
                                <!-- Sonraki sayfa -->
                                @if($currentPage < $totalPages)
                                <li class="page-item">
                                    <a class="page-link" href="?correction_page={{ $currentPage + 1 }}" aria-label="Sonraki">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </nav>
                        
                        <div class="pagination-info mt-2">
                            <small class="text-muted">
                                Toplam {{ $rejectedBarcodes->count() }} reddedilen barkod, {{ $totalPages }} sayfada gösteriliyor
                            </small>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn-modern">
                        <i class="fas fa-plus-circle"></i> Barkod Oluştur
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- bootstrap-select additional library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/js/bootstrap-select.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.selectpicker').selectpicker();
            
            // Sayfa yüklendiğinde tüm hidden input'ları devre dışı bırak
            $('.correction-quantity-hidden, .correction-barcode-hidden, .correction-note-hidden').prop('disabled', true);
            
            // Düzeltme faaliyeti checkbox'ları için event listener
            // Checkbox işaretlendiğinde hidden input'lar aktif olur, işaretlenmediğinde devre dışı kalır
            $('.correction-checkbox').on('change', function() {
                var isChecked = $(this).is(':checked');
                var hiddenInputs = $(this).siblings('input[type="hidden"]');
                
                if (isChecked) {
                    // Checkbox işaretlendiğinde hidden input'ları aktif hale getir
                    hiddenInputs.prop('disabled', false);
                } else {
                    // Checkbox işaretlenmediğinde hidden input'ları devre dışı bırak
                    hiddenInputs.prop('disabled', true);
                }
            });
            
            // Form submit öncesi validasyon
            $('form').on('submit', function(e) {
                var hasCorrection = false;
                var totalCorrectionQuantity = 0;
                
                // Şarj numarası kontrolü
                var loadNumber = $('#load_number').val().trim();
                if (loadNumber === '') {
                    toastr.error('Lütfen şarj numarasını giriniz.');
                    e.preventDefault();
                    return false;
                }
                
                $('.correction-checkbox:checked').each(function() {
                    hasCorrection = true;
                    var quantity = parseInt($(this).siblings('.correction-quantity-hidden').val());
                    if (quantity > 0) {
                        totalCorrectionQuantity += quantity;
                    }
                });
                
                // Başarı mesajı
                if (hasCorrection) {
                    toastr.info('Düzeltme faaliyeti ile ' + totalCorrectionQuantity + ' KG reddedilen malzeme kullanılacak.');
                }
            });
            
            // Şarj numarası ve barkod adedi için bilgilendirme
            $('#load_number').on('focus', function() {
                if (!$(this).val()) {
                    $(this).attr('placeholder', 'Örnek: 1001');
                }
            });
            
            // Barkod adedi değiştiğinde şarj numarası bilgisini güncelle
            $('#quantity').on('change', function() {
                var quantity = parseInt($(this).val()) || 0;
                var startLoadNumber = parseInt($('#load_number').val()) || 0;
                
                if (quantity > 0 && startLoadNumber > 0) {
                    var endLoadNumber = startLoadNumber + quantity - 1;
                    var infoText = 'Şarj numaraları: ' + startLoadNumber + ' - ' + endLoadNumber;
                    
                    // Bilgilendirme metnini güncelle
                    if ($('#load_number').siblings('.form-text').length === 0) {
                        $('#load_number').after('<small class="form-text text-info" id="load_number_info"><i class="fas fa-info-circle"></i> ' + infoText + '</small>');
                    } else {
                        $('#load_number_info').html('<i class="fas fa-info-circle"></i> ' + infoText);
                    }
                }
            });
            
            // Şarj numarası değiştiğinde de bilgilendirmeyi güncelle
            $('#load_number').on('input', function() {
                $('#quantity').trigger('change');
            });
        });
    </script>
@endsection