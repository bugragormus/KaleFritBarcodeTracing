@extends('layouts.app')

@php $warehouseId = request()->route('warehouse') ?? 1; @endphp

@section('styles')
    <style>
        body, .main-content, .modern-warehouse-stock-quantity {
            background: #f8f9fa !important;
        }
        .modern-warehouse-stock-quantity {
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
        
        .btn-warning-modern {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
        }
        
        .btn-secondary-modern {
            background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%);
            color: white;
        }
        
        .btn-sm-modern {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 8px;
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
        
        .table-modern tbody td:nth-child(1) {
            text-align: center !important;
        }
        
        .table-modern tbody td:nth-child(2) {
            text-align: left !important;
        }
        
        .table-modern tbody td:nth-child(3),
        .table-modern tbody td:nth-child(4),
        .table-modern tbody td:nth-child(5),
        .table-modern tbody td:nth-child(6),
        .table-modern tbody td:nth-child(7),
        .table-modern tbody td:nth-child(8) {
            text-align: center !important;
        }
        
        .table-modern tbody tr:hover {
            background: #f8f9fa;
        }
        
        .table-modern .stock-name {
            font-weight: 600;
            color: #495057;
            font-size: 1.1rem;
        }
        
        .table-modern .stock-quantity {
            font-weight: 600;
            color: #28a745;
            font-size: 1.1rem;
        }
        
        .table-modern .status-quantity {
            font-weight: 600;
            font-size: 1rem;
            text-align: center;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            min-width: 40px;
            display: inline-block;
        }
        
        .table-modern .waiting-quantity {
            background: rgba(255, 193, 7, 0.1);
            color: #856404;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }
        
        .table-modern .control-repeat-quantity {
            background: rgba(23, 162, 184, 0.1);
            color: #0c5460;
            border: 1px solid rgba(23, 162, 184, 0.3);
        }
        
        .table-modern .pre-approved-quantity {
            background: rgba(40, 167, 69, 0.1);
            color: #155724;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }
        
        .table-modern .shipment-approved-quantity {
            background: rgba(0, 123, 255, 0.1);
            color: #004085;
            border: 1px solid rgba(0, 123, 255, 0.3);
        }
        
        .table-modern .rejected-quantity {
            background: rgba(220, 53, 69, 0.1);
            color: #721c24;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }
        
        .table-modern .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .table-modern .btn-xs-modern {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 8px;
        }
        

        
        .action-buttons-header {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        
        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
            
            .table-modern .action-buttons {
                flex-direction: column;
            }
            
            .table-modern .btn-xs-modern {
                width: 100%;
                margin-bottom: 0.25rem;
            }
            
            .action-buttons-header {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
@endsection

@section('content')
    <div class="modern-warehouse-stock-quantity">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-boxes"></i> Depo Stok Adetleri
                        </h1>
                        <p class="page-subtitle-modern">Depodaki stok adetlerini ve durum dağılımlarını görüntüleyin</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{ route('warehouse.index') }}" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i> Geri Dön
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modern Card -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title-modern">
                                <i class="fas fa-list"></i> Stok Adetleri Listesi
                            </h3>
                            <p class="card-subtitle-modern">
                                Aşağıdaki listede depoda bulunan tüm stok adetleri ve durum dağılımlarını görebilirsiniz
                            </p>
                        </div>
                        <div class="action-buttons-header">
                            <button onclick="fixData()" class="btn-modern btn-danger-modern btn-sm-modern">
                                <i class="fas fa-wrench"></i> Veri Düzelt
                            </button>
                            <button onclick="clearCache()" class="btn-modern btn-warning-modern btn-sm-modern">
                                <i class="fas fa-sync-alt"></i> Cache Temizle
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body-modern">

                    <table id="datatable" class="table table-modern">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Stok Adı</th>
                                <th>Toplam Stok</th>
                                <th>Beklemede</th>
                                <th>Kontrol Tekrarı</th>
                                <th>Ön Onaylı</th>
                                <th>Sevk Onaylı</th>
                                <th>Reddedildi</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockDetails as $key => $detail)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>
                                    <div class="stock-name">{{ $detail->name }}</div>
                                    @if(config('app.debug'))
                                        <small class="text-muted">Stock ID: {{ $detail->id ?? 'null' }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="stock-quantity">{{ $detail->total_quantity }}</div>
                                </td>
                                <td>
                                    <div class="status-quantity waiting-quantity">{{ $detail->waiting_quantity }}</div>
                                </td>
                                <td>
                                    <div class="status-quantity control-repeat-quantity">{{ $detail->control_repeat_quantity }}</div>
                                </td>
                                <td>
                                    <div class="status-quantity pre-approved-quantity">{{ $detail->pre_approved_quantity }}</div>
                                </td>
                                <td>
                                    <div class="status-quantity shipment-approved-quantity">{{ $detail->shipment_approved_quantity }}</div>
                                </td>
                                <td>
                                    <div class="status-quantity rejected-quantity">{{ $detail->rejected_quantity }}</div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @if($detail->id)
                                            <a href="{{ route('stock.edit', ['stok' => $detail->id]) }}" class="btn-modern btn-success-modern btn-xs-modern">
                                                <i class="fas fa-edit"></i> Stok Adı Düzenle
                                            </a>
                                        @else
                                            <span class="btn-modern btn-secondary-modern btn-xs-modern" style="opacity: 0.6; cursor: not-allowed;">
                                                <i class="fas fa-edit"></i> Stok Adı Düzenle
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#datatable').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            pageLength: 25
        } );

        function clearCache() {
            $.ajax({
                url: '{{ route("warehouse.clear-cache", ["warehouse" => "__WAREHOUSE__"]) }}'.replace('__WAREHOUSE__', '{{ $warehouseId }}'),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Cache temizlendi! Sayfa yenileniyor...');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error('Cache temizlenirken hata oluştu!');
                    }
                },
                error: function() {
                    toastr.error('Cache temizlenirken hata oluştu!');
                }
            }); 
        }

        function fixData() {
            if (!confirm('Bu işlem:\n1. Null quantity_id olan barkodları düzeltecek\n2. "Kabul Edildi" ve "Beklemede" durumundaki barkodları "Depoda" durumuna çevirecek\n\nDevam etmek istiyor musunuz?')) {
                return;
            }
            
            $.ajax({
                url: '{{ route("warehouse.fix-data", ["warehouse" => "__WAREHOUSE__"]) }}'.replace('__WAREHOUSE__', '{{ $warehouseId }}'),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message + '! Sayfa yenileniyor...');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Veri düzeltilirken hata oluştu!');
                }
            });
        }
    </script>
@endsection
