@extends('layouts.app')
@section('styles')
    <style>
        body, .main-content, .modern-user-permission {
            background: #f8f9fa !important;
        }
        .modern-user-permission {
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
        
        .permission-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .permission-item {
            background: #ffffff;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .permission-item:hover {
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }
        
        .permission-item.active {
            border-color: #28a745;
            background: linear-gradient(135deg, #f8fff9 0%, #e8f5e8 100%);
        }
        
        .permission-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
            z-index: 10;
            cursor: pointer;
            padding: 5px;
            margin: -5px 0 -5px -5px;
        }
        
        .permission-switch input {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
            z-index: 3;
            cursor: pointer;
        }
        
        .permission-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
            z-index: 2;
        }
        
        .permission-slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        input:checked + .permission-slider {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        input:checked + .permission-slider:before {
            transform: translateX(26px);
        }
        
        .permission-switch:hover .permission-slider {
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .permission-switch:hover input:checked + .permission-slider {
            background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
        }
        
        /* Toggle switch'e tıklanabilirlik için ek stiller */
        .permission-switch input:focus + .permission-slider {
            box-shadow: 0 0 1px #667eea;
        }
        
        .permission-switch input:checked:focus + .permission-slider {
            box-shadow: 0 0 1px #28a745;
        }
        
        /* Toggle switch'in tıklanabilir alanını genişlet */
        .permission-switch {
            /* Tıklanabilir alan genişletildi */
        }
        
        /* Toggle switch hover efekti */
        .permission-switch:hover .permission-slider {
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .permission-label {
            display: flex;
            align-items: flex-start;
            font-weight: 600;
            color: #495057;
            margin-bottom: 1rem;
        }
        
        .permission-content {
            flex: 1;
            margin-left: 1rem;
        }
        
        .permission-header {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }
        
        .permission-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #495057;
            margin-left: 0.75rem;
        }
        
        .permission-description {
            color: #6c757d;
            font-size: 0.875rem;
            line-height: 1.5;
            margin-left: 0;
        }
        
        .permission-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.2rem;
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
        
        .permission-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: #ffffff;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .stat-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .btn-secondary-modern {
            background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%);
            color: white;
        }
        
        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
            
            .card-body-modern {
                padding: 1.5rem;
            }
            
            .permission-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
@section('content')
    <div class="modern-user-permission">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-shield-alt"></i> Kullanıcı Yetkileri
                        </h1>
                        <p class="page-subtitle-modern">Kullanıcının sistem yetkilerini yönetin</p>
                    </div>
                </div>
            </div>

            <!-- User Info Card -->
            <div class="user-info-card">
                <h5><i class="fas fa-user"></i> {{ $user->name }}</h5>
                <p>{{ $user->email }} | {{ $user->phone ?? 'Telefon yok' }}</p>
            </div>

            <!-- Permission Statistics -->
            <div class="permission-stats">
                <div class="stat-card">
                    <div class="stat-icon text-primary">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="stat-number">{{ count($permissions) }}</div>
                    <div class="stat-label">Toplam Yetki</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon text-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-number">{{ count($user_permissions) }}</div>
                    <div class="stat-label">Aktif Yetki</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon text-info">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="stat-number">{{ count($permissions) > 0 ? round((count($user_permissions) / count($permissions)) * 100) : 0 }}%</div>
                    <div class="stat-label">Yetki Oranı</div>
                </div>
            </div>

            <!-- Modern Card -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-cogs"></i> Yetki Yönetimi
                    </h3>
                    <p class="card-subtitle-modern">
                        Kullanıcının sahip olacağı yetkileri seçin
                    </p>
                </div>
                
                <div class="card-body-modern">
                    <form method="POST" action="{{ route('user.permission-sync', ['user' => $user->id]) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="permission-grid">
                            @foreach($permissions as $permission)
                                <div class="permission-item {{ in_array($permission->id, $user_permissions) ? 'active' : '' }}">
                                    <div class="permission-label">
                                        <div class="permission-icon">
                                            @php
                                                $icons = [
                                                    'Barkod Yönetimi' => 'fas fa-barcode',
                                                    'Laboratuvar Yönetimi' => 'fas fa-flask',
                                                    'Müşteri İşlemleri' => 'fas fa-handshake',
                                                    'Kullanıcı Görüntüleme' => 'fas fa-eye',
                                                    'Kullanıcı Yönetimi' => 'fas fa-user-cog',
                                                    'Sistem Yönetimi' => 'fas fa-cogs'
                                                ];
                                                $icon = $icons[$permission->name] ?? 'fas fa-shield-alt';
                                            @endphp
                                            <i class="{{ $icon }}"></i>
                                        </div>
                                        <div class="permission-content">
                                            <div class="permission-header">
                                                <div class="permission-switch">
                                                    <input type="checkbox" {{ in_array($permission->id, $user_permissions) ? 'checked' : '' }} id="permission_{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}">
                                                    <span class="permission-slider"></span>
                                                </div>
                                                <div class="permission-title">{{ $permission->name }}</div>
                                            </div>
                                            <div class="permission-description">
                                                @php
                                                    $descriptions = [
                                                        'Barkod Yönetimi' => 'Barkod oluşturma, düzenleme ve yönetim işlemlerini gerçekleştirme yetkisi',
                                                        'Laboratuvar Yönetimi' => 'Laboratuvar işlemleri, test sonuçları ve kalite kontrol süreçlerini yönetme yetkisi',
                                                        'Müşteri İşlemleri' => 'Müşteri transfer işlemleri, teslimat durumları ve müşteri ilişkilerini yönetme yetkisi',
                                                        'Kullanıcı Görüntüleme' => 'Sistem kullanıcılarını listeleme ve görüntüleme yetkisi',
                                                        'Kullanıcı Yönetimi' => 'Kullanıcı ekleme, düzenleme, silme ve yetki atama işlemlerini gerçekleştirme yetkisi',
                                                        'Sistem Yönetimi' => 'Depo, fırın, adet ve firma yönetimi gibi sistem geneli yapılandırma işlemlerini gerçekleştirme yetkisi'
                                                    ];
                                                @endphp
                                                {{ $descriptions[$permission->name] ?? 'Bu yetki ile ilgili açıklama bulunmuyor.' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn-modern btn-primary-modern">
                                <i class="fas fa-save"></i> Yetkileri Kaydet
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

@section('scripts')
    <script>
        $(document).ready(function() {
            // Toggle switch'lerin çalışması için
            $('.permission-switch input[type="checkbox"]').on('change', function() {
                const permissionItem = $(this).closest('.permission-item');
                const isChecked = $(this).is(':checked');
                
                // Kartın active class'ını güncelle
                if (isChecked) {
                    permissionItem.addClass('active');
                } else {
                    permissionItem.removeClass('active');
                }
                
                // İstatistikleri güncelle
                updatePermissionStats();
                
                // Debug için console log
                console.log('Permission changed:', $(this).val(), 'Checked:', isChecked);
            });
            
            // Toggle switch'e tıklama eventi ekle
            $('.permission-switch').on('click', function(e) {
                // Eğer tıklanan element checkbox ise, event'i işleme
                if ($(e.target).is('input[type="checkbox"]')) {
                    return;
                }
                
                const checkbox = $(this).find('input[type="checkbox"]');
                const isChecked = checkbox.is(':checked');
                
                // Checkbox'ı tersine çevir
                checkbox.prop('checked', !isChecked).trigger('change');
                
                // Event'i durdur
                e.preventDefault();
                e.stopPropagation();
                
                // Debug için console log
                console.log('Toggle clicked:', checkbox.val(), 'New state:', !isChecked);
            });
            
            // Checkbox'a direkt tıklama eventi
            $('.permission-switch input[type="checkbox"]').on('click', function(e) {
                e.stopPropagation();
                console.log('Checkbox clicked directly:', $(this).val());
            });
            
            // İstatistikleri güncelleme fonksiyonu
            function updatePermissionStats() {
                const totalPermissions = $('.permission-switch input[type="checkbox"]').length;
                const activePermissions = $('.permission-switch input[type="checkbox"]:checked').length;
                const percentage = totalPermissions > 0 ? Math.round((activePermissions / totalPermissions) * 100) : 0;
                
                // İstatistik kartlarını güncelle
                $('.stat-card:nth-child(1) .stat-number').text(totalPermissions);
                $('.stat-card:nth-child(2) .stat-number').text(activePermissions);
                $('.stat-card:nth-child(3) .stat-number').text(percentage + '%');
            }
            
            // Sayfa yüklendiğinde istatistikleri güncelle
            updatePermissionStats();
            
            // Form submit edildiğinde loading göster
            $('form').on('submit', function(e) {
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                
                // En az bir yetki seçili mi kontrol et
                const checkedPermissions = $('.permission-switch input[type="checkbox"]:checked').length;
                if (checkedPermissions === 0) {
                    e.preventDefault();
                    alert('En az bir yetki seçmelisiniz!');
                    return false;
                }
                
                submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Kaydediliyor...');
                submitBtn.prop('disabled', true);
                
                // Form submit işlemi tamamlandığında butonu eski haline getir
                setTimeout(function() {
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);
                }, 3000);
            });
            
            // Debug için tüm checkbox'ları kontrol et
            console.log('Total checkboxes found:', $('.permission-switch input[type="checkbox"]').length);
            console.log('Checked checkboxes:', $('.permission-switch input[type="checkbox"]:checked').length);
        });
    </script>
@endsection
