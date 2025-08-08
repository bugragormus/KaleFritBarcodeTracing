@extends('layouts.app')
@section('styles')
    <style>
        body, .main-content, .modern-user-edit {
            background: #f8f9fa !important;
        }
        .modern-user-edit {
            background: #ffffff;
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
        
        .form-section {
            background: #ffffff;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
        }
        
        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 0.5rem;
            color: #667eea;
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
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #ffffff;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .form-control::placeholder {
            color: #adb5bd;
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
        
        .user-info-card {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .user-info-card h5 {
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .user-info-card p {
            margin-bottom: 0;
            opacity: 0.9;
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
    <div class="modern-user-edit">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-user-edit"></i> Kullanıcı Düzenle
                        </h1>
                        <p class="page-subtitle-modern">Kullanıcı bilgilerini güncelleyin</p>
                    </div>
                </div>
            </div>

            <!-- User Info Card -->
            <div class="user-info-card">
                <h5><i class="fas fa-user"></i> {{ $user->name }}</h5>
                <p>{{ $user->email }} | {{ $user->phone ?? 'Telefon yok' }}</p>
            </div>

            <!-- Modern Card -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-edit"></i> Kullanıcı Bilgileri
                    </h3>
                    <p class="card-subtitle-modern">
                        Kullanıcının temel bilgilerini düzenleyin
                    </p>
                </div>
                
                <div class="card-body-modern">
                    <form method="POST" action="{{ route('user.update', ['user' => $user->id]) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-user"></i> Ad Soyad
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Ad soyad giriniz" value="{{ old('name', isset($user) ? $user->name : '') }}"/>
                                    @if($errors->has('name'))
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-envelope"></i> E-posta Adresi
                                    </label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="E-posta adresi giriniz" value="{{ old('email', isset($user) ? $user->email : '') }}"/>
                                    @if($errors->has('email'))
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-phone"></i> Telefon Numarası
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" name="phone" placeholder="Telefon numarası giriniz" data-mask="09999999999" value="{{ old('phone', isset($user) ? $user->phone : '') }}" class="form-control">
                                    @if($errors->has('phone'))
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $errors->first('phone') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-id-card"></i> Sicil Numarası
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" name="registration_number" class="form-control" placeholder="Sicil numarası giriniz" value="{{ old('registration_number', isset($user) ? $user->registration_number : '') }}"/>
                                    @if($errors->has('registration_number'))
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $errors->first('registration_number') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-lock"></i> Şifre
                                        <span class="required">*</span>
                                    </label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Şifre giriniz"/>
                                    @if($errors->has('password'))
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn-modern btn-primary-modern">
                                <i class="fas fa-save"></i> Kaydet
                            </button>
                            <a href="{{ route('user.index') }}" class="btn-modern btn-secondary-modern">
                                <i class="fas fa-times"></i> İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
