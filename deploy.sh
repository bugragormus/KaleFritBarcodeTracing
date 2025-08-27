#!/bin/bash

# Kalefrit Production Deployment Script
echo "🚀 Kalefrit Production Deployment Başlatılıyor..."

# 1. Composer bağımlılıklarını yükle
echo "📦 Composer bağımlılıkları yükleniyor..."
composer install --no-dev --optimize-autoloader

# 2. NPM bağımlılıklarını yükle ve build et
echo "📦 NPM bağımlılıkları yükleniyor..."
npm ci --production
npm run production

# 3. Environment dosyasını kopyala
if [ ! -f .env ]; then
    echo "⚙️ .env dosyası oluşturuluyor..."
    cp .env.example .env
fi

# 4. Application key oluştur
echo "🔑 Application key oluşturuluyor..."
php artisan key:generate

# 5. Cache'leri temizle ve yeniden oluştur
echo "🗑️ Cache'ler temizleniyor..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 6. Cache'leri optimize et
echo "⚡ Cache'ler optimize ediliyor..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Database migration'ları çalıştır
echo "🗄️ Database migration'ları çalıştırılıyor..."
php artisan migrate --force

# 8. Storage link oluştur
echo "🔗 Storage link oluşturuluyor..."
php artisan storage:link

# 9. Queue worker'ı başlat (background)
echo "🔄 Queue worker başlatılıyor..."
php artisan queue:work --daemon &

# 10. Cron job'ları ayarla
echo "⏰ Cron job'ları ayarlanıyor..."
(crontab -l 2>/dev/null; echo "* * * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1") | crontab -

# 11. Dosya izinlerini ayarla
echo "🔐 Dosya izinleri ayarlanıyor..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Deployment tamamlandı!"
echo "🌐 Uygulama https://your-domain.com adresinde çalışıyor" 