@extends('layouts.app')

@section('styles')
    <style>
        body, .main-content, .modern-barcode-history {
            background: #f8f9fa !important;
        }
        .modern-barcode-history {
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
        
        /* Table Card */
        .table-card-modern {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: none;
            overflow: hidden;
        }
        
        .table-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px 25px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .table-header h5 {
            margin: 0;
            font-weight: 600;
            color: #495057;
            display: flex;
            align-items: center;
        }
        
        .table-header h5 i {
            margin-right: 10px;
            color: #667eea;
        }
        
        .table-header p {
            margin: 5px 0 0 0;
            color: #6c757d;
            font-size: 14px;
        }
        
        .table-body {
            padding: 25px;
        }
        
        /* Status Badges */
        .status-badge {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            text-align: center;
            min-width: 100px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            border: none;
            transition: all 0.3s ease;
        }
        
        .status-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .status-waiting {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: #212529;
        }
        
        .status-control-repeat {
            background: linear-gradient(135deg, #fd7e14, #e55a00);
            color: white;
        }
        
        .status-pre-approved {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .status-shipment-approved {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
        }
        
        .status-rejected {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }
        
        .status-customer-transfer {
            background: linear-gradient(135deg, #6f42c1, #5a32a3);
            color: white;
        }
        
        .status-delivered {
            background: linear-gradient(135deg, #20c997, #17a2b8);
            color: white;
        }
        
        .status-merged {
            background: linear-gradient(135deg, #6f42c1, #5a32a3);
            color: white;
        }
        
        /* Table styling for better status badge display */
        .table td {
            vertical-align: middle;
        }
        
        .table th {
            vertical-align: middle;
            font-weight: 600;
            color: #495057;
        }
        
        /* Changes Display */
        .changes-container {
            max-width: 400px;
            min-width: 350px;
        }
        
        .change-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 12px;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .change-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .change-field {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-right: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .change-value {
            color: #28a745;
            font-weight: 600;
            font-size: 13px;
            padding: 2px 0;
        }
        
        .change-arrow {
            color: #667eea;
            margin: 0 5px;
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
            font-size: 0.875rem;
        }
        
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-secondary-modern {
            background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%);
            color: white;
        }
        
        .btn-secondary-modern:hover {
            background: linear-gradient(135deg, #9ca3af 0%, #5a6268 100%);
            color: white;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
            
            .table-body {
                padding: 15px;
            }
            
            .changes-container {
                max-width: 100%;
            }
            
            .action-buttons {
                flex-direction: column;
                width: 100%;
            }
            
            .action-buttons .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="modern-barcode-history">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-history"></i> Barkod Hareketleri
                        </h1>
                        <p class="page-subtitle-modern">Sistemdeki tüm barkod hareketlerini ve değişiklik geçmişini görüntüleyin</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="action-buttons justify-content-end">
                            <a href="{{ route('barcode.index') }}" class="btn-modern btn-secondary-modern">
                                <i class="fas fa-arrow-left"></i> Geri Dön
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modern Table Card -->
            <div class="table-card-modern">
                <div class="table-header">
                    <h5><i class="fas fa-list"></i> Hareket Listesi</h5>
                    <p>Aşağıdaki listede sistemde kayıtlı olan barkod hareketlerini ve değişiklik geçmişini görebilirsiniz.</p>
                </div>

                <div class="table-body">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Durumu</th>
                                <th>Kullanıcı</th>
                                <th>Açıklama</th>
                                <th>Değişiklikler</th>
                                <th>İşlem Tarihi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($histories as $history)
                            <tr>
                                <td>{{ $history->id}}</td>
                                <td class="text-center">
                                    @php
                                        $statusClass = '';
                                        switch($history->status) {
                                            case \App\Models\Barcode::STATUS_WAITING: 
                                                $statusClass = 'status-waiting'; 
                                                break;
                                            case \App\Models\Barcode::STATUS_CONTROL_REPEAT: 
                                                $statusClass = 'status-control-repeat'; 
                                                break;
                                            case \App\Models\Barcode::STATUS_PRE_APPROVED: 
                                                $statusClass = 'status-pre-approved'; 
                                                break;
                                            case \App\Models\Barcode::STATUS_SHIPMENT_APPROVED: 
                                                $statusClass = 'status-shipment-approved'; 
                                                break;
                                            case \App\Models\Barcode::STATUS_REJECTED: 
                                                $statusClass = 'status-rejected'; 
                                                break;
                                            case \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER: 
                                                $statusClass = 'status-customer-transfer'; 
                                                break;
                                            case \App\Models\Barcode::STATUS_DELIVERED: 
                                                $statusClass = 'status-delivered'; 
                                                break;
                                            case \App\Models\Barcode::STATUS_MERGED: 
                                                $statusClass = 'status-merged'; 
                                                break;
                                            default: 
                                                $statusClass = 'status-waiting';
                                        }
                                    @endphp
                                    <div class="d-flex justify-content-center">
                                        <span class="status-badge {{ $statusClass }}">{{ \App\Models\Barcode::STATUSES[$history->status] }}</span>
                                    </div>
                                </td>
                                <td>{{ $history->user->name}}</td>
                                <td>{{ $history->description}}</td>
                                <td>
                                    @if(!is_null($history->changes))
                                        <div class="changes-container">
                                            @foreach($history->changes as $key => $value)
                                                @php
                                                    $fieldNames = [
                                                        'status' => 'Durum',
                                                        // 'transfer_status' => 'Transfer Durumu', // Artık kullanılmıyor
                                                        'lab_at' => 'Lab İşlem Tarihi',
                                                        'lab_by' => 'Lab Personeli',
                                                        'warehouse_id' => 'Depo',
                                                        'warehouse_transferred_at' => 'Depo Transfer Tarihi',
                                                        'warehouse_transferred_by' => 'Depo Transfer Personeli',
                                                        'company_id' => 'Müşteri',
                                                        'company_transferred_at' => 'Müşteri Transfer Tarihi',
                                                        'delivered_at' => 'Teslim Tarihi',
                                                        'delivered_by' => 'Teslim Eden',
                                                        'lab_note' => 'Lab Notu',
                                                        'note' => 'Not',
                                                        'updated_at' => 'Güncelleme Tarihi'
                                                    ];
                                                    
                                                    $fieldName = $fieldNames[$key] ?? $key;
                                                    
                                                    // Değerleri kullanıcı dostu hale getir
                                                    $displayValue = $value;
                                                    if ($key === 'status') {
                                                        $displayValue = \App\Models\Barcode::STATUSES[$value] ?? $value;
                                                    } elseif ($key === 'transfer_status') {
    // Transfer status artık kullanılmıyor
    $displayValue = 'Transfer Durumu (Kaldırıldı)';
                                                    } elseif (in_array($key, ['lab_by', 'warehouse_transferred_by', 'delivered_by'])) {
                                                        $user = \App\Models\User::find($value);
                                                        $displayValue = $user ? $user->name : $value;
                                                    } elseif (in_array($key, ['warehouse_id', 'company_id'])) {
                                                        if ($key === 'warehouse_id') {
                                                            $warehouse = \App\Models\Warehouse::find($value);
                                                            $displayValue = $warehouse ? $warehouse->name : $value;
                                                        } else {
                                                            $company = \App\Models\Company::find($value);
                                                            $displayValue = $company ? $company->name : $value;
                                                        }
                                                    } elseif (in_array($key, ['lab_at', 'warehouse_transferred_at', 'company_transferred_at', 'delivered_at', 'updated_at'])) {
                                                        $displayValue = $value ? \Carbon\Carbon::parse($value)->format('d.m.Y H:i') : '-';
                                                    }
                                                @endphp
                                                <div class="change-item">
                                                    <span class="change-field">{{ $fieldName }}</span>
                                                    <i class="mdi mdi-arrow-right change-arrow"></i>
                                                    <span class="change-value">{{ $displayValue }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">Değişiklik bilgisi yok</span>
                                    @endif
                                </td>
                                <td>{{ $history->created_at->tz('Europe/Istanbul')->toDateTimeString() }}</td>
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
        $('#datatable').dataTable( {
            order: {
                order: [[0, "desc"]]
            }
        } );
    </script>
@endsection
