@extends('layouts.orders')

@section('content')
@php
    $isFrit     = $order->type === \App\Models\Order::TYPE_FRIT;
    $indexRoute = $isFrit ? route('orders.frit.index') : route('orders.granilya.index');
    $updateRoute = $isFrit ? route('orders.frit.update', $order) : route('orders.granilya.update', $order);
    $deleteRoute = $isFrit ? route('orders.frit.destroy', $order) : route('orders.granilya.destroy', $order);
    $accentColor = $isFrit ? '#2563eb' : '#059669';
    $icon        = $isFrit ? 'fas fa-fire' : 'fas fa-layer-group';
    $label       = $isFrit ? 'Frit' : 'Granilya';
    // Durum badge haritası
    $statusColors = [
        'open'      => ['bg'=>'#dbeafe','color'=>'#1e40af','text'=>'Açık'],
        'fulfilled' => ['bg'=>'#d1fae5','color'=>'#065f46','text'=>'Karşılandı'],
        'cancelled' => ['bg'=>'#fee2e2','color'=>'#991b1b','text'=>'İptal'],
    ];
    $sc = $statusColors[$order->status] ?? ['bg'=>'#f1f5f9','color'=>'#475569','text'=>$order->status];
@endphp

<div class="card mb-4" style="border-radius:15px; border:none; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow:hidden;">
    <div class="card-body p-0">
        <div class="d-flex align-items-stretch flex-wrap flex-md-nowrap">
            <!-- Sol: Menü & Başlık Geri Tuşu -->
            <div class="d-flex align-items-center p-4" style="background: #f8fafc; border-right: 1px solid #edf2f7;">
                <a href="{{ $indexRoute }}" class="btn btn-white shadow-sm mr-3" style="border-radius:12px; width:44px; height:44px; display:flex; align-items:center; justify-content:center; border:1px solid #e2e8f0;">
                    <i class="fas fa-arrow-left text-muted"></i>
                </a>
            </div>
            
            <!-- Orta: Bilgiler -->
            <div class="flex-grow-1 p-4 d-flex align-items-center">
                <div class="d-flex align-items-center flex-wrap" style="gap:20px;">
                    <div style="width:56px; height:56px; background:{{ $accentColor }}15; color:{{ $accentColor }}; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px;">
                        <i class="{{ $icon }}"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center mb-1" style="gap:10px;">
                            <h4 class="mb-0" style="font-weight:800; color:#1e293b; letter-spacing:-0.02em;">Sipariş #{{ $order->id }}</h4>
                            <span class="badge" style="background:{{ $accentColor }}; color:white; border-radius:6px; font-size:11px; padding:3px 8px; text-transform:uppercase; letter-spacing:0.05em;">{{ $label }}</span>
                        </div>
                        <div class="d-flex align-items-center text-muted" style="font-size:13px; gap:12px;">
                            <span><i class="far fa-calendar-alt mr-1"></i> {{ $order->created_at->format('d.m.Y H:i') }}</span>
                            <span><i class="far fa-user mr-1"></i> {{ $order->createdBy->name }}</span>
                            <span class="badge" style="background:{{ $sc['bg'] }}; color:{{ $sc['color'] }}; border-radius:20px; font-size:12px; padding:4px 12px; font-weight:600;">
                                <i class="fas fa-circle mr-1" style="font-size:8px; vertical-align:middle; opacity:0.8;"></i> {{ $sc['text'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sağ: Aksiyonlar & Analiz -->
            <div class="p-4 d-flex align-items-center" style="background: #fafafa;">
                <div class="d-flex align-items-center" style="gap:12px;">
                    @php $a = $analysis ?? $order->analyzeStock(); @endphp
                    @if($a['is_sufficient'])
                        <div class="px-3 py-2 text-success d-flex align-items-center" style="background:#ecfdf5; border-radius:12px; font-size:13px; font-weight:600; border:1px solid #d1fae5;">
                            <i class="fas fa-check-circle mr-2"></i> Stok Yeterli
                        </div>
                    @else
                        <div class="px-3 py-2 text-danger d-flex align-items-center" style="background:#fef2f2; border-radius:12px; font-size:13px; font-weight:600; border:1px solid #fee2e2;">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Eksik {{ number_format($a['deficit_kg'],0,',','.') }} KG
                        </div>
                    @endif
                    
                    <button id="btnCancelOrder" class="btn btn-outline-danger border-0" style="border-radius:12px; background:#fff1f2; color:#be123c; padding:10px 16px; font-weight:600; font-size:13px;" data-url="{{ $deleteRoute }}">
                        <i class="fas fa-trash-alt mr-2"></i> Siparişi Sil
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ $updateRoute }}" method="POST" id="editOrderForm">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- Sol: Düzenleme Formu -->
        <div class="col-lg-8 mb-4">
            <div class="card card-modern">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span><i class="{{ $icon }} mr-2" style="color:{{ $accentColor }};"></i> Sipariş Bilgileri</span>
                    <button type="submit" class="btn btn-sm btn-primary" style="border-radius:7px;font-weight:600;">
                        <i class="fas fa-save mr-1"></i> Kaydet
                    </button>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger" style="border-radius:9px;">
                            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Firma -->
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold" style="font-size:13px;">Firma</label>
                            <select name="company_id" class="form-control select2-search" data-placeholder="— Firma Seçin —" required>
                                <option value=""></option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ $order->company_id == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Durum -->
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold" style="font-size:13px;">Sipariş Durumu</label>
                            <select name="status" class="form-control select2-search" required>
                                <option value="open"      {{ $order->status === 'open'      ? 'selected' : '' }}>Açık</option>
                                <option value="fulfilled" {{ $order->status === 'fulfilled' ? 'selected' : '' }}>Karşılandı</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>İptal</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kalemler -->
                    <div class="form-group mt-2">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="font-weight-bold mb-0" style="font-size:13px;">Sipariş Kalemleri</label>
                            <button type="button" id="addItem" class="btn btn-sm btn-outline-primary" style="border-radius:7px;">
                                <i class="fas fa-plus mr-1"></i> Kalem Ekle
                            </button>
                        </div>

                        <!-- Başlıklar -->
                        <div class="row mb-1 px-2" style="font-size:11px;color:#6c757d;font-weight:600;text-transform:uppercase;letter-spacing:.04em;">
                            @if($isFrit)
                                <div class="col-7">Frit Ürünü</div><div class="col-3">Miktar (KG)</div><div class="col-2"></div>
                            @else
                                <div class="col-4">Frit Kodu</div><div class="col-3">Boyut</div><div class="col-3">Miktar (KG)</div><div class="col-2"></div>
                            @endif
                        </div>

                        <div id="itemsContainer">
                            @foreach($order->items as $i => $item)
                                <div class="item-row mb-2" data-index="{{ $i }}">
                                    <div class="row align-items-center">
                                        @if($isFrit)
                                            <div class="col-7">
                                                <select name="items[{{ $i }}][stock_code]" class="form-control form-control-sm stock-select select2-search" data-placeholder="— Tüm Frit —">
                                                    <option value=""></option>
                                                    @foreach($stocks as $stock)
                                                        <option value="{{ $stock->code }}" {{ $item->stock_code === $stock->code ? 'selected' : '' }}>
                                                            {{ $stock->code }} — {{ $stock->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            <div class="col-4">
                                                <select name="items[{{ $i }}][stock_code]" class="form-control form-control-sm stock-select select2-search" data-placeholder="— Tüm Frit —">
                                                    <option value=""></option>
                                                    @foreach($stocks as $stock)
                                                        <option value="{{ $stock->code }}" {{ $item->stock_code === $stock->code ? 'selected' : '' }}>{{ $stock->code }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <select name="items[{{ $i }}][granilya_size]" class="form-control form-control-sm size-select select2-search" data-placeholder="— Boyut —">
                                                    <option value=""></option>
                                                    @foreach($sizes ?? [] as $size)
                                                        <option value="{{ $size->name }}" {{ $item->granilya_size === $size->name ? 'selected' : '' }}>{{ $size->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        <div class="{{ $isFrit ? 'col-3' : 'col-3' }}">
                                            <input type="number" name="items[{{ $i }}][quantity_kg]" class="form-control form-control-sm qty-input"
                                                   value="{{ $item->quantity_kg }}" placeholder="0.00" step="0.01" min="0.01" required>
                                        </div>
                                        <div class="col-2 text-center">
                                            <button type="button" class="btn btn-sm btn-light remove-item" style="border-radius:6px;" {{ $order->items->count() === 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-trash text-danger"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Notlar -->
                    <div class="form-group">
                        <label class="font-weight-bold" style="font-size:13px;">Notlar</label>
                        <textarea name="notes" class="form-control" rows="2">{{ $order->notes }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sağ: Stok Analiz Özeti -->
        <div class="col-lg-4 mb-4">
            <div class="card card-modern mb-3">
                <div class="card-header"><i class="fas fa-chart-bar mr-2" style="color:#667eea;"></i> Stok Analizi</div>
                <div class="card-body p-0">
                    @forelse($a['items'] ?? [] as $it)
                        @php $ok = $it['is_sufficient']; @endphp
                        <div class="d-flex justify-content-between align-items-center px-3 py-2" style="border-bottom:1px solid #f8f9fa;">
                            <div style="font-size:13px;">
                                <span class="font-weight-bold">{{ $it['stock_code'] ?: 'Tüm Stok' }}</span>
                                @if(!empty($it['granilya_size']))
                                    <span class="badge ml-1" style="background:#ecfdf5;color:#065f46;border-radius:5px;font-size:11px;">{{ $it['granilya_size'] }}</span>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="font-weight-bold" style="color:{{ $ok ? '#065f46' : '#991b1b' }};font-size:13px;">
                                    {{ $ok ? '✅' : '⚠️' }} {{ number_format($it['required_kg'],0,',','.') }} KG
                                </div>
                                @if(!$ok)
                                    <div style="font-size:11px;color:#991b1b;">Eksik: {{ number_format($it['deficit_kg'],0,',','.') }} KG</div>
                                @else
                                    <div style="font-size:11px;color:#065f46;">Stok: {{ number_format($it['available_kg'],0,',','.') }} KG</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-3 text-center text-muted" style="font-size:13px;">Kalem yok</div>
                    @endforelse
                    <div class="px-3 py-2 d-flex justify-content-between" style="background:#f8f9fa;border-top:1px solid #e9ecef;">
                        <span class="font-weight-bold">Toplam İstenen</span>
                        <span class="font-weight-bold">{{ number_format(collect($a['items'] ?? [])->sum('required_kg'),0,',','.') }} KG</span>
                    </div>
                </div>
            </div>

            <!-- Meta bilgi -->
            <div class="card card-modern">
                <div class="card-body py-3 px-3" style="font-size:13px;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tür</span>
                        <strong>{{ $label }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Oluşturan</span>
                        <strong>{{ $order->createdBy->name }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Oluşturma</span>
                        <strong>{{ $order->created_at->format('d.m.Y H:i') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Güncelleme</span>
                        <strong>{{ $order->updated_at->format('d.m.Y H:i') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<style>
/* Select2 Premium Override for Show Page */
.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered {
    display: flex !important; flex-wrap: wrap !important; gap: 4px !important; padding: 4px !important;
    max-height: 120px !important; overflow-y: auto !important;
}
.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
    background: #f0f4ff !important; border: 1px solid #dbeafe !important; color: {{ $accentColor }} !important;
    border-radius: 12px !important; padding: 1px 8px !important; font-size: 12px !important; font-weight: 600 !important;
}
.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
    color: {{ $accentColor }} !important; border: none !important; margin-right: 5px !important;
}
</style>

<script>
let itemIndex = {{ $order->items->count() }};
const isFrit  = {{ $isFrit ? 'true' : 'false' }};

@if($isFrit)
const stockOpts = `{!! collect(\App\Models\Stock::orderBy('code')->get())->map(function($s){ return '<option value="'.$s->code.'">'.e($s->code).' — '.e($s->name).'</option>'; })->implode('') !!}`;
@else
const stockOpts = `{!! collect(\App\Models\Stock::orderBy('code')->get())->map(function($s){ return '<option value="'.$s->code.'">'.e($s->code).'</option>'; })->implode('') !!}`;
const sizeOpts  = `{!! collect(\App\Models\GranilyaSize::orderBy('name')->get())->map(function($s){ return '<option value="'.e($s->name).'">'.e($s->name).'</option>'; })->implode('') !!}`;
@endif

$('#addItem').on('click', function () {
    let rowHtml = '';
    if (isFrit) {
        rowHtml = `<div class="item-row mb-2" data-index="${itemIndex}">
            <div class="row align-items-center">
                <div class="col-7">
                    <select name="items[${itemIndex}][stock_code]" class="form-control form-control-sm stock-select select2-search" data-placeholder="— Tüm Frit —">
                        <option value=""></option>${stockOpts}
                    </select>
                </div>
                <div class="col-3">
                    <input type="number" name="items[${itemIndex}][quantity_kg]" class="form-control form-control-sm qty-input" placeholder="0.00" step="0.01" min="0.01" required>
                </div>
                <div class="col-2 text-center">
                    <button type="button" class="btn btn-sm btn-light remove-item" style="border-radius:6px;"><i class="fas fa-trash text-danger"></i></button>
                </div>
            </div>
        </div>`;
    } else {
        rowHtml = `<div class="item-row mb-2" data-index="${itemIndex}">
            <div class="row align-items-center">
                <div class="col-4">
                    <select name="items[${itemIndex}][stock_code]" class="form-control form-control-sm stock-select select2-search" data-placeholder="— Tüm Frit —">
                        <option value=""></option>${stockOpts}
                    </select>
                </div>
                <div class="col-3">
                    <select name="items[${itemIndex}][granilya_size]" class="form-control form-control-sm size-select select2-search" data-placeholder="— Boyut —">
                        <option value=""></option>${sizeOpts}
                    </select>
                </div>
                <div class="col-3">
                    <input type="number" name="items[${itemIndex}][quantity_kg]" class="form-control form-control-sm qty-input" placeholder="0.00" step="0.01" min="0.01" required>
                </div>
                <div class="col-2 text-center">
                    <button type="button" class="btn btn-sm btn-light remove-item" style="border-radius:6px;"><i class="fas fa-trash text-danger"></i></button>
                </div>
            </div>
        </div>`;
    }
    $('#itemsContainer').append(rowHtml);
    if (typeof $.fn.select2 !== 'undefined') {
        const $newRow = $('#itemsContainer .item-row').last();
        $newRow.find('.select2-search').select2({
            theme: 'bootstrap4', language: 'tr', allowClear: true, width: '100%',
            placeholder: function() { return $(this).data('placeholder') || '— Seçin —'; }
        });
    }
    itemIndex++;
    updateRemoveButtons();
});

$(document).on('click', '.remove-item', function () {
    $(this).closest('.item-row').remove();
    updateRemoveButtons();
});

function updateRemoveButtons() {
    const rows = $('.item-row');
    rows.find('.remove-item').prop('disabled', rows.length === 1);
}
</script>
@endsection
