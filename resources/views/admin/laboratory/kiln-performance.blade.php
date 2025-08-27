@extends('layouts.app')

@section('styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <style>
        body, .main-content, .modern-kiln-performance {
            background: #f8f9fa !important;
        }
        .modern-kiln-performance {
            background: #ffffff;
            min-height: 100vh;
            padding: 2rem 0;
        }
        .page-header-modern {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);
        }
        .page-title-modern {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        .page-title-modern i {
            margin-right: 1rem;
            font-size: 2rem;
        }
        .page-subtitle-modern {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }
        .card-modern {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .card-header-modern {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e9ecef;
        }
        .card-title-modern {
            font-size: 1.3rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        .card-title-modern i {
            margin-right: 0.5rem;
            color: #ff6b6b;
        }
        .card-subtitle-modern {
            color: #6c757d;
            margin-bottom: 0;
        }
        .card-body-modern {
            padding: 2rem;
        }
        .stat-card-modern {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
            background: #fff;
            padding: 2rem 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e9ecef;
            text-align: center;
            transition: transform 0.2s;
        }
        .stat-card-modern:hover {
            transform: translateY(-2px);
        }
        .stat-icon-modern {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        .stat-label-modern {
            color: #6c757d;
            font-size: 1rem;
            margin-bottom: 0;
        }
        .stat-number-modern {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .btn-modern {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .btn-success-modern {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        .btn-info-modern {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        .btn-warning-modern {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
        }
        .btn-danger-modern {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        .performance-indicator {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .performance-excellent { background: #d4edda; color: #155724; }
        .performance-good { background: #d1ecf1; color: #0c5460; }
        .performance-average { background: #fff3cd; color: #856404; }
        .performance-poor { background: #f8d7da; color: #721c24; }
        .performance-critical { background: #f5c6cb; color: #721c24; }
        .kiln-card {
            border-left: 4px solid #007bff;
            transition: all 0.3s ease;
        }
        .kiln-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .kiln-card.high-performance {
            border-left-color: #28a745;
        }
        .kiln-card.medium-performance {
            border-left-color: #ffc107;
        }
        .kiln-card.low-performance {
            border-left-color: #dc3545;
        }
        .efficiency-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="modern-kiln-performance">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-fire"></i> Fırın Performans Analizi
                        </h1>
                        <p class="page-subtitle-modern">Fırınların kalite performansı ve red sebepleri analizi</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('laboratory.report') }}" class="btn-modern btn-info-modern mr-2">
                                <i class="fas fa-chart-bar"></i> Lab Raporu
                            </a>
                            <a href="{{ route('laboratory.stock-quality-analysis') }}" class="btn-modern btn-success-modern">
                                <i class="fas fa-chart-line"></i> Stok Analizi
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarih Filtreleme -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-calendar-alt"></i> Tarih Filtreleme
                    </h3>
                    <p class="card-subtitle-modern">Analiz edilecek tarih aralığını seçin</p>
                </div>
                <div class="card-body-modern">
                    <form method="GET" action="{{ route('laboratory.kiln-performance') }}" class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Başlangıç Tarihi</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Bitiş Tarihi</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn-modern btn-success-modern d-block w-100">
                                    <i class="fas fa-search"></i> Filtrele
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Genel Performans İstatistikleri -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card-modern">
                        <div class="stat-icon-modern text-primary">
                            <i class="fas fa-fire"></i>
                        </div>
                        <div class="stat-number-modern text-primary">{{ $overallStats['total_kilns'] }}</div>
                        <div class="stat-label-modern">Analiz Edilen Fırın</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card-modern">
                        <div class="stat-icon-modern text-success">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-number-modern text-success">{{ number_format($overallStats['overall_efficiency_rate'], 1) }}%</div>
                        <div class="stat-label-modern">Genel Verimlilik</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card-modern">
                        <div class="stat-icon-modern text-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stat-number-modern text-danger">{{ number_format($overallStats['overall_rejection_rate'], 1) }}%</div>
                        <div class="stat-label-modern">Genel Red Oranı</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card-modern">
                        <div class="stat-icon-modern text-info">
                            <i class="fas fa-weight-hanging"></i>
                        </div>
                        <div class="stat-number-modern text-info">{{ number_format($overallStats['total_rejected_kg'], 1) }}</div>
                        <div class="stat-label-modern">Toplam Red KG</div>
                    </div>
                </div>
            </div>

            <!-- Fırın Performans Detayları -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-fire"></i> Fırın Bazında Performans Analizi
                    </h3>
                    <p class="card-subtitle-modern">Her fırın için detaylı performans metrikleri ve red sebepleri</p>
                </div>
                <div class="card-body-modern">
                    @foreach($kilnPerformanceData as $data)
                        @php
                            $performanceClass = '';
                            if ($data['efficiency_rate'] >= 90) {
                                $performanceClass = 'high-performance';
                            } elseif ($data['efficiency_rate'] >= 80) {
                                $performanceClass = 'medium-performance';
                            } else {
                                $performanceClass = 'low-performance';
                            }
                            
                            $qualityClass = '';
                            if ($data['acceptance_rate'] >= 90) {
                                $qualityClass = 'performance-excellent';
                            } elseif ($data['acceptance_rate'] >= 80) {
                                $qualityClass = 'performance-good';
                            } elseif ($data['acceptance_rate'] >= 70) {
                                $qualityClass = 'performance-average';
                            } elseif ($data['acceptance_rate'] >= 60) {
                                $qualityClass = 'performance-poor';
                            } else {
                                $qualityClass = 'performance-critical';
                            }
                        @endphp
                        
                        <div class="card kiln-card {{ $performanceClass }} mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <h6 class="card-title">
                                            <i class="fas fa-fire"></i> {{ $data['kiln']->name }}
                                        </h6>
                                        <p class="text-muted mb-1">Şarj No: {{ $data['kiln']->load_number ?? 'N/A' }}</p>
                                        <span class="quality-indicator {{ $qualityClass }}">
                                            {{ number_format($data['acceptance_rate'], 1) }}% Kabul
                                        </span>
                                        <div class="mt-2">
                                            <span class="badge badge-info efficiency-badge">
                                                Verimlilik: {{ number_format($data['efficiency_rate'], 1) }}%
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="text-success">
                                                    <strong>{{ $data['accepted_barcodes'] }}</strong>
                                                    <div class="small text-muted">Kabul</div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-danger">
                                                    <strong>{{ $data['rejected_barcodes'] }}</strong>
                                                    <div class="small text-muted">Red</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="text-info">
                                                    <strong>{{ number_format($data['accepted_kg'], 1) }}</strong>
                                                    <div class="small text-muted">Kabul KG</div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-danger">
                                                    <strong>{{ number_format($data['rejected_kg'], 1) }}</strong>
                                                    <div class="small text-muted">Red KG</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <div class="text-primary mb-2">
                                                <strong>{{ number_format($data['daily_average'], 1) }}</strong>
                                                <div class="small text-muted">Günlük Ortalama</div>
                                            </div>
                                            @if(count($data['rejection_reasons']) > 0)
                                                <div class="mb-2">
                                                    <strong class="text-danger">Ana Red Sebebi:</strong>
                                                    <div class="small">{{ $data['top_rejection_reason'] }}</div>
                                                </div>
                                                <div class="small text-muted">
                                                    {{ $data['top_rejection_count'] }} kez tekrarlandı
                                                </div>
                                            @else
                                                <div class="text-success">
                                                    <i class="fas fa-check"></i> Red yok
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                @if(count($data['rejection_reasons']) > 0)
                                    <div class="mt-3 pt-3 border-top">
                                        <h6 class="text-danger mb-2">
                                            <i class="fas fa-exclamation-triangle"></i> Red Sebepleri Detayı
                                        </h6>
                                        <div class="row">
                                            @foreach($data['rejection_reasons'] as $reasonName => $reasonStats)
                                                <div class="col-md-4 mb-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="badge badge-danger">{{ $reasonName }}</span>
                                                        <span class="text-muted">
                                                            {{ $reasonStats['count'] }} ürün 
                                                            ({{ number_format($reasonStats['kg'], 1) }} KG)
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Tarih filtreleme için otomatik submit
    $('input[type="date"]').on('change', function() {
        $(this).closest('form').submit();
    });
});
</script>
@endsection
