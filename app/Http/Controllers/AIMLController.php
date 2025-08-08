<?php

namespace App\Http\Controllers;

use App\Services\AIMLService;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AIMLController extends Controller
{
    protected $aiMLService;
    protected $cacheService;

    public function __construct(AIMLService $aiMLService, CacheService $cacheService)
    {
        $this->aiMLService = $aiMLService;
        $this->cacheService = $cacheService;
    }

    /**
     * Get production forecast
     */
    public function forecast(Request $request): JsonResponse
    {
        try {
            $days = $request->get('days', 30);
            $forecast = $this->aiMLService->forecastProduction($days);
            
            return response()->json($forecast);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Forecast data could not be retrieved',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get quality metrics
     */
    public function qualityMetrics(): JsonResponse
    {
        try {
            $metrics = $this->aiMLService->getQualityMetrics();
            
            return response()->json($metrics);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Quality metrics could not be retrieved',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get quality predictions
     */
    public function qualityPredictions(): JsonResponse
    {
        try {
            $predictions = $this->aiMLService->predictQualityMetrics();
            
            return response()->json($predictions);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Quality predictions could not be retrieved',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get anomalies
     */
    public function anomalies(): JsonResponse
    {
        try {
            $anomalies = $this->aiMLService->detectAnomalies();
            
            return response()->json($anomalies);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Anomalies could not be detected',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get warehouse optimization
     */
    public function warehouseOptimization(): JsonResponse
    {
        try {
            $optimization = $this->aiMLService->optimizeWarehouseAllocation();
            
            return response()->json($optimization);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Warehouse optimization could not be retrieved',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get maintenance predictions
     */
    public function maintenancePredictions(): JsonResponse
    {
        try {
            $predictions = $this->aiMLService->predictKilnMaintenance();
            
            return response()->json($predictions);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Maintenance predictions could not be retrieved',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer analysis
     */
    public function customerAnalysis(): JsonResponse
    {
        try {
            $analysis = $this->aiMLService->analyzeCustomerBehavior();
            
            return response()->json($analysis);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Customer analysis could not be retrieved',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer segments
     */
    public function customerSegments(): JsonResponse
    {
        try {
            $analysis = $this->aiMLService->analyzeCustomerBehavior();
            
            return response()->json($analysis['segmentation']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Customer segments could not be retrieved',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all AI/ML data for dashboard
     */
    public function dashboardData(): JsonResponse
    {
        try {
            $data = [
                'forecast' => $this->aiMLService->forecastProduction(),
                'quality_metrics' => $this->aiMLService->getQualityMetrics(),
                'quality_predictions' => $this->aiMLService->predictQualityMetrics(),
                'anomalies' => $this->aiMLService->detectAnomalies(),
                'warehouse_optimization' => $this->aiMLService->optimizeWarehouseAllocation(),
                'maintenance_predictions' => $this->aiMLService->predictKilnMaintenance(),
                'customer_analysis' => $this->aiMLService->analyzeCustomerBehavior(),
            ];
            
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Dashboard data could not be retrieved',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refresh all AI/ML data
     */
    public function refreshData(): JsonResponse
    {
        try {
            // Clear all caches
            $this->cacheService->clearAllCaches();
            
            // Warm up caches
            $this->cacheService->warmUpCaches();
            
            return response()->json([
                'message' => 'AI/ML data refreshed successfully',
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Data refresh failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system health for AI/ML services
     */
    public function systemHealth(): JsonResponse
    {
        try {
            $health = $this->cacheService->getSystemHealth();
            
            return response()->json([
                'health' => $health,
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'System health could not be retrieved',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
