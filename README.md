# Kalefrit - Yönetim Sistemi v2.1.1

<p align="center">
  <img src="public/assets/images/kale-logo.png" alt="Kalefrit Logo" width="200">
</p>

<p align="center">
  <strong>Gelişmiş İstatistiksel Analiz Destekli Modern ve Kullanıcı Dostu Yönetim Sistemi</strong>
</p>

## 🚀 Yeni: Gelişmiş İstatistiksel Analiz Sistemi

Kalefrit artık gelişmiş istatistiksel analiz özellikleri ile güçlendirilmiş! Üretim tahminleri, kalite risk analizi, anomali tespiti ve veri tabanlı optimizasyon önerileri ile işletmenizi bir üst seviyeye taşıyın.

## 📋 Proje Hakkında

Kalefrit, işletmeler için geliştirilmiş kapsamlı bir yönetim sistemidir. Bu sistem, işletme operasyonlarını dijitalleştirerek verimliliği artırır ve hata riskini minimize eder. **v2.0** ile birlikte gelişmiş istatistiksel analiz özellikleri eklenmiştir.

## ✨ Özellikler

### 📊 Gelişmiş İstatistiksel Analitik

-   **Üretim Tahmini**: Gelecek 7 gün için istatistiksel üretim tahminleri
-   **Kalite Risk Analizi**: Otomatik kalite risk değerlendirmesi ve uyarıları
-   **Anomali Tespiti**: İstatistiksel analiz ile üretim anomalilerinin tespiti
-   **Veri Tabanlı Öneriler**: İstatistiksel analiz ile üretilen optimizasyon önerileri
-   **Analitik Model Yönetimi**: 3 farklı analitik model ile sürekli veri analizi

### 📊 Gelişmiş Raporlama

-   **Günlük Raporlar**: Detaylı günlük üretim ve performans analizleri
-   **Vardiya Bazlı Analiz**: 3 vardiya halinde üretim performansı
-   **Fırın Performans Metrikleri**: Her fırının verimlilik analizi
-   **Kalite Kontrol Raporları**: Red oranları ve kalite trendleri
-   **Stok Yaşı Uyarıları**: Eski stoklar için otomatik uyarılar
-   **Excel Export**: Tüm raporların Excel formatında indirilmesi

### 🏢 Şirket Yönetimi

-   Çoklu şirket desteği
-   Şirket profil yönetimi
-   Kullanıcı yetkilendirme sistemi
-   Rol tabanlı erişim kontrolü

### 📦 Stok Yönetimi

-   Ürün stok takibi
-   Barkod sistemi entegrasyonu
-   Stok giriş/çıkış işlemleri
-   Minimum stok uyarıları
-   Stok yaşı analizi

### 🏭 Üretim Yönetimi

-   Fırın (Kiln) yönetimi
-   Üretim planlaması
-   Kalite kontrol süreçleri
-   Vardiya bazlı üretim takibi
-   Kapasite planlama

### 🔍 Barkod Sistemi

-   QR kod üretimi
-   Barkod tarama
-   Ürün takibi
-   Barkod birleştirme işlemleri
-   Hareket geçmişi takibi

### 🏪 Depo Yönetimi

-   Çoklu depo desteği
-   Depo transfer işlemleri
-   Konum bazlı stok takibi
-   Depo performans analizi

### 🚚 Sevkiyat Noktaları

-   Sevkiyat noktası yönetimi
-   Teslimat takibi

### 🔬 Laboratuvar Yönetimi

-   Kalite kontrol süreçleri
-   Test sonuçları yönetimi
-   Onay/red işlemleri
-   Toplu işlem desteği

## 🛠️ Teknolojiler

### Backend

-   **Framework**: Laravel 8+
-   **PHP**: 8.0+
-   **Veritabanı**: MySQL 5.7+

### Frontend

-   **CSS Framework**: Bootstrap 5
-   **JavaScript**: jQuery, Chart.js
-   **Icons**: Font Awesome, Material Design Icons

### Analitik & İstatistik

-   **İstatistiksel Analiz**: Z-Score, trend analizi, anomali tespiti
-   **Tahmin Modelleri**: Basit istatistiksel üretim ve kalite tahmin algoritmaları
-   **Gerçek Zamanlı İşleme**: Canlı veri analizi ve güncelleme
-   **Veri Görselleştirme**: Grafik ve tablo tabanlı raporlama

### Entegrasyonlar

-   **Barkod**: QR Code Generator, Barcode Scanner
-   **Export**: Laravel Excel
-   **Cache**: Redis (opsiyonel)
-   **Backup**: Spatie Laravel Backup

## 📦 Kurulum

### Gereksinimler

-   **PHP**: 8.0+ (8.1+ önerilen)
-   **Composer**: 2.0+
-   **MySQL**: 5.7+ (8.0+ önerilen)
-   **Web Server**: Apache/Nginx
-   **RAM**: 4GB+ (analitik özellikleri için 6GB+ önerilen)
-   **Storage**: 10GB+ boş alan

### Adımlar

1. **Projeyi klonlayın**

```bash
git clone [repository-url]
cd kalefrit
```

2. **Bağımlılıkları yükleyin**

```bash
composer install --no-dev --optimize-autoloader
```

3. **Environment dosyasını oluşturun**

```bash
cp .env.example .env
```

4. **Uygulama anahtarını oluşturun**

```bash
php artisan key:generate
```

5. **Veritabanını yapılandırın**

```bash
# .env dosyasında veritabanı bilgilerini güncelleyin
php artisan migrate
php artisan db:seed
```

6. **Storage linkini oluşturun**

```bash
php artisan storage:link
```

7. **Cache'i temizleyin**

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

8. **Uygulamayı çalıştırın**

```bash
php artisan serve
```

### Analitik Model Kurulumu

Analitik özelliklerin tam olarak çalışması için:

```bash
# Veri bütünlüğü kontrolü
php artisan data:integrity:check

# Cache'i optimize et
php artisan optimize
```

## 🔐 Güvenlik

-   Kullanıcı kimlik doğrulama sistemi
-   Rol tabanlı yetkilendirme
-   Veri bütünlüğü kontrolleri
-   Güvenli API endpoints
-   CSRF koruması
-   SQL injection koruması
-   XSS koruması

## 📱 Kullanım

### Temel Kullanım

1. **Giriş Yapın**: Admin paneline giriş yapın
2. **Modülleri Kullanın**: Üst menüden ihtiyacınız olan modülü seçin
3. **İşlemleri Gerçekleştirin**: Stok, üretim, raporlama işlemlerini yapın

### Analitik Özellikleri

1. **Dashboard'a Gidin**: Ana sayfaya gidin
2. **Analitik Bölümünü Bulun**: Sayfayı aşağı kaydırarak analitik bölümünü bulun
3. **İçgörüleri İnceleyin**: Üretim tahminleri, kalite risk analizi ve önerileri görüntüleyin

### Günlük Raporlar

1. **Rapor Menüsünü Açın**: Üst menüde "Rapor" dropdown'ına tıklayın
2. **Günlük Raporu Seçin**: "Günlük Rapor" seçeneğini seçin
3. **Tarih Filtreleme**: İstediğiniz tarihi seçin
4. **Verileri İndirin**: Excel formatında raporları indirin

## 📊 Analitik Özellik Detayları

### Üretim Tahmini

-   **Veri Analizi**: Son 30 günlük üretim verileri
-   **Tahmin Süresi**: Gelecek 7 gün
-   **Güven Seviyesi**: %60-95 arası
-   **Güncelleme**: Her gece otomatik
-   **Metod**: Basit istatistiksel hesaplama (ortalama × gün sayısı)

### Kalite Risk Analizi

-   **Risk Seviyeleri**: Düşük (≤%5), Orta (%6-15), Yüksek (>%15)
-   **Analiz Periyodu**: Son 14 gün
-   **Tahmin**: Basit trend analizi
-   **Öneriler**: Veri tabanlı optimizasyon önerileri

### Anomali Tespiti

-   **Algoritma**: Z-Score analizi
-   **Eşik Değeri**: 2.5 standart sapma
-   **Minimum Veri**: 3 günlük veri
-   **Tespit Türleri**: Üretim, kalite, zamanlama

## 🤝 Katkıda Bulunma

1. Fork yapın
2. Feature branch oluşturun (`git checkout -b feature/AmazingFeature`)
3. Değişikliklerinizi commit edin (`git commit -m 'Add some AmazingFeature'`)
4. Branch'inizi push edin (`git push origin feature/AmazingFeature`)
5. Pull Request oluşturun

### Geliştirme Kurulları

-   PSR-12 kod standartlarına uyun
-   Unit testler yazın
-   Analitik modelleri test edin
-   Dokümantasyonu güncelleyin

## 📄 Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Detaylar için `LICENSE` dosyasına bakın.

## 📞 Destek

-   **Teknik Destek**: onurcansahin@kale.com.tr
-   **Dokümantasyon**: `/resources/views/manual/` klasöründe
-   **Kurulum Rehberi**: `/talimatlar/` klasöründe

---

**Kalefrit v2.0** - İstatistiksel analiz destekli işletme yönetiminde dijital dönüşüm 🚀📊

---

# Kalefrit - Management System v2.0

<p align="center">
  <img src="public/assets/images/kale-logo.png" alt="Kalefrit Logo" width="200">
</p>

<p align="center">
  <strong>Advanced Statistical Analysis-Powered Modern and User-Friendly Management System</strong>
</p>

## 🚀 New: Advanced Statistical Analysis System

Kalefrit is now enhanced with advanced statistical analysis features! Take your business to the next level with production forecasts, quality risk analysis, anomaly detection, and data-driven optimization recommendations.

## 📋 About the Project

Kalefrit is a comprehensive management system developed for businesses. This system digitizes business operations to increase efficiency and minimize error risk. **v2.0** adds advanced statistical analysis features.

## ✨ Features

### 📊 Advanced Statistical Analytics

-   **Production Forecasting**: Statistical 7-day production forecasts
-   **Quality Risk Analysis**: Automatic quality risk assessment and alerts
-   **Anomaly Detection**: Statistical analysis-based production anomaly detection
-   **Data-Driven Recommendations**: Statistical analysis-generated optimization recommendations
-   **Analytics Model Management**: Continuous data analysis with 3 different analytical models

### 📊 Advanced Reporting

-   **Daily Reports**: Detailed daily production and performance analysis
-   **Shift-Based Analysis**: Production performance across 3 shifts
-   **Kiln Performance Metrics**: Efficiency analysis for each kiln
-   **Quality Control Reports**: Rejection rates and quality trends
-   **Stock Age Warnings**: Automatic warnings for old stock
-   **Excel Export**: Download all reports in Excel format

### 🏢 Company Management

-   Multi-company support
-   Company profile management
-   User authorization system
-   Role-based access control

### 📦 Inventory Management

-   Product stock tracking
-   Barcode system integration
-   Stock in/out operations
-   Minimum stock alerts
-   Stock age analysis

### 🏭 Production Management

-   Kiln management
-   Production planning
-   Quality control processes
-   Shift-based production tracking
-   Capacity planning

### 🔍 Barcode System

-   QR code generation
-   Barcode scanning
-   Product tracking
-   Barcode merging operations
-   Movement history tracking

### 🏪 Warehouse Management

-   Multi-warehouse support
-   Warehouse transfer operations
-   Location-based stock tracking
-   Warehouse performance analysis

### 🚚 Shipping Points

-   Shipping point management
-   Delivery tracking

### 🔬 Laboratory Management

-   Quality control processes
-   Test results management
-   Approval/rejection operations
-   Bulk operation support

## 🛠️ Technologies

### Backend

-   **Framework**: Laravel 8+
-   **PHP**: 8.0+
-   **Database**: MySQL 5.7+

### Frontend

-   **CSS Framework**: Bootstrap 5
-   **JavaScript**: jQuery, Chart.js
-   **Icons**: Font Awesome, Material Design Icons

### Analytics & Statistics

-   **Statistical Analysis**: Z-Score, trend analysis, anomaly detection
-   **Prediction Models**: Simple statistical production and quality prediction algorithms
-   **Real-time Processing**: Live data analysis and updates
-   **Data Visualization**: Chart and table-based reporting

### Integrations

-   **Barcode**: QR Code Generator, Barcode Scanner
-   **Export**: Laravel Excel
-   **Cache**: Redis (optional)
-   **Backup**: Spatie Laravel Backup

## 📦 Installation

### Requirements

-   **PHP**: 8.0+ (8.1+ recommended)
-   **Composer**: 2.0+
-   **MySQL**: 5.7+ (8.0+ recommended)
-   **Web Server**: Apache/Nginx
-   **RAM**: 4GB+ (6GB+ recommended for analytics features)
-   **Storage**: 10GB+ free space

### Steps

1. **Clone the project**

```bash
git clone [repository-url]
cd kalefrit
```

2. **Install dependencies**

```bash
composer install --no-dev --optimize-autoloader
```

3. **Create environment file**

```bash
cp .env.example .env
```

4. **Generate application key**

```bash
php artisan key:generate
```

5. **Configure database**

```bash
# Update database information in .env file
php artisan migrate
php artisan db:seed
```

6. **Create storage link**

```bash
php artisan storage:link
```

7. **Clear cache**

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

8. **Run the application**

```bash
php artisan serve
```

### Analytics Model Setup

For full analytics functionality:

```bash
# Check data integrity
php artisan data:integrity:check

# Optimize cache
php artisan optimize
```

## 🔐 Security

-   User authentication system
-   Role-based authorization
-   Data integrity checks
-   Secure API endpoints
-   CSRF protection
-   SQL injection protection
-   XSS protection

## 📱 Usage

### Basic Usage

1. **Login**: Access the admin panel
2. **Use Modules**: Select the module you need from the top menu
3. **Perform Operations**: Execute inventory, production, and reporting operations

### Analytics Features

1. **Go to Dashboard**: Navigate to the main page
2. **Find Analytics Section**: Scroll down to find the analytics section
3. **Review Insights**: View production forecasts, quality risk analysis, and recommendations

### Daily Reports

1. **Open Report Menu**: Click "Rapor" dropdown in the top menu
2. **Select Daily Report**: Choose "Günlük Rapor" option
3. **Date Filtering**: Select your desired date
4. **Download Data**: Export reports in Excel format

## 📊 Analytics Feature Details

### Production Forecasting

-   **Data Analysis**: Last 30 days of production data
-   **Forecast Period**: Next 7 days
-   **Confidence Level**: 60-95%
-   **Updates**: Automatic every night
-   **Method**: Simple statistical calculation (average × days)

### Quality Risk Analysis

-   **Risk Levels**: Low (≤5%), Medium (6-15%), High (>15%)
-   **Analysis Period**: Last 14 days
-   **Prediction**: Simple trend analysis
-   **Recommendations**: Data-driven optimization recommendations

### Anomaly Detection

-   **Algorithm**: Z-Score analysis
-   **Threshold**: 2.5 standard deviations
-   **Minimum Data**: 3 days of data
-   **Detection Types**: Production, quality, timing

## 🤝 Contributing

1. Fork the project
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Development Rules

-   Follow PSR-12 coding standards
-   Write unit tests
-   Test analytics models
-   Update documentation

## 📄 License

This project is licensed under the MIT License. See the `LICENSE` file for details.

## 📞 Support

-   **Technical Support**: onurcansahin@kale.com.tr
-   **Documentation**: In `/resources/views/manual/` folder
-   **Installation Guide**: In `/talimatlar/` folder

---

**Kalefrit v2.0** - Statistical analysis-powered digital transformation in business management 🚀📊
