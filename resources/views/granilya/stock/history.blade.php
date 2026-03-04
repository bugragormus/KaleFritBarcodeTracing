@extends('layouts.granilya')

@section('styles')
<style>
    .modern-history {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
    }
    
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
        
        /* Changes Display */
        .changes-container {
            max-width: 400px;
            min-width: 350px;
        }
        
        .change-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 12px 15px;
            margin-bottom: 8px;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .change-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .change-field {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-right: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .change-value {
            color: #28a745;
            font-weight: 600;
            font-size: 12px;
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
        
        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
            .changes-container {
                min-width: 100%;
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
                            <i class="fas fa-history"></i> Palet Hareketleri
                        </h1>
                        <p class="page-subtitle-modern">#{{ $pallet->pallet_number }} numaralı palete ait tüm hareketleri ve değişiklikleri görüntüleyin</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{ route('granilya.production.show', $pallet->id) }}" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i> Geri Dön
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="table-card-modern">
                <div class="table-header">
                    <h5><i class="fas fa-list"></i> Hareket Listesi</h5>
                    <p>Bu palet üzerinde yapılan tüm işlemler kronolojik olarak aşağıda listelenmiştir.</p>
                </div>

                <div class="table-body">
                    <table id="history-datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Durum</th>
                                <th>Kullanıcı</th>
                                <th>Açıklama</th>
                                <th>Değişiklikler</th>
                                <th>İşlem Tarihi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($histories as $history)
                            <tr>
                                <td>{{ $history->id }}</td>
                                <td class="text-center">
                                    {!! $pallet->status_badge !!}
                                </td>
                                <td>{{ $history->user->name }}</td>
                                <td>{{ $history->description }}</td>
                                <td>
                                    @if(!is_null($history->changes) && is_array($history->changes) && count($history->changes) > 0)
                                        <div class="changes-container">
                                            @foreach($history->changes as $key => $change)
                                                @php
                                                    $fieldNames = [
                                                        'stock_id' => 'Frit Kodu',
                                                        'load_number' => 'Şarj No',
                                                        'size_id' => 'Tane Boyutu',
                                                        'crusher_id' => 'Kırıcı Makina',
                                                        'company_id' => 'Firma',
                                                        'quantity_id' => 'Miktar (Sabit)',
                                                        'custom_quantity' => 'Miktar (Serbest)',
                                                        'pallet_number' => 'Palet No',
                                                        'status' => 'Durum',
                                                        'general_note' => 'Not'
                                                    ];
                                                    
                                                    $fieldName = $fieldNames[$key] ?? $key;
                                                    
                                                    $fromVal = $change['from'] ?? '-';
                                                    $toVal = $change['to'] ?? '-';
                                                    
                                                    // Resolve IDs to Names
                                                    if ($key == 'stock_id') {
                                                        $fromVal = \App\Models\Stock::find($fromVal)?->name ?? $fromVal;
                                                        $toVal = \App\Models\Stock::find($toVal)?->name ?? $toVal;
                                                    } elseif ($key == 'size_id') {
                                                        $fromVal = \App\Models\GranilyaSize::find($fromVal)?->name ?? $fromVal;
                                                        $toVal = \App\Models\GranilyaSize::find($toVal)?->name ?? $toVal;
                                                    } elseif ($key == 'crusher_id') {
                                                        $fromVal = \App\Models\GranilyaCrusher::find($fromVal)?->name ?? $fromVal;
                                                        $toVal = \App\Models\GranilyaCrusher::find($toVal)?->name ?? $toVal;
                                                    } elseif ($key == 'company_id') {
                                                        $fromVal = \App\Models\GranilyaCompany::find($fromVal)?->name ?? $fromVal;
                                                        $toVal = \App\Models\GranilyaCompany::find($toVal)?->name ?? $toVal;
                                                    } elseif ($key == 'quantity_id') {
                                                        $fromVal = \App\Models\GranilyaQuantity::find($fromVal)?->quantity . ' KG' ?? $fromVal;
                                                        $toVal = \App\Models\GranilyaQuantity::find($toVal)?->quantity . ' KG' ?? $toVal;
                                                    } elseif ($key == 'status') {
                                                        $statusList = \App\Models\GranilyaProduction::getStatusList();
                                                        $fromVal = $statusList[$fromVal] ?? $fromVal;
                                                        $toVal = $statusList[$toVal] ?? $toVal;
                                                    }
                                                @endphp
                                                <div class="change-item">
                                                    <span class="change-field">{{ $fieldName }}</span>
                                                    <span class="text-muted">{{ $fromVal }}</span>
                                                    <i class="fas fa-long-arrow-alt-right change-arrow"></i>
                                                    <span class="change-value">{{ $toVal }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">Değişiklik yok</span>
                                    @endif
                                </td>
                                <td>{{ $history->created_at->format('d.m.Y H:i:s') }}</td>
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
        $(document).ready(function() {
            $('#history-datatable').DataTable({
                order: [[0, "desc"]],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json"
                }
            });
        });
    </script>
@endsection
