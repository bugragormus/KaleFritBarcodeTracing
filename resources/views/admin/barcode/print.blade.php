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
        
        /* Print Styles for Thermal Label Printer */
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
                width: 100%;
            }
            .thermal-label {
                page-break-inside: avoid;
                margin: 0;
                padding: 0;
                border: none;
                box-shadow: none;
            }
            .no-print {
                display: none !important;
            }
        }
        
        /* Thermal Label Specific Styles */
        .thermal-label {
            width: 100mm; /* Standard thermal label width */
            min-height: 40mm; /* Minimum height for content */
            margin: 0 auto 2mm auto; /* 2mm spacing between labels */
            padding: 2mm;
            background: white;
            border: 1px solid #ddd;
            box-sizing: border-box;
            font-family: 'Courier New', monospace;
            font-size: 10pt;
            line-height: 1.2;
        }
        
        .label-header {
            text-align: center;
            margin-bottom: 2mm;
            border-bottom: 1px solid #000;
            padding-bottom: 1mm;
        }
        
        .label-title {
            font-size: 12pt;
            font-weight: bold;
            margin: 0;
        }
        
        .label-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2mm;
            margin-bottom: 3mm;
        }
        
        .label-field {
            margin-bottom: 1mm;
        }
        
        .label-field-label {
            font-weight: bold;
            font-size: 8pt;
            color: #333;
        }
        
        .label-field-value {
            font-size: 9pt;
            color: #000;
            word-break: break-all;
        }
        
        .barcode-section {
            text-align: center;
            margin-top: 2mm;
            padding-top: 2mm;
            border-top: 1px solid #000;
        }
        
        .barcode-svg {
            max-width: 100%;
            height: auto;
        }
        
        .barcode-number {
            font-size: 10pt;
            font-weight: bold;
            margin-top: 1mm;
        }
        
        /* Preview styles for screen */
        .print-preview {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .print-preview h6 {
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
            
            .card-body-modern {
                padding: 1.5rem;
            }
            
            .thermal-label {
                width: 90mm;
                font-size: 9pt;
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
                        <p class="page-subtitle-modern">Thermal Etiket Yazıcısı için Optimize Edilmiş Format</p>
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
                <h5><i class="fas fa-info-circle"></i> Thermal Yazıcı Optimizasyonu</h5>
                <p>Barkodlar Argox OS 214 Plus Series PPLA gibi thermal etiket yazıcıları için optimize edildi. Her etiket 100mm genişliğinde ve uygun boyutlarda.</p>
            </div>

            <!-- Print Preview Info -->
            <div class="print-preview no-print">
                <h6><i class="fas fa-eye"></i> Yazdırma Önizlemesi</h6>
                <p class="mb-0">Aşağıda yazdırılacak etiketlerin önizlemesi görünmektedir. Yazdır butonuna tıklayarak thermal yazıcıda çıktı alabilirsiniz.</p>
            </div>

            <!-- Modern Card -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-qrcode"></i> Thermal Etiket Formatı
                    </h3>
                    <p class="card-subtitle-modern">
                        Her barkod için ayrı thermal etiket (100mm x 40mm)
                    </p>
                </div>
                
                <div class="card-body-modern">
                    <div id="section-to-print">
                        @foreach($barcodes as $barcode)
                            <div class="thermal-label">
                                <!-- Etiket Başlığı -->
                                <div class="label-header">
                                    <div class="label-title">BARKOD ETİKETİ</div>
                                </div>
                                
                                <!-- Etiket İçeriği -->
                                <div class="label-content">
                                    <div class="label-field">
                                        <div class="label-field-label">Üretim Tarihi:</div>
                                        <div class="label-field-value">{{ $barcode->created_at->tz('Europe/Istanbul')->format('d-m-Y H:i') }}</div>
                                    </div>
                                    
                                    <div class="label-field">
                                        <div class="label-field-label">Stok Adı:</div>
                                        <div class="label-field-value">{{ $barcode->stock->name }}</div>
                                    </div>
                                    
                                    <div class="label-field">
                                        <div class="label-field-label">Stok Kodu:</div>
                                        <div class="label-field-value">{{ $barcode->stock->code }}</div>
                                    </div>
                                    
                                    <div class="label-field">
                                        <div class="label-field-label">Fırın No:</div>
                                        <div class="label-field-value">{{ $barcode->kiln->name }}</div>
                                    </div>
                                    
                                    <div class="label-field">
                                        <div class="label-field-label">Parti No:</div>
                                        <div class="label-field-value">{{ $barcode->party_number }}</div>
                                    </div>
                                    
                                    <div class="label-field">
                                        <div class="label-field-label">Şarj No:</div>
                                        <div class="label-field-value">{{ $barcode->load_number }}{{ !is_null($barcode->rejected_load_number) ? ' + ' . $barcode->rejected_load_number : '' }}</div>
                                    </div>
                                    
                                    <div class="label-field">
                                        <div class="label-field-label">Miktar:</div>
                                        <div class="label-field-value">{{ $barcode->quantity->quantity }} KG</div>
                                    </div>
                                    

                                </div>
                                
                                <!-- Barkod Bölümü -->
                                <div class="barcode-section">
                                    {!! DNS1D::getBarcodeSVG((string)$barcode->id, 'C128', 3, 80); !!}
                                    <div class="barcode-number">{{ $barcode->id }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
