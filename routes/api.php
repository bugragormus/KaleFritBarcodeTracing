<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIMLController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// AI/ML API Routes
Route::prefix('ai-ml')->group(function () {
    Route::get('/forecast', [AIMLController::class, 'forecast'])->middleware('auth:sanctum');
    Route::get('/quality-metrics', [AIMLController::class, 'qualityMetrics'])->middleware('auth:sanctum');
    Route::get('/quality-predictions', [AIMLController::class, 'qualityPredictions'])->middleware('auth:sanctum');
    Route::get('/anomalies', [AIMLController::class, 'anomalies'])->middleware('auth:sanctum');
    Route::get('/warehouse-optimization', [AIMLController::class, 'warehouseOptimization'])->middleware('auth:sanctum');
    Route::get('/maintenance-predictions', [AIMLController::class, 'maintenancePredictions'])->middleware('auth:sanctum');
    Route::get('/customer-analysis', [AIMLController::class, 'customerAnalysis'])->middleware('auth:sanctum');
    Route::get('/customer-segments', [AIMLController::class, 'customerSegments'])->middleware('auth:sanctum');
});
