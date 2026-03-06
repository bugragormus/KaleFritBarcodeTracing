@extends('layouts.granilya')

@section('styles')
<style>
    /* ---- Glassmorphism Base ---- */
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* ---- Page Header ---- */
    .page-header-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2.5rem;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }
    .page-header-modern h1 { font-size: 2.2rem; margin-bottom: 0.2rem; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .page-title-modern { font-weight: 800; display: flex; align-items: center; }
    .page-title-modern i { margin-right: 1.2rem; font-size: 2.4rem; color: #fff; }
    .page-subtitle-modern { font-size: 1.1rem; opacity: 0.9; margin-bottom: 0; font-weight: 500; }

    /* ---- Stat Cards ---- */
    .stat-card-modern {
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.98);
        padding: 2rem 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 240px; /* Standardized height */
    }
    .stat-card-modern:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0,0,0,0.12); }
    .stat-icon-modern { font-size: 2.8rem; margin-bottom: 1rem; }
    .stat-number-modern { font-size: 2.8rem; font-weight: 800; margin-bottom: 0.4rem; color: #1a202c; }
    .stat-label-modern { color: #64748b; font-size: 0.9rem; margin-bottom: 0; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; }
    
    /* Stats Colors Sync */
    .text-clean { color: #22c55e !important; }
    .text-exceptional { color: #f59e0b !important; }
    .text-corrected { color: #6366f1 !important; }
    .text-pending { color: #94a3b8 !important; }

    /* ---- Quick Action Buttons ---- */
    .quick-action-btn-modern {
        border-radius: 12px;
        padding: 1.2rem;
        font-weight: 700;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        width: 100%;
    }
    .quick-action-btn-modern i { font-size: 1.3rem; }
    .quick-action-btn-modern:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }

    /* ---- Cards & Layout ---- */
    .card-modern {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    .card-header-modern {
        background: rgba(255, 255, 255, 0.05);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    .card-title-modern {
        font-size: 1.3rem; font-weight: 800; color: #1a202c; margin-bottom: 0;
        display: flex; align-items: center;
    }
    .card-title-modern i { margin-right: 0.8rem; color: #667eea; font-size: 1.4rem; }

    /* ---- Form Controls ---- */
    .form-control {
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding: 0.7rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); outline: none; }
</style>
@endsection

@section('content')
<div class="modern-lab-dashboard">
    <div class="container py-4"> {{-- Changed container-fluid to container to prevent stretching --}}

        {{-- ========================== --}}
        {{-- PAGE HEADER --}}
        {{-- ========================== --}}
        <div class="page-header-modern">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title-modern">
                        <i class="fas fa-atom"></i> Granilya Lab Paneli
                    </h1>
                    <p class="page-subtitle-modern">Yüksek hassasiyetli laboratuvar veri yönetimi</p>
                </div>
                <div class="col-md-4 text-md-right">
                    <div class="badge badge-glass" style="font-size:1.1rem; padding:1rem 1.5rem; background: rgba(255,255,255,0.2); backdrop-filter: blur(5px); border-radius: 15px; color:white; border: 1px solid rgba(255,255,255,0.3);">
                        <i class="far fa-clock mr-2"></i> {{ now()->setTimezone('Europe/Istanbul')->format('d.m.Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================== --}}
        {{-- KPI DATE FILTER --}}
        {{-- ========================== --}}
        <div class="card-modern glass-card mb-4">
            <div class="card-body-modern">
                <div class="row align-items-center">
                    <div class="col-xl-6">
                        <h4 class="mb-2 font-weight-bold">
                            <i class="fas fa-calendar-check text-primary mr-3"></i>Analiz Zaman Aralığı
                        </h4>
                        <p class="text-muted mb-0">
                            <strong>Aktif Filtre:</strong> 
                            <span class="badge badge-soft-primary px-3 py-2" style="font-size: 0.9rem;">
                                {{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} — 
                                {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }}
                            </span>
                        </p>
                    </div>
                    <div class="col-xl-6">
                        <form id="kpi-date-filter" action="{{ route('granilya.laboratory.dashboard') }}" method="GET" class="row g-3">
                            <div class="col-md-5">
                                <label for="start_date" class="form-label">Başlangıç</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                       value="{{ $startDate }}">
                            </div>
                            <div class="col-md-5">
                                <label for="end_date" class="form-label">Bitiş</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                       value="{{ $endDate }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-block h-100" style="min-height: 48px;">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </form>
                        {{-- Quick Date Buttons --}}
                        <div class="mt-3">
                            <small class="text-muted">Hızlı seçim: </small>
                            <button type="button" class="btn btn-outline-secondary btn-sm mx-1" onclick="setDateRange('today')">Bugün</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm mx-1" onclick="setDateRange('yesterday')">Dün</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm mx-1" onclick="setDateRange('week')">Bu Hafta</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm mx-1" onclick="setDateRange('month')">Bu Ay</button>
                            <button type="button" class="btn btn-outline-warning btn-sm mx-1" onclick="resetDateFilter()">Sıfırla</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================== --}}
        {{-- KPI STATS --}}
        {{-- ========================== --}}
        <div class="row">
            {{-- Kabul Oranı --}}
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="stat-card-modern glass-card shadow-sm">
                    <div class="stat-icon-modern text-success">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="stat-number-modern text-clean">{{ $stats['acceptance_rate'] }}%</div>
                    <div class="stat-label-modern">Kabul Oranı</div>
                    <p class="text-muted small mt-2 mb-0">Hatasız + İstisnai Oranı</p>
                </div>
            </div>

            {{-- Hatasız Onaylanan --}}
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="stat-card-modern glass-card shadow-sm">
                    <div class="stat-icon-modern text-primary">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div class="stat-number-modern text-primary">{{ $stats['clean_approved'] }}</div>
                    <div class="stat-label-modern">Hatasız Onay</div>
                    <p class="text-muted small mt-2 mb-0">Doğrudan onay alanlar</p>
                </div>
            </div>

            {{-- İstisnai Onay --}}
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="stat-card-modern glass-card shadow-sm">
                    <div class="stat-icon-modern text-exceptional">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="stat-number-modern text-exceptional">{{ $stats['exceptional_approved'] }}</div>
                    <div class="stat-label-modern">İstisnai Onay</div>
                    <p class="text-muted small mt-2 mb-0">Limit dışı onaylananlar</p>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Düzeltme Faaliyeti --}}
            <div class="col-xl-4 col-md-4 mb-4">
                <div class="stat-card-modern glass-card shadow-sm">
                    <div class="stat-icon-modern text-corrected">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="stat-number-modern text-corrected">{{ $stats['corrected'] }}</div>
                    <div class="stat-label-modern">Düzeltme Faaliyeti</div>
                </div>
            </div>

            {{-- Reddedilen --}}
            <div class="col-xl-4 col-md-4 mb-4">
                <div class="stat-card-modern glass-card shadow-sm">
                    <div class="stat-icon-modern text-danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-number-modern text-danger">{{ $stats['rejected'] }}</div>
                    <div class="stat-label-modern">Reddedilen</div>
                </div>
            </div>

            {{-- Test Aşamasında --}}
            <div class="col-xl-4 col-md-4 mb-4">
                <div class="stat-card-modern glass-card shadow-sm">
                    <div class="stat-icon-modern text-pending">
                        <i class="fas fa-vial"></i>
                    </div>
                    <div class="stat-number-modern text-pending">{{ $stats['pending_total'] }}</div>
                    <div class="stat-label-modern">Test Aşamasında</div>
                </div>
            </div>
        </div>

        {{-- ========================== --}}
        {{-- QUICK ACTIONS CARD --}}
        {{-- ========================== --}}
        <div class="row">
            <div class="col-12">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern"><i class="fas fa-bolt"></i> Analiz ve Raporlar</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('granilya.laboratory.report') }}"
                                   class="btn btn-info btn-block quick-action-btn-modern">
                                    <i class="fas fa-file-alt mr-2"></i> Lab Raporu
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('granilya.laboratory.stock_analysis') }}"
                                   class="btn btn-dark btn-block quick-action-btn-modern">
                                    <i class="fas fa-boxes mr-2"></i> Stok Analizi
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('granilya.laboratory.crusher_performance') }}"
                                   class="btn btn-success btn-block quick-action-btn-modern">
                                    <i class="fas fa-tools mr-2"></i> Kırıcı Analizi
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('granilya.laboratory.bulk-process') }}"
                                   class="btn btn-warning btn-block quick-action-btn-modern text-white">
                                    <i class="fas fa-layer-group mr-2"></i> Toplu İşlem
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================== --}}
        {{-- PENDING PALLETS + RECENT ACTIVITIES --}}
        {{-- ========================== --}}
        <div class="row">
            {{-- Pending Pallets --}}
            <div class="col-lg-6">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">
                            <i class="fas fa-clock text-warning"></i>Laboratuvar İşlemleri (Son 20)
                        </h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Stok</th>
                                        <th>Şarj / Palet</th>
                                        <th>Miktar</th>
                                        <th>Oluşturan</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingPallets as $p)
                                    <tr>
                                        <td>
                                            <strong>{{ $p->stock->name ?? '-' }}</strong><br>
                                            {!! $p->status_badge !!}
                                        </td>
                                        <td>
                                            Şarj: {{ $p->load_number }}<br>
                                            <small>{{ $p->pallet_number }}</small>
                                        </td>
                                        <td>{{ $p->quantity->quantity ?? '-' }} KG</td>
                                        <td>
                                            {{ $p->user->name ?? '-' }}<br>
                                            <small>{{ $p->created_at->format('d.m.Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary"
                                                    onclick="openPalletDetail({{ $p->id }})">
                                                <i class="fas fa-eye"></i> İncele
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <i class="fas fa-check-circle text-success mr-2"></i>
                                            Bekleyen palet bulunmuyor!
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($pendingPallets->count() > 0)
                        <div class="text-center mt-3">
                            <a href="{{ route('granilya.laboratory.barcode-list') }}"
                               class="btn btn-outline-primary btn-sm">Tümünü Görüntüle</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recent Activities --}}
            <div class="col-lg-6">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">
                            <i class="fas fa-history text-info"></i>Son İşlemler
                        </h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="activity-list">
                            @forelse($recentActivities as $activity)
                            <div class="activity-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $activity->stock->name ?? '-' }}</strong><br>
                                        <small class="text-muted">
                                            Palet: {{ $activity->pallet_number }} | {{ $activity->quantity->quantity ?? '-' }} KG
                                        </small>
                                    </div>
                                    <div class="text-right">
                                        {!! $activity->status_badge !!}<br>
                                        <small class="text-muted">{{ $activity->updated_at->format('H:i') }}</small>
                                    </div>
                                </div>
                                <div class="mt-1">
                                    <small class="text-muted">
                                        <i class="fas fa-user mr-1"></i>{{ $activity->user->name ?? '-' }}
                                    </small>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-info-circle mr-2"></i>
                                Henüz işlem yapılmamış
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@include('granilya.laboratory.modals')
@endsection

@section('scripts')
<script>
// Quick date range helpers (mirrors Frit lab-dashboard.js)
function setDateRange(range) {
    var today = new Date();
    var start, end;

    if (range === 'today') {
        start = end = formatDate(today);
    } else if (range === 'yesterday') {
        var y = new Date(today); y.setDate(y.getDate() - 1);
        start = end = formatDate(y);
    } else if (range === 'week') {
        var w = new Date(today);
        w.setDate(w.getDate() - w.getDay() + (w.getDay() === 0 ? -6 : 1));
        start = formatDate(w); end = formatDate(today);
    } else if (range === 'month') {
        var m = new Date(today.getFullYear(), today.getMonth(), 1);
        start = formatDate(m); end = formatDate(today);
    }

    document.getElementById('start_date').value = start;
    document.getElementById('end_date').value   = end;
    document.getElementById('kpi-date-filter').submit();
}

function resetDateFilter() {
    var today = new Date();
    var past  = new Date(today); past.setDate(past.getDate() - 30);
    document.getElementById('start_date').value = formatDate(past);
    document.getElementById('end_date').value   = formatDate(today);
    document.getElementById('kpi-date-filter').submit();
}

function formatDate(d) {
    return d.getFullYear() + '-' +
           String(d.getMonth() + 1).padStart(2, '0') + '-' +
           String(d.getDate()).padStart(2, '0');
}
</script>
@endsection
