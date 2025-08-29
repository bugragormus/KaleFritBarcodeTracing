@extends('layouts.app')

@section('styles')
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
                                            <label for="stockSearch" class="form-label-modern">
                                                <i class="fas fa-search mr-2"></i>Stok Adı Ara
                                            </label>
                                            <input type="text" class="form-control-modern" id="stockSearch" 
                                                   placeholder="Stok adı yazın..." autocomplete="off">
                                        </div>
                                    </div>
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
                                    <div class="col-md-3">
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
<script>
let selectedBarcodes = [];
let currentAction = '';
let barcodeStatuses = {}; // Barkod durumlarını saklamak için

$(document).ready(function() {
    initializeCheckboxes();
    updateSelectedCount();
    loadBarcodeStatuses();
    initializeFilters();
});

function initializeCheckboxes() {
    // Tümünü seç checkbox'ı - sadece görünür barkodları seçer
    $('#selectAll').change(function() {
        const isChecked = $(this).is(':checked');
        
        // Sadece görünür (filtrelenmiş) barkodları seç
        $('.barcode-item-modern').each(function() {
            const $item = $(this);
            const $container = $item.closest('.col-md-6, .col-lg-4');
            const $checkbox = $item.find('.barcode-checkbox');
            
            // Eğer container görünürse checkbox'ı işaretle/kaldır
            if ($container.is(':visible')) {
                $checkbox.prop('checked', isChecked);
            }
        });
        
        updateSelectedBarcodes();
        updateSelectedCount();
        updateBulkButtons();
    });

    // Tekil barkod checkbox'ları
    $('.barcode-checkbox').change(function() {
        updateSelectedBarcodes();
        updateSelectedCount();
        updateBulkButtons();
        updateSelectAllState();
    });
    
    // Card'a tıklandığında checkbox'ı seç
    $('.clickable-card').click(function(e) {
        // Eğer checkbox'a tıklandıysa işlemi yapma (çift işlem olmasın)
        if ($(e.target).closest('.custom-control').length > 0) {
            return;
        }
        
        const $checkbox = $(this).find('.barcode-checkbox');
        const isChecked = $checkbox.is(':checked');
        
        // Checkbox durumunu tersine çevir
        $checkbox.prop('checked', !isChecked).trigger('change');
        
        // Card'ın seçili durumunu güncelle
        $(this).toggleClass('selected', !isChecked);
    });
}

function initializeFilters() {
    // Stok adına göre filtreleme (dropdown)
    $('#stockFilter').change(function() {
        filterBarcodes();
    });
    
    // Durum filtreleme (dropdown)
    $('#statusFilter').change(function() {
        filterBarcodes();
    });
    
    // Arama kutusu ile filtreleme
    $('#stockSearch').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        if (searchTerm.length >= 2) {
            // Arama terimi 2 karakterden fazlaysa filtrele
            filterBySearch(searchTerm);
        } else if (searchTerm.length === 0) {
            // Arama kutusu boşsa tümünü göster
            showAllBarcodes();
        }
    });
    
    // Filtreyi temizle butonu
    $('#clearFilter').click(function() {
        $('#stockSearch').val('');
        $('#stockFilter').val('');
        $('#statusFilter').val('');
        showAllBarcodes();
        resetCheckboxes();
    });
}

function filterBarcodes() {
    const selectedStock = $('#stockFilter').val();
    const selectedStatus = $('#statusFilter').val();
    let visibleCount = 0;
    
    // Filtreleme animasyonu
    $('.barcode-item-modern').each(function() {
        const $item = $(this);
        const $container = $item.closest('.col-md-6, .col-lg-4');
        const stockName = $item.find('h6 strong').text().trim();
        
        // Durum bilgisini al (durum badge'inden - ilk badge değil)
        const statusBadge = $item.find('.status-badge-container .badge').text().trim();
        const statusKey = getStatusKeyFromText(statusBadge);
        
        // Debug için console.log
        console.log('Barkod:', stockName, 'Durum Badge:', statusBadge, 'Durum Key:', statusKey, 'Seçilen Durum:', selectedStatus);
        
        // Hem stok hem de durum filtresini kontrol et
        const stockMatch = selectedStock === '' || stockName === selectedStock;
        const statusMatch = selectedStatus === '' || statusKey === selectedStatus;
        
        if (stockMatch && statusMatch) {
            $container.fadeIn(300);
            visibleCount++;
        } else {
            $container.fadeOut(300);
        }
    });
    
    // Görünür barkod sayısını güncelle
    updateFilteredCount(visibleCount);
    
    // Checkbox durumlarını sıfırla
    resetCheckboxes();
}

function getStatusKeyFromText(statusText) {
    // Durum metninden durum anahtarını bul
    const statusMap = {
        'Beklemede': 1,
        'Kontrol Tekrarı': 2,
        'Ön Onaylı': 3,
        'Sevk Onaylı': 4,
        'Reddedildi': 5
    };
    
    for (const [text, key] of Object.entries(statusMap)) {
        if (statusText === text) {
            return key.toString();
        }
    }
    
    return '';
}

function filterBySearch(searchTerm) {
    let visibleCount = 0;
    
    $('.barcode-item-modern').each(function() {
        const $item = $(this);
        const $container = $item.closest('.col-md-6, .col-lg-4');
        const stockName = $item.find('h6 strong').text().toLowerCase();
        
        if (stockName.includes(searchTerm)) {
            $container.fadeIn(300);
            visibleCount++;
        } else {
            $container.fadeOut(300);
        }
    });
    
    updateFilteredCount(visibleCount);
}

function showAllBarcodes() {
    $('.barcode-item-modern').each(function() {
        const $container = $(this).closest('.col-md-6, .col-lg-4');
        $container.fadeIn(300);
    });
    
    const totalCount = $('.barcode-item-modern').length;
    updateFilteredCount(totalCount);
}

function updateFilteredCount(visibleCount) {
    const totalCount = $('.barcode-item-modern').length;
    const $title = $('.card-title-modern');
    const selectedStock = $('#stockFilter').val();
    const selectedStatus = $('#statusFilter').val();
    const searchTerm = $('#stockSearch').val();
    
    if (selectedStock !== '' || selectedStatus !== '' || searchTerm !== '') {
        let filterText = '';
        if (selectedStock !== '') {
            filterText += `"${selectedStock}" stok adı`;
        }
        if (selectedStatus !== '') {
            if (filterText !== '') filterText += ' ve ';
            const statusText = $('#statusFilter option:selected').text();
            filterText += `"${statusText}" durumu ile`;
        }
        if (searchTerm !== '') {
            if (filterText !== '') filterText += ' ve ';
            filterText += `"${searchTerm}" arama terimi`;
        }
        $title.html(`<i class="fas fa-list mr-2"></i>Laboratuvar İşlemleri (${visibleCount}/${totalCount} adet) - <span class="text-primary filter-highlight">${filterText}</span> filtrelendi`);
    } else {
        $title.html(`<i class="fas fa-list mr-2"></i>Laboratuvar İşlemleri (${totalCount} adet)`);
    }
}

function resetCheckboxes() {
    // Tüm checkbox'ları temizle
    $('.barcode-checkbox').prop('checked', false);
    $('#selectAll').prop('checked', false).prop('indeterminate', false);
    
    // Seçili barkodları sıfırla
    selectedBarcodes = [];
    updateSelectedCount();
    updateBulkButtons();
}

function updateSelectedBarcodes() {
    selectedBarcodes = [];
    $('.barcode-checkbox:checked').each(function() {
        selectedBarcodes.push($(this).val());
    });
    
    // Seçili barkodlar değiştiğinde durumlarını yeniden yükle
    if (selectedBarcodes.length > 0) {
        loadBarcodeStatuses();
    }
}

function updateSelectedCount() {
    const count = selectedBarcodes.length;
    $('#selectedCount').text(count);
    
    // Sadece görünür (filtrelenmiş) barkod sayısını göster
    let visibleCount = 0;
    $('.barcode-item-modern').each(function() {
        const $item = $(this);
        const $container = $item.closest('.col-md-6, .col-lg-4');
        
        if ($container.is(':visible')) {
            visibleCount++;
        }
    });
    
    $('#totalCount').text(visibleCount);
}

function updateBulkButtons() {
    const hasSelection = selectedBarcodes.length > 0;
    const $preApprovedBtn = $('#preApprovedBtn');
    const $controlRepeatBtn = $('#controlRepeatBtn');
    const $shipmentApprovedBtn = $('#shipmentApprovedBtn');
    const $rejectBtn = $('#rejectBtn');
    const $selectionWarning = $('#selectionWarning');
    
    // Seçili barkodların durumlarını kontrol et
    let canPreApprove = hasSelection;
    let canControlRepeat = hasSelection;
    let canShipmentApprove = hasSelection;
    let canReject = hasSelection;
    
    if (hasSelection) {
        // Seçili barkodların durumlarını kontrol et
        for (let barcodeId of selectedBarcodes) {
            const status = barcodeStatuses[barcodeId];
            
            // Eğer barkod durumu henüz yüklenmemişse, varsayılan olarak işleme izin ver
            if (status === undefined) {
                continue;
            }
            
            // Beklemede (1) durumundaki barkodlar için
            if (status === 1) {
                // Sadece ön onay, kontrol tekrarı ve red
                canShipmentApprove = false;
            }
            // Ön onaylı (3) durumundaki barkodlar için
            else if (status === 3) {
                // Sadece kontrol tekrarı, sevk onaylı ve red
                canPreApprove = false;
            }
            // Kontrol tekrarı (2) durumundaki barkodlar için
            else if (status === 2) {
                // Sadece ön onay ve red
                canShipmentApprove = false;
                canControlRepeat = false;
            }
            // Sevk onaylı (4) veya reddedildi (5) durumundaki barkodlar için
            else if (status === 4 || status === 5) {
                // Hiçbir işlem yapılamaz
                canPreApprove = false;
                canControlRepeat = false;
                canShipmentApprove = false;
                canReject = false;
            }
        }
    }
    
    $preApprovedBtn.prop('disabled', !canPreApprove);
    $controlRepeatBtn.prop('disabled', !canControlRepeat);
    $shipmentApprovedBtn.prop('disabled', !canShipmentApprove);
    $rejectBtn.prop('disabled', !canReject);

    // Red sebeplerini göster/gizle
    if (canReject && hasSelection) {
        $('#rejectionReasonsSection').show();
    } else {
        $('#rejectionReasonsSection').hide();
    }
    
    if (hasSelection) {
        $preApprovedBtn.attr('title', canPreApprove ? 
            `${selectedBarcodes.length} barkod seçili - Ön onaylı işlemi yapabilirsiniz` : 
            'Seçili barkodlar ön onaylı işlemi için uygun değil');
        $controlRepeatBtn.attr('title', canControlRepeat ? 
            `${selectedBarcodes.length} barkod seçili - Kontrol tekrarı işlemi yapabilirsiniz` : 
            'Seçili barkodlar kontrol tekrarı işlemi için uygun değil');
        $shipmentApprovedBtn.attr('title', canShipmentApprove ? 
            `${selectedBarcodes.length} barkod seçili - Sevk onaylı işlemi yapabilirsiniz` : 
            'Sadece ön onaylı barkodlar sevk onaylı işlemi için uygun');
        $rejectBtn.attr('title', canReject ? 
            `${selectedBarcodes.length} barkod seçili - Red işlemi yapabilirsiniz` : 
            'Seçili barkodlar red işlemi için uygun değil');
        $selectionWarning.hide();
    } else {
        $preApprovedBtn.attr('title', 'Barkod seçmek için yukarıdaki checkbox\'ları işaretleyin');
        $controlRepeatBtn.attr('title', 'Barkod seçmek için yukarıdaki checkbox\'ları işaretleyin');
        $shipmentApprovedBtn.attr('title', 'Sadece ön onaylı barkodlar için kullanılabilir');
        $rejectBtn.attr('title', 'Barkod seçmek için yukarıdaki checkbox\'ları işaretleyin');
        $selectionWarning.show();
    }
}

function loadBarcodeStatuses() {
    // Sadece seçili barkod ID'lerini al
    const barcodeIds = selectedBarcodes.length > 0 ? selectedBarcodes : [];
    
    if (barcodeIds.length > 0) {
        // Barkod durumlarını AJAX ile getir
        $.ajax({
            url: '{{ route("laboratory.barcode-statuses") }}',
            type: 'POST',
            data: {
                barcode_ids: barcodeIds,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Mevcut durumları koru, sadece yeni olanları ekle
                    barcodeStatuses = { ...barcodeStatuses, ...response.statuses };
                    updateBulkButtons();
                }
            }
        });
    }
}

function updateSelectAllState() {
    // Sadece görünür (filtrelenmiş) barkodları say
    let visibleCheckboxes = 0;
    let checkedVisibleCheckboxes = 0;
    
    $('.barcode-item-modern').each(function() {
        const $item = $(this);
        const $container = $item.closest('.col-md-6, .col-lg-4');
        const $checkbox = $item.find('.barcode-checkbox');
        
        // Eğer container görünürse say
        if ($container.is(':visible')) {
            visibleCheckboxes++;
            if ($checkbox.is(':checked')) {
                checkedVisibleCheckboxes++;
                // Card'ı seçili olarak işaretle
                $item.addClass('selected');
            } else {
                // Card'ı seçili olmayan olarak işaretle
                $item.removeClass('selected');
            }
        }
    });
    
    // Görünür barkodların durumuna göre "Tümünü Seç" durumunu güncelle
    if (checkedVisibleCheckboxes === 0) {
        $('#selectAll').prop('indeterminate', false).prop('checked', false);
    } else if (checkedVisibleCheckboxes === visibleCheckboxes) {
        $('#selectAll').prop('indeterminate', false).prop('checked', true);
    } else {
        $('#selectAll').prop('indeterminate', true);
    }
}

function showConfirmation(action) {
    if (selectedBarcodes.length === 0) {
        toastr.warning('Hiçbir barkod seçili değil!', 'Uyarı', {
            timeOut: 3000,
            extendedTimeOut: 1000,
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-center'
        });
        return;
    }
    
    // Red işlemi için red sebebi kontrolü
    if (action === 'reject') {
        const selectedReasons = $('.rejection-reason-checkbox:checked').length;
        if (selectedReasons === 0) {
            toastr.error('Red işlemi için en az bir red sebebi seçmelisiniz!', 'Hata', {
                timeOut: 5000,
                extendedTimeOut: 2000,
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-center'
            });
            return;
        }
    }

    // İşlem kontrolü
    let canProcess = true;
    let errorMessage = '';
    
    for (let barcodeId of selectedBarcodes) {
        const status = barcodeStatuses[barcodeId];
        
        // Beklemede (1) durumundaki barkodlar için
        if (status === 1) {
            if (action === 'shipment_approved') {
                canProcess = false;
                errorMessage = 'Beklemede durumundaki barkodlar direkt sevk onaylı yapılamaz!';
                break;
            }
        }
        // Ön onaylı (3) durumundaki barkodlar için
        else if (status === 3) {
            if (action === 'pre_approved') {
                canProcess = false;
                errorMessage = 'Ön onaylı durumundaki barkodlar tekrar ön onaylı yapılamaz!';
                break;
            }
        }
        // Kontrol tekrarı (2) durumundaki barkodlar için
        else if (status === 2) {
            if (action === 'shipment_approved' || action === 'control_repeat') {
                canProcess = false;
                errorMessage = 'Kontrol tekrarı durumundaki barkodlar direkt sevk onaylı veya tekrar kontrol tekrarı yapılamaz!';
                break;
            }
        }
        // Sevk onaylı (4) veya reddedildi (5) durumundaki barkodlar için
        else if (status === 4 || status === 5) {
            canProcess = false;
            errorMessage = 'Sevk onaylı veya reddedildi durumundaki barkodlar işlenemez!';
            break;
        }
    }
    
    if (!canProcess) {
        toastr.error(errorMessage, 'İşlem Hatası', {
            timeOut: 5000,
            extendedTimeOut: 2000,
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-center'
        });
        return;
    }

    currentAction = action;
    let actionText = '';
    switch(action) {
        case 'pre_approved':
            actionText = 'Ön Onaylı';
            break;
        case 'control_repeat':
            actionText = 'Kontrol Tekrarı';
            break;
        case 'shipment_approved':
            actionText = 'Sevk Onaylı';
            break;
        case 'reject':
            actionText = 'Reddet';
            break;
    }
    const note = $('#bulkNote').val() || 'Toplu işlem notu yok';
    
    // Red sebeplerini al
    let rejectionReasonsText = 'Seçilmedi';
    if (action === 'reject') {
        const selectedReasons = $('.rejection-reason-checkbox:checked').map(function() {
            return $(this).next('label').text();
        }).get();
        rejectionReasonsText = selectedReasons.length > 0 ? selectedReasons.join(', ') : 'Seçilmedi';
    }

    // Seçili barkodların stok bilgilerini topla
    let stockInfo = [];
    $('.barcode-item-modern').each(function() {
        const $item = $(this);
        const $checkbox = $item.find('.barcode-checkbox');
        
        if ($checkbox.is(':checked')) {
            const stockName = $item.find('.stock-info strong').text().trim();
            const loadNumber = $item.find('.stock-info').text().split('Şarj No:')[1].trim();
            stockInfo.push(`${stockName} [${loadNumber}]`);
        }
    });
    
    // Stok bilgilerini göster (benzersiz olanları)
    const uniqueStockInfo = [...new Set(stockInfo)];
    const stockInfoText = uniqueStockInfo.length > 0 ? uniqueStockInfo.join(' - ') : 'Bilgi bulunamadı';
    
    $('#confirmAction').text(actionText);
    $('#confirmCount').text(selectedBarcodes.length);
    $('#confirmStockInfo').text(stockInfoText);
    $('#confirmNote').text(note);
    $('#confirmRejectionReasons').text(rejectionReasonsText);
    
    // Red sebepleri satırını göster/gizle
    if (action === 'reject') {
        $('#rejectionReasonsRow').show();
    } else {
        $('#rejectionReasonsRow').hide();
    }
    
    // Hide result display if visible
    hideResult();
    
    // Show confirmation
    $('#inlineConfirmation').addClass('show');
    
    // Scroll to confirmation
    $('html, body').animate({
        scrollTop: $('#inlineConfirmation').offset().top - 100
    }, 500);
}

function hideConfirmation() {
    $('#inlineConfirmation').removeClass('show');
}

function hideResult() {
    $('#resultDisplay').removeClass('show');
}

$('#confirmProcessBtn').click(function() {
    hideConfirmation();
    
    const note = $('#bulkNote').val();
    
    // Show loading in result display
    $('#resultContent').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">İşleniyor...</span>
            </div>
            <p class="mt-2">Barkodlar işleniyor, lütfen bekleyin...</p>
        </div>
    `);
    $('#resultDisplay').addClass('show');
    
    // Scroll to result
    $('html, body').animate({
        scrollTop: $('#resultDisplay').offset().top - 100
    }, 500);

    // Red sebeplerini al
    const rejectionReasons = $('.rejection-reason-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    // AJAX isteği
    $.ajax({
        url: '{{ route("laboratory.process-bulk") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            barcode_ids: selectedBarcodes,
            action: currentAction,
            note: note,
            rejection_reasons: rejectionReasons
        },
        success: function(response) {
            if (response.success) {
                showSuccessResult(response);
            } else {
                showErrorResult(response);
            }
        },
        error: function() {
            showErrorResult({
                message: 'İşlem sırasında bir hata oluştu!'
            });
        }
    });
});

function showSuccessResult(response) {
    let actionText = '';
    switch(currentAction) {
        case 'pre_approved':
            actionText = 'ön onaylı olarak işaretlendi';
            break;
        case 'control_repeat':
            actionText = 'kontrol tekrarı olarak işaretlendi';
            break;
        case 'shipment_approved':
            actionText = 'sevk onaylı olarak işaretlendi';
            break;
        case 'reject':
            actionText = 'red edildi';
            break;
    }
    
    $('#resultContent').html(`
        <div class="alert alert-success">
            <h6><i class="fas fa-check-circle mr-2"></i>İşlem Başarılı!</h6>
            <p class="mb-0">${response.message}</p>
        </div>
        
        <div class="result-stats">
            <div class="result-stat">
                <h4>${response.processed}</h4>
                <p>Başarıyla İşlenen</p>
            </div>
            <div class="result-stat">
                <h4>${response.errors.length}</h4>
                <p>Hata</p>
            </div>
        </div>
        
        ${response.errors.length > 0 ? `
        <div class="error-list">
            <h6><i class="fas fa-exclamation-triangle mr-1"></i>Hatalar:</h6>
            <ul>
                ${response.errors.map(error => `<li>${error}</li>`).join('')}
            </ul>
        </div>
        ` : ''}
    `);
}

function showErrorResult(response) {
    $('#resultDisplay').addClass('error');
    $('#resultContent').html(`
        <div class="alert alert-danger">
            <h6><i class="fas fa-exclamation-triangle mr-2"></i>İşlem Başarısız!</h6>
            <p class="mb-0">${response.message}</p>
        </div>
    `);
}
</script>
@endsection