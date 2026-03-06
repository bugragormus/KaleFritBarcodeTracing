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
        .quality-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }
        .quality-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0,0,0,0.12); }
        .rate-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.5rem;
            margin: 0 auto 1.5rem;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);
        }
        .rejection-reason {
            font-size: 0.9rem;
            background: #f8fafc;
            padding: 0.5rem 0.8rem;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #edf2f7;
        }
        .rejection-count { font-weight: 800; color: #ef4444; }

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
<div class="container py-4"> {{-- Changed container-fluid to container --}}
    <div class="page-header-granilya">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h2 mb-1 font-weight-bold"><i class="fas fa-microscope mr-3"></i> Stok Kalite Analizi</h1>
                <p class="mb-0 opacity-90 font-weight-500">Ürün bazlı kalite istatistikleri ve red sebepleri</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('granilya.laboratory.stock_analysis_excel', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="btn-excel-premium">
                    <i class="fas fa-file-excel"></i> Excel Olarak İndir
                </a>
            </div>
        </div>
    </div>

    {{-- Filtreler --}}
    <div class="card quality-card">
        <div class="card-body">
            <form action="{{ route('granilya.laboratory.stock_analysis') }}" method="GET" class="row align-items-end">
                <div class="col-md-4">
                    <label class="small font-weight-bold text-muted uppercase">Başlangıç Tarihi</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="small font-weight-bold text-muted uppercase">Bitiş Tarihi</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-dark btn-block mt-3 mt-md-0" style="padding: 0.8rem;">
                        <i class="fas fa-filter mr-1"></i> Analiz Et
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($stockQualityData as $data)
        <div class="col-xl-4 col-lg-6 mb-4"> {{-- Adjusted grid for better spacing --}}
            <div class="card h-100 quality-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="mb-0 font-weight-bold">{{ $data['stock']->name }}</h5>
                        <span class="badge badge-soft-dark px-2 py-1">Toplam: {{ $data['total'] }}</span>
                    </div>
                    
                    @php
                        $color = $data['acceptance_rate'] >= 95 ? 'success' : ($data['acceptance_rate'] >= 85 ? 'warning' : 'danger');
                    @endphp
                    
                    <div class="row align-items-center mb-4">
                        <div class="col-5 text-center">
                            <div class="rate-circle bg-light-{{ $color }} text-{{ $color }} border border-{{ $color }} mb-0">
                                {{ $data['acceptance_rate'] }}%
                            </div>
                            <p class="small text-muted mt-2 mb-0 uppercase font-weight-bold">Başarı</p>
                        </div>
                        <div class="col-7">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">Hatasız:</span>
                                <span class="font-weight-bold text-success">{{ $data['clean'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">İstisnai:</span>
                                <span class="font-weight-bold text-warning">{{ $data['exceptional'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">Düzeltme:</span>
                                <span class="font-weight-bold" style="color: #6366f1;">{{ $data['corrected'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">Red:</span>
                                <span class="font-weight-bold text-danger">{{ $data['rejected'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-top pt-1 mt-1">
                                <span class="text-muted small font-italic">Bekleyen:</span>
                                <span class="font-weight-bold text-secondary">{{ $data['pending'] }}</span>
                            </div>
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
