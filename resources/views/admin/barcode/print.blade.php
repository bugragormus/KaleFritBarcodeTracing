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
            /* Remove default page margins to prevent unexpected first blank space */
            @page { margin: 0; }

            /* Hide app chrome completely so it doesn't reserve space on printed page */
            header, footer,
            .modern-header,
            .modern-footer,
            .main-navbar,
            .top-navbar,
            .page-header-modern { display: none !important; }

            /* Only show the printable section */
            body * { visibility: hidden; }
            #section-to-print, #section-to-print * { visibility: visible; }

            /* Reset layout spacing for print */
            body { margin: 0 !important; padding: 0 !important; display: block !important; }
            .main-content { padding: 0 !important; }
            .modern-print-view, .container-fluid { padding: 0 !important; margin: 0 !important; background: #fff !important; }
            .card-body-modern { padding: 0 !important; }

            /* Ensure the printable area starts at the very top */
            #section-to-print { position: absolute; left: 0; top: 0; right: 0; }

            .page { page-break-inside: avoid; }
            .page-break {page-break-before: always;}
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
                
                    <div class="card-body-modern">
                        <div id="section-to-print" style="font-size: 55px;" class="m-t-5">
                            @foreach($barcodes as $barcode)
                                <div class="mx-auto border border-secondary rounded page p-5">
                                    <div class="container">
                                        <div class="row m-t-30">
                                            <div class="col-6">
                                                <span class="font-weight-bold"> Üretim Tarihi :</span><br/>
                                                <span class="font-weight-bold"> Stok Adı :</span><br/>
                                                <span class="font-weight-bold"> Stok Kodu :</span><br/>
                                                <span class="font-weight-bold"> Fırın No :</span><br/>
                                                <span class="font-weight-bold"> Parti Numarası :</span><br/>
                                                <span class="font-weight-bold"> Şarj Numarası :</span><br/>
                                                <span class="font-weight-bold"> Miktar :</span><br/>
                                                <span class="font-weight-bold"> Oluşturan :</span><br/>
                                            </div>
                                            <div class="col-6">
                                                <span style="font-size: 40px;"> {{ $barcode->created_at->tz('Europe/Istanbul')->format('d.m.Y H:i') }}</span><br/>
                                                <span> {{ $barcode->stock->name}}</span><br/>
                                                <span> {{ $barcode->stock->code}}</span><br/>
                                                <span> {{ $barcode->kiln->name}}</span><br/>
                                                <span> {{ $barcode->party_number}}</span><br/>
                                                <span> {{ $barcode->load_number }} {{ !is_null($barcode->rejected_load_number) ? ' + ' . $barcode->rejected_load_number : '' }}</span><br/>
                                                <span> {{ $barcode->quantity->quantity . " KG"}}</span><br/>
                                                <span> {{ $barcode->createdBy->registration_number}}</span><br/>
                                            </div>
                                            <div class="col-12" style="text-align: center; padding-top: 100px;">
                                            {!! DNS1D::getBarcodeSVG((string)$barcode->id, 'C128', 8, 300); !!}
                                            <br>
                                                <strong>{{ $barcode->id }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p style="page-break"></p>
                            @endforeach
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
