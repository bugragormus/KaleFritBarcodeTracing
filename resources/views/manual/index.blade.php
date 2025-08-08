@extends('layouts.app')

@section('styles')
    <style>
        body, .main-content, .manual-page {
            background: #f8f9fa !important;
        }
        
        .manual-page {
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
        
        .manual-section {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .manual-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .section-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 0.5rem;
            color: #667eea;
            width: 25px;
        }
        
        .step-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .step-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid #667eea;
        }
        
        .step-number {
            background: #667eea;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
            margin-right: 0.5rem;
        }
        
        .step-title {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .step-description {
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 0.5rem;
        }
        
        .step-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 0.5rem;
            font-size: 0.9rem;
            color: #856404;
            margin-top: 0.5rem;
        }
        
        .warning-box {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
            color: #721c24;
        }
        
        .info-box {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
            color: #0c5460;
        }
        
        .success-box {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
            color: #155724;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }
        
        .feature-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            border: 1px solid #e9ecef;
            text-align: center;
        }
        
        .feature-icon {
            font-size: 2rem;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        
        .feature-title {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.25rem;
        }
        
        .feature-description {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .table-modern {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .table-modern th {
            background: #667eea;
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }
        
        .table-modern td {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .table-modern tr:last-child td {
            border-bottom: none;
        }
        
        .table-modern tr:hover {
            background: #f8f9fa;
        }
        
        .toc {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #e9ecef;
        }
        
        .toc-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        
        .toc-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .toc-list li {
            margin-bottom: 0.5rem;
        }
        
        .toc-list a {
            color: #667eea;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .toc-list a:hover {
            background: #e9ecef;
            color: #495057;
        }
        
        .toc-list i {
            margin-right: 0.5rem;
            width: 16px;
        }
        
        .quick-start {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .quick-start h3 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        
        .quick-start p {
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }
        
        .quick-start-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .quick-start-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
            
            .card-body-modern {
                padding: 1.5rem;
            }
            
            .feature-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="manual-page">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-book"></i> Kullanıcı Kılavuzu
                        </h1>
                        <p class="page-subtitle-modern">Kalefrit Barkod Yönetim Sistemi - Kapsamlı Kullanım Rehberi</p>
                    </div>
                </div>
            </div>

            <!-- Hızlı Başlangıç -->
            <div class="quick-start">
                <h3><i class="fas fa-rocket"></i> Hızlı Başlangıç</h3>
                <p>Yeni misiniz? Bu kılavuzu takip ederek sistemi hızlıca öğrenebilirsiniz.</p>
                <a href="#temel-navigation" class="quick-start-btn">
                    <i class="fas fa-play"></i> Başlayın
                </a>
            </div>

            <!-- İçindekiler -->
            <div class="toc">
                <h3 class="toc-title">
                    <i class="fas fa-list"></i> İçindekiler
                </h3>
                <ul class="toc-list">
                    <li><a href="#sistem-hakkinda"><i class="fas fa-info-circle"></i> Sistem Hakkında</a></li>
                    <li><a href="#temel-navigation"><i class="fas fa-compass"></i> Temel Navigasyon</a></li>
                    <li><a href="#giris-cikis"><i class="fas fa-sign-in-alt"></i> Giriş ve Çıkış İşlemleri</a></li>
                    <li><a href="#dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard Kullanımı</a></li>
                    <li><a href="#barkod-yonetimi"><i class="fas fa-qrcode"></i> Barkod Yönetimi</a></li>
                    <li><a href="#stok-yonetimi"><i class="fas fa-boxes"></i> Stok Yönetimi</a></li>
                    <li><a href="#depo-yonetimi"><i class="fas fa-warehouse"></i> Depo Yönetimi</a></li>
                    <li><a href="#firin-yonetimi"><i class="fas fa-fire"></i> Fırın Yönetimi</a></li>
                    <li><a href="#firma-yonetimi"><i class="fas fa-building"></i> Firma Yönetimi</a></li>
                    <li><a href="#raporlama"><i class="fas fa-chart-line"></i> Raporlama ve Analitik</a></li>
                    <li><a href="#ayarlar"><i class="fas fa-cog"></i> Sistem Ayarları</a></li>
                    <li><a href="#guvenlik"><i class="fas fa-shield-alt"></i> Güvenlik ve Yetkilendirme</a></li>
                    <li><a href="#sss"><i class="fas fa-question-circle"></i> Sık Sorulan Sorular</a></li>
                    <li><a href="#iletisim"><i class="fas fa-headset"></i> Destek ve İletişim</a></li>
                </ul>
            </div>

            <!-- Sistem Hakkında -->
            <div class="card-modern" id="sistem-hakkinda">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-info-circle"></i> Sistem Hakkında
                    </h3>
                    <p class="card-subtitle-modern">Kalefrit Barkod Yönetim Sistemi'nin genel tanıtımı ve özellikleri</p>
                </div>
                <div class="card-body-modern">
                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-star"></i> Sistem Nedir?
                        </h4>
                        <p>Kalefrit Barkod Yönetim Sistemi, üretim süreçlerinizi dijitalleştiren ve optimize eden kapsamlı bir yazılım çözümüdür. Bu sistem sayesinde:</p>
                        
                        <div class="feature-grid">
                            <div class="feature-item">
                                <div class="feature-icon"><i class="fas fa-qrcode"></i></div>
                                <div class="feature-title">Barkod Yönetimi</div>
                                <div class="feature-description">Tüm barkod işlemlerinizi tek yerden yönetin</div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                                <div class="feature-title">Analitik Raporlar</div>
                                <div class="feature-description">Detaylı analizler ve performans raporları</div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon"><i class="fas fa-warehouse"></i></div>
                                <div class="feature-title">Depo Yönetimi</div>
                                <div class="feature-description">Çoklu depo desteği ve stok takibi</div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon"><i class="fas fa-industry"></i></div>
                                <div class="feature-title">Üretim Takibi</div>
                                <div class="feature-description">Üretim süreçlerinizi optimize edin</div>
                            </div>
                        </div>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-users"></i> Kimler Kullanır?
                        </h4>
                        <p>Bu sistem aşağıdaki kullanıcı grupları tarafından kullanılmaktadır:</p>
                        
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Kullanıcı Grubu</th>
                                    <th>Kullanım Alanı</th>
                                    <th>Temel İşlevler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Üretim Operatörleri</strong></td>
                                    <td>Barkod oluşturma ve takip</td>
                                    <td>Yeni barkod oluşturma, durum güncelleme</td>
                                </tr>
                                <tr>
                                    <td><strong>Laboratuvar Personeli</strong></td>
                                    <td>Kalite kontrol ve test sonuçları</td>
                                    <td>Test sonuçlarını giriş, onay/red işlemleri</td>
                                </tr>
                                <tr>
                                    <td><strong>Depo Sorumluları</strong></td>
                                    <td>Stok ve depo yönetimi</td>
                                    <td>Stok takibi, depo operasyonları</td>
                                </tr>
                                <tr>
                                    <td><strong>Sistem Yöneticileri</strong></td>
                                    <td>Genel sistem yönetimi</td>
                                    <td>Kullanıcı yönetimi, raporlama, ayarlar</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-lightbulb"></i> Sistem Avantajları
                        </h4>
                        <ul class="step-list">
                            <li class="step-item">
                                <div class="step-title">Dijital Dönüşüm</div>
                                <div class="step-description">Manuel süreçleri dijitalleştirerek hata oranını azaltır ve verimliliği artırır.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">Gerçek Zamanlı Takip</div>
                                <div class="step-description">Tüm işlemleri anlık olarak takip edebilir ve güncel bilgilere erişebilirsiniz.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">Kapsamlı Raporlama</div>
                                <div class="step-description">Detaylı analizler ve raporlar ile karar verme süreçlerinizi destekler.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">Güvenli Erişim</div>
                                <div class="step-description">Rol tabanlı yetkilendirme sistemi ile güvenli ve kontrollü erişim sağlar.</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Temel Navigasyon -->
            <div class="card-modern" id="temel-navigation">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-compass"></i> Temel Navigasyon
                    </h3>
                    <p class="card-subtitle-modern">Sistemde gezinme ve temel arayüz elemanları</p>
                </div>
                <div class="card-body-modern">
                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-bars"></i> Ana Menü
                        </h4>
                        <p>Sistemin sol tarafında bulunan ana menü ile tüm modüllere erişebilirsiniz:</p>
                        
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Menü Öğesi</th>
                                    <th>Açıklama</th>
                                    <th>İkon</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Dashboard</strong></td>
                                    <td>Ana sayfa - Sistem genel durumu</td>
                                    <td><i class="fas fa-tachometer-alt"></i></td>
                                </tr>
                                <tr>
                                    <td><strong>Barkod</strong></td>
                                    <td>Barkod işlemleri ve yönetimi</td>
                                    <td><i class="fas fa-qrcode"></i></td>
                                </tr>
                                <tr>
                                    <td><strong>Stok Yönetimi</strong></td>
                                    <td>Stok ekleme, düzenleme ve takip</td>
                                    <td><i class="fas fa-boxes"></i></td>
                                </tr>
                                <tr>
                                    <td><strong>Depo Yönetimi</strong></td>
                                    <td>Depo işlemleri ve analizleri</td>
                                    <td><i class="fas fa-warehouse"></i></td>
                                </tr>
                                <tr>
                                    <td><strong>Fırın Yönetimi</strong></td>
                                    <td>Fırın işlemleri ve takibi</td>
                                    <td><i class="fas fa-fire"></i></td>
                                </tr>
                                <tr>
                                    <td><strong>Firma Yönetimi</strong></td>
                                    <td>Firma bilgileri ve yönetimi</td>
                                    <td><i class="fas fa-building"></i></td>
                                </tr>
                                <tr>
                                    <td><strong>Ayarlar</strong></td>
                                    <td>Sistem ayarları ve yapılandırma</td>
                                    <td><i class="fas fa-cog"></i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-mouse-pointer"></i> Temel Butonlar
                        </h4>
                        <p>Sistemde sık kullanılan butonlar ve işlevleri:</p>
                        
                        <div class="feature-grid">
                            <div class="feature-item">
                                <div class="feature-icon"><i class="fas fa-plus"></i></div>
                                <div class="feature-title">Ekle</div>
                                <div class="feature-description">Yeni kayıt oluşturma</div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon"><i class="fas fa-edit"></i></div>
                                <div class="feature-title">Düzenle</div>
                                <div class="feature-description">Mevcut kaydı düzenleme</div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon"><i class="fas fa-trash"></i></div>
                                <div class="feature-title">Sil</div>
                                <div class="feature-description">Kayıt silme işlemi</div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon"><i class="fas fa-save"></i></div>
                                <div class="feature-title">Kaydet</div>
                                <div class="feature-description">Değişiklikleri kaydetme</div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon"><i class="fas fa-search"></i></div>
                                <div class="feature-title">Ara</div>
                                <div class="feature-description">Kayıt arama işlemi</div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon"><i class="fas fa-download"></i></div>
                                <div class="feature-title">İndir</div>
                                <div class="feature-description">Rapor indirme</div>
                            </div>
                        </div>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-bell"></i> Bildirimler ve Mesajlar
                        </h4>
                        <p>Sistemde çeşitli bildirim türleri bulunmaktadır:</p>
                        
                        <div class="info-box">
                            <strong><i class="fas fa-info-circle"></i> Bildirim Türleri:</strong>
                            <ul style="margin: 0.5rem 0 0 1.5rem;">
                                <li><strong>Başarı Mesajları:</strong> Yeşil renkte, işlem başarılı olduğunda</li>
                                <li><strong>Hata Mesajları:</strong> Kırmızı renkte, bir hata oluştuğunda</li>
                                <li><strong>Uyarı Mesajları:</strong> Sarı renkte, dikkat edilmesi gereken durumlarda</li>
                                <li><strong>Bilgi Mesajları:</strong> Mavi renkte, bilgilendirme amaçlı</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Giriş ve Çıkış -->
            <div class="card-modern" id="giris-cikis">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-sign-in-alt"></i> Giriş ve Çıkış İşlemleri
                    </h3>
                    <p class="card-subtitle-modern">Sisteme giriş ve çıkış yapma işlemleri</p>
                </div>
                <div class="card-body-modern">
                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-sign-in-alt"></i> Giriş Yapma
                        </h4>
                        <ol class="step-list">
                            <li class="step-item">
                                <div class="step-title">1. Giriş Sayfasına Git</div>
                                <div class="step-description">Sistem ana sayfasından "Giriş Yap" butonuna tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">2. Kullanıcı Bilgilerini Gir</div>
                                <div class="step-description">
                                    • Kullanıcı adınızı girin<br>
                                    • Şifrenizi girin
                                </div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">3. Giriş Yap</div>
                                <div class="step-description">"Giriş Yap" butonuna tıklayarak sisteme giriş yapın.</div>
                            </li>
                        </ol>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-sign-out-alt"></i> Çıkış Yapma
                        </h4>
                        <p>Sistemde çıkış yapmak için:</p>
                        
                        <ol class="step-list">
                            <li class="step-item">
                                <div class="step-title">1. Üst Menüden Çıkış Yap</div>
                                <div class="step-description">Sol menüden "Ayarlar" → "Çıkış Yap" seçeneğine tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">2. Onayla</div>
                                <div class="step-description">Çıkış yapmak istediğinize onay verin.</div>
                            </li>
                        </ol>
                        
                        <div class="warning-box">
                            <strong><i class="fas fa-exclamation-triangle"></i> Dikkat:</strong> Çıkış yapmak, oturumunuzu sonlandırır.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Kullanımı -->
            <div class="card-modern" id="dashboard">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-tachometer-alt"></i> Dashboard Kullanımı
                    </h3>
                    <p class="card-subtitle-modern">Ana sayfadaki dashboard ile sisteminizin genel durumunu takip edin</p>
                </div>
                <div class="card-body-modern">
                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-chart-bar"></i> Dashboard Özellikleri
                        </h4>
                        <p>Ana sayfadaki dashboard, sisteminizin genel durumunu görselleştirir. Bu bölümde:</p>
                        
                        <ul class="step-list">
                            <li class="step-item">
                                <div class="step-title">Toplam Barkod Sayısı</div>
                                <div class="step-description">Sistemdeki toplam barkod sayısını gösterir.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">Günlük İşlemler</div>
                                <div class="step-description">Bugün yapılan barkod işlemlerini listeler.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">Durum Dağılımı</div>
                                <div class="step-description">Barkodların durumlarına göre dağılımını gösterir.</div>
                            </li>
                        </ul>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-filter"></i> Filtreleme ve Arama
                        </h4>
                        <p>Dashboard'da filtreleme ve arama özellikleri ile istediğiniz bilgilere kolayca erişebilirsiniz:</p>
                        
                        <ul class="step-list">
                            <li class="step-item">
                                <div class="step-title">Filtreleme</div>
                                <div class="step-description">Dashboard'da sağ üstteki filtre butonuna tıklayarak farklı periyotları seçebilirsiniz.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">Arama</div>
                                <div class="step-description">Dashboard'da araç ile istediğiniz barkodu, stoku veya depo adını arayabilirsiniz.</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Barkod Yönetimi -->
            <div class="card-modern" id="barkod-yonetimi">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-qrcode"></i> Barkod Yönetimi
                    </h3>
                    <p class="card-subtitle-modern">Barkod oluşturma, takip etme ve yönetme işlemleri</p>
                </div>
                <div class="card-body-modern">
                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-plus-circle"></i> Barkod Oluşturma
                        </h4>
                        <ol class="step-list">
                            <li class="step-item">
                                <div class="step-title">1. Barkod Ekleme Sayfasına Gidin</div>
                                <div class="step-description">Sol menüden "Barkod" → "Barkod Ekle" seçeneğini tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">2. Gerekli Bilgileri Doldurun</div>
                                <div class="step-description">
                                    • Stok seçin<br>
                                    • Fırın seçin<br>
                                    • Parti numarası girin<br>
                                    • Miktar belirleyin<br>
                                    • Depo seçin
                                </div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">3. Barkodu Kaydedin</div>
                                <div class="step-description">"Kaydet" butonuna tıklayarak barkodu sisteme ekleyin.</div>
                                <div class="step-note">
                                    <strong>Not:</strong> Barkod otomatik olarak "Beklemede" durumunda oluşturulur.
                                </div>
                            </li>
                        </ol>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-search"></i> Barkod Takibi
                        </h4>
                        <p>Sistemde barkodların durumları şu şekilde takip edilir:</p>
                        
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Durum</th>
                                    <th>Açıklama</th>
                                    <th>Renk</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Beklemede</td>
                                    <td>Yeni oluşturulan barkod, henüz işlem görmemiş</td>
                                    <td><span style="color: #ffc107;">Sarı</span></td>
                                </tr>
                                <tr>
                                    <td>Ön Onaylı</td>
                                    <td>Laboratuvar testlerinden geçmiş barkod</td>
                                    <td><span style="color: #17a2b8;">Mavi</span></td>
                                </tr>
                                <tr>
                                    <td>Sevk Onaylı</td>
                                    <td>Sevkiyata hazır barkod</td>
                                    <td><span style="color: #28a745;">Yeşil</span></td>
                                </tr>
                                <tr>
                                    <td>Reddedildi</td>
                                    <td>Kalite standartlarını karşılamayan barkod</td>
                                    <td><span style="color: #dc3545;">Kırmızı</span></td>
                                </tr>
                                <tr>
                                    <td>Birleştirildi</td>
                                    <td>Diğer barkodlarla birleştirilmiş barkod</td>
                                    <td><span style="color: #6f42c1;">Mor</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-link"></i> Barkod Birleştirme
                        </h4>
                        <p>Reddedilmiş barkodları kabul edilmiş barkodlarla birleştirerek yeniden test edilebilir hale getirebilirsiniz:</p>
                        
                        <ol class="step-list">
                            <li class="step-item">
                                <div class="step-title">1. Birleştirme Sayfasına Gidin</div>
                                <div class="step-description">"Barkod" → "Barkod Birleştirme" menüsünü seçin.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">2. Barkodları Seçin</div>
                                <div class="step-description">Birleştirilecek barkodları iki grup halinde seçin.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">3. Depo Belirleyin</div>
                                <div class="step-description">Yeni oluşturulacak barkodların hangi depoya gideceğini seçin.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">4. Birleştirme İşlemini Tamamlayın</div>
                                <div class="step-description">"Barkodları Birleştir" butonuna tıklayın.</div>
                            </li>
                        </ol>
                        
                        <div class="warning-box">
                            <strong><i class="fas fa-exclamation-triangle"></i> Dikkat:</strong> Birleştirilmiş barkodlar tekrar birleştirilemez.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stok Yönetimi -->
            <div class="card-modern" id="stok-yonetimi">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-boxes"></i> Stok Yönetimi
                    </h3>
                    <p class="card-subtitle-modern">Stok ekleme, düzenleme ve takibi</p>
                </div>
                <div class="card-body-modern">
                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-plus"></i> Stok Ekleme
                        </h4>
                        <ol class="step-list">
                            <li class="step-item">
                                <div class="step-title">1. Stok Yönetimi Sayfasına Gidin</div>
                                <div class="step-description">Sol menüden "Stok Yönetimi" seçeneğini tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">2. Yeni Stok Ekle Butonunu Kullanın</div>
                                <div class="step-description">Sayfanın üst kısmındaki "Yeni Stok Ekle" butonuna tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">3. Stok Bilgilerini Girin</div>
                                <div class="step-description">
                                    • Stok adı<br>
                                    • Barkod numarası<br>
                                    • Birim fiyat<br>
                                    • Stok kodu<br>
                                    • Depo seçin
                                </div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">4. Stoku Kaydedin</div>
                                <div class="step-description">"Kaydet" butonuna tıklayarak stoku sisteme ekleyin.</div>
                            </li>
                        </ol>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-edit"></i> Stok Düzenleme
                        </h4>
                        <p>Mevcut stokları düzenlemek için:</p>
                        
                        <ol class="step-list">
                            <li class="step-item">
                                <div class="step-title">1. Stok Yönetimi Sayfasına Gidin</div>
                                <div class="step-description">Sol menüden "Stok Yönetimi" seçeneğini tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">2. Düzenlemek İstediğiniz Stoku Seçin</div>
                                <div class="step-description">Listeden düzenlemek istediğiniz stokun yanındaki "Düzenle" butonuna tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">3. Gerekli Bilgileri Düzenleyin</div>
                                <div class="step-description">
                                    • Stok adı<br>
                                    • Barkod numarası<br>
                                    • Birim fiyat<br>
                                    • Stok kodu<br>
                                    • Depo seçin
                                </div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">4. Stoku Kaydedin</div>
                                <div class="step-description">"Kaydet" butonuna tıklayarak düzenlenmiş stoku sisteme ekleyin.</div>
                            </li>
                        </ol>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-search"></i> Stok Arama
                        </h4>
                        <p>Stokları aramak için:</p>
                        
                        <ol class="step-list">
                            <li class="step-item">
                                <div class="step-title">1. Stok Yönetimi Sayfasına Gidin</div>
                                <div class="step-description">Sol menüden "Stok Yönetimi" seçeneğini tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">2. Araç Kullanın</div>
                                <div class="step-description">Sayfanın üst kısmındaki araç ile istediğiniz stok adını, barkodu veya stok kodunu arayabilirsiniz.</div>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Depo Yönetimi -->
            <div class="card-modern" id="depo-yonetimi">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-warehouse"></i> Depo Yönetimi
                    </h3>
                    <p class="card-subtitle-modern">Depo ekleme, düzenleme ve stok takibi</p>
                </div>
                <div class="card-body-modern">
                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-plus"></i> Depo Ekleme
                        </h4>
                        <ol class="step-list">
                            <li class="step-item">
                                <div class="step-title">1. Depo Yönetimi Sayfasına Gidin</div>
                                <div class="step-description">Sol menüden "Depo Yönetimi" seçeneğini tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">2. Yeni Depo Ekle Butonunu Kullanın</div>
                                <div class="step-description">Sayfanın üst kısmındaki "Yeni Depo Ekle" butonuna tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">3. Depo Bilgilerini Girin</div>
                                <div class="step-description">
                                    • Depo adı<br>
                                    • Depo adresi<br>
                                    • Gerekli notlar
                                </div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">4. Depoyu Kaydedin</div>
                                <div class="step-description">"Kaydet" butonuna tıklayarak depoyu sisteme ekleyin.</div>
                            </li>
                        </ol>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-chart-pie"></i> Depo Analizi
                        </h4>
                        <p>Her depo için detaylı analiz bilgileri görüntüleyebilirsiniz:</p>
                        
                        <ul class="step-list">
                            <li class="step-item">
                                <div class="step-title">Mevcut Stok</div>
                                <div class="step-description">Depoda bulunan toplam stok miktarı.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">Red Oranı</div>
                                <div class="step-description">Depodaki reddedilmiş ürünlerin oranı.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">Sevk Onayı Oranı</div>
                                <div class="step-description">Sevkiyata hazır ürünlerin oranı.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">Son Aktivite</div>
                                <div class="step-description">Depoda yapılan son işlem tarihi.</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Fırın Yönetimi -->
            <div class="card-modern" id="firin-yonetimi">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-fire"></i> Fırın Yönetimi
                    </h3>
                    <p class="card-subtitle-modern">Fırın ekleme, düzenleme ve takibi</p>
                </div>
                <div class="card-body-modern">
                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-plus"></i> Fırın Ekleme
                        </h4>
                        <ol class="step-list">
                            <li class="step-item">
                                <div class="step-title">1. Fırın Yönetimi Sayfasına Gidin</div>
                                <div class="step-description">Sol menüden "Fırın Yönetimi" seçeneğini tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">2. Yeni Fırın Ekle Butonunu Kullanın</div>
                                <div class="step-description">Sayfanın üst kısmındaki "Yeni Fırın Ekle" butonuna tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">3. Fırın Bilgilerini Girin</div>
                                <div class="step-description">
                                    • Fırın adı<br>
                                    • Fırın kapasitesi<br>
                                    • Fırın tipi<br>
                                    • Gerekli notlar
                                </div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">4. Fırını Kaydedin</div>
                                <div class="step-description">"Kaydet" butonuna tıklayarak fırını sisteme ekleyin.</div>
                            </li>
                        </ol>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-edit"></i> Fırın Düzenleme
                        </h4>
                        <p>Mevcut fırınları düzenlemek için:</p>
                        
                        <ol class="step-list">
                            <li class="step-item">
                                <div class="step-title">1. Fırın Yönetimi Sayfasına Gidin</div>
                                <div class="step-description">Sol menüden "Fırın Yönetimi" seçeneğini tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">2. Düzenlemek İstediğiniz Fırını Seçin</div>
                                <div class="step-description">Listeden düzenlemek istediğiniz fırının yanındaki "Düzenle" butonuna tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">3. Gerekli Bilgileri Düzenleyin</div>
                                <div class="step-description">
                                    • Fırın adı<br>
                                    • Fırın kapasitesi<br>
                                    • Fırın tipi<br>
                                    • Gerekli notlar
                                </div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">4. Fırını Kaydedin</div>
                                <div class="step-description">"Kaydet" butonuna tıklayarak düzenlenmiş fırını sisteme ekleyin.</div>
                            </li>
                        </ol>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-search"></i> Fırın Arama
                        </h4>
                        <p>Fırınları aramak için:</p>
                        
                        <ol class="step-list">
                            <li class="step-item">
                                <div class="step-title">1. Fırın Yönetimi Sayfasına Gidin</div>
                                <div class="step-description">Sol menüden "Fırın Yönetimi" seçeneğini tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">2. Araç Kullanın</div>
                                <div class="step-description">Sayfanın üst kısmındaki araç ile istediğiniz fırın adını, kapasitesini veya tipini arayabilirsiniz.</div>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Firma Yönetimi -->
            <div class="card-modern" id="firma-yonetimi">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-building"></i> Firma Yönetimi
                    </h3>
                    <p class="card-subtitle-modern">Firma bilgilerini düzenleme ve yönetme</p>
                </div>
                <div class="card-body-modern">
                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-edit"></i> Firma Bilgilerini Düzenleme
                        </h4>
                        <p>Sistemdeki firma bilgilerini düzenlemek için:</p>
                        
                        <ol class="step-list">
                            <li class="step-item">
                                <div class="step-title">1. Firma Yönetimi Sayfasına Gidin</div>
                                <div class="step-description">Sol menüden "Firma Yönetimi" seçeneğini tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">2. Düzenlemek İstediğiniz Firma Bilgilerini Seçin</div>
                                <div class="step-description">Sayfanın üst kısmındaki "Firma Bilgilerini Düzenle" butonuna tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">3. Gerekli Bilgileri Düzenleyin</div>
                                <div class="step-description">
                                    • Firma adı<br>
                                    • Adres<br>
                                    • Telefon<br>
                                    • E-posta<br>
                                    • Vergi numarası
                                </div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">4. Firma Bilgilerini Kaydedin</div>
                                <div class="step-description">"Kaydet" butonuna tıklayarak düzenlenmiş firma bilgilerini sisteme ekleyin.</div>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Raporlama ve Analitik -->
            <div class="card-modern" id="raporlama">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-chart-line"></i> Raporlama ve Analitik
                    </h3>
                    <p class="card-subtitle-modern">Performans analizi ve rapor oluşturma</p>
                </div>
                <div class="card-body-modern">
                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-file-excel"></i> Excel Raporları
                        </h4>
                        <p>Çeşitli raporları Excel formatında indirebilirsiniz:</p>
                        
                        <ol class="step-list">
                            <li class="step-item">
                                <div class="step-title">1. İlgili Sayfaya Gidin</div>
                                <div class="step-description">Rapor almak istediğiniz modülün sayfasına gidin (Stok, Fırın, Firma, Depo).</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">2. Rapor İndir Butonunu Kullanın</div>
                                <div class="step-description">Sayfadaki "Rapor İndir" butonuna tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">3. Dosyayı Kaydedin</div>
                                <div class="step-description">Excel dosyası otomatik olarak indirilecektir.</div>
                            </li>
                        </ol>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-chart-bar"></i> Dashboard Analizi
                        </h4>
                        <p>Dashboard'da bulunan grafikler ve tablolar ile sisteminizin genel durumunu analiz edebilirsiniz:</p>
                        
                        <ul class="step-list">
                            <li class="step-item">
                                <div class="step-title">Toplam Barkod Sayısı Grafiği</div>
                                <div class="step-description">Sistemdeki toplam barkod sayısının zamanla değişimini gösterir.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">Günlük İşlem Sayısı Grafiği</div>
                                <div class="step-description">Bugün yapılan işlem sayısının saatlere göre dağılımını gösterir.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">Durum Dağılım Tablosu</div>
                                <div class="step-description">Barkodların durumlarına göre sayısal dağılımını gösterir.</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sistem Ayarları -->
            <div class="card-modern" id="ayarlar">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-cog"></i> Sistem Ayarları
                    </h3>
                    <p class="card-subtitle-modern">Sistem genel ayarlarını ve kullanıcı yapılandırmasını düzenleyin</p>
                </div>
                <div class="card-body-modern">
                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-user-cog"></i> Kullanıcı Ayarları
                        </h4>
                        <p>Kullanıcı profilinizi düzenlemek için:</p>
                        
                        <ol class="step-list">
                            <li class="step-item">
                                <div class="step-title">1. Ayarlar Sayfasına Gidin</div>
                                <div class="step-description">Sol menüden "Ayarlar" → "Kullanıcı Ayarları" seçeneğine tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">2. Profil Bilgilerinizi Düzenleyin</div>
                                <div class="step-description">
                                    • Adınız<br>
                                    • Soyadınız<br>
                                    • E-posta adresiniz<br>
                                    • Şifrenizi değiştirmek isterseniz mevcut şifrenizi girin
                                </div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">3. Profili Kaydedin</div>
                                <div class="step-description">"Kaydet" butonuna tıklayarak düzenlenmiş profilinizi sisteme ekleyin.</div>
                            </li>
                        </ol>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-language"></i> Dil Ayarları
                        </h4>
                        <p>Sistemin dilini değiştirmek için:</p>
                        
                        <ol class="step-list">
                            <li class="step-item">
                                <div class="step-title">1. Ayarlar Sayfasına Gidin</div>
                                <div class="step-description">Sol menüden "Ayarlar" → "Dil Ayarları" seçeneğine tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">2. Dil Seçin</div>
                                <div class="step-description">Sistemin dilini seçin (Türkçe, İngilizce vb.).</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">3. Kaydet Butonuna Tıklayın</div>
                                <div class="step-description">"Kaydet" butonuna tıklayarak seçilen dilin sisteme uygulanmasını sağlayın.</div>
                            </li>
                        </ol>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-lock"></i> Güvenlik Ayarları
                        </h4>
                        <p>Sistem güvenlik ayarlarını düzenlemek için:</p>
                        
                        <ol class="step-list">
                            <li class="step-item">
                                <div class="step-title">1. Ayarlar Sayfasına Gidin</div>
                                <div class="step-description">Sol menüden "Ayarlar" → "Güvenlik Ayarları" seçeneğine tıklayın.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">2. Şifre Değiştirme</div>
                                <div class="step-description">
                                    • Mevcut şifrenizi girin<br>
                                    • Yeni şifrenizi girin<br>
                                    • Şifreyi tekrar girin
                                </div>
                            </li>
                            <li class="step-item">
                                <div class="step-title">3. Kaydet Butonuna Tıklayın</div>
                                <div class="step-description">"Kaydet" butonuna tıklayarak şifrenizin değiştirilmesini sağlayın.</div>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Güvenlik ve Yetkilendirme -->
            <div class="card-modern" id="guvenlik">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-shield-alt"></i> Güvenlik ve Yetkilendirme
                    </h3>
                    <p class="card-subtitle-modern">Kullanıcı yetkileri ve güvenlik önlemleri</p>
                </div>
                <div class="card-body-modern">
                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-user-shield"></i> Kullanıcı Yetkileri
                        </h4>
                        <p>Sistemde farklı kullanıcı rolleri bulunmaktadır:</p>
                        
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Rol</th>
                                    <th>Yetkiler</th>
                                    <th>Açıklama</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Yönetici</strong></td>
                                    <td>Tüm modüllerin tam kontrolü</td>
                                    <td>Sistem yöneticisi, kullanıcı yönetimi, raporlama, ayarlar</td>
                                </tr>
                                <tr>
                                    <td><strong>Operatör</strong></td>
                                    <td>Barkod işlemleri, stok takibi</td>
                                    <td>Üretim operasyonları, stok giriş/çıkış</td>
                                </tr>
                                <tr>
                                    <td><strong>Laboratuvar</strong></td>
                                    <td>Test sonuçları, onay/red işlemleri</td>
                                    <td>Kalite kontrol, barkod onaylama</td>
                                </tr>
                                <tr>
                                    <td><strong>Depo</strong></td>
                                    <td>Depo işlemleri, stok giriş/çıkış</td>
                                    <td>Depo operasyonları, stok takibi</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-lock"></i> Güvenlik Önlemleri
                        </h4>
                        <div class="info-box">
                            <strong><i class="fas fa-info-circle"></i> Güvenlik İpuçları:</strong>
                            <ul style="margin: 0.5rem 0 0 1.5rem;">
                                <li>Güçlü şifreler kullanın</li>
                                <li>Oturumunuzu kapatmayı unutmayın</li>
                                <li>Şifrenizi kimseyle paylaşmayın</li>
                                <li>Düzenli olarak şifrenizi değiştirin</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sık Sorulan Sorular -->
            <div class="card-modern" id="sss">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-question-circle"></i> Sık Sorulan Sorular
                    </h3>
                    <p class="card-subtitle-modern">Kullanıcıların sık sorduğu sorular ve detaylı cevapları</p>
                </div>
                <div class="card-body-modern">
                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-qrcode"></i> Barkod İşlemleri
                        </h4>
                        
                        <div class="step-item">
                            <div class="step-title">Barkod nasıl oluşturulur?</div>
                            <div class="step-description">Sol menüden "Barkod" → "Barkod Ekle" seçeneğine tıklayın. Gerekli bilgileri (stok, fırın, parti numarası, miktar, depo) doldurduktan sonra "Kaydet" butonuna tıklayın.</div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-title">Barkod durumu nasıl değiştirilir?</div>
                            <div class="step-description">Barkod listesi sayfasından ilgili barkodun yanındaki "Düzenle" butonuna tıklayın. Durum alanından yeni durumu seçin ve "Kaydet" butonuna tıklayın.</div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-title">Birleştirilmiş barkodlar tekrar birleştirilebilir mi?</div>
                            <div class="step-description">Hayır, birleştirilmiş barkodlar tekrar birleştirilemez. Bu işlem geri alınamaz.</div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-title">Barkod silinebilir mi?</div>
                            <div class="step-description">Evet, barkod listesi sayfasından ilgili barkodun yanındaki "Sil" butonuna tıklayarak silebilirsiniz. Ancak bu işlem geri alınamaz.</div>
                        </div>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-boxes"></i> Stok İşlemleri
                        </h4>
                        
                        <div class="step-item">
                            <div class="step-title">Yeni stok nasıl eklenir?</div>
                            <div class="step-description">Sol menüden "Stok Yönetimi" seçeneğine tıklayın. "Yeni Stok Ekle" butonuna tıklayarak gerekli bilgileri doldurun ve "Kaydet" butonuna tıklayın.</div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-title">Stok bilgileri nasıl güncellenir?</div>
                            <div class="step-description">Stok listesi sayfasından ilgili stokun yanındaki "Düzenle" butonuna tıklayın. Bilgileri güncelleyin ve "Kaydet" butonuna tıklayın.</div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-title">Stok arama nasıl yapılır?</div>
                            <div class="step-description">Stok yönetimi sayfasının üst kısmındaki arama kutusuna stok adı, barkod numarası veya stok kodunu yazarak arama yapabilirsiniz.</div>
                        </div>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-warehouse"></i> Depo İşlemleri
                        </h4>
                        
                        <div class="step-item">
                            <div class="step-title">Yeni depo nasıl eklenir?</div>
                            <div class="step-description">Sol menüden "Depo Yönetimi" seçeneğine tıklayın. "Yeni Depo Ekle" butonuna tıklayarak depo bilgilerini doldurun ve "Kaydet" butonuna tıklayın.</div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-title">Depo analizi nasıl görüntülenir?</div>
                            <div class="step-description">Depo yönetimi sayfasında her depo için mevcut stok, red oranı, sevk onayı oranı ve son aktivite bilgileri görüntülenir.</div>
                        </div>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-chart-line"></i> Raporlama
                        </h4>
                        
                        <div class="step-item">
                            <div class="step-title">Excel raporu nasıl indirilir?</div>
                            <div class="step-description">İlgili modül sayfasında (Stok, Fırın, Firma, Depo) "Rapor İndir" butonuna tıklayın. Excel dosyası otomatik olarak indirilecektir.</div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-title">Dashboard verileri nasıl güncellenir?</div>
                            <div class="step-description">Dashboard verileri gerçek zamanlı olarak güncellenir. Sayfayı yenilemek için F5 tuşuna basabilir veya sayfayı yenileyebilirsiniz.</div>
                        </div>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-user-shield"></i> Kullanıcı ve Güvenlik
                        </h4>
                        
                        <div class="step-item">
                            <div class="step-title">Şifremi nasıl değiştirebilirim?</div>
                            <div class="step-description">Sol menüden "Ayarlar" → "Güvenlik Ayarları" seçeneğine tıklayın. Mevcut şifrenizi ve yeni şifrenizi girin, ardından "Kaydet" butonuna tıklayın.</div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-title">Hangi işlemleri yapabilirim?</div>
                            <div class="step-description">Yapabileceğiniz işlemler kullanıcı rolünüze bağlıdır. Sistem yöneticiniz size atanan yetkileri belirler.</div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-title">Oturumum nasıl güvenli kalır?</div>
                            <div class="step-description">İşiniz bittiğinde mutlaka "Çıkış Yap" butonuna tıklayın. Güçlü bir şifre kullanın ve şifrenizi kimseyle paylaşmayın.</div>
                        </div>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-tools"></i> Teknik Sorunlar
                        </h4>
                        
                        <div class="step-item">
                            <div class="step-title">Sistem yavaş çalışıyor, ne yapmalıyım?</div>
                            <div class="step-description">Önce sayfayı yenileyin (F5). Sorun devam ederse sistem yöneticiniz ile iletişime geçin.</div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-title">Hata mesajı alıyorum, ne yapmalıyım?</div>
                            <div class="step-description">Hata mesajını not alın ve sistem yöneticiniz ile paylaşın. Hata mesajı sorunun çözümü için önemli bilgi içerir.</div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-title">Sayfa yüklenmiyor, ne yapmalıyım?</div>
                            <div class="step-description">İnternet bağlantınızı kontrol edin. Sorun devam ederse tarayıcınızın önbelleğini temizleyin veya farklı bir tarayıcı deneyin.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- İletişim -->
            <div class="card-modern" id="iletisim">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-headset"></i> Destek ve İletişim
                    </h3>
                    <p class="card-subtitle-modern">Teknik destek ve iletişim bilgileri</p>
                </div>
                <div class="card-body-modern">
                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-phone"></i> Teknik Destek
                        </h4>
                        <div class="success-box">
                            <strong><i class="fas fa-check-circle"></i> Destek Hattı:</strong>
                            <p style="margin: 0.5rem 0 0 0;">Sistem ile ilgili sorularınız için lütfen sistem yöneticiniz ile iletişime geçin.</p>
                        </div>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-envelope"></i> E-posta Desteği
                        </h4>
                        <div class="info-box">
                            <strong><i class="fas fa-info-circle"></i> E-posta Adresi:</strong>
                            <p style="margin: 0.5rem 0 0 0;">Teknik destek için: onurcansahin@kale.com.tr</p>
                        </div>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-map-marker-alt"></i> İletişim Bilgileri
                        </h4>
                        <div class="info-box">
                            <strong><i class="fas fa-building"></i> Dijital Dönüşüm Ofisi:</strong>
                            <p style="margin: 0.5rem 0 0 0;">Kale Grubu - Dijital Dönüşüm Ofisi</p>
                        </div>
                    </div>

                    <div class="manual-section">
                        <h4 class="section-title">
                            <i class="fas fa-clock"></i> Destek Saatleri
                        </h4>
                        <div class="info-box">
                            <strong><i class="fas fa-calendar"></i> Çalışma Saatleri:</strong>
                            <p style="margin: 0.5rem 0 0 0;">Pazartesi - Cuma: 08:00 - 17:30</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
