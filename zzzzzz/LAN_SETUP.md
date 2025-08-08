# 🌐 Kalefrit LAN Kurulum Rehberi

## 📋 LAN Kurulum Adımları

### 1. Statik IP Adresi Ayarlama

#### Windows'ta Statik IP Ayarlama:

1. **Ağ Ayarları** → **Ağ Bağdaştırıcısı Ayarları**
2. **Ethernet** → **Özellikler** → **Internet Protocol Version 4**
3. **Aşağıdaki IP'yi kullan** seçeneğini işaretleyin:
    ```
    IP Adresi: 192.168.1.100
    Alt Ağ Maskesi: 255.255.255.0
    Varsayılan Ağ Geçidi: 192.168.1.1
    DNS: 8.8.8.8, 8.8.4.4
    ```

#### Alternatif IP Adresleri:

```
192.168.1.100 - Kalefrit Server
192.168.1.101 - Yedek Server (opsiyonel)
192.168.1.102 - Test Server (opsiyonel)
```

### 2. Router Yapılandırması

#### Port Forwarding Ayarları:

Router yönetim paneline girin (genellikle http://192.168.1.1)

**Port Forwarding Kuralları:**

```
HTTP (Port 80):
- Dış Port: 80
- İç Port: 80
- Protokol: TCP
- Hedef IP: 192.168.1.100

HTTPS (Port 443) - SSL kullanıyorsanız:
- Dış Port: 443
- İç Port: 443
- Protokol: TCP
- Hedef IP: 192.168.1.100
```

#### DHCP Ayarları:

```
DHCP Aralığı: 192.168.1.50 - 192.168.1.99
DNS: 8.8.8.8, 8.8.4.4
```

### 3. DNS Yapılandırması

#### Yerel DNS Sunucu (Opsiyonel):

Eğer kendi DNS sunucunuz varsa:

**A Kayıtları:**

```
kalefrit.local     → 192.168.1.100
www.kalefrit.local → 192.168.1.100
```

#### Hosts Dosyası (Her Bilgisayarda):

`C:\Windows\System32\drivers\etc\hosts` dosyasına ekleyin:

```
# Kalefrit Barkod Sistemi
192.168.1.100 kalefrit.local
192.168.1.100 www.kalefrit.local
```

### 4. Güvenlik Duvarı Ayarları

#### Windows Güvenlik Duvarı:

PowerShell'i yönetici olarak açın:

```powershell
# HTTP için
New-NetFirewallRule -DisplayName "Kalefrit HTTP" -Direction Inbound -Protocol TCP -LocalPort 80 -Action Allow

# HTTPS için (SSL kullanıyorsanız)
New-NetFirewallRule -DisplayName "Kalefrit HTTPS" -Direction Inbound -Protocol TCP -LocalPort 443 -Action Allow

# MySQL için (sadece gerekirse)
New-NetFirewallRule -DisplayName "Kalefrit MySQL" -Direction Inbound -Protocol TCP -LocalPort 3306 -Action Allow
```

#### Antivirüs Yazılımı:

-   Windows Defender veya diğer antivirüs yazılımlarında port 80 ve 443'ü istisna olarak ekleyin
-   `C:\xampp\htdocs\kalefrit` klasörünü tarama dışında bırakın

### 5. Apache Virtual Host Yapılandırması

#### Ana Virtual Host:

`C:\xampp\apache\conf\extra\httpd-vhosts.conf` dosyasına ekleyin:

```apache
# Kalefrit Ana Uygulama
<VirtualHost *:80>
    ServerName kalefrit.local
    ServerAlias www.kalefrit.local
    ServerAdmin admin@kalefrit.local
    DocumentRoot "C:/xampp/htdocs/kalefrit/public"

    <Directory "C:/xampp/htdocs/kalefrit/public">
        AllowOverride All
        Require all granted
        Options Indexes FollowSymLinks MultiViews
    </Directory>

    # Log dosyaları
    ErrorLog "logs/kalefrit-error.log"
    CustomLog "logs/kalefrit-access.log" combined

    # Güvenlik başlıkları
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
</VirtualHost>

# IP Erişimi için
<VirtualHost *:80>
    ServerName 192.168.1.100
    DocumentRoot "C:/xampp/htdocs/kalefrit/public"

    <Directory "C:/xampp/htdocs/kalefrit/public">
        AllowOverride All
        Require all granted
        Options Indexes FollowSymLinks MultiViews
    </Directory>

    ErrorLog "logs/kalefrit-ip-error.log"
    CustomLog "logs/kalefrit-ip-access.log" combined
</VirtualHost>
```

### 6. SSL Sertifikası (Opsiyonel)

#### Self-Signed Sertifika Oluşturma:

```bash
# OpenSSL ile sertifika oluşturun
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout kalefrit.key -out kalefrit.crt

# Sertifika bilgileri:
Country Name: TR
State: Istanbul
Locality: Istanbul
Organization Name: Kale
Organizational Unit: Dijital Donusum Ofisi
Common Name: kalefrit.local
Email Address: admin@kalefrit.local
```

#### HTTPS Virtual Host:

```apache
<VirtualHost *:443>
    ServerName kalefrit.local
    ServerAlias www.kalefrit.local
    DocumentRoot "C:/xampp/htdocs/kalefrit/public"

    SSLEngine on
    SSLCertificateFile "C:/xampp/apache/conf/ssl/kalefrit.crt"
    SSLCertificateKeyFile "C:/xampp/apache/conf/ssl/kalefrit.key"

    <Directory "C:/xampp/htdocs/kalefrit/public">
        AllowOverride All
        Require all granted
        Options Indexes FollowSymLinks MultiViews
    </Directory>

    ErrorLog "logs/kalefrit-ssl-error.log"
    CustomLog "logs/kalefrit-ssl-access.log" combined
</VirtualHost>
```

### 7. Test ve Doğrulama

#### Bağlantı Testleri:

```bash
# Yerel test
curl -I http://kalefrit.local
curl -I http://127.0.0.1

# LAN test (diğer bilgisayarlardan)
curl -I http://192.168.1.100
ping 192.168.1.100

# DNS test
nslookup kalefrit.local
```

#### Tarayıcı Testleri:

1. **Yerel Erişim**: http://kalefrit.local
2. **IP Erişimi**: http://192.168.1.100
3. **LAN Erişimi**: Diğer bilgisayarlardan http://192.168.1.100

### 8. Performans Optimizasyonu

#### Apache Optimizasyonu:

`C:\xampp\apache\conf\httpd.conf` dosyasında:

```apache
# Performans ayarları
Timeout 300
KeepAlive On
MaxKeepAliveRequests 100
KeepAliveTimeout 5

# Worker ayarları
<IfModule mpm_winnt_module>
    ThreadsPerChild      150
    MaxRequestsPerChild    0
</IfModule>
```

#### PHP Optimizasyonu:

`C:\xampp\php\php.ini` dosyasında:

```ini
; Performans ayarları
memory_limit = 512M
max_execution_time = 300
max_input_time = 300
post_max_size = 64M
upload_max_filesize = 64M

; OPcache ayarları
opcache.enable = 1
opcache.memory_consumption = 128
opcache.interned_strings_buffer = 8
opcache.max_accelerated_files = 4000
opcache.revalidate_freq = 2
opcache.fast_shutdown = 1
```

### 9. Yedekleme ve İzleme

#### Otomatik Yedekleme:

```batch
@echo off
REM C:\backup\lan_backup.bat

set BACKUP_DIR=C:\backup\lan
set DATE=%date:~-4,4%%date:~-10,2%%date:~-7,2%
set TIME=%time:~0,2%%time:~3,2%%time:~6,2%

REM Veritabanı yedekleme
mysqldump -u kalefrit_user -p kalefrit_db > "%BACKUP_DIR%\db_%DATE%_%TIME%.sql"

REM Dosya yedekleme
xcopy "C:\xampp\htdocs\kalefrit" "%BACKUP_DIR%\files_%DATE%_%TIME%" /E /I /H

REM Log dosyaları yedekleme
xcopy "C:\xampp\apache\logs" "%BACKUP_DIR%\logs_%DATE%_%TIME%" /E /I /H

echo LAN backup completed at %DATE% %TIME%
```

#### Ağ İzleme:

```bash
# Bağlantıları izle
netstat -an | findstr :80
netstat -an | findstr :443

# Apache log'larını izle
tail -f C:\xampp\apache\logs\kalefrit-access.log
```

### 10. Sorun Giderme

#### Yaygın LAN Sorunları:

**1. Bağlantı Yok:**

```bash
# IP adresini kontrol et
ipconfig /all

# Ping test
ping 192.168.1.100

# Port kontrol
telnet 192.168.1.100 80
```

**2. DNS Sorunu:**

```bash
# DNS çözümleme test
nslookup kalefrit.local

# Hosts dosyasını kontrol et
type C:\Windows\System32\drivers\etc\hosts
```

**3. Güvenlik Duvarı Sorunu:**

```bash
# Güvenlik duvarı kurallarını kontrol et
netsh advfirewall firewall show rule name="Kalefrit*"
```

**4. Apache Sorunu:**

```bash
# Apache durumunu kontrol et
net start | findstr Apache

# Apache log'larını kontrol et
type C:\xampp\apache\logs\error.log
```

### 11. Güvenlik Kontrolleri

#### Ağ Güvenliği:

1. **Router Güvenliği**: Router şifresini değiştirin
2. **WPA3 Şifreleme**: Kullanıyorsanız WPA3 kullanın
3. **MAC Filtreleme**: Sadece izin verilen cihazlar
4. **Port Tarama**: Gereksiz portları kapatın

#### Uygulama Güvenliği:

1. **HTTPS Kullanımı**: SSL sertifikası kurun
2. **Güçlü Şifreler**: Veritabanı ve admin şifreleri
3. **Düzenli Güncellemeler**: Sistem güncellemeleri
4. **Log İzleme**: Şüpheli aktiviteleri izleyin

### 12. Erişim Adresleri

#### Kurulum Sonrası Erişim:

```
Yerel Erişim:
- http://kalefrit.local
- http://127.0.0.1

LAN Erişimi:
- http://192.168.1.100
- http://192.168.1.100:80

HTTPS (SSL kurulduysa):
- https://kalefrit.local
- https://192.168.1.100
```

#### Yönetim Panelleri:

```
phpMyAdmin: http://192.168.1.100/phpmyadmin
XAMPP Control: http://192.168.1.100/xampp
```

Bu rehber ile Kalefrit Barkod Sistemi'nizi LAN ortamında başarıyla yayınlayabilir ve diğer bilgisayarlardan erişim sağlayabilirsiniz.
