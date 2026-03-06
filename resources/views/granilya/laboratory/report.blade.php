@extends('layouts.app')

@section('styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <style>
        .page-header-granilya {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: 0 10px 25px rgba(30, 58, 138, 0.2);
        }
        .card-granilya {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        .stat-card {
            text-align: center;
            padding: 1.5rem;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-number { font-size: 1.8rem; font-weight: 700; margin-bottom: 0.2rem; }
        .stat-label { color: #64748b; font-size: 0.9rem; text-transform: uppercase; }
        .table-granilya thead th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="page-header-granilya">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h2 mb-1"><i class="fas fa-microscope mr-2"></i> Granilya Lab Raporu</h1>
                <p class="mb-0 opacity-75">Laboratuvar analiz sonuçları ve genel istatistikler</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('granilya.laboratory.export_report', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="btn btn-light">
                    <i class="fas fa-file-excel mr-1 text-success"></i> Excel İndir
                </a>
            </div>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="card card-granilya">
        <div class="card-body">
            <form action="{{ route('granilya.laboratory.report') }}" method="GET" class="row align-items-end">
                <div class="col-md-4">
                    <label class="small font-weight-bold text-muted">Başlangıç Tarihi</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="small font-weight-bold text-muted">Bitiş Tarihi</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4 mt-3 mt-md-0">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter mr-1"></i> Filtrele
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Özetler -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number text-primary">{{ $summary['total_processed'] }}</div>
                <div class="stat-label">Toplam İşlenen</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number text-success">{{ $summary['shipment_approved'] }}</div>
                <div class="stat-label">Sevk Onaylı</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-number text-danger">{{ $summary['rejected'] }}</div>
                <div class="stat-label">Reddedildi</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-number text-info">{{ $summary['pre_approved'] }}</div>
                <div class="stat-label">Ön Onaylı</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-number text-warning">{{ $summary['exceptional_approved'] }}</div>
                <div class="stat-label">İstisnai Onay</div>
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
