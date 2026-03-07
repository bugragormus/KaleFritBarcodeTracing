@extends('layouts.orders')

@section('styles')
<style>
/* Modern Container & Header */
.bulk-title-box {
    background: linear-gradient(135deg, #4f46e5 0%, #764ba2 100%);
    border-radius: 16px; padding: 28px 32px; margin-bottom: 24px; color: #fff;
    box-shadow: 0 10px 25px rgba(79, 70, 229, 0.2);
    position: relative; overflow: hidden;
}
.bulk-title-box::after {
    content: ''; position: absolute; top: -50%; right: -10%; width: 300px; height: 300px;
    background: rgba(255,255,255,0.05); border-radius: 50%;
}
.bulk-title-box h4 { font-size: 22px; font-weight: 800; margin: 0; color: #fff; letter-spacing: -0.02em; }
.bulk-title-box p { margin: 6px 0 0; opacity: 0.9; font-size: 15px; font-weight: 400; }

/* Filter Section Refinements */
.card-modern { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,.04); background: #fff; }
.filter-form-row { display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; }
.filter-group { flex: 1; min-width: 180px; }
.filter-group-wide { flex: 2; min-width: 300px; }
.filter-group-btns { flex: 0 0 auto; display: flex; gap: 8px; }

/* Input Styling Standard */
.form-control-custom {
    height: 44px !important;
    border-radius: 10px !important;
    border: 1.5px solid #edf2f7 !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
    padding: 0 14px !important;
}
.form-control-custom:focus {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
    background-color: #fff !important;
}

/* Select2 Premium Overrides */
.select2-container--bootstrap4 .select2-selection {
    border: 1.5px solid #edf2f7 !important;
    border-radius: 10px !important;
    min-height: 44px !important;
    background-color: #fcfcfd !important;
}
.select2-container--bootstrap4.select2-container--focus .select2-selection {
/* Button & UI Elements */
.btn-modern {
    height: 44px; border-radius: 10px; font-weight: 600; font-size: 14px;
    display: inline-flex; align-items: center; justify-content: center;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    letter-spacing: 0.01em; padding: 0 20px;
}
.btn-modern:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
.btn-modern:active { transform: translateY(0); }

.btn-filter { background: #4f46e5; border: none; color: #fff; }
.btn-filter:hover { background: #4338ca; color: #fff; }
.btn-reset { background: #f1f5f9; border: none; color: #475569; }
.btn-reset:hover { background: #e2e8f0; color: #1e293b; }

.label-premium {
    font-size: 12px; font-weight: 700; color: #64748b;
    margin-bottom: 6px; display: block; text-transform: uppercase; letter-spacing: 0.03em;
}

.table thead th {
    background: #f8fafc; border: none; color: #64748b;
    font-weight: 700; font-size: 11px; text-transform: uppercase;
    letter-spacing: 0.05em; padding: 16px 20px;
}
.table td { vertical-align: middle; padding: 16px 20px; border-bottom: 1px solid #f1f5f9; }

.badge-order-type { border-radius: 6px; font-size: 10px; font-weight: 700; text-transform: uppercase; padding: 4px 8px; }
.item-tag { 
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; 
    padding: 3px 8px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;
}
</style>
@endsection

@section('content')
<div class="bulk-title-box">
    <h4><i class="fas fa-tasks mr-2" style="opacity:.8;"></i> Toplu Sipariş Yönetimi</h4>
    <p>Tüm siparişleri filtreleyin ve durumlarını tek seferde güncelleyin.</p>
</div>

<div class="card card-modern mb-4">
    <div class="card-body p-4">
        <form method="GET" id="filterForm">
            <!-- First Row: Core Filters -->
            <div class="filter-form-row mb-3">
                <div class="filter-group">
                    <label class="label-premium">Tür</label>
                    <select name="type" class="form-control select2-search">
                        <option value="">Tüm Türler</option>
                        <option value="frit" {{ request('type') == 'frit' ? 'selected' : '' }}>Frit</option>
                        <option value="granilya" {{ request('type') == 'granilya' ? 'selected' : '' }}>Granilya</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="label-premium">Durum</label>
                    <select name="status" class="form-control select2-search">
                        <option value="">Tüm Durumlar</option>
                        @foreach(\App\Models\Order::STATUS_LABELS as $val => $lab)
                            <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $lab }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group-wide">
                    <label class="label-premium">Firma</label>
                    <select name="company_ids[]" class="form-control select2-search" multiple>
                        <optgroup label="Frit Firmaları">
                            @foreach($companies as $c)
                                <option value="{{ $c->id }}" {{ is_array(request('company_ids')) && in_array($c->id, request('company_ids')) ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Granilya Firmaları">
                            @foreach($granilyaCompanies as $c)
                                <option value="{{ $c->id }}" {{ is_array(request('company_ids')) && in_array($c->id, request('company_ids')) ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>

            <!-- Second Row: Advanced Filters & Actions -->
            <div class="filter-form-row">
                <div class="filter-group">
                    <label class="label-premium">Frit Kodu</label>
                    <select name="stock_codes[]" class="form-control select2-search" multiple>
                        @foreach($stocks as $s)
                            <option value="{{ $s->code }}" {{ is_array(request('stock_codes')) && in_array($s->code, request('stock_codes')) ? 'selected' : '' }}>{{ $s->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label class="label-premium">Boyut</label>
                    <select name="granilya_sizes[]" class="form-control select2-search" multiple>
                        @foreach($sizes as $sz)
                            <option value="{{ $sz->name }}" {{ is_array(request('granilya_sizes')) && in_array($sz->name, request('granilya_sizes')) ? 'selected' : '' }}>{{ $sz->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label class="label-premium">Başlangıç</label>
                    <input type="date" name="date_from" class="form-control form-control-custom" value="{{ request('date_from') }}">
                </div>
                <div class="filter-group">
                    <label class="label-premium">Bitiş</label>
                    <input type="date" name="date_to" class="form-control form-control-custom" value="{{ request('date_to') }}">
                </div>
                <div class="filter-group-btns">
                    <button type="submit" class="btn btn-modern btn-filter">
                        <i class="fas fa-filter mr-2"></i> Filtrele
                    </button>
                    <a href="{{ route('orders.bulk.index') }}" class="btn btn-modern btn-reset">
                        Temizle
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<form action="{{ route('orders.bulk.update') }}" method="POST" id="bulkForm">
    @csrf
    <div class="card card-modern">
        <div class="card-header bg-white d-flex align-items-center justify-content-between py-3 px-4">
            <div class="d-flex align-items-center" style="gap:12px;">
                <select name="status" class="form-control form-control-custom" style="width:220px;" id="bulkUpdateStatus" required>
                    <option value="">— İşlem Seçin —</option>
                    @foreach(\App\Models\Order::STATUS_LABELS as $val => $lab)
                        <option value="{{ $val }}">{{ $lab }} Durumuna Güncelle</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-modern btn-success px-4">
                    <i class="fas fa-check-double mr-2"></i> Seçilenleri Uygula
                </button>
            </div>
            <div class="selected-badge" id="selectedCountContainer" style="display:none;">
                <span class="badge badge-primary px-3 py-2" style="border-radius:20px; font-size:12px;">
                    <i class="fas fa-info-circle mr-1"></i> <span id="selectedCount">0</span> sipariş seçildi
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th width="40"><input type="checkbox" class="check-all"></th>
                            <th width="60">ID</th>
                            <th>Firma</th>
                            <th width="100">Tür</th>
                            <th>Ürün Bilgisi / Detaylar</th>
                            <th width="120">Durum</th>
                            <th width="140">Tarih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td><input type="checkbox" name="order_ids[]" value="{{ $order->id }}" class="order-checkbox"></td>
                                <td class="text-muted" style="cursor: pointer;" onclick="window.location='{{ $order->type == 'frit' ? route('orders.frit.show', $order) : route('orders.granilya.show', $order) }}'">#{{ $order->id }}</td>
                                <td style="cursor: pointer;" onclick="window.location='{{ $order->type == 'frit' ? route('orders.frit.show', $order) : route('orders.granilya.show', $order) }}'"><strong>{{ $order->company_name }}</strong></td>
                                <td>
                                    <span class="badge {{ $order->type == 'frit' ? 'badge-primary' : 'badge-success' }}" style="border-radius:6px; font-size:10px; font-weight:600; text-transform:uppercase;">
                                        {{ $order->type_label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="mb-1">
                                        @foreach($order->items as $item)
                                            <span class="badge badge-light border mr-1 mb-1" style="font-size: 11px; font-weight: 500;">
                                                <span class="text-primary">{{ $item->stock_code }}</span> 
                                                @if($item->granilya_size) <span class="text-success ml-1">({{ $item->granilya_size }})</span> @endif
                                                <span class="ml-1 text-dark">{{ number_format($item->quantity_kg, 0, ',', '.') }} KG</span>
                                            </span>
                                        @endforeach
                                    </div>
                                    <small class="text-muted">Toplam: <strong>{{ number_format($order->items->sum('quantity_kg'), 0, ',', '.') }} KG</strong> ({{ $order->items->count() }} kalem)</small>
                                </td>
                                <td>
                                    @php $cls = ['open'=>'badge-open','fulfilled'=>'badge-fulfilled','cancelled'=>'badge-cancelled'][$order->status] ?? 'badge-secondary'; @endphp
                                    <span class="badge {{ $cls }} px-2 py-1" style="border-radius:6px; font-size:11px;">{{ $order->getStatusLabel() }}</span>
                                </td>
                                <td class="text-muted" style="font-size:12px;">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-5 text-muted">Sipariş bulunamadı.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
                <div class="p-3 border-top">{{ $orders->links() }}</div>
            @endif
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
$(function() {
    // Select2 Premium Initialization
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select2-search').select2({
            theme: 'bootstrap4',
            language: 'tr',
            placeholder: '— Seçin —',
            allowClear: true,
            width: '100%'
        });
    }

    // Checkbox Logic & Dynamic UI
    $('.check-all').on('change', function() {
        $('.order-checkbox').prop('checked', $(this).prop('checked'));
        updateUI();
    });

    $('.order-checkbox').on('change', function() {
        const allChecked = $('.order-checkbox:checked').length === $('.order-checkbox').length;
        $('.check-all').prop('checked', allChecked);
        updateUI();
    });

    function updateUI() {
        const count = $('.order-checkbox:checked').length;
        const container = $('#selectedCountContainer');
        const countSpan = $('#selectedCount');
        
        if (count > 0) {
            countSpan.text(count);
            container.fadeIn(200);
        } else {
            container.fadeOut(200);
        }
    }

    // Form submission validation
    $('#bulkForm').on('submit', function(e) {
        const count = $('.order-checkbox:checked').length;
        const status = $('#bulkUpdateStatus').val();

        if (count === 0) {
            e.preventDefault();
            Swal.fire({
                title: 'Hata',
                text: 'Lütfen en az bir sipariş seçin.',
                icon: 'error',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }
        if (!status) {
            e.preventDefault();
            Swal.fire({
                title: 'Hata',
                text: 'Lütfen yeni durumu seçin.',
                icon: 'error',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }
    });

    // Row click functionality (optional but nice)
    // Removed to allow text selection but added visual feedback to interactive elements
});
</script>
@endsection
