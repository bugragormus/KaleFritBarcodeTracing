@extends('layouts.app')

@section('styles')
    <style>
        body, .main-content, .modern-settings {
            background: #f8f9fa !important;
        }
        .modern-settings {
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
        
        .settings-item {
            background: #ffffff;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .settings-item:hover {
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }
        
        .settings-info {
            flex: 1;
        }
        
        .settings-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .settings-title i {
            margin-right: 0.75rem;
            color: #667eea;
            font-size: 1.2rem;
        }
        
        .settings-description {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        
        .kiln-selector {
            margin-top: 1rem;
        }
        
        .kiln-selector select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
            max-width: 300px;
            height: auto;
            min-height: 45px;
            white-space: normal;
            word-wrap: break-word;
        }
        
        .kiln-selector select option {
            font-size: 0.875rem;
            padding: 0.5rem;
            white-space: normal;
            word-wrap: break-word;
        }
        
        .kiln-selector select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .btn-warning-modern {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
        }
        
        .btn-warning-modern:hover {
            background: linear-gradient(135deg, #e0a800 0%, #d39e00 100%);
            color: #212529;
        }
        
        .btn-warning-modern:disabled {
            opacity: 0.6;
            cursor: not-allowed;
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
            font-size: 1rem;
        }
        
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-danger-modern {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }
        
        .btn-danger-modern:hover {
            background: linear-gradient(135deg, #c82333 0%, #e55a00 100%);
        }
        
        .warning-card {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 2px solid #ffc107;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .warning-title {
            color: #856404;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .warning-title i {
            margin-right: 0.5rem;
            color: #ffc107;
        }
        
        .warning-text {
            color: #856404;
            margin-bottom: 0;
        }
        
        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
            
            .settings-item {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .settings-info {
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="modern-settings">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-cogs"></i> Sistem Ayarları
                        </h1>
                        <p class="page-subtitle-modern">Sistem genelinde kritik ayarları yönetin</p>
                    </div>
                </div>
            </div>

            <!-- Warning Card -->
            <div class="warning-card">
                <h5 class="warning-title">
                    <i class="fas fa-exclamation-triangle"></i> Dikkat!
                </h5>
                <p class="warning-text">
                    Aşağıdaki işlemler sistem genelinde etkili olacaktır. Bu işlemleri yaparken dikkatli olunuz!
                </p>
            </div>

            <!-- Modern Card -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-tools"></i> Sistem İşlemleri
                    </h3>
                    <p class="card-subtitle-modern">
                        Kritik sistem işlemlerini gerçekleştirin
                    </p>
                </div>
                
                <div class="card-body-modern">
                    <div class="settings-item">
                        <div class="settings-info">
                            <div class="settings-title">
                                <i class="fas fa-fire"></i> Tüm Fırın Şarj Numaralarını Sıfırla
                            </div>
                            <div class="settings-description">
                                Tüm fırınların şarj numaralarını sıfırlar. Bu işlem geri alınamaz!
                            </div>
                        </div>
                        <button class="btn-modern btn-danger-modern" onclick="resetKilnLoadNumber()">
                            <i class="fas fa-trash"></i> Tümünü Sıfırla
                        </button>
                    </div>

                    <div class="settings-item">
                        <div class="settings-info">
                            <div class="settings-title">
                                <i class="fas fa-fire"></i> Tek Fırın Şarj Numarasını Sıfırla
                            </div>
                            <div class="settings-description">
                                Seçilen fırının şarj numarasını sıfırlar. Bu işlem geri alınamaz!
                            </div>
                            <div class="kiln-selector">
                                <select id="kilnSelect" class="form-control">
                                    <option value="">Fırın seçiniz</option>
                                    @foreach($kilns as $kiln)
                                        <option value="{{ $kiln->id }}">{{ $kiln->name }} (Mevcut: {{ $kiln->load_number }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button class="btn-modern btn-warning-modern" onclick="resetSingleKilnLoadNumber()" id="resetSingleBtn" disabled>
                            <i class="fas fa-eraser"></i> Seçili Fırını Sıfırla
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
        });
        
        // Fırın seçimi değiştiğinde buton durumunu güncelle
        $('#kilnSelect').on('change', function() {
            var selectedValue = $(this).val();
            var resetBtn = $('#resetSingleBtn');
            
            if (selectedValue) {
                resetBtn.prop('disabled', false);
            } else {
                resetBtn.prop('disabled', true);
            }
        });
        
        function resetKilnLoadNumber() {
            swal({
                title: "Fırın şarj numaralarını sıfırlamak istediğinizden emin misiniz?",
                text: "Bu işlem tüm fırınların şarj numaralarını sıfırlayacak ve geri alınamaz!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn btn-danger btn-lg',
                confirmButtonText: "Evet, Sıfırla",
                cancelButtonClass: 'btn btn-primary btn-lg m-l-10',
                cancelButtonText: "Vazgeç",
                buttonsStyling: false,
                reverseButtons: true
            }).then(function (e) {
                if (e.value === true) {
                    var data = {
                        "_token": $('input[name="_token"]').val(),
                    }
                    
                    $.ajax({
                        type: 'put',
                        url: "{{route('settings.resetKilnLoadNumber')}}",
                        data: data,
                        success: function (results) {
                            if (results) {
                                swal({
                                    title: "Başarılı!",
                                    text: results.message,
                                    type: "success",
                                    confirmButtonClass: 'btn btn-success btn-lg',
                                    confirmButtonText: "Tamam",
                                    buttonsStyling: false
                                }).then(function() {
                                    location.reload();
                                });
                            } else {
                                swal({
                                    title: "Hata!",
                                    text: "Lütfen tekrar deneyin!",
                                    type: "error",
                                    confirmButtonClass: 'btn btn-danger btn-lg',
                                    confirmButtonText: "Tamam",
                                    buttonsStyling: false
                                });
                            }
                        },
                        error: function() {
                            swal({
                                title: "Hata!",
                                text: "Bir hata oluştu. Lütfen tekrar deneyin!",
                                type: "error",
                                confirmButtonClass: 'btn btn-danger btn-lg',
                                confirmButtonText: "Tamam",
                                buttonsStyling: false
                            });
                        }
                    });
                }
            });
        }
        
        function resetSingleKilnLoadNumber() {
            var selectedKilnId = $('#kilnSelect').val();
            var selectedKilnName = $('#kilnSelect option:selected').text();
            
            if (!selectedKilnId) {
                swal({
                    title: "Uyarı!",
                    text: "Lütfen bir fırın seçiniz!",
                    type: "warning",
                    confirmButtonClass: 'btn btn-warning btn-lg',
                    confirmButtonText: "Tamam",
                    buttonsStyling: false
                });
                return;
            }
            
            swal({
                title: "Fırın şarj numarasını sıfırlamak istediğinizden emin misiniz?",
                text: selectedKilnName + " fırınının şarj numarası sıfırlanacak. Bu işlem geri alınamaz!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn btn-warning btn-lg',
                confirmButtonText: "Evet, Sıfırla",
                cancelButtonClass: 'btn btn-primary btn-lg m-l-10',
                cancelButtonText: "Vazgeç",
                buttonsStyling: false,
                reverseButtons: true
            }).then(function (e) {
                if (e.value === true) {
                    var data = {
                        "_token": $('input[name="_token"]').val(),
                        "kiln_id": selectedKilnId
                    }
                    
                    $.ajax({
                        type: 'post',
                        url: "{{route('settings.resetSingleKilnLoadNumber')}}",
                        data: data,
                        success: function (results) {
                            if (results) {
                                swal({
                                    title: "Başarılı!",
                                    text: results.message,
                                    type: "success",
                                    confirmButtonClass: 'btn btn-success btn-lg',
                                    confirmButtonText: "Tamam",
                                    buttonsStyling: false
                                }).then(function() {
                                    location.reload();
                                });
                            } else {
                                swal({
                                    title: "Hata!",
                                    text: "Lütfen tekrar deneyin!",
                                    type: "error",
                                    confirmButtonClass: 'btn btn-danger btn-lg',
                                    confirmButtonText: "Tamam",
                                    buttonsStyling: false
                                });
                            }
                        },
                        error: function() {
                            swal({
                                title: "Hata!",
                                text: "Bir hata oluştu. Lütfen tekrar deneyin!",
                                type: "error",
                                confirmButtonClass: 'btn btn-danger btn-lg',
                                confirmButtonText: "Tamam",
                                buttonsStyling: false
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
