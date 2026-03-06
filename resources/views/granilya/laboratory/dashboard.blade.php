@extends('layouts.granilya')

@section('styles')
    <style>
        /* ---- Global Layout Fixes ---- */
        * { font-family: 'Poppins', 'Inter', 'Segoe UI', Arial, sans-serif !important; }
        
        body { background-color: #f8fafc !important; }
        
        .container {
            max-width: 1280px !important;
            margin: 0 auto !important;
        }

        /* ---- Header Sync ---- */
        .page-header-granilya {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }
        .page-header-granilya h1 { font-size: 2.2rem; margin-bottom: 0.2rem; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .page-header-granilya p { opacity: 0.9; font-weight: 500; }
        
        /* ---- Stat Cards Sync ---- */
        .stat-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 18px;
            padding: 2rem 1.5rem;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 200px;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
        .stat-icon { font-size: 2.5rem; margin-bottom: 1rem; }
        .stat-number { font-size: 2.2rem; font-weight: 800; margin-bottom: 0.4rem; }
        .stat-label { font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 0; }
        .stat-subtext { font-size: 0.75rem; color: #94a3b8; margin-top: 0.5rem; }

        /* ---- Colors ---- */
        .text-clean { color: #22c55e !important; }
        .text-exceptional { color: #f59e0b !important; }
        .text-corrected { color: #6366f1 !important; }
        .text-rejected { color: #ef4444 !important; }
        .text-pending { color: #94a3b8 !important; }

        /* ---- Containers ---- */
        .card-granilya {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .card-header-granilya {
            background: rgba(0, 0, 0, 0.02);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-header-granilya h5 { margin: 0; font-weight: 800; color: #1a202c; display: flex; align-items: center; }
        .card-header-granilya h5 i { color: #667eea; margin-right: 12px; }

        /* Quick Action Buttons */
        .btn-quick-action {
            border-radius: 12px;
            padding: 1.2rem;
            font-weight: 700;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            height: 100%;
        }
        .btn-quick-action:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .btn-quick-action i { font-size: 1.3rem; }

        /* Table */
        .table-granilya thead th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1rem;
            border: none;
        }
        .table-granilya tbody td { padding: 1rem; border-top: 1px solid #f1f5f9; vertical-align: middle; }
        
        .activity-item { padding: 1rem; border-bottom: 1px solid #f1f5f9; }
        .activity-item:last-child { border-bottom: none; }
    </style>
@endsection

@section('content')
<div class="container py-4">
    {{-- Header Section --}}
    <div class="page-header-granilya">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="font-weight-bold"><i class="fas fa-atom mr-3"></i> Granilya Laboratuvar Paneli</h1>
                <p class="mb-0">Yüksek hassasiyetli veri takibi ve kalite güvence analitiği</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <div class="badge badge-light px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.9rem; background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">
                    <i class="far fa-clock mr-2"></i> {{ now()->format('d.m.Y H:i') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Date Filter Card --}}
    <div class="card-granilya mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-xl-5">
                    <h5 class="mb-1 font-weight-bold"><i class="fas fa-calendar-alt text-primary mr-2"></i> Analiz Zaman Aralığı</h5>
                    <p class="text-muted small mb-0">Seçilen tarih aralığındaki veriler KPI'lara yansıtılır.</p>
                </div>
                <div class="col-xl-7 mt-3 mt-xl-0">
                    <form id="kpi-date-filter" action="{{ route('granilya.laboratory.dashboard') }}" method="GET" class="row align-items-end">
                        <div class="col-md-5 mb-2 mb-md-0">
                            <label class="text-muted small font-weight-bold uppercase mb-1">Başlangıç</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-5 mb-2 mb-md-0">
                            <label class="text-muted small font-weight-bold uppercase mb-1">Bitiş</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block" style="height: calc(1.5em + 1.5rem + 2px);">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </form>
                    <div class="mt-3">
                        <small class="text-muted font-weight-bold mr-2">HIZLI SEÇİM:</small>
                        <button type="button" class="btn btn-sm btn-outline-secondary px-3 mr-1" onclick="setDateRange('today')">Bugün</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary px-3 mr-1" onclick="setDateRange('yesterday')">Dün</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary px-3 mr-1" onclick="setDateRange('week')">Bu Hafta</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary px-3 mr-1" onclick="setDateRange('month')">Bu Ay</button>
                        <button type="button" class="btn btn-sm btn-outline-warning px-3" onclick="resetDateFilter()">Sıfırla</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Metrics Rows --}}
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon text-success"><i class="fas fa-percentage"></i></div>
                <div class="stat-number text-clean">{{ $stats['acceptance_rate'] }}%</div>
                <div class="stat-label">Kabul Oranı</div>
                <div class="stat-subtext">Hatasız Onay + İstisnai Onay / Toplam</div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon text-primary"><i class="fas fa-check-circle"></i></div>
                <div class="stat-number text-primary">{{ $stats['clean_approved'] }}</div>
                <div class="stat-label">Hatasız Onay</div>
                <div class="stat-subtext">Doğrudan laboratuvar onayı alanlar</div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon text-exceptional"><i class="fas fa-star"></i></div>
                <div class="stat-number text-exceptional">{{ $stats['exceptional_approved'] }}</div>
                <div class="stat-label">İstisnai Onay</div>
                <div class="stat-subtext">Limit dışı değerlendirilip onaylananlar</div>
            </div>
        </div>
        
        <div class="col-xl-4 col-md-4 col-sm-6 mb-3">
            <div class="stat-card" style="min-height: 160px;">
                <div class="stat-icon text-corrected" style="font-size: 1.8rem;"><i class="fas fa-wrench"></i></div>
                <div class="stat-label">Düzeltme Faaliyeti</div>
                <div class="stat-number text-corrected" style="font-size: 1.8rem;">{{ $stats['corrected'] }}</div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4 col-sm-6 mb-3">
            <div class="stat-card" style="min-height: 160px;">
                <div class="stat-icon text-rejected" style="font-size: 1.8rem;"><i class="fas fa-times-circle"></i></div>
                <div class="stat-label">Reddedilen</div>
                <div class="stat-number text-rejected" style="font-size: 1.8rem;">{{ $stats['rejected'] }}</div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4 col-sm-6 mb-3">
            <div class="stat-card" style="min-height: 160px;">
                <div class="stat-icon text-pending" style="font-size: 1.8rem;"><i class="fas fa-vials"></i></div>
                <div class="stat-label">Test Aşamasında</div>
                <div class="stat-number text-pending" style="font-size: 1.8rem;">{{ $stats['pending_total'] }}</div>
            </div>
        </div>
    </div>

    {{-- Quick Access Section --}}
    <h5 class="mb-3 font-weight-bold ml-1 text-muted uppercase" style="letter-spacing: 1px;">Analiz ve Rapor Modülleri</h5>
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-2">
            <a href="{{ route('granilya.laboratory.report') }}" class="btn btn-info btn-block btn-quick-action">
                <i class="fas fa-file-invoice"></i> Lab Raporu
            </a>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <a href="{{ route('granilya.laboratory.stock_analysis') }}" class="btn btn-secondary btn-block btn-quick-action" style="background: #1e293b; color: white;">
                <i class="fas fa-cubes"></i> Stok Analizi
            </a>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <a href="{{ route('granilya.laboratory.crusher_performance') }}" class="btn btn-success btn-block btn-quick-action">
                <i class="fas fa-chart-bar"></i> Kırıcı Analizi
            </a>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <a href="{{ route('granilya.laboratory.bulk-process') }}" class="btn btn-warning btn-block btn-quick-action text-white">
                <i class="fas fa-tasks"></i> Toplu İşlem
            </a>
        </div>
    </div>

    {{-- Lists Section --}}
    <div class="row mt-4">
        {{-- Pending Pallets --}}
        <div class="col-lg-7">
            <div class="card-granilya">
                <div class="card-header-granilya">
                    <h5><i class="fas fa-hourglass-half"></i> Bekleyen İşlemler (Son 20)</h5>
                    <a href="{{ route('granilya.laboratory.barcode-list') }}" class="btn btn-sm btn-link text-primary font-weight-bold">Tümünü Gör</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-granilya mb-0">
                            <thead>
                                <tr>
                                    <th>Stok</th>
                                    <th>Şarj / Palet No</th>
                                    <th>Miktar</th>
                                    <th>Durum</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingPallets as $p)
                                <tr>
                                    <td><strong>{{ $p->stock->name ?? '-' }}</strong></td>
                                    <td>{{ $p->load_number }} / <small>{{ $p->pallet_number }}</small></td>
                                    <td>{{ $p->quantity->quantity ?? '-' }} KG</td>
                                    <td>{!! $p->status_badge !!}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="openPalletDetail({{ $p->id }})">
                                            İncele
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted italic">Kayıt bulunamadı.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="col-lg-5">
            <div class="card-granilya">
                <div class="card-header-granilya">
                    <h5><i class="fas fa-history"></i> Son İşlemler</h5>
                </div>
                <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                    @forelse($recentActivities as $activity)
                    <div class="activity-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div style="flex: 1;">
                                <div class="font-weight-bold">{{ $activity->stock->name ?? '-' }}</div>
                                <div class="text-muted small">Palet: {{ $activity->pallet_number }} | {{ $activity->quantity->quantity ?? '-' }} KG</div>
                                <div class="text-muted small mt-1"><i class="fas fa-user mr-1"></i> {{ $activity->user->name ?? '-' }}</div>
                            </div>
                            <div class="text-right">
                                <div class="mb-1">{!! $activity->status_badge !!}</div>
                                <div class="small text-muted">{{ $activity->updated_at->format('H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-5 text-center text-muted">Henüz işlem yapılmamış.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@include('granilya.laboratory.modals')
@endsection

@section('scripts')
<script>
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
