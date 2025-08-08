# 🔄 Kalefrit Yumuşak Geçiş Planı

## 📋 Adım Adım Entegrasyon

### Faz 1: Hazırlık (1-2 gün)

#### 1.1 Sistem Analizi

```bash
# Mevcut sistemin sürümlerini kontrol edin
version_check.bat

# Veritabanı yapısını analiz edin
mysqldump -u root -p --no-data kalefrit_db > schema_old.sql
```

#### 1.2 Yeni Sistem Kurulumu

```bash
# Yeni klasör oluşturun
mkdir C:\xampp\htdocs\kalefrit_new
cd C:\xampp\htdocs\kalefrit_new

# Yeni projeyi kopyalayın
xcopy "C:\Users\[KullanıcıAdı]\Desktop\kalefrit\*" "C:\xampp\htdocs\kalefrit_new\" /E /I /H
```

#### 1.3 Sürüm Uyumluluğu Kontrolü

-   **PHP**: Mevcut sistem PHP 7.4+ kullanıyorsa sorun yok
-   **MySQL**: Mevcut sistem MySQL 5.7+ kullanıyorsa sorun yok
-   **Laravel**: Yeni uygulama Laravel 8+ kullanıyorsa uyumlu

### Faz 2: Paralel Test (3-5 gün)

#### 2.1 Yeni Sistem Kurulumu

```bash
cd C:\xampp\htdocs\kalefrit_new

# Composer bağımlılıkları
composer install --no-dev --optimize-autoloader

# Environment dosyası
copy .env.example .env
```

#### 2.2 Veritabanı Yapılandırması

```env
# .env dosyasında
DB_DATABASE=kalefrit_db_new
DB_USERNAME=kalefrit_user_new
DB_PASSWORD=secure_password_new
```

#### 2.3 Test Ortamı

```apache
# C:\xampp\apache\conf\extra\httpd-vhosts.conf
<VirtualHost *:80>
    ServerName kalefrit-test.local
    DocumentRoot "C:/xampp/htdocs/kalefrit_new/public"

    <Directory "C:/xampp/htdocs/kalefrit_new/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Faz 3: Veri Migrasyonu (1-2 gün)

#### 3.1 Veri Yedekleme

```bash
# Mevcut veritabanının tam yedeği
mysqldump -u root -p kalefrit_db > kalefrit_db_backup_$(date +%Y%m%d).sql
```

#### 3.2 Veri Transferi

```bash
# Yeni veritabanına veri aktarımı
mysql -u root -p kalefrit_db_new < kalefrit_db_backup_$(date +%Y%m%d).sql

# Laravel migration'ları çalıştırın
cd C:\xampp\htdocs\kalefrit_new
php artisan migrate
```

### Faz 4: Canlı Geçiş (1 gün)

#### 4.1 Son Testler

-   [ ] Tüm fonksiyonlar çalışıyor
-   [ ] Veri bütünlüğü korunuyor
-   [ ] Performans testleri geçiyor

#### 4.2 Geçiş Stratejisi

```bash
# 1. Mevcut sistemi durdurun (bakım modu)
echo "Sistem bakımda..." > C:\xampp\htdocs\kalefrit\public\maintenance.html

# 2. Yeni sistemi aktif edin
ren C:\xampp\htdocs\kalefrit kalefrit_old
ren C:\xampp\htdocs\kalefrit_new kalefrit

# 3. Veritabanı bağlantısını güncelleyin
# .env dosyasında eski veritabanı adını kullanın

# 4. Cache'i temizleyin
php artisan config:clear
php artisan cache:clear
```

### Faz 5: İzleme ve Optimizasyon (Sürekli)

#### 5.1 Performans İzleme

-   Log dosyalarını kontrol edin
-   Veritabanı performansını izleyin
-   Kullanıcı geri bildirimlerini toplayın

#### 5.2 Geri Alma Planı

```bash
# Eğer sorun çıkarsa hızlı geri alma
ren C:\xampp\htdocs\kalefrit kalefrit_problem
ren C:\xampp\htdocs\kalefrit_old kalefrit
```

## ⚠️ Önemli Notlar

1. **Yedekleme**: Her adımda yedek alın
2. **Test**: Her değişikliği test edin
3. **Dokümantasyon**: Tüm değişiklikleri kaydedin
4. **İletişim**: Kullanıcıları bilgilendirin
5. **Zamanlama**: Düşük trafik saatlerinde geçiş yapın

## 🔧 Sürüm Uyumluluğu Kontrol Listesi

-   [ ] PHP 7.4+ uyumluluğu
-   [ ] MySQL 5.7+ uyumluluğu
-   [ ] Laravel sürüm uyumluluğu
-   [ ] Composer paket uyumluluğu
-   [ ] Apache modül uyumluluğu
