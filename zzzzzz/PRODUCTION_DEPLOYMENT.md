# 🚀 Kalefrit Production Deployment Rehberi

## 📋 İçindekiler

1. [Sistem Gereksinimleri](#sistem-gereksinimleri)
2. [Yazılım Kurulumları](#yazılım-kurulumları)
3. [Proje Kurulumu](#proje-kurulumu)
4. [Veritabanı Yapılandırması](#veritabanı-yapılandırması)
5. [Web Sunucu Yapılandırması](#web-sunucu-yapılandırması)
6. [Güvenlik Ayarları](#güvenlik-ayarları)
7. [LAN Yayınlama](#lan-yayınlama)
8. [SSL Sertifikası](#ssl-sertifikası)
9. [Performans Optimizasyonu](#performans-optimizasyonu)
10. [Yedekleme ve Bakım](#yedekleme-ve-bakım)
11. [Sorun Giderme](#sorun-giderme)

---

## 🖥️ Sistem Gereksinimleri

### Minimum Sistem Gereksinimleri

-   **İşletim Sistemi**: Windows 10/11 veya Windows Server 2019/2022
-   **İşlemci**: Intel Core i3 veya AMD Ryzen 3 (2.0 GHz+)
-   **RAM**: 8 GB (16 GB önerilen)
-   **Depolama**: 50 GB boş alan (SSD önerilen)
-   **Ağ**: Gigabit Ethernet bağlantısı

### Önerilen Sistem Gereksinimleri

-   **İşlemci**: Intel Core i5/i7 veya AMD Ryzen 5/7
-   **RAM**: 16 GB
-   **Depolama**: 100 GB SSD
-   **Ağ**: Gigabit Ethernet + Yedekli bağlantı

---

## 📦 Yazılım Kurulumları

### 1. XAMPP Kurulumu

```bash
# 1. XAMPP'ı indirin (https://www.apachefriends.org/)
# 2. Kurulum sırasında şu bileşenleri seçin:
#    - Apache
#    - MySQL
#    - PHP 8.1+
#    - phpMyAdmin
#    - FileZilla (opsiyonel)

# 3. Kurulum dizini: C:\xampp
# 4. XAMPP Control Panel'i yönetici olarak çalıştırın
```

### 2. Composer Kurulumu

```bash
# 1. Composer'ı indirin (https://getcomposer.org/)
# 2. Kurulum sırasında PHP yolunu seçin: C:\xampp\php\php.exe
# 3. Kurulumu tamamlayın ve bilgisayarı yeniden başlatın
```

### 3. Git Kurulumu

```bash
# 1. Git'i indirin (https://git-scm.com/)
# 2. Varsayılan ayarlarla kurulumu tamamlayın
# 3. Kurulum sonrası bilgisayarı yeniden başlatın
```

### 4. Node.js Kurulumu (Opsiyonel)

```bash
# 1. Node.js LTS'yi indirin (https://nodejs.org/)
# 2. Kurulumu tamamlayın
# 3. NPM'in kurulduğunu doğrulayın: npm --version
```

---

## 🛠️ Proje Kurulumu

### 1. Proje Dosyalarını İndirme

```bash
# 1. CMD'yi yönetici olarak açın
# 2. XAMPP htdocs dizinine gidin
cd C:\xampp\htdocs

# 3. Proje klasörünü oluşturun
mkdir kalefrit
cd kalefrit

# 4. Proje dosyalarını kopyalayın (USB, ağ paylaşımı veya Git ile)
# Eğer Git kullanıyorsanız:
git clone [repository-url] .

# 5. Dosya izinlerini kontrol edin
```

### 2. Composer Bağımlılıklarını Kurma

```bash
# 1. Proje dizininde CMD açın
cd C:\xampp\htdocs\kalefrit

# 2. Composer bağımlılıklarını yükleyin
composer install --no-dev --optimize-autoloader

# 3. Kurulumu doğrulayın
composer --version
```

### 3. Environment Dosyası Yapılandırması

```bash
# 1. .env.example dosyasını .env olarak kopyalayın
copy .env.example .env

# 2. .env dosyasını düzenleyin
notepad .env
```

**.env Dosyası Yapılandırması:**

```env
APP_NAME="Kalefrit Barkod Sistemi"
APP_ENV=production
APP_KEY=base64:your-generated-key-here
APP_DEBUG=false
APP_URL=http://your-server-ip

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kalefrit_db
DB_USERNAME=kalefrit_user
DB_PASSWORD=your-secure-password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### 4. Laravel Uygulama Anahtarı Oluşturma

```bash
# 1. Uygulama anahtarını oluşturun
php artisan key:generate

# 2. Cache'i temizleyin
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

---

## 🗄️ Veritabanı Yapılandırması

### 1. MySQL Veritabanı Oluşturma

```sql
-- 1. phpMyAdmin'e gidin: http://localhost/phpmyadmin
-- 2. Yeni veritabanı oluşturun:
CREATE DATABASE kalefrit_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 3. Yeni kullanıcı oluşturun:
CREATE USER 'kalefrit_user'@'localhost' IDENTIFIED BY 'your-secure-password';

-- 4. Kullanıcıya yetki verin:
GRANT ALL PRIVILEGES ON kalefrit_db.* TO 'kalefrit_user'@'localhost';
FLUSH PRIVILEGES;
```

### 2. Veritabanı Migration'ları

```bash
# 1. Migration'ları çalıştırın
php artisan migrate

# 2. Seed verilerini yükleyin (opsiyonel)
php artisan db:seed

# 3. Veritabanını doğrulayın
php artisan migrate:status
```

### 3. Veritabanı Optimizasyonu

```sql
-- MySQL performans ayarları (my.ini dosyasında)
[mysqld]
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
query_cache_size = 128M
query_cache_type = 1
max_connections = 200
```

---

## 🌐 Web Sunucu Yapılandırması

### 1. Apache Virtual Host Yapılandırması

```apache
# C:\xampp\apache\conf\extra\httpd-vhosts.conf dosyasına ekleyin:

<VirtualHost *:80>
    ServerName kalefrit.local
    ServerAlias www.kalefrit.local
    DocumentRoot "C:/xampp/htdocs/kalefrit/public"

    <Directory "C:/xampp/htdocs/kalefrit/public">
        AllowOverride All
        Require all granted
        Options Indexes FollowSymLinks MultiViews
    </Directory>

    ErrorLog "logs/kalefrit-error.log"
    CustomLog "logs/kalefrit-access.log" combined
</VirtualHost>
```

### 2. .htaccess Dosyası (public/.htaccess)

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Cache Control
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/ico "access plus 1 year"
    ExpiresByType image/icon "access plus 1 year"
    ExpiresByType text/plain "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType application/x-shockwave-flash "access plus 1 month"
</IfModule>
```

### 3. Apache Modüllerini Etkinleştirme

```apache
# C:\xampp\apache\conf\httpd.conf dosyasında şu satırların başındaki # işaretini kaldırın:
LoadModule rewrite_module modules/mod_rewrite.so
LoadModule headers_module modules/mod_headers.so
LoadModule deflate_module modules/mod_deflate.so
LoadModule expires_module modules/mod_expires.so
```

---

## 🔒 Güvenlik Ayarları

### 1. Dosya İzinleri

```bash
# Windows'ta dosya izinlerini ayarlayın
# storage ve bootstrap/cache klasörlerine yazma izni verin

# CMD'de (yönetici olarak):
icacls "C:\xampp\htdocs\kalefrit\storage" /grant "Everyone:(OI)(CI)F"
icacls "C:\xampp\htdocs\kalefrit\bootstrap\cache" /grant "Everyone:(OI)(CI)F"
```

### 2. Güvenlik Duvarı Ayarları

```bash
# Windows Güvenlik Duvarı'nda şu portları açın:
# - 80 (HTTP)
# - 443 (HTTPS) - SSL kullanıyorsanız
# - 3306 (MySQL) - sadece gerekirse

# PowerShell'de (yönetici olarak):
New-NetFirewallRule -DisplayName "Kalefrit HTTP" -Direction Inbound -Protocol TCP -LocalPort 80 -Action Allow
New-NetFirewallRule -DisplayName "Kalefrit HTTPS" -Direction Inbound -Protocol TCP -LocalPort 443 -Action Allow
```

### 3. Laravel Güvenlik Ayarları

```php
// config/app.php dosyasında:
'debug' => env('APP_DEBUG', false),
'env' => env('APP_ENV', 'production'),

// config/session.php dosyasında:
'secure' => true, // HTTPS kullanıyorsanız
'http_only' => true,
'same_site' => 'lax',
```

---

## 🌐 LAN Yayınlama

### 1. Statik IP Adresi Ayarlama

```bash
# Windows'ta statik IP ayarlayın:
# 1. Ağ Ayarları > Ağ Bağdaştırıcısı Ayarları
# 2. Ethernet > Özellikler > Internet Protocol Version 4
# 3. Aşağıdaki ayarları yapın:
#    IP Adresi: 192.168.1.100 (veya tercih ettiğiniz IP)
#    Alt Ağ Maskesi: 255.255.255.0
#    Varsayılan Ağ Geçidi: 192.168.1.1
#    DNS: 8.8.8.8, 8.8.4.4
```

### 2. Hosts Dosyası Düzenleme

```bash
# C:\Windows\System32\drivers\etc\hosts dosyasına ekleyin:
192.168.1.100 kalefrit.local
192.168.1.100 www.kalefrit.local
```

### 3. DNS Sunucu Ayarları (Opsiyonel)

```bash
# Eğer kendi DNS sunucunuz varsa:
# 1. DNS sunucusunda A kaydı oluşturun:
#    kalefrit.local -> 192.168.1.100
#    www.kalefrit.local -> 192.168.1.100
```

### 4. Router Ayarları

```bash
# Router'da port forwarding ayarlayın:
# 1. Router yönetim paneline girin
# 2. Port Forwarding bölümüne gidin
# 3. Aşağıdaki kuralları ekleyin:
#    HTTP: 80 -> 192.168.1.100:80
#    HTTPS: 443 -> 192.168.1.100:443 (SSL kullanıyorsanız)
```

---

## 🔐 SSL Sertifikası (Opsiyonel)

### 1. Let's Encrypt Sertifikası (Ücretsiz)

```bash
# 1. Certbot'u indirin: https://certbot.eff.org/
# 2. Sertifikayı oluşturun:
certbot certonly --webroot -w C:\xampp\htdocs\kalefrit\public -d kalefrit.local

# 3. Apache SSL yapılandırması:
# C:\xampp\apache\conf\extra\httpd-ssl.conf dosyasını düzenleyin
```

### 2. Self-Signed Sertifikası (Test için)

```bash
# 1. OpenSSL ile sertifika oluşturun:
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout kalefrit.key -out kalefrit.crt

# 2. Sertifikayı Apache'ye yükleyin
# 3. HTTPS virtual host yapılandırması yapın
```

---

## ⚡ Performans Optimizasyonu

### 1. Laravel Optimizasyonu

```bash
# 1. Config cache'i oluşturun
php artisan config:cache

# 2. Route cache'i oluşturun
php artisan route:cache

# 3. View cache'i oluşturun
php artisan view:cache

# 4. Composer autoload'u optimize edin
composer install --optimize-autoloader --no-dev
```

### 2. PHP Optimizasyonu

```ini
; C:\xampp\php\php.ini dosyasında:
memory_limit = 512M
max_execution_time = 300
upload_max_filesize = 64M
post_max_size = 64M
opcache.enable = 1
opcache.memory_consumption = 128
opcache.interned_strings_buffer = 8
opcache.max_accelerated_files = 4000
opcache.revalidate_freq = 2
opcache.fast_shutdown = 1
```

### 3. MySQL Optimizasyonu

```ini
; C:\xampp\mysql\bin\my.ini dosyasında:
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
query_cache_size = 128M
max_connections = 200
```

---

## 💾 Yedekleme ve Bakım

### 1. Otomatik Yedekleme Scripti

```batch
@echo off
REM C:\backup\backup.bat dosyası oluşturun

set BACKUP_DIR=C:\backup
set DATE=%date:~-4,4%%date:~-10,2%%date:~-7,2%
set TIME=%time:~0,2%%time:~3,2%%time:~6,2%

REM Veritabanı yedekleme
mysqldump -u kalefrit_user -p kalefrit_db > "%BACKUP_DIR%\db_backup_%DATE%_%TIME%.sql"

REM Dosya yedekleme
xcopy "C:\xampp\htdocs\kalefrit" "%BACKUP_DIR%\files_backup_%DATE%_%TIME%" /E /I /H

REM Eski yedekleri temizle (30 günden eski)
forfiles /p "%BACKUP_DIR%" /s /m *.* /d -30 /c "cmd /c del @path"

echo Backup completed at %DATE% %TIME%
```

### 2. Windows Task Scheduler

```bash
# 1. Görev Zamanlayıcısı'nı açın
# 2. Yeni görev oluşturun:
#    - Program: C:\backup\backup.bat
#    - Zamanlama: Günlük, saat 02:00
#    - Koşullar: Bilgisayar açık değilse başlat
```

### 3. Log Rotasyonu

```apache
# C:\xampp\apache\conf\httpd.conf dosyasında:
LogFormat "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"" combined
CustomLog "logs/access.log" combined
ErrorLog "logs/error.log"
```

---

## 🔧 Sorun Giderme

### 1. Yaygın Sorunlar ve Çözümleri

#### Apache Başlatılamıyor

```bash
# 1. Port 80'in kullanımda olup olmadığını kontrol edin:
netstat -ano | findstr :80

# 2. IIS'i durdurun (varsa):
net stop w3svc

# 3. Skype'ı kapatın (port 80 kullanabilir)
```

#### MySQL Bağlantı Hatası

```bash
# 1. MySQL servisinin çalıştığını kontrol edin
# 2. Kullanıcı yetkilerini kontrol edin
# 3. .env dosyasındaki veritabanı bilgilerini doğrulayın
```

#### Laravel 500 Hatası

```bash
# 1. Log dosyalarını kontrol edin:
# C:\xampp\htdocs\kalefrit\storage\logs\laravel.log

# 2. Dosya izinlerini kontrol edin
# 3. APP_KEY'in doğru olduğunu kontrol edin
```

### 2. Debug Modu

```php
// Geçici olarak debug modunu açın (.env dosyasında):
APP_DEBUG=true
```

### 3. Log Dosyaları

```bash
# Önemli log dosyaları:
# - C:\xampp\apache\logs\error.log
# - C:\xampp\apache\logs\access.log
# - C:\xampp\htdocs\kalefrit\storage\logs\laravel.log
# - C:\xampp\mysql\data\mysql_error.log
```

---

## 📞 Destek ve İletişim

### Teknik Destek

-   **E-posta**: onurcansahin@kale.com.tr
-   **Departman**: Dijital Dönüşüm Ofisi
-   **Geliştirici**: Buğra GÖRMÜŞ

### Acil Durumlar

-   **Sistem Çökmesi**: Yedeklerden geri yükleme
-   **Veri Kaybı**: Veritabanı yedeklerini kontrol edin
-   **Performans Sorunları**: Log dosyalarını inceleyin

---

## ✅ Kurulum Kontrol Listesi

-   [ ] XAMPP kuruldu ve çalışıyor
-   [ ] Composer kuruldu
-   [ ] Proje dosyaları kopyalandı
-   [ ] .env dosyası yapılandırıldı
-   [ ] Veritabanı oluşturuldu
-   [ ] Migration'lar çalıştırıldı
-   [ ] Apache virtual host yapılandırıldı
-   [ ] .htaccess dosyası düzenlendi
-   [ ] Dosya izinleri ayarlandı
-   [ ] Güvenlik duvarı ayarları yapıldı
-   [ ] Statik IP adresi atandı
-   [ ] Hosts dosyası düzenlendi
-   [ ] Router port forwarding ayarlandı
-   [ ] SSL sertifikası kuruldu (opsiyonel)
-   [ ] Performans optimizasyonları yapıldı
-   [ ] Yedekleme sistemi kuruldu
-   [ ] Test edildi ve çalışıyor

---

## 🎯 Son Adımlar

1. **Test Edebilme**: http://kalefrit.local adresinden erişim
2. **LAN Erişimi**: Diğer bilgisayarlardan http://192.168.1.100 erişimi
3. **Güvenlik Testi**: Güvenlik açıklarını kontrol edin
4. **Performans Testi**: Yük testi yapın
5. **Yedekleme Testi**: Yedekleme sistemini test edin
6. **Dokümantasyon**: Kurulum notlarını saklayın

Bu rehber ile Kalefrit Barkod Sistemi'nizi Windows LAN ortamında başarıyla yayınlayabilirsiniz. Herhangi bir sorunla karşılaştığınızda yukarıdaki iletişim bilgilerinden destek alabilirsiniz.
