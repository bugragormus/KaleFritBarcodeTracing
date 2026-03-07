@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center mb-5">
                    <a href="{{ url('/') }}" class="logo logo-admin">
                        <img src="{{ asset('assets/images/kale-seeklogo.png') }}" height="40" alt="logo">
                    </a>
                    <h4 class="font-18 text-muted mt-3">Lütfen Çalışmak İstediğiniz Sistemi Seçin</h4>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <!-- Frit Üretim Sistemi Kartı -->
            <div class="col-md-5 mb-4">
                <div class="card card-custom hover-elevation text-center h-100">
                    <div class="card-body p-5">
                        <div class="icon-circle bg-primary-light mx-auto mb-4">
                            <i class="mdi mdi-factory text-primary" style="font-size: 40px;"></i>
                        </div>
                        <h4 class="card-title font-20 mb-3">Frit Üretim Sistemi</h4>
                        <p class="text-muted mb-4">
                            Barkod oluşturma, stok yönetimi, kalite kontrol ve sevkiyat işlemlerinin yapıldığı ana sistem.
                        </p>
                        
                        <form method="POST" action="{{ route('system.selection.store') }}">
                            @csrf
                            <input type="hidden" name="system" value="frit">
                            <button type="submit" class="btn btn-primary btn-lg btn-block waves-effect waves-light mt-auto">
                                Frit Sistemine Gir <i class="mdi mdi-arrow-right ml-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Granilya Sistemi Kartı -->
            <div class="col-md-5 mb-4">
                <div class="card card-custom hover-elevation text-center h-100 border-info">
                    <div class="card-body p-5">
                        <div class="icon-circle bg-info-light mx-auto mb-4">
                            <i class="mdi mdi-hexagon-multiple text-info" style="font-size: 40px;"></i>
                        </div>
                        <h4 class="card-title font-20 mb-3 text-info">Granilya Sistemi</h4>
                        <p class="text-muted mb-4">
                            Granilya Üretim Süreçleri, Kalite Kontrol ve Dijital Takip Sistemi.
                        </p>
                        
                        <form method="POST" action="{{ route('system.selection.store') }}">
                            @csrf
                            <input type="hidden" name="system" value="granilya">
                            <button type="submit" class="btn btn-info btn-lg btn-block waves-effect waves-light mt-auto">
                                Granilya Sistemine Gir <i class="mdi mdi-arrow-right ml-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12 text-center">
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-muted">
                        <i class="mdi mdi-logout mr-1"></i> Çıkış Yap ve Farklı Kullanıcı ile Gir
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Custom CSS for the system selection cards -->
    <style>
        body {
            background-color: #f5f6f8;
        }
        .card-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .hover-elevation:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .bg-primary-light {
            background-color: rgba(98, 110, 212, 0.1);
        }
        .bg-info-light {
            background-color: rgba(40, 167, 69, 0.1); /* Assuming info is green/blue, adjusted if needed based on the template */
        }
        .text-info {
            color: #17a2b8 !important; /* Adjust if the theme uses a different info color */
        }
        .border-info {
            border: 2px solid rgba(23, 162, 184, 0.2) !important;
        }
        .card-custom:hover.border-info {
            border-color: #17a2b8 !important;
        }
    </style>
@endsection
