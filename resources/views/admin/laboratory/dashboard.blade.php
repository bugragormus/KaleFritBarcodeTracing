@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
    </li>
    <li class="breadcrumb-item active">
        <i class="fas fa-flask"></i> Laboratuvar
    </li>
@endsection

@section('styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <style>
        body, .main-content, .modern-lab-dashboard {
            background: #f8f9fa !important;
        }
        .modern-lab-dashboard {
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
        .stat-card-modern {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s;
            background: #fff;
            padding: 2rem 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e9ecef;
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
        .quick-action-btn-modern {
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
            transition: all 0.3s;
            font-weight: 600;
            font-size: 1rem;
        }
        .quick-action-btn-modern:hover {
            transform: scale(1.05);
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
        }
        /* Modal stilleri */
        .modal-header.bg-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        }
        .modal-header.bg-danger {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%) !important;
        }
        .modal-header.bg-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
        }
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
        .activity-item {
            border-left: 3px solid #007bff;
            padding-left: 15px;
            margin-bottom: 10px;
        }
        .pending-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 8px;
            border-left: 4px solid #ffc107;
        }
        .inline-action-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-top: 0.5rem;
        }
        .inline-action-buttons .btn-modern {
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            transition: all 0.2s;
        }
        .inline-action-buttons .btn-accept {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        .inline-action-buttons .btn-accept:hover {
            background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
            color: white;
        }
        .inline-action-buttons .btn-reject {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }
        .inline-action-buttons .btn-reject:hover {
            background: linear-gradient(135deg, #fd7e14 0%, #dc3545 100%);
            color: white;
        }
        .inline-action-buttons .btn-cancel {
            background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%);
            color: white;
        }
        .inline-action-buttons .btn-cancel:hover {
            background: linear-gradient(135deg, #6c757d 0%, #adb5bd 100%);
            color: white;
        }
        /* Date Filter Styles */
        .form-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .stat-card-modern {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
            
            .stat-card-modern {
                padding: 1.2rem 1rem;
                margin-bottom: 1rem;
            }
            
            .card-body-modern {
                padding: 1.2rem;
            }
            
            .inline-action-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .inline-action-buttons .btn-modern {
                width: 100%;
                justify-content: center;
            }

            /* Date filter responsive */
            .card-modern .row {
                flex-direction: column;
            }

            .card-modern .col-md-6 {
                margin-bottom: 1rem;
            }

            .card-modern .col-md-6:last-child {
                margin-bottom: 0;
            }

            #kpi-date-filter .row {
                flex-direction: column;
            }

            #kpi-date-filter .col-md-5,
            #kpi-date-filter .col-md-2 {
                margin-bottom: 0.5rem;
            }

            #kpi-date-filter .col-md-2 {
                margin-bottom: 0;
            }
        }

        @media (max-width: 576px) {
            .page-title-modern {
                font-size: 1.8rem;
            }

            .stat-card-modern {
                padding: 1rem 0.8rem;
            }

            .card-body-modern {
                padding: 1rem;
            }

            .form-control {
                font-size: 0.9rem;
                padding: 0.5rem 0.75rem;
            }

            .btn-primary {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
@endsection

@section('content')
<div class="modern-lab-dashboard">
    <div class="container-fluid">
        <!-- Modern Page Header -->
        <div class="page-header-modern">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title-modern">
                        <i class="fas fa-flask"></i> Laboratuvar Yönetim Paneli
                    </h1>
                    <p class="page-subtitle-modern">Hoş geldiniz, {{ auth()->user()->name }}!</p>
                </div>
                <div class="col-md-4 text-right">
                    <span class="badge badge-info" style="font-size:1rem; padding:0.75rem 1.25rem;">
                        {{ now()->setTimezone('Europe/Istanbul')->format('d.m.Y H:i') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- KPI Tarih Filtresi -->
        <div class="card-modern mb-3">
            <div class="card-body-modern">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt text-primary mr-2"></i>
                            KPI Tarih Filtresi
                        </h5>
                        <small class="text-muted">
                            Tüm KPI'lar seçilen tarih aralığında oluşturulan barkodları gösterir | 
                            <strong>Aktif Tarih: {{ \Carbon\Carbon::parse(request('start_date', now()->tz('Europe/Istanbul')->subDays(30)->format('Y-m-d')))->format('d.m.Y') }} - {{ \Carbon\Carbon::parse(request('end_date', now()->tz('Europe/Istanbul')->format('Y-m-d')))->format('d.m.Y') }}</strong>
                        </small>
                    </div>
                    <div class="col-md-6">
                        <form id="kpi-date-filter" class="row g-2">
                            <div class="col-md-5">
                                <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ request('start_date', now()->tz('Europe/Istanbul')->subDays(30)->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-5">
                                <label for="end_date" class="form-label">Bitiş Tarihi</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ request('end_date', now()->tz('Europe/Istanbul')->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </form>
                        <!-- Hızlı Tarih Seçenekleri -->
                        <div class="mt-2">
                            <small class="text-muted">Hızlı seçim: </small>
                            <button type="button" class="btn btn-outline-secondary btn-sm mx-1" onclick="setDateRange('today')">Bugün</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm mx-1" onclick="setDateRange('yesterday')">Dün</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm mx-1" onclick="setDateRange('week')">Bu Hafta</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm mx-1" onclick="setDateRange('month')">Bu Ay</button>
                            <button type="button" class="btn btn-outline-warning btn-sm mx-1" onclick="resetDateFilter()">Sıfırla</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- İstatistik Kartları -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-modern text-center" title="Seçilen tarih aralığında oluşturulan bekleyen barkodlar">
                    <div class="stat-icon-modern text-warning mb-2">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-number-modern">{{ $stats['waiting'] }}</div>
                    <div class="stat-label-modern">Bekleyen Barkod</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-modern text-center" title="Seçilen tarih aralığında oluşturulan ön onaylı barkodlar">
                    <div class="stat-icon-modern text-success mb-2">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-number-modern">{{ $stats['accepted'] }}</div>
                    <div class="stat-label-modern">Ön Onaylı</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-modern text-center" title="Seçilen tarih aralığında oluşturulan reddedilen barkodlar">
                    <div class="stat-icon-modern text-danger mb-2">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-number-modern">{{ $stats['rejected'] }}</div>
                    <div class="stat-label-modern">Reddedilen</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-modern text-center" title="Seçilen tarih aralığında laboratuvar işlemi yapılan barkodlar">
                    <div class="stat-icon-modern text-info mb-2">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-number-modern">{{ $stats['processed_today'] }}</div>
                    <div class="stat-label-modern">Seçilen Tarihte İşlenen</div>
                </div>
            </div>
        </div>

        <!-- Ek KPI Kartları -->
        <div class="row mt-3">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-modern text-center" title="Seçilen tarih aralığında laboratuvar işlemi yapılan toplam barkodlar">
                    <div class="stat-icon-modern text-primary mb-2">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-number-modern">{{ $stats['total_processed'] ?? 0 }}</div>
                    <div class="stat-label-modern">Toplam İşlenen</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-modern text-center" title="Seçilen tarih aralığında oluşturulan kontrol tekrarı barkodlar">
                    <div class="stat-icon-modern text-secondary mb-2">
                        <i class="fas fa-redo"></i>
                    </div>
                    <div class="stat-number-modern">{{ $stats['control_repeat'] ?? 0 }}</div>
                    <div class="stat-label-modern">Kontrol Tekrarı</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-modern text-center" title="Seçilen tarih aralığında oluşturulan sevk onaylı barkodlar">
                    <div class="stat-icon-modern text-dark mb-2">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="stat-number-modern">{{ $stats['shipment_approved'] ?? 0 }}</div>
                    <div class="stat-label-modern">Sevk Onaylı</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-modern text-center" title="Seçilen tarih aralığındaki sevk onaylı oranı">
                    <div class="stat-icon-modern text-success mb-2">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="stat-number-modern">{{ $stats['acceptance_rate'] ?? 0 }}%</div>
                    <div class="stat-label-modern">Sevk Oranı</div>
                </div>
            </div>
        </div>

        <!-- Hızlı İşlemler -->
        <div class="row">
            <div class="col-12">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern"><i class="fas fa-bolt"></i> Hızlı İşlemler</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('laboratory.barcode-list') }}" class="btn btn-primary btn-block quick-action-btn-modern">
                                    <i class="fas fa-list mr-2"></i> Barkod Listesi
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('laboratory.bulk-process') }}" class="btn btn-warning btn-block quick-action-btn-modern">
                                    <i class="fas fa-layer-group mr-2"></i> Toplu İşlem
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('laboratory.report') }}" class="btn btn-info btn-block quick-action-btn-modern">
                                    <i class="fas fa-chart-bar mr-2"></i> Raporlar
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-success btn-block quick-action-btn-modern" onclick="refreshDashboard()">
                                    <i class="fas fa-sync-alt mr-2"></i> Yenile
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Bekleyen Barkodlar -->
            <div class="col-lg-6">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern"><i class="fas fa-clock text-warning mr-2"></i>Laboratuvar İşlemleri (Son 20)</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Stok</th>
                                        <th>Şarj/Fırın</th>
                                        <th>Miktar</th>
                                        <th>Oluşturan</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingBarcodes as $barcode)
                                    <tr id="barcode-row-{{ $barcode->id }}">
                                        <td>
                                            <strong>{{ $barcode->stock->name }}</strong><br>
                                            <small>{{ $barcode->stock->code }}</small>
                                            <br>
                                            <span class="badge badge-{{ 
                                                $barcode->status == \App\Models\Barcode::STATUS_WAITING ? 'warning' : 
                                                ($barcode->status == \App\Models\Barcode::STATUS_PRE_APPROVED ? 'success' : 
                                                ($barcode->status == \App\Models\Barcode::STATUS_CONTROL_REPEAT ? 'info' : 'secondary')) 
                                            }}">
                                                {{ \App\Models\Barcode::STATUSES[$barcode->status] }}
                                            </span>
                                        </td>
                                        <td>
                                            Şarj: {{ $barcode->load_number }}<br>
                                            <small>{{ $barcode->kiln->name }}</small>
                                        </td>
                                        <td>{{ $barcode->quantity->quantity }} KG</td>
                                        <td>
                                            {{ $barcode->createdBy->name }}<br>
                                            <small>{{ optional($barcode->created_at)->tz('Europe/Istanbul')->format('d.m.Y H:i:s') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if($barcode->status == \App\Models\Barcode::STATUS_WAITING)
                                                    <button class="btn btn-success process-barcode-btn" data-id="{{ $barcode->id }}" data-action="pre_approved" 
                                                            title="Ön Onaylı - Kontrol geçti, sevk onayı için hazır">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-info process-barcode-btn" data-id="{{ $barcode->id }}" data-action="control_repeat"
                                                            title="Kontrol Tekrarı - Tekrar kontrol gerekli">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                    <button class="btn btn-danger process-barcode-btn" data-id="{{ $barcode->id }}" data-action="reject"
                                                            title="Reddet - Kontrol geçemedi, işlem durduruldu">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @elseif($barcode->status == \App\Models\Barcode::STATUS_PRE_APPROVED)
                                                    <button class="btn btn-info process-barcode-btn" data-id="{{ $barcode->id }}" data-action="control_repeat"
                                                            title="Kontrol Tekrarı - Tekrar kontrol gerekli">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                    <button class="btn btn-primary process-barcode-btn" data-id="{{ $barcode->id }}" data-action="shipment_approved"
                                                            title="Sevk Onaylı - Direkt sevk için onaylandı">
                                                        <i class="fas fa-shipping-fast"></i>
                                                    </button>
                                                    <button class="btn btn-danger process-barcode-btn" data-id="{{ $barcode->id }}" data-action="reject"
                                                            title="Reddet - Kontrol geçemedi, işlem durduruldu">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @elseif($barcode->status == \App\Models\Barcode::STATUS_CONTROL_REPEAT)
                                                    <button class="btn btn-success process-barcode-btn" data-id="{{ $barcode->id }}" data-action="pre_approved" 
                                                            title="Ön Onaylı - Kontrol geçti, sevk onayı için hazır">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-danger process-barcode-btn" data-id="{{ $barcode->id }}" data-action="reject"
                                                            title="Reddet - Kontrol geçemedi, işlem durduruldu">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <i class="fas fa-check-circle text-success mr-2"></i>
                                            Bekleyen barkod bulunmuyor!
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($pendingBarcodes->count() > 0)
                        <div class="text-center mt-3">
                            <a href="{{ route('laboratory.barcode-list') }}" class="btn btn-outline-primary btn-sm">
                                Tümünü Görüntüle
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Son İşlemler -->
            <div class="col-lg-6">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern"><i class="fas fa-history text-info mr-2"></i>Son İşlemler</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="activity-list">
                            @forelse($recentActivities as $activity)
                            <div class="activity-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $activity->stock->code }} - {{ $activity->stock->name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $activity->kiln->name }} | {{ $activity->quantity->quantity }} KG
                                        </small>
                                    </div>
                                    <div class="text-right">
                                        <span class="badge badge-{{ 
                                            $activity->status == \App\Models\Barcode::STATUS_PRE_APPROVED ? 'success' : 
                                            ($activity->status == \App\Models\Barcode::STATUS_CONTROL_REPEAT ? 'info' : 
                                            ($activity->status == \App\Models\Barcode::STATUS_SHIPMENT_APPROVED ? 'primary' : 
                                            ($activity->status == \App\Models\Barcode::STATUS_REJECTED ? 'danger' : 'secondary'))) 
                                        }}">
                                            {{ \App\Models\Barcode::STATUSES[$activity->status] ?? '-' }}
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ optional($activity->lab_at)->tz('Europe/Istanbul')->format('H:i') }}</small>
                                    </div>
                                </div>
                                <div class="mt-1">
                                    <small class="text-muted">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $activity->labBy->name }}
                                    </small>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-info-circle mr-2"></i>
                                Henüz işlem yapılmamış
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    window.LabDashConfig = {
        processRoute: '{{ route("laboratory.process-barcode") }}',
        detailRoute: '{{ route("laboratory.barcode-detail", ":id") }}',
        csrfToken: '{{ csrf_token() }}',
        rejectionReasons: @json(\App\Models\RejectionReason::active()->get(['id', 'name']))
    };
</script>
<script src="{{ asset('assets/js/modules/lab-dashboard.js') }}"></script>
@endsection 