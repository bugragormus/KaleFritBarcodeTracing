@echo off
echo ========================================
echo Kalefrit Sistem Sürüm Kontrolü
echo ========================================

echo.
echo 1. PHP Sürümü:
php -v

echo.
echo 2. MySQL Sürümü:
mysql --version

echo.
echo 3. Apache Sürümü:
httpd -v

echo.
echo 4. Composer Sürümü:
composer --version

echo.
echo 5. Node.js Sürümü (varsa):
node --version

echo.
echo 6. Laravel Sürümü (mevcut projede):
cd C:\xampp\htdocs\kalefrit
php artisan --version

echo.
echo ========================================
pause
