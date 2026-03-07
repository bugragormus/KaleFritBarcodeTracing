@extends('layouts.orders')

@section('styles')
<style>
.page-title-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 14px;
    padding: 28px 32px;
    margin-bottom: 28px;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.page-title-box::before {
    content: '';
    position: absolute;
    right: -30px;
    top: -30px;
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
}
.page-title-box::after {
    content: '';
    position: absolute;
    right: 50px;
    bottom: -40px;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: rgba(255,255,255,.05);
}
.page-title-box h3 { font-size: 22px; font-weight: 700; margin: 0 0 4px; color:#fff; }
.page-title-box p  { margin: 0; opacity: .82; font-size: 14px; }
.page-title-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 16px; }
.page-title-actions .btn { border-radius: 9px; font-weight: 600; font-size: 13px; }
.stat-icon { width: 52px; height: 52px; border-radius: 13px; display:flex; align-items:center; justify-content:center; font-size: 22px; }
.stat-card { border: none; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.06); }
</style>
@endsection

@section('content')

{{-- Page Title Box --}}
<div class="page-title-box">
    <div class="d-flex align-items-start justify-content-between flex-wrap" style="gap:12px;">
        <div>
            <h3><i class="fas fa-shipping-fast mr-2" style="opacity:.9;"></i> Sipariş Karşılama Sistemi</h3>
            <p>Frit ve Granilya siparişlerini yönetin, stok durumunu anlık takip edin.</p>
        </div>
        <div class="page-title-actions">
            <a href="{{ route('orders.frit.create') }}" class="btn btn-light text-primary">
                <i class="fas fa-fire mr-1" style="color:#2563eb;"></i> Frit Siparişi Ekle
            </a>
            <a href="{{ route('orders.granilya.create') }}" class="btn btn-light text-success">
                <i class="fas fa-layer-group mr-1" style="color:#059669;"></i> Granilya Siparişi Ekle
            </a>
        </div>
    </div>
</div>

{{-- KPI Kartları --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card card" style="border-left: 4px solid #3b82f6;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:.06em;font-weight:600;">Açık Frit Siparişi</p>
                        <h2 class="mb-0 font-weight-bold" style="color:#1e40af;">{{ $openFritOrders }}</h2>
                    </div>
                    <div class="stat-icon" style="background:#dbeafe;">
                        <i class="fas fa-fire" style="color:#2563eb;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card card" style="border-left: 4px solid #10b981;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:.06em;font-weight:600;">Açık Granilya Siparişi</p>
                        <h2 class="mb-0 font-weight-bold" style="color:#065f46;">{{ $openGranilyaOrders }}</h2>
                    </div>
                    <div class="stat-icon" style="background:#d1fae5;">
                        <i class="fas fa-layer-group" style="color:#059669;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card card" style="border-left: 4px solid #ef4444;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:.06em;font-weight:600;">Stok Yetersiz</p>
                        <h2 class="mb-0 font-weight-bold" style="color:#991b1b;">{{ $insufficientCount }}</h2>
                    </div>
                    <div class="stat-icon" style="background:#fee2e2;">
                        <i class="fas fa-exclamation-triangle" style="color:#dc2626;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card card" style="border-left: 4px solid #f59e0b;">
            <div class="card-body">
                <div class="d-flex flex-column justify-content-center">
                    <p class="text-muted mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:.06em;font-weight:600;">Toplam Stok</p>
                    <p class="mb-0 font-weight-bold" style="font-size:14px;color:#92400e;">
                        Frit: <strong>{{ number_format($fritStock, 0, ',', '.') }} KG</strong>
                    </p>
                    <p class="mb-0" style="font-size:13px;color:#6b7280;">
                        Granilya: <strong>{{ number_format($granilyaStock, 0, ',', '.') }} KG</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- İki Ana Kart --}}
<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="card card-modern" style="overflow:hidden;">
            <div style="background:linear-gradient(135deg,#1e3a8a 0%,#2563eb 100%);padding:1.75rem 2rem;">
                <div class="d-flex align-items-center" style="gap:16px;">
                    <div style="width:56px;height:56px;border-radius:14px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-fire" style="font-size:26px;color:#fff;"></i>
                    </div>
                    <div>
                        <h5 style="color:#fff;margin:0;font-weight:700;">Frit Sipariş Karşılama</h5>
                        <p style="color:rgba(255,255,255,.75);margin:0;font-size:13px;">Frit siparişleri ve stok analizi</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <div class="font-weight-bold" style="font-size:22px;color:#1e40af;">{{ $openFritOrders }}</div>
                        <div class="text-muted" style="font-size:12px;">Açık Sipariş</div>
                    </div>
                    <div class="col-6" style="border-left:1px solid #f0f0f0;">
                        <div class="font-weight-bold" style="font-size:22px;color:#065f46;">{{ number_format($fritStock, 0, ',', '.') }}</div>
                        <div class="text-muted" style="font-size:12px;">Mevcut Stok (KG)</div>
                    </div>
                </div>
                <div class="d-flex" style="gap:8px;">
                    <a href="{{ route('orders.frit.index') }}" class="btn btn-primary flex-fill" style="border-radius:9px;font-weight:600;">
                        <i class="fas fa-list mr-1"></i> Siparişleri Gör
                    </a>
                    <a href="{{ route('orders.frit.create') }}" class="btn btn-outline-primary" style="border-radius:9px;font-weight:600;">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card card-modern" style="overflow:hidden;">
            <div style="background:linear-gradient(135deg,#064e3b 0%,#059669 100%);padding:1.75rem 2rem;">
                <div class="d-flex align-items-center" style="gap:16px;">
                    <div style="width:56px;height:56px;border-radius:14px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-layer-group" style="font-size:26px;color:#fff;"></i>
                    </div>
                    <div>
                        <h5 style="color:#fff;margin:0;font-weight:700;">Granilya Sipariş Karşılama</h5>
                        <p style="color:rgba(255,255,255,.75);margin:0;font-size:13px;">Granilya siparişleri ve stok analizi</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <div class="font-weight-bold" style="font-size:22px;color:#065f46;">{{ $openGranilyaOrders }}</div>
                        <div class="text-muted" style="font-size:12px;">Açık Sipariş</div>
                    </div>
                    <div class="col-6" style="border-left:1px solid #f0f0f0;">
                        <div class="font-weight-bold" style="font-size:22px;color:#065f46;">{{ number_format($granilyaStock, 0, ',', '.') }}</div>
                        <div class="text-muted" style="font-size:12px;">Mevcut Stok (KG)</div>
                    </div>
                </div>
                <div class="d-flex" style="gap:8px;">
                    <a href="{{ route('orders.granilya.index') }}" class="btn btn-success flex-fill" style="border-radius:9px;font-weight:600;">
                        <i class="fas fa-list mr-1"></i> Siparişleri Gör
                    </a>
                    <a href="{{ route('orders.granilya.create') }}" class="btn btn-outline-success" style="border-radius:9px;font-weight:600;">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Son Açık Siparişler (son 20) --}}
@if($allOpen->count() > 0)
<div class="card card-modern">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-clock mr-2" style="color:#f59e0b;"></i> Son Açık Siparişler <small class="text-muted ml-1">(son 20)</small></span>
        <span class="badge" style="background:#fef3c7;color:#92400e;border-radius:20px;padding:4px 12px;">{{ $allOpen->count() }} sipariş</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8f9fa;font-size:12px;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;">
                    <tr>
                        <th class="pl-4" style="padding-top:12px;padding-bottom:12px;">Sistem</th>
                        <th>Firma</th>
                        <th>Kalemler</th>
                        <th>Toplam İstenen</th>
                        <th>Stok Durumu</th>
                        <th>Tarih</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allOpen as $order)
                        @php
                            try {
                                $a = $order->analyzeStock();
                            } catch (\Exception $e) {
                                $a = ['is_sufficient' => true, 'deficit_kg' => 0, 'required_kg' => 0, 'available_kg' => 0, 'items' => []];
                            }
                            $totalKg  = $order->items->sum('quantity_kg');
                            $itemList = $order->items->map(fn($i) => $i->stock_code ?: '?')->join(', ');
                            $detailRoute = $order->type === 'frit'
                                ? route('orders.frit.show', $order)
                                : route('orders.granilya.show', $order);
                        @endphp
                        <tr style="cursor:pointer;" onclick="window.location='{{ $detailRoute }}'">
                            <td class="pl-4">
                                @if($order->type === 'frit')
                                    <span class="badge badge-primary" style="border-radius:6px;">Frit</span>
                                @else
                                    <span class="badge badge-success" style="border-radius:6px;">Granilya</span>
                                @endif
                            </td>
                            <td class="font-weight-600">{{ $order->company_name }}</td>
                            <td class="text-muted" style="font-size:12px;max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $itemList ?: '—' }}
                            </td>
                            <td><strong>{{ number_format($totalKg, 0, ',', '.') }} KG</strong></td>
                            <td>
                                @if($a['is_sufficient'])
                                    <span class="badge badge-sufficient px-2 py-1" style="border-radius:6px;">✅ Yeterli</span>
                                @else
                                    <span class="badge badge-insufficient px-2 py-1" style="border-radius:6px;">⚠️ Eksik {{ number_format($a['deficit_kg'], 0, ',', '.') }} KG</span>
                                @endif
                            </td>
                            <td class="text-muted" style="font-size:12px;">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <i class="fas fa-chevron-right text-muted" style="font-size:11px;"></i>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="card card-modern">
    <div class="card-body text-center py-5">
        <div style="width:72px;height:72px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
            <i class="fas fa-clipboard-check" style="font-size:30px;color:#f59e0b;"></i>
        </div>
        <h5 class="text-muted">Açık sipariş bulunmuyor</h5>
        <p class="text-muted">Yeni bir sipariş eklemek için yukarıdaki butonları kullanın.</p>
    </div>
</div>
@endif
@endsection
