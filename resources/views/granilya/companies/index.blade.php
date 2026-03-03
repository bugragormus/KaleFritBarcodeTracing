@extends('layouts.granilya')

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

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('granilya.dashboard') }}"><i class="fas fa-home"></i> Ana Sayfa</a></li>
    <li class="breadcrumb-item active">Firmalar</li>
@endsection

@section('content')
    <div class="modern-company-management">
        <div class="container-fluid">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-briefcase"></i> Firma Yönetimi
                        </h1>
                        <p class="page-subtitle-modern">Granilya firma performanslarını analiz edin, alım verilerini takip edin ve müşteri memnuniyetini ölçün</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="d-flex gap-4 justify-content-end">
                            <button type="button" class="btn-modern btn-success-modern mr-2" data-toggle="modal" data-target="#createModal">
                                <i class="fas fa-plus"></i> Yeni Firma Ekle
                            </button>
                            <!-- Export Button hidden until Granilya Tracking is Implemented 
                            <a href="#" class="btn-modern btn-warning-modern">
                                <i class="fas fa-file-excel"></i>Excel İndir
                            </a>
                            -->
                        </div>
                    </div>
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
                    <div class="company-name">{{ $company->name }} <small>({{ $company->code ?? '-' }})</small></div>
                    <div class="company-address">Durum: {{ $company->is_active ? 'Aktif' : 'Pasif' }}</div>
                </div>
                
                <div class="company-body">
                    <!-- Temel İstatistikler -->
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($company->total_purchase, 0) }}</div>
                            <div class="stat-label">Toplam Alım (KG)</div>
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
                    <div class="action-buttons mt-3">
                        <button type="button" class="btn-modern btn-success-modern btn-xs-modern edit-btn" 
                                data-id="{{ $company->id }}" 
                                data-name="{{ $company->name }}" 
                                data-code="{{ $company->code }}" 
                                data-active="{{ $company->is_active }}"
                                data-toggle="modal" data-target="#editModal">
                            <i class="fas fa-edit"></i> Düzenle
                        </button>
                        <!-- Analytics & Reports hidden until Granilya Tracking is Implemented 
                        <a href="#" class="btn-modern btn-info-modern btn-xs-modern">
                            <i class="fas fa-chart-bar"></i> Detaylı Analiz
                        </a>
                        <a href="#" class="btn-modern btn-warning-modern btn-xs-modern">
                            <i class="fas fa-file-excel"></i> Rapor İndir
                        </a>
                        -->
                        <form action="{{ route('granilya.firma.destroy', $company->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu firmayı silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-modern btn-danger-modern btn-xs-modern">
                                <i class="fas fa-trash"></i> Sil
                            </button>
                        </form>
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
                    <p class="text-muted">İlk firmayı ekleyerek başlayın</p>
                    <button type="button" class="btn-modern btn-success-modern" data-toggle="modal" data-target="#createModal">
                        <i class="fas fa-plus"></i> İlk Firmayı Ekle
                    </button>
                </div>
            </div>
            @endif

        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Yeni Firma Ekle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('granilya.firma.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Kod</label>
                            <input type="text" name="code" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Adı <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck1" name="is_active" value="1" checked>
                                <label class="custom-control-label" for="customCheck1">Aktif</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Firma Düzenle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Kod</label>
                            <input type="text" name="code" id="edit_code" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Adı <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="editCheck1" name="is_active" value="1">
                                <label class="custom-control-label" for="editCheck1">Aktif</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Edit Modal Data Binding
        $('.edit-btn').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var code = $(this).data('code');
            var active = $(this).data('active');

            $('#editForm').attr('action', '/granilya/firma/' + id);
            $('#edit_name').val(name);
            $('#edit_code').val(code);
            
            if(active == 1) {
                $('#editCheck1').prop('checked', true);
            } else {
                $('#editCheck1').prop('checked', false);
            }
        });
    });
</script>
@endsection
