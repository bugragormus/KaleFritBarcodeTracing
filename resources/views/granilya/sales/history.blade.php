@extends('layouts.granilya')

@section('styles')
    <!-- bootstrap-select library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/css/bootstrap-select.min.css" />
    <style>
        .history-container {
            padding: 2rem 0;
            background: #f8fafc;
        }

        .premium-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .filter-header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.5rem 2rem;
        }

        .history-table thead th {
            background: #f8fafc;
            border-top: none;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 1.25rem;
        }

        .history-table tbody td {
            padding: 1.25rem;
            vertical-align: middle;
            color: #1e293b;
            border-bottom: 1px solid #f1f5f9;
        }

        .customer-tag {
            background: #eff6ff;
            color: #1d4ed8;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #475569;
        }

        .user-icon {
            width: 28px;
            height: 28px;
            background: #6366f1;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 800;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .status-badge {
            background: #dcfce7;
            color: #166534;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
        }
    </style>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('granilya.dashboard') }}"><i class="fas fa-home"></i> Ana Sayfa</a></li>
    <li class="breadcrumb-item"><a href="{{ route('granilya.sales') }}">Satış Ekranı</a></li>
    <li class="breadcrumb-item active">Satış Geçmişi</li>
@endsection

@section('content')
<div class="history-container">
    <div class="container-fluid">
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title">
                <i class="fas fa-history text-primary"></i> Satış Geçmişi
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('granilya.sales.history.export', request()->all()) }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                    <i class="fas fa-file-excel mr-2"></i>Excel Olarak İndir
                </a>
                <a href="{{ route('granilya.sales') }}" class="btn btn-outline-primary rounded-pill px-4 ml-2">
                    <i class="fas fa-shopping-cart mr-2"></i>Aktif Satış Ekranı
                </a>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm rounded-20 bg-primary text-white" style="border-radius: 20px; overflow: hidden; position: relative; height: 100%;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 font-weight-bold mb-2 uppercase" style="letter-spacing: 1px;">TOPLAM SATIŞ</h6>
                                <h2 class="font-weight-800 mb-0">{{ number_format($stats['total_weight'], 0, ',', '.') }} KG</h2>
                            </div>
                            <div class="stat-icon-circle bg-white-20" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="fas fa-truck-loading"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm rounded-20 bg-info text-white" style="border-radius: 20px; overflow: hidden; position: relative; height: 100%;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 font-weight-bold mb-2 uppercase" style="letter-spacing: 1px;">SATIŞ ADEDİ</h6>
                                <h2 class="font-weight-800 mb-0">{{ $stats['total_sales'] }} Palet</h2>
                            </div>
                            <div class="stat-icon-circle bg-white-20" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="fas fa-pallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm rounded-20 bg-white" style="border-radius: 20px; overflow: hidden; position: relative; height: 100%; border: 1px solid #e2e8f0;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted font-weight-bold mb-2 uppercase" style="letter-spacing: 1px;">EN ÇOK SATIN ALAN</h6>
                                <h5 class="font-weight-bold text-dark mb-0 text-truncate" style="max-width: 180px;">{{ $stats['top_customer_name'] }}</h5>
                            </div>
                            <div class="stat-icon-circle" style="width: 50px; height: 50px; background: #eff6ff; color: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="fas fa-building"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm rounded-20 bg-white" style="border-radius: 20px; overflow: hidden; position: relative; height: 100%; border: 1px solid #e2e8f0;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted font-weight-bold mb-2 uppercase" style="letter-spacing: 1px;">EN ÇOK SATILAN</h6>
                                <h5 class="font-weight-bold text-dark mb-0 text-truncate" style="max-width: 180px;">{{ $stats['top_product_name'] }}</h5>
                            </div>
                            <div class="stat-icon-circle" style="width: 50px; height: 50px; background: #f0fdf4; color: #22c55e; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="fas fa-box-open"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="premium-card mb-4">
            <div class="filter-header">
                <h5 class="mb-0 font-weight-bold text-muted"><i class="fas fa-filter mr-2"></i>Arama ve Filtreleme</h5>
            </div>
            <div class="p-4">
                <form action="{{ route('granilya.sales.history') }}" method="GET">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="small font-weight-bold">Palet No</label>
                                <input type="text" name="pallet_no" class="form-control" value="{{ request('pallet_no') }}" placeholder="#123">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="small font-weight-bold">Frit Kodu</label>
                                <select name="stock_id[]" class="form-control selectpicker" multiple data-live-search="true" title="Tümü">
                                    @foreach($stocks as $stock)
                                        <option value="{{ $stock->id }}" {{ is_array(request('stock_id')) && in_array($stock->id, request('stock_id')) ? 'selected' : '' }}>{{ $stock->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="small font-weight-bold">Satın Alan Firma</label>
                                <select name="company_id" class="form-control selectpicker" data-live-search="true" title="Tümü">
                                    <option value="">Tümü</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="small font-weight-bold">Başlangıç Tarihi</label>
                                <input type="date" name="date_start" class="form-control" value="{{ request('date_start') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="small font-weight-bold">Bitiş Tarihi</label>
                                <input type="date" name="date_end" class="form-control" value="{{ request('date_end') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="small font-weight-bold">İşlemi Yapan</label>
                                <select name="user_id" class="form-control selectpicker" data-live-search="true" title="Tümü">
                                    <option value="">Tümü</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-9 text-right d-flex align-items-end justify-content-end mb-3">
                            <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm">
                                <i class="fas fa-search mr-2"></i>Filtrele
                            </button>
                            <a href="{{ route('granilya.sales.history') }}" class="btn btn-light ml-2 rounded-pill">
                                <i class="fas fa-sync-alt mr-2"></i>Temizle
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- History Table -->
        <div class="premium-card">
            <div class="table-responsive">
                <table class="table history-table mb-0">
                    <thead>
                        <tr>
                            <th>Satış Tarihi</th>
                            <th>Palet No</th>
                            <th>Frit Kodu</th>
                            <th>Miktar</th>
                            <th>Müşteri</th>
                            <th>İşlemi Yapan</th>
                            <th>Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td>
                                    <div class="font-weight-bold">{{ $sale->delivered_at ? $sale->delivered_at->format('d.m.Y H:i') : '-' }}</div>
                                    <small class="text-muted">{{ $sale->delivered_at ? $sale->delivered_at->diffForHumans() : '' }}</small>
                                </td>
                                <td><span class="badge badge-dark p-2 px-3">#{{ $sale->pallet_number }}</span></td>
                                <td>{{ $sale->stock->name ?? '-' }}</td>
                                <td><span class="font-weight-bold text-success">{{ number_format($sale->used_quantity, 0) }} KG</span></td>
                                <td>
                                    <div class="customer-tag">
                                        <i class="fas fa-building"></i>
                                        {{ $sale->deliveryCompany->name ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="user-tag">
                                        <div class="user-icon">
                                            {{ strtoupper(substr($sale->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        {{ $sale->user->name ?? '-' }}
                                    </div>
                                </td>
                                <td><span class="status-badge">Teslim Edildi</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="mb-3"><i class="fas fa-receipt fa-4x text-light"></i></div>
                                    <h5 class="text-muted">Kayıt Bulunamadı</h5>
                                    <p class="text-muted small">Henüz bir satış yapılmamış veya filtreleme kriterlerine uygun kayıt yok.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($sales->hasPages())
                <div class="p-4 border-top">
                    {{ $sales->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.selectpicker').selectpicker();
        });
    </script>
@endsection
