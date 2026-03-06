@extends('layouts.granilya')

@section('styles')
    <!-- bootstrap-select additional library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/css/bootstrap-select.min.css" />
    <style>
        .sales-container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 2.5rem;
            margin: 2rem auto;
            max-width: 1200px;
            border: 1px solid #e9ecef;
        }

        .filter-section {
            background: #f8fafc;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #e2e8f0;
        }

        .page-header {
            text-align: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f8f9fa;
        }

        /* ... existing styles ... */
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .info-card {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .pallet-group-card {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .pallet-group-card:hover {
            border-color: #10b981;
            background: rgba(16, 185, 129, 0.02);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.1);
        }

        .pallet-group-card.selected {
            border-color: #10b981;
            background: rgba(16, 185, 129, 0.05);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .card-selection-marker {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .selected .card-selection-marker {
            background: #10b981;
            border-color: #10b981;
            color: white;
        }

        .pallet-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .pallet-id {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1e293b;
        }

        .pallet-weight {
            font-size: 1.1rem;
            font-weight: 600;
            color: #059669;
            background: #ecfdf5;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .pallet-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            color: #64748b;
            font-size: 0.95rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-item i {
            color: #10b981;
            width: 16px;
        }

        .sales-footer {
            position: sticky;
            bottom: 2rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 1.5rem 2.5rem;
            margin-top: 3rem;
            box-shadow: 0 -10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
        }

        .customer-selector {
            flex: 1;
            max-width: 400px;
        }

        .btn-sell {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-sell:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
            color: white;
        }

        .btn-sell:disabled {
            background: #cbd5e1;
            transform: none;
            box-shadow: none;
            cursor: not-allowed;
        }

        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            color: #64748b;
        }

        .empty-state i {
            font-size: 4rem;
            color: #e2e8f0;
            margin-bottom: 1.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="sales-container">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-shopping-cart"></i> Granilya Satış Ekranı
                    </h1>
                    <p class="text-muted">Sevkiyat için hazır olan palet gruplarını listeleyin ve satışını gerçekleştirin.</p>
                </div>
                <a href="{{ route('granilya.sales.history') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fas fa-history mr-2"></i>Satış Geçmişini Gör
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            @endif

            <!-- Filtreler -->
            <div class="filter-section">
                <form action="{{ route('granilya.sales') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label class="font-weight-bold"><i class="fas fa-pallet mr-1"></i> Palet No (Virgülle ayırın)</label>
                                <input type="text" name="pallet_no" class="form-control" value="{{ request('pallet_no') }}" placeholder="Örn: 1, 2, 5">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label class="font-weight-bold"><i class="fas fa-box mr-1"></i> Frit Kodu (Çoklu Seçim)</label>
                                <select name="stock_id[]" class="form-control selectpicker" data-live-search="true" multiple data-actions-box="true" data-selected-text-format="count > 2" title="Tümü">
                                    @foreach($stocks as $stock)
                                        <option value="{{ $stock->id }}" {{ is_array(request('stock_id')) && in_array($stock->id, request('stock_id')) ? 'selected' : '' }}>{{ $stock->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label class="font-weight-bold"><i class="fas fa-hashtag mr-1"></i> Şarj No</label>
                                <input type="text" name="load_number" class="form-control" value="{{ request('load_number') }}" placeholder="Örn: 123, 124">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="fas fa-filter mr-1"></i> Filtrele
                                </button>
                                <a href="{{ route('granilya.sales') }}" class="btn btn-outline-secondary ml-2">
                                    <i class="fas fa-sync-alt mr-1"></i> Temizle
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="info-card">
                <i class="fas fa-info-circle fa-2x"></i>
                <div>
                    <h5 class="mb-0">Nasıl Kullanılır?</h5>
                    <p class="mb-0 opacity-90">Satışını yapmak istediğiniz palet gruplarının üzerine tıklayarak seçin. Ardından alttaki panelden firmayı seçip "Satışı Onayla" butonuna basın.</p>
                </div>
            </div>

            <form action="{{ route('granilya.sales.store') }}" method="POST" id="salesForm">
                @csrf
                @if($readyPallets->count() > 0)
                    <div class="pallet-groups mt-4">
                        @foreach($readyPallets as $baseNo => $pallets)
                            @php
                                $totalWeight = $pallets->sum('used_quantity');
                                $firstPallet = $pallets->first();
                            @endphp
                            <div class="pallet-group-card" data-pallet-no="{{ $baseNo }}">
                                <input type="checkbox" name="base_pallet_numbers[]" value="{{ $baseNo }}" class="pallet-checkbox d-none">
                                <div class="card-selection-marker">
                                    <i class="fas fa-check"></i>
                                </div>
                                
                                <div class="pallet-header">
                                    <div class="pallet-id">
                                        <i class="fas fa-pallet mr-2 text-success"></i> Palet No: #{{ $baseNo }}
                                    </div>
                                    <div class="pallet-weight">
                                        {{ number_format($totalWeight, 0) }} KG
                                    </div>
                                </div>

                                <div class="pallet-details">
                                    <div class="detail-item">
                                        <i class="fas fa-box"></i>
                                        <span><strong>Frit Kodu:</strong> {{ $firstPallet->stock->name ?? '-' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-hashtag"></i>
                                        <span><strong>Şarj No:</strong> {{ $firstPallet->load_number ?? '-' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-layer-group"></i>
                                        <span><strong>Çuval Sayısı:</strong> {{ $pallets->count() }} Adet</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-ruler-combined"></i>
                                        <span><strong>Boyut:</strong> {{ $firstPallet->size->name ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Footer inside form -->
                    <div class="sales-footer container-fluid">
                        <div class="selection-status text-muted">
                            <span id="selectedCount">0</span> palet grubu seçildi
                        </div>

                        <div class="customer-selector">
                            <select name="company_id" id="company_id" class="form-control selectpicker" data-live-search="true" required>
                                <option value="" disabled selected>Satın alacak firmayı seçin...</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" data-subtext="{{ $company->code }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="button" class="btn-sell" id="submitBtn" disabled>
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>Satışı Tamamla</span>
                        </button>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <h3>Satışa Hazır Palet Bulunmuyor</h3>
                        <p>Henüz tüm çuvalları "Sevk Onaylı" durumuna gelmiş ve 1000 KG'a tamamlanmış bir palet grubu bulunmuyor veya arama kriterlerine uygun kayıt yok.</p>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.selectpicker').selectpicker();

            $('.pallet-group-card').on('click', function() {
                const card = $(this);
                const checkbox = card.find('.pallet-checkbox');
                
                card.toggleClass('selected');
                checkbox.prop('checked', !checkbox.prop('checked'));
                
                updateFooter();
            });

            function updateFooter() {
                const checkedCount = $('.pallet-checkbox:checked').length;
                const companyId = $('#company_id').val();
                
                $('#selectedCount').text(checkedCount);
                
                if (checkedCount > 0 && companyId) {
                    $('#submitBtn').prop('disabled', false);
                } else {
                    $('#submitBtn').prop('disabled', true);
                }
            }

            $('#company_id').on('change', updateFooter);

            $('#submitBtn').on('click', function(e) {
                console.log('Satışı Tamamla tıklandı');
                const form = document.getElementById('salesForm');
                
                const companyName = $('#company_id option:selected').text();
                const palletCount = $('.pallet-checkbox:checked').length;

                if (palletCount === 0) {
                    alert('Lütfen en az bir palet grubu seçin.');
                    return;
                }

                if (!$('#company_id').val()) {
                    alert('Lütfen bir firma seçin.');
                    return;
                }

                const swalTitle = 'Satışı Onaylıyor musunuz?';
                const swalText = `${palletCount} adet palet grubunun ${companyName} firmasına satışı gerçekleştirilecek. Bu işlem geri alınamaz!`;
                
                console.log('Onay penceresi açılıyor...');

                if (typeof Swal !== 'undefined' && Swal.fire) {
                    console.log('Modern Swal2 kullanılıyor');
                    Swal.fire({
                        title: swalTitle,
                        text: swalText,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Evet, Satışı Yap',
                        cancelButtonText: 'İptal',
                        reverseButtons: true
                    }).then((result) => {
                        console.log('Swal2 sonucu:', result);
                        if (result.value || result.isConfirmed) {
                            console.log('Form gönderiliyor (Swal2)...');
                            form.submit();
                        }
                    });
                } else if (typeof swal !== 'undefined') {
                    console.log('Eski swal kullanılıyor');
                    swal({
                        title: swalTitle,
                        text: swalText,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        confirmButtonText: 'Evet, Satışı Yap',
                        cancelButtonText: 'İptal',
                        closeOnConfirm: true
                    }, function(isConfirm) {
                        console.log('Swal sonucu:', isConfirm);
                        if (isConfirm) {
                            console.log('Form gönderiliyor (Swal)...');
                            form.submit();
                        }
                    });
                } else {
                    console.log('Native confirm kullanılıyor');
                    if (confirm(swalText)) {
                        console.log('Form gönderiliyor (Native)...');
                        form.submit();
                    }
                }
            });
        });
    </script>
@endsection
