@extends('layouts.app')

@section('styles')
    <style>
        .page-header-granilya {
            background: linear-gradient(135deg, #065f46 0%, #10b981 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: 0 10px 25px rgba(6, 95, 70, 0.2);
        }
        .crusher-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        .acceptance-bar {
            height: 10px;
            border-radius: 5px;
            background: #e2e8f0;
            overflow: hidden;
            margin: 1rem 0;
        }
        .acceptance-fill {
            height: 100%;
            transition: width 0.5s ease-in-out;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="page-header-granilya">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h2 mb-1"><i class="fas fa-tools mr-2"></i> Kırıcı Performans Analizi</h1>
                <p class="mb-0 opacity-75">Kırıcı bazlı üretim kalitesi ve verimlilik analizi</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('granilya.laboratory.crusher_performance_excel', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="btn btn-light">
                    <i class="fas fa-file-excel mr-1 text-success"></i> Excel İndir
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
