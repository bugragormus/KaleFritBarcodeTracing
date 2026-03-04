@extends('layouts.granilya')

@section('styles')
<style>
    body, .main-content, .modern-barcode-edit {
        background: #f8f9fa !important;
    }
    .modern-barcode-edit {
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
    
    /* Status Badges */
    .status-badge {
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        text-align: center;
        min-width: 100px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        border: none;
        transition: all 0.3s ease;
    }
    
    .status-badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    .status-waiting {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #212529;
    }
    
    .status-control-repeat {
        background: linear-gradient(135deg, #fd7e14, #e55a00);
        color: white;
    }
    
    .status-pre-approved {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .status-shipment-approved {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
    }
    
    .status-rejected {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }
    
    .status-exceptional {
        background: linear-gradient(135deg, #6f42c1, #5a32a3);
        color: white;
    }
    
    .info-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #e9ecef;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .info-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    
    .info-value {
        font-size: 1rem;
        color: #212529;
        font-weight: 500;
        margin-bottom: 0;
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
    
    .card-modern {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .card-modern .card-body {
        padding: 0;
    }
    
    .form-section {
        padding: 1.5rem;
        background: white;
    }
    
    .btn-modern {
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .btn-primary-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-secondary-modern {
        background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%);
        color: white;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Edit Form Styles */
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
    
    .btn-save-modern {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    
    .btn-cancel-modern {
        background: #e9ecef;
        color: #495057;
    }
    
    #editFormContainer {
        display: none;
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('content')
    <div class="modern-barcode-edit">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h1 class="page-title-modern">
                            <i class="fas fa-barcode"></i> Palet Detay/Düzenle
                        </h1>
                        <p class="page-subtitle-modern">Palet bilgilerini görüntüleyin ve düzenleyin</p>
                    </div>
                    <div class="col-md-5 text-md-right mt-3 mt-md-0">
                        <div class="action-buttons justify-content-md-end">
                            <a href="{{ route('granilya.production.history', $pallet->id) }}" class="btn-modern" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                <i class="fas fa-list"></i> Hareketler
                            </a>
                            <a href="{{ route('granilya.stock.index') }}" class="btn-modern btn-secondary-modern">
                                <i class="fas fa-arrow-left"></i> Geri Dön
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form Card -->
            <div class="card-modern">
                <div class="section-header">
                    <i class="fas fa-edit"></i>
                    Düzenleme Formu
                </div>
                <div class="form-section p-4">
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

                        <div class="action-buttons mt-4">
                            <button type="submit" class="btn-modern btn-save-modern" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
                                <i class="fas fa-save"></i> Kaydet
                            </button>
                            <a href="{{ route('granilya.stock.index') }}" class="btn-modern btn-secondary-modern">
                                <i class="fas fa-times"></i> İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Status Overview Card -->
            <div class="card-modern">
                <div class="section-header">
                    <i class="fas fa-info-circle"></i>
                    Durum Bilgileri
                </div>
                <div class="form-section">
                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-label">Palet Numarası</div>
                            <div class="info-value"><strong>{{ $pallet->pallet_number }}</strong></div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-label">Durum</div>
                            <div class="info-value">{!! $pallet->status_badge !!}</div>
                        </div>

                        <div class="info-card">
                            <div class="info-label"><i class="fas fa-user-edit"></i> Oluşturan</div>
                            <div class="info-value">{{ $pallet->user ? $pallet->user->name : '-' }}</div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-label">Üretim Tarihi</div>
                            <div class="info-value">{{ $pallet->created_at->format('d.m.Y H:i') }}</div>
                        </div>

                        <div class="info-card">
                            <div class="info-label">Frit Kodu (Stok)</div>
                            <div class="info-value">{{ $pallet->stock ? $pallet->stock->name : 'Bulunamadı' }}</div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-label">Şarj Numarası</div>
                            <div class="info-value">{{ $pallet->load_number }}</div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-label">Tane Boyutu</div>
                            <div class="info-value">{{ $pallet->size ? $pallet->size->name : 'Bulunamadı' }}</div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-label">Kırıcı Makina</div>
                            <div class="info-value">{{ $pallet->crusher ? $pallet->crusher->name : 'Bulunamadı' }}</div>
                        </div>

                        <div class="info-card">
                            <div class="info-label">Kullanılan Miktar</div>
                            <div class="info-value">{{ $pallet->used_quantity }} KG</div>
                        </div>

                        <div class="info-card">
                            <div class="info-label">Firma</div>
                            <div class="info-value">{{ $pallet->company ? $pallet->company->name : 'Belirtilmemiş' }}</div>
                        </div>
                    </div>

                    <div class="info-card mt-3" style="width: 100%;">
                        <div class="info-label"><i class="fas fa-sticky-note"></i> Genel Not</div>
                        <div class="info-value">{{ $pallet->general_note ?: 'Not eklenmemiş' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Quantity logic (same as create)
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

