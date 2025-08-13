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
            'monthlyComparison'
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
            
            // Vardiya için ton bazında veriler
            $acceptedQuantity = DB::select('
                SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
                FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                WHERE barcodes.created_at BETWEEN ? AND ?
                AND barcodes.status IN (?, ?)
                AND barcodes.deleted_at IS NULL
            ', [$startTime, $endTime, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_SHIPMENT_APPROVED])[0]->total_quantity ?? 0;
            
            $rejectedQuantity = DB::select('
                SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
                FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                WHERE barcodes.created_at BETWEEN ? AND ?
                AND barcodes.status = ?
                AND barcodes.deleted_at IS NULL
            ', [$startTime, $endTime, Barcode::STATUS_REJECTED])[0]->total_quantity ?? 0;
            
            $shiftData[$shiftName] = [
                'barcode_count' => Barcode::whereBetween('created_at', [$startTime, $endTime])->count(),
                'total_quantity' => DB::select('
                    SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
                    FROM barcodes
                    LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                    WHERE barcodes.created_at BETWEEN ? AND ?
                    AND barcodes.deleted_at IS NULL
                ', [$startTime, $endTime])[0]->total_quantity ?? 0,
                'accepted_quantity' => $acceptedQuantity,
                'rejected_quantity' => $rejectedQuantity,
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
                COALESCE(SUM(CASE WHEN barcodes.status IN (?, ?) THEN quantities.quantity ELSE 0 END), 0) as accepted_quantity,
                COALESCE(SUM(CASE WHEN barcodes.status = ? THEN quantities.quantity ELSE 0 END), 0) as rejected_quantity,
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
                COALESCE(SUM(CASE WHEN barcodes.status = ? THEN quantities.quantity ELSE 0 END), 0) as rejected_quantity,
                COUNT(CASE WHEN barcodes.status = ? THEN 1 END) as rejected_count,
                ROUND(
                    (COUNT(CASE WHEN barcodes.status = ? THEN 1 END) * 100.0 / COUNT(barcodes.id)), 2
                ) as rejection_rate
            FROM kilns
            LEFT JOIN barcodes ON kilns.id = barcodes.kiln_id 
                AND barcodes.created_at BETWEEN ? AND ?
                AND barcodes.deleted_at IS NULL
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            GROUP BY kilns.id, kilns.name
            HAVING total_barcodes > 0
            ORDER BY rejection_rate DESC
        ', [
            Barcode::STATUS_REJECTED,
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
                COALESCE(SUM(CASE WHEN barcodes.status IN (?, ?) THEN quantities.quantity ELSE 0 END), 0) as accepted_quantity,
                COALESCE(SUM(CASE WHEN barcodes.status = ? THEN quantities.quantity ELSE 0 END), 0) as rejected_quantity,
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
            'change_percentage' => $this->calculateChangePercentage($currentMonthData, $previousMonthData)
        ];
    }

    /**
     * Ay verilerini getir
     */
    private function getMonthData($monthStart)
    {
        $monthEnd = $monthStart->copy()->endOfMonth();
        
        return [
            'total_barcodes' => Barcode::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
            'total_quantity' => DB::select('
                SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
                FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                WHERE barcodes.created_at BETWEEN ? AND ?
                AND barcodes.deleted_at IS NULL
            ', [$monthStart, $monthEnd])[0]->total_quantity ?? 0,
            'accepted_barcodes' => Barcode::whereBetween('created_at', [$monthStart, $monthEnd])
                ->whereIn('status', [Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_SHIPMENT_APPROVED])
                ->count(),
            'rejected_barcodes' => Barcode::whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('status', Barcode::STATUS_REJECTED)
                ->count()
        ];
    }

    /**
     * Değişim yüzdesini hesapla
     */
    private function calculateChangePercentage($current, $previous)
    {
        $changes = [];
        
        foreach ($current as $key => $value) {
            if (isset($previous[$key]) && $previous[$key] > 0) {
                $changes[$key] = round((($value - $previous[$key]) / $previous[$key]) * 100, 2);
            } else {
                $changes[$key] = 0;
            }
        }
        
        return $changes;
    }
}
