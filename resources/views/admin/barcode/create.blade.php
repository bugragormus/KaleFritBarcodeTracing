@extends('layouts.app')
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

        /* Hidden utility to support filtering */
        .hidden { display: none !important; }

        /* Fix for collapsed parent clipping dropdowns on legacy browsers */
        #correctionSection {
            overflow: visible;
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

        /* Collapsible Section Styles */
        .section-header-collapsible {
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 15px;
            padding: 1rem;
            margin: -1rem -1rem 1rem -1rem;
        }
        
        .section-header-collapsible:hover {
            background: rgba(102, 126, 234, 0.05);
        }
        
        .section-header-collapsible .section-title {
            margin-bottom: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }
        
        .collapse-icon {
            transition: transform 0.3s ease;
            color: #667eea;
            font-size: 0.9rem;
        }
        
        .section-header-collapsible[aria-expanded="true"] .collapse-icon {
            transform: rotate(180deg);
        }
        
        .collapse {
            transition: all 0.3s ease;
        }
        
        .collapse.show {
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Filter Section Styles */
        .filter-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e9ecef;
        }
        
        .filter-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        
        .filter-title i {
            margin-right: 0.5rem;
            color: #667eea;
        }
        
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: end;
        }
        
        .filter-col {
            flex: 1;
            min-width: 200px;
        }
        
        .filter-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }
        
        .filter-label i {
            margin-right: 0.5rem;
            color: #667eea;
            font-size: 0.8rem;
        }
        
        .select2-filter {
            width: 100%;
        }
        
        .select2-container--default .select2-selection--single {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            height: 38px;
            display: flex;
            align-items: center;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
            padding-left: 12px;
            color: #495057;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .select2-dropdown {
            border: 2px solid #667eea;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }
        
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #667eea;
        }
        
        .btn-outline-secondary {
            border: 2px solid #6c757d;
            color: white;
            background: #6c757d;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
        }
        
        /* Hidden items for filtering */
        .correction-item.hidden {
            display: none;
        }
        
        .filter-count {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
            padding: 0.5rem 0;
            border-top: 1px solid #dee2e6;
            margin-top: 1rem;
        }
        
        /* Seçim Bilgisi Stilleri */
        .selection-info {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
        }
        
        .selection-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .selection-count, .selection-total {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #495057;
        }
        
        .selection-count {
            color: #28a745;
            font-weight: 500;
        }
        
        .selection-count i {
            color: #28a745;
        }
        
        .selection-total {
            color: #6c757d;
        }
        
        .selection-total i {
            color: #17a2b8;
        }
        
        .selection-count strong, .selection-total strong {
            font-weight: 600;
            color: #212529;
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
            
            .section-header-collapsible .section-title {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .collapse-icon {
                align-self: flex-end;
            }
            
            .filter-row {
                flex-direction: column;
            }
            
            .filter-col {
                min-width: 100%;
            }
            
            .selection-summary {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
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
                                        <input type="number" name="party_number" id="party_number" class="form-control" placeholder="Parti numarası giriniz" value="{{ old('party_number') }}" min="1" step="1" pattern="[1-9][0-9]*" title="Sadece pozitif sayı giriniz (0 ile başlayamaz)"/>
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
                    <div class="section-header-collapsible" data-toggle="collapse" data-target="#correctionSection" aria-expanded="false" aria-controls="correctionSection">
                        <h3 class="section-title">
                            <i class="fas fa-tools"></i> Düzeltme Faaliyeti
                            <span class="collapse-icon">
                                <i class="fas fa-chevron-down"></i>
                            </span>
                        </h3>
                    </div>
                    
                    <div class="collapse" id="correctionSection">
                        <div class="info-card" style="margin-bottom: 1.5rem;">
                            <h6><i class="fas fa-info-circle"></i> Düzeltme Faaliyeti Nedir?</h6>
                            <p>Önceki üretimlerden reddedilen malzemeleri, yeni üretim sırasında düzeltme faaliyeti olarak kullanabilirsiniz. Bu sayede hammadde verimliliğinizi artırabilir ve atık miktarını azaltabilirsiniz.</p>
                        </div>

                        <!-- Filtreleme Bölümü -->
                        <div class="filter-section">
                            <h6 class="filter-title">
                                <i class="fas fa-filter"></i> Filtreleme
                            </h6>
                            <div class="filter-row">
                                <div class="filter-col">
                                    <label class="filter-label">
                                        <i class="fas fa-hashtag"></i> Şarj Numarası
                                    </label>
                                    <select class="form-control select2-filter" id="loadNumberFilter" data-placeholder="Şarj numarası seçiniz">
                                        <option value="">Tümü</option>
                                        @foreach($rejectedBarcodes->unique('load_number')->pluck('load_number')->sort() as $loadNumber)
                                            <option value="{{ $loadNumber }}">{{ $loadNumber }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="filter-col">
                                    <label class="filter-label">
                                        <i class="fas fa-box"></i> Stok Adı
                                    </label>
                                    <select class="form-control select2-filter" id="stockNameFilter" data-placeholder="Stok adı seçiniz">
                                        <option value="">Tümü</option>
                                        @foreach($rejectedBarcodes->unique('stock_id')->pluck('stock.name')->sort() as $stockName)
                                            <option value="{{ $stockName }}">{{ $stockName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="filter-col">
                                    <label class="filter-label">
                                        <i class="fas fa-times"></i> Temizle
                                    </label>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="clearFilters">
                                        <i class="fas fa-eraser"></i> Filtreleri Temizle
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Seçim Bilgisi -->
                            <div class="selection-info">
                                <div class="selection-summary">
                                    <span class="selection-count">
                                        <i class="fas fa-check-circle"></i> 
                                        <strong id="selectedCount">0</strong> ürün seçildi
                                    </span>
                                    <span class="selection-total">
                                        <i class="fas fa-info-circle"></i> 
                                        Toplam <strong>{{ $rejectedBarcodes->count() }}</strong> reddedilen ürün
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="correction-items">
                            @foreach($rejectedBarcodes as $index => $rejectedBarcode)
                            <div class="correction-item" 
                                 data-load-number="{{ $rejectedBarcode->load_number }}"
                                 data-stock-name="{{ $rejectedBarcode->stock->name }}">
                                <div class="correction-header">
                                    <div class="correction-info">
                                        <strong>Şarj: #{{ $rejectedBarcode->load_number }} | Barkod: #{{ $rejectedBarcode->id }} | Parti: #{{ $rejectedBarcode->party_number}}</strong>
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
                    </div>
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
    <!-- Select2 library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    <script type="text/javascript">
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
            
            // Initialize Select2 for filter dropdowns
            // Robust Select2 init with dropdownParent for legacy browsers
            $('.select2-filter').select2({
                theme: 'default',
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true,
                dropdownAutoWidth: true,
                adaptDropdownCssClass: function() { return null; }
            }).on('select2:open', function() {
                // Force position recalculation on open for old browsers
                const $dropdown = $('.select2-container .select2-dropdown');
                if ($dropdown && $dropdown.length) {
                    $dropdown.css('width', 'auto');
                }
            });

            // Attach dropdownParent after initialization to nearest section to avoid clipping in legacy browsers
            $('#loadNumberFilter').select2('destroy').select2({
                theme: 'default',
                width: '100%',
                placeholder: $('#loadNumberFilter').data('placeholder'),
                allowClear: true,
                dropdownParent: $('#correctionSection')
            });
            $('#stockNameFilter').select2('destroy').select2({
                theme: 'default',
                width: '100%',
                placeholder: $('#stockNameFilter').data('placeholder'),
                allowClear: true,
                dropdownParent: $('#correctionSection')
            });
            
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
                    // Checkbox işaretlenmediğinde hidden input'ları devre dışı kalır
                    hiddenInputs.prop('disabled', true);
                }
                
                // Seçim sayacını güncelle
                updateSelectionCount();
            });
            
            // Form submit öncesi validasyon
            $('form').on('submit', function(e) {
                var hasCorrection = false;
                var totalCorrectionQuantity = 0;
                
                // Parti numarası kontrolü
                var partyNumber = $('#party_number').val().trim();
                if (partyNumber === '' || partyNumber === '0') {
                    toastr.error('Lütfen geçerli bir parti numarası giriniz (0 olamaz).');
                    $('#party_number').focus();
                    e.preventDefault();
                    return false;
                }
                
                // Parti numarası sadece sayı olmalı
                if (!/^[1-9]\d*$/.test(partyNumber)) {
                    toastr.error('Parti numarası sadece pozitif sayı olmalıdır ve 0 ile başlayamaz.');
                    $('#party_number').focus();
                    e.preventDefault();
                    return false;
                }
                
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
            
            // Parti numarası validasyonu
            $('#party_number').on('input', function() {
                var value = $(this).val();
                var $input = $(this);
                
                // Sadece sayı girişine izin ver
                value = value.replace(/[^0-9]/g, '');
                
                // 0 ile başlayan sayıları engelle
                if (value.startsWith('0')) {
                    value = value.replace(/^0+/, '');
                }
                
                // Boş değer kontrolü
                if (value === '') {
                    return;
                }
                
                // Validasyon - sadece hata durumunda mesaj göster
                if (!/^[1-9]\d*$/.test(value)) {
                    if ($input.siblings('.error-message').length === 0) {
                        $input.after('<div class="error-message"><i class="fas fa-exclamation-triangle"></i> Sadece pozitif sayı giriniz</div>');
                    }
                } else {
                    // Geçerli değer - hata mesajını kaldır
                    $input.siblings('.error-message').remove();
                }
                
                // Input değerini güncelle
                if (value !== $(this).val()) {
                    $(this).val(value);
                }
            });
            
            // Collapsible section functionality
            $('.section-header-collapsible').on('click', function() {
                var target = $(this).data('target');
                var isExpanded = $(this).attr('aria-expanded') === 'true';
                
                // Toggle aria-expanded attribute
                $(this).attr('aria-expanded', !isExpanded);
                
                // Toggle collapse
                $(target).collapse('toggle');
                
                // Update icon rotation
                var icon = $(this).find('.collapse-icon i');
                if (isExpanded) {
                    icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                } else {
                    icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                }
            });
            
            // Initialize collapse with Bootstrap
            $('.collapse').collapse({
                toggle: false
            });
            
            // Filter functionality
            function applyFilters() {
                var loadNumberFilter = $('#loadNumberFilter').val();
                var stockNameFilter = $('#stockNameFilter').val();
                
                console.log('Load Number Filter:', loadNumberFilter);
                console.log('Stock Name Filter:', stockNameFilter);
                
                $('.correction-item').each(function() {
                    var $item = $(this);
                    var loadNumber = $item.data('load-number');
                    var stockName = $item.data('stock-name');
                    
                    console.log('Item Load Number:', loadNumber, 'Type:', typeof loadNumber);
                    console.log('Item Stock Name:', stockName);
                    
                    var showItem = true;
                    
                    // Apply load number filter - use loose comparison for better compatibility
                    if (loadNumberFilter && loadNumberFilter !== '') {
                        // Convert both to numbers if possible, otherwise use string comparison
                        var filterNum = parseInt(loadNumberFilter);
                        var itemNum = parseInt(loadNumber);
                        
                        if (!isNaN(filterNum) && !isNaN(itemNum)) {
                            // Both are valid numbers, compare as numbers
                            if (filterNum !== itemNum) {
                                showItem = false;
                            }
                        } else {
                            // Fallback to string comparison
                            if (String(loadNumberFilter) !== String(loadNumber)) {
                                showItem = false;
                            }
                        }
                    }
                    
                    // Apply stock name filter
                    if (stockNameFilter && stockNameFilter !== '') {
                        if (stockNameFilter !== stockName) {
                            showItem = false;
                        }
                    }
                    
                    // Show/hide item
                    if (showItem) {
                        $item.removeClass('hidden');
                    } else {
                        $item.addClass('hidden');
                    }
                });
                
                // Update visible count
                updateVisibleCount();
                
                // Update selection count (filtreleme sonrası seçim sayısı güncellenir)
                updateSelectionCount();
            }
            
            // Update visible items count
            function updateVisibleCount() {
                var visibleCount = $('.correction-item:not(.hidden)').length;
                var totalCount = $('.correction-item').length;
                
                // Add or update count display
                if ($('.filter-count').length === 0) {
                    $('.filter-title').after('<div class="filter-count text-muted small mt-2">Gösterilen: ' + visibleCount + ' / ' + totalCount + ' öğe</div>');
                } else {
                    $('.filter-count').text('Gösterilen: ' + visibleCount + ' / ' + totalCount + ' öğe');
                }
            }
            
            // Update selection count
            function updateSelectionCount() {
                var selectedCount = $('.correction-checkbox:checked').length;
                $('#selectedCount').text(selectedCount);
                
                // Seçim sayısına göre renk değişimi
                var $selectionCount = $('.selection-count');
                if (selectedCount > 0) {
                    $selectionCount.addClass('has-selection');
                } else {
                    $selectionCount.removeClass('has-selection');
                }
            }
            
            // Filter change events
            $('#loadNumberFilter, #stockNameFilter').on('change', function() {
                console.log('Filter changed:', $(this).attr('id'), 'Value:', $(this).val());
                applyFilters();
            });
            
            // Clear filters button
            $('#clearFilters').on('click', function() {
                $('#loadNumberFilter, #stockNameFilter').val('').trigger('change');
                applyFilters();
            });
            
            // Initialize visible count
            updateVisibleCount();
            
            // Initialize selection count
            updateSelectionCount();
            
            // Debug: Check filter dropdowns content
            console.log('Load Number Filter Options:', $('#loadNumberFilter option').map(function() {
                return { value: $(this).val(), text: $(this).text() };
            }).get());
            
            console.log('Stock Name Filter Options:', $('#stockNameFilter option').map(function() {
                return { value: $(this).val(), text: $(this).text() };
            }).get());
            
            console.log('Correction Items Data:', $('.correction-item').map(function() {
                return {
                    loadNumber: $(this).data('load-number'),
                    stockName: $(this).data('stock-name')
                };
            }).get());
        });
    </script>
@endsection