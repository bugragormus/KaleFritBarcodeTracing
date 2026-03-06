@extends('layouts.app')

@section('styles')
    <style>
        .page-header-granilya {
            background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.2);
        }
        .quality-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            transition: transform 0.2s;
        }
        .quality-card:hover { transform: translateY(-5px); }
        .rate-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            margin: 0 auto 1rem;
        }
        .rejection-reason {
            font-size: 0.85rem;
            background: #f1f5f9;
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
            margin-bottom: 0.3rem;
            display: flex;
            justify-content: space-between;
        }
        .rejection-count { font-weight: 700; color: #ef4444; }
    </style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="page-header-granilya">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h2 mb-1"><i class="fas fa-check-shield mr-2"></i> Stok Kalite Analizi</h1>
                <p class="mb-0 opacity-75">Ürün bazlı kalite istatistikleri ve red sebepleri</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('granilya.laboratory.stock_analysis_excel', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="btn btn-light">
                    <i class="fas fa-file-excel mr-1 text-success"></i> Excel İndir
                </a>
            </div>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="card quality-card">
        <div class="card-body">
            <form action="{{ route('granilya.laboratory.stock_analysis') }}" method="GET" class="row align-items-end">
                <div class="col-md-4">
                    <label class="small font-weight-bold text-muted">Başlangıç Tarihi</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="small font-weight-bold text-muted">Bitiş Tarihi</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-dark btn-block mt-3 mt-md-0">
                        <i class="fas fa-filter mr-1"></i> Analiz Et
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($stockQualityData as $data)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <div class="card h-100 quality-card">
                <div class="card-body text-center p-4">
                    <h5 class="mb-3 font-weight-bold">{{ $data['stock']->name }}</h5>
                    
                    @php
                        $color = $data['acceptance_rate'] >= 95 ? 'success' : ($data['acceptance_rate'] >= 85 ? 'warning' : 'danger');
                    @endphp
                    
                    <div class="rate-circle bg-light-{{ $color }} text-{{ $color }} border border-{{ $color }}">
                        {{ $data['acceptance_rate'] }}%
                    </div>
                    <p class="small text-muted mb-4 uppercase font-weight-bold">Kabul Oranı</p>
                    
                    <div class="row mb-3">
                        <div class="col-6 border-right">
                            <div class="h5 mb-0 font-weight-bold">{{ $data['accepted'] }}</div>
                            <div class="small text-muted">Kabul</div>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0 font-weight-bold text-danger">{{ $data['rejected'] }}</div>
                            <div class="small text-muted">Red</div>
                        </div>
                    </div>

                    @if(!empty($data['rejection_reasons']))
                        <div class="text-left mt-4 border-top pt-3">
                            <label class="small text-muted font-weight-bold">RED SEBEPLERİ</label>
                            @foreach($data['rejection_reasons'] as $reason => $count)
                                <div class="rejection-reason">
                                    <span>{{ $reason }}</span>
                                    <span class="rejection-count">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="mt-4 pt-3 border-top text-muted small">
                            Red kaydı bulunmuyor.
                        </div>
                    @endif
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
