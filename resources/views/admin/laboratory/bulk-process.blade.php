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
        font-size: 1rem;
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
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
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
                <div class="row">
                    <!-- Seçim Kontrolü -->
                    <div class="col-lg-3 col-md-6 mb-3">
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
                            <div class="mt-2">
                                <small class="text-muted">
                                    Seçili: <span class="badge badge-primary" id="selectedCount">0</span> / 
                                    <span class="badge badge-secondary" id="totalCount">{{ $pendingBarcodes->count() }}</span>
                                </small>
                                <div class="mt-1" id="selectionWarning" style="display: none;">
                                    <small class="text-warning">
                                        <i class="fas fa-exclamation-triangle"></i> 
                                        İşlem yapmak için en az bir barkod seçmelisiniz
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- İşlem Notu -->
                    <div class="col-lg-6 col-md-6 mb-3">
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

                    <!-- İşlem Butonları -->
                    <div class="col-lg-3 col-md-12 mb-3">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-play-circle"></i> İşlem Seçenekleri
                            </label>
                            <div class="d-grid gap-2">
                                <button class="btn-modern btn-success-modern" onclick="showConfirmation('pre_approved')" disabled id="preApprovedBtn" 
                                        title="Barkod seçmek için yukarıdaki checkbox'ları işaretleyin">
                                    <i class="fas fa-check"></i> Ön Onaylı
                                </button>
                                <button class="btn-modern btn-info-modern" onclick="showConfirmation('control_repeat')" disabled id="controlRepeatBtn"
                                        title="Barkod seçmek için yukarıdaki checkbox'ları işaretleyin">
                                    <i class="fas fa-redo"></i> Kontrol Tekrarı
                                </button>
                                <button class="btn-modern btn-primary-modern" onclick="showConfirmation('shipment_approved')" disabled id="shipmentApprovedBtn"
                                        title="Sadece ön onaylı barkodlar için kullanılabilir">
                                    <i class="fas fa-shipping-fast"></i> Sevk Onaylı
                                </button>
                                <button class="btn-modern btn-danger-modern" onclick="showConfirmation('reject')" disabled id="rejectBtn"
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
                <p><strong>Not:</strong> <span id="confirmNote"></span></p>
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
                        <div class="row" id="barcodeList">
                            @foreach($pendingBarcodes as $barcode)
                            <div class="col-md-6 col-lg-4">
                                <div class="barcode-item-modern" data-barcode-id="{{ $barcode->id }}">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input barcode-checkbox" 
                                               id="barcode_{{ $barcode->id }}" value="{{ $barcode->id }}">
                                        <label class="custom-control-label" for="barcode_{{ $barcode->id }}"></label>
                                    </div>
                                    <div class="mt-2">
                                        <h6 class="mb-1">
                                            <strong>{{ $barcode->stock->code }}</strong> - {{ $barcode->stock->name }}
                                            <br>
                                            <span class="badge badge-{{ 
                                                $barcode->status == \App\Models\Barcode::STATUS_WAITING ? 'warning' : 
                                                ($barcode->status == \App\Models\Barcode::STATUS_PRE_APPROVED ? 'success' : 
                                                ($barcode->status == \App\Models\Barcode::STATUS_CONTROL_REPEAT ? 'info' : 'secondary')) 
                                            }}">
                                                {{ \App\Models\Barcode::STATUSES[$barcode->status] }}
                                            </span>
                                        </h6>
                                        <p class="mb-1 text-muted">
                                            <i class="fas fa-fire mr-1"></i>{{ $barcode->kiln->name }}
                                        </p>
                                        <p class="mb-1 text-muted">
                                            <i class="fas fa-hashtag mr-1"></i>Şarj: {{ $barcode->load_number }}
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
});

function initializeCheckboxes() {
    // Tümünü seç checkbox'ı
    $('#selectAll').change(function() {
        const isChecked = $(this).is(':checked');
        $('.barcode-checkbox').prop('checked', isChecked);
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
}

function updateSelectedBarcodes() {
    selectedBarcodes = [];
    $('.barcode-checkbox:checked').each(function() {
        selectedBarcodes.push($(this).val());
    });
}

function updateSelectedCount() {
    const count = selectedBarcodes.length;
    $('#selectedCount').text(count);
    $('#totalCount').text($('.barcode-checkbox').length);
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
    // Tüm barkod ID'lerini al
    const barcodeIds = [];
    $('.barcode-checkbox').each(function() {
        barcodeIds.push($(this).val());
    });
    
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
                    barcodeStatuses = response.statuses;
                    updateBulkButtons();
                }
            }
        });
    }
}

function updateSelectAllState() {
    const totalCheckboxes = $('.barcode-checkbox').length;
    const checkedCheckboxes = $('.barcode-checkbox:checked').length;
    
    if (checkedCheckboxes === 0) {
        $('#selectAll').prop('indeterminate', false).prop('checked', false);
    } else if (checkedCheckboxes === totalCheckboxes) {
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

    $('#confirmAction').text(actionText);
    $('#confirmCount').text(selectedBarcodes.length);
    $('#confirmNote').text(note);
    
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

    // AJAX isteği
    $.ajax({
        url: '{{ route("laboratory.process-bulk") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            barcode_ids: selectedBarcodes,
            action: currentAction,
            note: note
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