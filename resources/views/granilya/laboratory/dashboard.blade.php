@extends('layouts.granilya')

@section('styles')
<style>
    body, .main-content, .modern-lab-dashboard { background: #f8f9fa !important; }
    .modern-lab-dashboard { background: #ffffff; min-height: 100vh; padding: 2rem 0; }

    /* ---- Header ---- */
    .page-header-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px; padding: 2rem; margin-bottom: 2rem;
        color: white; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    .page-title-modern { font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem; display: flex; align-items: center; }
    .page-title-modern i { margin-right: 1rem; font-size: 2rem; }
    .page-subtitle-modern { font-size: 1.1rem; opacity: 0.9; margin-bottom: 0; }

    /* ---- Stat Cards ---- */
    .stat-card-modern {
        border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.08);
        transition: transform 0.2s; background: #fff;
        padding: 2rem 1.5rem; margin-bottom: 1.5rem; border: 1px solid #e9ecef; text-align: center;
    }
    .stat-card-modern:hover { transform: translateY(-2px); }
    .stat-icon-modern { font-size: 2.5rem; opacity: 0.8; margin-bottom: 0.5rem; }
    .stat-number-modern { font-size: 2rem; font-weight: 700; margin-bottom: 0.25rem; }
    .stat-label-modern { color: #6c757d; font-size: 1rem; margin-bottom: 0; font-weight: 600; }

    /* ---- Quick Action Buttons ---- */
    .quick-action-btn-modern {
        border-radius: 10px; padding: 15px; margin: 10px 0;
        transition: all 0.3s; font-weight: 600; font-size: 1rem; display: block;
    }
    .quick-action-btn-modern:hover { transform: scale(1.05); }

    /* ---- Cards ---- */
    .card-modern {
        background: #ffffff; border-radius: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef; overflow: hidden; margin-bottom: 2rem;
    }
    .card-header-modern {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem 2rem; border-bottom: 1px solid #e9ecef;
    }
    .card-title-modern {
        font-size: 1.3rem; font-weight: 600; color: #495057; margin-bottom: 0;
        display: flex; align-items: center;
    }
    .card-title-modern i { margin-right: 0.5rem; color: #667eea; }
    .card-subtitle-modern { color: #6c757d; margin-bottom: 0; }
    .card-body-modern { padding: 2rem; }

    /* ---- Activity List ---- */
    .activity-item {
        border-left: 3px solid #007bff; padding-left: 15px; margin-bottom: 10px;
    }
    .pending-item {
        background: #f8f9fa; border-radius: 8px; padding: 10px;
        margin-bottom: 8px; border-left: 4px solid #ffc107;
    }

    /* ---- Date Filter ---- */
    .form-label { font-weight: 600; color: #495057; font-size: 0.9rem; margin-bottom: 0.3rem; }
    .form-control {
        border-radius: 8px; border: 1px solid #ced4da; transition: all 0.3s ease;
    }
    .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none; border-radius: 8px; transition: all 0.3s ease;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); }

    /* ---- Responsive ---- */
    @media (max-width: 768px) {
        .page-title-modern { font-size: 2rem; }
        .stat-card-modern { padding: 1.2rem 1rem; margin-bottom: 1rem; }
        .card-body-modern { padding: 1.2rem; }
    }
    @media (max-width: 576px) {
        .page-title-modern { font-size: 1.8rem; }
        .stat-card-modern { padding: 1rem 0.8rem; }
        .card-body-modern { padding: 1rem; }
    }
</style>
@endsection

@section('content')
<div class="modern-lab-dashboard">
    <div class="container-fluid">

        {{-- ========================== --}}
        {{-- PAGE HEADER --}}
        {{-- ========================== --}}
        <div class="page-header-modern">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title-modern">
                        <i class="fas fa-flask"></i> Granilya Laboratuvar Paneli
                    </h1>
                    <p class="page-subtitle-modern">Hoş geldiniz, {{ auth()->user()->name }}!</p>
                </div>
                <div class="col-md-4 text-right">
                    <span class="badge badge-info" style="font-size:1rem; padding:0.75rem 1.25rem; background: rgba(255,255,255,0.15); border:none; color:white;">
                        {{ now()->setTimezone('Europe/Istanbul')->format('d.m.Y H:i') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ========================== --}}
        {{-- KPI DATE FILTER --}}
        {{-- ========================== --}}
        <div class="card-modern mb-3">
            <div class="card-body-modern">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt text-primary mr-2"></i>
                            KPI Tarih Filtresi
                        </h5>
                        <small class="text-muted">
                            Tüm KPI'lar seçilen tarih aralığında oluşturulan paletleri gösterir |
                            <strong>Aktif Tarih:
                                {{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} -
                                {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }}
                            </strong>
                        </small>
                    </div>
                    <div class="col-md-6">
                        <form id="kpi-date-filter" action="{{ route('granilya.laboratory.dashboard') }}" method="GET" class="row g-2">
                            <div class="col-md-5">
                                <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                       value="{{ $startDate }}">
                            </div>
                            <div class="col-md-5">
                                <label for="end_date" class="form-label">Bitiş Tarihi</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                       value="{{ $endDate }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </form>
                        {{-- Quick Date Buttons --}}
                        <div class="mt-2">
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
        {{-- KPI ROW 1 --}}
        {{-- ========================== --}}
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-modern" title="Seçilen tarih aralığında bekleyen paletler">
                    <div class="stat-icon-modern text-warning"><i class="fas fa-clock"></i></div>
                    <div class="stat-number-modern">{{ $stats['waiting'] }}</div>
                    <div class="stat-label-modern">Bekleyen Palet</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-modern" title="Seçilen tarih aralığında ön onaylı paletler">
                    <div class="stat-icon-modern text-success"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-number-modern">{{ $stats['pre_approved'] }}</div>
                    <div class="stat-label-modern">Ön Onaylı</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-modern" title="Seçilen tarih aralığında reddedilen paletler">
                    <div class="stat-icon-modern text-danger"><i class="fas fa-times-circle"></i></div>
                    <div class="stat-number-modern">{{ $stats['rejected'] }}</div>
                    <div class="stat-label-modern">Reddedilen</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-modern" title="Toplam işlenen palet sayısı">
                    <div class="stat-icon-modern text-info"><i class="fas fa-tasks"></i></div>
                    <div class="stat-number-modern">{{ $stats['total_processed'] }}</div>
                    <div class="stat-label-modern">Toplam İşlenen</div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-xl-4 col-md-6">
                <div class="stat-card-modern" title="Sevk onaylı paletler">
                    <div class="stat-icon-modern text-dark"><i class="fas fa-shipping-fast"></i></div>
                    <div class="stat-number-modern">{{ $stats['shipment_approved'] }}</div>
                    <div class="stat-label-modern">Sevk Onaylı</div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="stat-card-modern" title="İstisnai onay verilen paletler">
                    <div class="stat-icon-modern text-warning"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="stat-number-modern">{{ $stats['exceptional_approved'] ?? 0 }}</div>
                    <div class="stat-label-modern">İstisnai Onay</div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="stat-card-modern" title="Sevk onaylı oranı">
                    <div class="stat-icon-modern text-success"><i class="fas fa-percentage"></i></div>
                    <div class="stat-number-modern">{{ $stats['acceptance_rate'] }}%</div>
                    <div class="stat-label-modern">Sevk Oranı</div>
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
