@extends('layouts.app')

@section('styles')
    <style>
        body, .main-content, .modern-warehouse-management {
            background: #f8f9fa !important;
        }
        .modern-warehouse-management {
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
        
        .warehouse-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
            overflow: hidden;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .warehouse-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .warehouse-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            position: relative;
        }
        
        .warehouse-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .warehouse-address {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .warehouse-body {
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
        
        .status-waiting { background: #fff3cd; color: #856404; }
        .status-control-repeat { background: #e3f2fd; color: #1976d2; }
        .status-pre-approved { background: #e2d9f3; color: #6f42c1; }
        .status-shipment-approved { background: #ffeaa7; color: #856404; }
        .status-rejected { background: #f8d7da; color: #721c24; }

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
        
        .activity-indicator {
            position: absolute;
            top: 1rem;
            right: 3rem;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            border: 2px solid white;
        }
        
        .activity-active { background: #28a745; }
        .activity-inactive { background: #6c757d; }
        
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
    <div class="modern-warehouse-management">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-warehouse"></i> Depo Yönetimi
                        </h1>
                        <p class="page-subtitle-modern">Depo performanslarını analiz edin, stok verilerini takip edin ve operasyonel verimliliği ölçün</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('warehouse.create') }}" class="btn-modern btn-success-modern">
                                <i class="fas fa-plus"></i> Yeni Depo Ekle
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Depo Performans Kartları -->
            @foreach($warehouses as $warehouse)
            <div class="warehouse-card">
                <div class="warehouse-header">
                    <div class="performance-indicator 
                        @if($warehouse->is_active) performance-excellent
                        @else performance-poor
                        @endif">
                    </div>
                    <div class="warehouse-name">{{ $warehouse->name }}</div>
                    <div class="warehouse-address">{{ $warehouse->address }}</div>
                </div>
                
                <div class="warehouse-body">
                    <!-- Temel İstatistikler -->
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($warehouse->current_stock_kg, 0) }}</div>
                            <div class="stat-label">Toplam Stok (KG)</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $warehouse->rejection_rate }}%</div>
                            <div class="stat-label">Red Oranı</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $warehouse->current_barcodes }}</div>
                            <div class="stat-label">Depodaki Barkod</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $warehouse->shipment_approval_rate }}%</div>
                            <div class="stat-label">Sevk Onayı Oranı</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">
                                @if($warehouse->last_activity_date)
                                    {{ \Carbon\Carbon::parse($warehouse->last_activity_date)->format('d.m.Y') }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="stat-label">Son Giriş</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">
                                @if($warehouse->is_active)
                                    <i class="fas fa-circle text-success"></i> Aktif
                                @else
                                    <i class="fas fa-circle text-muted"></i> Pasif
                                @endif
                            </div>
                            <div class="stat-label">Durum</div>
                        </div>
                    </div>

                    <!-- Performans Göstergeleri -->
                    <div class="progress-section">
                        <div class="progress-item">
                            <div class="progress-label">
                                <span>Red Oranı</span>
                                <span>{{ $warehouse->rejection_rate }}%</span>
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill 
                                    @if($warehouse->rejection_rate <= 5) progress-success
                                    @elseif($warehouse->rejection_rate <= 10) progress-info
                                    @elseif($warehouse->rejection_rate <= 20) progress-warning
                                    @else progress-danger
                                    @endif"
                                    style="width: {{ min($warehouse->rejection_rate, 100) }}%">
                                </div>
                            </div>
                        </div>
                        
                        <div class="progress-item">
                            <div class="progress-label">
                                <span>Sevk Onayı Oranı</span>
                                <span>{{ $warehouse->shipment_approval_rate }}%</span>
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill 
                                    @if($warehouse->shipment_approval_rate >= 80) progress-success
                                    @elseif($warehouse->shipment_approval_rate >= 60) progress-info
                                    @elseif($warehouse->shipment_approval_rate >= 40) progress-warning
                                    @else progress-danger
                                    @endif"
                                    style="width: {{ $warehouse->shipment_approval_rate }}%">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Durum Dağılımı -->
                    <div class="status-distribution">
                        <div class="status-item status-waiting">
                            <div>{{ number_format($warehouse->waiting_kg, 0) }}</div>
                            <small>Beklemede (KG)</small>
                        </div>
                        <div class="status-item status-control-repeat">
                            <div>{{ number_format($warehouse->control_repeat_kg, 0) }}</div>
                            <small>Kontrol Tekrarı (KG)</small>
                        </div>
                        <div class="status-item status-pre-approved">
                            <div>{{ number_format($warehouse->pre_approved_kg, 0) }}</div>
                            <small>Ön Onaylı (KG)</small>
                        </div>
                        <div class="status-item status-shipment-approved">
                            <div>{{ number_format($warehouse->shipment_approved_kg, 0) }}</div>
                            <small>Sevk Onaylı (KG)</small>
                        </div>
                        <div class="status-item status-rejected">
                            <div>{{ number_format($warehouse->rejected_kg, 0) }}</div>
                            <small>Reddedildi (KG)</small>
                        </div>
                    </div>

                    <!-- İşlem Butonları -->
                    <div class="action-buttons">
                        <a href="{{ route('warehouse.stock-quantity', ['depo' => $warehouse->id]) }}" class="btn-modern btn-primary-modern btn-xs-modern">
                            <i class="fas fa-chart-line"></i> Detaylı Analiz
                        </a>
                        <a href="{{ route('warehouse.edit', ['depo' => $warehouse->id]) }}" class="btn-modern btn-success-modern btn-xs-modern">
                            <i class="fas fa-edit"></i> Düzenle
                        </a>
                        <a href="#" class="btn-modern btn-warning-modern btn-xs-modern">
                            <i class="fas fa-file-excel"></i> Rapor İndir
                        </a>
                        <button class="btn-modern btn-danger-modern btn-xs-modern" data-id="{{ $warehouse->id }}" data-action="{{ route('warehouse.destroy', $warehouse->id) }}" onclick='deleteConfirmation("{{$warehouse->id}}")'>
                            <i class="fas fa-trash"></i> Sil
                        </button>
                    </div>
                </div>
            </div>
            @endforeach

            @if(empty($warehouses) || count($warehouses) == 0)
            <div class="card-modern">
                <div class="card-body-modern text-center">
                    <i class="fas fa-warehouse" style="font-size: 4rem; color: #e9ecef; margin-bottom: 1rem;"></i>
                    <h4>Henüz depo kaydı bulunmuyor</h4>
                    <p class="text-muted">İlk deponuzu ekleyerek başlayın</p>
                    <a href="{{ route('warehouse.create') }}" class="btn-modern btn-success-modern">
                        <i class="fas fa-plus"></i> İlk Depoyu Ekle
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
                        url: "{{url('/depo')}}/" + id,
                        data: data,
                        success: function (results) {
                            if (results) {
                                swal("Başarılı!", results.message, "success");
                                location.reload();
                            } else {
                                swal("Hata!", "Lütfen tekrar deneyin!", "error");
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
