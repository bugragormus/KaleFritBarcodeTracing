@extends('layouts.app')

@section('styles')
    <style>
        .page-header-granilya {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
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
            height: 12px;
            border-radius: 6px;
            background: #e2e8f0;
            overflow: hidden;
            margin: 1.5rem 0;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
        }
        .acceptance-fill {
            height: 100%;
            transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
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
    </style>
@endsection

@section('content')
<div class="container-fluid py-4">
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

    <!-- Filtreler -->
    <div class="card crusher-card">
        <div class="card-body">
            <form action="{{ route('granilya.laboratory.crusher_performance') }}" method="GET" class="row align-items-end">
                <div class="col-md-4">
                    <label class="small font-weight-bold text-muted">Başlangıç Tarihi</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="small font-weight-bold text-muted">Bitiş Tarihi</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success btn-block mt-3 mt-md-0">
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
                        <div class="badge badge-soft-success px-3 py-2 h5 mb-0">
                            {{ $data['acceptance_rate'] }}% Başarı
                        </div>
                    </div>

                    <div class="acceptance-bar">
                        <div class="acceptance-fill bg-success" style="width: {{ $data['acceptance_rate'] }}%"></div>
                    </div>

                    <div class="row text-center mt-4">
                        <div class="col-4 border-right">
                            <div class="h3 mb-0 font-weight-bold text-dark">{{ $data['total'] }}</div>
                            <div class="small text-muted uppercase">Toplam Palet</div>
                        </div>
                        <div class="col-4 border-right">
                            <div class="h3 mb-0 font-weight-bold text-success">{{ $data['accepted'] }}</div>
                            <div class="small text-muted uppercase">Onaylanan</div>
                        </div>
                        <div class="col-4">
                            <div class="h3 mb-0 font-weight-bold text-danger">{{ $data['rejected'] }}</div>
                            <div class="small text-muted uppercase">Reddedilen</div>
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
