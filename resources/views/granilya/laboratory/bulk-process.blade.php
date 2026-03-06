@extends('layouts.granilya')

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
    .page-title-modern i { margin-right: 1rem; font-size: 2rem; }
    .page-subtitle-modern { font-size: 1.1rem; opacity: 0.9; margin-bottom: 0; }
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
    .card-title-modern i { margin-right: 0.5rem; color: #667eea; }
    .card-subtitle-modern { color: #6c757d; margin-bottom: 0; }
    .card-body-modern { padding: 2rem; }
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
    .barcode-item-modern:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .barcode-item-modern.selected { border-color: #007bff; background-color: #f8f9ff; }
    .clickable-card {
        cursor: pointer;
        position: relative;
    }
    .clickable-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .clickable-card .custom-control { pointer-events: none; }
    .clickable-card .custom-control-input { pointer-events: auto; }
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
    .btn-modern:disabled { opacity: 0.6; cursor: not-allowed; transform: none !important; }
    .btn-modern:disabled:hover { transform: none !important; box-shadow: none !important; }
    .btn-modern:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); }
    .btn-success-modern  { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; }
    .btn-danger-modern   { background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); color: white; }
    .btn-info-modern     { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; }
    .btn-primary-modern  { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; }
    .btn-warning-modern  { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: white; }
    .btn-secondary-modern { background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%); color: white; }
    .btn-purple-modern   { background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%); color: white; }
    .form-label { font-weight: 600; color: #495057; margin-bottom: 0.5rem; display: flex; align-items: center; }
    .form-label i { margin-right: 0.5rem; color: #667eea; font-size: 0.9rem; }
    .form-control {
        border: 2px solid #e9ecef; border-radius: 10px;
        padding: 0.75rem 1rem; font-size: 1rem;
        transition: all 0.3s ease; background: #ffffff;
    }
    .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); outline: none; }
    .d-grid { display: grid; }
    .gap-2 { gap: 0.5rem; }
    .gap-3 { gap: 1rem !important; }
    .badge { padding: 0.5rem 0.75rem; font-size: 0.875rem; border-radius: 8px; }
    .badge-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .badge-secondary { background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%); color: white; }
    .fs-6 { font-size: 1rem !important; }
    .flex-fill { flex: 1 1 auto !important; min-width: 140px; }
    .alert { border-radius: 12px; border: none; padding: 1rem 1.5rem; }
    .alert-info  { background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%); color: #0c5460; border: 2px solid #17a2b8; border-radius: 15px; }
    .alert-warning { background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border: 2px solid #ffc107; border-radius: 15px; }

    /* ---- Filter Section ---- */
    .form-label-modern { font-weight: 600; color: #495057; margin-bottom: 0.5rem; display: flex; align-items: center; font-size: 0.95rem; }
    .form-label-modern i { margin-right: 0.5rem; color: #667eea; font-size: 0.9rem; }
    .form-control-modern {
        border: 2px solid #e9ecef; border-radius: 10px;
        padding: 0.75rem 1rem; font-size: 1rem;
        transition: all 0.3s ease; background: #ffffff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        width: 100%;
    }
    .form-control-modern:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); outline: none; }
    .form-control-modern:hover { border-color: #667eea; }
    .filter-section-modern {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px; border: 1px solid #dee2e6;
        overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .filter-header-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 1rem 1.5rem; border-bottom: 1px solid #dee2e6;
    }
    .filter-title-modern {
        color: white; font-size: 1.1rem; font-weight: 600; margin: 0;
        display: flex; align-items: center;
    }
    .filter-title-modern i { margin-right: 0.5rem; font-size: 1rem; }
    .filter-content-modern { padding: 1.5rem; background: white; }
    .filter-actions-modern { display: flex; justify-content: flex-end; align-items: center; height: 100%; padding-top: 1.5rem; }
    .btn-outline-secondary { border: 2px solid #6c757d; color: #6c757d; }
    .btn-outline-secondary:hover { background: #6c757d; color: white; box-shadow: 0 4px 8px rgba(108,117,125,0.3); }
    .btn-clear-filter {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%); color: white;
        min-width: 140px; height: 45px; font-size: 0.9rem; font-weight: 600;
        border-radius: 8px; display: flex; align-items: center; justify-content: center;
        transition: none !important;
    }
    .btn-clear-filter:hover { background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%); color: white; transform: none !important; box-shadow: none !important; }
    .form-group.mb-0 { margin-bottom: 0 !important; }
    .spinner-border-sm { width: 1rem; height: 1rem; }

    /* ---- Rejection Reasons ---- */
    .rejection-reason-block { background: linear-gradient(135deg, #fff3cd, #ffeaa7); border: 2px solid #ffc107; border-radius: 15px; padding: 1.25rem 1.5rem; margin-bottom: 0.75rem; }
    .custom-control-label { font-size: 0.9rem; color: #495057; cursor: pointer; }
    .custom-control-input:checked ~ .custom-control-label { color: #dc3545; font-weight: 600; }

    /* ---- Inline Confirmation ---- */
    .inline-confirmation {
        display: none;
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 2px solid #ffc107; border-radius: 15px;
        padding: 1.5rem; margin: 1rem 0;
        box-shadow: 0 5px 15px rgba(255, 193, 7, 0.2);
    }
    .inline-confirmation.show { display: block; animation: slideDown 0.3s ease-out; }
    .inline-confirmation h5 { color: #856404; margin-bottom: 1rem; display: flex; align-items: center; }
    .inline-confirmation h5 i { margin-right: 0.5rem; }
    .confirmation-details { background: rgba(255,255,255,0.7); border-radius: 10px; padding: 1rem; margin: 1rem 0; }
    .confirmation-details p { margin-bottom: 0.5rem; color: #495057; }
    .confirmation-details strong { color: #856404; }
    .confirmation-actions { display: flex; gap: 1rem; margin-top: 1rem; }
    .confirmation-actions .btn { flex: 1; border-radius: 10px; padding: 0.75rem 1.5rem; font-weight: 600; transition: all 0.3s ease; }
    .confirmation-actions .btn:hover { transform: translateY(-2px); }

    /* ---- Result Display ---- */
    .result-display {
        display: none;
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border: 2px solid #28a745; border-radius: 15px;
        padding: 1.5rem; margin: 1rem 0;
        box-shadow: 0 5px 15px rgba(40,167,69,0.2);
    }
    .result-display.show { display: block; animation: slideDown 0.3s ease-out; }
    .result-display.error { background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); border-color: #dc3545; box-shadow: 0 5px 15px rgba(220,53,69,0.2); }
    .result-display h5 { color: #155724; margin-bottom: 1rem; display: flex; align-items: center; }
    .result-display.error h5 { color: #721c24; }
    .result-display h5 i { margin-right: 0.5rem; }
    .result-stats { display: flex; gap: 1rem; margin: 1rem 0; }
    .result-stat { flex: 1; background: rgba(255,255,255,0.7); border-radius: 10px; padding: 1rem; text-align: center; }
    .result-stat h4 { margin-bottom: 0.5rem; color: #155724; }
    .result-stat p { margin-bottom: 0; color: #495057; font-size: 0.9rem; }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .page-title-modern { font-size: 2rem; }
        .card-body-modern { padding: 1.2rem 1rem; }
        .confirmation-actions { flex-direction: column; }
        .result-stats { flex-direction: column; }
    }

    /* Select2 */
    .select2-container--default .select2-selection--single {
        border: 2px solid #e9ecef; border-radius: 10px;
        height: auto; padding: 0.75rem 1rem; font-size: 1rem;
        transition: all 0.3s ease; background: #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered { color: #495057; padding: 0; line-height: 1.5; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 100%; right: 1rem; }
    .select2-container--default .select2-selection--single .select2-selection__arrow b { border-color: #667eea transparent transparent transparent; border-width: 6px 4px 0 4px; }
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b { border-color: transparent transparent #667eea transparent; border-width: 0 4px 6px 4px; }
    .select2-dropdown { border: 2px solid #667eea; border-radius: 10px; box-shadow: 0 5px 15px rgba(102,126,234,0.2); background: #ffffff; z-index: 9999; }
    .select2-container--default .select2-search--dropdown .select2-search__field { border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 1rem; }
    .select2-container--default .select2-results__option { padding: 0.75rem 1rem; font-size: 1rem; color: #495057; transition: all 0.2s ease; }
    .select2-container--default .select2-results__option--highlighted[aria-selected] { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .select2-container--default .select2-results__option[aria-selected=true] { background: rgba(102,126,234,0.1); color: #667eea; }
    .select2-container { width: 100% !important; }
</style>
@endsection

@section('content')
<div class="modern-lab-bulk-process">
    <div class="container-fluid">

        {{-- ========================== --}}
        {{-- PAGE HEADER --}}
        {{-- ========================== --}}
        <div class="page-header-modern">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title-modern">
                        <i class="fas fa-layer-group"></i> Toplu İşlem
                    </h1>
                    <p class="page-subtitle-modern">Laboratuvar işlemleri için paletleri toplu olarak işleyin</p>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('granilya.laboratory.dashboard') }}" class="btn-modern btn-secondary-modern">
                        <i class="fas fa-arrow-left mr-1"></i> Geri Dön
                    </a>
                </div>
            </div>
        </div>

        {{-- ========================== --}}
        {{-- INFO BOX --}}
        {{-- ========================== --}}
        <div class="info-alert-modern">
            <h5><i class="fas fa-info-circle mr-2"></i>Toplu İşlem Nasıl Çalışır?</h5>
            <ul class="mb-0">
                <li>Aşağıdan işlem yapmak istediğiniz paletleri checkbox ile seçin</li>
                <li>Test türüne göre Onay veya Red butonuna tıklayın</li>
                <li>Red seçilirse zorunlu red sebebini seçin (Elek: Dirilik/Tozama | Yüzey: Renk/Parlaklık)</li>
                <li>İşlemi onaylayın</li>
            </ul>
        </div>

        {{-- ========================== --}}
        {{-- CONTROL PANEL --}}
        {{-- ========================== --}}
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-cogs"></i> Toplu İşlem Kontrol Paneli
                </h3>
                <p class="card-subtitle-modern">Seçili paletleri toplu olarak işlemek için gerekli ayarları yapın</p>
            </div>
            <div class="card-body-modern">

                {{-- Row 1: Select All + Counts --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-clipboard-list"></i> Palet Seçimi
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
                                        <i class="fas fa-list"></i> Toplam: <span id="totalCount">{{ $pendingPallets->count() }}</span>
                                    </span>
                                </div>
                                <div class="mt-2" id="selectionWarning" style="display: none;">
                                    <div class="alert alert-warning py-2 px-3 mb-0">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        İşlem yapmak için en az bir palet seçmelisiniz
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
                                      placeholder="Seçili tüm paletler için işlem notu ekleyin..."></textarea>
                            <small class="form-text text-muted">Bu not tüm seçili paletler için geçerli olacaktır</small>
                        </div>
                    </div>
                </div>

                {{-- Row 2: Rejection Reasons --}}
                <div class="row mb-4" id="sieveRejectionSection" style="display: none;">
                    <div class="col-12">
                        <div class="rejection-reason-block">
                            <label class="form-label mb-3">
                                <i class="fas fa-exclamation-triangle text-danger"></i>
                                Elek Red Sebebi <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex gap-3 mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input sieve-reason-cb" id="reason_dirilik" value="Dirilik">
                                    <label class="custom-control-label" for="reason_dirilik">Dirilik</label>
                                </div>
                                <div class="custom-control custom-checkbox ml-4">
                                    <input type="checkbox" class="custom-control-input sieve-reason-cb" id="reason_tozama" value="Tozama">
                                    <label class="custom-control-label" for="reason_tozama">Tozama</label>
                                </div>
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <button type="button" class="btn btn-danger font-weight-bold"
                                        onclick="showConfirmation('sieve', 'Red')" style="border-radius:10px;">
                                    <i class="fas fa-arrow-right mr-1"></i> Devam Et – Onayla
                                </button>
                                <button type="button" class="btn btn-outline-secondary"
                                        onclick="hideSieveSection()" style="border-radius:10px;">
                                    <i class="fas fa-times mr-1"></i> İptal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Row 3: Rejection Reasons --}}
                <div class="row mb-4" id="surfaceRejectionSection" style="display: none;">
                    <div class="col-12">
                        <div class="rejection-reason-block">
                            <label class="form-label mb-3">
                                <i class="fas fa-exclamation-triangle text-danger"></i>
                                Yüzey Red Sebebi <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex gap-3 mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input surface-reason-cb" id="reason_renk" value="Renk">
                                    <label class="custom-control-label" for="reason_renk">Renk</label>
                                </div>
                                <div class="custom-control custom-checkbox ml-4">
                                    <input type="checkbox" class="custom-control-input surface-reason-cb" id="reason_parlaklik" value="Parlaklık">
                                    <label class="custom-control-label" for="reason_parlaklik">Parlaklık</label>
                                </div>
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <button type="button" class="btn btn-danger font-weight-bold"
                                        onclick="showConfirmation('surface', 'Red')" style="border-radius:10px;">
                                    <i class="fas fa-arrow-right mr-1"></i> Devam Et – Onayla
                                </button>
                                <button type="button" class="btn btn-outline-secondary"
                                        onclick="hideSurfaceSection()" style="border-radius:10px;">
                                    <i class="fas fa-times mr-1"></i> İptal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Row 4: Action Buttons --}}
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label mb-3">
                                <i class="fas fa-play-circle"></i> İşlem Seçenekleri
                            </label>
                            <div class="d-flex flex-wrap gap-3">
                                {{-- Elek --}}
                                <button class="btn-modern btn-success-modern flex-fill"
                                        onclick="showConfirmation('sieve', 'Onay')" disabled id="btn_sieve_onay"
                                        title="Elek testini onaylamak için palet seçin">
                                    <i class="fas fa-check"></i> Elek Onaylı
                                </button>
                                <button class="btn-modern btn-danger-modern flex-fill"
                                        onclick="showRejection('sieve')" disabled id="btn_sieve_red"
                                        title="Elek testini reddetmek için palet seçin">
                                    <i class="fas fa-times"></i> Elek Red
                                </button>
                                {{-- Yüzey --}}
                                <button class="btn-modern btn-info-modern flex-fill"
                                        onclick="showConfirmation('surface', 'Onay')" disabled id="btn_surface_onay"
                                        title="Yüzey testini onaylamak için palet seçin">
                                    <i class="fas fa-check"></i> Yüzey Onaylı
                                </button>
                                <button class="btn-modern btn-danger-modern flex-fill"
                                        onclick="showRejection('surface')" disabled id="btn_surface_red"
                                        title="Yüzey testini reddetmek için palet seçin">
                                    <i class="fas fa-times"></i> Yüzey Red
                                </button>
                                {{-- Arge --}}
                                <button class="btn-modern btn-purple-modern flex-fill"
                                        onclick="showConfirmation('arge', 'Onay')" disabled id="btn_arge_onay"
                                        title="Arge testini onaylamak için palet seçin">
                                    <i class="fas fa-check"></i> Arge Onaylı
                                </button>
                                <button class="btn-modern btn-secondary-modern flex-fill"
                                        onclick="showConfirmation('arge', 'Red')" disabled id="btn_arge_red"
                                        title="Arge testini reddetmek için palet seçin">
                                    <i class="fas fa-times"></i> Arge Red
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Processing Status --}}
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

        {{-- ========================== --}}
        {{-- INLINE CONFIRMATION --}}
        {{-- ========================== --}}
        <div class="inline-confirmation" id="inlineConfirmation">
            <h5><i class="fas fa-exclamation-triangle"></i> İşlem Onayı</h5>
            <div class="confirmation-details">
                <p><strong>Test Türü:</strong> <span id="confirmTestType">-</span></p>
                <p><strong>İşlem:</strong> <span id="confirmAction">-</span></p>
                <p><strong>Seçili Palet Sayısı:</strong> <span id="confirmCount">-</span></p>
                <p id="confirmReasonRow" style="display: none;"><strong>Red Sebebi:</strong> <span id="confirmReasons">-</span></p>
                <p id="confirmNoteRow" style="display: none;"><strong>Not:</strong> <span id="confirmNote">-</span></p>
                <p class="text-muted mb-0"><i class="fas fa-info-circle mr-1"></i>Bu işlem seçili tüm paletleri aynı anda işleyecektir ve geri alınamaz.</p>
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

        {{-- ========================== --}}
        {{-- RESULT DISPLAY --}}
        {{-- ========================== --}}
        <div class="result-display" id="resultDisplay">
            <h5><i class="fas fa-check-circle"></i> İşlem Sonucu</h5>
            <div id="resultContent"></div>
            <div class="confirmation-actions">
                <button type="button" class="btn btn-secondary" onclick="hideResult()">
                    <i class="fas fa-times"></i> Kapat
                </button>
                <button type="button" class="btn btn-primary" onclick="location.reload()">
                    <i class="fas fa-redo"></i> Sayfayı Yenile
                </button>
            </div>
        </div>

        {{-- ========================== --}}
        {{-- PALLET LIST --}}
        {{-- ========================== --}}
        <div class="row">
            <div class="col-12">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">
                            <i class="fas fa-list"></i>Bekleyen Paletler ({{ $pendingPallets->count() }} adet)
                        </h3>
                    </div>
                    <div class="card-body-modern">
                        @if($pendingPallets->count() > 0)

                        {{-- FILTERS --}}
                        <div class="filter-section-modern mb-4">
                            <div class="filter-header-modern">
                                <h5 class="filter-title-modern">
                                    <i class="fas fa-filter"></i>Filtreleme Seçenekleri
                                </h5>
                            </div>
                            <div class="filter-content-modern">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <div class="form-group mb-0">
                                            <label for="stockFilter" class="form-label-modern">
                                                <i class="fas fa-list"></i>Stok
                                            </label>
                                            <select class="form-control-modern select2init" id="stockFilter" multiple="multiple" data-placeholder="Tüm Stoklar">
                                                @foreach($pendingPallets->unique('stock_id') as $p)
                                                    <option value="{{ $p->stock->name ?? '' }}">{{ $p->stock->name ?? '-' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-0">
                                            <label for="loadFilter" class="form-label-modern">
                                                <i class="fas fa-hashtag"></i>Şarj
                                            </label>
                                            <select class="form-control-modern select2init" id="loadFilter" multiple="multiple" data-placeholder="Tüm Şarjlar">
                                                @foreach($pendingPallets->unique('load_number') as $p)
                                                    <option value="{{ $p->load_number }}">{{ $p->load_number }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-0">
                                            <label for="palletFilter" class="form-label-modern">
                                                <i class="fas fa-pallet"></i>Palet No
                                            </label>
                                            <input type="text" class="form-control-modern" id="palletFilter" placeholder="Örn: 1 veya 1,2,5">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label for="statusFilter" class="form-label-modern">
                                                <i class="fas fa-flag"></i>Durum
                                            </label>
                                            <select class="form-control-modern select2init" id="statusFilter" multiple="multiple" data-placeholder="Tüm Durumlar">
                                                @foreach($pendingPallets->unique('status') as $p)
                                                    <option value="{{ $p->status }}">{{ $p->status_label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="filter-actions-modern">
                                            <button type="button" class="btn btn-modern btn-outline-secondary btn-clear-filter px-2" id="clearFilter">
                                                <i class="fas fa-times mr-1"></i>Temizle
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PALLET CARD GRID --}}
                        <div class="row" id="palletGrid">
                            @foreach($pendingPallets as $pallet)
                            <div class="col-md-6 col-lg-4 pallet-col"
                                 data-stock="{{ $pallet->stock->name ?? '' }}"
                                 data-load="{{ $pallet->load_number }}"
                                 data-pallet="{{ $pallet->pallet_number }}"
                                 data-status="{{ $pallet->status }}">
                                <div class="barcode-item-modern clickable-card" data-id="{{ $pallet->id }}">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input pallet-checkbox"
                                               id="pck_{{ $pallet->id }}" value="{{ $pallet->id }}">
                                        <label class="custom-control-label" for="pck_{{ $pallet->id }}"></label>
                                    </div>
                                    <div class="mt-2">
                                        <h6 class="mb-1">
                                            <div class="stock-info mb-2">
                                                <strong>{{ $pallet->pallet_number }}</strong>
                                                <span class="text-muted"> — {{ $pallet->stock->name ?? '-' }}</span>
                                            </div>
                                            <div>
                                                {!! $pallet->status_badge !!}
                                            </div>
                                        </h6>
                                        <p class="mb-1 text-muted small">
                                            <i class="fas fa-hashtag mr-1"></i>Şarj: {{ $pallet->load_number }}
                                        </p>
                                        <p class="mb-1">
                                            <span class="badge badge-info">
                                                <i class="fas fa-weight-hanging mr-1"></i>{{ $pallet->quantity->quantity ?? '-' }} KG
                                            </span>
                                        </p>
                                        <div class="mt-2 small text-muted">
                                            <div>
                                                <i class="fas fa-vial mr-1 text-success"></i>
                                                Elek: <strong class="test-badge-{{ $pallet->sieve_test_result === 'Onay' ? 'success' : ($pallet->sieve_test_result === 'Red' ? 'danger' : 'secondary') }}">{{ $pallet->sieve_test_result ?? 'Bekliyor' }}</strong>
                                            </div>
                                            <div>
                                                <i class="fas fa-layer-group mr-1 text-info"></i>
                                                Yüzey: <strong>{{ $pallet->surface_test_result ?? 'Bekliyor' }}</strong>
                                            </div>
                                            <div>
                                                <i class="fas fa-flask mr-1 text-purple"></i>
                                                Arge: <strong>{{ $pallet->arge_test_result ?? 'Bekliyor' }}</strong>
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            <i class="fas fa-user mr-1"></i>{{ $pallet->user->name ?? '-' }}
                                            &nbsp;|&nbsp;
                                            <i class="fas fa-clock mr-1"></i>{{ $pallet->created_at->format('d.m.Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-muted">Laboratuvar işlemi bekleyen palet bulunmuyor!</h5>
                            <p class="text-muted">Tüm paletler işlenmiş durumda.</p>
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
$(document).ready(function() {

    // ========================
    // Select2 Init
    // ========================
    $('.select2init').select2({ width: '100%', allowClear: true });
    
    // Palet No için enter basıldığında veya input değiştiğinde filtrele
    $('#palletFilter').on('keyup change', function() {
        applyFilters();
    });

    // ========================
    // Selection Logic
    // ========================
    function updateSelectionState() {
        var n = $('.pallet-checkbox:visible:checked').length;
        $('#selectedCount').text(n);
        var disabled = (n === 0);
        $('[id^="btn_"]').prop('disabled', disabled);
    }

    // Click on card = toggle checkbox
    $('.clickable-card').on('click', function(e) {
        if ($(e.target).is('input') || $(e.target).is('label')) return;
        var cb = $(this).find('.pallet-checkbox');
        cb.prop('checked', !cb.prop('checked'));
        $(this).toggleClass('selected', cb.prop('checked'));
        updateSelectionState();
    });

    $('.pallet-checkbox').on('change', function() {
        $(this).closest('.barcode-item-modern').toggleClass('selected', $(this).prop('checked'));
        updateSelectionState();
    });

    $('#selectAll').on('change', function() {
        var checked = $(this).prop('checked');
        $('.pallet-col:visible .pallet-checkbox').prop('checked', checked).each(function() {
            $(this).closest('.barcode-item-modern').toggleClass('selected', checked);
        });
        updateSelectionState();
    });

    updateSelectionState();

    // ========================
    // Filtering
    // ========================
    function applyFilters() {
        var stocks  = $('#stockFilter').val() || [];
        var loads   = $('#loadFilter').val() || [];
        var palletsText = $('#palletFilter').val().trim();
        var pallets = palletsText ? palletsText.split(',').map(s => s.trim().toLowerCase()) : [];
        var statuses = $('#statusFilter').val() || [];

        $('.pallet-col').each(function() {
            var itemStock = String($(this).data('stock'));
            var itemLoad = String($(this).data('load'));
            var itemPallet = String($(this).data('pallet')).toLowerCase();
            var itemStatus = String($(this).data('status'));

            var ms = stocks.length === 0 || stocks.includes(itemStock);
            var ml = loads.length === 0 || loads.includes(itemLoad);
            
            // Palet No Filtre Mantığı (Base Number veya tam eşleşme)
            var mp = true;
            if (pallets.length > 0) {
                mp = false;
                var basePallet = itemPallet.split('-')[0];
                for (var i = 0; i < pallets.length; i++) {
                    var filterVal = pallets[i];
                    if (filterVal.includes('-')) {
                        // Tam eşleşme kontrolü
                        if (filterVal === itemPallet) {
                            mp = true;
                            break;
                        }
                    } else {
                        // Grup (base number) eşleşme kontrolü
                        if (filterVal === basePallet) {
                            mp = true;
                            break;
                        }
                    }
                }
            }
            
            var mst = statuses.length === 0 || statuses.includes(itemStatus);
            $(this).toggle(ms && ml && mp && mst);
        });

        // Uncheck hidden
        $('.pallet-col:hidden .pallet-checkbox').prop('checked', false).each(function() {
            $(this).closest('.barcode-item-modern').removeClass('selected');
        });
        $('#selectAll').prop('checked', false);
        updateSelectionState();
    }

    $('#stockFilter, #loadFilter, #statusFilter').on('change', applyFilters);
    $('#clearFilter').on('click', function() {
        $('#stockFilter, #loadFilter, #statusFilter').val('').trigger('change');
        $('#palletFilter').val('');
        applyFilters();
    });
});

// ========================
// Confirmation Logic
// ========================
var pendingTestType  = null;
var pendingResult    = null;

function showRejection(testType) {
    // Hide other, reset
    $('#sieveRejectionSection, #surfaceRejectionSection').hide();
    $('#inlineConfirmation').removeClass('show');
    $('.sieve-reason-cb, .surface-reason-cb').prop('checked', false);

    if (testType === 'sieve')   $('#sieveRejectionSection').show();
    if (testType === 'surface') $('#surfaceRejectionSection').show();

    pendingTestType = testType;
    pendingResult   = 'Red';
    // Scroll reason section into view
    var $section = testType === 'sieve' ? $('#sieveRejectionSection') : $('#surfaceRejectionSection');
    $('html, body').animate({ scrollTop: $section.offset().top - 80 }, 300);
}

function hideSieveSection() {
    $('#sieveRejectionSection').hide();
    $('.sieve-reason-cb').prop('checked', false);
    pendingTestType = pendingResult = null;
}

function hideSurfaceSection() {
    $('#surfaceRejectionSection').hide();
    $('.surface-reason-cb').prop('checked', false);
    pendingTestType = pendingResult = null;
}

function showConfirmation(testType, result) {
    // Validate reason if Red
    if (result === 'Red') {
        if (testType === 'sieve') {
            var reasons = getCheckedReasons('.sieve-reason-cb');
            if (!reasons.length) { alert('Lütfen elek red sebebi seçiniz (Dirilik veya Tozama).'); return; }
        }
        if (testType === 'surface') {
            var reasons = getCheckedReasons('.surface-reason-cb');
            if (!reasons.length) { alert('Lütfen yüzey red sebebi seçiniz (Renk veya Parlaklık).'); return; }
        }
    }

    var count = $('.pallet-checkbox:visible:checked').length;
    if (count === 0) { alert('Lütfen en az bir palet seçin.'); return; }

    pendingTestType = testType;
    pendingResult   = result;

    var testLabels   = { sieve: 'Elek', surface: 'Yüzey', arge: 'Arge' };
    var resultLabels = { 'Onay': '✅ Onaylı', 'Red': '❌ Red' };
    var reasons;

    $('#confirmTestType').text(testLabels[testType] || testType);
    $('#confirmAction').text(resultLabels[result] || result);
    $('#confirmCount').text(count + ' adet');

    // Reasons
    if (result === 'Red' && testType !== 'arge') {
        reasons = (testType === 'sieve')
            ? getCheckedReasons('.sieve-reason-cb')
            : getCheckedReasons('.surface-reason-cb');
        $('#confirmReasonRow').show();
        $('#confirmReasons').text(reasons.join(', '));
    } else {
        $('#confirmReasonRow').hide();
    }

    var note = $('#bulkNote').val().trim();
    if (note) { $('#confirmNoteRow').show(); $('#confirmNote').text(note); }
    else       { $('#confirmNoteRow').hide(); }

    $('#inlineConfirmation').addClass('show');
    $('#resultDisplay').removeClass('show error');
}

function hideConfirmation() {
    $('#inlineConfirmation').removeClass('show');
}

function hideResult() {
    $('#resultDisplay').removeClass('show error');
}

function getCheckedReasons(selector) {
    var reasons = [];
    $(selector + ':checked').each(function() { reasons.push($(this).val()); });
    return reasons;
}

$('#confirmProcessBtn').on('click', function() {
    var pallets = [];
    $('.pallet-checkbox:visible:checked').each(function() { pallets.push($(this).val()); });
    if (!pallets.length) return;

    var reasons;
    if (pendingResult === 'Red') {
        if (pendingTestType === 'sieve')   reasons = getCheckedReasons('.sieve-reason-cb')[0] || null;
        if (pendingTestType === 'surface') reasons = getCheckedReasons('.surface-reason-cb')[0] || null;
    }

    hideConfirmation();
    $('#processStatus').show();
    $('#statusMessage').text(pallets.length + ' palet işleniyor...');

    $.ajax({
        url: "{{ route('granilya.laboratory.bulk') }}",
        type: 'POST',
        data: {
            _token:        "{{ csrf_token() }}",
            pallets:       pallets,
            test_type:     pendingTestType,
            result:        pendingResult,
            reject_reason: reasons || null
        },
        success: function(res) {
            $('#processStatus').hide();
            var $rd = $('#resultDisplay');
            if (res.success) {
                $rd.removeClass('error').addClass('show');
                $('#resultContent').html(
                    '<div class="result-stats">' +
                    '<div class="result-stat"><h4>' + pallets.length + '</h4><p>İşlenen Palet</p></div>' +
                    '</div>' +
                    '<p class="mb-0 text-success font-weight-bold">' + res.message + '</p>'
                );
            } else {
                $rd.addClass('show error');
                $('#resultContent').html('<p class="text-danger mb-0">' + (res.message || 'Bir hata oluştu.') + '</p>');
            }
        },
        error: function(xhr) {
            $('#processStatus').hide();
            var msg = 'Sunucu hatası.';
            if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
            $('#resultDisplay').addClass('show error');
            $('#resultContent').html('<p class="text-danger mb-0">' + msg + '</p>');
        }
    });
});
</script>
@endsection
