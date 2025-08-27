@extends('layouts.app')

@section('styles')
    <style>
        body, .main-content, .about-page {
            background: #f8f9fa !important;
        }
        
        .about-page {
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
            margin-bottom: 2rem;
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
        
        .system-info-container {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }
        
        .system-info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        @media (max-width: 768px) {
            .system-info-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .developer-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 3rem;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
        }
        
        .developer-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 50%, rgba(255,255,255,0.05) 100%);
        }
        
        .developer-content {
            position: relative;
            z-index: 2;
        }
        
        .developer-avatar-large {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: white;
            font-size: 3rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .developer-name-large {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .developer-role-large {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            opacity: 0.95;
        }
        
        .developer-details {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }
        
        .developer-detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .developer-detail-item i {
            font-size: 1.1rem;
        }
        
        .developer-detail-item span {
            font-weight: 500;
        }
        
        .info-item {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            padding: 2rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .info-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .info-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border-color: #667eea;
        }
        
        .info-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }
        
        .info-item:hover .info-icon {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }
        
        .info-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #495057;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-description {
            color: #6c757d;
            line-height: 1.6;
            font-size: 1.1rem;
            font-weight: 500;
        }
        

        
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .feature-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
        }
        
        .feature-list li:last-child {
            border-bottom: none;
        }
        
        .feature-list li i {
            color: #28a745;
            margin-right: 0.5rem;
            font-size: 0.9rem;
        }
        
        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .tech-badge {
            background: #e9ecef;
            color: #495057;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .contact-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
        }
        
        .contact-info h5 {
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .contact-info p {
            margin-bottom: 0;
            opacity: 0.9;
        }
        

        
        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .card-body-modern {
                padding: 1.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="about-page">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-info-circle"></i> Hakkında
                        </h1>
                        <p class="page-subtitle-modern">Kalefrit Barkod Yönetim Sistemi hakkında detaylı bilgiler</p>
                    </div>
                </div>
            </div>

            <!-- Sistem Bilgileri -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-cog"></i> Sistem Bilgileri
                    </h3>
                    <p class="card-subtitle-modern">Sistem versiyonu ve teknik detaylar</p>
                </div>
                <div class="card-body-modern">
                    <div class="system-info-container">

                    <div class="developer-section">
                            <div class="developer-content">
                                <div class="developer-avatar-large">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="developer-name-large">Buğra GÖRMÜŞ</div>
                            </div>
                            <div class="developer-details">
                                <div class="developer-detail-item">
                                    <i class="fas fa-user-tie"></i>
                                    <span>Yapay Zeka Uzmanı - Yazılım Mühendisi</span>
                                </div>
                                <div class="developer-detail-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>bugra.gormus@hotmail.com</span>
                                </div>
                            </div>
                        </div>
                        <div class="system-info-grid">
                            
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-rocket"></i>
                                </div>
                                <div class="info-title">Sistem Adı</div>
                                <div class="info-description">Kalefrit Barkod Yönetim Sistemi</div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="info-title">Geliştirme Tarihi</div>
                                <div class="info-description">Ağustos 2025</div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-code-branch"></i>
                                </div>
                                <div class="info-title">Versiyon</div>
                                <div class="info-description">2.0.0</div>
                            </div>
                        </div>

                       
                    </div>
                </div>
            </div>

            <!-- Özellikler -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-star"></i> Sistem Özellikleri
                    </h3>
                    <p class="card-subtitle-modern">Sistemin sunduğu kapsamlı özellikler ve yetenekler</p>
                </div>
                <div class="card-body-modern">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fas fa-qrcode"></i> Barkod Yönetimi</h5>
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i> Barkod oluşturma ve takibi</li>
                                <li><i class="fas fa-check"></i> QR kod okuma ve yazdırma</li>
                                <li><i class="fas fa-check"></i> Barkod birleştirme işlemleri</li>
                                <li><i class="fas fa-check"></i> Detaylı barkod geçmişi</li>
                                <li><i class="fas fa-check"></i> Toplu barkod işlemleri</li>
                                <li><i class="fas fa-check"></i> Barkod durumu takibi (Beklemede, Kabul, Red)</li>
                                <li><i class="fas fa-check"></i> Laboratuvar sonuçları entegrasyonu</li>
                                <li><i class="fas fa-check"></i> Barkod yazdırma ve etiketleme</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-chart-line"></i> Analitik ve Raporlama</h5>
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i> Gerçek zamanlı dashboard</li>
                                <li><i class="fas fa-check"></i> Detaylı analitik raporlar</li>
                                <li><i class="fas fa-check"></i> Excel export özelliği</li>
                                <li><i class="fas fa-check"></i> Performans metrikleri</li>
                                <li><i class="fas fa-check"></i> Grafik ve görselleştirmeler</li>
                                <li><i class="fas fa-check"></i> Trend analizi ve tahminleme</li>
                                <li><i class="fas fa-check"></i> Özelleştirilebilir raporlar</li>
                                <li><i class="fas fa-check"></i> Otomatik rapor gönderimi</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5><i class="fas fa-warehouse"></i> Depo ve Stok Yönetimi</h5>
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i> Çoklu depo desteği</li>
                                <li><i class="fas fa-check"></i> Stok takibi ve kontrolü</li>
                                <li><i class="fas fa-check"></i> Otomatik stok hesaplamaları</li>
                                <li><i class="fas fa-check"></i> Depo performans analizi</li>
                                <li><i class="fas fa-check"></i> Stok seviyesi uyarıları</li>
                                <li><i class="fas fa-check"></i> Depo doluluk oranları</li>
                                <li><i class="fas fa-check"></i> Stok hareket geçmişi</li>
                                <li><i class="fas fa-check"></i> Depo optimizasyon önerileri</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-industry"></i> Üretim Yönetimi</h5>
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i> Fırın yönetimi</li>
                                <li><i class="fas fa-check"></i> Üretim takibi</li>
                                <li><i class="fas fa-check"></i> Kalite kontrol süreçleri</li>
                                <li><i class="fas fa-check"></i> Müşteri transfer yönetimi</li>
                                <li><i class="fas fa-check"></i> Üretim planlama</li>
                                <li><i class="fas fa-check"></i> Kalite standartları takibi</li>
                                <li><i class="fas fa-check"></i> Üretim verimliliği analizi</li>
                                <li><i class="fas fa-check"></i> Hata analizi ve iyileştirme</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5><i class="fas fa-users"></i> Kullanıcı ve Yetki Yönetimi</h5>
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i> Rol tabanlı erişim kontrolü</li>
                                <li><i class="fas fa-check"></i> Kullanıcı yetkilendirme sistemi</li>
                                <li><i class="fas fa-check"></i> İşlem logları ve denetim</li>
                                <li><i class="fas fa-check"></i> Güvenli oturum yönetimi</li>
                                <li><i class="fas fa-check"></i> Kullanıcı aktivite takibi</li>
                                <li><i class="fas fa-check"></i> Şifre politikaları</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-cogs"></i> Sistem ve Entegrasyon</h5>
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i> Modern web teknolojileri</li>
                                <li><i class="fas fa-check"></i> Responsive tasarım</li>
                                <li><i class="fas fa-check"></i> Hızlı ve güvenli performans</li>
                                <li><i class="fas fa-check"></i> Veri yedekleme ve güvenlik</li>
                                <li><i class="fas fa-check"></i> Gerçek zamanlı veri güncelleme</li>
                                <li><i class="fas fa-check"></i> Gelişmiş arama ve filtreleme</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('manual') }}" class="btn-modern btn-primary-modern">
                            <i class="fas fa-book"></i> Kullanıcı Kılavuzu
                        </a>
                    </div>
                </div>
            </div>

            <!-- İletişim Bilgileri -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-envelope"></i> İletişim
                    </h3>
                    <p class="card-subtitle-modern">Destek ve iletişim bilgileri</p>
                </div>
                <div class="card-body-modern">
                    <div class="contact-info">
                        <h5><i class="fas fa-headset"></i> Teknik Destek</h5>
                        <p>Sistem ile ilgili sorularınız için lütfen sistem yöneticiniz ile iletişime geçin.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
