@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div>
                    <div>
                        <a href="{{ route('home') }}" class="logo logo-admin"><img src="{{ asset('assets/images/kale-seeklogo.png') }}" height="28"
                                                                          alt="logo"></a>
                    </div>
                    <h5 class="font-14 text-muted mb-4">Kalefrit - Stok Yönetim Sistemi</h5>
                    <p class="text-muted mb-4">Fabrika içerisindeki stok takibinin hızlı, pratik ve doğru
                    bir şekilde yapılması için hazırlanmıştır.</p>

                    <h5 class="font-14 text-muted mb-4">Kullanım Aşamaları :</h5>
                    <div>
                        <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>Giriş yapın</p>
                        <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>Barkod oluşturun</p>
                        <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>Stok girişi yapın</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="p-2">
                            <div>
                                <a href="{{ route('home') }}" class="logo logo-admin"><img src="{{ asset('assets/images/kale-seeklogo.png') }}"
                                                                                  height="28" alt="logo"></a>
                            </div>
                        </div>

                        <div class="p-2">
                            <form method="POST" action="{{ route('login') }}" class="form-horizontal m-t-20">
                                @csrf

                                <div class="form-group row">
                                    <div class="col-12">
                                        <label for="registration_number">{{ __('Sicil Numarası') }}</label>
                                        <input id="registration_number" type="text"
                                               class="form-control @error('registration_number') is-invalid @enderror" name="registration_number"
                                               value="{{ old('registration_number') }}" placeholder="{{ __('Sicil numaranızı giriniz') }}" required autocomplete="registration_number" autofocus>

                                        @error('registration_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12">
                                        <label for="email">{{ __('Şifre') }}</label>
                                        <input id="password" type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               name="password" placeholder="{{ __('Şifrenizi giriniz') }}" required autocomplete="current-password">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="custom-control custom-checkbox">
                                            <input name="remember" type="checkbox" class="custom-control-input"
                                                   id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                   for="remember">{{ __('Beni Hatırla') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group text-center row m-t-20">
                                    <div class="col-12">
                                        <button class="btn btn-primary btn-block waves-effect waves-light"
                                                type="submit">{{ __('Giriş') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
@endsection
