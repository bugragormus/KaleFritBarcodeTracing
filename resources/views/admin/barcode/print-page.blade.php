@extends('layouts.app')
@section('styles')
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
    <style>
        body, .main-content, .modern-print-page {
            background: #f8f9fa !important;
        }
        
        .modern-print-page {
            background: #f8f9fa;
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
        }
        
        .card-header-modern {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .card-title-modern {
            font-size: 1.3rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .card-title-modern i {
            margin-right: 0.5rem;
            color: #667eea;
        }
        
        .card-subtitle-modern {
            color: #6c757d;
            margin-bottom: 0;
        }
        
        .card-body-modern {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            margin-right: 0.5rem;
            color: #667eea;
            font-size: 0.9rem;
        }
        
        .required {
            color: #dc3545;
            margin-left: 0.25rem;
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
        
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
        }
        
        .error-message i {
            margin-right: 0.25rem;
        }
        
        .info-card {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .info-card h5 {
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .info-card p {
            margin-bottom: 0;
            opacity: 0.9;
        }
        
        .select2-container--default .select2-selection--multiple {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            min-height: 50px;
            font-size: 0.875rem;
        }
        
        .select2-container--default .select2-selection--multiple:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 0.25rem 0.5rem;
            margin: 0.25rem;
            font-size: 0.8rem;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 0.5rem;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #f8f9fa;
        }
        
        .select2-container--default .select2-results__option {
            font-size: 0.875rem;
            padding: 0.5rem;
            white-space: normal;
            word-wrap: break-word;
        }
        
        .select2-dropdown {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background: #667eea;
            color: white;
        }
        
        @media print {
            body * {
                visibility: hidden;
            }
            #section-to-print, #section-to-print * {
                visibility: visible;
            }
            #section-to-print {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
        
        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
            
            .card-body-modern {
                padding: 1.5rem;
            }
        }
    </style>
@endsection
@section('content')
    <div class="modern-print-page">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-print"></i> Barkod Yazdırma
                        </h1>
                        <p class="page-subtitle-modern">Seçilen barkodları yazdırın</p>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="info-card">
                <h5><i class="fas fa-info-circle"></i> Yazdırma İşlemi</h5>
                <p>Yazdırmak istediğiniz barkodları seçin ve yazdırma sayfasına yönlendirileceksiniz</p>
            </div>

            <!-- Modern Card -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-list"></i> Barkod Seçimi
                    </h3>
                    <p class="card-subtitle-modern">
                        Yazdırmak istediğiniz barkodları aşağıdan seçin
                    </p>
                </div>
                
                <div class="card-body-modern">
                    <form class="form" method="GET" action="{{ route('barcode.print') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-qrcode"></i> Barkod Seçiniz
                                        <span class="required">*</span>
                                    </label>
                                    <select class="itemName" style="width: 100%" name="barcode_ids[]" multiple="multiple">
                                    </select>
                                    @if($errors->has('barcode_ids'))
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $errors->first('barcode_ids') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn-modern btn-primary-modern">
                                <i class="fas fa-print"></i> Yazdır
                            </button>
                            <a href="{{ route('barcode.index') }}" class="btn-modern btn-secondary-modern">
                                <i class="fas fa-times"></i> İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $('.itemName').select2({
            placeholder: 'Yazdırmak istediğiniz barkod numarasını giriniz',
            ajax: {
                url: "{{url('/barkod/islemler/print-page-barcodes-ajax')}}",
                type: "get",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            console.log(item);
                            return {
                                text: '#' + item.id + ' - Stok: ' + item.stock_name + ' Şarj: ' + item.load_number + (item.rejected_load_number !== null ? ' + ' + item.rejected_load_number : ''),
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    </script>
@endsection
