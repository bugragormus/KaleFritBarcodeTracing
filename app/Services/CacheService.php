<?php

namespace App\Services;

use App\Models\Barcode;
use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\Kiln;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CacheService
{
    // Cache keys
    const CACHE_KEYS = [
        'barcode_stats' => 'barcode_stats',
        'stock_stats' => 'stock_stats',
        'warehouse_stats' => 'warehouse_stats',
        'kiln_stats' => 'kiln_stats',
        'company_stats' => 'company_stats',
        'user_stats' => 'user_stats',
        'dashboard_data' => 'dashboard_data',
        'recent_activities' => 'recent_activities',
        'production_trend' => 'production_trend',
        'quality_metrics' => 'quality_metrics',
        'stock_alerts' => 'stock_alerts',
        'user_performance' => 'user_performance',
        'system_health' => 'system_health',
    ];

    // Cache durations (in seconds)
    const CACHE_DURATIONS = [
        'short' => 300,    // 5 minutes
        'medium' => 1800,  // 30 minutes
        'long' => 3600,    // 1 hour
        'daily' => 86400,  // 24 hours
    ];

    /**
     * Get barcode statistics with caching
     */
    public function getBarcodeStats()
    {
        return Cache::remember(self::CACHE_KEYS['barcode_stats'], self::CACHE_DURATIONS['medium'], function () {
            return [
                'total' => Barcode::count(),
                'waiting' => Barcode::where('status', Barcode::STATUS_WAITING)->count(),
                'rejected' => Barcode::where('status', Barcode::STATUS_REJECTED)->count(),
                'approved' => Barcode::whereIn('status', [
                    Barcode::STATUS_PRE_APPROVED,
                    Barcode::STATUS_SHIPMENT_APPROVED
                ])->count(),
                'delivered' => Barcode::where('status', Barcode::STATUS_DELIVERED)->count(),
                'today' => Barcode::whereDate('created_at', today())->count(),
                'this_month' => Barcode::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'status_distribution' => $this->getStatusDistribution(),
            ];
        });
    }

    /**
     * Get stock statistics with caching
     */
    public function getStockStats()
    {
        return Cache::remember(self::CACHE_KEYS['stock_stats'], self::CACHE_DURATIONS['medium'], function () {
            return [
                'total_stocks' => Stock::count(),
                'active_stocks' => Stock::whereHas('barcodes')->count(),
                'top_stocks' => $this->getTopStocks(),
                'stock_performance' => $this->getStockPerformance(),
            ];
        });
    }

    /**
     * Get warehouse statistics with caching
     */
    public function getWarehouseStats()
    {
        return Cache::remember(self::CACHE_KEYS['warehouse_stats'], self::CACHE_DURATIONS['medium'], function () {
            return [
                'total_warehouses' => Warehouse::count(),
                'warehouse_utilization' => $this->getWarehouseUtilization(),
                'warehouse_performance' => $this->getWarehousePerformance(),
            ];
        });
    }

    /**
     * Get kiln statistics with caching
     */
    public function getKilnStats()
    {
        return Cache::remember(self::CACHE_KEYS['kiln_stats'], self::CACHE_DURATIONS['medium'], function () {
            return [
                'total_kilns' => Kiln::count(),
                'active_kilns' => Kiln::whereHas('barcodes')->count(),
                'kiln_performance' => $this->getKilnPerformance(),
            ];
        });
    }

    /**
     * Get company statistics with caching
     */
    public function getCompanyStats()
    {
        return Cache::remember(self::CACHE_KEYS['company_stats'], self::CACHE_DURATIONS['medium'], function () {
            return [
                'total_companies' => Company::count(),
                'active_companies' => Company::whereHas('barcodes')->count(),
                'company_performance' => $this->getCompanyPerformance(),
            ];
        });
    }

    /**
     * Get user statistics with caching
     */
    public function getUserStats()
    {
        return Cache::remember(self::CACHE_KEYS['user_stats'], self::CACHE_DURATIONS['medium'], function () {
            return [
                'total_users' => User::count(),
                'active_users' => User::whereHas('barcodesCreated')->count(),
                'user_performance' => $this->getUserPerformance(),
            ];
        });
    }

    /**
     * Get dashboard data with caching
     */
    public function getDashboardData()
    {
        return Cache::remember(self::CACHE_KEYS['dashboard_data'], self::CACHE_DURATIONS['short'], function () {
            return [
                'barcode_stats' => $this->getBarcodeStats(),
                'stock_stats' => $this->getStockStats(),
                'warehouse_stats' => $this->getWarehouseStats(),
                'kiln_stats' => $this->getKilnStats(),
                'company_stats' => $this->getCompanyStats(),
                'user_stats' => $this->getUserStats(),
                'recent_activities' => $this->getRecentActivities(),
                'production_trend' => $this->getProductionTrend(),
                'quality_metrics' => $this->getQualityMetrics(),
                'stock_alerts' => $this->getStockAlerts(),
                'system_health' => $this->getSystemHealth(),
            ];
        });
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities()
    {
        return Cache::remember(self::CACHE_KEYS['recent_activities'], self::CACHE_DURATIONS['short'], function () {
            return Barcode::with(['stock', 'kiln', 'createdBy'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($barcode) {
                    return [
                        'id' => $barcode->id,
                        'action' => 'Barkod oluşturuldu',
                        'stock_name' => $barcode->stock->name ?? 'Bilinmiyor',
                        'kiln_name' => $barcode->kiln->name ?? 'Bilinmiyor',
                        'user_name' => $barcode->createdBy->name ?? 'Bilinmiyor',
                        'created_at' => $barcode->created_at->format('d.m.Y H:i'),
                        'status' => $barcode->getStatusNameAttribute(),
                    ];
                });
        });
    }

    /**
     * Get production trend
     */
    public function getProductionTrend()
    {
        return Cache::remember(self::CACHE_KEYS['production_trend'], self::CACHE_DURATIONS['medium'], function () {
            $days = 30;
            $trend = [];
            
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $count = Barcode::whereDate('created_at', $date)->count();
                
                $trend[] = [
                    'date' => $date->format('d.m.Y'),
                    'count' => $count,
                ];
            }
            
            return $trend;
        });
    }

    /**
     * Get quality metrics
     */
    public function getQualityMetrics()
    {
        return Cache::remember(self::CACHE_KEYS['quality_metrics'], self::CACHE_DURATIONS['medium'], function () {
            $total = Barcode::count();
            
            if ($total === 0) {
                return [
                    'approval_rate' => 0,
                    'rejection_rate' => 0,
                    'delivery_rate' => 0,
                ];
            }
            
            $approved = Barcode::whereIn('status', [
                Barcode::STATUS_PRE_APPROVED,
                Barcode::STATUS_SHIPMENT_APPROVED
            ])->count();
            
            $rejected = Barcode::where('status', Barcode::STATUS_REJECTED)->count();
            $delivered = Barcode::where('status', Barcode::STATUS_DELIVERED)->count();
            
            return [
                'approval_rate' => round(($approved / $total) * 100, 2),
                'rejection_rate' => round(($rejected / $total) * 100, 2),
                'delivery_rate' => round(($delivered / $total) * 100, 2),
            ];
        });
    }

    /**
     * Get stock alerts
     */
    public function getStockAlerts()
    {
        return Cache::remember(self::CACHE_KEYS['stock_alerts'], self::CACHE_DURATIONS['short'], function () {
            $alerts = [];
            
            // Low stock alerts
            $lowStockStocks = Stock::whereHas('barcodes', function ($query) {
                $query->where('status', Barcode::STATUS_WAITING);
            }, '<', 10)->get();
            
            foreach ($lowStockStocks as $stock) {
                $alerts[] = [
                    'type' => 'low_stock',
                    'message' => "{$stock->name} stokunda düşük miktar",
                    'stock_id' => $stock->id,
                    'severity' => 'warning',
                ];
            }
            
            // High rejection rate alerts
            $highRejectionStocks = Stock::whereHas('barcodes', function ($query) {
                $query->where('status', Barcode::STATUS_REJECTED);
            }, '>', 50)->get();
            
            foreach ($highRejectionStocks as $stock) {
                $alerts[] = [
                    'type' => 'high_rejection',
                    'message' => "{$stock->name} stokunda yüksek red oranı",
                    'stock_id' => $stock->id,
                    'severity' => 'danger',
                ];
            }
            
            return $alerts;
        });
    }

    /**
     * Get system health
     */
    public function getSystemHealth()
    {
        return Cache::remember(self::CACHE_KEYS['system_health'], self::CACHE_DURATIONS['short'], function () {
            $health = [
                'database' => 'healthy',
                'cache' => 'healthy',
                'storage' => 'healthy',
                'memory' => 'healthy',
            ];
            
            // Database health check
            try {
                DB::connection()->getPdo();
            } catch (\Exception $e) {
                $health['database'] = 'unhealthy';
            }
            
            // Cache health check
            try {
                Cache::put('health_check', 'ok', 60);
                if (Cache::get('health_check') !== 'ok') {
                    $health['cache'] = 'unhealthy';
                }
            } catch (\Exception $e) {
                $health['cache'] = 'unhealthy';
            }
            
            // Storage health check
            try {
                $testFile = storage_path('app/health_test.txt');
                file_put_contents($testFile, 'test');
                unlink($testFile);
            } catch (\Exception $e) {
                $health['storage'] = 'unhealthy';
            }
            
            // Memory usage check
            $memoryUsage = memory_get_usage(true);
            $memoryLimit = ini_get('memory_limit');
            
            if ($memoryUsage > 0.8 * $this->parseMemoryLimit($memoryLimit)) {
                $health['memory'] = 'warning';
            }
            
            return $health;
        });
    }

    /**
     * Get status distribution
     */
    private function getStatusDistribution()
    {
        return Barcode::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Get top stocks
     */
    private function getTopStocks()
    {
        return Stock::withCount('barcodes')
            ->orderBy('barcodes_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($stock) {
                return [
                    'id' => $stock->id,
                    'name' => $stock->name,
                    'code' => $stock->code,
                    'barcode_count' => $stock->barcodes_count,
                ];
            });
    }

    /**
     * Get stock performance
     */
    private function getStockPerformance()
    {
        return Stock::withCount(['barcodes as total_barcodes'])
            ->withCount(['barcodes as approved_barcodes' => function ($query) {
                $query->whereIn('status', [
                    Barcode::STATUS_PRE_APPROVED,
                    Barcode::STATUS_SHIPMENT_APPROVED
                ]);
            }])
            ->withCount(['barcodes as rejected_barcodes' => function ($query) {
                $query->where('status', Barcode::STATUS_REJECTED);
            }])
            ->get()
            ->map(function ($stock) {
                $approvalRate = $stock->total_barcodes > 0 
                    ? round(($stock->approved_barcodes / $stock->total_barcodes) * 100, 2)
                    : 0;
                
                return [
                    'id' => $stock->id,
                    'name' => $stock->name,
                    'total_barcodes' => $stock->total_barcodes,
                    'approved_barcodes' => $stock->approved_barcodes,
                    'rejected_barcodes' => $stock->rejected_barcodes,
                    'approval_rate' => $approvalRate,
                ];
            });
    }

    /**
     * Get warehouse utilization
     */
    private function getWarehouseUtilization()
    {
        return Warehouse::withCount('barcodes')
            ->get()
            ->map(function ($warehouse) {
                return [
                    'id' => $warehouse->id,
                    'name' => $warehouse->name,
                    'barcode_count' => $warehouse->barcodes_count,
                ];
            });
    }

    /**
     * Get warehouse performance
     */
    private function getWarehousePerformance()
    {
        return Warehouse::withCount(['barcodes as total_barcodes'])
            ->withCount(['barcodes as delivered_barcodes' => function ($query) {
                $query->where('status', Barcode::STATUS_DELIVERED);
            }])
            ->get()
            ->map(function ($warehouse) {
                $deliveryRate = $warehouse->total_barcodes > 0 
                    ? round(($warehouse->delivered_barcodes / $warehouse->total_barcodes) * 100, 2)
                    : 0;
                
                return [
                    'id' => $warehouse->id,
                    'name' => $warehouse->name,
                    'total_barcodes' => $warehouse->total_barcodes,
                    'delivered_barcodes' => $warehouse->delivered_barcodes,
                    'delivery_rate' => $deliveryRate,
                ];
            });
    }

    /**
     * Get kiln performance
     */
    private function getKilnPerformance()
    {
        return Kiln::withCount('barcodes')
            ->orderBy('barcodes_count', 'desc')
            ->get()
            ->map(function ($kiln) {
                return [
                    'id' => $kiln->id,
                    'name' => $kiln->name,
                    'barcode_count' => $kiln->barcodes_count,
                    'load_number' => $kiln->load_number,
                ];
            });
    }

    /**
     * Get company performance
     */
    private function getCompanyPerformance()
    {
        return Company::withCount(['barcodes as total_barcodes'])
            ->withCount(['barcodes as delivered_barcodes' => function ($query) {
                $query->where('status', Barcode::STATUS_DELIVERED);
            }])
            ->get()
            ->map(function ($company) {
                $deliveryRate = $company->total_barcodes > 0 
                    ? round(($company->delivered_barcodes / $company->total_barcodes) * 100, 2)
                    : 0;
                
                return [
                    'id' => $company->id,
                    'name' => $company->name,
                    'total_barcodes' => $company->total_barcodes,
                    'delivered_barcodes' => $company->delivered_barcodes,
                    'delivery_rate' => $deliveryRate,
                ];
            });
    }

    /**
     * Get user performance
     */
    private function getUserPerformance()
    {
        return User::withCount(['barcodesCreated as total_created'])
            ->orderBy('total_created', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'total_created' => $user->total_created,
                ];
            });
    }

    /**
     * Parse memory limit string to bytes
     */
    private function parseMemoryLimit($memoryLimit)
    {
        $unit = strtolower(substr($memoryLimit, -1));
        $value = (int) substr($memoryLimit, 0, -1);
        
        switch ($unit) {
            case 'k':
                return $value * 1024;
            case 'm':
                return $value * 1024 * 1024;
            case 'g':
                return $value * 1024 * 1024 * 1024;
            default:
                return $value;
        }
    }

    /**
     * Clear all caches
     */
    public function clearAllCaches()
    {
        foreach (self::CACHE_KEYS as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Clear specific cache
     */
    public function clearCache($key)
    {
        if (isset(self::CACHE_KEYS[$key])) {
            Cache::forget(self::CACHE_KEYS[$key]);
        }
    }

    /**
     * Warm up caches
     */
    public function warmUpCaches()
    {
        $this->getDashboardData();
        $this->getBarcodeStats();
        $this->getStockStats();
        $this->getWarehouseStats();
        $this->getKilnStats();
        $this->getCompanyStats();
        $this->getUserStats();
    }
}
