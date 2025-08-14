<?php

use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\KilnController;
use App\Http\Controllers\QuantityController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::post('/dashboard/export-kiln-performance', [App\Http\Controllers\DashboardController::class, 'exportKilnPerformance'])->name('dashboard.export-kiln-performance')->middleware('auth');
Route::get('/hakkinda', [App\Http\Controllers\AboutController::class, 'index'])->name('about');
Route::get('/kullanim-kilavuzu', [App\Http\Controllers\ManualController::class, 'index'])->name('manual');

// Health check routes (production monitoring)
Route::get('/health', [HealthController::class, 'check'])->name('health.check');
Route::get('/health/detailed', [HealthController::class, 'detailed'])->name('health.detailed');

Auth::routes();

Route::middleware('auth')
    ->group(function () {
        Route::get('/anasayfa', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

        Route::as('user.')->prefix('kullanici')->group(function () {
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/ekle', [UserController::class, 'create'])->name('create');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            Route::get('/{user}/duzenle', [UserController::class, 'edit'])->name('edit');
            Route::get('/{user}/yetki/duzenle', [UserController::class, 'permissionEdit'])->name('permission-edit');
            Route::put('/{user}/yetki/', [UserController::class, 'permissionSync'])->name('permission-sync');
        });

        Route::as('company.')->prefix('firma')->group(function () {
    Route::post('/', [CompanyController::class, 'store'])->name('store');
    Route::get('/', [CompanyController::class, 'index'])->name('index');
    Route::get('/ekle', [CompanyController::class, 'create'])->name('create');
    Route::put('/{firma}', [CompanyController::class, 'update'])->name('update');
    Route::get('/{firma}', [CompanyController::class, 'show'])->name('show');
    Route::delete('/{firma}', [CompanyController::class, 'destroy'])->name('destroy');
    Route::get('/{firma}/duzenle', [CompanyController::class, 'edit'])->name('edit');
    Route::get('/{firma}/analiz', [CompanyController::class, 'analysis'])->name('analysis');
    Route::get('/{firma}/rapor-indir', [CompanyController::class, 'downloadReport'])->name('download.report');
});

        Route::as('warehouse.')->prefix('depo')->group(function () {
            Route::post('/', [WarehouseController::class, 'store'])->name('store');
            Route::get('/', [WarehouseController::class, 'index'])->name('index');
            Route::get('/ekle', [WarehouseController::class, 'create'])->name('create');
            Route::put('/{depo}', [WarehouseController::class, 'update'])->name('update');
            Route::get('/{depo}', [WarehouseController::class, 'show'])->name('show');
            Route::delete('/{depo}', [WarehouseController::class, 'destroy'])->name('destroy');
            Route::get('/{depo}/duzenle', [WarehouseController::class, 'edit'])->name('edit');
            Route::get('/{depo}/stok-adetleri', [WarehouseController::class, 'stockQuantity'])->name('stock-quantity');
            Route::post('/{warehouse}/clear-cache', [WarehouseController::class, 'clearCache'])->name('clear-cache');
            Route::post('/{warehouse}/fix-data', [WarehouseController::class, 'fixData'])->name('fix-data');
        });

        Route::as('barcode.')->prefix('barkod')->group(function () {
            Route::post('/', [BarcodeController::class, 'store'])->name('store');
            Route::get('/', [BarcodeController::class, 'index'])->name('index');
            Route::get('/ekle', [BarcodeController::class, 'create'])->name('create');
            Route::get('/print', [BarcodeController::class, 'print'])->name('print');
            Route::put('/{barkod}', [BarcodeController::class, 'update'])->name('update');
            Route::get('/{barkod}', [BarcodeController::class, 'show'])->name('show');
            Route::delete('/{barkod}', [BarcodeController::class, 'destroy'])->name('destroy');
            Route::get('/{barkod}/duzenle', [BarcodeController::class, 'edit'])->name('edit');
            Route::get('/qr/scan', [BarcodeController::class, 'qrRead'])->name('qr-read');
            Route::get('/rapor/barcode/hareketler', [BarcodeController::class, 'historyIndex'])->name('historyIndex');
            Route::get('/{barkod}/barkod/hareketler', [BarcodeController::class, 'history'])->name('history');
            Route::get('/islemler/barkod/birlestirme', [BarcodeController::class, 'merge'])->name('merge');
            Route::post('/islemler/barkod/birlestirme', [BarcodeController::class, 'mergeStore'])->name('mergeStore');
            Route::get('/islemler/barkod/yazdirma', [BarcodeController::class, 'printPageLayout'])->name('printPage.layout');
            Route::get('/islemler/print-page-barcodes-ajax', [BarcodeController::class, 'printPage'])->name('printPage');
        });

        Route::as('stock.')->prefix('stok')->group(function () {
            Route::get('/export/excel', [StockController::class, 'downloadExcel'])->name('excel.download');
            Route::post('/', [StockController::class, 'store'])->name('store');
            Route::get('/', [StockController::class, 'index'])->name('index');
            Route::get('/ekle', [StockController::class, 'create'])->name('create');
            Route::put('/{stok}', [StockController::class, 'update'])->name('update');
            Route::get('/{stok}', [StockController::class, 'show'])->name('show');
            Route::get('/{stok}/pdf', [StockController::class, 'exportPdf'])->name('pdf');
            Route::get('/{stok}/excel', [StockController::class, 'exportExcel'])->name('excel');
            Route::get('/{stok}/print', [StockController::class, 'print'])->name('print');
            Route::delete('/{stok}', [StockController::class, 'destroy'])->name('destroy');
            Route::get('/{stok}/duzenle', [StockController::class, 'edit'])->name('edit');
        });

        Route::as('kiln.')->prefix('firin')->group(function () {
    Route::post('/', [KilnController::class, 'store'])->name('store');
    Route::get('/', [KilnController::class, 'index'])->name('index');
    Route::get('/ekle', [KilnController::class, 'create'])->name('create');
    Route::put('/{firin}', [KilnController::class, 'update'])->name('update');
    Route::get('/{firin}', [KilnController::class, 'show'])->name('show');
    Route::delete('/{firin}', [KilnController::class, 'destroy'])->name('destroy');
    Route::get('/{firin}/duzenle', [KilnController::class, 'edit'])->name('edit');
    Route::get('/{firin}/analiz', [KilnController::class, 'analysis'])->name('analysis');
    Route::get('/{firin}/rapor-indir', [KilnController::class, 'downloadReport'])->name('download.report');
});

        Route::as('quantity.')->prefix('adet')->group(function () {
            Route::post('/', [QuantityController::class, 'store'])->name('store');
            Route::get('/', [QuantityController::class, 'index'])->name('index');
            Route::get('/ekle', [QuantityController::class, 'create'])->name('create');
            Route::put('/{adet}', [QuantityController::class, 'update'])->name('update');
            Route::get('/{adet}', [QuantityController::class, 'show'])->name('show');
            Route::delete('/{adet}', [QuantityController::class, 'destroy'])->name('destroy');
            Route::get('/{adet}/duzenle', [QuantityController::class, 'edit'])->name('edit');
        });

        Route::as('settings.')->prefix('ayarlar')->group(function () {
            Route::get('/', [SettingsController::class, 'show'])->name('show');
            Route::put('/firin-sarj-numaralarini-sifirla', [SettingsController::class, 'resetKilnLoadNumber'])->name('resetKilnLoadNumber');
            Route::post('/tek-firin-sarj-sifirla', [SettingsController::class, 'resetSingleKilnLoadNumber'])->name('resetSingleKilnLoadNumber');
        });

        // Laboratuvar ekibi route'ları
        Route::as('laboratory.')->prefix('laboratuvar')->group(function () {
            Route::get('/', [App\Http\Controllers\LaboratoryController::class, 'dashboard'])->name('dashboard');
            Route::get('/barkodlar', [App\Http\Controllers\LaboratoryController::class, 'barcodeList'])->name('barcode-list');
            Route::post('/barkod-islem', [App\Http\Controllers\LaboratoryController::class, 'processBarcode'])->name('process-barcode');
            Route::get('/barkod-detay/{id}', [App\Http\Controllers\LaboratoryController::class, 'getBarcodeDetail'])->name('barcode-detail');
            Route::get('/toplu-islem', [App\Http\Controllers\LaboratoryController::class, 'bulkProcess'])->name('bulk-process');
            Route::post('/toplu-islem', [App\Http\Controllers\LaboratoryController::class, 'processBulk'])->name('process-bulk');
            Route::get('/rapor', [App\Http\Controllers\LaboratoryController::class, 'report'])->name('report');
Route::post('/barkod-durumlar', [App\Http\Controllers\LaboratoryController::class, 'getBarcodeStatuses'])->name('barcode-statuses');
        });

        // Dashboard Widget route'ları
        Route::as('widget.')->prefix('widget')->group(function () {
            Route::get('/all', [App\Http\Controllers\DashboardWidgetController::class, 'getMainWidgets'])->name('all');
            Route::post('/settings', [App\Http\Controllers\DashboardWidgetController::class, 'saveWidgetSettings'])->name('settings');
            Route::post('/refresh', [App\Http\Controllers\DashboardWidgetController::class, 'refreshWidget'])->name('refresh');
        });

        // AI/ML Dashboard
        Route::get('/ai-ml', function () {
            return view('ai-ml.dashboard');
        })->name('ai-ml.dashboard')->middleware(['auth', 'verified']);
});
