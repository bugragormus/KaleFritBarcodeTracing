@extends('layouts.granilya')

@section('styles')
<style>
    body, .main-content, .modern-pallet-detail { background: #f8f9fa !important; }
    .modern-pallet-detail { padding: 2rem 0; }

    /* ---- Header ---- */
    .page-header-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px; padding: 2.5rem; margin-bottom: 2.5rem;
        color: white; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        position: relative; overflow: hidden;
    }
    .page-header-modern::after {
        content: ''; position: absolute; top: -50%; right: -10%;
        width: 400px; height: 400px; background: rgba(255, 255, 255, 0.1);
        border-radius: 50%; pointer-events: none;
    }
    .page-title-modern { font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem; display: flex; align-items: center; letter-spacing: -0.5px; }
    .page-title-modern i { margin-right: 1.25rem; font-size: 2.25rem; }
    .page-subtitle-modern { font-size: 1.15rem; opacity: 0.9; margin-bottom: 0; font-weight: 500; }

    /* ---- Cards ---- */
    .card-modern {
        background: #ffffff; border-radius: 25px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(233, 236, 239, 0.5); overflow: hidden; margin-bottom: 2.5rem;
    }
    .card-header-modern {
        background: #ffffff; padding: 1.5rem 2.5rem; border-bottom: 1px solid #f1f3f5;
        display: flex; align-items: center; justify-content: space-between;
    }
    .card-title-modern { font-size: 1.25rem; font-weight: 700; color: #212529; margin: 0; display: flex; align-items: center; }
    .card-title-modern i { margin-right: 0.75rem; color: #667eea; }
    .card-body-modern { padding: 2.5rem; }

    /* ---- Status Badges ---- */
    .status-badge {
        padding: 8px 16px; border-radius: 12px; font-size: 11px; font-weight: 800;
        text-transform: uppercase; letter-spacing: 0.5px; display: inline-block;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .status-waiting { background: #fff4e6; color: #fd7e14; }
    .status-pre-approved { background: #e6fcf5; color: #099268; }
    .status-shipment-approved { background: #e7f5ff; color: #1c7ed6; }
    .status-rejected { background: #fff5f5; color: #fa5252; }
    .status-exceptional { background: #f3f0ff; color: #7950f2; }

    /* ---- Info Grid ---- */
    .info-section-title { font-size: 0.85rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 1.5rem; display: flex; align-items: center; }
    .info-section-title i { margin-right: 0.5rem; color: #667eea; }
    .info-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; }
    @media (max-width: 768px) { .info-row { grid-template-columns: 1fr; } }
    .info-block { border-left: 3px solid #eef2f7; padding-left: 1rem; }
    .info-block .info-label { font-size: 0.7rem; font-weight: 700; color: #adb5bd; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.4rem; }
    .info-block .info-value { font-size: 1.05rem; font-weight: 700; color: #495057; }

    /* ---- Test Cards ---- */
    .test-card-modern {
        border-radius: 18px; padding: 1.5rem; border: 2px solid #f1f3f5;
        background: #fcfcfd; transition: all 0.3s ease; position: relative;
        overflow: hidden; height: 100%;
    }
    .test-card-modern.approved { border-color: #099268; background: #f0fff4; }
    .test-card-modern.rejected { border-color: #fa5252; background: #fff5f5; }
    .test-card-modern h6 { font-weight: 800; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: #adb5bd; margin-bottom: 1rem; }
    .test-card-modern.approved h6 { color: #099268; }
    .test-card-modern.rejected h6 { color: #fa5252; }

    /* ---- Form ---- */
    .form-group label { font-weight: 700; color: #495057; margin-bottom: 0.75rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }
    .form-control, .custom-select {
        border: 2px solid #eef2f7; border-radius: 12px; 
        padding: 0.5rem 1rem;
        font-size: 0.95rem; font-weight: 600; transition: all 0.3s; background: #ffffff;
        color: #495057;
        height: 50px !important;
        line-height: 1.5;
    }
    .form-control:focus, .custom-select:focus { border-color: #667eea; box-shadow: 0 0 0 4px rgba(102,126,234,0.1); outline: none; }
    .form-control:disabled, .custom-select:disabled { background: #f8f9fa; opacity: 0.7; }

    /* ---- Buttons ---- */
    .btn-modern {
        border-radius: 12px; padding: 0.85rem 1.75rem; font-weight: 700;
        transition: all 0.3s; border: none; display: inline-flex;
        align-items: center; gap: 0.75rem; font-size: 0.95rem; cursor: pointer;
    }
    .btn-primary-modern  { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; box-shadow: 0 4px 15px rgba(102,126,234,0.3); }
    .btn-secondary-modern { background: #f8f9fa; color: #495057; border: 2px solid #eef2f7; }
    .btn-save-modern { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; box-shadow: 0 4px 15px rgba(40,167,69,0.3); }
    
    .btn-modern:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); }
    .btn-primary-modern:hover { background: linear-gradient(135deg, #764ba2 0%, #667eea 100%); color: white; }
    .btn-secondary-modern:hover { background: #eef2f7; color: #212529; }
</style>
@endsection

@section('content')
<div class="modern-pallet-detail">
    <div class="container-fluid">

        {{-- PAGE HEADER --}}
        <div class="page-header-modern">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title-modern">
                        <i class="fas fa-pallet"></i> {{ $pallet->pallet_number }}
                        @if($pallet->is_correction)
                            <span class="badge badge-info ml-3" style="background-color: #f59f00; border-radius: 8px; padding: 6px 12px; font-size: 0.9rem; align-self: center;">
                                <i class="fas fa-tools"></i> Düzeltme Ürünü
                            </span>
                        @endif
                    </h1>
                    <p class="page-subtitle-modern">
                        {{ $pallet->stock->name ?? '-' }} &nbsp;|&nbsp; Şarj No: {{ $pallet->load_number }}
                        @if($pallet->is_correction && $pallet->correctionSource)
                            <br>
                            <small style="color: rgba(255,255,255,0.8); font-size: 0.85rem;">
                                <i class="fas fa-level-up-alt fa-rotate-90"></i> Kaynak Palet: <a href="{{ route('granilya.production.show', $pallet->correction_source_id) }}" style="color: white; text-decoration: underline;">{{ $pallet->correctionSource->pallet_number }}</a>'dan geri kazanıldı.
                            </small>
                        @endif
                        @if($pallet->is_correction && $pallet->triggerProduction)
                            <br>
                            <small style="color: rgba(255,255,255,0.8); font-size: 0.85rem;">
                                <i class="fas fa-link"></i> Tetikleyen Üretim: <a href="{{ route('granilya.production.show', $pallet->trigger_production_id) }}" style="color: white; text-decoration: underline;">{{ $pallet->triggerProduction->pallet_number }}</a>
                            </small>
                        @endif
                    </p>
                    @if($pallet->is_exceptionally_approved)
                    <div class="mt-2">
                        <span class="badge badge-warning" style="background: linear-gradient(135deg, #ffc107, #e0a800); color: #212529; padding: 6px 12px; font-size: 13px; font-weight: 700; border-radius: 20px;">
                            <i class="fas fa-exclamation-triangle"></i> İstisnai onay ile redden sevke döndü
                        </span>
                    </div>
                    @endif
                </div>
                <div class="col-md-4 text-md-right mt-3 mt-md-0">
                    <div class="d-flex justify-content-md-end gap-3">
                        <a href="{{ route('granilya.production.history', $pallet->id) }}" class="btn-modern btn-primary-modern">
                            <i class="fas fa-history"></i> Hareketler
                        </a>
                        <a href="{{ route('granilya.stock.index') }}" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i> Geri Dön
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius:15px;">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if($pallet->triggeredCorrections->count() > 0)
            <div class="alert alert-info border-0 shadow-sm mb-4" style="border-radius:15px; background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%); color: #00695c;">
                <h6 class="font-weight-bold mb-2"><i class="fas fa-tools mr-2"></i> Bu Üretim Sırasındaki Düzeltme Faaliyetleri</h6>
                <p class="mb-2 small text-muted">Aşağıdaki paletler, bu paletin üretimi sırasında düzeltme faaliyeti olarak sisteme geri kazandırılmıştır:</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($pallet->triggeredCorrections as $corr)
                        <a href="{{ route('granilya.production.show', $corr->id) }}" class="badge badge-light p-2 shadow-sm" style="border-radius: 8px; border: 1px solid #004d40; color: #004d40;">
                            <i class="fas fa-pallet mr-1"></i> {{ $corr->pallet_number }} ({{ number_format($corr->used_quantity, 0) }} KG)
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius:15px;">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- EDIT FORM (TOP) --}}
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern"><i class="fas fa-edit"></i> Palet Düzenleme</h3>
                <div class="text-right">
                    {!! $pallet->status_badge !!}
                    @if(!in_array($pallet->status, [\App\Models\GranilyaProduction::STATUS_REJECTED, \App\Models\GranilyaProduction::STATUS_CUSTOMER_TRANSFER, \App\Models\GranilyaProduction::STATUS_SHIPPED]))
                        @php
                            $baseNum = $pallet->base_pallet_number;
                            $grpWeight = \App\Models\GranilyaProduction::where('pallet_number', 'LIKE', $baseNum . '-%')
                                ->whereNotIn('status', [\App\Models\GranilyaProduction::STATUS_REJECTED, \App\Models\GranilyaProduction::STATUS_CORRECTED])
                                ->sum('used_quantity');
                        @endphp
                        @if($grpWeight < 1000)
                            <div class="mt-2 text-muted" style="font-size: 11px; font-weight: 700;">
                                <i class="fas fa-layer-group"></i> {{ round($grpWeight) }}/1000 KG
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            <div class="card-body-modern">
                <form action="{{ route('granilya.production.update', $pallet->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Palet Numarası</label>
                                <input type="text" name="pallet_number" class="form-control" value="{{ $pallet->pallet_number }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Müşteri Tipi</label>
                                <select name="customer_type" class="custom-select" required>
                                    <option value="İç Müşteri" {{ $pallet->customer_type == 'İç Müşteri' ? 'selected' : '' }}>İç Müşteri</option>
                                    <option value="Dış Müşteri" {{ $pallet->customer_type == 'Dış Müşteri' ? 'selected' : '' }}>Dış Müşteri</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Durum</label>
                                <select name="status" id="statusSelect" class="custom-select">
                                    @foreach($pallet->getAvailableStatuses() as $key => $value)
                                        <option value="{{ $key }}" {{ old('status', $pallet->status) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Kurallara uygun geçiş yapınız.</small>
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Rejection Fields --}}
                    <div id="rejectionFields" style="display: none; background: #fff5f5; border: 1px dashed #feb2b2; border-radius: 12px; padding: 1.5rem; margin-top: 1rem;">
                        <h5 style="color: #c53030; font-size: 0.9rem; margin-bottom: 1rem;"><i class="fas fa-exclamation-circle"></i> Ret Detayları</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ret Sebebi Testi</label>
                                    <select name="rejected_test" id="rejectTestSelect" class="custom-select">
                                        <option value="Elek">Elek Testi</option>
                                        <option value="Yüzey">Yüzey Testi</option>
                                        {{-- Arge only if already pre-approved --}}
                                        @if($pallet->status == \App\Models\GranilyaProduction::STATUS_PRE_APPROVED)
                                            <option value="Arge">Arge Testi</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Red Nedeni</label>
                                    <select name="reject_reason" id="rejectReasonSelect" class="custom-select">
                                        {{-- JS will populate this --}}
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Genel Not</label>
                                <textarea name="general_note" class="form-control" rows="2">{{ $pallet->general_note }}</textarea>
                            </div>
                        </div>
                    </div>
                    @if($pallet->system_note)
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="text-info"><i class="fas fa-info-circle mr-1"></i> Sistem Bilgisi</label>
                                <div class="p-3 bg-light rounded border text-muted" style="white-space: pre-wrap; font-size: 0.9rem;">{{ $pallet->system_note }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="mt-4">
                        <button type="submit" class="btn-modern btn-save-modern">
                            <i class="fas fa-save"></i> Değişiklikleri Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- PRODUCTION INFO (FULL WIDTH) --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern"><i class="fas fa-info-circle"></i> Üretim & Stok Bilgileri</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="info-row" style="grid-template-columns: repeat(4, 1fr);">
                            <div class="info-block">
                                <div class="info-label">Palet Numarası</div>
                                <div class="info-value">{{ $pallet->pallet_number }}</div>
                            </div>
                            <div class="info-block">
                                <div class="info-label">Şarj Numarası</div>
                                <div class="info-value">{{ $pallet->load_number }}</div>
                            </div>
                            <div class="info-block">
                                <div class="info-label">Frit Kodu / Stok</div>
                                <div class="info-value">{{ $pallet->stock->name ?? '-' }}</div>
                            </div>
                            <div class="info-block">
                                <div class="info-label">Tane Boyutu</div>
                                <div class="info-value">{{ $pallet->size->name ?? '-' }}</div>
                            </div>
                            <div class="info-block">
                                <div class="info-label">Kırıcı Makina</div>
                                <div class="info-value">{{ $pallet->crusher->name ?? '-' }}</div>
                            </div>
                            <div class="info-block">
                                <div class="info-label">Net Miktar</div>
                                <div class="info-value">{{ number_format($pallet->used_quantity, 0, ',', '.') }} KG</div>
                            </div>
                            <div class="info-block">
                                <div class="info-label">Müşteri Tipi</div>
                                <div class="info-value">{{ $pallet->customer_type }}</div>
                            </div>
                            <div class="info-block">
                                <div class="info-label">Üretim Tarihi</div>
                                <div class="info-value">{{ $pallet->created_at->format('d.m.Y H:i') }}</div>
                            </div>
                            <div class="info-block">
                                <div class="info-label">Oluşturan Personel</div>
                                <div class="info-value">{{ $pallet->user->name ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- LABORATORY RESULTS --}}
        <div class="card-modern mb-4">
            <div class="card-header-modern">
                <h3 class="card-title-modern"><i class="fas fa-microscope"></i> Laboratuvar & Kalite Sonuçları</h3>
                @php
                    $lastLabAction = $pallet->histories()
                        ->where('description', 'NOT LIKE', '%bilgileri güncellendi%')
                        ->where('description', 'NOT LIKE', '%oluşturuldu%')
                        ->orderByDesc('created_at')
                        ->first();
                @endphp
                @if($lastLabAction)
                    <div class="small text-muted">
                        <i class="fas fa-info-circle"></i> Son Güncelleme: {{ $lastLabAction->user->name }} | {{ $lastLabAction->created_at->format('d.m.Y H:i') }}
                    </div>
                @endif
            </div>
            <div class="card-body-modern">
                {{-- Detailed Lab History like Frit Barcode --}}
                <div class="mb-4">
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <thead class="text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">
                                <tr>
                                    <th>Test Türü</th>
                                    <th>Sonuç</th>
                                    <th>İşlemi Yapan</th>
                                    <th>Tarih</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 0.9rem; font-weight: 600;">
                                @php
                                    $testHistories = $pallet->histories()
                                        ->where('description', 'NOT LIKE', '%bilgileri güncellendi%')
                                        ->where('description', 'NOT LIKE', '%oluşturuldu%')
                                        ->orderByDesc('created_at')
                                        ->get();
                                @endphp
                                @forelse($testHistories as $h)
                                    <tr>
                                        <td>
                                            @if(str_contains($h->description, 'Elek')) <span class="badge badge-light border text-dark">Elek</span>
                                            @elseif(str_contains($h->description, 'Yüzey')) <span class="badge badge-light border text-dark">Yüzey</span>
                                            @elseif(str_contains($h->description, 'Arge')) <span class="badge badge-light border text-dark">Arge</span>
                                            @elseif(str_contains($h->description, 'İstisnai')) <span class="badge badge-light border text-dark">İstisnai</span>
                                            @elseif(str_contains($h->description, 'Durum')) <span class="badge badge-light border text-dark">Durum</span>
                                            @elseif(str_contains($h->description, 'Palet oluşturuldu')) <span class="badge badge-light border text-dark">Sistem</span>
                                            @else <span class="badge badge-light border text-dark">Bilgi</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(str_contains($h->description, 'Onay')) <span class="text-success">Onay</span>
                                            @elseif(str_contains($h->description, 'Red')) <span class="text-danger">Red</span>
                                            @else <span class="text-info">İşlem</span>
                                            @endif
                                        </td>
                                        <td>{{ $h->user->name }}</td>
                                        <td class="text-muted small">{{ $h->created_at->format('d.m.Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Henüz laboratuvar kaydı bulunmuyor.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="section-divider"></div>

                <div class="row">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="test-card-modern {{ $pallet->sieve_test_result === 'Onay' ? 'approved' : ($pallet->sieve_test_result === 'Red' ? 'rejected' : '') }}">
                            <h6>Elek Testi</h6>
                            <div class="h5 font-weight-bold {{ $pallet->sieve_test_result === 'Onay' ? 'text-success' : ($pallet->sieve_test_result === 'Red' ? 'text-danger' : '') }}">
                                {{ $pallet->sieve_test_result ?? 'Bekliyor' }}
                            </div>
                            @if($pallet->sieve_reject_reason)
                                <div class="mt-2 small font-weight-bold text-danger">Sebep: {{ $pallet->sieve_reject_reason }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="test-card-modern {{ $pallet->surface_test_result === 'Onay' ? 'approved' : ($pallet->surface_test_result === 'Red' ? 'rejected' : '') }}">
                            <h6>Yüzey Testi</h6>
                            <div class="h5 font-weight-bold {{ $pallet->surface_test_result === 'Onay' ? 'text-success' : ($pallet->surface_test_result === 'Red' ? 'text-danger' : '') }}">
                                {{ $pallet->surface_test_result ?? 'Bekliyor' }}
                            </div>
                            @if($pallet->surface_reject_reason)
                                <div class="mt-2 small font-weight-bold text-danger">Sebep: {{ $pallet->surface_reject_reason }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="test-card-modern {{ $pallet->arge_test_result === 'Onay' ? 'approved' : ($pallet->arge_test_result === 'Red' ? 'rejected' : '') }}">
                            <h6>Arge Testi</h6>
                            <div class="h5 font-weight-bold {{ $pallet->arge_test_result === 'Onay' ? 'text-success' : ($pallet->arge_test_result === 'Red' ? 'text-danger' : '') }}">
                                {{ $pallet->arge_test_result ?? 'Bekliyor' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('statusSelect');
    const rejectTestSelect = document.getElementById('rejectTestSelect');
    const rejectReasonSelect = document.getElementById('rejectReasonSelect');
    const rejectionFields = document.getElementById('rejectionFields');
    
    const statusRejected = "{{ \App\Models\GranilyaProduction::STATUS_REJECTED }}";
    const currentStatus = "{{ $pallet->status }}";
    const statusWaiting = "{{ \App\Models\GranilyaProduction::STATUS_WAITING }}";

    const sieveReasons = @json(\App\Models\GranilyaProduction::getSieveRejectionReasons());
    const surfaceReasons = @json(\App\Models\GranilyaProduction::getSurfaceRejectionReasons());

    function updateRejectReasons() {
        const testType = rejectTestSelect.value;
        let reasons = [];
        if (testType === 'Elek') reasons = sieveReasons;
        else if (testType === 'Yüzey') reasons = surfaceReasons;
        else if (testType === 'Arge') reasons = ['Arge Reddi'];

        rejectReasonSelect.innerHTML = '';
        reasons.forEach(r => {
            const opt = document.createElement('option');
            opt.value = r;
            opt.text = r;
            rejectReasonSelect.appendChild(opt);
        });
    }

    function toggleRejectionFields() {
        const selectedValue = statusSelect.value;
        
        // Sadece BEKLEMEDE (1) olan bir ürün REDDEDİLDİ (5) yapılıyorsa detay sor.
        if (selectedValue == statusRejected && currentStatus == statusWaiting) {
            rejectionFields.style.display = 'block';
            rejectionFields.querySelectorAll('select').forEach(el => el.required = true);
            updateRejectReasons();
        } else {
            rejectionFields.style.display = 'none';
            rejectionFields.querySelectorAll('select').forEach(el => el.required = false);
        }
    }

    statusSelect.addEventListener('change', toggleRejectionFields);
    rejectTestSelect.addEventListener('change', updateRejectReasons);
    
    toggleRejectionFields(); 
});
</script>
@endsection
@endsection
