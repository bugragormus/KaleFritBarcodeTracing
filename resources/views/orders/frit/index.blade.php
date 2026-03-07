@extends('layouts.orders')

@section('styles')
<style>
.page-title-box {
    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
    border-radius: 14px;
    padding: 24px 28px;
    margin-bottom: 24px;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.15);
}
.page-title-box h4 { font-size: 20px; font-weight: 700; margin: 0; color: #fff; }
.page-title-box p { margin: 4px 0 0; opacity: 0.85; font-size: 14px; }
.page-title-box .btn { border-radius: 9px; font-weight: 600; font-size: 13px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
</style>
@endsection

@section('content')
<div class="page-title-box d-flex align-items-center justify-content-between">
    <div>
        <h4><i class="fas fa-fire mr-2" style="opacity:.9;"></i> Frit Siparişleri</h4>
        <p>Toplam sevke hazır Frit stoku: <strong>{{ number_format($totalFritStock, 0, ',', '.') }} KG</strong></p>
    </div>
    <a href="{{ route('orders.frit.create') }}" class="btn btn-light text-primary">
        <i class="fas fa-plus mr-1"></i> Yeni Sipariş
    </a>
</div>

<!-- Filtre Paneli -->
<div class="card card-modern mb-4">
    <div class="card-body py-3 px-4">
        <form method="GET" action="{{ route('orders.frit.index') }}" class="row align-items-end">
            <div class="col-md-3 mb-2 mb-md-0">
                <label class="form-label" style="font-size:12px;font-weight:600;color:#6c757d;text-transform:uppercase;letter-spacing:.04em;">Firma</label>
                <select name="company_id" class="form-control form-control-sm select2-search">
                    <option value="">Tüm Firmalar</option>
                    @foreach($companies as $c)
                        <option value="{{ $c->id }}" {{ request('company_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2 mb-md-0">
                <label class="form-label" style="font-size:12px;font-weight:600;color:#6c757d;text-transform:uppercase;letter-spacing:.04em;">Durum</label>
                <select name="status" class="form-control form-control-sm select2-search">
                    <option value="">Tüm Durumlar</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Açık</option>
                    <option value="fulfilled" {{ request('status') == 'fulfilled' ? 'selected' : '' }}>Karşılandı</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>İptal</option>
                </select>
            </div>
            <div class="col-md-2 mb-2 mb-md-0">
                <label class="form-label" style="font-size:12px;font-weight:600;color:#6c757d;text-transform:uppercase;letter-spacing:.04em;">Başlangıç</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2 mb-2 mb-md-0">
                <label class="form-label" style="font-size:12px;font-weight:600;color:#6c757d;text-transform:uppercase;letter-spacing:.04em;">Bitiş</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius:7px;font-weight:600;"><i class="fas fa-search mr-1"></i> Filtrele</button>
                <a href="{{ route('orders.frit.index') }}" class="btn btn-light btn-sm ml-2" style="border-radius:7px;">Temizle</a>
            </div>
        </form>
    </div>
</div>

@if($orders->isEmpty())
<div class="card card-modern">
    <div class="card-body text-center py-5">
        <div style="width:72px;height:72px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
            <i class="fas fa-fire" style="font-size:30px;color:#2563eb;"></i>
        </div>
        <h5 class="text-muted">{{ request()->hasAny(['company_id','status','date_from','date_to']) ? 'Filtreye uygun sipariş bulunamadı.' : 'Henüz frit siparişi yok.' }}</h5>
        <a href="{{ route('orders.frit.create') }}" class="btn btn-primary mt-3" style="border-radius:9px;">Yeni Sipariş Ekle</a>
    </div>
</div>
@else
<div class="card card-modern">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-list mr-2" style="color:#667eea;"></i> Sipariş Listesi</span>
        <span class="badge" style="background:#e8eaff;color:#4338ca;border-radius:20px;padding:4px 12px;">{{ $orders->count() }} adet</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="fritOrdersTable">
                <thead style="background:#f8f9fa;font-size:11px;text-transform:uppercase;letter-spacing:.06em;color:#6c757d;">
                    <tr>
                        <th class="pl-4" style="padding-top:12px;padding-bottom:12px;">#</th>
                        <th>Firma</th>
                        <th>Kalem Sayısı</th>
                        <th>Toplam İstenen</th>
                        <th>Stok Durumu</th>
                        <th>Sipariş Durumu</th>
                        <th>Oluşturan</th>
                        <th>Tarih</th>
                        <th class="text-center">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        @php
                            $a = $order->analyzeStock();
                            $totalKg = $order->items->sum('quantity_kg');
                        @endphp
                        <tr>
                            <td class="pl-4 text-muted" style="font-size:13px;">{{ $order->id }}</td>
                            <td><strong>{{ $order->company_name }}</strong></td>
                            <td>
                                <span class="badge" style="background:#e8eaff;color:#4338ca;border-radius:6px;padding:4px 10px;">
                                    {{ $order->items->count() }} kalem
                                </span>
                            </td>
                            <td><strong>{{ number_format($totalKg, 0, ',', '.') }} KG</strong></td>
                            <td>
                                @if($a['is_sufficient'])
                                    <span class="badge badge-sufficient px-2 py-1" style="border-radius:6px;font-size:11px;">✅ Stok Yeterli</span>
                                @else
                                    <span class="badge badge-insufficient px-2 py-1" style="border-radius:6px;font-size:11px;" title="Eksik: {{ number_format($a['deficit_kg'],0,',','.') }} KG">⚠️ Eksik {{ number_format($a['deficit_kg'],0,',','.') }} KG</span>
                                @endif
                            </td>
                            <td>
                                @php $cls = ['open'=>'badge-open','fulfilled'=>'badge-fulfilled','cancelled'=>'badge-cancelled'][$order->status] ?? 'badge-cancelled'; @endphp
                                <span class="badge {{ $cls }} px-2 py-1" style="border-radius:6px;font-size:11px;">{{ $order->getStatusLabel() }}</span>
                            </td>
                            <td class="text-muted" style="font-size:13px;">{{ $order->createdBy->name }}</td>
                            <td class="text-muted" style="font-size:12px;">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('orders.frit.show', $order) }}" class="btn btn-sm btn-light" style="border-radius:6px;" title="Detay / Düzenleme">
                                    <i class="fas fa-edit" style="color:#667eea;"></i>
                                </a>
                                @if($order->notes)
                                    <button class="btn btn-sm btn-light ml-1" style="border-radius:6px;" title="{{ $order->notes }}" data-toggle="tooltip">
                                        <i class="fas fa-sticky-note text-warning"></i>
                                    </button>
                                @endif
                                <button class="btn btn-sm btn-light btn-delete-order ml-1" data-id="{{ $order->id }}" data-url="{{ route('orders.frit.destroy', $order) }}" style="border-radius:6px;">
                                    <i class="fas fa-times text-danger"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    if ($('#fritOrdersTable').length) {
        $('#fritOrdersTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/tr.json' },
            order: [[0, 'desc']], pageLength: 25, responsive: true,
        });
    }
    $('[data-toggle="tooltip"]').tooltip();

    });
});
</script>
@endsection
