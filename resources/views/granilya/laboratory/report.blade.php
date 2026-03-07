@extends('layouts.granilya')

@section('styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <style>
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
        
        /* ---- Stat Cards Sync ---- */
        .stat-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 18px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-number { font-size: 1.8rem; font-weight: 800; margin-bottom: 0.3rem; }
        .stat-label { font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }

        /* ---- Colors ---- */
        .text-clean { color: #22c55e !important; }
        .text-exceptional { color: #f59e0b !important; }
        .text-corrected { color: #6366f1 !important; }
        .text-pending { color: #94a3b8 !important; }

        /* ---- Card Refinement ---- */
        .card-granilya {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .table-granilya thead th {
            background-color: #f7fafc;
            color: #4a5568;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
            padding: 1.2rem;
            border: none;
        }
        .table-granilya tbody td {
            padding: 1.2rem;
            vertical-align: middle;
            border-top: 1px solid #edf2f7;
        }

        /* Premium Excel Button */
        .btn-excel-premium {
            background: linear-gradient(135deg, #22c55e 0%, #15803d 100%);
            color: white !important;
            border: none;
            border-radius: 12px;
            padding: 0.8rem 1.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
            transition: all 0.3s ease;
        }
        .btn-excel-premium:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 8px 25px rgba(34, 197, 94, 0.4);
            filter: brightness(1.1);
        }
        .btn-excel-premium i { font-size: 1.2rem; }
    </style>
@endsection

@section('content')
<div class="container py-4"> {{-- Changed container-fluid to container --}}
    <div class="page-header-granilya">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h2 mb-1 font-weight-bold"><i class="fas fa-chart-line mr-3"></i> Granilya Lab Raporu</h1>
                <p class="mb-0 opacity-90 font-weight-500">Kapsamlı laboratuvar analiz ve işlem verileri</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('granilya.laboratory.export_report', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="btn-excel-premium">
                    <i class="fas fa-file-excel"></i> Excel Olarak İndir
                </a>
            </div>
        </div>
    </div>

    {{-- Filtreler --}}
    <div class="card-granilya mb-4">
        <div class="card-body p-4">
            <form action="{{ route('granilya.laboratory.report') }}" method="GET" class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-muted small font-weight-bold uppercase">Başlangıç Tarihi</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small font-weight-bold uppercase">Bitiş Tarihi</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-block" style="padding: 0.8rem;">
                        <i class="fas fa-filter mr-1"></i> Filtrele ve Raporla
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Özet Kartları --}}
    <div class="row mb-2">
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-number text-clean">{{ $summary['acceptance_rate'] }}%</div>
                <div class="stat-label">Kabul Oranı</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-number text-primary">{{ $summary['clean_approved'] }}</div>
                <div class="stat-label">Hatasız Onay</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-number text-exceptional">{{ $summary['exceptional_approved'] }}</div>
                <div class="stat-label">İstisnai Onay</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-number text-corrected">{{ $summary['corrected'] }}</div>
                <div class="stat-label">Düzeltme</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-number text-danger">{{ $summary['rejected'] }}</div>
                <div class="stat-label">Reddedilen</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-number text-pending">{{ $summary['waiting'] + $summary['pre_approved'] }}</div>
                <div class="stat-label">Testte</div>
            </div>
        </div>
    </div>

    <!-- Detaylı Liste -->
    <div class="card card-granilya">
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <h5 class="mb-0"><i class="fas fa-list mr-2"></i> Analiz Detayları (Gruplanmış)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-granilya mb-0">
                    <thead>
                        <tr>
                            <th class="px-4">Tarih</th>
                            <th>Ürün Adı</th>
                            <th>Durum</th>
                            <th>İşlem Yapan</th>
                            <th class="text-center">Adet</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report as $item)
                        <tr>
                            <td class="px-4">{{ \Carbon\Carbon::parse($item->lab_date)->format('d.m.Y') }}</td>
                            <td>{{ $item->stock->name }}</td>
                            <td>
                                @php
                                    $statusList = \App\Models\GranilyaProduction::getStatusList();
                                    $label = $statusList[$item->status] ?? 'Bilinmiyor';
                                    $class = match($item->status) {
                                        3 => 'info',
                                        4 => 'success',
                                        5 => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge badge-{{ $class }} px-2 py-1">{{ $label }}</span>
                            </td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                            <td class="text-center"><strong>{{ $item->count }}</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Seçilen tarih aralığında veri bulunamadı.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
