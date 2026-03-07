@extends('layouts.orders')

@section('content')
<div class="page-header d-flex align-items-center">
    <a href="{{ route('orders.granilya.index') }}" class="btn btn-light mr-3" style="border-radius:9px;">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h4><i class="fas fa-plus-circle mr-2 text-success"></i> Yeni Granilya Siparişi</h4>
        <p>Firma seçin, Frit kodu ve boyut bazlı birden fazla palet ekleyebilirsiniz.</p>
    </div>
</div>

<div class="row">
    <!-- ===== FORM ===== -->
    <div class="col-lg-7 mb-4">
        <div class="card card-modern">
            <div class="card-header">
                <i class="fas fa-layer-group mr-2 text-success"></i> Sipariş Detayları
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger" style="border-radius:9px;border:none;">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('orders.granilya.store') }}" method="POST">
                    @csrf

                    <!-- Firma -->
                    <div class="form-group">
                        <label class="font-weight-bold" style="font-size:14px;">Firma <span class="text-danger">*</span></label>
                        <select name="company_id" class="form-control select2-search" data-placeholder="— Firma Seçin —" required>
                            <option value="">— Firma Seçin —</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sipariş Kalemleri -->
                    <div class="form-group mb-2">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="font-weight-bold mb-0" style="font-size:14px;">Sipariş Kalemleri <span class="text-danger">*</span></label>
                            <button type="button" id="addItem" class="btn btn-sm btn-outline-success" style="border-radius:7px;">
                                <i class="fas fa-plus mr-1"></i> Ürün Ekle
                            </button>
                        </div>

                        <!-- Başlıklar -->
                        <div class="row mb-1 px-2" style="font-size:11px;color:#6c757d;font-weight:600;text-transform:uppercase;letter-spacing:.04em;">
                            <div class="col-4">Frit Kodu</div>
                            <div class="col-3">Boyut</div>
                            <div class="col-3">Miktar (KG)</div>
                            <div class="col-2"></div>
                        </div>

                        <div id="itemsContainer">
                            <div class="item-row mb-2" data-index="0">
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <select name="items[0][stock_code]" class="form-control form-control-sm stock-select select2-search" data-placeholder="— Tüm Frit —">
                                            <option value="">— Tümü —</option>
                                            @foreach($stocks as $stock)
                                                <option value="{{ $stock->code }}">{{ $stock->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <select name="items[0][granilya_size]" class="form-control form-control-sm size-select select2-search" data-placeholder="— Tüm Boyut —">
                                            <option value="">— Tüm Boyut —</option>
                                            @foreach($sizes as $size)
                                                <option value="{{ $size->name }}">{{ $size->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <input type="number" name="items[0][quantity_kg]" class="form-control form-control-sm qty-input"
                                               placeholder="0.00" step="0.01" min="0.01" required>
                                    </div>
                                    <div class="col-2 text-center">
                                        <button type="button" class="btn btn-sm btn-light remove-item" style="border-radius:6px;" disabled>
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notlar -->
                    <div class="form-group">
                        <label class="font-weight-bold" style="font-size:14px;">Notlar</label>
                        <textarea name="notes" class="form-control" rows="2"
                                  placeholder="Termin tarihi, özel talepler vb.">{{ old('notes') }}</textarea>
                    </div>

                    <!-- AJAX Analiz Sonucu -->
                    <div id="analysisResult" class="mb-3" style="display:none;">
                        <div class="p-3 rounded" id="analysisBox">
                            <div id="analysisContent"></div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center flex-wrap">
                        <button type="button" id="btnAnaliz" class="btn btn-outline-success" style="border-radius:9px;font-weight:600;">
                            <i class="fas fa-search mr-1"></i> Stok Analiz Et
                        </button>
                        <button type="submit" class="btn btn-success ml-2" style="border-radius:9px;font-weight:600;">
                            <i class="fas fa-save mr-1"></i> Siparişi Kaydet
                        </button>
                        <a href="{{ route('orders.granilya.index') }}" class="btn btn-light ml-2" style="border-radius:9px;">Vazgeç</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== STOK SIDEBAR ===== -->
    <div class="col-lg-5 mb-4">
        <div class="card card-modern">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-pallet mr-2 text-success"></i> Satışa Hazır Palletler</span>
                <small class="text-muted">Sayfa {{ $granilyaStocks->currentPage() }}/{{ $granilyaStocks->lastPage() }}</small>
            </div>
            <div class="card-body p-0">
                <div class="px-3 pt-3 pb-1">
                    <p class="text-muted mb-0" style="font-size:12px;">
                        <i class="fas fa-info-circle mr-1"></i> Müşteri Transfer (Satışa Hazır) paletler — Frit × Boyut
                    </p>
                </div>
                @foreach($granilyaStocks as $row)
                    <div class="d-flex justify-content-between align-items-center px-3 py-2" style="border-bottom:1px solid #f8f9fa;">
                        <div>
                            <div class="font-weight-bold" style="font-size:13px;">
                                {{ $row->stock->code ?? '?' }}
                                <span class="badge ml-1" style="background:#ecfdf5;color:#065f46;border-radius:5px;font-size:11px;">{{ $row->size->name ?? '—' }}</span>
                            </div>
                            <div class="text-muted" style="font-size:11px;">{{ $row->stock->name ?? '' }}</div>
                        </div>
                        <span class="font-weight-bold text-success" style="font-size:13px;">
                            {{ number_format($row->total_kg, 0, ',', '.') }} KG
                        </span>
                    </div>
                @endforeach

                @if($granilyaStocks->isEmpty())
                    <div class="p-4 text-center text-muted" style="font-size:13px;">
                        <i class="fas fa-inbox mb-2" style="font-size:24px;display:block;"></i>
                        Satışa hazır palet bulunmuyor.
                    </div>
                @endif

                <!-- Pagination -->
                @if($granilyaStocks->lastPage() > 1)
                    <div class="px-3 py-2 d-flex justify-content-between align-items-center" style="background:#f8f9fa;">
                        @if($granilyaStocks->onFirstPage())
                            <span class="btn btn-sm btn-light" style="cursor:default;border-radius:7px;opacity:.5;"><i class="fas fa-chevron-left"></i></span>
                        @else
                            <a href="{{ $granilyaStocks->previousPageUrl() }}" class="btn btn-sm btn-light" style="border-radius:7px;"><i class="fas fa-chevron-left"></i></a>
                        @endif
                        <small class="text-muted">{{ $granilyaStocks->perPage() * ($granilyaStocks->currentPage()-1) + 1 }}–{{ min($granilyaStocks->perPage() * $granilyaStocks->currentPage(), $granilyaStocks->total()) }} / {{ $granilyaStocks->total() }}</small>
                        @if($granilyaStocks->hasMorePages())
                            <a href="{{ $granilyaStocks->nextPageUrl() }}" class="btn btn-sm btn-light" style="border-radius:7px;"><i class="fas fa-chevron-right"></i></a>
                        @else
                            <span class="btn btn-sm btn-light" style="cursor:default;border-radius:7px;opacity:.5;"><i class="fas fa-chevron-right"></i></span>
                        @endif
                    </div>
                @else
                    <div class="d-flex justify-content-between align-items-center px-3 py-2" style="background:#f8f9fa;">
                        <strong>Toplam</strong>
                        <strong class="text-success">{{ number_format($granilyaStocks->sum('total_kg'), 0, ',', '.') }} KG</strong>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let itemIndex = 1;
const stockOpts = `{!! collect(\App\Models\Stock::orderBy('code')->get())->map(function($s){ return '<option value="'.$s->code.'">'.e($s->code).'</option>'; })->implode('') !!}`;
const sizeOpts  = `{!! collect(\App\Models\GranilyaSize::orderBy('name')->get())->map(function($s){ return '<option value="'.e($s->name).'">'.e($s->name).'</option>'; })->implode('') !!}`;

$('#addItem').on('click', function () {
    const html = `
    <div class="item-row mb-2" data-index="${itemIndex}">
        <div class="row align-items-center">
            <div class="col-4">
                <select name="items[${itemIndex}][stock_code]" class="form-control form-control-sm stock-select select2-search" data-placeholder="— Tüm Frit —">
                    <option value=""></option>${stockOpts}
                </select>
            </div>
            <div class="col-3">
                <select name="items[${itemIndex}][granilya_size]" class="form-control form-control-sm size-select select2-search" data-placeholder="— Tüm Boyut —">
                    <option value=""></option>${sizeOpts}
                </select>
            </div>
            <div class="col-3">
                <input type="number" name="items[${itemIndex}][quantity_kg]" class="form-control form-control-sm qty-input"
                       placeholder="0.00" step="0.01" min="0.01" required>
            </div>
            <div class="col-2 text-center">
                <button type="button" class="btn btn-sm btn-light remove-item" style="border-radius:6px;">
                    <i class="fas fa-trash text-danger"></i>
                </button>
            </div>
        </div>
    </div>`;
    $('#itemsContainer').append(html);
    if (typeof $.fn.select2 !== 'undefined') {
        $('#itemsContainer .item-row').last().find('.select2-search').select2({
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

$('#btnAnaliz').on('click', function () {
    const $btn = $(this);
    $btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Analiz ediliyor...').prop('disabled', true);

    const items = [];
    $('.item-row').each(function () {
        const code = $(this).find('.stock-select').val();
        const size = $(this).find('.size-select').val();
        const qty  = parseFloat($(this).find('.qty-input').val()) || 0;
        if (qty > 0) { items.push({ code, size, qty }); }
    });

    if (!items.length) {
        alert('En az bir palet miktarı girin.');
        $btn.html('<i class="fas fa-search mr-1"></i> Stok Analiz Et').prop('disabled', false);
        return;
    }

    const promises = items.map(item =>
        $.get('/siparis-karsilama/granilya/analiz', { qty: item.qty, code: item.code, size: item.size })
    );

    Promise.all(promises).then(results => {
        $btn.html('<i class="fas fa-search mr-1"></i> Stok Analiz Et').prop('disabled', false);
        let html = '';
        let allOk = true;
        results.forEach((r, i) => {
            const label = (items[i].code || 'Tüm') + (items[i].size ? ' / ' + items[i].size : '');
            if (!r.is_sufficient) allOk = false;
            const icon = r.is_sufficient ? '✅' : '⚠️';
            const color = r.is_sufficient ? '#065f46' : '#991b1b';
            html += `<div class="d-flex justify-content-between mb-1" style="font-size:13px;">
                <span><strong>${icon} ${label}</strong></span>
                <span style="color:${color}">
                    ${r.is_sufficient
                        ? 'Yeterli (' + parseFloat(r.available_kg).toLocaleString('tr-TR') + ' KG)'
                        : 'Eksik: ' + parseFloat(r.deficit_kg).toLocaleString('tr-TR') + ' KG'}
                </span>
            </div>`;
        });

        const box = $('#analysisBox');
        box.css({'background': allOk ? '#d1fae5' : '#fee2e2', 'color': allOk ? '#065f46' : '#991b1b', 'border-radius': '10px'});
        $('#analysisContent').html(html);
        $('#analysisResult').show();
    }).catch(() => {
        $btn.html('<i class="fas fa-search mr-1"></i> Stok Analiz Et').prop('disabled', false);
        alert('Analiz sırasında hata oluştu.');
    });
});
</script>
@endsection
