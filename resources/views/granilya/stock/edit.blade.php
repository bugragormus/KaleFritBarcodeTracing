@extends('layouts.granilya')

@section('styles')
<style>
    body, .main-content, .modern-pallet-edit {
        background: #f8f9fa !important;
    }
    .modern-pallet-edit {
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
    
    .card-modern {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 15px 15px 0 0;
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
    }
    
    .section-header i {
        margin-right: 0.75rem;
        font-size: 1.2rem;
    }
    
    .form-section {
        padding: 2rem;
    }
    
    .form-group label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .form-control, .custom-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
        height: auto;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .custom-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.1);
    }
    
    .btn-modern {
        border-radius: 10px;
        padding: 0.8rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
    }
    
    .btn-primary-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-secondary-modern {
        background: #e9ecef;
        color: #495057;
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        color: inherit;
        text-decoration: none;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .status-indicator {
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        text-align: center;
        min-width: 100px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .status-waiting { background: linear-gradient(135deg, #ffc107, #e0a800); color: #212529; }
    .status-pre-approved { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
    .status-shipment-approved { background: linear-gradient(135deg, #17a2b8, #138496); color: white; }
    .status-rejected { background: linear-gradient(135deg, #dc3545, #c82333); color: white; }
</style>
@endsection

@section('content')
<div class="modern-pallet-edit">
    <div class="container-fluid">
        <div class="page-header-modern">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title-modern">
                        <i class="fas fa-pallet"></i> Palet Düzenle
                    </h1>
                    <p class="page-subtitle-modern">Palet bilgilerini ve üretim detaylarını güncelleyin</p>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('granilya.stock.index') }}" class="btn-modern btn-secondary-modern">
                        <i class="fas fa-arrow-left"></i> Geri Dön
                    </a>
                </div>
            </div>
        </div>

        <div class="card-modern">
            <div class="section-header">
                <i class="fas fa-edit"></i>
                Düzenleme Formu - {{ $pallet->pallet_number }}
            </div>
            <div class="form-section">
                <form action="{{ route('granilya.production.update', $pallet->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Frit Kodu</label>
                                <select name="stock_id" class="custom-select shadow-sm" required>
                                    @foreach($stocks as $stock)
                                        <option value="{{ $stock->id }}" {{ $pallet->stock_id == $stock->id ? 'selected' : '' }}>
                                            {{ $stock->code }} - {{ $stock->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Şarj No</label>
                                <input type="text" name="load_number" class="form-control" value="{{ $pallet->load_number }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tane Boyutu</label>
                                <select name="size_id" class="custom-select" id="size_id" required>
                                    @foreach($sizes as $size)
                                        <option value="{{ $size->id }}" {{ $pallet->size_id == $size->id ? 'selected' : '' }}>
                                            {{ $size->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kırıcı Makina</label>
                                <select name="crusher_id" class="custom-select" required>
                                    @foreach($crushers as $crusher)
                                        <option value="{{ $crusher->id }}" {{ $pallet->crusher_id == $crusher->id ? 'selected' : '' }}>
                                            {{ $crusher->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Firma</label>
                                <select name="company_id" class="custom-select" required>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ $pallet->company_id == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div id="quantity_select_div" style="{{ optional($pallet->size)->name == 'TOZ' ? 'display:none' : '' }}">
                                <div class="form-group">
                                    <label>Miktar (KG)</label>
                                    <select name="quantity_id" class="custom-select" id="quantity_id">
                                        <option value="">Seçiniz</option>
                                        @foreach($quantities as $quantity)
                                            <option value="{{ $quantity->id }}" {{ $pallet->quantity_id == $quantity->id ? 'selected' : '' }}>
                                                {{ $quantity->quantity }} KG
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="quantity_input_div" style="{{ optional($pallet->size)->name == 'TOZ' ? '' : 'display:none' }}">
                                <div class="form-group">
                                    <label>Miktar (KG - Serbest Giriş)</label>
                                    <input type="number" step="0.01" name="custom_quantity" class="form-control" value="{{ $pallet->custom_quantity }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Palet No</label>
                                <input type="text" name="pallet_number" class="form-control" value="{{ $pallet->pallet_number }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Durum</label>
                                <select name="status" class="custom-select">
                                    @foreach(\App\Models\GranilyaProduction::getStatusList() as $key => $value)
                                        <option value="{{ $key }}" {{ $pallet->status == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Genel Not</label>
                                <input type="text" name="general_note" class="form-control" value="{{ $pallet->general_note }}">
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn-modern btn-primary-modern">
                            <i class="fas fa-save"></i> Değişiklikleri Kaydet
                        </button>
                        <a href="{{ route('granilya.stock.index') }}" class="btn-modern btn-secondary-modern">
                            İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#size_id').change(function() {
            var sizeText = $(this).find('option:selected').text().trim();
            if (sizeText === 'TOZ') {
                $('#quantity_select_div').hide();
                $('#quantity_input_div').show();
                $('#quantity_id').val('');
            } else {
                $('#quantity_select_div').show();
                $('#quantity_input_div').hide();
                $('input[name="custom_quantity"]').val('');
            }
        });
    });
</script>
@endsection
