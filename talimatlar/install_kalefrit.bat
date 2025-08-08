@echo off
chcp 65001 >nul
title Kalefrit Barkod Sistemi - Kurulum Scripti

echo.
echo ========================================
echo   Kalefrit Barkod Sistemi Kurulumu
echo ========================================
echo.

:: Yönetici hakları kontrolü
net session >nul 2>&1
if %errorLevel% == 0 (
    echo [✓] Yönetici hakları mevcut
) else (
    echo [✗] Bu script yönetici hakları gerektirir!
    echo Lütfen CMD'yi yönetici olarak çalıştırın.
    pause
    exit /b 1
)

:: XAMPP kontrolü
if not exist "C:\xampp\apache\bin\httpd.exe" (
    echo [✗] XAMPP bulunamadı!
    echo Lütfen önce XAMPP'ı kurun: https://www.apachefriends.org/
    pause
    exit /b 1
) else (
    echo [✓] XAMPP bulundu
)

:: Composer kontrolü
composer --version >nul 2>&1
if %errorLevel% == 0 (
    echo [✓] Composer bulundu
) else (
    echo [✗] Composer bulunamadı!
    echo Lütfen Composer'ı kurun: https://getcomposer.org/
    pause
    exit /b 1
)

:: Proje dizini kontrolü
if not exist "C:\xampp\htdocs\kalefrit" (
    echo [✗] Kalefrit proje dizini bulunamadı!
    echo Lütfen proje dosyalarını C:\xampp\htdocs\kalefrit dizinine kopyalayın.
    pause
    exit /b 1
) else (
    echo [✓] Proje dizini bulundu
)

echo.
echo Kurulum başlıyor...
echo.

:: Proje dizinine geç
cd /d "C:\xampp\htdocs\kalefrit"

:: .env dosyası kontrolü
if not exist ".env" (
    echo [i] .env dosyası oluşturuluyor...
    if exist ".env.example" (
        copy ".env.example" ".env" >nul
        echo [✓] .env dosyası oluşturuldu
    ) else (
        echo [✗] .env.example dosyası bulunamadı!
        pause
        exit /b 1
    )
) else (
    echo [✓] .env dosyası mevcut
)

:: Composer bağımlılıklarını yükle
echo [i] Composer bağımlılıkları yükleniyor...
composer install --no-dev --optimize-autoloader
if %errorLevel% == 0 (
    echo [✓] Composer bağımlılıkları yüklendi
) else (
    echo [✗] Composer bağımlılıkları yüklenemedi!
    pause
    exit /b 1
)

:: Laravel uygulama anahtarı oluştur
echo [i] Laravel uygulama anahtarı oluşturuluyor...
php artisan key:generate --force
if %errorLevel% == 0 (
    echo [✓] Uygulama anahtarı oluşturuldu
) else (
    echo [✗] Uygulama anahtarı oluşturulamadı!
    pause
    exit /b 1
)

:: Cache'leri temizle
echo [i] Cache'ler temizleniyor...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo [✓] Cache'ler temizlendi

:: Dosya izinlerini ayarla
echo [i] Dosya izinleri ayarlanıyor...
icacls "storage" /grant "Everyone:(OI)(CI)F" >nul 2>&1
icacls "bootstrap\cache" /grant "Everyone:(OI)(CI)F" >nul 2>&1
echo [✓] Dosya izinleri ayarlandı

:: Veritabanı migration'larını çalıştır
echo [i] Veritabanı migration'ları çalıştırılıyor...
php artisan migrate --force
if %errorLevel% == 0 (
    echo [✓] Migration'lar tamamlandı
) else (
    echo [⚠] Migration hatası! Veritabanı ayarlarını kontrol edin.
)

:: Optimizasyonları yap
echo [i] Performans optimizasyonları yapılıyor...
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo [✓] Optimizasyonlar tamamlandı

:: Apache virtual host yapılandırması
echo [i] Apache virtual host yapılandırması kontrol ediliyor...
set VHOST_FILE=C:\xampp\apache\conf\extra\httpd-vhosts.conf
set VHOST_CONFIG=<VirtualHost *:80^>^
    ServerName kalefrit.local^
    ServerAlias www.kalefrit.local^
    DocumentRoot "C:/xampp/htdocs/kalefrit/public"^
    ^<Directory "C:/xampp/htdocs/kalefrit/public"^>^
        AllowOverride All^
        Require all granted^
        Options Indexes FollowSymLinks MultiViews^
    ^</Directory^>^
    ErrorLog "logs/kalefrit-error.log"^
    CustomLog "logs/kalefrit-access.log" combined^
^</VirtualHost^>

:: Virtual host yapılandırmasını kontrol et
findstr /C:"kalefrit.local" "%VHOST_FILE%" >nul 2>&1
if %errorLevel% == 0 (
    echo [✓] Virtual host yapılandırması mevcut
) else (
    echo [i] Virtual host yapılandırması ekleniyor...
    echo. >> "%VHOST_FILE%"
    echo # Kalefrit Virtual Host >> "%VHOST_FILE%"
    echo %VHOST_CONFIG% >> "%VHOST_FILE%"
    echo [✓] Virtual host yapılandırması eklendi
)

:: Hosts dosyası kontrolü
echo [i] Hosts dosyası kontrol ediliyor...
set HOSTS_FILE=C:\Windows\System32\drivers\etc\hosts
findstr /C:"kalefrit.local" "%HOSTS_FILE%" >nul 2>&1
if %errorLevel% == 0 (
    echo [✓] Hosts dosyası yapılandırması mevcut
) else (
    echo [i] Hosts dosyasına kayıt ekleniyor...
    echo # Kalefrit Application >> "%HOSTS_FILE%"
    echo 127.0.0.1 kalefrit.local >> "%HOSTS_FILE%"
    echo 127.0.0.1 www.kalefrit.local >> "%HOSTS_FILE%"
    echo [✓] Hosts dosyası güncellendi
)

:: Güvenlik duvarı ayarları
echo [i] Güvenlik duvarı ayarları kontrol ediliyor...
netsh advfirewall firewall show rule name="Kalefrit HTTP" >nul 2>&1
if %errorLevel% == 0 (
    echo [✓] HTTP güvenlik duvarı kuralı mevcut
) else (
    echo [i] HTTP güvenlik duvarı kuralı ekleniyor...
    netsh advfirewall firewall add rule name="Kalefrit HTTP" dir=in action=allow protocol=TCP localport=80
    echo [✓] HTTP güvenlik duvarı kuralı eklendi
)

:: XAMPP servislerini başlat
echo [i] XAMPP servisleri başlatılıyor...
net start Apache2.4 >nul 2>&1
net start MySQL80 >nul 2>&1
echo [✓] XAMPP servisleri başlatıldı

:: Test bağlantısı
echo [i] Bağlantı test ediliyor...
timeout /t 3 /nobreak >nul
curl -s -o nul -w "%%{http_code}" http://kalefrit.local | findstr "200" >nul 2>&1
if %errorLevel% == 0 (
    echo [✓] Bağlantı testi başarılı
) else (
    echo [⚠] Bağlantı testi başarısız! Manuel kontrol gerekli.
)

echo.
echo ========================================
echo   Kurulum Tamamlandı!
echo ========================================
echo.
echo Erişim adresleri:
echo - Local: http://kalefrit.local
echo - IP: http://127.0.0.1
echo.
echo Yapılması gerekenler:
echo 1. .env dosyasındaki veritabanı ayarlarını kontrol edin
echo 2. Veritabanı kullanıcısını oluşturun
echo 3. SSL sertifikası kurun (opsiyonel)
echo 4. Yedekleme sistemi kurun
echo.
echo Detaylı bilgi için PRODUCTION_DEPLOYMENT.md dosyasını inceleyin.
echo.
pause
