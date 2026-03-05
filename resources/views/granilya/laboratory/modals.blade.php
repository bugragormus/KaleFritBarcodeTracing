{{-- ================================================================ --}}
{{-- GRANILYA LAB MODALS --}}
{{-- Palet Detay Modal (1:1 Frit barcode detail style) --}}
{{-- ================================================================ --}}

<style>
    /* ---- Custom Modal System ---- */
    .g-modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,0.55); z-index: 1040;
        animation: gFadeIn 0.2s ease;
    }
    .g-modal-overlay.active { display: block; }

    .g-modal-box {
        display: none; position: fixed;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%) scale(0.95);
        z-index: 1050; width: 90%; max-width: 860px;
        max-height: 90vh; overflow-y: auto; opacity: 0;
        transition: all 0.25s ease; border-radius: 20px;
        box-shadow: 0 25px 80px rgba(0,0,0,0.35); background: #fff;
    }
    .g-modal-box.active {
        display: block;
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }
    .g-modal-box.confirm-box { max-width: 460px; z-index: 1060; }
    .g-modal-overlay.confirm-overlay { z-index: 1055; }

    @keyframes gFadeIn { from { opacity: 0; } to { opacity: 1; } }

    /* ---- Modal Header ---- */
    .g-modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px 20px 0 0; padding: 1.75rem 2rem;
    }

    /* ---- Info Grid ---- */
    .info-block { margin-bottom: 1.25rem; }
    .info-block h6 {
        font-size: 0.75rem; font-weight: 700; color: #6c757d;
        text-transform: uppercase; letter-spacing: 0.05em;
        margin-bottom: 0.3rem; display: flex; align-items: center; gap: 0.4rem;
    }
    .info-block .info-value { font-size: 1rem; font-weight: 600; color: #2d3748; }

    /* ---- Test Cards ---- */
    .test-card {
        border-radius: 14px; padding: 1.25rem; border: 2px solid #e9ecef;
        background: #f8f9fa; transition: all 0.2s;
    }
    .test-card.approved { background: #d4edda; border-color: #28a745; }
    .test-card.rejected { background: #f8d7da; border-color: #dc3545; }
    .test-card h6 { margin-bottom: 0.5rem; font-weight: 700; font-size: 0.9rem; }
    .test-card .test-result-badge { margin-bottom: 0.75rem; }
    .test-card .reason-tag {
        font-size: 0.78rem; color: #dc3545; font-weight: 600;
        background: rgba(220,53,69,0.1); border-radius: 6px; padding: 0.2rem 0.5rem;
        display: inline-block; margin-bottom: 0.5rem;
    }

    /* ---- Status Badge Styling ---- */
    #mStatusBadge .badge, #mDetailStatus .badge {
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: 800;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        border: 2px solid rgba(255,255,255,0.2);
        display: inline-block;
        text-transform: uppercase;
        font-size: 0.75rem;
    }

    /* ---- Reason selector in confirm box ---- */
    .reason-btn-group { display: flex; gap: 0.5rem; margin: 0.75rem 0; }
    .reason-select-btn {
        flex: 1; border: 2px solid #dee2e6; border-radius: 10px;
        padding: 0.6rem 1rem; font-weight: 600; background: white;
        cursor: pointer; transition: all 0.2s; text-align: center; color: #495057;
    }
    .reason-select-btn.active { border-color: #dc3545; background: #dc3545; color: white; }
    .reason-select-btn:hover:not(.active) { border-color: #dc3545; color: #dc3545; }

    /* ---- Section divider ---- */
    .section-divider { border: none; border-top: 2px solid #e9ecef; margin: 1.5rem 0; }
</style>

{{-- ================================================================ --}}
{{-- OVERLAY: Pallet Detail --}}
{{-- ================================================================ --}}
<div class="g-modal-overlay" id="palletOverlay"></div>
<div class="g-modal-box" id="palletDetailBox">

    {{-- Header --}}
    <div class="g-modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px 20px 0 0; padding: 2rem; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        <div class="d-flex justify-content-between align-items-center position-relative">
            <div>
                <h5 class="text-white font-weight-bold mb-1" style="font-size:1.6rem; letter-spacing: -0.5px;">
                    <i class="fas fa-pallet mr-2" style="opacity: 0.8;"></i>
                    Palet: <span id="mPalletNumber" style="text-decoration: underline; text-underline-offset: 4px;">-</span>
                </h5>
                <p class="text-white mb-0 font-weight-500" style="opacity:0.9; font-size: 0.95rem;">
                    <span id="mStockName">-</span>
                    &nbsp;|&nbsp;Şarj: <span id="mLoadNumber">-</span>
                    &nbsp;|&nbsp;<span id="mQuantity">-</span>
                </p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div id="mStatusBadge"></div>
                <button onclick="closeModal('pallet')" class="btn"
                        style="background:rgba(255,255,255,0.15); color:white; border-radius:12px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.2); transition: all 0.2s;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Body --}}
    <div class="p-4" style="background: #fcfcfd;">

        {{-- Info Grid --}}
        <div class="row mb-4">
            <div class="col-md-7">
                <div class="p-4 bg-white shadow-sm border" style="border-radius: 20px;">
                    <h6 class="font-weight-bold text-dark mb-4 d-flex align-items-center" style="font-size: 0.95rem;">
                        <i class="fas fa-info-circle mr-2 text-primary"></i> Genel Bilgiler
                    </h6>
                    <div class="row">
                        <div class="col-6 mb-4">
                            <div class="info-block">
                                <h6 style="color: #adb5bd; font-size: 0.7rem;"><i class="fas fa-barcode"></i> PALET NO</h6>
                                <div class="info-value" id="mDetailPallet" style="color: #2d3748; font-weight: 700;">-</div>
                            </div>
                        </div>
                        <div class="col-6 mb-4">
                            <div class="info-block">
                                <h6 style="color: #adb5bd; font-size: 0.7rem;"><i class="fas fa-hashtag"></i> ŞARJ NO</h6>
                                <div class="info-value" id="mDetailLoad" style="color: #2d3748; font-weight: 700;">-</div>
                            </div>
                        </div>
                        <div class="col-6 mb-4">
                            <div class="info-block">
                                <h6 style="color: #adb5bd; font-size: 0.7rem;"><i class="fas fa-weight-hanging"></i> MİKTAR</h6>
                                <div class="info-value" id="mDetailQty" style="color: #2d3748; font-weight: 700;">-</div>
                            </div>
                        </div>
                        <div class="col-6 mb-4">
                            <div class="info-block">
                                <h6 style="color: #adb5bd; font-size: 0.7rem;"><i class="fas fa-calendar-day"></i> ÜRETİM TARİHİ</h6>
                                <div class="info-value" id="mDetailDate" style="color: #2d3748; font-weight: 700;">-</div>
                            </div>
                        </div>
                        <div class="col-12 mb-0">
                            <div class="info-block">
                                <h6 style="color: #adb5bd; font-size: 0.7rem;"><i class="fas fa-tags"></i> STOK TANIMI</h6>
                                <div class="info-value" id="mDetailStock" style="color: #2d3748; font-weight: 700;">-</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="p-4 h-100 d-flex flex-column justify-content-center bg-white shadow-sm border text-center" style="border-radius: 20px;">
                    <h6 style="color: #adb5bd; font-size: 0.75rem; letter-spacing: 1px; margin-bottom: 1rem;">GÜNCEL DURUM</h6>
                    <div id="mDetailStatus" style="margin-bottom: 1.5rem;">-</div>
                    <div id="exceptionalArea" style="display:none;">
                        <button class="btn btn-warning w-100 py-3 font-weight-bold" onclick="exceptionalApprove()"
                                style="border-radius: 12px; box-shadow: 0 4px 12px rgba(255,193,7,0.3); border: none; background: linear-gradient(135deg, #ffc107, #e0a800); color: white;">
                            <i class="fas fa-shield-alt mr-2"></i> İSTİSNAİ ONAY VER
                        </button>
                        <p class="small text-muted mt-2">Bu palet reddedilmiş fakat istisnai onayla sevk edilebilir.</p>
                    </div>
                    <div id="terminalArea" style="display:none;">
                        <div class="p-3 bg-light rounded text-success font-weight-bold" style="border: 1px dashed #28a745;">
                            <i class="fas fa-check-circle mr-1"></i> Süreç Tamamlandı
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Test Cards --}}
        <div class="mt-5">
            <h6 class="font-weight-bold mb-4 d-flex align-items-center" style="font-size: 0.95rem; color: #2d3748;">
                <i class="fas fa-microscope mr-2 text-primary"></i> KALİTE KONTROL TESTLERİ
            </h6>
            <div class="row">
                {{-- Elek --}}
                <div class="col-md-4 mb-4">
                    <div class="test-card shadow-sm" id="tcSieve" style="border-radius: 20px; border-width: 2px;">
                        <h6 class="mb-3"><i class="fas fa-filter mr-2 text-secondary" style="font-size: 0.8rem;"></i> 1. ELEK TESTİ</h6>
                        <div class="test-result-badge mb-3" id="trSieve">
                            <span class="badge badge-secondary px-3 py-2">Bekliyor</span>
                        </div>
                        <div id="trSieveReason"></div>
                        <div class="btn-group w-100" id="btnGroupSieve" style="border-radius: 12px; overflow: hidden;">
                            <button class="btn btn-success py-2 font-weight-bold" onclick="triggerTest('sieve', 'Onay')" style="font-size: 0.85rem;">ONAY</button>
                            <button class="btn btn-danger py-2 font-weight-bold" onclick="triggerTest('sieve', 'Red')" style="font-size: 0.85rem;">RED</button>
                        </div>
                    </div>
                </div>
                {{-- Yüzey --}}
                <div class="col-md-4 mb-4">
                    <div class="test-card shadow-sm" id="tcSurface" style="border-radius: 20px; border-width: 2px;">
                        <h6 class="mb-3"><i class="fas fa-layer-group mr-2 text-secondary" style="font-size: 0.8rem;"></i> 2. YÜZEY TESTİ</h6>
                        <div class="test-result-badge mb-3" id="trSurface">
                            <span class="badge badge-secondary px-3 py-2">Bekliyor</span>
                        </div>
                        <div id="trSurfaceReason"></div>
                        <div class="btn-group w-100" id="btnGroupSurface" style="border-radius: 12px; overflow: hidden;">
                            <button class="btn btn-success py-2 font-weight-bold" onclick="triggerTest('surface', 'Onay')" style="font-size: 0.85rem;">ONAY</button>
                            <button class="btn btn-danger py-2 font-weight-bold" onclick="triggerTest('surface', 'Red')" style="font-size: 0.85rem;">RED</button>
                        </div>
                    </div>
                </div>
                {{-- Arge --}}
                <div class="col-md-4 mb-4">
                    <div class="test-card shadow-sm" id="tcArge" style="border-radius: 20px; border-width: 2px;">
                        <h6 class="mb-3"><i class="fas fa-vial mr-2 text-secondary" style="font-size: 0.8rem;"></i> 3. ARGE TESTİ</h6>
                        <div class="test-result-badge mb-3" id="trArge">
                            <span class="badge badge-secondary px-3 py-2">Bekliyor</span>
                        </div>
                        <div id="trArgeReason"></div>
                        <div class="btn-group w-100" id="btnGroupArge" style="border-radius: 12px; overflow: hidden;">
                            <button class="btn btn-success py-2 font-weight-bold" onclick="triggerTest('arge', 'Onay')" style="font-size: 0.85rem;">ONAY</button>
                            <button class="btn btn-danger py-2 font-weight-bold" onclick="triggerTest('arge', 'Red')" style="font-size: 0.85rem;">RED</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


    </div>
</div>

{{-- ================================================================ --}}
{{-- CONFIRM MODAL: Red reason picker --}}
{{-- ================================================================ --}}
<div class="g-modal-overlay confirm-overlay" id="confirmOverlay"></div>
<div class="g-modal-box confirm-box" id="confirmBox">
    <div class="g-modal-header" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 20px 20px 0 0;">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="font-weight-bold mb-0 text-dark" id="confirmBoxTitle">Test Güncelle</h6>
            <button onclick="closeModal('confirm')" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="p-4">
        <p id="confirmBoxText" class="text-muted mb-3"></p>

        <div id="sieveReasonPick" style="display:none;">
            <p class="small font-weight-bold text-danger mb-2">Red Sebebi Seçin <span>*</span></p>
            <div class="reason-btn-group">
                <div class="reason-select-btn" onclick="pickReason(this, 'Dirilik')">Dirilik</div>
                <div class="reason-select-btn" onclick="pickReason(this, 'Tozama')">Tozama</div>
            </div>
        </div>

        <div id="surfaceReasonPick" style="display:none;">
            <p class="small font-weight-bold text-danger mb-2">Red Sebebi Seçin <span>*</span></p>
            <div class="reason-btn-group">
                <div class="reason-select-btn" onclick="pickReason(this, 'Renk')">Renk</div>
                <div class="reason-select-btn" onclick="pickReason(this, 'Parlaklık')">Parlaklık</div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-3">
            <button class="btn btn-secondary flex-fill" onclick="closeModal('confirm')" style="border-radius:10px;">
                <i class="fas fa-arrow-left mr-1"></i> Geri
            </button>
            <button class="btn btn-primary flex-fill font-weight-bold" id="doTestBtn"
                    onclick="submitTest()" style="border-radius:10px;">
                <i class="fas fa-check mr-1"></i> Kaydet
            </button>
        </div>
    </div>
</div>

<script>
// ============================================================
// STATE
// ============================================================
var gCurrentPalletId = null;
var gTestType  = null;
var gTestResult = null;
var gReason    = null;

// ============================================================
// OPEN PALLET DETAIL
// ============================================================
function openPalletDetail(id) {
    gCurrentPalletId = id;
    $.ajax({
        url: "{{ route('granilya.laboratory.barcode-detail', ':id') }}".replace(':id', id),
        type: 'GET',
        success: function(res) {
            if (!res.success) { alert('Veri alınamadı.'); return; }
            populatePalletModal(res);
            openModal('pallet');
        },
        error: function() { alert('Sunucu hatası.'); }
    });
}

function populatePalletModal(res) {
    var p = res.pallet;
    // Header
    $('#mPalletNumber').text(p.pallet_number || '-');
    $('#mStockName').text(p.stock ? p.stock.name : '-');
    $('#mLoadNumber').text(p.load_number || '-');
    $('#mQuantity').text(p.used_quantity ? p.used_quantity + ' KG' : '-');
    $('#mStatusBadge').html(res.status_badge || '');

    // Detail grid
    $('#mDetailPallet').text(p.pallet_number || '-');
    $('#mDetailLoad').text(p.load_number || '-');
    $('#mDetailQty').text(p.used_quantity ? p.used_quantity + ' KG' : '-');
    $('#mDetailDate').text(p.created_at ? p.created_at.substring(0,10).split('-').reverse().join('.') : '-');
    $('#mDetailStock').text(p.stock ? p.stock.name : '-');
    $('#mDetailStatus').html(res.status_badge || '');

    // Area Visibility
    var isExceptionalPossible = (p.status == 5 && p.sieve_test_result != 'Red' && p.surface_test_result != 'Red' && p.arge_test_result == 'Red');
    $('#exceptionalArea').toggle(isExceptionalPossible); 
    $('#terminalArea').toggle(p.status == 4 || p.status == 12); // 4 = Shipment Approved, 12 = Exceptional

    // Test cards state
    // STATUS_WAITING = 1, STATUS_PRE_APPROVED = 3, STATUS_REJECTED = 5, ...
    var terminal = (p.status == 4 || p.status == 12);
    
    // Enable/Disable buttons based on status
    if (terminal) {
        $('.btn-group').hide();
    } else if (p.status == 5) {
        $('.btn-group').hide();
    } else {
        $('.btn-group').show();
        // Waiting state: only sieve and surface enabled. Arge should wait for pre-approved.
        if (p.status == 1) {
            $('#btnGroupArge').css('opacity', '0.5').css('pointer-events', 'none');
            $('#btnGroupSieve, #btnGroupSurface').css('opacity', '1').css('pointer-events', 'auto');
        } else if (p.status == 3) {
            $('#btnGroupArge').css('opacity', '1').css('pointer-events', 'auto');
            // Once pre-approved, sieve and surface are done
            $('#btnGroupSieve, #btnGroupSurface').css('opacity', '0.5').css('pointer-events', 'none');
        }
    }

    setTestCard('Sieve',   p.sieve_test_result,   p.sieve_reject_reason);
    setTestCard('Surface', p.surface_test_result, p.surface_reject_reason);
    setTestCard('Arge',    p.arge_test_result,     null);
}

function setTestCard(key, result, reason) {
    var $card   = $('#tc' + key);
    var $badge  = $('#tr' + key);
    var $reason = $('#tr' + key + 'Reason');

    $card.removeClass('approved rejected');
    if (result === 'Onay') {
        $badge.html('<span class="status-badge" style="background:#e6fcf5; color:#099268; font-size:10px;">ONAYLANDI</span>');
        $card.addClass('approved');
    } else if (result === 'Red') {
        $badge.html('<span class="status-badge" style="background:#fff5f5; color:#fa5252; font-size:10px;">REDDEDİLDİ</span>');
        $card.addClass('rejected');
    } else {
        $badge.html('<span class="status-badge" style="background:#f8f9fa; color:#adb5bd; font-size:10px;">BEKLİYOR</span>');
    }
    
    if (reason) {
        $reason.html('<div class="small text-danger font-weight-bold mb-2"><i class="fas fa-exclamation-circle mr-1"></i>' + reason + '</div>');
    } else {
        $reason.html('');
    }
}

function exceptionalApprove() {
    swal({
        title: "İstisnai Onay Ver",
        text: "Bu paleti reddedilmiş olmasına rağmen istisnai olarak onaylamak istediğinize emin misiniz?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Evet, Onayla",
        cancelButtonText: "Vazgeç"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: "{{ route('granilya.laboratory.exceptional.approve', ':id') }}".replace(':id', gCurrentPalletId),
                type: 'POST',
                data: { _token: "{{ csrf_token() }}" },
                success: function(res) {
                    if (res.success) {
                        swal("Başarılı!", res.message, "success");
                        populatePalletModal(res);
                        if (window.table) window.table.ajax.reload(null, false);
                    } else {
                        swal("Hata!", res.message, "error");
                    }
                },
                error: function() { swal("Hata!", "İşlem sırasında bir hata oluştu.", "error"); }
            });
        }
    });
}

function triggerTest(testType, result) {
    gTestType   = testType;
    gTestResult = result;
    gReason     = null;

    var typeLabels = { sieve: 'Elek', surface: 'Yüzey', arge: 'Arge' };
    var label = typeLabels[testType] || testType;

    $('#sieveReasonPick, #surfaceReasonPick').hide();
    $('.reason-select-btn').removeClass('active');

    if (result === 'Red') {
        $('#confirmBoxTitle').text(label + ' Testi — Red Kararı');
        if (testType === 'sieve') {
            $('#confirmBoxText').text('Elek testi için red sebebini seçin:');
            $('#sieveReasonPick').show();
        } else if (testType === 'surface') {
            $('#confirmBoxText').text('Yüzey testi için red sebebini seçin:');
            $('#surfaceReasonPick').show();
        } else {
            $('#confirmBoxText').text('Arge testini reddetmek istediğinize emin misiniz?');
        }
    } else {
        $('#confirmBoxTitle').text(label + ' Testi — Onay Kararı');
        $('#confirmBoxText').text(label + ' testini onaylamak istediğinize emin misiniz?');
    }

    openModal('confirm');
}

function pickReason(el, value) {
    $(el).closest('.reason-btn-group').find('.reason-select-btn').removeClass('active');
    $(el).addClass('active');
    gReason = value;
}

function submitTest() {
    if (gTestResult === 'Red' && gTestType !== 'arge' && !gReason) {
        alert('Lütfen red sebebi seçiniz.');
        return;
    }

    var $btn = $('#doTestBtn');
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Kaydediliyor...');

    $.ajax({
        url: "{{ route('granilya.laboratory.test.update', ':id') }}".replace(':id', gCurrentPalletId),
        type: 'POST',
        data: {
            _token:        "{{ csrf_token() }}",
            test_type:     gTestType,
            result:        gTestResult,
            reject_reason: gReason || null
        },
        success: function(res) {
            closeModal('confirm');
            if (res.success) {
                populatePalletModal(res);
                if (window.table) window.table.ajax.reload(null, false);
            } else {
                alert(res.message || 'Güncelleme başarısız.');
            }
        },
        error: function(xhr) {
            var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Sunucu hatası.';
            alert(msg);
        },
        complete: function() {
            $btn.prop('disabled', false).html('<i class="fas fa-check mr-1"></i> Kaydet');
        }
    });
}

function openModal(which) {
    if (which === 'pallet') {
        $('#palletOverlay').addClass('active');
        setTimeout(function() { $('#palletDetailBox').addClass('active'); }, 10);
        $('body').css('overflow', 'hidden');
    } else {
        $('#confirmOverlay').addClass('active');
        setTimeout(function() { $('#confirmBox').addClass('active'); }, 10);
    }
}

function closeModal(which) {
    if (which === 'pallet') {
        $('#palletDetailBox').removeClass('active');
        $('#palletOverlay').removeClass('active');
        $('body').css('overflow', '');
    } else {
        $('#confirmBox').removeClass('active');
        $('#confirmOverlay').removeClass('active');
    }
}

$('#palletOverlay').on('click', function() { closeModal('pallet'); });
$('#confirmOverlay').on('click', function() { closeModal('confirm'); });

$(document).on('keydown', function(e) {
    if (e.key === 'Escape') {
        if ($('#confirmBox').hasClass('active')) closeModal('confirm');
        else if ($('#palletDetailBox').hasClass('active')) closeModal('pallet');
    }
});
</script>
