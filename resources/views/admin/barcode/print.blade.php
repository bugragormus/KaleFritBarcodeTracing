@extends('layouts.app')
@section('styles')
    <style>
        body, .main-content, .modern-print-view {
            background: #f8f9fa !important;
        }
        
        .modern-print-view {
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
        
        .btn-success-modern {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-secondary-modern {
            background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%);
            color: white;
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
        
        /* Print Styles */
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
            .page {page-break-inside: avoid;}
        }
        
        /* Print Content Styles */
        .print-content {
            font-size: 55px;
            margin-top: 1rem;
        }
        
        .print-page {
            margin: 0 auto;
            border: 2px solid #6c757d;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .print-page .row {
            margin-top: 1.5rem;
        }
        
        .print-page .col-6 {
            padding: 0.5rem;
        }
        
        .print-page .font-weight-bold {
            font-weight: 700;
            color: #495057;
        }
        
        .print-page .barcode-section {
            text-align: center;
            padding-top: 3rem;
        }
        
        .print-page .barcode-section strong {
            font-size: 1.2em;
            color: #495057;
        }
        
        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
            
            .card-body-modern {
                padding: 1.5rem;
            }
            
            .print-content {
                font-size: 40px;
            }
        }
    </style>
@endsection
@section('content')
    <div class="modern-print-view">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-print"></i> Barkod Yazdırma
                        </h1>
                        <p class="page-subtitle-modern">Seçilen barkodların yazdırılabilir formatı</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <button class="btn-modern btn-success-modern" onclick="window.print(); return false;">
                            <i class="fas fa-print"></i> Yazdır
                        </button>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="info-card">
                <h5><i class="fas fa-info-circle"></i> Yazdırma Hazır</h5>
                <p>Barkodlar yazdırma için hazır. Yazdır butonuna tıklayarak yazdırma işlemini başlatabilirsiniz</p>
            </div>

            <!-- Modern Card -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-qrcode"></i> Barkod Etiketleri
                    </h3>
                    <p class="card-subtitle-modern">
                        Her barkod için ayrı etiket oluşturulacak
                    </p>
                </div>
                
                <div class="card-body-modern">
                    <div id="section-to-print" class="print-content">
                        @foreach($barcodes as $barcode)
                            <div class="print-page">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="font-weight-bold">Üretim Tarihi:</span><br/>
                                            <span class="font-weight-bold">Stok Adı:</span><br/>
                                            <span class="font-weight-bold">Stok Kodu:</span><br/>
                                            <span class="font-weight-bold">Fırın No:</span><br/>
                                            <span class="font-weight-bold">Parti Numarası:</span><br/>
                                            <span class="font-weight-bold">Şarj Numarası:</span><br/>
                                            <span class="font-weight-bold">Miktar:</span><br/>
                                            <span class="font-weight-bold">Oluşturan:</span><br/>
                                        </div>
                                        <div class="col-6">
                                            <span style="font-size: 40px;">{{ $barcode->created_at->tz('Europe/Istanbul')->format('d-m-Y H:i') }}</span><br/>
                                            <span>{{ $barcode->stock->name}}</span><br/>
                                            <span>{{ $barcode->stock->code}}</span><br/>
                                            <span>{{ $barcode->kiln->name}}</span><br/>
                                            <span>{{ $barcode->party_number}}</span><br/>
                                            <span>{{ $barcode->load_number }} {{ !is_null($barcode->rejected_load_number) ? ' + ' . $barcode->rejected_load_number : '' }}</span><br/>
                                            <span>{{ $barcode->quantity->quantity . " KG"}}</span><br/>
                                            <span>{{ $barcode->createdBy->registration_number}}</span><br/>
                                        </div>
                                        <div class="col-12 barcode-section">
                                            {!! DNS1D::getBarcodeSVG((string)$barcode->id, 'C128', 8, 300); !!}
                                            <br>
                                            <strong>{{ $barcode->id }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="page-break-before: always"></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
