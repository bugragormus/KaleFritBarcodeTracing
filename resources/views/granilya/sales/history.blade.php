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
        <div class="page-header d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="fas fa-history text-primary"></i> Satış Geçmişi
            </h1>
            <a href="{{ route('granilya.sales') }}" class="btn btn-outline-primary rounded-pill px-4">
                <i class="fas fa-shopping-cart mr-2"></i>Aktif Satış Ekranı
            </a>
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
