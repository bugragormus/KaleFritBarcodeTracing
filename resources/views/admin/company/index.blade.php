@extends('layouts.app')

@section('styles')
    <style>
        body, .main-content, .modern-company-management {
            background: #f8f9fa !important;
        }
        .modern-company-management {
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
        
        .company-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
            overflow: hidden;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .company-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .company-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            position: relative;
        }
        
        .company-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .company-address {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .company-body {
            padding: 1.5rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-item {
            text-align: center;
            padding: 1rem;
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
            font-size: 0.8rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
        
        .status-customer-transfer { background: #e2d9f3; color: #6f42c1; }
        .status-delivered { background: #c3e6cb; color: #155724; }

        .status-item > div {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .status-item small {
            font-size: 0.75rem;
            opacity: 0.8;
        }
        
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
    <div class="modern-company-management">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-briefcase"></i> Firma Yönetimi
                        </h1>
                        <p class="page-subtitle-modern">Firma performanslarını analiz edin, alım verilerini takip edin ve müşteri memnuniyetini ölçün</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="d-flex gap-4 justify-content-end">
                        <a href="{{ route('company.create') }}" class="btn-modern btn-success-modern mr-2">
                                <i class="fas fa-plus"></i> Yeni Firma Ekle
                            </a>
                            <a href="{{ route('company.excel.download') }}" class="btn-modern btn-warning-modern">
                                <i class="fas fa-file-excel"></i>Excel İndir
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
                    <p class="card-subtitle-modern">Belirli tarih aralığındaki firma performansını görüntüleyin</p>
                </div>
                <div class="card-body-modern">
                    <!-- Hızlı Filtre Butonları -->
                    <div class="quick-filters mb-3">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('company.index') }}" class="btn-modern btn-sm {{ !request('period') ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                                <i class="fas fa-calendar-day"></i> Günlük
                            </a>
                            <a href="{{ route('company.index', ['period' => 'monthly']) }}" class="btn-modern btn-sm {{ request('period') == 'monthly' ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                                <i class="fas fa-calendar-alt"></i> Aylık
                            </a>
                            <a href="{{ route('company.index', ['period' => 'quarterly']) }}" class="btn-modern btn-sm {{ request('period') == 'quarterly' ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                                <i class="fas fa-calendar-week"></i> 3 Aylık
                            </a>
                            <a href="{{ route('company.index', ['period' => 'yearly']) }}" class="btn-modern btn-sm {{ request('period') == 'yearly' ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                                <i class="fas fa-calendar"></i> Yıllık
                            </a>
                            <a href="{{ route('company.index', ['period' => 'all']) }}" class="btn-modern btn-sm {{ request('period') == 'all' ? 'btn-primary-modern' : 'btn-secondary-modern' }}">
                                <i class="fas fa-infinity"></i> Tüm Zamanlar
                            </a>
                        </div>
                    </div>

                    <!-- Arama Çubuğu -->
                    <div class="mb-4">
                        <form id="company-search-form" method="GET" action="{{ route('company.index') }}" class="row align-items-end">
                            @if(request('start_date'))
                                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                            @endif
                            @if(request('end_date'))
                                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                            @endif
                            @if(request('period'))
                                <input type="hidden" name="period" value="{{ request('period') }}">
                            @endif
                            <div class="col-md-12">
                                <label class="form-label">Firma Adına Göre Arama</label>
                                <input type="text" 
                                       id="company-search-input"
                                       name="name" 
                                       class="form-control" 
                                       placeholder="Firma adına göre arama yapın..." 
                                       value="{{ request('name') }}">
                            </div>
                        </form>
                        @if(request('name'))
                            <div class="mt-2">
                                <span class="text-muted">
                                    <i class="fas fa-search"></i>
                                    "{{ request('name') }}" için arama sonuçları
                                </span>
                            </div>
                        @endif
                    </div>

                    <form method="GET" action="{{ route('company.index') }}" class="row align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Başlangıç Tarihi</label>
                            <input type="date" name="start_date" class="form-control" 
                                   value="{{ request('start_date') }}" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bitiş Tarihi</label>
                            <input type="date" name="end_date" class="form-control" 
                                   value="{{ request('end_date') }}" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn-modern btn-primary-modern w-100">
                                <i class="fas fa-filter"></i> Filtrele
                            </button>
                        </div>
                    </form>
                    @if(request('start_date') || request('end_date') || request('period'))
                        <div class="mt-3">
                            <a href="{{ route('company.index') }}" class="btn-modern btn-secondary-modern">
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
                                için filtrelenmiş sonuçlar
                            </span>
                        </div>
                    @endif


                </div>
            </div>



            <!-- Firma Performans Kartları -->
            <div class="row">
            @foreach($companies as $company)
            <div class="col-md-6 mb-3">
            <div class="company-card">
                <div class="company-header">
                    <div class="performance-indicator 
                        @if($company->delivery_rate >= 90) performance-excellent
                        @elseif($company->delivery_rate >= 75) performance-good
                        @elseif($company->delivery_rate >= 50) performance-average
                        @else performance-poor
                        @endif">
                    </div>
                    <div class="company-name">{{ $company->name }}</div>
                    <div class="company-address">{{ $company->address }}</div>
                </div>
                
                <div class="company-body">
                    <!-- Temel İstatistikler -->
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($company->total_purchase, 0) }}</div>
                            <div class="stat-label">Toplam Satış (KG)</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($company->last_30_days_purchase, 0) }}</div>
                            <div class="stat-label">Son 30 Gün (KG)</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $company->barcodes_count }}</div>
                            <div class="stat-label">Toplam Sipariş</div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-value">{{ $company->delivery_rate }}%</div>
                            <div class="stat-label">Teslim Oranı</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">
                                @if($company->last_purchase_date)
                                    {{ \Carbon\Carbon::parse($company->last_purchase_date)->format('d.m.Y') }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="stat-label">Son Teslim Tarihi</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($company->average_order_size, 0) }}</div>
                            <div class="stat-label">Ort. Sipariş (KG)</div>
                        </div>
                    </div>

                    <!-- Performans Göstergeleri -->
                    <div class="progress-section">
                        <div class="progress-item">
                            <div class="progress-label">
                                <span>Teslim Oranı</span>
                                <span>{{ $company->delivery_rate }}%</span>
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill 
                                    @if($company->delivery_rate >= 90) progress-success
                                    @elseif($company->delivery_rate >= 75) progress-info
                                    @elseif($company->delivery_rate >= 50) progress-warning
                                    @else progress-danger
                                    @endif"
                                    style="width: {{ $company->delivery_rate }}%">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Durum Dağılımı -->
                    <div class="status-distribution">
                        <div class="status-item status-customer-transfer">
                            <div>{{ number_format($company->customer_transfer_kg ?? 0, 0) }}</div>
                            <small>Müşteri Transfer (KG)</small>
                        </div>
                        <div class="status-item status-delivered">
                            <div>{{ number_format($company->delivered_kg ?? 0, 0) }}</div>
                            <small>Teslim Edildi (KG)</small>
                        </div>
                    </div>

                    <!-- İşlem Butonları -->
                    <div class="action-buttons">
                        <a href="{{ route('company.edit', ['firma' => $company->id]) }}" class="btn-modern btn-success-modern btn-xs-modern">
                            <i class="fas fa-edit"></i> Düzenle
                        </a>
                        <a href="{{ route('company.analysis', ['firma' => $company->id]) }}" class="btn-modern btn-info-modern btn-xs-modern">
                            <i class="fas fa-chart-bar"></i> Detaylı Analiz
                        </a>
                        <a href="{{ route('company.download.report', ['firma' => $company->id]) }}" class="btn-modern btn-warning-modern btn-xs-modern">
                            <i class="fas fa-file-excel"></i> Rapor İndir
                        </a>
                        <button class="btn-modern btn-danger-modern btn-xs-modern" data-id="{{ $company->id }}" data-action="{{ route('company.destroy', $company->id) }}" onclick='deleteConfirmation("{{$company->id}}")'>
                            <i class="fas fa-trash"></i> Sil
                        </button>
                    </div>
                </div>
            </div>
            </div>
            @endforeach
            </div>

            @if($companies->isEmpty())
            <div class="card-modern">
                <div class="card-body-modern text-center">
                    <i class="fas fa-briefcase" style="font-size: 4rem; color: #e9ecef; margin-bottom: 1rem;"></i>
                    <h4>Henüz firma kaydı bulunmuyor</h4>
                    <p class="text-muted">İlk firmanızı ekleyerek başlayın</p>
                    <a href="{{ route('company.create') }}" class="btn-modern btn-success-modern">
                        <i class="fas fa-plus"></i> İlk Firmayı Ekle
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        // Arama fonksiyonu (debounce ile)
        (function(){
            var searchInput = document.getElementById('company-search-input');
            var form = document.getElementById('company-search-form');
            if (!searchInput || !form) return;
            
            var debounceTimer;
            var lastSubmittedValue = searchInput.value;
            
            function submitForm() {
                if (searchInput.value === lastSubmittedValue) return;
                lastSubmittedValue = searchInput.value;
                form.requestSubmit ? form.requestSubmit() : form.submit();
            }
            
            searchInput.addEventListener('input', function(){
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(submitForm, 300);
            });
        })();

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
                        url: "{{url('/firma')}}/" + id,
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
                                swal("Silnemez!", response.message, "warning");
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
