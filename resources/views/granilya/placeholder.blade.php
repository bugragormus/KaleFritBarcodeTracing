@extends('layouts.granilya')

@section('title', isset($title) ? $title : 'Granilya Sistemi')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="page-title m-0">{{ isset($title) ? $title : 'Granilya Sistemi' }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="card text-center" style="border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    <div class="card-body py-5">
                        <div class="mb-4">
                            <i class="mdi mdi-tools text-warning" style="font-size: 80px;"></i>
                        </div>
                        <h2 class="text-dark mb-3">Yapım Aşamasında</h2>
                        <p class="text-muted font-18 mb-5">
                            Granilya sistemi henüz geliştirme aşamasındadır. Takip, yönetim ve raporlama modülleri yakında burada yer alacaktır.
                        </p>
                        
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg waves-effect waves-light"
                           onclick="event.preventDefault(); document.getElementById('switch-frit-form').submit();">
                            <i class="mdi mdi-arrow-left mr-2"></i> Frit Üretim Sistemine Geri Dön
                        </a>
                        
                        <form id="switch-frit-form" action="{{ route('system.selection.store') }}" method="POST" class="d-none">
                            @csrf
                            <input type="hidden" name="system" value="frit">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
