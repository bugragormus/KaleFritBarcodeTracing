<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Stock;
use App\Models\Company;
use App\Models\Warehouse;
use App\Models\Kiln;
use App\Services\StockCalculationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $stockCalculationService;

    public function __construct(StockCalculationService $stockCalculationService)
    {
        $this->stockCalculationService = $stockCalculationService;
    }

    public function index()
    {
        // Tarih seçimi (varsayılan: bugün)
        $selectedDate = request('date', Carbon::today('Europe/Istanbul')->format('Y-m-d'));
        $date = Carbon::parse($selectedDate)->setTimezone('Europe/Istanbul');
        
        // Günlük üretim raporu
        $dailyProduction = $this->getDailyProduction($date);
        
        // Vardiya raporu (3 vardiya)
        $shiftReport = $this->getShiftReport($date);
        
        // Fırın başına üretim performansı
        $kilnPerformance = $this->getKilnPerformance($date);
        
        // Fırın başına red oranları
        $kilnRejectionRates = $this->getKilnRejectionRates($date);
        
        // Stok yaşı uyarıları
        $stockAgeWarnings = $this->getStockAgeWarnings();
        
        // Ürün özelinde fırın kapasite analizi
        $productKilnAnalysis = $this->getProductKilnAnalysis($date);
        
        // Günlük genel istatistikler
        $dailyStats = $this->getDailyStats($date);
        
        // Haftalık trend
        $weeklyTrend = $this->getWeeklyTrend($date);
        
        // Aylık karşılaştırma
        $monthlyComparison = $this->getMonthlyComparison($date);
        
        // Yeni eklenen raporlar
        $productionEfficiency = $this->getProductionEfficiency($date);
        $stockABC = $this->getStockABC($date);
        $shiftEfficiency = $this->getShiftEfficiency($date);
        $timeComparison = $this->getTimeComparison($date);
        $kpiMetrics = $this->getKPIMetrics($date);
        $userActivityLogs = $this->getUserActivityLogs($date);
        
        return view('admin.dashboard', compact(
            'selectedDate',
            'date',
            'dailyProduction',
            'shiftReport',
            'kilnPerformance',
            'kilnRejectionRates',
            'stockAgeWarnings',
            'productKilnAnalysis',
            'dailyStats',
            'weeklyTrend',
            'monthlyComparison',
            'productionEfficiency',
            'stockABC',
            'shiftEfficiency',
            'timeComparison',
            'kpiMetrics',
            'userActivityLogs'
        ));
    }

    /**
     * Excel export for kiln performance
     */
    public function exportKilnPerformance(Request $request)
    {
        try {
            \Log::info('Export request received', ['request' => $request->all()]);
            
            $selectedDate = $request->input('date', Carbon::today('Europe/Istanbul')->format('Y-m-d'));
            $date = Carbon::parse($selectedDate)->setTimezone('Europe/Istanbul');
            
            \Log::info('Date parsed', ['selectedDate' => $selectedDate, 'parsedDate' => $date->toDateTimeString()]);
            
            $kilnPerformance = $this->getKilnPerformance($date);
            
            \Log::info('Kiln performance data retrieved', ['count' => count($kilnPerformance)]);
            
            $filename = 'firin_performans_' . $date->format('Y-m-d') . '.csv';
            
            return response()->json([
                'success' => true,
                'data' => $kilnPerformance,
                'filename' => $filename
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Export error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Günlük üretim raporu
     */
    private function getDailyProduction($date)
    {
        $startDate = $date->copy()->startOfDay();
        $endDate = $date->copy()->endOfDay();
        
        return [
            'total_barcodes' => Barcode::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_quantity' => DB::select('
                SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
                FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                WHERE barcodes.created_at BETWEEN ? AND ?
                AND barcodes.deleted_at IS NULL
            ', [$startDate, $endDate])[0]->total_quantity ?? 0,
            'accepted_barcodes' => Barcode::whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('status', [Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_SHIPMENT_APPROVED])
                ->count(),
            'rejected_barcodes' => Barcode::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', Barcode::STATUS_REJECTED)
                ->count(),
            'pending_barcodes' => Barcode::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', Barcode::STATUS_WAITING)
                ->count()
        ];
    }

    /**
     * Vardiya raporu (3 vardiya)
     */
    private function getShiftReport($date)
    {
        $shifts = [
            'gece' => ['start' => '22:00', 'end' => '06:00'],
            'gündüz' => ['start' => '06:00', 'end' => '14:00'],
            'akşam' => ['start' => '14:00', 'end' => '22:00']
        ];
        
        $shiftData = [];
        
        foreach ($shifts as $shiftName => $shiftTime) {
            $startTime = $date->copy()->setTimeFromTimeString($shiftTime['start']);
            $endTime = $date->copy()->setTimeFromTimeString($shiftTime['end']);
            
            // Gece vardiyası için gün değişimi
            if ($shiftName === 'gece') {
                $endTime->addDay();
            }
            
            $shiftData[$shiftName] = [
                'barcode_count' => Barcode::whereBetween('created_at', [$startTime, $endTime])->count(),
                'total_quantity' => DB::select('
                    SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
                    FROM barcodes
                    LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                    WHERE barcodes.created_at BETWEEN ? AND ?
                    AND barcodes.deleted_at IS NULL
                ', [$startTime, $endTime])[0]->total_quantity ?? 0,
                'accepted_count' => Barcode::whereBetween('created_at', [$startTime, $endTime])
                    ->whereIn('status', [Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_SHIPMENT_APPROVED])
                    ->count(),
                'rejected_count' => Barcode::whereBetween('created_at', [$startTime, $endTime])
                    ->where('status', Barcode::STATUS_REJECTED)
                    ->count()
            ];
        }
        
        return $shiftData;
    }

    /**
     * Fırın başına üretim performansı
     */
    private function getKilnPerformance($date)
    {
        $startDate = $date->copy()->startOfDay();
        $endDate = $date->copy()->endOfDay();
        
        $result = DB::select('
            SELECT 
                kilns.id,
                kilns.name as kiln_name,
                COUNT(barcodes.id) as barcode_count,
                COALESCE(SUM(quantities.quantity), 0) as total_quantity,
                AVG(quantities.quantity) as avg_quantity,
                COUNT(CASE WHEN barcodes.status IN (?, ?) THEN 1 END) as accepted_count,
                COUNT(CASE WHEN barcodes.status = ? THEN 1 END) as rejected_count
            FROM kilns
            LEFT JOIN barcodes ON kilns.id = barcodes.kiln_id 
                AND barcodes.created_at BETWEEN ? AND ?
                AND barcodes.deleted_at IS NULL
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            GROUP BY kilns.id, kilns.name
        ', [
            Barcode::STATUS_PRE_APPROVED,
            Barcode::STATUS_SHIPMENT_APPROVED,
            Barcode::STATUS_REJECTED,
            $startDate,
            $endDate
        ]);
        
        // Doğal sıralama (natural sorting) ile fırın adlarını sırala
        usort($result, function($a, $b) {
            return strnatcmp($a->kiln_name, $b->kiln_name);
        });
        
        return $result;
    }

    /**
     * Fırın başına red oranları
     */
    private function getKilnRejectionRates($date)
    {
        $startDate = $date->copy()->startOfDay();
        $endDate = $date->copy()->endOfDay();
        
        return DB::select('
            SELECT 
                kilns.id,
                kilns.name as kiln_name,
                COUNT(barcodes.id) as total_barcodes,
                COUNT(CASE WHEN barcodes.status = ? THEN 1 END) as rejected_count,
                ROUND(
                    (COUNT(CASE WHEN barcodes.status = ? THEN 1 END) * 100.0 / COUNT(barcodes.id)), 2
                ) as rejection_rate
            FROM kilns
            LEFT JOIN barcodes ON kilns.id = barcodes.kiln_id 
                AND barcodes.created_at BETWEEN ? AND ?
                AND barcodes.deleted_at IS NULL
            GROUP BY kilns.id, kilns.name
            HAVING total_barcodes > 0
            ORDER BY rejection_rate DESC
        ', [
            Barcode::STATUS_REJECTED,
            Barcode::STATUS_REJECTED,
            $startDate,
            $endDate
        ]);
    }

    /**
     * Stok yaşı uyarıları
     */
    private function getStockAgeWarnings()
    {
        $warningThresholds = [
            'critical' => 30, // 30 günden eski - kritik
            'warning' => 15,  // 15 günden eski - uyarı
            'info' => 7       // 7 günden eski - bilgi
        ];
        
        $warnings = [];
        
        foreach ($warningThresholds as $level => $days) {
            $cutoffDate = Carbon::now()->subDays($days);
            
            $stocks = DB::select('
                SELECT 
                    stocks.id,
                    stocks.name,
                    stocks.code,
                    MAX(barcodes.created_at) as last_production_date,
                    DATEDIFF(NOW(), MAX(barcodes.created_at)) as days_old,
                    COUNT(barcodes.id) as total_barcodes
                FROM stocks
                LEFT JOIN barcodes ON stocks.id = barcodes.stock_id 
                    AND barcodes.deleted_at IS NULL
                GROUP BY stocks.id, stocks.name, stocks.code
                HAVING last_production_date < ? OR last_production_date IS NULL
                ORDER BY days_old DESC
            ', [$cutoffDate]);
            
            if (!empty($stocks)) {
                $warnings[$level] = $stocks;
            }
        }
        
        return $warnings;
    }

    /**
     * Ürün özelinde fırın kapasite analizi
     */
    private function getProductKilnAnalysis($date)
    {
        $startDate = $date->copy()->startOfDay();
        $endDate = $date->copy()->endOfDay();
        
        return DB::select('
            SELECT 
                stocks.id as stock_id,
                stocks.name as stock_name,
                stocks.code as stock_code,
                kilns.id as kiln_id,
                kilns.name as kiln_name,
                COUNT(barcodes.id) as barcode_count,
                COALESCE(SUM(quantities.quantity), 0) as total_quantity,
                COUNT(CASE WHEN barcodes.status IN (?, ?) THEN 1 END) as accepted_count,
                COUNT(CASE WHEN barcodes.status = ? THEN 1 END) as rejected_count,
                ROUND(
                    (COUNT(CASE WHEN barcodes.status IN (?, ?) THEN 1 END) * 100.0 / COUNT(barcodes.id)), 2
                ) as acceptance_rate
            FROM stocks
            LEFT JOIN barcodes ON stocks.id = barcodes.stock_id 
                AND barcodes.created_at BETWEEN ? AND ?
                AND barcodes.deleted_at IS NULL
            LEFT JOIN kilns ON barcodes.kiln_id = kilns.id
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            GROUP BY stocks.id, stocks.name, stocks.code, kilns.id, kilns.name
            HAVING barcode_count > 0
            ORDER BY stocks.name, total_quantity DESC
        ', [
            Barcode::STATUS_PRE_APPROVED,
            Barcode::STATUS_SHIPMENT_APPROVED,
            Barcode::STATUS_REJECTED,
            Barcode::STATUS_PRE_APPROVED,
            Barcode::STATUS_SHIPMENT_APPROVED,
            $startDate,
            $endDate
        ]);
    }

    /**
     * Günlük genel istatistikler
     */
    private function getDailyStats($date)
    {
        $startDate = $date->copy()->startOfDay();
        $endDate = $date->copy()->endOfDay();
        
        return [
            'total_stocks' => Stock::count(),
            'total_barcodes' => Barcode::count(),
            'total_companies' => Company::count(),
            'total_warehouses' => Warehouse::count(),
            'total_kilns' => Kiln::count(),
            'today_production' => Barcode::whereBetween('created_at', [$startDate, $endDate])->count(),
            'today_quantity' => DB::select('
                SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
                FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                WHERE barcodes.created_at BETWEEN ? AND ?
                AND barcodes.deleted_at IS NULL
            ', [$startDate, $endDate])[0]->total_quantity ?? 0,
            'today_accepted' => Barcode::whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('status', [Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_SHIPMENT_APPROVED])
                ->count(),
            'today_rejected' => Barcode::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', Barcode::STATUS_REJECTED)
                ->count()
        ];
    }

    /**
     * Haftalık trend
     */
    private function getWeeklyTrend($date)
    {
        $startDate = $date->copy()->subDays(6)->startOfDay();
        $endDate = $date->copy()->endOfDay();
        
        return DB::select('
            SELECT 
                DATE(barcodes.created_at) as date,
                COUNT(*) as barcode_count,
                COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
            GROUP BY DATE(barcodes.created_at)
            ORDER BY date
        ', [$startDate, $endDate]);
    }

    /**
     * Aylık karşılaştırma
     */
    private function getMonthlyComparison($date)
    {
        $currentMonth = $date->copy()->startOfMonth();
        $previousMonth = $date->copy()->subMonth()->startOfMonth();
        
        $currentMonthData = $this->getMonthData($currentMonth);
        $previousMonthData = $this->getMonthData($previousMonth);
        
        return [
            'current_month' => $currentMonthData,
            'previous_month' => $previousMonthData,
            'change_percentage' => [
                'total_barcodes' => $this->calculatePercentageChange($previousMonthData['total_barcodes'], $currentMonthData['total_barcodes']),
                'total_quantity' => $this->calculatePercentageChange($previousMonthData['total_quantity'], $currentMonthData['total_quantity'])
            ]
        ];
    }

    /**
     * Üretim verimliliği - Fırın kapasite kullanım oranı
     */
    private function getProductionEfficiency($date)
    {
        $startDate = $date->copy()->startOfDay();
        $endDate = $date->copy()->endOfDay();
        
        return DB::select('
            SELECT 
                kilns.id,
                kilns.name as kiln_name,
                -- Günlük ortalama üretim kapasitesi (son 30 gün)
                COALESCE(
                    (SELECT ROUND(AVG(daily_avg), 2)
                     FROM (
                         SELECT DATE(b.created_at) as date, SUM(q.quantity) as daily_avg
                         FROM barcodes b
                         LEFT JOIN quantities q ON b.quantity_id = q.id
                         WHERE b.kiln_id = kilns.id 
                         AND b.created_at >= DATE_SUB(?, INTERVAL 30 DAY)
                         AND b.deleted_at IS NULL
                         GROUP BY DATE(b.created_at)
                     ) as daily_averages
                ), 0) as theoretical_capacity,
                -- Bugünkü gerçek üretim (barkod sayısı)
                COUNT(barcodes.id) as actual_production,
                -- Bugünkü gerçek üretim (miktar - ton)
                COALESCE(SUM(quantities.quantity), 0) as actual_quantity,
                -- Kapasite kullanım oranı (barkod sayısı bazında)
                ROUND(
                    (COUNT(barcodes.id) / NULLIF(
                        COALESCE(
                            (SELECT ROUND(AVG(daily_avg), 2)
                             FROM (
                                 SELECT DATE(b.created_at) as date, SUM(q.quantity) as daily_avg
                                 FROM barcodes b
                                 LEFT JOIN quantities q ON b.quantity_id = q.id
                                 WHERE b.kiln_id = kilns.id 
                                 AND b.created_at >= DATE_SUB(?, INTERVAL 30 DAY)
                                 AND b.deleted_at IS NULL
                                 GROUP BY DATE(b.created_at)
                             ) as daily_averages
                        ), 0)
                    ) * 100, 2
                ) as capacity_utilization,
                -- Miktar kullanım oranı (ton bazında)
                ROUND(
                    (COALESCE(SUM(quantities.quantity), 0) / NULLIF(
                        COALESCE(
                            (SELECT ROUND(AVG(daily_avg), 2)
                             FROM (
                                 SELECT DATE(b.created_at) as date, SUM(q.quantity) as daily_avg
                                 FROM barcodes b
                                 LEFT JOIN quantities q ON b.quantity_id = q.id
                                 WHERE b.kiln_id = kilns.id 
                                 AND b.created_at >= DATE_SUB(?, INTERVAL 30 DAY)
                                 AND b.deleted_at IS NULL
                                 GROUP BY DATE(b.created_at)
                             ) as daily_averages
                        ), 0)
                    ) * 100, 2
                ) as quantity_utilization
            FROM kilns
            LEFT JOIN barcodes ON kilns.id = barcodes.kiln_id 
                AND barcodes.created_at BETWEEN ? AND ?
                AND barcodes.deleted_at IS NULL
            LEFT JOIN quantities q ON quantities.id = barcodes.quantity_id
            GROUP BY kilns.id, kilns.name
            ORDER BY capacity_utilization DESC
        ', [$startDate, $startDate, $startDate, $startDate, $endDate]);
    }

    /**
     * Stok ve depo yönetimi - ABC analizi
     */
    private function getStockABC($date)
    {
        $startDate = $date->copy()->subDays(30); // Son 30 gün
        
        return DB::select('
            SELECT 
                stocks.id,
                stocks.name,
                stocks.code,
                COUNT(barcodes.id) as usage_frequency,
                COALESCE(SUM(quantities.quantity), 0) as total_quantity,
                ROUND(
                    (COUNT(barcodes.id) * 100.0 / (
                        SELECT COUNT(*) FROM barcodes 
                        WHERE created_at >= ? AND deleted_at IS NULL
                    )), 2
                ) as usage_percentage
            FROM stocks
            LEFT JOIN barcodes ON stocks.id = barcodes.stock_id 
                AND barcodes.created_at >= ?
                AND barcodes.deleted_at IS NULL
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            GROUP BY stocks.id, stocks.name, stocks.code
            HAVING usage_frequency > 0
            ORDER BY usage_frequency DESC
        ', [$startDate, $startDate]);
    }

    /**
     * İnsan kaynakları - Vardiya verimliliği detayı
     */
    private function getShiftEfficiency($date)
    {
        $shifts = [
            'gece' => ['start' => '22:00', 'end' => '06:00', 'name' => 'Gece Vardiyası'],
            'gündüz' => ['start' => '06:00', 'end' => '14:00', 'name' => 'Gündüz Vardiyası'],
            'akşam' => ['start' => '14:00', 'end' => '22:00', 'name' => 'Akşam Vardiyası']
        ];
        
        $shiftEfficiency = [];
        
        foreach ($shifts as $shiftKey => $shiftInfo) {
            $startTime = $date->copy()->setTimeFromTimeString($shiftInfo['start']);
            $endTime = $date->copy()->setTimeFromTimeString($shiftInfo['end']);
            
            if ($shiftKey === 'gece') {
                $endTime->addDay();
            }
            
            $data = DB::select('
            SELECT 
                    COUNT(barcodes.id) as total_barcodes,
                    COALESCE(SUM(quantities.quantity), 0) as total_quantity,
                    COUNT(CASE WHEN barcodes.status IN (?, ?) THEN 1 END) as accepted_count,
                    COUNT(CASE WHEN barcodes.status = ? THEN 1 END) as rejected_count,
                    ROUND(
                        (COUNT(CASE WHEN barcodes.status IN (?, ?) THEN 1 END) * 100.0 / COUNT(barcodes.id)), 2
                    ) as acceptance_rate,
                    ROUND(
                        (COUNT(CASE WHEN barcodes.status = ? THEN 1 END) * 100.0 / COUNT(barcodes.id)), 2
                    ) as rejection_rate
                FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                WHERE barcodes.created_at BETWEEN ? AND ?
                AND barcodes.deleted_at IS NULL
            ', [
                Barcode::STATUS_PRE_APPROVED,
                Barcode::STATUS_SHIPMENT_APPROVED,
                Barcode::STATUS_REJECTED,
                Barcode::STATUS_PRE_APPROVED,
                Barcode::STATUS_SHIPMENT_APPROVED,
                Barcode::STATUS_REJECTED,
                $startTime,
                $endTime
            ]);
            
            if (!empty($data)) {
                $shiftEfficiency[$shiftKey] = array_merge($shiftInfo, (array) $data[0]);
            }
        }
        
        return $shiftEfficiency;
    }

    /**
     * Detaylı filtreleme - Zaman aralığı karşılaştırması
     */
    private function getTimeComparison($date)
    {
        $currentPeriod = $date->copy()->startOfDay();
        $previousPeriod = $date->copy()->subDay()->startOfDay();
        
        $currentData = $this->getDailyProduction($currentPeriod);
        $previousData = $this->getDailyProduction($previousPeriod);
        
        return [
            'current' => $currentData,
            'previous' => $previousData,
            'changes' => [
                'barcodes' => $this->calculatePercentageChange($previousData['total_barcodes'], $currentData['total_barcodes']),
                'quantity' => $this->calculatePercentageChange($previousData['total_quantity'], $currentData['total_quantity']),
                'accepted' => $this->calculatePercentageChange($previousData['accepted_barcodes'], $currentData['accepted_barcodes']),
                'rejected' => $this->calculatePercentageChange($previousData['rejected_barcodes'], $currentData['rejected_barcodes'])
            ]
        ];
    }

    /**
     * KPI ve hedef takibi
     */
    private function getKPIMetrics($date)
    {
        $startDate = $date->copy()->startOfMonth();
        $endDate = $date->copy()->endOfMonth();
        
        $monthlyData = $this->getMonthData($startDate);
        
        // Hedefleri cache'den al (varsayılan değerler ile)
        $targets = cache('kpi_targets', [
            'monthly_barcodes' => 10000,
            'monthly_quantity' => 50000,
            'acceptance_rate' => 95,
            'rejection_rate' => 5
        ]);
        
        $actualAcceptanceRate = $monthlyData['total_barcodes'] > 0 ? 
            round(($monthlyData['accepted_barcodes'] / $monthlyData['total_barcodes']) * 100, 2) : 0;
        
        $actualRejectionRate = $monthlyData['total_barcodes'] > 0 ? 
            round(($monthlyData['rejected_barcodes'] / $monthlyData['total_barcodes']) * 100, 2) : 0;
        
        return [
            'targets' => $targets,
            'actual' => [
                'monthly_barcodes' => $monthlyData['total_barcodes'],
                'monthly_quantity' => $monthlyData['total_quantity'],
                'acceptance_rate' => $actualAcceptanceRate,
                'rejection_rate' => $actualRejectionRate
            ],
            'achievement' => [
                'barcodes' => round(($monthlyData['total_barcodes'] / $targets['monthly_barcodes']) * 100, 2),
                'quantity' => round(($monthlyData['total_quantity'] / $targets['monthly_quantity']) * 100, 2),
                'acceptance' => $actualAcceptanceRate >= $targets['acceptance_rate'] ? 100 : round(($actualAcceptanceRate / $targets['acceptance_rate']) * 100, 2),
                'rejection' => $actualRejectionRate <= $targets['rejection_rate'] ? 100 : round(($targets['rejection_rate'] / $actualRejectionRate) * 100, 2)
            ]
        ];
    }

    /**
     * KPI hedeflerini güncelle
     */
    public function updateKPITargets(Request $request)
    {
        try {
            $validated = $request->validate([
                'monthly_barcodes' => 'required|integer|min:1',
                'monthly_quantity' => 'required|integer|min:1',
                'acceptance_rate' => 'required|numeric|min:0|max:100',
                'rejection_rate' => 'required|numeric|min:0|max:100'
            ]);

            // Hedefleri cache'e kaydet (gerçek uygulamada database'e kaydedilebilir)
            cache(['kpi_targets' => $validated], now()->addYear());

            return response()->json([
                'success' => true,
                'message' => 'KPI hedefleri başarıyla güncellendi',
                'targets' => $validated
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hedef güncellenirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * KPI hedeflerini getir
     */
    public function getKPITargets()
    {
        $targets = cache('kpi_targets', [
            'monthly_barcodes' => 10000,
            'monthly_quantity' => 50000,
            'acceptance_rate' => 95,
            'rejection_rate' => 5
        ]);

        return response()->json([
            'success' => true,
            'targets' => $targets
        ]);
    }

    /**
     * Güvenlik ve audit - Kullanıcı aktivite logları
     */
    private function getUserActivityLogs($date)
    {
        $startDate = $date->copy()->startOfDay();
        $endDate = $date->copy()->endOfDay();
        
        // Bu metod gerçek uygulamada activity log tablosundan veri çeker
        // Şimdilik örnek veri döndürüyoruz
        return [
            'total_activities' => rand(50, 200),
            'login_count' => rand(10, 30),
            'data_changes' => rand(20, 80),
            'export_activities' => rand(5, 15),
            'recent_activities' => [
                ['user' => 'Admin User', 'action' => 'Dashboard görüntülendi', 'time' => $date->copy()->subHours(1)->format('H:i'), 'ip' => '192.168.1.100', 'status' => 'success'],
                ['user' => 'Operator 1', 'action' => 'Barkod eklendi', 'time' => $date->copy()->subHours(2)->format('H:i'), 'ip' => '192.168.1.101', 'status' => 'success'],
                ['user' => 'Manager', 'action' => 'Rapor indirildi', 'time' => $date->copy()->subHours(3)->format('H:i'), 'ip' => '192.168.1.102', 'status' => 'success'],
                ['user' => 'Operator 2', 'action' => 'Veri güncellendi', 'time' => $date->copy()->subHours(4)->format('H:i'), 'ip' => '192.168.1.103', 'status' => 'warning'],
                ['user' => 'Guest', 'action' => 'Yetkisiz erişim denemesi', 'time' => $date->copy()->subHours(5)->format('H:i'), 'ip' => '192.168.1.104', 'status' => 'danger']
            ],
            'security_alerts' => [
                ['type' => 'Failed Login', 'count' => rand(1, 10), 'severity' => 'medium'],
                ['type' => 'Suspicious Activity', 'count' => rand(0, 5), 'severity' => 'high'],
                ['type' => 'Data Export', 'count' => rand(5, 20), 'severity' => 'low']
            ],
            'user_performance' => [
                ['user' => 'Admin User', 'login_count' => rand(5, 15), 'actions' => rand(20, 50), 'last_activity' => $date->copy()->subMinutes(30)->format('H:i')],
                ['user' => 'Operator 1', 'login_count' => rand(3, 10), 'actions' => rand(15, 40), 'last_activity' => $date->copy()->subHours(2)->format('H:i')],
                ['user' => 'Manager', 'login_count' => rand(2, 8), 'actions' => rand(10, 30), 'last_activity' => $date->copy()->subHours(4)->format('H:i')]
            ]
        ];
    }

    /**
     * Yardımcı metodlar
     */
    private function getMonthData($date)
    {
        $startDate = $date->copy()->startOfMonth();
        $endDate = $date->copy()->endOfMonth();
        
        return [
            'total_barcodes' => Barcode::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_quantity' => DB::select('
                SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                WHERE barcodes.created_at BETWEEN ? AND ?
                AND barcodes.deleted_at IS NULL
            ', [$startDate, $endDate])[0]->total_quantity ?? 0,
            'accepted_barcodes' => Barcode::whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('status', [Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_SHIPMENT_APPROVED])
                ->count(),
            'rejected_barcodes' => Barcode::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', Barcode::STATUS_REJECTED)
                ->count()
        ];
    }

    private function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue > 0 ? 100 : 0;
        }
        
        return round((($newValue - $oldValue) / $oldValue) * 100, 2);
    }
}
