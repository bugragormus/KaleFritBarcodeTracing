@extends('layouts.granilya')
@section('styles')
    <!-- bootstrap-select additional library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/css/bootstrap-select.min.css" />
    <!-- Select2 library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" />
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

        /* Select2 cross-browser height/line-height fixes */
        .select2-container--default .select2-selection--single {
            height: 42px; /* Match .form-control height */
            border: 2px solid #e9ecef;
            border-radius: 10px;
            background: #ffffff;
            box-sizing: border-box;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px; /* height - borders */
            padding-left: 12px;
            padding-right: 30px; /* space for arrow/clear */
            color: #495057;
            font-size: 1rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
            right: 8px;
        }

        /* Ensure dropdown overlays correctly on old browsers */
        .select2-container {
            z-index: 1060; /* above collapses/modals */
            width: 100% !important;
        }

        .select2-dropdown {
            border: 2px solid #e9ecef;
            border-top: none;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }

        /* Native select fallback height alignment */
        select.form-control, select.custom-select {
            height: 42px;
            line-height: 40px;
        }

        /* Bootstrap-Select (selectpicker) cross-browser fixes */
        .bootstrap-select { width: 100% !important; }
        .bootstrap-select > .dropdown-toggle {
            height: 42px;
            line-height: 40px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            background: #ffffff;
            color: #495057;
            padding: 0 1rem;
        }
        .bootstrap-select > .dropdown-toggle:focus,
        .bootstrap-select > .dropdown-toggle:active {
            outline: none !important;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
            border-color: #667eea !important;
        }
        .bootstrap-select .filter-option-inner,
        .bootstrap-select .filter-option-inner-inner {
            line-height: 40px;
        }
        .bootstrap-select .dropdown-menu {
            z-index: 1060; /* keep above other UI on legacy browsers */
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            border: 2px solid #e9ecef;
        }
        .bootstrap-select .dropdown-menu .inner .dropdown-item {
            padding: .5rem .75rem;
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
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
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
        
    </style>
@endsection
@section('content')
    <div class="modern-form">
        <div class="form-container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-industry"></i> Üretim Girişi
                </h1>
                <p class="page-subtitle">Sisteme yeni granilya üretimi ekleyin</p>
            </div>

            <!-- Info Card -->
            <div class="info-card">
                <h5><i class="fas fa-info-circle"></i> Bilgilendirme</h5>
                <p>Lütfen üretim için gerekli olan tüm alanları (*) doldurun. Tane boyutu "TOZ" seçildiğinde miktar alanını elle değer girilecek şekilde değişecektir.</p>
            </div>

            <form method="POST" action="#">
                @csrf
                
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-cube"></i> Üretim Bilgileri
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-box"></i> Frit Kodu
                                    <span class="required">*</span>
                                </label>
                                <select class="form-control selectpicker" name="stock_id" id="stock_id" data-live-search="true">
                                    <option value="" disabled selected>Frit seçiniz</option>
                                    @foreach($uniqueStocks as $stock)
                                        <option data-tokens="{{$stock['name']}}" value="{{$stock['id']}}">{{$stock['name']}} - {{$stock['code']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-hashtag"></i> Frit Şarj No
                                    <span class="required">*</span>
                                </label>
                                <select class="form-control selectpicker" name="load_number" id="load_number" data-live-search="true">
                                    <option value="" disabled selected>Önce Frit Kodu seçiniz</option>
                                    <!-- Options will be populated via JS -->
                                </select>
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-compress-arrows-alt"></i> Tane Boyutu
                                    <span class="required">*</span>
                                </label>
                                <select class="form-control selectpicker" name="size_id" id="size_id" data-live-search="true">
                                    <option value="" disabled selected>Boyut seçiniz</option>
                                    @foreach($sizes as $size)
                                        <option value="{{$size->id}}" data-name="{{strtoupper($size->name)}}">{{$size->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-cogs"></i> Kırıcı Makina
                                    <span class="required">*</span>
                                </label>
                                <select class="form-control selectpicker" name="crusher_id" data-live-search="true">
                                    <option value="" disabled selected>Makina seçiniz</option>
                                    @foreach($crushers as $crusher)
                                        <option value="{{$crusher->id}}">{{$crusher->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group" id="quantity_select_wrapper">
                                <label class="form-label">
                                    <i class="fas fa-weight-hanging"></i> Miktar
                                    <span class="required">*</span>
                                </label>
                                <select class="form-control selectpicker" name="quantity_id" id="quantity_id" data-live-search="true">
                                    <option value="" disabled selected>Miktar seçiniz</option>
                                    @foreach($quantities as $quantity)
                                        <option value="{{$quantity->id}}">{{$quantity->quantity}} KG</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group" id="quantity_input_wrapper" style="display: none;">
                                <label class="form-label">
                                    <i class="fas fa-weight-hanging"></i> Miktar (Elle Giriş)
                                    <span class="required">*</span>
                                </label>
                                <input type="number" class="form-control" name="custom_quantity" id="custom_quantity" placeholder="KG giriniz" min="1" step="0.01">
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-building"></i> Firma
                                    <span class="required">*</span>
                                </label>
                                <select class="form-control selectpicker" name="company_id" data-live-search="true">
                                    <option value="" disabled selected>Firma seçiniz</option>
                                    @foreach($companies as $company)
                                        <option value="{{$company->id}}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-pallet"></i> Palet Numarası
                                    <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" name="pallet_number" placeholder="Palet numarasını manuel giriniz">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="button" class="btn-modern">
                        <i class="fas fa-plus-circle"></i> Üret
                    </button>
                    <!-- Form submission logic is mocked for now as per instructions -->
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- bootstrap-select additional library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/js/bootstrap-select.min.js"></script>
    <!-- Select2 library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    <script type="text/javascript">
        // Backend'ten gelen stok ID - Şarj No mapping verisi
        var stockLoadNumbers = @json($stockLoadNumbers);

        $(document).ready(function(){
            // Initialize bootstrap-select with safe container and events
            $('.selectpicker').selectpicker({
                container: 'body',
                dropupAuto: false,
                liveSearchNormalize: true
            }).on('shown.bs.select', function () {
                // Force reposition on show for legacy browsers
                $(this).data('selectpicker') && $(this).data('selectpicker').$menu
                    && $(this).data('selectpicker').$menu.css('min-width', $(this).closest('.bootstrap-select').outerWidth());
            }).on('loaded.bs.select', function () {
                // Normalize height after load
                $(this).closest('.bootstrap-select').find('> .dropdown-toggle').css({ height: '42px', lineHeight: '40px' });
            });

            // Frit Kodu seçildiğinde Frit Şarj No dropdown'unu güncelle
            $('#stock_id').on('change', function() {
                var selectedStockId = $(this).val();
                var loadNumberDropdown = $('#load_number');
                
                loadNumberDropdown.empty();
                
                if (selectedStockId && stockLoadNumbers[selectedStockId] && stockLoadNumbers[selectedStockId].length > 0) {
                    loadNumberDropdown.append('<option value="" disabled selected>Şarj No seçiniz</option>');
                    
                    // Şarj numaralarını ekle
                    $.each(stockLoadNumbers[selectedStockId], function(index, value) {
                        loadNumberDropdown.append('<option value="' + value + '">' + value + '</option>');
                    });
                } else {
                    loadNumberDropdown.append('<option value="" disabled selected>Bu frite ait şarj bulunamadı</option>');
                }
                
                // Selectpicker'ı yenile
                loadNumberDropdown.selectpicker('refresh');
            });
            
            // Tane boyutu "TOZ" değiştiğinde miktar alanını değiştir
            $('#size_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var sizeName = selectedOption.data('name');
                
                if (sizeName === 'TOZ') {
                    // Miktar dropdown'unu gizle, input'u göster
                    $('#quantity_select_wrapper').hide();
                    $('#quantity_input_wrapper').show();
                } else {
                    // Miktar input'unu gizle, dropdown'u göster
                    $('#quantity_select_wrapper').show();
                    $('#quantity_input_wrapper').hide();
                }
            });
        });
    </script>
@endsection
