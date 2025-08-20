@extends('layouts.app')

@section('styles')
<style>
    body, .main-content, .modern-barcode-edit {
        background: #f8f9fa !important;
    }
    .modern-barcode-edit {
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
    
    /* Status Badges */
    .status-badge {
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        text-align: center;
        min-width: 100px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        border: none;
        transition: all 0.3s ease;
    }
    
    .status-badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
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
    
    /* Status indicator styles for edit page */
    .status-indicator {
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        text-align: center;
        min-width: 100px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        border: none;
        transition: all 0.3s ease;
    }
    
    .status-indicator:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    .status-indicator.status-waiting {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #212529;
    }
    
    .status-indicator.status-control-repeat {
        background: linear-gradient(135deg, #fd7e14, #e55a00);
        color: white;
    }
    
    .status-indicator.status-pre-approved {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .status-indicator.status-shipment-approved {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
    }
    
    .status-indicator.status-rejected {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }
    
    .status-indicator.status-customer-transfer {
        background: linear-gradient(135deg, #6f42c1, #5a32a3);
        color: white;
    }
    
    .status-indicator.status-delivered {
        background: linear-gradient(135deg, #20c997, #17a2b8);
        color: white;
    }
    
    .status-indicator.status-merged {
        background: linear-gradient(135deg, #6f42c1, #5a32a3);
        color: white;
    }
    
    .status-indicator.status-approved {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .info-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #e9ecef;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .info-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    
    .info-value {
        font-size: 1rem;
        color: #212529;
        font-weight: 500;
        margin-bottom: 0;
    }
    
    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 15px 15px 0 0;
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
    }
    
    .section-header i {
        margin-right: 0.75rem;
        font-size: 1.2rem;
    }
    
    .card-modern {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .card-modern .card-body {
        padding: 0;
    }
    
    .form-section {
        padding: 1.5rem;
        background: white;
    }
    
    .form-control, .custom-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .custom-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    /* Dropdown menü iyileştirmeleri */
    .custom-select {
        min-width: 200px;
        max-width: 100%;
        white-space: normal;
        word-wrap: break-word;
        padding-right: 30px;
        background-position: right 8px center;
        background-size: 16px 12px;
        height: auto;
        min-height: 45px;
    }
    
    .custom-select option {
        padding: 0.5rem 0.75rem;
        white-space: normal;
        word-wrap: break-word;
        min-height: 20px;
        line-height: 1.4;
        font-size: 0.875rem;
    }
    
    /* Seçili option için daha iyi görünüm */
    .custom-select:focus option:checked {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.25rem;
        font-weight: 600;
        color: #495057;
    }
    
    /* Form alanları için daha iyi görünüm */
    .form-section .row {
        margin-bottom: 0.75rem;
    }
    
    .form-section .col-lg-6 {
        margin-bottom: 1rem;
    }
    
    /* Dropdown container için daha fazla alan */
    .col-lg-6 {
        min-width: 300px;
        margin-bottom: 0.75rem;
    }
    
    .col-lg-3 {
        min-width: 250px;
        margin-bottom: 0.75rem;
    }
    
    /* Responsive dropdown genişliği */
    @media (max-width: 768px) {
        .custom-select {
            min-width: 100%;
            width: 100%;
        }
        
        .col-lg-6, .col-lg-3 {
            min-width: 100%;
            margin-bottom: 1rem;
        }
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
    
    .btn-primary-modern:hover {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        color: white;
    }
    
    .btn-secondary-modern {
        background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%);
        color: white;
    }
    
    .btn-secondary-modern:hover {
        background: linear-gradient(135deg, #9ca3af 0%, #5a6268 100%);
        color: white;
    }
    
    .btn-info-modern {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }
    
    .btn-info-modern:hover {
        background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
        color: white;
    }
    
    .btn-warning-modern {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #212529;
    }
    
    .btn-warning-modern:hover {
        background: linear-gradient(135deg, #e0a800 0%, #d39e00 100%);
        color: #212529;
    }
    
    .barcode-id {
        font-weight: 700;
        font-size: 1.1em;
        color: #667eea;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: 2px solid #667eea;
        text-align: center;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        align-items: center;
    }
    
    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #e9ecef, transparent);
        margin: 1.5rem 0;
    }
    

    
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
            width: 100%;
        }
        
        .action-buttons .btn {
            width: 100%;
        }
        
        .page-title-modern {
            font-size: 2rem;
        }
    }
</style>
@endsection

@section('content')
    <div class="modern-barcode-edit">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-barcode"></i> Barkod Detay/Düzenle
                        </h1>
                        <p class="page-subtitle-modern">Barkod bilgilerini görüntüleyin ve düzenleyin</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="action-buttons justify-content-end">
                            <a href="{{ route('barcode.history', ['barkod' => $barcode->id]) }}" class="btn-modern btn-info-modern">
                                <i class="fas fa-list"></i> Barkod Hareketleri
                            </a>
                            <a href="{{ route('barcode.print', ['barcode_ids' => [$barcode->id]]) }}" class="btn-modern btn-warning-modern">
                                <i class="fas fa-print"></i> Barkod Yazdır
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if(auth()->user()->hasPermission(App\Models\Permission::LAB_PROCESSES) || $barcode->status !== \App\Models\Barcode::STATUS_WAITING)
            <!-- Edit Form Card -->
            <div class="card-modern">
                <div class="section-header">
                    <i class="fas fa-edit"></i>
                    Düzenleme Formu
                </div>
                <div class="form-section">
                    <form class="form" method="POST" action="{{ route('barcode.update', ['barkod' => $barcode->id]) }}">
                        @csrf
                        @method('PUT')
                        
                        @if($barcode->status !== \App\Models\Barcode::STATUS_MERGED)
                        <div class="row">
                            @if(auth()->user()->hasPermission(App\Models\Permission::LAB_PROCESSES) || auth()->user()->hasPermission(App\Models\Permission::MANAGEMENT))
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="info-label">Durum <span style="color: #dc3545">*</span></label>
                                        <select class="custom-select" name="status" id="status-select">
                                            <option value="" {{old('status') == '' ? 'selected' : ''}}>Durum seçiniz</option>
                                            @php
                                                $availableStatuses = [];
                                                
                                                // LAB_PROCESSES yetkisi varsa LAB_STATUSES'ı ekle
                                                if(auth()->user()->hasPermission(App\Models\Permission::LAB_PROCESSES)) {
                                                    foreach(\App\Models\Barcode::LAB_STATUSES as $key => $value) {
                                                        $availableStatuses[$key] = $value;
                                                    }
                                                }
                                                
                                                // MANAGEMENT yetkisi varsa WORKFLOW_STATUSES'ı ekle
                                                if(auth()->user()->hasPermission(App\Models\Permission::MANAGEMENT)) {
                                                    foreach(\App\Models\Barcode::WORKFLOW_STATUSES as $key => $value) {
                                                        $availableStatuses[$key] = $value;
                                                    }
                                                }
                                                
                                                // Sıralama için ksort
                                                ksort($availableStatuses);
                                            @endphp
                                            
                                            @foreach($availableStatuses as $key => $value)
                                                @if($barcode->canTransitionTo($key) || $barcode->status == $key)
                                                    <option {{ ($barcode->status == $key || old('status') == $key) ? 'selected' : ''}} value="{{$key}}" data-status-id="{{$key}}" data-status-name="{{$value}}">{{$value}}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        @if($errors->has('status'))
                                            <small class="form-text text-danger">
                                                {{ $errors->first('status') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            @endif


                        </div>
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group" id="company-group" style="display: none;">
                                    <label class="info-label">Firma</label>
                                    <select class="custom-select" name="company_id" id="company-select">
                                        <option {{old('company_id') == '' ? 'selected' : ''}} disabled>Firma seçiniz</option>
                                        @foreach($companies as $company)
                                            <option {{($barcode->company_id == $company->id || old('company_id') == $company->id) ? 'selected' : ''}} value="{{$company->id}}">{{$company->name}}</option>
                                        @endforeach
                                    </select>

                                    @if($errors->has('company_id'))
                                        <small class="form-text text-danger">
                                            {{ $errors->first('company_id') }}
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group" id="warehouse-group">
                                    <label class="info-label">Depo</label>
                                    <select class="custom-select" name="warehouse_id" id="warehouse-select">
                                        <option {{old('warehouse_id') == '' ? 'selected' : ''}} disabled>Depo seçiniz</option>
                                        @foreach($wareHouses as $wareHouse)
                                            <option {{($barcode->warehouse_id == $wareHouse->id || old('warehouse_id') == $wareHouse->id) ? 'selected' : ''}} value="{{$wareHouse->id}}">{{$wareHouse->name}}</option>
                                        @endforeach
                                    </select>

                                    @if($errors->has('warehouse_id'))
                                        <small class="form-text text-danger">
                                            {{ $errors->first('warehouse_id') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="info-label">Laboratuvar Notu</label>
                                    <input type="text" name="lab_note" id="lab_note" class="form-control" placeholder="Laboratuvar notu giriniz..." value="{{ old('lab_note', isset($barcode) ? $barcode->lab_note : '') }}"/>

                                    @if($errors->has('lab_note'))
                                        <small class="form-text text-danger">
                                            {{ $errors->first('lab_note') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="info-label">Genel Not</label>
                                    <input type="text" name="note" id="note" class="form-control" placeholder="Genel not giriniz..." value="{{ old('note', isset($barcode) ? $barcode->note : '') }}"/>

                                    @if($errors->has('note'))
                                        <small class="form-text text-danger">
                                            {{ $errors->first('note') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Red Sebepleri Seçimi -->
                        <div class="row" id="rejection-reasons-section" style="display: none;">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="info-label">Red Sebepleri <span class="text-danger">*</span></label>
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Red işlemi için en az bir sebep seçilmelidir.
                                    </div>
                                    <div class="row">
                                        @foreach($rejectionReasons as $reason)
                                            <div class="col-md-3 mb-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" 
                                                           class="custom-control-input" 
                                                           id="rejection_reason_{{ $reason->id }}" 
                                                           name="rejection_reasons[]" 
                                                           value="{{ $reason->id }}"
                                                           {{ in_array($reason->id, old('rejection_reasons', $barcode->rejectionReasons->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="rejection_reason_{{ $reason->id }}">
                                                        {{ $reason->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($errors->has('rejection_reasons'))
                                        <small class="form-text text-danger">
                                            {{ $errors->first('rejection_reasons') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="form-group">
                            <div class="action-buttons">
                                <button type="submit" class="btn-modern btn-primary-modern">
                                    <i class="fas fa-save"></i> Kaydet
                                </button>
                                <a href="{{ route('barcode.index') }}" class="btn-modern btn-secondary-modern">
                                    <i class="fas fa-times"></i> İptal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Status Overview Card -->
            <div class="card-modern">
                <div class="section-header">
                    <i class="fas fa-info-circle"></i>
                    Durum Bilgileri
                </div>
                <div class="form-section">
                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-label">Barkod Numarası</div>
                            <div class="info-value">{{ $barcode->id }}</div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-label">LAB Durumu</div>
                            @php
                                $statusClass = 'approved';
                                if ($barcode->status === \App\Models\Barcode::STATUS_WAITING) {
                                    $statusClass = 'waiting';
                                } elseif ($barcode->status === \App\Models\Barcode::STATUS_CONTROL_REPEAT) {
                                    $statusClass = 'control-repeat';
                                } elseif ($barcode->status === \App\Models\Barcode::STATUS_PRE_APPROVED) {
                                    $statusClass = 'pre-approved';
                                } elseif ($barcode->status === \App\Models\Barcode::STATUS_SHIPMENT_APPROVED) {
                                    $statusClass = 'shipment-approved';
                                } elseif ($barcode->status === \App\Models\Barcode::STATUS_REJECTED) {
                                    $statusClass = 'rejected';
                                } elseif ($barcode->status === \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER) {
                                    $statusClass = 'customer-transfer';
                                } elseif ($barcode->status === \App\Models\Barcode::STATUS_DELIVERED) {
                                    $statusClass = 'delivered';
                                } elseif ($barcode->status === \App\Models\Barcode::STATUS_MERGED) {
                                    $statusClass = 'merged';
                                }
                            @endphp
                            <div class="status-indicator status-{{ $statusClass }}">
                                {{ isset(\App\Models\Barcode::STATUSES[$barcode->status]) ? \App\Models\Barcode::STATUSES[$barcode->status] : 'Bilinmeyen Durum' }}
                            </div>
                        </div>

                        @if($barcode->rejectionReasons->count() > 0)
                        <div class="info-card" style="grid-column: span 2;">
                            <div class="info-label">Red Sebepleri</div>
                            <div class="info-value">
                                @foreach($barcode->rejectionReasons as $reason)
                                    <span class="badge badge-danger mr-1">{{ $reason->name }}</span>
                                @endforeach
                                @if($barcode->is_exceptionally_approved)
                                    <small class="d-block mt-2 text-muted">
                                        <i class="fas fa-info-circle"></i> Bu ürün red sebepleri olmasına rağmen istisnai onay ile ilerletilmiştir.
                                    </small>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($barcode->is_returned)
                        <div class="info-card" style="grid-column: span 2;">
                            <div class="info-label">İade Durumu</div>
                            <div class="info-value">
                                <span class="badge badge-warning" style="background: linear-gradient(135deg, #ffc107, #e0a800); color: #212529; padding: 8px 16px; border-radius: 25px; font-weight: 700;">
                                    <i class="fas fa-undo"></i> İade Edildi
                                </span>
                                <small class="d-block mt-2 text-muted">
                                    Bu ürün teslim edildi durumundan sonra ön onaylı durumuna alınarak iade edilmiştir.
                                </small>
                            </div>
                        </div>
                        @endif

                        @if($barcode->is_exceptionally_approved)
                        <div class="info-card" style="grid-column: span 2;">
                            <div class="info-label">İstisnai Onay</div>
                            <div class="info-value">
                                <span class="badge badge-warning" style="background: linear-gradient(135deg, #ffc107, #e0a800); color: #212529; padding: 8px 16px; border-radius: 25px; font-weight: 700;">
                                    <i class="fas fa-exclamation-triangle"></i> İstisnai Onaylı
                                </span>
                                <small class="d-block mt-2 text-muted">
                                    Bu ürün reddedildi durumundan sonra özel onay ile müşteri transfer veya teslim edildi durumuna geçirilmiştir.
                                </small>
                            </div>
                        </div>
                        @endif
                        

                        
                        <div class="info-card">
                            <div class="info-label">Stok</div>
                            <div class="info-value">{{ $barcode->stock ? $barcode->stock->name . ' - ' . $barcode->stock->code : 'Stok bulunamadı' }}</div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-label">Fırın</div>
                            <div class="info-value">{{ $barcode->kiln ? $barcode->kiln->name : 'Fırın bulunamadı' }}</div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-label">Parti Numarası</div>
                            <div class="info-value">{{ $barcode->party_number }}</div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-label">Şarj Numarası</div>
                            <div class="info-value">{{ $barcode->load_number }} {{ !is_null($barcode->rejected_load_number) ? ' + ' . $barcode->rejected_load_number : '' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Basic Information Card -->
            <div class="card-modern">
                <div class="section-header">
                    <i class="fas fa-clipboard"></i>
                    Temel Bilgiler
                </div>
                <div class="form-section">
                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-label">Miktar</div>
                            <div class="info-value">{{ $barcode->quantity ? $barcode->quantity->quantity . " KG" : 'Miktar bulunamadı' }}</div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-label">Oluşturan Kişi</div>
                            <div class="info-value">{{ $barcode->createdBy ? $barcode->createdBy->name : 'Kullanıcı bulunamadı' }}</div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-label">Oluşturulma Tarihi</div>
                            <div class="info-value">{{ $barcode->created_at->tz('Europe/Istanbul')->format('d.m.Y H:i') }}</div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-label">Depo</div>
                            <div class="info-value">{{ $barcode->warehouse ? $barcode->warehouse->name : 'Depo bulunamadı' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laboratory Information Card -->
            <div class="card-modern">
                <div class="section-header">
                    <i class="fas fa-flask"></i>
                    Laboratuvar Bilgileri
                </div>
                <div class="form-section">
                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-label">Lab Personeli</div>
                            <div class="info-value">{{ $barcode->labBy->name ?? 'Belirtilmemiş' }}</div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-label">Lab İşlem Tarihi</div>
                            <div class="info-value">{{ $barcode->lab_at ? \Carbon\Carbon::parse($barcode->lab_at)->format('d.m.Y H:i') : 'Belirtilmemiş' }}</div>
                        </div>
                        
                        <div class="info-card" style="grid-column: span 2;">
                            <div class="info-label">Lab Notu</div>
                            <div class="info-value">{{ $barcode->lab_note ?? 'Not bulunmuyor' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Warehouse Information Card -->
            <div class="card-modern">
                <div class="section-header">
                    <i class="fas fa-warehouse"></i>
                    Depo Bilgileri
                </div>
                <div class="form-section">
                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-label">Depo Transfer Personeli</div>
                            <div class="info-value">{{ $barcode->warehouseTransferredBy->name ?? 'Belirtilmemiş' }}</div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-label">Depo Transfer İşlem Tarihi</div>
                            <div class="info-value">{{ $barcode->warehouse_transferred_at ? \Carbon\Carbon::parse($barcode->warehouse_transferred_at)->format('d.m.Y H:i') : 'Belirtilmemiş' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company Information Card -->
            <div class="card-modern">
                <div class="section-header">
                    <i class="fas fa-building"></i>
                    Müşteri Bilgileri
                </div>
                <div class="form-section">
                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-label">Müşteri</div>
                            <div class="info-value">{{ $barcode->company ? $barcode->company->name : 'Müşteri bulunamadı' }}</div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-label">Müşteri Transfer İşlem Tarihi</div>
                            <div class="info-value">{{ $barcode->company_transferred_at ? \Carbon\Carbon::parse($barcode->company_transferred_at)->format('d.m.Y H:i') : 'Belirtilmemiş' }}</div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-label">Teslim Eden Personel</div>
                            <div class="info-value">{{ $barcode->deliveredBy->name ?? 'Belirtilmemiş' }}</div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-label">Teslim Edilme Tarihi</div>
                            <div class="info-value">{{ $barcode->delivered_at ? \Carbon\Carbon::parse($barcode->delivered_at)->format('d.m.Y H:i') : 'Belirtilmemiş' }}</div>
                        </div>
                        @if($barcode->is_returned)
                        <div class="info-card">
                            <div class="info-label">İade İşlemi</div>
                            <div class="info-value">
                                {{ $barcode->returned_at ? \Carbon\Carbon::parse($barcode->returned_at)->format('d.m.Y H:i') : 'Belirtilmemiş' }}
                                <br>
                                <small>İade Eden: {{ optional($barcode->returnedBy)->name ?? 'Belirtilmemiş' }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Durum geçiş kuralları - Barcode modelindeki güncel sabitlerle uyumlu
    const statusTransitions = {
        1: [2, 3, 5], // Beklemede -> Kontrol Tekrarı, Ön Onaylı, Reddedildi
        2: [3, 5],    // Kontrol Tekrarı -> Ön Onaylı, Reddedildi
        3: [4, 2, 5], // Ön Onaylı -> Sevk Onaylı, Kontrol Tekrarı, Reddedildi
        4: [9, 10],   // Sevk Onaylı -> Müşteri Transfer, Teslim Edildi
        5: [9, 10],   // Reddedildi -> Müşteri Transfer, Teslim Edildi
        8: [],        // Düzeltme Faaliyetinde Kullanıldı -> Geri dönüş yok
        9: [10],      // Müşteri Transfer -> Teslim Edildi
        10: [3]       // Teslim Edildi -> Ön Onaylı (iade)
    };

    const statusNames = {
        1: 'Beklemede',
        2: 'Kontrol Tekrarı',
        3: 'Ön Onaylı',
        4: 'Sevk Onaylı',
        5: 'Reddedildi',
        8: 'Düzeltme Faaliyetinde Kullanıldı',
        9: 'Müşteri Transfer',
        10: 'Teslim Edildi'
    };

    const currentStatus = {{ $barcode->status }};
    const currentStatusName = statusNames[currentStatus];

    // Firma ve depo alanlarını durum bazında kontrol et
    function updateCompanyAndWarehouseFields(selectedStatus) {
        const companyGroup = $('#company-group');
        const warehouseGroup = $('#warehouse-group');
        const companySelect = $('#company-select');
        const warehouseSelect = $('#warehouse-select');
        
        // Müşteri Transfer (9) veya Teslim Edildi (10) durumlarında
        if (selectedStatus === 9 || selectedStatus === 10) {
            // Firma alanını göster
            companyGroup.show();
            companySelect.prop('required', true);
            
            // Depo alanını gizle ve değerini temizle
            warehouseGroup.hide();
            warehouseSelect.prop('required', false);
            warehouseSelect.val('');
        } else {
            // Diğer durumlarda
            // Firma alanını gizle ve değerini temizle
            companyGroup.hide();
            companySelect.prop('required', false);
            companySelect.val('');
            
            // Depo alanını göster
            warehouseGroup.show();
            warehouseSelect.prop('required', true);
        }
    }

// Red sebepleri bölümünü durum bazında göster/gizle
function updateRejectionReasonsSection(selectedStatus) {
    const rejectionReasonsSection = $('#rejection-reasons-section');
    
    if (selectedStatus === 5) { // STATUS_REJECTED
        rejectionReasonsSection.show();
    } else {
        rejectionReasonsSection.hide();
        // Red sebeplerini temizle
        $('input[name="rejection_reasons[]"]').prop('checked', false);
    }
}

    // Sayfa yüklendiğinde mevcut duruma göre alanları ayarla
    updateCompanyAndWarehouseFields(currentStatus);
    updateRejectionReasonsSection(currentStatus);

    // Durum seçimi değiştiğinde kontrol
    $('#status-select').on('change', function() {
        const selectedStatus = parseInt($(this).val());
        const selectedText = $(this).find('option:selected').text();
        const selectedOption = $(this).find('option:selected');
        const statusId = selectedOption.data('status-id');
        const statusName = selectedOption.data('status-name');
        
        // Debug bilgileri (gerekirse aktif edilebilir)
        // console.log('Seçilen durum ID:', selectedStatus);
        // console.log('Seçilen durum metni:', selectedText);
        // console.log('Data status-id:', statusId);
        // console.log('Data status-name:', statusName);
        
        // Sadece durum değişikliği varsa kontrol et (undefined veya boş değer kontrolü)
        if (selectedStatus && selectedStatus !== '' && selectedStatus !== currentStatus) {
            const allowedTransitions = statusTransitions[currentStatus] || [];
            
            if (!allowedTransitions.includes(selectedStatus)) {
                const selectedStatusName = statusNames[selectedStatus] || 'Bilinmeyen Durum';
                alert(`Geçersiz durum geçişi!\n\nMevcut durum: ${currentStatusName}\nSeçilen durum: ${selectedStatusName}\n\nBu geçiş laboratuvar kurallarına uygun değil.`);
                $(this).val(currentStatus);
                return false;
            }
        }
        
        // Red sebepleri bölümünü göster/gizle
        updateRejectionReasonsSection(selectedStatus);
        
        // Firma ve depo alanlarını güncelle
        updateCompanyAndWarehouseFields(selectedStatus);
    });

    // Form gönderilmeden önce son kontrol
    $('form').on('submit', function(e) {
        const selectedStatus = parseInt($('#status-select').val());
        const selectedText = $('#status-select').find('option:selected').text();
        const selectedOption = $('#status-select').find('option:selected');
        const statusId = selectedOption.data('status-id');
        const statusName = selectedOption.data('status-name');
        
        // Debug bilgileri (gerekirse aktif edilebilir)
        // console.log('Form submit - Seçilen durum ID:', selectedStatus);
        // console.log('Form submit - Seçilen durum metni:', selectedText);
        // console.log('Form submit - Data status-id:', statusId);
        // console.log('Form submit - Data status-name:', statusName);
        
        // Sadece durum değişikliği varsa kontrol et (undefined veya boş değer kontrolü)
        if (selectedStatus && selectedStatus !== '' && selectedStatus !== currentStatus) {
            const allowedTransitions = statusTransitions[currentStatus] || [];
            
            if (!allowedTransitions.includes(selectedStatus)) {
                e.preventDefault();
                const selectedStatusName = statusNames[selectedStatus] || 'Bilinmeyen Durum';
                alert(`Form gönderilemedi!\n\nGeçersiz durum geçişi: ${currentStatusName} → ${selectedStatusName}\n\nBu geçiş laboratuvar kurallarına uygun değil.`);
                return false;
            }
        }
        
        // Durum bazında firma ve depo validasyonu
        if (selectedStatus === 9 || selectedStatus === 10) {
            // Müşteri Transfer veya Teslim Edildi durumunda firma zorunlu
            const companyValue = $('#company-select').val();
            if (!companyValue) {
                e.preventDefault();
                alert('Müşteri Transfer veya Teslim Edildi durumunda firma seçimi zorunludur!');
                return false;
            }
        } else {
            // Diğer durumlarda depo zorunlu
            const warehouseValue = $('#warehouse-select').val();
            if (!warehouseValue) {
                e.preventDefault();
                alert('Bu durumda depo seçimi zorunludur!');
                return false;
            }
        }

        // Red durumunda red sebepleri validasyonu
        if (selectedStatus === 5) { // STATUS_REJECTED
            const selectedReasons = $('input[name="rejection_reasons[]"]:checked').length;
            if (selectedReasons === 0) {
                e.preventDefault();
                alert('Red işlemi için en az bir red sebebi seçilmelidir!');
                return false;
            }
        }
    });
});
</script>
@endsection
