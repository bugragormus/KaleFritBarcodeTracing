@extends('layouts.app')

@section('title', 'Erişim Reddedildi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Ana Sayfa</a></li>
                        <li class="breadcrumb-item active">Erişim Reddedildi</li>
                    </ol>
                </div>
                <h4 class="page-title">Erişim Reddedildi</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-ban text-danger" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h3 class="text-danger mb-3">Erişim Reddedildi</h3>
                    
                    <p class="text-muted mb-4">
                        Bu sayfaya erişim yetkiniz bulunmamaktadır. Gerekli yetkilere sahip olmak için sistem yöneticinizle iletişime geçiniz.
                    </p>
                    
                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i> Ana Sayfaya Dön
                        </a>
                        
                        <a href="{{ url()->previous() }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-arrow-left"></i> Geri Dön
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
