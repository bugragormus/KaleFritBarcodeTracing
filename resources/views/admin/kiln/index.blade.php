@extends('layouts.app')

@section('styles')
    <style>
        body, .main-content, .modern-kiln-management {
            background: #f8f9fa !important;
        }
        .modern-kiln-management {
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
        
        .btn-danger-modern {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }
        
        .btn-info-modern {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        
        .btn-warning-modern {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
        }
        
        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .kiln-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
            overflow: hidden;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .kiln-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .kiln-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            position: relative;
        }
        
        .kiln-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .kiln-load-number {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .kiln-daily-average {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 0.25rem;
        }
        
        .kiln-body {
            padding: 1.5rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-item {
            text-align: center;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }
        
        .progress-section {
            margin-bottom: 1.5rem;
        }
        
        .progress-item {
            margin-bottom: 1rem;
        }
        
        .progress-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .progress-bar-custom {
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        
        .progress-success { background: linear-gradient(90deg, #28a745, #20c997); }
        .progress-warning { background: linear-gradient(90deg, #ffc107, #fd7e14); }
        .progress-danger { background: linear-gradient(90deg, #dc3545, #fd7e14); }
        .progress-info { background: linear-gradient(90deg, #17a2b8, #138496); }
        
        .status-distribution {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .status-item {
            text-align: center;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-waiting { background: #fff3cd; color: #856404; }
        .status-control-repeat { background: #ffeaa7; color: #856404; }
        .status-pre-approved { background: #d1edff; color: #0c5460; }
        .status-shipment-approved { background: #d4edda; color: #155724; }
        .status-customer-transfer { background: #e2d9f3; color: #6f42c1; }
        .status-delivered { background: #c3e6cb; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        .status-merged { background: #f8f9fa; color: #6c757d; }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
            flex-wrap: wrap;
        }
        
        .btn-xs-modern {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 8px;
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 8px;
        }
        
        .quick-filters {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 1rem;
        }
        
        .quick-filters .btn-modern {
            margin-bottom: 0.25rem;
        }
        
        .performance-indicator {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
        }

        .status-item > div {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .status-item small {
            font-size: 0.75rem;
            opacity: 0.8;
        }
        
        .performance-excellent { background: #28a745; }
        .performance-good { background: #17a2b8; }
        .performance-average { background: #ffc107; }
        .performance-poor { background: #dc3545; }
        
        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .status-distribution {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .action-buttons {
                justify-content: center;
            }
            
            .action-buttons .btn-modern {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="modern-kiln-management">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-fire"></i> Fırın Yönetimi
                        </h1>
                        <p class="page-subtitle-modern">Fırın performanslarını analiz edin, üretim verilerini takip edin ve kalite kontrolü yapın</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{ route('kiln.create') }}" class="btn-modern btn-success-modern">
                            <i class="fas fa-plus"></i> Yeni Fırın Ekle
                        </a>
                    </div>
                </div>
            </div>

                    <!-- Tarih Filtreleme -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-calendar-alt"></i> Tarih Filtreleme
                </h3>
                <p class="card-subtitle-modern">Belirli tarih aralığındaki fırın performansını görüntüleyin</p>
            </div>
            <div class="card-body-modern">
                <!-- Hızlı Filtre Butonları -->
                <div class="quick-filters mb-3">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('kiln.index') }}" class="btn-modern btn-sm {{ !request('period') ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                            <i class="fas fa-calendar-day"></i> Günlük
                        </a>
                        <a href="{{ route('kiln.index', ['period' => 'monthly']) }}" class="btn-modern btn-sm {{ request('period') == 'monthly' ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                            <i class="fas fa-calendar-alt"></i> Aylık
                        </a>
                        <a href="{{ route('kiln.index', ['period' => 'quarterly']) }}" class="btn-modern btn-sm {{ request('period') == 'quarterly' ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                            <i class="fas fa-calendar-week"></i> 3 Aylık
                        </a>
                        <a href="{{ route('kiln.index', ['period' => 'yearly']) }}" class="btn-modern btn-sm {{ request('period') == 'yearly' ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                            <i class="fas fa-calendar"></i> Yıllık
                        </a>
                        <a href="{{ route('kiln.index', ['period' => 'all']) }}" class="btn-modern btn-sm {{ request('period') == 'all' ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                            <i class="fas fa-infinity"></i> Tüm Zamanlar
                        </a>
                    </div>
                </div>

                <form method="GET" action="{{ route('kiln.index') }}" class="row align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Başlangıç Tarihi</label>
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ request('start_date') }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bitiş Tarihi</label>
                        <input type="date" name="end_date" class="form-control" 
                               value="{{ request('end_date') }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fırın Seçimi</label>
                        <select name="kiln_id" class="form-control">
                            <option value="">Tüm Fırınlar</option>
                            @foreach($kilns as $kilnOption)
                                <option value="{{ $kilnOption->id }}" {{ request('kiln_id') == $kilnOption->id ? 'selected' : '' }}>
                                    {{ $kilnOption->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn-modern btn-primary-modern w-100">
                            <i class="fas fa-filter"></i> Filtrele
                        </button>
                    </div>
                </form>
                @if(request('start_date') || request('end_date') || request('kiln_id') || request('period'))
                    <div class="mt-3">
                        <a href="{{ route('kiln.index') }}" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-times"></i> Filtreleri Temizle
                        </a>
                        <span class="ml-3 text-muted">
                            <i class="fas fa-info-circle"></i>
                            @if(request('period'))
                                @php
                                    $periodNames = [
                                        'monthly' => 'Aylık',
                                        'quarterly' => '3 Aylık',
                                        'yearly' => 'Yıllık',
                                        'all' => 'Tüm Zamanlar'
                                    ];
                                @endphp
                                {{ $periodNames[request('period')] ?? 'Günlük' }} görünüm
                            @endif
                            @if(request('start_date') && request('end_date'))
                                - {{ request('start_date') }} - {{ request('end_date') }} tarihleri arası
                            @endif
                            @if(request('kiln_id'))
                                @php
                                    $selectedKiln = $kilns->firstWhere('id', request('kiln_id'));
                                @endphp
                                @if($selectedKiln)
                                    - {{ $selectedKiln->name }} fırını
                                @endif
                            @endif
                            için filtrelenmiş sonuçlar
                        </span>
                    </div>
                @endif
            </div>
        </div>

            <!-- Fırın Performans Kartları -->
            @foreach($kilns as $kiln)
            <div class="kiln-card">
                <div class="kiln-header">
                    <div class="performance-indicator 
                        @if($kiln->rejection_rate <= 5) performance-excellent
                        @elseif($kiln->rejection_rate <= 10) performance-good
                        @elseif($kiln->rejection_rate <= 20) performance-average
                        @else performance-poor
                        @endif">
                    </div>
                    <div class="kiln-name">{{ $kiln->name }}</div>
                    <div class="kiln-load-number">Yük Numarası: {{ $kiln->load_number ?? 'Belirtilmemiş' }}</div>
                    <div class="kiln-daily-average">Günlük Ortalama: {{ number_format($kiln->daily_production_average ?? 0, 2) }} Ton</div>
                </div>
                
                <div class="kiln-body">
                    <!-- Temel İstatistikler -->
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($kiln->total_production, 0) }}</div>
                            <div class="stat-label">Toplam Üretim (KG)</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($kiln->last_30_days_production, 0) }}</div>
                            <div class="stat-label">Son 30 Gün (KG)</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($kiln->daily_production_average ?? 0, 2) }}</div>
                            <div class="stat-label">Hedef Günlük (Ton)</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($kiln->daily_average_30_days, 2) }}</div>
                            <div class="stat-label">Son 30 Gün Ort. (KG)</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($kiln->monthly_average_12_months, 2) }}</div>
                            <div class="stat-label">Son 12 Ay Ort. (KG)</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $kiln->rejection_rate }}%</div>
                            <div class="stat-label">Red Oranı</div>
                        </div>
                    </div>

                    <!-- Performans Göstergeleri -->
                    <div class="progress-section">
                        <div class="progress-item">
                            <div class="progress-label">
                                <span>Red Oranı</span>
                                <span>{{ $kiln->rejection_rate }}%</span>
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill 
                                    @if($kiln->rejection_rate <= 5) progress-success
                                    @elseif($kiln->rejection_rate <= 10) progress-info
                                    @elseif($kiln->rejection_rate <= 20) progress-warning
                                    @else progress-danger
                                    @endif"
                                    style="width: {{ min($kiln->rejection_rate, 100) }}%">
                                </div>
                            </div>
                        </div>
                        

                    </div>

                    <!-- Durum Dağılımı -->
                    <div class="status-distribution">
                        <div class="status-item status-waiting">
                            <div>{{ number_format($kiln->waiting_kg ?? 0, 0) }}</div>
                            <small>Beklemede (KG)</small>
                        </div>
                        <div class="status-item status-control-repeat">
                            <div>{{ number_format($kiln->control_repeat_kg ?? 0, 0) }}</div>
                            <small>Kontrol Tekrarı (KG)</small>
                        </div>
                        <div class="status-item status-pre-approved">
                            <div>{{ number_format($kiln->pre_approved_kg ?? 0, 0) }}</div>
                            <small>Ön Onaylı (KG)</small>
                        </div>
                        <div class="status-item status-shipment-approved">
                            <div>{{ number_format($kiln->shipment_approved_kg ?? 0, 0) }}</div>
                            <small>Sevk Onaylı (KG)</small>
                        </div>
                        <div class="status-item status-customer-transfer">
                            <div>{{ number_format($kiln->customer_transfer_kg ?? 0, 0) }}</div>
                            <small>Müşteri Transfer (KG)</small>
                        </div>
                        <div class="status-item status-delivered">
                            <div>{{ number_format($kiln->delivered_kg ?? 0, 0) }}</div>
                            <small>Teslim Edildi (KG)</small>
                        </div>
                        <div class="status-item status-rejected">
                            <div>{{ number_format($kiln->rejected_kg ?? 0, 0) }}</div>
                            <small>Reddedildi (KG)</small>
                        </div>
                        <div class="status-item status-merged">
                            <div>{{ number_format($kiln->merged_kg ?? 0, 0) }}</div>
                            <small>Birleştirildi (KG)</small>
                        </div>
                    </div>

                    <!-- İşlem Butonları -->
                                    <div class="action-buttons">
                                        <a href="{{ route('kiln.edit', ['firin' => $kiln->id]) }}" class="btn-modern btn-success-modern btn-xs-modern">
                                            <i class="fas fa-edit"></i> Düzenle
                                        </a>
                        <a href="{{ route('kiln.analysis', ['firin' => $kiln->id]) }}" class="btn-modern btn-info-modern btn-xs-modern">
                            <i class="fas fa-chart-bar"></i> Detaylı Analiz
                        </a>
                        <a href="{{ route('kiln.download.report', ['firin' => $kiln->id]) }}" class="btn-modern btn-warning-modern btn-xs-modern">
                            <i class="fas fa-file-excel"></i> Rapor İndir
                        </a>
                                        <button class="btn-modern btn-danger-modern btn-xs-modern" data-id="{{ $kiln->id }}" data-action="{{ route('kiln.destroy', $kiln->id) }}" onclick='deleteConfirmation("{{$kiln->id}}")'>
                                            <i class="fas fa-trash"></i> Sil
                                        </button>
                                    </div>
                </div>
            </div>
                            @endforeach

            @if($kilns->isEmpty())
            <div class="card-modern">
                <div class="card-body-modern text-center">
                    <i class="fas fa-fire" style="font-size: 4rem; color: #e9ecef; margin-bottom: 1rem;"></i>
                    <h4>Henüz fırın kaydı bulunmuyor</h4>
                    <p class="text-muted">İlk fırınınızı ekleyerek başlayın</p>
                    <a href="{{ route('kiln.create') }}" class="btn-modern btn-success-modern">
                        <i class="fas fa-plus"></i> İlk Fırını Ekle
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
        });
        function deleteConfirmation(id) {

            swal({
                title: "Silmek istediğinize emin misiniz?",
                text: "Silme işlemi geri alınamaz!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn btn-danger btn-lg',
                confirmButtonText: "Sil",
                cancelButtonClass: 'btn btn-primary btn-lg m-l-10',
                cancelButtonText: "Vazgeç",
                buttonsStyling: false
            }).then(function (e) {
                if (e.value === true) {
                    var data = {
                        "_token": $('input[name="_token"]').val(),
                        "id": id
                    }

                    $.ajax({
                        type: 'DELETE',
                        url: "{{url('/firin')}}/" + id,
                        data: data,
                        success: function (results) {
                            if (results.success) {
                                swal("Başarılı!", results.message, "success");
                                location.reload();
                            } else {
                                swal("Hata!", results.message, "error");
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                // Validation error or constraint violation
                                var response = xhr.responseJSON;
                                swal("Silinemez!", response.message, "warning");
                            } else if (xhr.status === 500) {
                                // Server error
                                var response = xhr.responseJSON;
                                swal("Sunucu Hatası!", response.message || "Beklenmeyen bir hata oluştu.", "error");
                            } else {
                                swal("Hata!", "Silme işlemi sırasında bir hata oluştu.", "error");
                            }
                        }
                    });

                } else {
                    e.dismiss;
                }

            }, function (dismiss) {
                return false;
            })
        }
    </script>
@endsection
