@extends('layouts.app')

@section('styles')
    <style>
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
        
        .crusher-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }
        .crusher-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0,0,0,0.12); }
        
        .acceptance-bar {
            height: 10px;
            border-radius: 5px;
            background: rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin: 1rem 0;
        }
        .acceptance-fill {
            height: 100%;
            transition: width 1s ease-in-out;
        }
        
        /* Premium Excel Button Sync */
        .btn-excel-premium {
            background: linear-gradient(135deg, #22c55e 0%, #15803d 100%);
            color: white !important;
            border: none;
            border-radius: 12px;
            padding: 0.7rem 1.5rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.2);
            transition: transform 0.3s ease;
        }
        .btn-excel-premium:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(34, 197, 94, 0.3); text-decoration: none; }
        
        /* Stats Colors Sync */
        .text-clean { color: #22c55e !important; }
        .text-exceptional { color: #f59e0b !important; }
        .text-corrected { color: #6366f1 !important; }
        .text-pending { color: #94a3b8 !important; }
    </style>
@endsection

@section('content')
<div class="container py-4"> {{-- Changed container-fluid to container --}}
    <div class="page-header-granilya">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h2 mb-1 font-weight-bold"><i class="fas fa-tools mr-3"></i> Kırıcı Performans Analizi</h1>
                <p class="mb-0 opacity-90 font-weight-500">Kırıcı bazlı üretim kalitesi ve verimlilik analizi</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('granilya.laboratory.crusher_performance_excel', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="btn-excel-premium">
                    <i class="fas fa-file-excel"></i> Excel Olarak İndir
                </a>
            </div>
        </div>
    </div>

    {{-- Filtreler --}}
    <div class="card crusher-card">
        <div class="card-body">
            <form action="{{ route('granilya.laboratory.crusher_performance') }}" method="GET" class="row align-items-end">
                <div class="col-md-4">
                    <label class="small font-weight-bold text-muted uppercase">Başlangıç Tarihi</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="small font-weight-bold text-muted uppercase">Bitiş Tarihi</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success btn-block mt-3 mt-md-0" style="padding: 0.8rem;">
                        <i class="fas fa-search mr-1"></i> Analiz Et
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($crusherData as $data)
        <div class="col-lg-6 mb-4">
            <div class="card crusher-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0 font-weight-bold text-success">{{ $data['crusher']->name }}</h4>
                        <div class="badge badge-soft-success px-3 py-2 h5 mb-0" style="border-radius: 10px;">
                            {{ $data['acceptance_rate'] }}% Başarı
                        </div>
                    </div>

                    <div class="acceptance-bar">
                        <div class="acceptance-fill bg-success" style="width: {{ $data['acceptance_rate'] }}%"></div>
                    </div>

                    <div class="row text-center mt-4">
                        <div class="col-4 border-right mb-3">
                            <div class="h4 mb-0 font-weight-bold text-dark">{{ $data['total'] }}</div>
                            <div class="small text-muted uppercase font-weight-bold" style="font-size: 0.65rem;">Toplam</div>
                        </div>
                        <div class="col-4 border-right mb-3">
                            <div class="h4 mb-0 font-weight-bold text-clean">{{ $data['clean'] }}</div>
                            <div class="small text-muted uppercase font-weight-bold" style="font-size: 0.65rem;">Hatasız</div>
                        </div>
                        <div class="col-4 mb-3">
                            <div class="h4 mb-0 font-weight-bold text-exceptional">{{ $data['exceptional'] }}</div>
                            <div class="small text-muted uppercase font-weight-bold" style="font-size: 0.65rem;">İstisnai</div>
                        </div>
                        <div class="col-4 border-right">
                            <div class="h4 mb-0 font-weight-bold text-corrected">{{ $data['corrected'] }}</div>
                            <div class="small text-muted uppercase font-weight-bold" style="font-size: 0.65rem;">Düzeltme</div>
                        </div>
                        <div class="col-4 border-right">
                            <div class="h4 mb-0 font-weight-bold text-danger">{{ $data['rejected'] }}</div>
                            <div class="small text-muted uppercase font-weight-bold" style="font-size: 0.65rem;">Reddedilen</div>
                        </div>
                        <div class="col-4">
                            <div class="h4 mb-0 font-weight-bold text-pending">{{ $data['pending'] }}</div>
                            <div class="small text-muted uppercase font-weight-bold" style="font-size: 0.65rem;">Bekleyen</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="text-muted h4">Veri bulunamadı.</div>
        </div>
        @endforelse
    </div>
</div>
@endsection
