<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Stock;
use App\Models\User;
use App\Services\StockCalculationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardWidgetController extends Controller
{
    protected $stockCalculationService;

    public function __construct(StockCalculationService $stockCalculationService)
    {
        $this->middleware('auth');
        $this->stockCalculationService = $stockCalculationService;
    }

    /**
     * Ana dashboard widget'ları
     */
    public function getMainWidgets()
    {
        // KPI verilerini hesapla
        $kpiData = $this->getKPIData();
        
        return response()->json([
            'success' => true,
            'data' => $kpiData,
            'widgets' => [
                'production_trend' => $this->getProductionTrend(),
                'quality_metrics' => $this->getQualityMetrics(),
                'recent_activities' => $this->getRecentActivities(),
                'stock_alerts' => $this->getStockAlerts(),
                'user_performance' => $this->getUserPerformance(),
                'system_health' => $this->getSystemHealth(),
                'quick_actions' => $this->getQuickActions()
            ]
        ]);
    }

    /**
     * KPI verilerini hesapla
     */
    private function getKPIData()
    {
        // Toplam barkod sayısı
        $totalBarcodes = Barcode::count();
        
        // Bugün işlenen barkod sayısı
        $processedToday = Barcode::whereDate('lab_at', today())->count();
        
        // Bekleyen barkod sayısı
        $pendingBarcodes = Barcode::where('status', Barcode::STATUS_WAITING)->count();
        
        // Teslim oranı
        $totalDelivered = Barcode::where('status', Barcode::STATUS_DELIVERED)->count();
        $deliveryRate = $totalBarcodes > 0 ? round(($totalDelivered / $totalBarcodes) * 100, 1) : 0;
        
        // Yeni KPI verileri
        $totalUsers = User::count();
        $totalWarehouses = \App\Models\Warehouse::count();
        $totalCompanies = \App\Models\Company::count();
        $totalKilns = \App\Models\Kiln::count();
        $totalStocks = \App\Models\Stock::count();
        // Toplam stok miktarı (KG cinsinden) - tüm barkodlardaki miktarların toplamı
        $totalQuantity = DB::select('
            SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.deleted_at IS NULL
        ')[0]->total_quantity ?? 0;
        
        // Durum dağılımı - yeni durum yapısına göre
        $statusDistribution = [
            'waiting' => Barcode::where('status', Barcode::STATUS_WAITING)->count(),
            'control_repeat' => Barcode::where('status', Barcode::STATUS_CONTROL_REPEAT)->count(),
            'pre_approved' => Barcode::where('status', Barcode::STATUS_PRE_APPROVED)->count(),
            'shipment_approved' => Barcode::where('status', Barcode::STATUS_SHIPMENT_APPROVED)->count(),
            'rejected' => Barcode::where('status', Barcode::STATUS_REJECTED)->count(),
            'customer_transfer' => Barcode::where('status', Barcode::STATUS_CUSTOMER_TRANSFER)->count(),
            'delivered' => Barcode::where('status', Barcode::STATUS_DELIVERED)->count(),
        ];
        
        // Günlük trend (son 7 gün)
        $dailyTrend = $this->getDailyTrend();
        
        return [
            'total_barcodes' => $totalBarcodes,
            'processed_today' => $processedToday,
            'pending_barcodes' => $pendingBarcodes,
            'delivery_rate' => $deliveryRate,
            'total_users' => $totalUsers,
            'total_warehouses' => $totalWarehouses,
            'total_companies' => $totalCompanies,
            'total_kilns' => $totalKilns,
            'total_stocks' => $totalStocks,
            'total_quantity' => $totalQuantity,
            'status_distribution' => $statusDistribution,
            'daily_trend' => $dailyTrend
        ];
    }

    /**
     * Günlük trend verilerini hesapla
     */
    private function getDailyTrend()
    {
        $last7Days = collect();
        $labels = [];
        $created = [];
        $processed = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d.m');
            $created[] = Barcode::whereDate('created_at', $date)->count();
            $processed[] = Barcode::whereDate('lab_at', $date)->count();
        }
        
        return [
            'labels' => $labels,
            'created' => $created,
            'processed' => $processed
        ];
    }

    /**
     * Üretim trendi widget'ı
     */
    public function getProductionTrend()
    {
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Barcode::whereDate('created_at', $date)->count();
            $last7Days->push([
                'date' => $date->format('d.m'),
                'count' => $count,
                'day' => $date->format('l')
            ]);
        }

        $totalThisWeek = $last7Days->sum('count');
        $totalLastWeek = Barcode::whereBetween('created_at', [
            now()->subDays(13), 
            now()->subDays(7)
        ])->count();

        $growth = $totalLastWeek > 0 ? (($totalThisWeek - $totalLastWeek) / $totalLastWeek) * 100 : 0;

        return [
            'type' => 'chart',
            'title' => 'Üretim Trendi',
            'subtitle' => 'Son 7 gün',
            'icon' => 'fas fa-chart-line',
            'color' => 'primary',
            'data' => [
                'labels' => $last7Days->pluck('date'),
                'values' => $last7Days->pluck('count'),
                'total' => $totalThisWeek,
                'growth' => round($growth, 1),
                'growth_type' => $growth >= 0 ? 'positive' : 'negative'
            ]
        ];
    }

    /**
     * Kalite metrikleri widget'ı
     */
    public function getQualityMetrics()
    {
        $today = now();
        $thisMonth = now()->startOfMonth();

        $todayStats = [
            'total' => Barcode::whereDate('created_at', $today)->count(),
            'pre_approved' => Barcode::whereDate('created_at', $today)
                ->where('status', Barcode::STATUS_PRE_APPROVED)->count(),
            'rejected' => Barcode::whereDate('created_at', $today)
                ->where('status', Barcode::STATUS_REJECTED)->count(),
        ];

        $monthlyStats = [
            'total' => Barcode::whereBetween('created_at', [$thisMonth, now()])->count(),
            'pre_approved' => Barcode::whereBetween('created_at', [$thisMonth, now()])
                ->where('status', Barcode::STATUS_PRE_APPROVED)->count(),
            'rejected' => Barcode::whereBetween('created_at', [$thisMonth, now()])
                ->where('status', Barcode::STATUS_REJECTED)->count(),
        ];

        $todayAcceptanceRate = $todayStats['total'] > 0 ? 
            round(($todayStats['pre_approved'] / $todayStats['total']) * 100, 1) : 0;
        
        $monthlyAcceptanceRate = $monthlyStats['total'] > 0 ? 
            round(($monthlyStats['pre_approved'] / $monthlyStats['total']) * 100, 1) : 0;

        return [
            'type' => 'metrics',
            'title' => 'Kalite Metrikleri',
            'subtitle' => 'Bugün',
            'icon' => 'fas fa-award',
            'color' => 'success',
            'data' => [
                'today' => [
                    'total' => $todayStats['total'],
                                    'pre_approved' => $todayStats['pre_approved'],
                'rejected' => $todayStats['rejected'],
                    'acceptance_rate' => $todayAcceptanceRate
                ],
                'monthly' => [
                    'total' => $monthlyStats['total'],
                                    'pre_approved' => $monthlyStats['pre_approved'],
                'rejected' => $monthlyStats['rejected'],
                    'acceptance_rate' => $monthlyAcceptanceRate
                ]
            ]
        ];
    }

    /**
     * Son aktiviteler widget'ı
     */
    public function getRecentActivities()
    {
        $activities = Barcode::with(['stock', 'kiln', 'createdBy', 'labBy'])
            ->whereNotNull('lab_at')
            ->orderByDesc('lab_at')
            ->limit(8)
            ->get()
            ->map(function ($barcode) {
                return [
                    'id' => $barcode->id,
                    'type' => 'barcode_processed',
                    'title' => $barcode->stock->code . ' - ' . $barcode->stock->name,
                    'subtitle' => $barcode->kiln->name . ' | ' . $barcode->load_number,
                    'status' => Barcode::STATUSES[$barcode->status],
                    'status_color' => $barcode->status == Barcode::STATUS_PRE_APPROVED ? 'success' : 'danger',
                    'user' => $barcode->labBy->name ?? 'Sistem',
                    'time' => $barcode->lab_at->diffForHumans(),
                    'timestamp' => $barcode->lab_at->format('H:i')
                ];
            });

        return [
            'type' => 'activity',
            'title' => 'Son Aktiviteler',
            'subtitle' => 'Son 8 işlem',
            'icon' => 'fas fa-history',
            'color' => 'info',
            'data' => $activities
        ];
    }

    /**
     * Stok uyarıları widget'ı
     */
    public function getStockAlerts()
    {
        $lowStockThreshold = 100; // KG cinsinden minimum stok seviyesi
        
        $lowStockItems = DB::select('
            SELECT 
                stocks.id,
                stocks.code,
                stocks.name,
                COALESCE(SUM(CASE WHEN barcodes.status = ? THEN quantities.quantity ELSE 0 END), 0) as available_stock
            FROM stocks
            LEFT JOIN barcodes ON barcodes.stock_id = stocks.id AND barcodes.deleted_at IS NULL
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id

            GROUP BY stocks.id, stocks.code, stocks.name
            HAVING available_stock <= ?
            ORDER BY available_stock ASC
            LIMIT 5
        ', [Barcode::STATUS_SHIPMENT_APPROVED, $lowStockThreshold]);

        $criticalItems = collect($lowStockItems)->filter(function ($item) use ($lowStockThreshold) {
            return $item->available_stock <= ($lowStockThreshold * 0.5);
        });

        return [
            'type' => 'alert',
            'title' => 'Stok Uyarıları',
            'subtitle' => count($lowStockItems) . ' ürün düşük stok',
            'icon' => 'fas fa-exclamation-triangle',
            'color' => count($criticalItems) > 0 ? 'danger' : 'warning',
            'data' => [
                'low_stock_items' => $lowStockItems,
                'critical_count' => count($criticalItems),
                'threshold' => $lowStockThreshold
            ]
        ];
    }

    /**
     * Kullanıcı performansı widget'ı
     */
    public function getUserPerformance()
    {
        $userStats = User::withCount(['barcodesCreated', 'barcodesProcessed'])
            ->whereHas('barcodesProcessed', function ($query) {
                $query->whereDate('lab_at', today());
            })
            ->get()
            ->map(function ($user) {
                $todayProcessed = $user->barcodesProcessed()
                    ->whereDate('lab_at', today())
                    ->count();
                
                $todayAccepted = $user->barcodesProcessed()
                    ->whereDate('lab_at', today())
                    ->where('status', Barcode::STATUS_PRE_APPROVED)
                    ->count();

                $acceptanceRate = $todayProcessed > 0 ? 
                    round(($todayAccepted / $todayProcessed) * 100, 1) : 0;

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->avatar ?? 'default-avatar.png',
                    'processed_today' => $todayProcessed,
                    'accepted_today' => $todayAccepted,
                    'acceptance_rate' => $acceptanceRate,
                    'performance_color' => $acceptanceRate >= 90 ? 'success' : 
                        ($acceptanceRate >= 75 ? 'warning' : 'danger')
                ];
            })
            ->sortByDesc('processed_today')
            ->take(5);

        return [
            'type' => 'performance',
            'title' => 'Kullanıcı Performansı',
            'subtitle' => 'Bugün en aktif 5 kullanıcı',
            'icon' => 'fas fa-users',
            'color' => 'primary',
            'data' => $userStats
        ];
    }

    /**
     * Sistem sağlığı widget'ı
     */
    public function getSystemHealth()
    {
        $diskUsage = disk_free_space(storage_path()) / disk_total_space(storage_path()) * 100;
        $memoryUsage = memory_get_usage(true) / 1024 / 1024; // MB
        $databaseConnections = DB::select('SHOW STATUS LIKE "Threads_connected"')[0]->Value ?? 0;

        // Data Integrity Kontrolleri
        $dataIntegrityIssues = $this->checkDataIntegrity();
        
        $healthScore = 100;
        $issues = [];

        if ($diskUsage > 90) {
            $healthScore -= 30;
            $issues[] = 'Disk alanı kritik seviyede';
        } elseif ($diskUsage > 80) {
            $healthScore -= 15;
            $issues[] = 'Disk alanı azalıyor';
        }

        if ($memoryUsage > 512) {
            $healthScore -= 20;
            $issues[] = 'Yüksek bellek kullanımı';
        }

        if ($databaseConnections > 50) {
            $healthScore -= 10;
            $issues[] = 'Çok sayıda veritabanı bağlantısı';
        }

        // Data integrity sorunlarından puan düş
        $healthScore -= count($dataIntegrityIssues) * 5;
        $issues = array_merge($issues, $dataIntegrityIssues);

        return [
            'type' => 'health',
            'title' => 'Sistem Sağlığı',
            'subtitle' => 'Sistem durumu',
            'icon' => 'fas fa-heartbeat',
            'color' => $healthScore >= 80 ? 'success' : ($healthScore >= 60 ? 'warning' : 'danger'),
            'data' => [
                'health_score' => max(0, $healthScore),
                'disk_usage' => round($diskUsage, 1),
                'memory_usage' => round($memoryUsage, 1),
                'db_connections' => $databaseConnections,
                'data_integrity_issues' => $dataIntegrityIssues,
                'issues' => $issues,
                'last_check' => now()->format('H:i:s')
            ]
        ];
    }

    /**
     * Veri bütünlüğü kontrollerini yap
     */
    private function checkDataIntegrity()
    {
        $issues = [];
        
        // 1. Orphaned barcodes (parent kayıtları olmayan)
        $orphanedBarcodes = Barcode::whereDoesntHave('stock')
            ->orWhereDoesntHave('kiln')
            ->orWhereDoesntHave('quantity')
            ->count();
            
        if ($orphanedBarcodes > 0) {
            $issues[] = "{$orphanedBarcodes} adet orphaned barkod bulundu";
        }
        
        // 2. Geçersiz durum değerleri
        $invalidStatusBarcodes = Barcode::whereNotIn('status', [
            Barcode::STATUS_WAITING,
            Barcode::STATUS_CONTROL_REPEAT,
            Barcode::STATUS_PRE_APPROVED,
            Barcode::STATUS_SHIPMENT_APPROVED,
            Barcode::STATUS_REJECTED,
            Barcode::STATUS_CUSTOMER_TRANSFER,
            Barcode::STATUS_DELIVERED,
            Barcode::STATUS_MERGED
        ])->count();
        
        if ($invalidStatusBarcodes > 0) {
            $issues[] = "{$invalidStatusBarcodes} adet geçersiz durum değeri";
        }
        
        // 3. Tutarsız tarih değerleri
        $inconsistentDates = Barcode::where('lab_at', '<', 'created_at')
            ->orWhere('warehouse_transferred_at', '<', 'lab_at')
            ->orWhere('delivered_at', '<', 'warehouse_transferred_at')
            ->count();
            
        if ($inconsistentDates > 0) {
            $issues[] = "{$inconsistentDates} adet tutarsız tarih değeri";
        }
        
        // 4. Boş zorunlu alanlar
        $emptyRequiredFields = Barcode::whereNull('stock_id')
            ->orWhereNull('kiln_id')
            ->orWhereNull('quantity_id')
            ->count();
            
        if ($emptyRequiredFields > 0) {
            $issues[] = "{$emptyRequiredFields} adet boş zorunlu alan";
        }
        
        // 5. Duplicate load numbers (aynı fırında aynı şarj numarası)
        $duplicateLoadNumbers = DB::select('
            SELECT COUNT(*) as count FROM (
                SELECT kiln_id, load_number, COUNT(*) as cnt
                FROM barcodes 
                WHERE load_number IS NOT NULL 
                GROUP BY kiln_id, load_number 
                HAVING cnt > 1
            ) as duplicates
        ')[0]->count ?? 0;
        
        if ($duplicateLoadNumbers > 0) {
            $issues[] = "{$duplicateLoadNumbers} adet duplicate şarj numarası";
        }
        
        return $issues;
    }

    /**
     * Hızlı işlemler widget'ı
     */
    public function getQuickActions()
    {
        return [
            'type' => 'actions',
            'title' => 'Hızlı İşlemler',
            'subtitle' => 'Sık kullanılan işlemler',
            'icon' => 'fas fa-bolt',
            'color' => 'warning',
            'data' => [
                [
                    'title' => 'Barkod Oluştur',
                    'icon' => 'fas fa-plus',
                    'url' => route('barcode.create'),
                    'color' => 'primary'
                ],
                [
                    'title' => 'Barkod Tara',
                    'icon' => 'fas fa-qrcode',
                    'url' => route('barcode.qr-read'),
                    'color' => 'success'
                ],
                [
                    'title' => 'Stok Raporu',
                    'icon' => 'fas fa-chart-bar',
                    'url' => route('stock.index'),
                    'color' => 'info'
                ],
                [
                    'title' => 'Laboratuvar',
                    'icon' => 'fas fa-flask',
                    'url' => route('laboratory.dashboard'),
                    'color' => 'warning'
                ]
            ]
        ];
    }

    /**
     * Widget ayarlarını kaydet
     */
    public function saveWidgetSettings(Request $request)
    {
        $request->validate([
            'widgets' => 'required|array',
            'widgets.*.id' => 'required|string',
            'widgets.*.enabled' => 'required|boolean',
            'widgets.*.position' => 'required|integer'
        ]);

        $user = auth()->user();
        $user->widget_settings = $request->widgets;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Widget ayarları kaydedildi'
        ]);
    }

    /**
     * Widget verilerini yenile
     */
    public function refreshWidget(Request $request)
    {
        $request->validate([
            'widget_type' => 'required|string'
        ]);

        $method = 'get' . str_replace('_', '', ucwords($request->widget_type, '_'));
        
        if (method_exists($this, $method)) {
            return response()->json([
                'success' => true,
                'data' => $this->$method()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Widget bulunamadı'
        ], 404);
    }
} 