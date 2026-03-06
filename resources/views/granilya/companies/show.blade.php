@extends('layouts.granilya')

@section('styles')
    <style>
        .company-detail-container {
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

        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 3rem 2rem;
            color: white;
            position: relative;
        }

        .company-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .stat-box {
            background: #ffffff;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .stat-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
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

        .status-pill {
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .pill-delivered { background: #dcfce7; color: #166534; }
        .pill-transfer { background: #e0e7ff; color: #3730a3; }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i { color: #667eea; }
    </style>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('granilya.dashboard') }}"><i class="fas fa-home"></i> Ana Sayfa</a></li>
    <li class="breadcrumb-item"><a href="{{ route('granilya.firma.index') }}">Firmalar</a></li>
    <li class="breadcrumb-item active">{{ $firma->name }}</li>
@endsection

@section('content')
<div class="company-detail-container">
    <div class="container-fluid">
        <!-- Company Header -->
        <div class="premium-card">
            <div class="header-gradient">
                <div class="company-badge"><i class="fas fa-building mr-2"></i>Firma Detay Profili</div>
                <h1 class="display-4 font-weight-bold mb-2">{{ $firma->name }}</h1>
                <p class="lead opacity-80 mb-0">Kurumsal Alım Geçmişi ve Performans Analizi</p>
                
                <div class="mt-4">
                    <a href="{{ route('granilya.firma.edit', $firma->id) }}" class="btn btn-light rounded-pill px-4">
                        <i class="fas fa-edit mr-2"></i>Bilgileri Düzenle
                    </a>
                </div>
            </div>
            
            <div class="p-4 bg-white">
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-box">
                            <div class="stat-value">{{ number_format($firma->getTotalDeliveredWeight(), 0) }}</div>
                            <div class="stat-label">Toplam Alım (KG)</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <div class="stat-value">{{ $uniquePalletCount }}</div>
                            <div class="stat-label">Toplam Palet Sayısı</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <div class="stat-value">{{ $deliveredProductions->count() }}</div>
                            <div class="stat-label">Toplam Çuval Sayısı</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <div class="stat-value"><i class="fas fa-calendar-check text-primary"></i></div>
                            <div class="stat-label">Son Alım: {{ $firma->deliveredProductions()->max('delivered_at') ? \Carbon\Carbon::parse($firma->deliveredProductions()->max('delivered_at'))->format('d.m.Y') : '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Deliveries -->
            <div class="col-xl-12">
                <div class="section-title">
                    <i class="fas fa-history"></i> Satış ve Teslimat Geçmişi
                </div>
                <div class="premium-card">
                    <div class="table-responsive">
                        <table class="table history-table mb-0">
                            <thead>
                                <tr>
                                    <th>Tarih</th>
                                    <th>Palet No</th>
                                    <th>Frit Kodu</th>
                                    <th>Miktar (KG)</th>
                                    <th>İşlemi Yapan</th>
                                    <th>Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deliveredProductions as $prod)
                                    <tr>
                                        <td><strong>{{ $prod->delivered_at ? $prod->delivered_at->format('d.m.Y H:i') : '-' }}</strong></td>
                                        <td><span class="badge badge-dark">#{{ $prod->pallet_number }}</span></td>
                                        <td>{{ $prod->stock->name ?? '-' }}</td>
                                        <td><span class="font-weight-bold text-success">{{ number_format($prod->used_quantity, 0) }} KG</span></td>
                                        <td>{{ $prod->user->name ?? '-' }}</td>
                                        <td><span class="status-pill pill-delivered">Teslim Edildi</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="fas fa-box-open fa-3x text-light mb-3"></i>
                                            <p class="text-muted">Bu firmaya henüz bir teslimat yapılmamış.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
