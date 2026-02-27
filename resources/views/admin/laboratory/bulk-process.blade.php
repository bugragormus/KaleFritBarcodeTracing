@extends('layouts.app')

@section('styles')
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
<style>
    body, .main-content, .modern-lab-bulk-process {
        background: #f8f9fa !important;
    }
    .modern-lab-bulk-process {
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
    .info-alert-modern {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        border: 1px solid #e9ecef;
        color: #495057;
    }
    .barcode-item-modern {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.3s;
        background: #fff;
    }
    .barcode-item-modern:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .barcode-item-modern.selected {
        border-color: #007bff;
        background-color: #f8f9ff;
    }
    
    /* Tıklanabilir card stili */
    .clickable-card {
        cursor: pointer;
        position: relative;
    }
    
    .clickable-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .clickable-card .custom-control {
        pointer-events: none;
    }
    
    .clickable-card .custom-control-input {
        pointer-events: auto;
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
    }
    .btn-modern:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }
    .btn-modern:disabled:hover {
        transform: none !important;
        box-shadow: none !important;
    }
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    .btn-success-modern {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    .btn-danger-modern {
        background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        color: white;
    }
    .btn-warning-modern {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: white;
    }
    .btn-info-modern {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }
    .btn-primary-modern {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
    }
    .btn-secondary-modern {
        background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%);
        color: white;
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
    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #ffffff;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
    }
    .form-control::placeholder {
        color: #adb5bd;
    }
    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
    }
    .d-grid {
        display: grid;
    }
    .gap-2 {
        gap: 0.5rem;
    }
    .badge {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 8px;
    }
    .badge-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .badge-secondary {
        background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%);
        color: white;
    }
    .alert {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.5rem;
    }
    .alert-info {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        color: #0c5460;
    }
    
    /* Filtre Bölümü Stilleri */
    .form-label-modern {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        font-size: 0.95rem;
    }
    .form-label-modern i {
        margin-right: 0.5rem;
        color: #667eea;
        font-size: 0.9rem;
    }
    .form-control-modern {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #ffffff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    .form-control-modern:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
        transform: translateY(-1px);
    }
    .form-control-modern:hover {
        border-color: #667eea;
        transform: translateY(-1px);
    }
    .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
    }
    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
    }
    
    /* Gelişmiş Filtre Bölümü Stilleri */
    .filter-section-modern {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        border: 1px solid #dee2e6;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    .filter-header-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .filter-title-modern {
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
    }
    
    .filter-title-modern i {
        margin-right: 0.5rem;
        font-size: 1rem;
    }
    
    .filter-content-modern {
        padding: 1.5rem;
        background: white;
    }
    
    .filter-actions-modern {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        height: 100%;
        padding-top: 1.5rem;
    }
    
    .btn-clear-filter {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
        min-width: 140px;
        height: 45px;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: none !important;
    }
    
    .btn-clear-filter:hover {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
        transform: none !important;
        box-shadow: none !important;
    }
    
    .form-group.mb-0 {
        margin-bottom: 0 !important;
    }
    
    /* Filtre vurgulama stili */
    .filter-highlight {
        padding: 0.25rem 0.5rem;
        margin: 0 0.25rem;
        border-radius: 4px;
        background-color: rgba(102, 126, 234, 0.1);
        border: 1px solid rgba(102, 126, 234, 0.2);
    }
    
    /* Responsive düzenlemeler */
    @media (max-width: 768px) {
        .filter-content-modern .row {
            flex-direction: column;
        }
        
        .filter-content-modern .col-md-3 {
            width: 100%;
            margin-bottom: 1rem;
        }
        
        .filter-actions-modern {
            justify-content: center;
        }
        
        .btn-clear-filter {
            width: 100%;
            min-width: auto;
        }
    }
    
    @media (max-width: 992px) {
        .filter-content-modern .col-md-3 {
            margin-bottom: 1rem;
        }
    }
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }
    
    /* Rejection Reasons Styling */
    .rejection-reason-checkbox {
        margin-right: 0.5rem;
    }
    
    .custom-control-label {
        font-size: 0.9rem;
        color: #495057;
        cursor: pointer;
    }
    
    .custom-control-input:checked ~ .custom-control-label {
        color: #dc3545;
        font-weight: 600;
    }
    
    /* Bulk Process Panel Improvements */
    .fs-6 {
        font-size: 1rem !important;
    }
    
    .gap-3 {
        gap: 1rem !important;
    }
    
    .flex-fill {
        flex: 1 1 auto !important;
        min-width: 200px;
    }
    
    .alert-info {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        border: 2px solid #17a2b8;
        border-radius: 15px;
    }
    
    .alert-warning {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 2px solid #ffc107;
        border-radius: 15px;
    }
    
    .badge {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        border-radius: 10px;
    }
    
    .badge-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .badge-secondary {
        background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%);
    }
    
    /* Inline Confirmation Styles */
    .inline-confirmation {
        display: none;
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 2px solid #ffc107;
        border-radius: 15px;
        padding: 1.5rem;
        margin: 1rem 0;
        box-shadow: 0 5px 15px rgba(255, 193, 7, 0.2);
    }
    .inline-confirmation.show {
        display: block;
        animation: slideDown 0.3s ease-out;
    }
    .inline-confirmation h5 {
        color: #856404;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
    .inline-confirmation h5 i {
        margin-right: 0.5rem;
    }
    .confirmation-details {
        background: rgba(255, 255, 255, 0.7);
        border-radius: 10px;
        padding: 1rem;
        margin: 1rem 0;
    }
    .confirmation-details p {
        margin-bottom: 0.5rem;
        color: #495057;
    }
    .confirmation-details strong {
        color: #856404;
    }
    .confirmation-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }
    .confirmation-actions .btn {
        flex: 1;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .confirmation-actions .btn:hover {
        transform: translateY(-2px);
    }
    
    /* Result Display Styles */
    .result-display {
        display: none;
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border: 2px solid #28a745;
        border-radius: 15px;
        padding: 1.5rem;
        margin: 1rem 0;
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.2);
    }
    .result-display.show {
        display: block;
        animation: slideDown 0.3s ease-out;
    }
    .result-display.error {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        border-color: #dc3545;
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.2);
    }
    .result-display h5 {
        color: #155724;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
    .result-display.error h5 {
        color: #721c24;
    }
    .result-display h5 i {
        margin-right: 0.5rem;
    }
    .result-stats {
        display: flex;
        gap: 1rem;
        margin: 1rem 0;
    }
    .result-stat {
        flex: 1;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
    }
    .result-stat h4 {
        margin-bottom: 0.5rem;
        color: #155724;
    }
    .result-stat p {
        margin-bottom: 0;
        color: #495057;
        font-size: 0.9rem;
    }
    .error-list {
        background: rgba(255, 255, 255, 0.7);
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
    }
    .error-list h6 {
        color: #721c24;
        margin-bottom: 0.5rem;
    }
    .error-list ul {
        margin-bottom: 0;
        padding-left: 1.5rem;
    }
    .error-list li {
        color: #721c24;
        margin-bottom: 0.25rem;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @media (max-width: 768px) {
        .page-title-modern {
            font-size: 2rem;
        }
        .card-body-modern, .info-alert-modern, .bulk-actions-modern {
            padding: 1.2rem 1rem;
        }
        .confirmation-actions {
            flex-direction: column;
        }
        .result-stats {
            flex-direction: column;
        }
    }
    
    /* Select2 Custom Styling */
    .select2-container--default .select2-selection--single {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        height: auto;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #ffffff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    .select2-container--default .select2-selection--single:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
    }
    
    .select2-container--default .select2-selection--single:hover {
        border-color: #667eea;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #495057;
        padding: 0;
        line-height: 1.5;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        right: 1rem;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #667eea transparent transparent transparent;
        border-width: 6px 4px 0 4px;
    }
    
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #667eea transparent;
        border-width: 0 4px 6px 4px;
    }
    
    .select2-dropdown {
        border: 2px solid #667eea;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        background: #ffffff;
    }
    
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
    }
    
    .select2-container--default .select2-results__option {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        color: #495057;
        transition: all 0.2s ease;
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .select2-container--default .select2-results__option[aria-selected=true] {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
    }
    
    .select2-container {
        width: 100% !important;
    }
    
    /* Select2 Dropdown Positioning */
    .select2-dropdown {
        z-index: 9999;
    }
    
    /* Select2 Search Field Focus */
    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
    }
    
    /* Select2 Results Container */
    .select2-results__option {
        font-size: 0.95rem;
        padding: 0.6rem 1rem;
    }
    
    /* Select2 Loading State */
    .select2-container--default .select2-results__option--loading {
        color: #6c757d;
        font-style: italic;
    }
    
    /* Select2 Clear Button */
    .select2-container--default .select2-selection--single .select2-selection__clear {
        color: #6c757d;
        font-weight: bold;
        margin-right: 2rem;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__clear:hover {
        color: #dc3545;
    }
</style>
@endsection

@section('content')
<div class="modern-lab-bulk-process">
    <div class="container-fluid">
        <!-- Modern Page Header -->
        <div class="page-header-modern">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title-modern">
                        <i class="fas fa-layer-group"></i> Toplu İşlem
                    </h1>
                    <p class="page-subtitle-modern">Laboratuvar işlemleri için barkodları toplu olarak işleyin</p>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('laboratory.dashboard') }}" class="btn-modern btn-secondary-modern">
                        <i class="fas fa-arrow-left mr-1"></i> Geri Dön
                    </a>
                </div>
            </div>
        </div>

        <!-- Toplu İşlem Bilgileri -->
        <div class="info-alert-modern">
            <h5><i class="fas fa-info-circle mr-2"></i>Toplu İşlem Nasıl Çalışır?</h5>
            <ul class="mb-0">
                <li>Laboratuvar işlemleri için barkodları seçin</li>
                <li>İşlem türünü belirleyin (Kabul/Red)</li>
                <li>İsteğe bağlı not ekleyin</li>
                <li>Toplu işlemi başlatın</li>
            </ul>
        </div>

        <!-- Toplu İşlem Araçları -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-cogs"></i> Toplu İşlem Kontrol Paneli
                </h3>
                <p class="card-subtitle-modern">
                    Seçili barkodları toplu olarak işlemek için gerekli ayarları yapın
                </p>
            </div>
            <div class="card-body-modern">
                <!-- Seçim ve İstatistikler -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-clipboard-list"></i> Barkod Seçimi
                            </label>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="selectAll">
                                <label class="custom-control-label" for="selectAll">
                                    <strong>Tümünü Seç</strong>
                                </label>
                            </div>
                            <div class="mt-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge badge-primary fs-6">
                                        <i class="fas fa-check-circle"></i> Seçili: <span id="selectedCount">0</span>
                                    </span>
                                    <span class="badge badge-secondary fs-6">
                                        <i class="fas fa-list"></i> Toplam: <span id="totalCount">{{ $pendingBarcodes->count() }}</span>
                                    </span>
                                </div>
                                <div class="mt-2" id="selectionWarning" style="display: none;">
                                    <div class="alert alert-warning py-2 px-3 mb-0">
                                        <i class="fas fa-exclamation-triangle"></i> 
                                        İşlem yapmak için en az bir barkod seçmelisiniz
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-sticky-note"></i> İşlem Notu
                            </label>
                            <textarea class="form-control" id="bulkNote" rows="3" 
                                      placeholder="Seçili tüm barkodlar için işlem notu ekleyin..."></textarea>
                            <small class="form-text text-muted">
                                Bu not tüm seçili barkodlar için geçerli olacaktır
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Red Sebepleri -->
                <div class="row mb-4" id="rejectionReasonsSection" style="display: none;">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-exclamation-triangle"></i> Red Sebepleri <span class="text-danger">*</span>
                            </label>
                            <div class="alert alert-warning py-3">
                                <div class="row">
                                    @foreach(\App\Models\RejectionReason::active()->get() as $reason)
                                    <div class="col-md-3 col-sm-6 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input rejection-reason-checkbox" 
                                                   id="reason_{{ $reason->id }}" value="{{ $reason->id }}">
                                            <label class="custom-control-label" for="reason_{{ $reason->id }}">
                                                {{ $reason->name }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="alert alert-danger py-2 mt-2 mb-0">
                                    <i class="fas fa-exclamation-triangle"></i> <strong>Zorunlu:</strong> Red işlemi için en az bir red sebebi seçmelisiniz!
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- İşlem Butonları -->
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label mb-3">
                                <i class="fas fa-play-circle"></i> İşlem Seçenekleri
                            </label>
                            <div class="d-flex flex-wrap gap-3">
                                <button class="btn-modern btn-success-modern flex-fill" onclick="showConfirmation('pre_approved')" disabled id="preApprovedBtn" 
                                        title="Barkod seçmek için yukarıdaki checkbox'ları işaretleyin">
                                    <i class="fas fa-check"></i> Ön Onaylı
                                </button>
                                <button class="btn-modern btn-info-modern flex-fill" onclick="showConfirmation('control_repeat')" disabled id="controlRepeatBtn"
                                        title="Barkod seçmek için yukarıdaki checkbox'ları işaretleyin">
                                    <i class="fas fa-redo"></i> Kontrol Tekrarı
                                </button>
                                <button class="btn-modern btn-primary-modern flex-fill" onclick="showConfirmation('shipment_approved')" disabled id="shipmentApprovedBtn"
                                        title="Sadece ön onaylı barkodlar için kullanılabilir">
                                    <i class="fas fa-shipping-fast"></i> Sevk Onaylı
                                </button>
                                <button class="btn-modern btn-danger-modern flex-fill" onclick="showConfirmation('reject')" disabled id="rejectBtn"
                                        title="Barkod seçmek için yukarıdaki checkbox'ları işaretleyin">
                                    <i class="fas fa-times"></i> Reddet
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- İşlem Durumu -->
                <div class="row mt-3" id="processStatus" style="display: none;">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <div class="spinner-border spinner-border-sm mr-2" role="status">
                                    <span class="sr-only">İşleniyor...</span>
                                </div>
                                <span id="statusMessage">İşlem hazırlanıyor...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inline Confirmation -->
        <div class="inline-confirmation" id="inlineConfirmation">
            <h5><i class="fas fa-exclamation-triangle"></i> İşlem Onayı</h5>
            <div class="confirmation-details">
                <p><strong>İşlem Türü:</strong> <span id="confirmAction"></span></p>
                <p><strong>Seçili Barkod Sayısı:</strong> <span id="confirmCount"></span></p>
                <p><strong>Stok Bilgileri:</strong> <span id="confirmStockInfo"></span></p>
                <p><strong>Not:</strong> <span id="confirmNote"></span></p>
                <p id="rejectionReasonsRow" style="display: none;"><strong>Red Sebepleri:</strong> <span id="confirmRejectionReasons"></span></p>
                <p class="text-muted mb-0"><i class="fas fa-info-circle mr-1"></i>Bu işlem seçili tüm barkodları aynı anda işleyecektir ve geri alınamaz.</p>
            </div>
            <div class="confirmation-actions">
                <button type="button" class="btn btn-secondary" onclick="hideConfirmation()">
                    <i class="fas fa-times"></i> İptal
                </button>
                <button type="button" class="btn btn-primary" id="confirmProcessBtn">
                    <i class="fas fa-check"></i> İşlemi Onayla
                </button>
            </div>
        </div>

        <!-- Result Display -->
        <div class="result-display" id="resultDisplay">
            <h5><i class="fas fa-check-circle"></i> İşlem Sonucu</h5>
            <div id="resultContent">
                <!-- Sonuç içeriği buraya yüklenecek -->
            </div>
            <div class="confirmation-actions">
                <button type="button" class="btn btn-secondary" onclick="hideResult()">
                    <i class="fas fa-times"></i> Kapat
                </button>
                <button type="button" class="btn btn-primary" onclick="location.reload()">
                    <i class="fas fa-redo"></i> Sayfayı Yenile
                </button>
            </div>
        </div>

        <!-- Barkod Listesi -->
        <div class="row">
            <div class="col-12">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern"><i class="fas fa-list mr-2"></i>Laboratuvar İşlemleri ({{ $pendingBarcodes->count() }} adet)</h3>
                    </div>
                    <div class="card-body-modern">
                        @if($pendingBarcodes->count() > 0)
                        <!-- Filtre Bölümü -->
                        <div class="filter-section-modern mb-4">
                            <div class="filter-header-modern">
                                <h5 class="filter-title-modern">
                                    <i class="fas fa-filter mr-2"></i>Filtreleme Seçenekleri
                                </h5>
                            </div>
                            <div class="filter-content-modern">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label for="stockFilter" class="form-label-modern">
                                                <i class="fas fa-list mr-2"></i>Stok Seç
                                            </label>
                                            <select class="form-control-modern" id="stockFilter">
                                                <option value="">Tüm Stoklar</option>
                                                @php
                                                    $uniqueStocks = $pendingBarcodes->pluck('stock.name')->unique()->sort();
                                                @endphp
                                                @foreach($uniqueStocks as $stockName)
                                                    <option value="{{ $stockName }}">{{ $stockName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label for="loadNumberFilter" class="form-label-modern">
                                                <i class="fas fa-hashtag mr-2"></i>Şarj No
                                            </label>
                                            <select class="form-control-modern" id="loadNumberFilter">
                                                <option value="">Tüm Şarj Numaraları</option>
                                                @php
                                                    $uniqueLoadNumbers = $pendingBarcodes->pluck('load_number')->unique()->sort();
                                                @endphp
                                                @foreach($uniqueLoadNumbers as $loadNumber)
                                                    <option value="{{ $loadNumber }}">#{{ $loadNumber }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label for="partyNumberFilter" class="form-label-modern">
                                                <i class="fas fa-tag mr-2"></i>Parti No
                                            </label>
                                            <select class="form-control-modern" id="partyNumberFilter">
                                                <option value="">Tüm Parti Numaraları</option>
                                                @php
                                                    $uniquePartyNumbers = $pendingBarcodes->pluck('party_number')->filter()->unique()->sort();
                                                @endphp
                                                @foreach($uniquePartyNumbers as $partyNumber)
                                                    <option value="{{ $partyNumber }}">#{{ $partyNumber }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label for="statusFilter" class="form-label-modern">
                                                <i class="fas fa-info-circle mr-2"></i>Durum Seç
                                            </label>
                                            <select class="form-control-modern" id="statusFilter">
                                                <option value="">Tüm Durumlar</option>
                                                @foreach(\App\Models\Barcode::STATUSES as $statusKey => $statusName)
                                                    <option value="{{ $statusKey }}">{{ $statusName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="filter-actions-modern">
                                            <button type="button" class="btn btn-modern btn-outline-secondary btn-clear-filter" id="clearFilter">
                                                <i class="fas fa-times mr-2"></i>Filtreyi Temizle
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" id="barcodeList">
                            @foreach($pendingBarcodes as $barcode)
                            <div class="col-md-6 col-lg-4">
                                <div class="barcode-item-modern clickable-card" data-barcode-id="{{ $barcode->id }}">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input barcode-checkbox" 
                                               id="barcode_{{ $barcode->id }}" value="{{ $barcode->id }}">
                                        <label class="custom-control-label" for="barcode_{{ $barcode->id }}"></label>
                                    </div>
                                    <div class="mt-2">
                                        <h6 class="mb-1">
                                            <div class="stock-info mb-2">
                                                <strong>{{ $barcode->stock->name }}</strong> - Şarj No: #{{ $barcode->load_number }} - Parti No: #{{ $barcode->party_number ?? 'N/A' }}
                                            </div>
                                            <div class="status-badge-container">
                                                <span class="badge badge-{{ 
                                                    $barcode->status == \App\Models\Barcode::STATUS_WAITING ? 'warning' : 
                                                    ($barcode->status == \App\Models\Barcode::STATUS_PRE_APPROVED ? 'success' : 
                                                    ($barcode->status == \App\Models\Barcode::STATUS_CONTROL_REPEAT ? 'info' : 'secondary')) 
                                                }}">
                                                    {{ \App\Models\Barcode::STATUSES[$barcode->status] }}
                                                </span>
                                            </div>
                                        </h6>
                                        <p class="mb-1 text-muted">
                                            <i class="fas fa-fire mr-1"></i>{{ $barcode->kiln->name }}
                                        </p>
                                        <p class="mb-1 text-muted">
                                            <i class="fas fa-hashtag mr-1"></i>{{ $barcode->stock->code }}
                                        </p>
                                        <p class="mb-1">
                                            <span class="badge badge-info status-badge">
                                                <i class="fas fa-weight-hanging mr-1"></i>{{ $barcode->quantity->quantity }} KG
                                            </span>
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-user mr-1"></i>{{ $barcode->createdBy->name }}
                                            <br>
                                            <i class="fas fa-clock mr-1"></i>{{ $barcode->created_at->tz('Europe/Istanbul')->format('d.m.Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-muted">Laboratuvar işlemi bekleyen barkod bulunmuyor!</h5>
                            <p class="text-muted">Tüm barkodlar işlenmiş durumda.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script>
    window.LabBulkConfig = {
        processRoute: '{{ route("laboratory.process-bulk") }}',
        statusRoute: '{{ route("laboratory.barcode-statuses") }}',
        csrfToken: '{{ csrf_token() }}'
    };
</script>
<script src="{{ asset('assets/js/modules/lab-bulk-process.js') }}"></script>
@endsection