@extends('layouts.app')

@section('styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <style>
        body, .main-content, .modern-lab-report {
            background: #f8f9fa !important;
        }
        .modern-lab-report {
            background: #ffffff;
            min-height: 100vh;
            padding: 2rem 0;
        }
        .page-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
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
            color: #667eea;
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
        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-secondary-modern {
            background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%);
            color: white;
        }
        .btn-success-modern {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        .btn-warning-modern {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #212529;
    }
        .btn-info-modern {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
            color: white;
        }
        .btn-danger-modern {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }
        .table-modern {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        .table-modern thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-align: center;
        }
        .table-modern tbody td {
            padding: 1rem;
            border: none;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }
        .table-modern tbody tr:hover {
            background: #f8f9fa;
        }
        .chart-container {
            position: relative;
            height: 300px;
        }
        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
            .card-body-modern {
                padding: 1.2rem;
            }
        }
    </style>
@endsection

@section('content')
<div class="modern-lab-report">
    <div class="container-fluid">
        <!-- Modern Page Header -->
        <div class="page-header-modern">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title-modern">
                        <i class="fas fa-chart-bar"></i> Laboratuvar Raporu
                    </h1>
                    <p class="page-subtitle-modern">Laboratuvar işlem istatistikleri ve raporları - İş akışı kurallarına uygun</p>
                </div>
                <div class="col-md-4 text-right">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('laboratory.report.export', ['start_date' => request('start_date', $startDate->format('Y-m-d')), 'end_date' => request('end_date', $endDate->format('Y-m-d'))]) }}" class="btn-modern btn-success-modern mr-2">
                            <i class="fas fa-file-excel"></i> Excel İndir
                        </a>
                        <a href="{{ route('laboratory.dashboard') }}" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i> Geri Dön
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarih Filtreleri -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-calendar-alt"></i> Tarih Filtreleri
                </h3>
                <p class="card-subtitle-modern">
                    Raporları tarih aralığına göre filtreleyin
                </p>
            </div>
            <div class="card-body-modern">
                <form method="GET" action="{{ route('laboratory.report') }}" class="row align-items-end">
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-calendar"></i> Başlangıç Tarihi</label>
                            <input type="date" class="form-control" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-calendar-check"></i> Bitiş Tarihi</label>
                            <input type="date" class="form-control" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <button type="submit" class="btn-modern btn-primary-modern">
                                <i class="fas fa-filter"></i> Filtrele
                            </button>
                            <a href="{{ route('laboratory.report') }}" class="btn-modern btn-secondary-modern ml-2">
                                <i class="fas fa-times"></i> Temizle
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Özet İstatistikler -->
        <div class="row">
            <div class="col-xl-2 col-md-4">
                <div class="stat-card-modern">
                    <div class="stat-icon-modern text-primary mb-2">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-number-modern">{{ $summary['total_processed'] }}</div>
                    <div class="stat-label-modern">Toplam İşlenen</div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4">
                <div class="stat-card-modern">
                    <div class="stat-icon-modern text-warning mb-2">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-number-modern">{{ $summary['waiting'] }}</div>
                    <div class="stat-label-modern">Beklemede</div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4">
                <div class="stat-card-modern">
                    <div class="stat-icon-modern text-success mb-2">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-number-modern">{{ $summary['accepted'] }}</div>
                    <div class="stat-label-modern">Ön Onaylı</div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4">
                <div class="stat-card-modern">
                    <div class="stat-icon-modern text-info mb-2">
                        <i class="fas fa-redo"></i>
                    </div>
                    <div class="stat-number-modern">{{ $summary['control_repeat'] }}</div>
                    <div class="stat-label-modern">Kontrol Tekrarı</div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4">
                <div class="stat-card-modern">
                    <div class="stat-icon-modern text-primary mb-2">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="stat-number-modern">{{ $summary['shipment_approved'] }}</div>
                    <div class="stat-label-modern">Sevk Onaylı</div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4">
                <div class="stat-card-modern">
                    <div class="stat-icon-modern text-danger mb-2">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-number-modern">{{ $summary['rejected'] }}</div>
                    <div class="stat-label-modern">Reddedildi</div>
                </div>
            </div>
        </div>

        <!-- Grafikler -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern"><i class="fas fa-chart-pie"></i> Durum Dağılımı</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="chart-container">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern"><i class="fas fa-chart-line"></i> Günlük İşlem Sayısı</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="chart-container">
                            <canvas id="dailyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Personel Performans Raporu -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern"><i class="fas fa-user-tie"></i> Personel Performans Raporu</h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Personel</th>
                                <th>Toplam İşlem</th>
                                <th>Kabul Edilen</th>
                                <th>Red Edilen</th>
                                <th>Kabul Oranı</th>
                                <th>Son İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $personnelStats = $report->groupBy('lab_by')->map(function($items) {
                                    return [
                                        'total' => $items->sum('count'),
                                        'pre_approved' => $items->where('status', \App\Models\Barcode::STATUS_SHIPMENT_APPROVED)->sum('count'),
                                        'rejected' => $items->where('status', \App\Models\Barcode::STATUS_REJECTED)->sum('count'),
                                        'last_activity' => $items->max('lab_date')
                                    ];
                                });
                            @endphp
                            @forelse($personnelStats as $userId => $stats)
                                @php
                                    $user = \App\Models\User::find($userId);
                                    $acceptanceRate = $stats['total'] > 0 ? round(($stats['pre_approved'] / $stats['total']) * 100, 1) : 0;
                                @endphp
                                <tr>
                                    <td>
                                        @if($user)
                                            <strong>{{ $user->name }}</strong>
                                        @else
                                            <span class="text-muted">Kullanıcı bulunamadı</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $stats['total'] }}</strong></td>
                                    <td><span class="text-success">{{ $stats['pre_approved'] }}</span></td>
                                    <td><span class="text-danger">{{ $stats['rejected'] }}</span></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" style="width: {{ $acceptanceRate }}%">
                                                {{ $acceptanceRate }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($stats['last_activity'])
                                            {{ \Carbon\Carbon::parse($stats['last_activity'])->format('d.m.Y H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Personel performans verisi bulunamadı
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <!-- Red Sebepleri Analizi -->
    <div class="card-modern">
        <div class="card-header-modern">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title-modern">
                        <i class="fas fa-exclamation-triangle"></i> Red Sebepleri Analizi
                    </h3>
                    <p class="card-subtitle-modern">Seçilen tarih aralığında red edilen ürünlerin sebeplere göre dağılımı</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('laboratory.stock-quality-analysis') }}" class="btn-modern btn-success-modern mr-2">
                        <i class="fas fa-chart-line"></i> Stok Kalite Analizi
                    </a>
                    <a href="{{ route('laboratory.kiln-performance') }}" class="btn-modern btn-warning-modern">
                        <i class="fas fa-fire"></i> Fırın Performans Analizi
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body-modern">
            <!-- Genel Red Sebepleri İstatistikleri -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="mb-3"><i class="fas fa-chart-pie"></i> Genel Red Sebepleri Dağılımı</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Red Sebebi</th>
                                    <th>Red Edilen Ürün Sayısı</th>
                                    <th>Toplam KG</th>
                                    <th>Oran (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalRejectedCount = $summary['rejected'];
                                    $totalRejectedKg = $generalRejectionStats->sum('total_kg');
                                @endphp
                                @foreach($generalRejectionStats as $reasonName => $stats)
                                    <tr>
                                        <td>
                                            <span class="badge badge-danger">{{ $reasonName }}</span>
                                        </td>
                                        <td>{{ $stats['count'] }}</td>
                                        <td>{{ number_format($stats['total_kg'], 2) }} KG</td>
                                        <td>
                                            @if($totalRejectedCount > 0)
                                                {{ number_format(($stats['count'] / $totalRejectedCount) * 100, 1) }}%
                                            @else
                                                0%
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Stok Bazında Red Sebepleri -->
            <div class="row">
                <div class="col-12">
                    <h5 class="mb-3"><i class="fas fa-boxes"></i> Stok Bazında Red Sebepleri</h5>
                    @foreach($rejectionReasonsAnalysis as $stockId => $analysis)
                        <div class="card mb-3" style="border-left: 4px solid #dc3545;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h6 class="card-title text-danger">
                                            <i class="fas fa-box"></i> {{ $analysis['stock']->name }}
                                        </h6>
                                        <p class="text-muted mb-1">Kod: {{ $analysis['stock']->code }}</p>
                                        <p class="mb-0">
                                            <span class="badge badge-danger">
                                                Toplam Red: {{ $analysis['total_rejected'] }} ürün 
                                                ({{ number_format($analysis['total_rejected_kg'], 2) }} KG)
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            @foreach($analysis['reasons_breakdown'] as $reasonName => $reasonStats)
                                                <div class="col-md-6 mb-2">
                                                    <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                                        <span class="badge badge-danger">{{ $reasonName }}</span>
                                                        <span class="text-muted">
                                                            {{ $reasonStats['count'] }} ürün 
                                                            ({{ number_format($reasonStats['kg'], 2) }} KG)
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    initializeCharts();
});

function initializeCharts() {
    // Durum dağılımı grafiği
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Beklemede', 'Ön Onaylı', 'Kontrol Tekrarı', 'Sevk Onaylı', 'Reddedildi'],
            datasets: [{
                data: [
                    {{ $summary['waiting'] }}, 
                    {{ $summary['accepted'] }}, 
                    {{ $summary['control_repeat'] }}, 
                    {{ $summary['shipment_approved'] }}, 
                    {{ $summary['rejected'] }}
                ],
                backgroundColor: ['#ffc107', '#28a745', '#17a2b8', '#007bff', '#dc3545'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Günlük işlem sayısı grafiği
    const dailyCtx = document.getElementById('dailyChart').getContext('2d');
    @php
        $dailyData = $report->groupBy('lab_date')->map(function($items) {
            return $items->sum('count');
        });
    @endphp
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyData->keys()->map(function($date) { return \Carbon\Carbon::parse($date)->format('d.m'); })->values()) !!},
            datasets: [{
                label: 'Günlük İşlem Sayısı',
                data: {!! json_encode($dailyData->values()) !!},
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}
</script>
@endsection 