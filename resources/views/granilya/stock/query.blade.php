@extends('layouts.granilya')

@section('styles')
    <style>
        body, .main-content, .modern-pallet-query {
            background: #f8f9fa !important;
            min-height: 100vh;
        }

        /* Modern Page Header */
        .page-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .page-header-modern h4 {
            margin: 0;
            font-weight: 700;
            font-size: 28px;
            display: flex;
            align-items: center;
        }
        
        .page-header-modern h4 i {
            margin-right: 15px;
            font-size: 32px;
        }
        
        .page-header-modern p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        
        /* Scanner Card */
        .qr-scanner-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
            overflow: hidden;
            padding: 40px;
            margin-top: 20px;
        }
        
        .scanner-container {
            text-align: center;
            position: relative;
        }
        
        .scanner-animation {
            width: 250px;
            height: 250px;
            margin: 0 auto 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .scanner-animation::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            animation: scan 2s infinite;
        }
        
        @keyframes scan {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .scanner-animation img {
            border-radius: 15px;
            max-width: 100%;
            height: auto;
        }
        
        .scanner-title {
            font-size: 24px;
            font-weight: 700;
            color: #495057;
            margin-bottom: 15px;
        }
        
        .scanner-description {
            color: #6c757d;
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        /* Input Styling */
        .barcode-input-container {
            position: relative;
            max-width: 400px;
            margin: 0 auto;
        }
        
        .barcode-input {
            width: 100%;
            padding: 20px 25px 20px 50px;
            border: 3px solid #e9ecef;
            border-radius: 15px;
            font-size: 18px;
            font-weight: 600;
            text-align: left;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .barcode-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25), 0 8px 25px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }
        
        .barcode-input::placeholder {
            color: #adb5bd;
            font-weight: 500;
        }
        
        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            font-size: 20px;
            z-index: 10;
        }
        
        /* Instructions */
        .instructions {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 25px;
            margin-top: 30px;
            border-left: 4px solid #667eea;
            text-align: left;
        }
        
        .instructions h6 {
            color: #495057;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .instructions h6 i {
            margin-right: 10px;
            color: #667eea;
        }
        
        .instructions ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .instructions li {
            color: #6c757d;
            margin-bottom: 8px;
            line-height: 1.5;
        }
        
        /* Loading Animation */
        .loading-spinner {
            display: none;
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header-modern {
                padding: 20px;
            }
            .page-header-modern h4 {
                font-size: 24px;
            }
            .qr-scanner-card {
                padding: 25px;
            }
            .scanner-animation {
                width: 200px;
                height: 200px;
            }
            .scanner-title {
                font-size: 20px;
            }
            .scanner-description {
                font-size: 14px;
            }
            .barcode-input {
                padding: 15px 20px 15px 45px;
                font-size: 16px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="modern-pallet-query pt-4">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4><i class="fas fa-search"></i>PALET SORGULA</h4>
                        <p>Üretilen paletlere ait detaylı bilgileri hızlıca sorgulayın</p>
                    </div>
                </div>
            </div>

            <!-- Scanner Card -->
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="qr-scanner-card">
                        <div class="scanner-container">
                            <div class="scanner-animation">
                                <img src="{{ asset('assets/images/qr-scan.gif') }}" alt="Barkod Tarayıcı" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3596/3596091.png';">
                            </div>
                            
                            <h3 class="scanner-title">
                                <i class="fas fa-barcode"></i> Palet No ile Sorgulama
                            </h3>
                            
                            <p class="scanner-description">
                                Hızlı erişim için palet numarasını sistemden okutun veya tuşlayın.
                            </p>
                            
                            <div class="barcode-input-container">
                                <i class="fas fa-search input-icon"></i>
                                <!-- Error Handling Display -->
                                @if(session('error'))
                                    <div class="alert alert-danger mb-3 p-2 text-center" style="border-radius: 10px; font-size:14px;">
                                        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                                    </div>
                                @endif
                                
                                <input type="text" id="pallet_number" class="barcode-input" placeholder="Palet numarasını girin..." autofocus>
                            </div>
                            
                            <div class="loading-spinner" id="loading-spinner"></div>
                            
                            <div class="instructions mt-4">
                                <h6><i class="fas fa-info-circle"></i> Kullanım Talimatları</h6>
                                <ul>
                                    <li>Palet numarasını manuel olarak girebilir veya barkod okuyucuyla okutabilirsiniz.</li>
                                    <li>Enter tuşuna basarak sistemde sorgulama işlemini başlatabilirsiniz.</li>
                                    <li>Palet numarası bulunduğunda detay sayfasına otomatik yönlendirileceksiniz.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var APP_URL = {!! json_encode(url('/')) !!};
        
        function showLoading() {
            $('#loading-spinner').show();
        }
        
        function hideLoading() {
            $('#loading-spinner').hide();
        }
        
        function searchPallet(palletId) {
            if (!palletId || palletId.trim() === '') {
                return;
            }
            
            showLoading();
            
            // Redirect to detail page
            setTimeout(function() {
                window.location.replace(APP_URL + "/granilya/palet/" + palletId.trim());
            }, 500);
        }
        
        $("#pallet_number").on("keyup", function(e) {
            if (e.keyCode !== 13) {
                return;
            }
            searchPallet($(this).val());
        });
        
        $("#pallet_number").on("focus", function() {
            if ($(this).val() === $(this).attr('placeholder')) {
                $(this).val('');
            }
        });
        
        $(document).ready(function() {
            $("#pallet_number").focus();
        });
    </script>
@endsection
