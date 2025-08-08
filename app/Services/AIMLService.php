<?php

namespace App\Services;

use App\Models\Barcode;
use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\Kiln;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AIMLService
{
    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Production forecasting using time series analysis
     */
    public function forecastProduction(int $days = 30): array
    {
        return Cache::remember("production_forecast_{$days}", 3600, function () use ($days) {
            // Get historical production data
            $historicalData = $this->getHistoricalProductionData($days * 2);
            
            // Calculate trend and seasonality
            $trend = $this->calculateTrend($historicalData);
            $seasonality = $this->calculateSeasonality($historicalData);
            
            // Generate forecast
            $forecast = [];
            for ($i = 1; $i <= $days; $i++) {
                $date = now()->addDays($i);
                $predictedValue = $this->predictValue($trend, $seasonality, $i);
                
                $forecast[] = [
                    'date' => $date->format('Y-m-d'),
                    'predicted_production' => round($predictedValue),
                    'confidence_interval' => [
                        'lower' => round($predictedValue * 0.85),
                        'upper' => round($predictedValue * 1.15)
                    ]
                ];
            }
            
            return [
                'forecast' => $forecast,
                'trend' => $trend,
                'accuracy' => $this->calculateForecastAccuracy($historicalData),
                'seasonality_pattern' => $seasonality
            ];
        });
    }

    /**
     * Quality prediction using machine learning
     */
    public function predictQualityMetrics(): array
    {
        return Cache::remember('quality_prediction', 1800, function () {
            // Get quality metrics for different stocks
            $stockQualityData = $this->getStockQualityData();
            
            $predictions = [];
            foreach ($stockQualityData as $stock) {
                $prediction = $this->predictStockQuality($stock);
                $predictions[] = [
                    'stock_id' => $stock['stock_id'],
                    'stock_name' => $stock['stock_name'],
                    'current_approval_rate' => $stock['approval_rate'],
                    'predicted_approval_rate' => $prediction['approval_rate'],
                    'risk_level' => $prediction['risk_level'],
                    'recommendations' => $prediction['recommendations']
                ];
            }
            
            return [
                'predictions' => $predictions,
                'overall_quality_trend' => $this->calculateOverallQualityTrend(),
                'risk_alerts' => $this->generateQualityRiskAlerts($predictions)
            ];
        });
    }

    /**
     * Anomaly detection for production data
     */
    public function detectAnomalies(): array
    {
        return Cache::remember('anomaly_detection', 900, function () {
            $anomalies = [];
            
            // Production volume anomalies
            $productionAnomalies = $this->detectProductionAnomalies();
            $anomalies['production'] = $productionAnomalies;
            
            // Quality anomalies
            $qualityAnomalies = $this->detectQualityAnomalies();
            $anomalies['quality'] = $qualityAnomalies;
            
            // User behavior anomalies
            $userAnomalies = $this->detectUserBehaviorAnomalies();
            $anomalies['user_behavior'] = $userAnomalies;
            
            // System performance anomalies
            $systemAnomalies = $this->detectSystemAnomalies();
            $anomalies['system'] = $systemAnomalies;
            
            return $anomalies;
        });
    }

    /**
     * Optimize warehouse allocation
     */
    public function optimizeWarehouseAllocation(): array
    {
        return Cache::remember('warehouse_optimization', 3600, function () {
            $warehouses = Warehouse::withCount('barcodes')->get();
            $optimization = [];
            
            foreach ($warehouses as $warehouse) {
                $utilization = $this->calculateWarehouseUtilization($warehouse);
                $recommendations = $this->generateWarehouseRecommendations($warehouse, $utilization);
                
                $optimization[] = [
                    'warehouse_id' => $warehouse->id,
                    'warehouse_name' => $warehouse->name,
                    'current_utilization' => $utilization['current'],
                    'optimal_utilization' => $utilization['optimal'],
                    'efficiency_score' => $utilization['efficiency_score'],
                    'recommendations' => $recommendations,
                    'predicted_demand' => $this->predictWarehouseDemand($warehouse)
                ];
            }
            
            return [
                'optimization_data' => $optimization,
                'overall_efficiency' => $this->calculateOverallEfficiency($optimization),
                'resource_allocation_suggestions' => $this->generateResourceAllocationSuggestions($optimization)
            ];
        });
    }

    /**
     * Predictive maintenance for kilns
     */
    public function predictKilnMaintenance(): array
    {
        return Cache::remember('kiln_maintenance_prediction', 7200, function () {
            $kilns = Kiln::withCount('barcodes')->get();
            $predictions = [];
            
            foreach ($kilns as $kiln) {
                $maintenanceData = $this->getKilnMaintenanceData($kiln);
                $prediction = $this->predictMaintenanceSchedule($kiln, $maintenanceData);
                
                $predictions[] = [
                    'kiln_id' => $kiln->id,
                    'kiln_name' => $kiln->name,
                    'current_health_score' => $prediction['health_score'],
                    'next_maintenance_date' => $prediction['next_maintenance'],
                    'maintenance_urgency' => $prediction['urgency'],
                    'recommended_actions' => $prediction['recommendations'],
                    'failure_probability' => $prediction['failure_probability']
                ];
            }
            
            return [
                'maintenance_predictions' => $predictions,
                'critical_alerts' => $this->generateMaintenanceAlerts($predictions),
                'maintenance_schedule' => $this->generateMaintenanceSchedule($predictions)
            ];
        });
    }

    /**
     * Customer behavior analysis
     */
    public function analyzeCustomerBehavior(): array
    {
        return Cache::remember('customer_behavior_analysis', 3600, function () {
            $companies = Company::withCount('barcodes')->get();
            $analysis = [];
            
            foreach ($companies as $company) {
                $behaviorData = $this->getCustomerBehaviorData($company);
                $analysis[] = [
                    'company_id' => $company->id,
                    'company_name' => $company->name,
                    'order_pattern' => $this->analyzeOrderPattern($behaviorData),
                    'preferred_stocks' => $this->analyzePreferredStocks($behaviorData),
                    'delivery_preferences' => $this->analyzeDeliveryPreferences($behaviorData),
                    'lifetime_value' => $this->calculateCustomerLifetimeValue($behaviorData),
                    'churn_risk' => $this->predictChurnRisk($behaviorData),
                    'recommendations' => $this->generateCustomerRecommendations($behaviorData)
                ];
            }
            
            return [
                'customer_analysis' => $analysis,
                'segmentation' => $this->segmentCustomers($analysis),
                'retention_strategies' => $this->generateRetentionStrategies($analysis)
            ];
        });
    }

    /**
     * Get historical production data
     */
    private function getHistoricalProductionData(int $days): array
    {
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Barcode::whereDate('created_at', $date)->count();
            
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'production' => $count,
                'day_of_week' => $date->dayOfWeek
            ];
        }
        
        return $data;
    }

    /**
     * Calculate trend using linear regression
     */
    private function calculateTrend(array $data): array
    {
        $n = count($data);
        $sumX = 0;
        $sumY = 0;
        $sumXY = 0;
        $sumX2 = 0;
        
        foreach ($data as $i => $point) {
            $x = $i;
            $y = $point['production'];
            
            $sumX += $x;
            $sumY += $y;
            $sumXY += $x * $y;
            $sumX2 += $x * $x;
        }
        
        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        $intercept = ($sumY - $slope * $sumX) / $n;
        
        return [
            'slope' => $slope,
            'intercept' => $intercept,
            'direction' => $slope > 0 ? 'increasing' : ($slope < 0 ? 'decreasing' : 'stable')
        ];
    }

    /**
     * Calculate seasonality patterns
     */
    private function calculateSeasonality(array $data): array
    {
        $seasonality = [];
        
        // Group by day of week
        for ($day = 0; $day < 7; $day++) {
            $dayData = array_filter($data, function ($point) use ($day) {
                return $point['day_of_week'] == $day;
            });
            
            $avgProduction = array_sum(array_column($dayData, 'production')) / count($dayData);
            $seasonality[$day] = $avgProduction;
        }
        
        return $seasonality;
    }

    /**
     * Predict value using trend and seasonality
     */
    private function predictValue(array $trend, array $seasonality, int $daysAhead): float
    {
        $trendValue = $trend['slope'] * $daysAhead + $trend['intercept'];
        $dayOfWeek = (now()->addDays($daysAhead)->dayOfWeek);
        $seasonalFactor = $seasonality[$dayOfWeek] ?? 1;
        
        return $trendValue * $seasonalFactor;
    }

    /**
     * Calculate forecast accuracy
     */
    private function calculateForecastAccuracy(array $historicalData): float
    {
        // Simple accuracy calculation based on recent data
        $recentData = array_slice($historicalData, -7);
        $totalVariation = 0;
        
        foreach ($recentData as $i => $point) {
            if ($i > 0) {
                $variation = abs($point['production'] - $recentData[$i-1]['production']);
                $totalVariation += $variation;
            }
        }
        
        $avgVariation = $totalVariation / (count($recentData) - 1);
        $avgProduction = array_sum(array_column($recentData, 'production')) / count($recentData);
        
        return $avgProduction > 0 ? (1 - ($avgVariation / $avgProduction)) * 100 : 0;
    }

    /**
     * Get stock quality data
     */
    private function getStockQualityData(): array
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
                    ? ($stock->approved_barcodes / $stock->total_barcodes) * 100
                    : 0;
                
                return [
                    'stock_id' => $stock->id,
                    'stock_name' => $stock->name,
                    'total_barcodes' => $stock->total_barcodes,
                    'approved_barcodes' => $stock->approved_barcodes,
                    'rejected_barcodes' => $stock->rejected_barcodes,
                    'approval_rate' => $approvalRate,
                    'recent_trend' => $this->getStockQualityTrend($stock)
                ];
            })
            ->toArray();
    }

    /**
     * Predict stock quality
     */
    private function predictStockQuality(array $stock): array
    {
        $currentRate = $stock['approval_rate'];
        $trend = $stock['recent_trend'];
        
        // Simple prediction based on trend
        $predictedRate = $currentRate + ($trend * 5); // 5% adjustment based on trend
        
        // Determine risk level
        $riskLevel = 'low';
        if ($predictedRate < 70) {
            $riskLevel = 'high';
        } elseif ($predictedRate < 85) {
            $riskLevel = 'medium';
        }
        
        // Generate recommendations
        $recommendations = [];
        if ($predictedRate < 85) {
            $recommendations[] = 'Kalite kontrol süreçlerini gözden geçirin';
            $recommendations[] = 'Üretim parametrelerini optimize edin';
        }
        if ($trend < 0) {
            $recommendations[] = 'Düşüş trendini analiz edin';
        }
        
        return [
            'approval_rate' => round($predictedRate, 2),
            'risk_level' => $riskLevel,
            'recommendations' => $recommendations
        ];
    }

    /**
     * Get stock quality trend
     */
    private function getStockQualityTrend($stock): float
    {
        $recentBarcodes = Barcode::where('stock_id', $stock->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at')
            ->get();
        
        if ($recentBarcodes->count() < 10) {
            return 0;
        }
        
        $firstHalf = $recentBarcodes->take($recentBarcodes->count() / 2);
        $secondHalf = $recentBarcodes->slice($recentBarcodes->count() / 2);
        
        $firstRate = $firstHalf->whereIn('status', [
            Barcode::STATUS_PRE_APPROVED,
            Barcode::STATUS_SHIPMENT_APPROVED
        ])->count() / $firstHalf->count() * 100;
        
        $secondRate = $secondHalf->whereIn('status', [
            Barcode::STATUS_PRE_APPROVED,
            Barcode::STATUS_SHIPMENT_APPROVED
        ])->count() / $secondHalf->count() * 100;
        
        return $secondRate - $firstRate;
    }

    /**
     * Detect production anomalies
     */
    private function detectProductionAnomalies(): array
    {
        $anomalies = [];
        
        // Get daily production for last 30 days
        $dailyProduction = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Barcode::whereDate('created_at', $date)->count();
            $dailyProduction[] = $count;
        }
        
        // Calculate mean and standard deviation
        $mean = array_sum($dailyProduction) / count($dailyProduction);
        $variance = array_sum(array_map(function ($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $dailyProduction)) / count($dailyProduction);
        $stdDev = sqrt($variance);
        
        // Detect anomalies (values outside 2 standard deviations)
        foreach ($dailyProduction as $i => $production) {
            if (abs($production - $mean) > 2 * $stdDev) {
                $anomalies[] = [
                    'date' => now()->subDays(29 - $i)->format('Y-m-d'),
                    'production' => $production,
                    'expected_range' => [
                        'min' => round($mean - 2 * $stdDev),
                        'max' => round($mean + 2 * $stdDev)
                    ],
                    'deviation' => round(($production - $mean) / $stdDev, 2)
                ];
            }
        }
        
        return $anomalies;
    }

    /**
     * Detect quality anomalies
     */
    private function detectQualityAnomalies(): array
    {
        $anomalies = [];
        
        // Check for sudden drops in approval rates
        $stocks = Stock::withCount(['barcodes as total_barcodes'])
            ->withCount(['barcodes as approved_barcodes' => function ($query) {
                $query->whereIn('status', [
                    Barcode::STATUS_PRE_APPROVED,
                    Barcode::STATUS_SHIPMENT_APPROVED
                ])->where('created_at', '>=', now()->subDays(7));
            }])
            ->get();
        
        foreach ($stocks as $stock) {
            if ($stock->total_barcodes > 0) {
                $recentRate = ($stock->approved_barcodes / $stock->total_barcodes) * 100;
                
                // Get historical rate
                $historicalBarcodes = Barcode::where('stock_id', $stock->id)
                    ->where('created_at', '>=', now()->subDays(30))
                    ->where('created_at', '<', now()->subDays(7))
                    ->get();
                
                if ($historicalBarcodes->count() > 0) {
                    $historicalRate = $historicalBarcodes->whereIn('status', [
                        Barcode::STATUS_PRE_APPROVED,
                        Barcode::STATUS_SHIPMENT_APPROVED
                    ])->count() / $historicalBarcodes->count() * 100;
                    
                    // If recent rate is significantly lower than historical
                    if ($recentRate < $historicalRate - 20) {
                        $anomalies[] = [
                            'stock_id' => $stock->id,
                            'stock_name' => $stock->name,
                            'recent_approval_rate' => round($recentRate, 2),
                            'historical_approval_rate' => round($historicalRate, 2),
                            'drop_percentage' => round($historicalRate - $recentRate, 2)
                        ];
                    }
                }
            }
        }
        
        return $anomalies;
    }

    /**
     * Detect user behavior anomalies
     */
    private function detectUserBehaviorAnomalies(): array
    {
        $anomalies = [];
        
        // Check for unusual user activity patterns
        $users = User::withCount(['barcodesCreated as today_barcodes' => function ($query) {
            $query->whereDate('created_at', today());
        }])
        ->withCount(['barcodesCreated as weekly_barcodes' => function ($query) {
            $query->where('created_at', '>=', now()->subWeek());
        }])
        ->get();
        
        foreach ($users as $user) {
            $avgDaily = $user->weekly_barcodes / 7;
            
            // If today's activity is significantly higher than average
            if ($user->today_barcodes > $avgDaily * 3) {
                $anomalies[] = [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'today_activity' => $user->today_barcodes,
                    'average_daily_activity' => round($avgDaily, 2),
                    'increase_factor' => round($user->today_barcodes / $avgDaily, 2),
                    'type' => 'high_activity'
                ];
            }
            
            // If today's activity is significantly lower than average
            if ($user->today_barcodes < $avgDaily * 0.3 && $avgDaily > 0) {
                $anomalies[] = [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'today_activity' => $user->today_barcodes,
                    'average_daily_activity' => round($avgDaily, 2),
                    'decrease_factor' => round($user->today_barcodes / $avgDaily, 2),
                    'type' => 'low_activity'
                ];
            }
        }
        
        return $anomalies;
    }

    /**
     * Detect system anomalies
     */
    private function detectSystemAnomalies(): array
    {
        $anomalies = [];
        
        // Check for system performance issues
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');
        $memoryPercentage = ($memoryUsage / $this->parseMemoryLimit($memoryLimit)) * 100;
        
        if ($memoryPercentage > 80) {
            $anomalies[] = [
                'type' => 'high_memory_usage',
                'current_usage' => $memoryPercentage,
                'threshold' => 80,
                'severity' => 'warning'
            ];
        }
        
        // Check for slow queries
        $slowQueries = DB::select("
            SELECT 
                COUNT(*) as slow_query_count,
                AVG(duration) as avg_duration
            FROM information_schema.processlist 
            WHERE command = 'Query' 
            AND time > 5
        ");
        
        if ($slowQueries[0]->slow_query_count > 0) {
            $anomalies[] = [
                'type' => 'slow_queries',
                'count' => $slowQueries[0]->slow_query_count,
                'average_duration' => round($slowQueries[0]->avg_duration, 2),
                'severity' => 'warning'
            ];
        }
        
        return $anomalies;
    }

    /**
     * Calculate warehouse utilization
     */
    private function calculateWarehouseUtilization($warehouse): array
    {
        $totalBarcodes = $warehouse->barcodes_count;
        $optimalCapacity = 1000; // Assume optimal capacity
        
        $currentUtilization = ($totalBarcodes / $optimalCapacity) * 100;
        $optimalUtilization = min(85, $currentUtilization); // 85% is optimal
        
        $efficiencyScore = 100 - abs($currentUtilization - $optimalUtilization);
        
        return [
            'current' => round($currentUtilization, 2),
            'optimal' => $optimalUtilization,
            'efficiency_score' => round($efficiencyScore, 2)
        ];
    }

    /**
     * Generate warehouse recommendations
     */
    private function generateWarehouseRecommendations($warehouse, array $utilization): array
    {
        $recommendations = [];
        
        if ($utilization['current'] > 90) {
            $recommendations[] = 'Depo kapasitesi kritik seviyede. Yeni depo alanı gerekli.';
        } elseif ($utilization['current'] > 75) {
            $recommendations[] = 'Depo kapasitesi yüksek. Kapasite artırımı düşünülebilir.';
        } elseif ($utilization['current'] < 30) {
            $recommendations[] = 'Depo kapasitesi düşük kullanılıyor. Optimizasyon gerekli.';
        }
        
        if ($utilization['efficiency_score'] < 70) {
            $recommendations[] = 'Depo verimliliği düşük. Süreç iyileştirmesi gerekli.';
        }
        
        return $recommendations;
    }

    /**
     * Predict warehouse demand
     */
    private function predictWarehouseDemand($warehouse): array
    {
        // Simple demand prediction based on historical data
        $recentBarcodes = Barcode::where('warehouse_id', $warehouse->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();
        
        $dailyAverage = $recentBarcodes / 30;
        $weeklyPrediction = $dailyAverage * 7;
        $monthlyPrediction = $dailyAverage * 30;
        
        return [
            'daily_average' => round($dailyAverage, 2),
            'weekly_prediction' => round($weeklyPrediction, 2),
            'monthly_prediction' => round($monthlyPrediction, 2)
        ];
    }

    /**
     * Parse memory limit string to bytes
     */
    private function parseMemoryLimit($memoryLimit): int
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
     * Get kiln maintenance data
     */
    private function getKilnMaintenanceData($kiln): array
    {
        // Simulate maintenance data
        $lastMaintenance = now()->subDays(rand(30, 180));
        $maintenanceFrequency = rand(60, 120); // days
        
        return [
            'last_maintenance' => $lastMaintenance,
            'maintenance_frequency' => $maintenanceFrequency,
            'total_operations' => $kiln->barcodes_count,
            'error_count' => rand(0, 5)
        ];
    }

    /**
     * Predict maintenance schedule
     */
    private function predictMaintenanceSchedule($kiln, array $maintenanceData): array
    {
        $daysSinceLastMaintenance = now()->diffInDays($maintenanceData['last_maintenance']);
        $healthScore = max(0, 100 - ($daysSinceLastMaintenance / $maintenanceData['maintenance_frequency']) * 100);
        
        $nextMaintenance = $maintenanceData['last_maintenance']->addDays($maintenanceData['maintenance_frequency']);
        $urgency = 'low';
        
        if ($healthScore < 30) {
            $urgency = 'critical';
        } elseif ($healthScore < 50) {
            $urgency = 'high';
        } elseif ($healthScore < 70) {
            $urgency = 'medium';
        }
        
        $recommendations = [];
        if ($healthScore < 50) {
            $recommendations[] = 'Acil bakım gerekli';
        } elseif ($healthScore < 70) {
            $recommendations[] = 'Planlı bakım yapılmalı';
        }
        
        $failureProbability = max(0, (100 - $healthScore) / 100);
        
        return [
            'health_score' => round($healthScore, 2),
            'next_maintenance' => $nextMaintenance->format('Y-m-d'),
            'urgency' => $urgency,
            'recommendations' => $recommendations,
            'failure_probability' => round($failureProbability * 100, 2)
        ];
    }

    /**
     * Generate maintenance alerts
     */
    private function generateMaintenanceAlerts(array $predictions): array
    {
        return array_filter($predictions, function ($prediction) {
            return $prediction['maintenance_urgency'] === 'critical' || 
                   $prediction['maintenance_urgency'] === 'high';
        });
    }

    /**
     * Generate maintenance schedule
     */
    private function generateMaintenanceSchedule(array $predictions): array
    {
        usort($predictions, function ($a, $b) {
            return strtotime($a['next_maintenance_date']) - strtotime($b['next_maintenance_date']);
        });
        
        return $predictions;
    }

    /**
     * Get customer behavior data
     */
    private function getCustomerBehaviorData($company): array
    {
        $barcodes = Barcode::where('company_id', $company->id)
            ->with(['stock', 'warehouse'])
            ->orderBy('created_at')
            ->get();
        
        return [
            'total_orders' => $barcodes->count(),
            'total_volume' => $barcodes->sum('quantity_id'),
            'first_order' => $barcodes->first()?->created_at,
            'last_order' => $barcodes->last()?->created_at,
            'preferred_stocks' => $barcodes->groupBy('stock_id')->map->count(),
            'preferred_warehouses' => $barcodes->groupBy('warehouse_id')->map->count(),
            'order_frequency' => $this->calculateOrderFrequency($barcodes)
        ];
    }

    /**
     * Calculate order frequency
     */
    private function calculateOrderFrequency($barcodes): float
    {
        if ($barcodes->count() < 2) {
            return 0;
        }
        
        $firstOrder = $barcodes->first()->created_at;
        $lastOrder = $barcodes->last()->created_at;
        $totalDays = $firstOrder->diffInDays($lastOrder);
        
        return $totalDays > 0 ? $barcodes->count() / $totalDays : 0;
    }

    /**
     * Analyze order pattern
     */
    private function analyzeOrderPattern(array $behaviorData): array
    {
        $frequency = $behaviorData['order_frequency'];
        
        if ($frequency > 0.1) {
            $pattern = 'high_frequency';
            $description = 'Yüksek sıklıkta sipariş';
        } elseif ($frequency > 0.05) {
            $pattern = 'medium_frequency';
            $description = 'Orta sıklıkta sipariş';
        } else {
            $pattern = 'low_frequency';
            $description = 'Düşük sıklıkta sipariş';
        }
        
        return [
            'pattern' => $pattern,
            'description' => $description,
            'frequency' => round($frequency, 3)
        ];
    }

    /**
     * Analyze preferred stocks
     */
    private function analyzePreferredStocks(array $behaviorData): array
    {
        $preferredStocks = $behaviorData['preferred_stocks']->sortDesc()->take(3);
        
        return $preferredStocks->map(function ($count, $stockId) {
            $stock = Stock::find($stockId);
            return [
                'stock_id' => $stockId,
                'stock_name' => $stock?->name ?? 'Bilinmiyor',
                'order_count' => $count
            ];
        })->values()->toArray();
    }

    /**
     * Analyze delivery preferences
     */
    private function analyzeDeliveryPreferences(array $behaviorData): array
    {
        $preferredWarehouses = $behaviorData['preferred_warehouses']->sortDesc()->take(3);
        
        return $preferredWarehouses->map(function ($count, $warehouseId) {
            $warehouse = Warehouse::find($warehouseId);
            return [
                'warehouse_id' => $warehouseId,
                'warehouse_name' => $warehouse?->name ?? 'Bilinmiyor',
                'order_count' => $count
            ];
        })->values()->toArray();
    }

    /**
     * Calculate customer lifetime value
     */
    private function calculateCustomerLifetimeValue(array $behaviorData): float
    {
        $totalVolume = $behaviorData['total_volume'];
        $orderCount = $behaviorData['total_orders'];
        
        // Simple CLV calculation (can be enhanced with actual pricing data)
        $avgOrderValue = $orderCount > 0 ? $totalVolume / $orderCount : 0;
        $lifetimeValue = $avgOrderValue * $orderCount;
        
        return round($lifetimeValue, 2);
    }

    /**
     * Predict churn risk
     */
    private function predictChurnRisk(array $behaviorData): string
    {
        $lastOrder = $behaviorData['last_order'];
        $daysSinceLastOrder = $lastOrder ? now()->diffInDays($lastOrder) : 0;
        
        if ($daysSinceLastOrder > 90) {
            return 'high';
        } elseif ($daysSinceLastOrder > 60) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    /**
     * Generate customer recommendations
     */
    private function generateCustomerRecommendations(array $behaviorData): array
    {
        $recommendations = [];
        $churnRisk = $this->predictChurnRisk($behaviorData);
        
        if ($churnRisk === 'high') {
            $recommendations[] = 'Müşteri kaybı riski yüksek. Özel teklifler sunun.';
        }
        
        if ($behaviorData['order_frequency'] < 0.05) {
            $recommendations[] = 'Sipariş sıklığını artırmak için promosyonlar önerin.';
        }
        
        return $recommendations;
    }

    /**
     * Segment customers
     */
    private function segmentCustomers(array $analysis): array
    {
        $segments = [
            'high_value' => [],
            'medium_value' => [],
            'low_value' => [],
            'at_risk' => []
        ];
        
        foreach ($analysis as $customer) {
            $lifetimeValue = $customer['lifetime_value'];
            $churnRisk = $customer['churn_risk'];
            
            if ($churnRisk === 'high') {
                $segments['at_risk'][] = $customer;
            } elseif ($lifetimeValue > 1000) {
                $segments['high_value'][] = $customer;
            } elseif ($lifetimeValue > 500) {
                $segments['medium_value'][] = $customer;
            } else {
                $segments['low_value'][] = $customer;
            }
        }
        
        return $segments;
    }

    /**
     * Generate retention strategies
     */
    private function generateRetentionStrategies(array $analysis): array
    {
        $strategies = [];
        
        $atRiskCustomers = array_filter($analysis, function ($customer) {
            return $customer['churn_risk'] === 'high';
        });
        
        if (count($atRiskCustomers) > 0) {
            $strategies[] = [
                'type' => 'retention_campaign',
                'target' => 'at_risk_customers',
                'count' => count($atRiskCustomers),
                'actions' => [
                    'Kişiselleştirilmiş teklifler',
                    'Müşteri memnuniyeti anketi',
                    'Özel destek hizmeti'
                ]
            ];
        }
        
        return $strategies;
    }

    /**
     * Calculate overall quality trend
     */
    private function calculateOverallQualityTrend(): array
    {
        $recentQuality = Barcode::where('created_at', '>=', now()->subDays(7))
            ->whereIn('status', [
                Barcode::STATUS_PRE_APPROVED,
                Barcode::STATUS_SHIPMENT_APPROVED
            ])->count() / Barcode::where('created_at', '>=', now()->subDays(7))->count() * 100;
        
        $previousQuality = Barcode::where('created_at', '>=', now()->subDays(14))
            ->where('created_at', '<', now()->subDays(7))
            ->whereIn('status', [
                Barcode::STATUS_PRE_APPROVED,
                Barcode::STATUS_SHIPMENT_APPROVED
            ])->count() / Barcode::where('created_at', '>=', now()->subDays(14))
            ->where('created_at', '<', now()->subDays(7))->count() * 100;
        
        $trend = $recentQuality - $previousQuality;
        
        return [
            'current_rate' => round($recentQuality, 2),
            'previous_rate' => round($previousQuality, 2),
            'trend' => round($trend, 2),
            'direction' => $trend > 0 ? 'improving' : ($trend < 0 ? 'declining' : 'stable')
        ];
    }

    /**
     * Generate quality risk alerts
     */
    private function generateQualityRiskAlerts(array $predictions): array
    {
        return array_filter($predictions, function ($prediction) {
            return $prediction['risk_level'] === 'high';
        });
    }

    /**
     * Calculate overall efficiency
     */
    private function calculateOverallEfficiency(array $optimization): float
    {
        if (empty($optimization)) {
            return 0;
        }
        
        $totalEfficiency = array_sum(array_column($optimization, 'efficiency_score'));
        return round($totalEfficiency / count($optimization), 2);
    }

    /**
     * Generate resource allocation suggestions
     */
    private function generateResourceAllocationSuggestions(array $optimization): array
    {
        $suggestions = [];
        
        $lowEfficiencyWarehouses = array_filter($optimization, function ($warehouse) {
            return $warehouse['efficiency_score'] < 70;
        });
        
        if (!empty($lowEfficiencyWarehouses)) {
            $suggestions[] = [
                'type' => 'efficiency_improvement',
                'target' => 'low_efficiency_warehouses',
                'count' => count($lowEfficiencyWarehouses),
                'actions' => [
                    'Süreç optimizasyonu',
                    'Personel eğitimi',
                    'Teknoloji yatırımı'
                ]
            ];
        }
        
        return $suggestions;
    }
}
