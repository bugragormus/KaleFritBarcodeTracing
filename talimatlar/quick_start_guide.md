# ⚡ Kalefrit Hızlı Başlangıç Rehberi

## 🎯 İlk Adımlar (Bugün Yapılacaklar)

### 1. Mevcut Sistem Analizi (30 dakika)

```bash
# 1. Sürüm kontrolü yapın
version_check.bat

# 2. Sonuçları kaydedin
# - PHP sürümü: _______
# - MySQL sürümü: _______
# - Laravel sürümü: _______
# - Composer sürümü: _______
```

### 2. Yedekleme (1 saat)

```bash
# 1. Tam sistem yedeği
xcopy "C:\xampp\htdocs\kalefrit" "C:\backup\kalefrit_$(date +%Y%m%d)" /E /I /H

# 2. Veritabanı yedeği
mysqldump -u root -p kalefrit_db > C:\backup\kalefrit_db_$(date +%Y%m%d).sql

# 3. XAMPP yapılandırma yedeği
xcopy "C:\xampp\apache\conf" "C:\backup\apache_conf_$(date +%Y%m%d)" /E /I /H
```

### 3. Yeni Sistem Kurulumu (2 saat)

```bash
# 1. Yeni klasör oluşturun
mkdir C:\xampp\htdocs\kalefrit_new

# 2. Yeni projeyi kopyalayın
xcopy "C:\Users\[KullanıcıAdı]\Desktop\kalefrit\*" "C:\xampp\htdocs\kalefrit_new\" /E /I /H

# 3. Composer bağımlılıklarını yükleyin
cd C:\xampp\htdocs\kalefrit_new
composer install --no-dev --optimize-autoloader

# 4. Environment dosyası oluşturun
copy .env.example .env
```

## 🔧 Sürüm Uyumluluğu Kontrolü

### PHP Kontrolü

```bash
# PHP sürümünü kontrol edin
php -v

# Eğer PHP 7.4+ ise: ✅ Devam edin
# Eğer PHP 7.3- ise: ❌ XAMPP'ı güncelleyin
```

### MySQL Kontrolü

```bash
# MySQL sürümünü kontrol edin
mysql --version

# Eğer MySQL 5.7+ ise: ✅ Devam edin
# Eğer MySQL 5.6- ise: ❌ XAMPP'ı güncelleyin
```

### Composer Kontrolü

```bash
# Composer sürümünü kontrol edin
composer --version

# Eğer Composer 2.x ise: ✅ Devam edin
# Eğer Composer 1.x ise: ❌ Güncelleyin
composer self-update
```

## 🚀 Hızlı Test Kurulumu

### 1. Test Ortamı Oluşturma

```apache
# C:\xampp\apache\conf\extra\httpd-vhosts.conf dosyasına ekleyin:

<VirtualHost *:8080>
    ServerName kalefrit-test.local
    DocumentRoot "C:/xampp/htdocs/kalefrit_new/public"

    <Directory "C:/xampp/htdocs/kalefrit_new/public">
        AllowOverride All
        Require all granted
        Options Indexes FollowSymLinks MultiViews
    </Directory>

    ErrorLog "logs/kalefrit-test-error.log"
    CustomLog "logs/kalefrit-test-access.log" combined
</VirtualHost>
```

### 2. Hosts Dosyası Güncelleme

```
# C:\Windows\System32\drivers\etc\hosts dosyasına ekleyin:
127.0.0.1 kalefrit-test.local
```

### 3. Test Veritabanı Oluşturma

```sql
-- phpMyAdmin'de yeni veritabanı oluşturun:
CREATE DATABASE kalefrit_test;
CREATE USER 'kalefrit_test'@'localhost' IDENTIFIED BY 'test123';
GRANT ALL PRIVILEGES ON kalefrit_test.* TO 'kalefrit_test'@'localhost';
FLUSH PRIVILEGES;
```

### 4. Environment Yapılandırması

```env
# C:\xampp\htdocs\kalefrit_new\.env dosyasında:
APP_NAME="Kalefrit Test"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://kalefrit-test.local:8080

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kalefrit_test
DB_USERNAME=kalefrit_test
DB_PASSWORD=test123
```

### 5. Laravel Kurulumu

```bash
cd C:\xampp\htdocs\kalefrit_new

# Uygulama anahtarı oluşturun
php artisan key:generate

# Cache'i temizleyin
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Migration'ları çalıştırın
php artisan migrate

# Test verilerini yükleyin (varsa)
php artisan db:seed
```

## 🧪 Test ve Doğrulama

### 1. Tarayıcı Testi

```
http://kalefrit-test.local:8080
```

### 2. Fonksiyon Testleri

-   [ ] Ana sayfa yükleniyor
-   [ ] Giriş yapılabiliyor
-   [ ] Barkod okuma çalışıyor
-   [ ] Veritabanı bağlantısı var
-   [ ] Dosya yükleme çalışıyor

### 3. Performans Testi

```bash
# Sayfa yükleme süresini kontrol edin
# Memory kullanımını izleyin
# Hata loglarını kontrol edin
```

## 📋 Günlük Plan

### Gün 1: Hazırlık

-   [ ] Sistem analizi
-   [ ] Yedekleme
-   [ ] Yeni sistem kurulumu
-   [ ] Test ortamı hazırlama

### Gün 2: Test

-   [ ] Fonksiyon testleri
-   [ ] Performans testleri
-   [ ] Hata düzeltmeleri
-   [ ] Kullanıcı testleri

### Gün 3: Geçiş Planı

-   [ ] Veri migrasyonu
-   [ ] Canlı geçiş hazırlığı
-   [ ] Rollback planı
-   [ ] Kullanıcı bilgilendirmesi

### Gün 4: Canlı Geçiş

-   [ ] Düşük trafik saatinde geçiş
-   [ ] Hızlı test
-   [ ] İzleme ve optimizasyon

## ⚠️ Acil Durum Planı

### Hızlı Geri Alma

```bash
# Eğer sorun çıkarsa:
# 1. Yeni sistemi durdurun
# 2. Eski sistemi geri yükleyin
ren C:\xampp\htdocs\kalefrit_new kalefrit_problem
ren C:\xampp\htdocs\kalefrit_old kalefrit

# 3. Apache'yi yeniden başlatın
# 4. Test edin
```

### İletişim Planı

-   Kullanıcıları önceden bilgilendirin
-   Acil durum numarası paylaşın
-   Geri dönüş süresini belirtin

## 📞 Destek

### Sorun Giderme

1. Log dosyalarını kontrol edin
2. Sürüm uyumluluğunu doğrulayın
3. Yedeklerden geri yükleyin
4. Uzman desteği alın

### Önemli Dosyalar

-   `C:\xampp\apache\logs\error.log`
-   `C:\xampp\apache\logs\kalefrit-test-error.log`
-   `C:\xampp\htdocs\kalefrit_new\storage\logs\laravel.log`
