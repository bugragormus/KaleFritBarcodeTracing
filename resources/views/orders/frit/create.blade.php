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
<div class="page-title-box d-flex align-items-center">
    <a href="{{ route('orders.frit.index') }}" class="btn btn-light text-primary mr-3">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h4><i class="fas fa-plus-circle mr-2" style="opacity:0.9;"></i> Yeni Frit Siparişi</h4>
        <p>Firma seçin, birden fazla ürün satırı ekleyebilirsiniz. Stok analizi anlık yapılır.</p>
    </div>
</div>

<div class="row">
    <!-- ===== FORM ===== -->
    <div class="col-lg-7 mb-4">
        <div class="card card-modern">
            <div class="card-header">
                <i class="fas fa-fire mr-2 text-primary"></i> Sipariş Detayları
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger" style="border-radius:9px;border:none;">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('orders.frit.store') }}" method="POST" id="fritOrderForm">
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
                            <button type="button" id="addItem" class="btn btn-sm btn-outline-primary" style="border-radius:7px;">
                                <i class="fas fa-plus mr-1"></i> Ürün Ekle
                            </button>
                        </div>

                        <!-- Kalem Başlıkları -->
                        <div class="row mb-1 px-2" style="font-size:12px;color:#6c757d;font-weight:600;text-transform:uppercase;letter-spacing:.04em;">
                            <div class="col-6">Frit Ürünü</div>
                            <div class="col-4">Miktar (KG)</div>
                            <div class="col-2"></div>
                        </div>

                        <!-- Kalem Satırları -->
                        <div id="itemsContainer">
                            <div class="item-row mb-2" data-index="0">
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <select name="items[0][stock_code]" class="form-control form-control-sm stock-select select2-search" data-placeholder="— Tüm Frit —">
                                            <option value="">— Tüm Frit —</option>
                                            @foreach(\App\Models\Stock::orderBy('code')->get() as $stock)
                                                <option value="{{ $stock->code }}">{{ $stock->code }} — {{ $stock->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">
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

                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <button type="button" id="btnAnaliz" class="btn btn-outline-primary" style="border-radius:9px;font-weight:600;">
                            <i class="fas fa-search mr-1"></i> Stok Analiz Et
                        </button>
                        <button type="submit" class="btn btn-primary ml-2" style="border-radius:9px;font-weight:600;">
                            <i class="fas fa-save mr-1"></i> Siparişi Kaydet
                        </button>
                        <a href="{{ route('orders.frit.index') }}" class="btn btn-light ml-2" style="border-radius:9px;">Vazgeç</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== STOK SIDEBAR ===== -->
    <div class="col-lg-5 mb-4">
        <div class="card card-modern">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-boxes mr-2 text-success"></i> Sevke Hazır Frit Stoğu</span>
                <small class="text-muted">Sayfa {{ $stocks->currentPage() }}/{{ $stocks->lastPage() }}</small>
            </div>
            <div class="card-body p-0">
                <div class="px-3 pt-3 pb-1">
                    <p class="text-muted mb-0" style="font-size:12px;">
                        <i class="fas fa-info-circle mr-1"></i> Sevk Onaylı stoklar
                    </p>
                </div>
                @foreach($stocks as $stock)
                    @php $stockKg = \App\Models\Order::getFritStock($stock->code); @endphp
                    <div class="d-flex justify-content-between align-items-center px-3 py-2" style="border-bottom:1px solid #f8f9fa;">
                        <div>
                            <div class="font-weight-bold" style="font-size:13px;">{{ $stock->code }}</div>
                            <div class="text-muted" style="font-size:11px;">{{ $stock->name }}</div>
                        </div>
                        <span class="{{ $stockKg > 0 ? 'text-success font-weight-bold' : 'text-muted' }}" style="font-size:13px;">
                            {{ number_format($stockKg, 0, ',', '.') }} KG
                        </span>
                    </div>
                @endforeach

                <!-- Pagination -->
                @if($stocks->lastPage() > 1)
                    <div class="px-3 py-2 d-flex justify-content-between align-items-center" style="background:#f8f9fa;">
                        @if($stocks->onFirstPage())
                            <span class="btn btn-sm btn-light" style="cursor:default;border-radius:7px;opacity:.5;"><i class="fas fa-chevron-left"></i></span>
                        @else
                            <a href="{{ $stocks->previousPageUrl() }}" class="btn btn-sm btn-light" style="border-radius:7px;"><i class="fas fa-chevron-left"></i></a>
                        @endif
                        <small class="text-muted">{{ $stocks->perPage() * ($stocks->currentPage()-1) + 1 }}–{{ min($stocks->perPage() * $stocks->currentPage(), $stocks->total()) }} / {{ $stocks->total() }}</small>
                        @if($stocks->hasMorePages())
                            <a href="{{ $stocks->nextPageUrl() }}" class="btn btn-sm btn-light" style="border-radius:7px;"><i class="fas fa-chevron-right"></i></a>
                        @else
                            <span class="btn btn-sm btn-light" style="cursor:default;border-radius:7px;opacity:.5;"><i class="fas fa-chevron-right"></i></span>
                        @endif
                    </div>
                @else
                    <div class="d-flex justify-content-between align-items-center px-3 py-2" style="background:#f8f9fa;">
                        <strong>Toplam</strong>
                        <strong class="text-success">{{ number_format(\App\Models\Order::getFritStock(), 0, ',', '.') }} KG</strong>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
let itemIndex = 1;
const stockOptions = `{!! collect(\App\Models\Stock::orderBy('code')->get())->map(function($s){ return '<option value="'.$s->code.'">'.$s->code.' — '.e($s->name).'</option>'; })->implode('') !!}`;

// Ürün Satırı Ekle
$('#addItem').on('click', function () {
    const html = `
    <div class="item-row mb-2" data-index="${itemIndex}">
        <div class="row align-items-center">
            <div class="col-6">
                <select name="items[${itemIndex}][stock_code]" class="form-control form-control-sm stock-select select2-search" data-placeholder="— Tüm Frit —">
                    <option value=""></option>
                    ${stockOptions}
                </select>
            </div>
            <div class="col-4">
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
    // Select2'yi yeni eklenen elemente uygula
    if (typeof $.fn.select2 !== 'undefined') {
        $('#itemsContainer .item-row').last().find('.select2-search').select2({
            theme: 'bootstrap4', language: 'tr', allowClear: true, width: '100%',
            placeholder: function() { return $(this).data('placeholder') || '— Seçin —'; }
        });
    }
    itemIndex++;
    updateRemoveButtons();
});

// Satır Kaldır
$(document).on('click', '.remove-item', function () {
    $(this).closest('.item-row').remove();
    updateRemoveButtons();
});

function updateRemoveButtons() {
    const rows = $('.item-row');
    rows.find('.remove-item').prop('disabled', rows.length === 1);
}

// AJAX Stok Analizi
$('#btnAnaliz').on('click', function () {
    const $btn = $(this);
    $btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Analiz ediliyor...').prop('disabled', true);

    const items = [];
    $('.item-row').each(function () {
        const code = $(this).find('.stock-select').val();
        const qty  = parseFloat($(this).find('.qty-input').val()) || 0;
        if (qty > 0) { items.push({ code, qty }); }
    });

    if (!items.length) {
        alert('En az bir kalem miktarı girin.');
        $btn.html('<i class="fas fa-search mr-1"></i> Stok Analiz Et').prop('disabled', false);
        return;
    }

    const promises = items.map(item =>
        $.get("{{ route('orders.frit.analiz') }}", { qty: item.qty, code: item.code })
    );

    Promise.all(promises).then(results => {
        $btn.html('<i class="fas fa-search mr-1"></i> Stok Analiz Et').prop('disabled', false);
        let html = '';
        let allOk = true;
        results.forEach((r, i) => {
            const label = items[i].code ? items[i].code : 'Tüm Frit';
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
    }).catch((error) => {
        $btn.html('<i class="fas fa-search mr-1"></i> Stok Analiz Et').prop('disabled', false);
        console.error("Analysis error:", error);
        alert('Analiz sırasında hata oluştu.');
    });
});
}); // End $(document).ready
</script>
@endsection
