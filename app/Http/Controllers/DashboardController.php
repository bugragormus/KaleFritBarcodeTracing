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
        // Periyot seçimi (varsayılan: günlük)
        $period = request('period', 'daily');
        $selectedDate = request('date', Carbon::today('Europe/Istanbul')->format('Y-m-d'));
        $startDate = request('start_date');
        $endDate = request('end_date');
        $date = Carbon::parse($selectedDate)->setTimezone('Europe/Istanbul');
        
        // Periyot bilgilerini hesapla
        $periodInfo = $this->calculatePeriodInfo($date, $period, $startDate, $endDate);
        
        // Periyot bazında üretim raporu
        $productionData = $this->getProductionData($date, $period, $periodInfo);
        
        // Vardiya raporu (3 vardiya) - sadece günlük periyotta
        $shiftReport = $period === 'daily' ? $this->getShiftReport($date) : [];
        
        // Fırın başına üretim performansı
        $kilnPerformance = $this->getKilnPerformance($date);
        
        // Fırın başına red oranları
        $kilnRejectionRates = $this->getKilnRejectionRates($date);
        
        // Red sebepleri analizi
        $rejectionReasonsAnalysis = $this->getRejectionReasonsAnalysis($date);
        
        // Ürün özelinde fırın kapasite analizi
        $productKilnAnalysis = $this->getProductKilnAnalysis($date);
        
        // Genel istatistikler
        $generalStats = $this->getGeneralStats($date, $period, $periodInfo);
        
        // Haftalık trend
        $weeklyTrend = $this->getWeeklyTrend($date);
        
        // Aylık karşılaştırma
        $monthlyComparison = $this->getMonthlyComparison($date);
        
        // Algoritmik Trend Analizleri - her zaman güncel tarihe göre
        $aiInsights = $this->generateAIInsights(Carbon::today('Europe/Istanbul'));
        
        // Stok yaşı analizi
        $stockAgeAnalysis = $this->getStockAgeAnalysis();
        
        // Stok, depo ve fırın bilgileri
        $stockInfo = $this->getStockInfo();
        $warehouseInfo = $this->getWarehouseInfo();
        $kilnInfo = $this->getKilnInfo();
        
        return view('admin.dashboard', compact(
            'selectedDate',
            'date',
            'period',
            'periodInfo',
            'startDate',
            'endDate',
            'productionData',
            'shiftReport',
            'kilnPerformance',
            'kilnRejectionRates',
            'rejectionReasonsAnalysis',
            'productKilnAnalysis',
            'generalStats',
            'weeklyTrend',
            'monthlyComparison',
            'aiInsights',
            'stockAgeAnalysis',
            'stockInfo',
            'warehouseInfo',
            'kilnInfo'
        ));
    }

    /**
     * Periyot bilgilerini hesapla
     */
    private function calculatePeriodInfo($date, $period, $startDate = null, $endDate = null)
    {
        $today = Carbon::today('Europe/Istanbul');
        
        // Özel tarih aralığı seçilmişse
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->setTimezone('Europe/Istanbul');
            $end = Carbon::parse($endDate)->setTimezone('Europe/Istanbul');
            
            // Gelecekteki tarihleri bugüne sınırla
            if ($end->isAfter($today)) {
                $end = $today->copy();
            }
            
            return [
                'name' => 'Özel Tarih Aralığı',
                'range' => $start->format('d.m.Y') . ' - ' . $end->format('d.m.Y'),
                'start_date' => $start,
                'end_date' => $end,
                'start_date_formatted' => $start->format('d.m.Y'),
                'end_date_formatted' => $end->format('d.m.Y'),
                'is_custom' => true
            ];
        }
        
        $startDate = $date->copy();
        $endDate = $date->copy();
        
        switch ($period) {
            case 'daily':
                $startDate = $date->copy()->startOfDay();
                $endDate = $date->copy()->endOfDay();
                $periodName = 'Günlük';
                $periodRange = $date->format('d.m.Y');
                break;
                
            case 'weekly':
                $startDate = $date->copy()->startOfWeek();
                $endDate = $date->copy()->endOfWeek();
                
                // Gelecekteki tarihleri bugüne sınırla
                if ($endDate->isAfter($today)) {
                    $endDate = $today->copy()->endOfDay();
                }
                
                $periodName = 'Haftalık';
                $periodRange = $startDate->format('d.m.Y') . ' - ' . $endDate->format('d.m.Y');
                break;
                
            case 'monthly':
                $startDate = $date->copy()->startOfMonth();
                $endDate = $date->copy()->endOfMonth();
                
                // Gelecekteki tarihleri bugüne sınırla
                if ($endDate->isAfter($today)) {
                    $endDate = $today->copy()->endOfDay();
                }
                
                $periodName = 'Aylık';
                $periodRange = $startDate->format('F Y');
                break;
                
            case 'quarterly':
                $startDate = $date->copy()->startOfQuarter();
                $endDate = $date->copy()->endOfQuarter();
                
                // Gelecekteki tarihleri bugüne sınırla
                if ($endDate->isAfter($today)) {
                    $endDate = $today->copy()->endOfDay();
                }
                
                $periodName = '3 Aylık';
                $periodRange = $startDate->format('d.m.Y') . ' - ' . $endDate->format('d.m.Y');
                break;
                
            case 'yearly':
                $startDate = $date->copy()->startOfYear();
                $endDate = $date->copy()->endOfYear();
                
                // Gelecekteki tarihleri bugüne sınırla
                if ($endDate->isAfter($today)) {
                    $endDate = $today->copy()->endOfDay();
                }
                
                $periodName = 'Yıllık';
                $periodRange = $startDate->format('Y');
                break;
                
            default:
                $startDate = $date->copy()->startOfDay();
                $endDate = $date->copy()->endOfDay();
                $periodName = 'Günlük';
                $periodRange = $date->format('d.m.Y');
        }
        
        return [
            'name' => $periodName,
            'range' => $periodRange,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'start_date_formatted' => $startDate->format('d.m.Y'),
            'end_date_formatted' => $endDate->format('d.m.Y'),
            'is_custom' => false
        ];
    }
    
    /**
     * Periyot bazında üretim verilerini topla
     */
    private function getProductionData($date, $period, $periodInfo)
    {
        $startDate = $periodInfo['start_date'];
        $endDate = $periodInfo['end_date'];
        
        // Kabul edilen miktar (ton)
        $acceptedQuantity = DB::select('
            SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.status = ?
            AND barcodes.deleted_at IS NULL
        ', [$startDate, $endDate, Barcode::STATUS_SHIPMENT_APPROVED])[0]->total_quantity ?? 0;
        
        // Test sürecinde olan miktar (ton)
        $testingQuantity = DB::select('
            SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.status IN (?, ?, ?)
            AND barcodes.deleted_at IS NULL
        ', [$startDate, $endDate, Barcode::STATUS_WAITING, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_CONTROL_REPEAT])[0]->total_quantity ?? 0;
        
        // Teslimat sürecinde olan miktar (ton)
        $deliveryQuantity = DB::select('
            SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.status IN (?, ?)
            AND barcodes.deleted_at IS NULL
        ', [$startDate, $endDate, Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED])[0]->total_quantity ?? 0;
        
        // Reddedilen miktar (ton)
        $rejectedQuantity = DB::select('
            SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.status IN (?, ?)
            AND barcodes.deleted_at IS NULL
        ', [$startDate, $endDate, Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED])[0]->total_quantity ?? 0;

        // Toplam barkod sayısı
        $totalBarcodes = DB::select('
            SELECT COUNT(*) as total_count
            FROM barcodes
            WHERE created_at BETWEEN ? AND ?
            AND deleted_at IS NULL
        ', [$startDate, $endDate])[0]->total_count ?? 0;

        // Toplam miktar
        $totalQuantity = DB::select('
            SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
        ', [$startDate, $endDate])[0]->total_quantity ?? 0;

        // Düzeltme faaliyeti ile oluşturulan üretim çıktısı (ton)
        $correctionOutputQuantity = DB::select('
            SELECT COALESCE(SUM(q.quantity), 0) as total_quantity
            FROM barcodes b
            LEFT JOIN quantities q ON q.id = b.quantity_id
            WHERE b.created_at BETWEEN ? AND ?
            AND IFNULL(b.is_correction, 0) = 1
            AND b.deleted_at IS NULL
        ', [$startDate, $endDate])[0]->total_quantity ?? 0;

        // Düzeltme faaliyeti kapsamında kullanılan red miktarı (ton)
        $correctionInputUsed = DB::select('
            SELECT COALESCE(SUM(b.correction_quantity), 0) as total_quantity
            FROM barcodes b
            WHERE b.created_at BETWEEN ? AND ?
            AND IFNULL(b.is_correction, 0) = 1
            AND b.deleted_at IS NULL
        ', [$startDate, $endDate])[0]->total_quantity ?? 0;

        // Düzeltmesiz üretim çıktısı (ton)
        $nonCorrectionOutputQuantity = DB::select('
            SELECT COALESCE(SUM(q.quantity), 0) as total_quantity
            FROM barcodes b
            LEFT JOIN quantities q ON q.id = b.quantity_id
            WHERE b.created_at BETWEEN ? AND ?
            AND IFNULL(b.is_correction, 0) = 0
            AND b.deleted_at IS NULL
        ', [$startDate, $endDate])[0]->total_quantity ?? 0;

        // Hammadde kullanımı (ton)
        $rawMaterialUsed = DB::select('
            SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
        ', [$startDate, $endDate])[0]->total_quantity ?? 0;

        return [
            'total_barcodes' => $totalBarcodes,
            'total_quantity' => $totalQuantity,
            'accepted_quantity' => $acceptedQuantity,
            'testing_quantity' => $testingQuantity,
            'delivery_quantity' => $deliveryQuantity,
            'rejected_quantity' => $rejectedQuantity,
            'with_correction_output' => $correctionOutputQuantity,
            'without_correction_output' => $nonCorrectionOutputQuantity,
            'correction_input_used' => $correctionInputUsed,
            'raw_material_used' => $rawMaterialUsed,
        ];
    }
    
    /**
     * Genel istatistikler
     */
    private function getGeneralStats($date, $period)
    {
        $periodInfo = $this->calculatePeriodInfo($date, $period);
        $startDate = $periodInfo['start_date'];
        $endDate = $periodInfo['end_date'];
        
        // Bu metod eski getDailyStats metodunun yerine geçer
        // Periyot bazında genel istatistikler toplanır
        return [
            'period_name' => $periodInfo['name'],
            'period_range' => $periodInfo['range'],
            'start_date' => $periodInfo['start_date_formatted'],
            'end_date' => $periodInfo['end_date_formatted']
        ];
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
     * Excel export for complete dashboard report
     */
    public function exportDashboard(Request $request)
    {
        try {
            \Log::info('Dashboard export request received', ['request' => $request->all()]);
            
            $selectedDate = $request->input('date', Carbon::today('Europe/Istanbul')->format('Y-m-d'));
            $period = $request->input('period', 'daily');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $date = Carbon::parse($selectedDate)->setTimezone('Europe/Istanbul');
            
            \Log::info('Date and period parsed for dashboard export', [
                'selectedDate' => $selectedDate, 
                'period' => $period,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'parsedDate' => $date->toDateTimeString()
            ]);
            
            // Periyot bilgilerini hesapla
            $periodInfo = $this->calculatePeriodInfo($date, $period, $startDate, $endDate);
            
            // Tüm dashboard verilerini topla
            $dashboardData = [
                'selectedDate' => $date->format('d.m.Y'),
                'period' => $period,
                'periodInfo' => $periodInfo,
                'productionData' => $this->getProductionData($date, $period, $periodInfo),
                'shiftReport' => $period === 'daily' ? $this->getShiftReport($date) : [],
                'kilnPerformance' => $this->getKilnPerformance($date),
                'kilnRejectionRates' => $this->getKilnRejectionRates($date),
                'rejectionReasonsAnalysis' => $this->getRejectionReasonsAnalysis($date),
                'productKilnAnalysis' => $this->getProductKilnAnalysis($date),
                'monthlyComparison' => $this->getMonthlyComparison($date),
                'aiInsights' => $this->generateAIInsights(Carbon::today('Europe/Istanbul')),
                'stockAgeAnalysis' => $this->getStockAgeAnalysis(),
            ];
            
            \Log::info('Dashboard data collected', ['dataKeys' => array_keys($dashboardData)]);
            
            // Excel export sınıfını kullan
            $export = new \App\Exports\DashboardExport($dashboardData);
            
            $filename = 'uretim_raporu_' . $period . '_' . $date->format('Y-m-d') . '.xlsx';
            
            \Log::info('Dashboard export completed', ['filename' => $filename]);
            
            return \Maatwebsite\Excel\Facades\Excel::download($export, $filename);
            
        } catch (\Exception $e) {
            \Log::error('Dashboard export error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            return response()->json([
                'success' => false,
                'error' => 'Dashboard export sırasında bir hata oluştu: ' . $e->getMessage()
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
        
        // Kabul edilen miktar (ton) - Sadece sevk onaylı
        $acceptedQuantity = DB::select('
            SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.status = ?
            AND barcodes.deleted_at IS NULL
        ', [$startDate, $endDate, Barcode::STATUS_SHIPMENT_APPROVED])[0]->total_quantity ?? 0;
        
        // Test sürecinde olan miktar (ton) - Beklemede, ön onaylı, kontrol tekrarı
        $testingQuantity = DB::select('
            SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.status IN (?, ?, ?)
            AND barcodes.deleted_at IS NULL
        ', [$startDate, $endDate, Barcode::STATUS_WAITING, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_CONTROL_REPEAT])[0]->total_quantity ?? 0;
        
        // Teslimat sürecinde olan miktar (ton) - Müşteri transfer ve teslim edildi
        $deliveryQuantity = DB::select('
            SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.status IN (?, ?)
            AND barcodes.deleted_at IS NULL
        ', [$startDate, $endDate, Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED])[0]->total_quantity ?? 0;
        
        // Reddedilen miktar (ton) - Reddedildi ve Birleştirildi statusundaki barkodlar dahil
        $rejectedQuantity = DB::select('
            SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.status IN (?, ?)
            AND barcodes.deleted_at IS NULL
        ', [$startDate, $endDate, Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED])[0]->total_quantity ?? 0;

        // Düzeltme faaliyeti ile oluşturulan üretim çıktısı (ton)
        $correctionOutputQuantity = DB::select('
            SELECT COALESCE(SUM(q.quantity), 0) as total_quantity
            FROM barcodes b
            LEFT JOIN quantities q ON q.id = b.quantity_id
            WHERE b.created_at BETWEEN ? AND ?
            AND IFNULL(b.is_correction, 0) = 1
            AND b.deleted_at IS NULL
        ', [$startDate, $endDate])[0]->total_quantity ?? 0;

        // Düzeltme faaliyeti kapsamında kullanılan red miktarı (ton)
        $correctionInputUsed = DB::select('
            SELECT COALESCE(SUM(b.correction_quantity), 0) as total_quantity
            FROM barcodes b
            WHERE b.created_at BETWEEN ? AND ?
            AND IFNULL(b.is_correction, 0) = 1
            AND b.deleted_at IS NULL
        ', [$startDate, $endDate])[0]->total_quantity ?? 0;

        // Düzeltmesiz üretim çıktısı (ton)
        $nonCorrectionOutputQuantity = DB::select('
            SELECT COALESCE(SUM(q.quantity), 0) as total_quantity
            FROM barcodes b
            LEFT JOIN quantities q ON q.id = b.quantity_id
            WHERE b.created_at BETWEEN ? AND ?
            AND IFNULL(b.is_correction, 0) = 0
            AND b.deleted_at IS NULL
        ', [$startDate, $endDate])[0]->total_quantity ?? 0;

        // Düzeltmeli üretimde kullanılan yeni hammadde (ton) = üretim çıktısı - kullanılan red miktarı
        $newMaterialUsedInCorrections = DB::select('
            SELECT COALESCE(SUM(GREATEST(q.quantity - COALESCE(b.correction_quantity, 0), 0)), 0) as total_quantity
            FROM barcodes b
            LEFT JOIN quantities q ON q.id = b.quantity_id
            WHERE b.created_at BETWEEN ? AND ?
            AND IFNULL(b.is_correction, 0) = 1
            AND b.deleted_at IS NULL
        ', [$startDate, $endDate])[0]->total_quantity ?? 0;

        // Toplam hammadde kullanımı (ton) = düzeltmesiz üretim + (düzeltmeli üretimde kullanılan yeni hammadde)
        $rawMaterialUsed = ($nonCorrectionOutputQuantity + $newMaterialUsedInCorrections);
        
        return [
            'total_barcodes' => Barcode::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_quantity' => DB::select('
                SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
                FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                WHERE barcodes.created_at BETWEEN ? AND ?
                AND barcodes.deleted_at IS NULL
            ', [$startDate, $endDate])[0]->total_quantity ?? 0,
            'accepted_quantity' => $acceptedQuantity,
            'testing_quantity' => $testingQuantity,
            'delivery_quantity' => $deliveryQuantity,
            'rejected_quantity' => $rejectedQuantity,
            // Düzeltme faaliyeti detayları
            'with_correction_output' => $correctionOutputQuantity,
            'without_correction_output' => $nonCorrectionOutputQuantity,
            'correction_input_used' => $correctionInputUsed,
            'raw_material_used' => $rawMaterialUsed,
            'accepted_barcodes' => Barcode::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', Barcode::STATUS_SHIPMENT_APPROVED)
                ->count(),
            'rejected_barcodes' => Barcode::whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('status', [Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED])
                ->count(),
            'testing_barcodes' => Barcode::whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('status', [Barcode::STATUS_WAITING, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_CONTROL_REPEAT])
                ->count(),
            'delivery_barcodes' => Barcode::whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('status', [Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED])
                ->count()
        ];
    }

    /**
     * Vardiya raporu (3 vardiya)
     */
    private function getShiftReport($date)
    {
        $shifts = [
            'gece' => ['start' => '00:00', 'end' => '08:00'],
            'gündüz' => ['start' => '08:00', 'end' => '16:00'],
            'akşam' => ['start' => '16:00', 'end' => '24:00']
        ];
        
        $shiftData = [];
        
        foreach ($shifts as $shiftName => $shiftTime) {
            $startTime = $date->copy()->setTimeFromTimeString($shiftTime['start']);
            
            // Vardiya bitiş zamanını hesapla
            if ($shiftTime['end'] === '24:00') {
                $endTime = $date->copy()->addDay()->setTimeFromTimeString('00:00');
            } else {
                $endTime = $date->copy()->setTimeFromTimeString($shiftTime['end']);
            }
            
            // Debug için log ekle
            \Log::info("Vardiya: {$shiftName}", [
                'date' => $date->format('Y-m-d'),
                'start_time' => $startTime->format('Y-m-d H:i:s'),
                'end_time' => $endTime->format('Y-m-d H:i:s')
            ]);
            
            // Vardiya için ton bazında veriler
            $acceptedQuantity = DB::select('
                SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
                FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                WHERE barcodes.created_at BETWEEN ? AND ?
                AND barcodes.status = ?
                AND barcodes.deleted_at IS NULL
            ', [$startTime, $endTime, Barcode::STATUS_SHIPMENT_APPROVED])[0]->total_quantity ?? 0;
            
            $rejectedQuantity = DB::select('
                SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
                FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                WHERE barcodes.created_at BETWEEN ? AND ?
                AND barcodes.status IN (?, ?)
                AND barcodes.deleted_at IS NULL
            ', [$startTime, $endTime, Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED])[0]->total_quantity ?? 0;
            
            // Test sürecinde olan miktar (ton)
            $testingQuantity = DB::select('
                SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
                FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                WHERE barcodes.created_at BETWEEN ? AND ?
                AND barcodes.status IN (?, ?, ?)
                AND barcodes.deleted_at IS NULL
            ', [$startTime, $endTime, Barcode::STATUS_WAITING, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_CONTROL_REPEAT])[0]->total_quantity ?? 0;
            
            // Teslimat sürecinde olan miktar (ton)
            $deliveryQuantity = DB::select('
                SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
                FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                WHERE barcodes.created_at BETWEEN ? AND ?
                AND barcodes.status IN (?, ?)
                AND barcodes.deleted_at IS NULL
            ', [$startTime, $endTime, Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED])[0]->total_quantity ?? 0;
            
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
                'testing_quantity' => $testingQuantity,
                'delivery_quantity' => $deliveryQuantity,
                'rejected_quantity' => $rejectedQuantity,
                'accepted_count' => Barcode::whereBetween('created_at', [$startTime, $endTime])
                    ->where('status', Barcode::STATUS_SHIPMENT_APPROVED)
                    ->count(),
                'rejected_count' => Barcode::whereBetween('created_at', [$startTime, $endTime])
                    ->whereIn('status', [Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED])
                    ->count(),
                'testing_count' => Barcode::whereBetween('created_at', [$startTime, $endTime])
                    ->whereIn('status', [Barcode::STATUS_WAITING, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_CONTROL_REPEAT])
                    ->count(),
                'delivery_count' => Barcode::whereBetween('created_at', [$startTime, $endTime])
                    ->whereIn('status', [Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED])
                    ->count()
            ];
        }
        
        return $shiftData;
    }

    /**
     * Fırın başına üretim performansı
     */
    private function getKilnPerformance($date, $period = 'daily', $periodInfo = null)
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
                COALESCE(SUM(CASE WHEN barcodes.status = ? THEN quantities.quantity ELSE 0 END), 0) as accepted_quantity,
                COALESCE(SUM(CASE WHEN barcodes.status IN (?, ?, ?) THEN quantities.quantity ELSE 0 END), 0) as testing_quantity,
                COALESCE(SUM(CASE WHEN barcodes.status IN (?, ?) THEN quantities.quantity ELSE 0 END), 0) as delivery_quantity,
                COALESCE(SUM(CASE WHEN barcodes.status IN (?, ?) THEN quantities.quantity ELSE 0 END), 0) as rejected_quantity,
                COUNT(CASE WHEN barcodes.status = ? THEN 1 END) as accepted_count,
                COUNT(CASE WHEN barcodes.status IN (?, ?, ?) THEN 1 END) as testing_count,
                COUNT(CASE WHEN barcodes.status IN (?, ?) THEN 1 END) as delivery_count,
                COUNT(CASE WHEN barcodes.status IN (?, ?) THEN 1 END) as rejected_count
            FROM kilns
            LEFT JOIN barcodes ON kilns.id = barcodes.kiln_id 
                AND barcodes.created_at BETWEEN ? AND ?
                AND barcodes.deleted_at IS NULL
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            GROUP BY kilns.id, kilns.name
        ', [
            Barcode::STATUS_SHIPMENT_APPROVED, // Kabul edildi: sevk onaylı
            Barcode::STATUS_WAITING, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_CONTROL_REPEAT, // Test süreci: beklemede, ön onaylı, kontrol tekrarı
            Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED, // Teslimat süreci: müşteri transfer, teslim edildi
            Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED, // Red: reddedildi ve birleştirildi
            Barcode::STATUS_SHIPMENT_APPROVED, // Kabul edildi: sevk onaylı (count için)
            Barcode::STATUS_WAITING, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_CONTROL_REPEAT, // Test süreci count için
            Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED, // Teslimat süreci count için
            Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED, // Red count için: reddedildi ve birleştirildi
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
    private function getKilnRejectionRates($date, $period = 'daily', $periodInfo = null)
    {
        $startDate = $date->copy()->startOfDay();
        $endDate = $date->copy()->endOfDay();
        
        return DB::select('
            SELECT 
                kilns.id,
                kilns.name as kiln_name,
                COUNT(barcodes.id) as total_barcodes,
                COALESCE(SUM(CASE WHEN barcodes.status IN (?, ?) THEN quantities.quantity ELSE 0 END), 0) as rejected_quantity,
                COUNT(CASE WHEN barcodes.status IN (?, ?) THEN 1 END) as rejected_count,
                ROUND(
                    (COUNT(CASE WHEN barcodes.status IN (?, ?) THEN 1 END) * 100.0 / COUNT(barcodes.id)), 2
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
            Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED,
            Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED,
            Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED,
            $startDate,
            $endDate
        ]);
    }

    /**
     * Ürün özelinde fırın kapasite analizi
     */
    private function getProductKilnAnalysis($date, $period = 'daily', $periodInfo = null)
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
                COALESCE(SUM(CASE WHEN barcodes.status = ? THEN quantities.quantity ELSE 0 END), 0) as accepted_quantity,
                COALESCE(SUM(CASE WHEN barcodes.status IN (?, ?, ?) THEN quantities.quantity ELSE 0 END), 0) as testing_quantity,
                COALESCE(SUM(CASE WHEN barcodes.status IN (?, ?) THEN quantities.quantity ELSE 0 END), 0) as delivery_quantity,
                COALESCE(SUM(CASE WHEN barcodes.status IN (?, ?) THEN quantities.quantity ELSE 0 END), 0) as rejected_quantity,
                COUNT(CASE WHEN barcodes.status = ? THEN 1 END) as accepted_count,
                COUNT(CASE WHEN barcodes.status IN (?, ?, ?) THEN 1 END) as testing_count,
                COUNT(CASE WHEN barcodes.status IN (?, ?) THEN 1 END) as delivery_count,
                COUNT(CASE WHEN barcodes.status IN (?, ?) THEN 1 END) as rejected_count,
                ROUND(
                    (COUNT(CASE WHEN barcodes.status = ? THEN 1 END) * 100.0 / COUNT(barcodes.id)), 2
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
            Barcode::STATUS_SHIPMENT_APPROVED, // Kabul edildi: sevk onaylı
            Barcode::STATUS_WAITING, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_CONTROL_REPEAT, // Test süreci: beklemede, ön onaylı, kontrol tekrarı
            Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED, // Teslimat süreci: müşteri transfer, teslim edildi
            Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED, // Red: reddedildi ve birleştirildi
            Barcode::STATUS_SHIPMENT_APPROVED, // Kabul edildi: sevk onaylı (count için)
            Barcode::STATUS_WAITING, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_CONTROL_REPEAT, // Test süreci count için
            Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED, // Teslimat süreci count için
            Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED, // Red count için: reddedildi ve birleştirildi
            Barcode::STATUS_SHIPMENT_APPROVED, // Kabul edildi: sevk onaylı (acceptance rate için)
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
                ->where('status', Barcode::STATUS_SHIPMENT_APPROVED)
                ->count(),
            'today_rejected' => Barcode::whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('status', [Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED])
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
                ->whereIn('status', [Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED])
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
            if (isset($previous[$key])) {
                if ($previous[$key] > 0) {
                    // Önceki ay verisi varsa normal hesaplama
                    $changes[$key] = round((($value - $previous[$key]) / $previous[$key]) * 100, 2);
                } elseif ($previous[$key] == 0 && $value > 0) {
                    // Önceki ay 0, bu ay veri varsa %100 artış
                    $changes[$key] = 100.00;
                } elseif ($previous[$key] == 0 && $value == 0) {
                    // Her iki ay da 0 ise değişim yok
                    $changes[$key] = 0.00;
                } else {
                    // Önceki ay veri var, bu ay 0 ise %100 düşüş
                    $changes[$key] = -100.00;
                }
            } else {
                $changes[$key] = 0.00;
            }
        }
        
        return $changes;
    }

    /**
     * Algoritmik Trend Analizleri üretimi
     */
    private function generateAIInsights($date)
    {
        // OEE ve Algoritmik Trend Analizleri her zaman güncel tarihe göre çalışsın
        // Tarih filtresinden etkilenmesin
        $currentDate = Carbon::today('Europe/Istanbul');
        
        // Production forecast based on historical data
        $productionForecast = $this->calculateProductionForecast($currentDate);
        
        // Quality risk assessment
        $qualityRisk = $this->assessQualityRisk($currentDate);
        
        // Anomaly detection
        $anomalies = $this->detectAnomalies($currentDate);
        
        // Optimization recommendations
        $recommendations = $this->generateRecommendations($currentDate);
        
        // Model status and accuracy
        $modelStatus = $this->getModelStatus();
        
        // Production efficiency analysis
        $productionEfficiency = $this->calculateProductionEfficiency($currentDate);
        
        // Quality prediction model
        $qualityPrediction = $this->predictQualityIssues($currentDate);
        
        // Process optimization insights
        $processInsights = $this->generateProcessInsights($currentDate);
        
        // Debug: Log model status
        \Log::info('Model Status Debug:', [
            'production_accuracy' => $modelStatus['accuracy']['production'],
            'quality_accuracy' => $modelStatus['accuracy']['quality'],
            'anomaly_accuracy' => $modelStatus['accuracy']['anomaly'],
            'full_model_status' => $modelStatus
        ]);
        
        return [
            'production_forecast' => $productionForecast['forecast'],
            'confidence_level' => $productionForecast['confidence'],
            'trend_direction' => $productionForecast['trend_direction'],
            'trend_percentage' => $productionForecast['trend_percentage'],
            'quality_risk_level' => $qualityRisk['risk_level'],
            'expected_rejection_rate' => $qualityRisk['expected_rejection_rate'],
            'quality_recommendation' => $qualityRisk['recommendation'],
            'anomalies' => $anomalies,
            'recommendations' => $recommendations,
            'model_status' => $modelStatus,
            'production_efficiency' => $productionEfficiency,
            'quality_prediction' => $qualityPrediction,
            'process_insights' => $processInsights
        ];
    }

    /**
     * Calculate production forecast for next 7 days
     */
    private function calculateProductionForecast($date)
    {
        // Get historical production data for the last 30 days
        $startDate = $date->copy()->subDays(30)->startOfDay();
        $endDate = $date->copy()->endOfDay();
        
        $historicalData = DB::select('
            SELECT 
                DATE(barcodes.created_at) as date,
                COALESCE(SUM(quantities.quantity), 0) as daily_production
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
            GROUP BY DATE(barcodes.created_at)
            ORDER BY date
        ', [$startDate, $endDate]);
        
        if (empty($historicalData)) {
            return [
                'forecast' => 0,
                'confidence' => 0,
                'trend_direction' => 'up',
                'trend_percentage' => 0
            ];
        }
        
        // Calculate average daily production
        $totalProduction = array_sum(array_column($historicalData, 'daily_production'));
        $daysCount = count($historicalData);
        $avgDailyProduction = $daysCount > 0 ? $totalProduction / $daysCount : 0;
        
        // Calculate trend (comparing last 7 days vs previous 7 days)
        $recentDays = array_slice($historicalData, -7);
        $previousDays = array_slice($historicalData, -14, 7);
        
        $recentAvg = !empty($recentDays) ? array_sum(array_column($recentDays, 'daily_production')) / count($recentDays) : 0;
        $previousAvg = !empty($previousDays) ? array_sum(array_column($previousDays, 'daily_production')) / count($previousDays) : 0;
        
        $trendDirection = $recentAvg >= $previousAvg ? 'up' : 'down';
        
        // Safety check: Prevent division by zero
        $trendPercentage = 0;
        if ($previousAvg > 0) {
            $trendPercentage = abs(($recentAvg - $previousAvg) / $previousAvg * 100);
        }
        
        // 7-day forecast
        $forecast = $avgDailyProduction * 7;
        
        // Confidence level based on data consistency
        $variance = $this->calculateVariance(array_column($historicalData, 'daily_production'));
        $confidence = max(60, min(95, 95 - ($variance / 10)));
        
        return [
            'forecast' => round($forecast, 1),
            'confidence' => round($confidence),
            'trend_direction' => $trendDirection,
            'trend_percentage' => round($trendPercentage, 1)
        ];
    }

    /**
     * Assess quality risk based on recent data
     */
    private function assessQualityRisk($date)
    {
        $startDate = $date->copy()->subDays(14)->startOfDay();
        $endDate = $date->copy()->endOfDay();
        
        $qualityData = DB::select('
            SELECT 
                COUNT(*) as total_barcodes,
                COUNT(CASE WHEN barcodes.status = ? THEN 1 END) as rejected_count
            FROM barcodes
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
        ', [Barcode::STATUS_REJECTED, $startDate, $endDate]);
        
        $totalBarcodes = $qualityData[0]->total_barcodes ?? 0;
        $rejectedCount = $qualityData[0]->rejected_count ?? 0;
        
        // Safety check: Prevent division by zero
        $currentRejectionRate = 0;
        if ($totalBarcodes > 0) {
            $currentRejectionRate = ($rejectedCount / $totalBarcodes) * 100;
        }
        
        // Predict future rejection rate using simple moving average
        $expectedRejectionRate = min(25, max(0, $currentRejectionRate * 1.1)); // Slight increase prediction
        
        // Determine risk level
        if ($expectedRejectionRate <= 5) {
            $riskLevel = 'low';
            $recommendation = 'Mevcut kalite kontrol prosedürlerine devam edin. Kalite metrikleri mükemmel.';
        } elseif ($expectedRejectionRate <= 15) {
            $riskLevel = 'medium';
            $recommendation = 'Kalite trendlerini yakından takip edin. Yüksek riskli ürünler için ek kalite kontrolleri düşünün.';
        } else {
            $riskLevel = 'high';
            $recommendation = 'Acil eylem gerekli. Kalite kontrol süreçlerini gözden geçirin ve kök nedenleri araştırın.';
        }
        
        return [
            'risk_level' => $riskLevel,
            'expected_rejection_rate' => round($expectedRejectionRate, 1),
            'recommendation' => $recommendation
        ];
    }

    /**
     * Detect anomalies in production data
     */
    private function detectAnomalies($date)
    {
        $anomalies = [];
        
        // Check for unusual production patterns
        $startDate = $date->copy()->subDays(7)->startOfDay();
        $endDate = $date->copy()->endOfDay();
        
        $productionData = DB::select('
            SELECT 
                DATE(barcodes.created_at) as date,
                COALESCE(SUM(quantities.quantity), 0) as daily_production
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
            GROUP BY DATE(barcodes.created_at)
            ORDER BY date
        ', [$startDate, $endDate]);
        
        if (count($productionData) >= 3) {
            $productions = array_column($productionData, 'daily_production');
            $mean = array_sum($productions) / count($productions);
            $variance = $this->calculateVariance($productions);
            $stdDev = sqrt($variance);
            
            foreach ($productions as $index => $production) {
                $zScore = abs(($production - $mean) / $stdDev);
                if ($zScore > 2.5 && $stdDev > 0) { // Statistical anomaly threshold
                    $anomalies[] = [
                        'type' => 'Üretim Anomalisi',
                        'description' => $productionData[$index]->date . ' tarihinde olağandışı üretim hacmi tespit edildi',
                        'severity' => $zScore > 3.5 ? 'high' : 'medium'
                    ];
                }
            }
        }
        
        // Check for quality anomalies
        $qualityAnomaly = $this->checkQualityAnomaly($date);
        if ($qualityAnomaly) {
            $anomalies[] = $qualityAnomaly;
        }
        
        return $anomalies;
    }

    /**
     * Generate optimization recommendations
     */
    private function generateRecommendations($date)
    {
        $recommendations = [];
        
        // Get production efficiency data
        $productionEfficiency = $this->calculateProductionEfficiency($date);
        if ($productionEfficiency['oee_score'] < 70) {
            $recommendations[] = [
                'category' => 'Üretim Verimliliği',
                'text' => 'OEE skoru %' . $productionEfficiency['oee_score'] . ' - Üretim verimliliğini artırmak için vardiya programlarını ve ekipman bakımını optimize etmeyi düşünün.',
                'impact' => 'medium'
            ];
        }
        
        // Get quality prediction data
        $qualityPrediction = $this->predictQualityIssues($date);
        if ($qualityPrediction['overall_trend'] === 'increasing') {
            $recommendations[] = [
                'category' => 'Kalite Kontrol',
                'text' => 'Kalite trendi artıyor (%' . $qualityPrediction['trend_percentage'] . ') - Ek kalite kontrol noktaları uygulayın ve hammadde kalite standartlarını gözden geçirin.',
                'impact' => 'high'
            ];
        }
        
        // Get process insights data
        $processInsights = $this->generateProcessInsights($date);
        if ($processInsights['correction_efficiency'] > 20) {
            $recommendations[] = [
                'category' => 'Düzeltme Faaliyeti',
                'text' => 'Düzeltme oranı %' . $processInsights['correction_efficiency'] . ' - Kalite kontrol süreçlerini iyileştir, hataları kaynağında önle.',
                'impact' => 'medium'
            ];
        }
        
        return $recommendations;
    }

    /**
     * Get ML model status and accuracy based on real data
     */
    private function getModelStatus()
    {
        // Production model accuracy based on forecast vs actual production
        $productionAccuracy = $this->calculateProductionModelAccuracy();
        
        // Quality model accuracy based on predicted vs actual rejection rates
        $qualityAccuracy = $this->calculateQualityModelAccuracy();
        
        // Anomaly detection accuracy based on detected vs actual anomalies
        $anomalyAccuracy = $this->calculateAnomalyModelAccuracy();
        
        return [
            'status' => [
                'production' => $productionAccuracy >= 70 ? 'active' : 'inactive',
                'quality' => $qualityAccuracy >= 70 ? 'active' : 'inactive',
                'anomaly' => $anomalyAccuracy >= 70 ? 'active' : 'inactive'
            ],
            'accuracy' => [
                'production' => $productionAccuracy,
                'quality' => $qualityAccuracy,
                'anomaly' => $anomalyAccuracy
            ]
        ];
    }

    /**
     * Helper method to calculate variance
     */
    private function calculateVariance($data)
    {
        if (empty($data) || count($data) < 2) return 0;
        
        $mean = array_sum($data) / count($data);
        $variance = 0;
        
        foreach ($data as $value) {
            $variance += pow($value - $mean, 2);
        }
        
        // Safety check: Prevent division by zero
        $count = count($data);
        if ($count > 0) {
            return $variance / $count;
        }
        
        return 0;
    }

    /**
     * Check for quality anomalies
     */
    private function checkQualityAnomaly($date)
    {
        $startDate = $date->copy()->subDays(3)->startOfDay();
        $endDate = $date->copy()->endOfDay();
        
        $recentRejectionRate = DB::select('
            SELECT 
                COUNT(*) as total_barcodes,
                COUNT(CASE WHEN barcodes.status IN (?, ?) THEN 1 END) as rejected_count
            FROM barcodes
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
        ', [Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED, $startDate, $endDate]);
        
        $total = $recentRejectionRate[0]->total_barcodes ?? 0;
        $rejected = $recentRejectionRate[0]->rejected_count ?? 0;
        
        // Safety check: Prevent division by zero
        if ($total > 0 && ($rejected / $total) > 0.2) { // 20% rejection rate threshold
            return [
                'type' => 'Kalite Anomalisi',
                'description' => 'Son üretimde yüksek red oranı tespit edildi. Acil araştırma önerilir.',
                'severity' => 'high'
            ];
        }
        
        return null;
    }

    /**
     * Calculate comprehensive production efficiency (OEE)
     */
    private function calculateProductionEfficiency($date)
    {
        // Get last 30 days of production data
        $startDate = $date->copy()->subDays(30)->startOfDay();
        $endDate = $date->copy()->endOfDay();
        
        // Calculate Availability (Makinelerin çalışma süresi)
        $availabilityData = DB::select('
            SELECT 
                COUNT(*) as total_barcodes,
                COUNT(CASE WHEN barcodes.status IN (?, ?, ?, ?, ?) THEN 1 END) as active_barcodes,
                COUNT(CASE WHEN barcodes.status = ? THEN 1 END) as rejected_barcodes,
                COUNT(CASE WHEN barcodes.status = ? THEN 1 END) as merged_barcodes
            FROM barcodes
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
        ', [
            Barcode::STATUS_WAITING, 
            Barcode::STATUS_CONTROL_REPEAT, 
            Barcode::STATUS_PRE_APPROVED, 
            Barcode::STATUS_SHIPMENT_APPROVED,
            Barcode::STATUS_CUSTOMER_TRANSFER,
            Barcode::STATUS_REJECTED,
            Barcode::STATUS_MERGED,
            $startDate, 
            $endDate
        ]);
        
        $totalBarcodes = $availabilityData[0]->total_barcodes ?? 0;
        $activeBarcodes = $availabilityData[0]->active_barcodes ?? 0;
        $rejectedBarcodes = $availabilityData[0]->rejected_barcodes ?? 0;
        $mergedBarcodes = $availabilityData[0]->merged_barcodes ?? 0;
        
        // Safety check: If no data, return default values
        if ($totalBarcodes == 0) {
            return [
                'oee_score' => 0,
                'availability' => 0,
                'performance' => 0,
                'quality_rate' => 0,
                'level' => 'critical',
                'total_barcodes' => 0,
                'active_barcodes' => 0,
                'rejected_barcodes' => 0,
                'merged_barcodes' => 0,
                'avg_quantity' => 0
            ];
        }
        
        // Availability = Aktif süre / Toplam süre
        $availability = ($activeBarcodes / $totalBarcodes) * 100;
        
        // Performance = Standart üretim hızı vs gerçek üretim hızı
        $performanceData = DB::select('
            SELECT 
                AVG(quantities.quantity) as avg_quantity,
                COUNT(*) as barcode_count
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
            AND barcodes.status IN (?, ?, ?, ?, ?)
        ', [
            $startDate, 
            $endDate,
            Barcode::STATUS_WAITING, 
            Barcode::STATUS_CONTROL_REPEAT, 
            Barcode::STATUS_PRE_APPROVED, 
            Barcode::STATUS_SHIPMENT_APPROVED,
            Barcode::STATUS_CUSTOMER_TRANSFER
        ]);
        
        $avgQuantity = $performanceData[0]->avg_quantity ?? 0;
        $barcodeCount = $performanceData[0]->barcode_count ?? 0;
        
        // Assume standard production rate (can be configurable)
        $standardRate = 1000; // KG per barcode (standard)
        $performance = $avgQuantity > 0 ? min(100, ($avgQuantity / $standardRate) * 100) : 0;
        
        // Quality = Kaliteli ürün / Toplam ürün
        $quality = (($totalBarcodes - $rejectedBarcodes - $mergedBarcodes) / $totalBarcodes) * 100;
        
        // Overall Equipment Effectiveness (OEE)
        $oee = ($availability * $performance * $quality) / 10000;
        
        // Efficiency score (0-100)
        $efficiencyScore = round($oee, 2);
        
        // Efficiency level
        $efficiencyLevel = $this->getEfficiencyLevel($efficiencyScore);
        
        return [
            'oee_score' => $efficiencyScore,
            'availability' => round($availability, 2),
            'performance' => round($performance, 2),
            'quality_rate' => round($quality, 2),
            'level' => $efficiencyLevel,
            'total_barcodes' => $totalBarcodes,
            'active_barcodes' => $activeBarcodes,
            'rejected_barcodes' => $rejectedBarcodes,
            'merged_barcodes' => $mergedBarcodes,
            'avg_quantity' => round($avgQuantity, 2)
        ];
    }
    
    /**
     * Get efficiency level based on OEE score
     */
    private function getEfficiencyLevel($oeeScore)
    {
        if ($oeeScore >= 90) return 'excellent';
        if ($oeeScore >= 80) return 'good';
        if ($oeeScore >= 70) return 'average';
        if ($oeeScore >= 60) return 'poor';
        return 'critical';
    }

    /**
     * Predict quality issues using historical data analysis
     */
    private function predictQualityIssues($date)
    {
        // Get last 60 days of quality data for pattern analysis
        $startDate = $date->copy()->subDays(60)->startOfDay();
        $endDate = $date->copy()->endOfDay();
        
        // Analyze quality patterns by stock type
        $qualityByStock = DB::select('
            SELECT 
                stocks.name as stock_name,
                stocks.code as stock_code,
                COUNT(*) as total_barcodes,
                COUNT(CASE WHEN barcodes.status IN (?, ?) THEN 1 END) as rejected_count,
                AVG(quantities.quantity) as avg_quantity,
                COUNT(CASE WHEN barcodes.status = ? THEN 1 END) as correction_count
            FROM barcodes
            LEFT JOIN stocks ON stocks.id = barcodes.stock_id
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
            GROUP BY stocks.id, stocks.name, stocks.code
            HAVING total_barcodes >= 5
            ORDER BY rejected_count DESC
        ', [
            Barcode::STATUS_REJECTED, 
            Barcode::STATUS_MERGED,
            Barcode::STATUS_CORRECTED,
            $startDate, 
            $endDate
        ]);
        
        // Analyze quality patterns by kiln
        $qualityByKiln = DB::select('
            SELECT 
                kilns.name as kiln_name,
                COUNT(*) as total_barcodes,
                COUNT(CASE WHEN barcodes.status IN (?, ?) THEN 1 END) as rejected_count,
                AVG(quantities.quantity) as avg_quantity
            FROM barcodes
            LEFT JOIN kilns ON kilns.id = barcodes.kiln_id
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
            GROUP BY kilns.id, kilns.name
            HAVING total_barcodes >= 5
            ORDER BY rejected_count DESC
        ', [
            Barcode::STATUS_REJECTED, 
            Barcode::STATUS_MERGED,
            $startDate, 
            $endDate
        ]);
        
        // Calculate overall quality trends
        $qualityTrends = DB::select('
            SELECT 
                DATE(barcodes.created_at) as date,
                COUNT(*) as total_barcodes,
                COUNT(CASE WHEN barcodes.status IN (?, ?) THEN 1 END) as rejected_count
            FROM barcodes
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
            GROUP BY DATE(barcodes.created_at)
            ORDER BY date
        ', [
            Barcode::STATUS_REJECTED, 
            Barcode::STATUS_MERGED,
            $startDate, 
            $endDate
        ]);
        
        // Calculate trend direction
        $trendDirection = 'stable';
        $trendPercentage = 0;
        
        if (count($qualityTrends) >= 14) {
            $recentWeek = array_slice($qualityTrends, -7);
            $previousWeek = array_slice($qualityTrends, -14, 7);
            
            $recentTotal = array_sum(array_column($recentWeek, 'total_barcodes'));
            $previousTotal = array_sum(array_column($previousWeek, 'total_barcodes'));
            
            // Safety check: Prevent division by zero
            $recentRejectionRate = 0;
            $previousRejectionRate = 0;
            
            if ($recentTotal > 0) {
                $recentRejectionRate = array_sum(array_column($recentWeek, 'rejected_count')) / $recentTotal * 100;
            }
            
            if ($previousTotal > 0) {
                $previousRejectionRate = array_sum(array_column($previousWeek, 'rejected_count')) / $previousTotal * 100;
            }
            
            if ($previousRejectionRate > 0) {
                $trendPercentage = (($recentRejectionRate - $previousRejectionRate) / $previousRejectionRate) * 100;
                
                if ($trendPercentage > 5) {
                    $trendDirection = 'increasing';
                } elseif ($trendPercentage < -5) {
                    $trendDirection = 'decreasing';
                }
            }
        }
        
        // Identify high-risk stocks and kilns
        $highRiskStocks = array_filter($qualityByStock, function($stock) {
            $rejectionRate = $stock->total_barcodes > 0 ? ($stock->rejected_count / $stock->total_barcodes) * 100 : 0;
            return $rejectionRate > 15; // %15'ten fazla red oranı
        });
        
        $highRiskKilns = array_filter($qualityByKiln, function($kiln) {
            $rejectionRate = $kiln->total_barcodes > 0 ? ($kiln->rejected_count / $kiln->total_barcodes) * 100 : 0;
            return $rejectionRate > 15; // %15'ten fazla red oranı
        });
        
        return [
            'overall_trend' => $trendDirection,
            'trend_percentage' => round($trendPercentage, 2),
            'high_risk_stocks' => array_values($highRiskStocks),
            'high_risk_kilns' => array_values($highRiskKilns),
            'quality_by_stock' => $qualityByStock,
            'quality_by_kiln' => $qualityByKiln,
            'total_analysis_period' => 60,
            'prediction_confidence' => $this->calculateQualityPredictionConfidence($qualityTrends)
        ];
    }
    
    /**
     * Calculate quality prediction confidence
     */
    private function calculateQualityPredictionConfidence($qualityTrends)
    {
        if (count($qualityTrends) < 10) {
            return 60; // Low confidence with limited data
        }
        
        // Calculate data consistency
        $rejectionRates = [];
        foreach ($qualityTrends as $trend) {
            if ($trend->total_barcodes > 0) {
                $rejectionRates[] = ($trend->rejected_count / $trend->total_barcodes) * 100;
            }
        }
        
        if (empty($rejectionRates)) {
            return 60;
        }
        
        // Lower variance = higher confidence
        $variance = $this->calculateVariance($rejectionRates);
        $confidence = max(60, min(95, 95 - ($variance / 2)));
        
        return round($confidence);
    }

    /**
     * Generate process optimization insights
     */
    private function generateProcessInsights($date)
    {
        // Get last 30 days of process data
        $startDate = $date->copy()->subDays(30)->startOfDay();
        $endDate = $date->copy()->endOfDay();
        
        // Analyze process bottlenecks
        $processBottlenecks = DB::select('
            SELECT 
                barcodes.status,
                COUNT(*) as barcode_count,
                AVG(TIMESTAMPDIFF(HOUR, barcodes.created_at, COALESCE(barcodes.lab_at, NOW()))) as avg_lab_time,
                AVG(TIMESTAMPDIFF(HOUR, barcodes.lab_at, COALESCE(barcodes.warehouse_transferred_at, NOW()))) as avg_warehouse_time,
                AVG(TIMESTAMPDIFF(HOUR, barcodes.warehouse_transferred_at, COALESCE(barcodes.company_transferred_at, NOW()))) as avg_delivery_time
            FROM barcodes
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
            GROUP BY barcodes.status
            HAVING barcode_count >= 3
        ', [$startDate, $endDate]);
        
        // Analyze correction activity patterns
        $correctionPatterns = DB::select('
            SELECT 
                DATE(barcodes.created_at) as date,
                COUNT(*) as total_barcodes,
                COUNT(CASE WHEN barcodes.is_correction = 1 THEN 1 END) as correction_barcodes,
                SUM(CASE WHEN barcodes.is_correction = 1 THEN quantities.quantity ELSE 0 END) as correction_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
            GROUP BY DATE(barcodes.created_at)
            ORDER BY date
        ', [$startDate, $endDate]);
        
        // Calculate correction efficiency
        $totalCorrections = array_sum(array_column($correctionPatterns, 'correction_barcodes'));
        $totalBarcodes = array_sum(array_column($correctionPatterns, 'total_barcodes'));
        $correctionEfficiency = $totalBarcodes > 0 ? ($totalCorrections / $totalBarcodes) * 100 : 0;
        
        // Analyze merge patterns
        $mergePatterns = DB::select('
            SELECT 
                DATE(barcodes.created_at) as date,
                COUNT(*) as total_barcodes,
                COUNT(CASE WHEN barcodes.is_merged = 1 THEN 1 END) as merged_barcodes
            FROM barcodes
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
            GROUP BY DATE(barcodes.created_at)
            ORDER BY date
        ', [$startDate, $endDate]);
        
        // Calculate merge efficiency
        $totalMerges = array_sum(array_column($mergePatterns, 'merged_barcodes'));
        $mergeEfficiency = $totalBarcodes > 0 ? ($totalMerges / $totalBarcodes) * 100 : 0;
        
        // Generate optimization recommendations
        $recommendations = [];
        
        // Lab process optimization
        $avgLabTime = 0;
        if (!empty($processBottlenecks)) {
            $avgLabTime = array_sum(array_column($processBottlenecks, 'avg_lab_time')) / count($processBottlenecks);
        }
        
        if ($avgLabTime > 24) {
            $recommendations[] = [
                'category' => 'Laboratuvar Süreci',
                'issue' => 'Ortalama lab süresi ' . round($avgLabTime, 1) . ' saat',
                'recommendation' => 'Lab personeli artırımı veya süreç optimizasyonu gerekli',
                'impact' => 'high',
                'estimated_improvement' => 'Lab süresini %30 azaltabilir'
            ];
        }
        
        // Correction activity optimization
        if ($correctionEfficiency > 20) {
            $recommendations[] = [
                'category' => 'Düzeltme Faaliyeti',
                'issue' => 'Düzeltme oranı %' . round($correctionEfficiency, 1),
                'recommendation' => 'Kalite kontrol süreçlerini iyileştir, hataları kaynağında önle',
                'impact' => 'medium',
                'estimated_improvement' => 'Düzeltme oranını %50 azaltabilir'
            ];
        }
        
        // Merge activity optimization
        if ($mergeEfficiency > 15) {
            $recommendations[] = [
                'category' => 'Birleştirme Faaliyeti',
                'issue' => 'Birleştirme oranı %' . round($mergeEfficiency, 1),
                'recommendation' => 'Üretim planlamasını optimize et, küçük partileri birleştir',
                'impact' => 'low',
                'estimated_improvement' => 'Birleştirme oranını %30 azaltabilir'
            ];
        }
        
        return [
            'process_bottlenecks' => $processBottlenecks,
            'correction_patterns' => $correctionPatterns,
            'merge_patterns' => $mergePatterns,
            'correction_efficiency' => round($correctionEfficiency, 2),
            'merge_efficiency' => round($mergeEfficiency, 2),
            'avg_lab_time' => round($avgLabTime, 1),
            'optimization_recommendations' => $recommendations,
            'analysis_period' => 30
        ];
    }

    /**
     * Calculate production model accuracy based on forecast vs actual production
     */
    private function calculateProductionModelAccuracy()
    {
        // Get last 7 days of production data to compare with forecasts
        $endDate = now()->endOfDay();
        $startDate = $endDate->copy()->subDays(7)->startOfDay();
        
        // Get actual production data
        $actualProduction = DB::select('
            SELECT 
                DATE(barcodes.created_at) as date,
                COALESCE(SUM(quantities.quantity), 0) as daily_production
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
            GROUP BY DATE(barcodes.created_at)
            ORDER BY date
        ', [$startDate, $endDate]);
        
        if (empty($actualProduction)) {
            return 75; // Default accuracy if no data
        }
        
        // Calculate total actual production
        $totalActual = array_sum(array_column($actualProduction, 'daily_production'));
        
        // Get forecast for the same period (using historical data from 7 days before)
        $forecastStartDate = $startDate->copy()->subDays(7);
        $forecastEndDate = $startDate->copy()->subDay();
        
        $historicalData = DB::select('
            SELECT 
                DATE(barcodes.created_at) as date,
                COALESCE(SUM(quantities.quantity), 0) as daily_production
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
            GROUP BY DATE(barcodes.created_at)
            ORDER BY date
        ', [$forecastStartDate, $forecastEndDate]);
        
        if (empty($historicalData)) {
            return 75; // Default accuracy if no historical data
        }
        
        // Calculate forecast based on historical average
        $historicalAvg = array_sum(array_column($historicalData, 'daily_production')) / count($historicalData);
        $forecast = $historicalAvg * 7;
        
        // Calculate accuracy based on forecast vs actual
        if ($forecast > 0) {
            $accuracy = max(0, min(100, 100 - (abs($forecast - $totalActual) / $forecast * 100)));
        } else {
            $accuracy = 75;
        }
        
        return round($accuracy);
    }
    
    /**
     * Calculate quality model accuracy based on predicted vs actual rejection rates
     */
    private function calculateQualityModelAccuracy()
    {
        // Get last 14 days of quality data
        $endDate = now()->endOfDay();
        $startDate = $endDate->copy()->subDays(14)->startOfDay();
        
        // Get actual rejection rates
        $actualQuality = DB::select('
            SELECT 
                DATE(barcodes.created_at) as date,
                COUNT(*) as total_barcodes,
                COUNT(CASE WHEN barcodes.status IN (?, ?) THEN 1 END) as rejected_count
            FROM barcodes
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
            GROUP BY DATE(barcodes.created_at)
            ORDER BY date
        ', [Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED, $startDate, $endDate]);
        
        if (empty($actualQuality)) {
            return 80; // Default accuracy if no data
        }
        
        // Calculate average actual rejection rate
        $totalActual = array_sum(array_column($actualQuality, 'total_barcodes'));
        $totalRejected = array_sum(array_column($actualQuality, 'rejected_count'));
        $actualRejectionRate = $totalActual > 0 ? ($totalRejected / $totalActual) * 100 : 0;
        
        // Get historical rejection rate from previous 14 days for comparison
        $historicalStartDate = $startDate->copy()->subDays(14);
        $historicalEndDate = $startDate->copy()->subDay();
        
        $historicalQuality = DB::select('
            SELECT 
                COUNT(*) as total_barcodes,
                COUNT(CASE WHEN barcodes.status IN (?, ?) THEN 1 END) as rejected_count
            FROM barcodes
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
        ', [Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED, $historicalStartDate, $historicalEndDate]);
        
        if (empty($historicalQuality)) {
            return 80; // Default accuracy if no historical data
        }
        
        $historicalTotal = $historicalQuality[0]->total_barcodes ?? 0;
        $historicalRejected = $historicalQuality[0]->rejected_count ?? 0;
        $historicalRejectionRate = $historicalTotal > 0 ? ($historicalRejected / $historicalTotal) * 100 : 0;
        
        // Calculate accuracy based on prediction stability
        $accuracy = 80; // Default accuracy
        if ($historicalRejectionRate > 0) {
            $accuracy = max(0, min(100, 100 - (abs($actualRejectionRate - $historicalRejectionRate) / $historicalRejectionRate * 100)));
        }
        
        return round($accuracy);
    }
    
    /**
     * Calculate anomaly detection model accuracy
     */
    private function calculateAnomalyModelAccuracy()
    {
        // Get last 30 days of data to analyze anomalies
        $endDate = now()->endOfDay();
        $startDate = $endDate->copy()->subDays(30)->startOfDay();
        
        // Get production data with potential anomalies
        $productionData = DB::select('
            SELECT 
                DATE(barcodes.created_at) as date,
                COALESCE(SUM(quantities.quantity), 0) as daily_production,
                COUNT(*) as barcode_count
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
            GROUP BY DATE(barcodes.created_at)
            ORDER BY date
        ', [$startDate, $endDate]);
        
        if (empty($productionData)) {
            return 85; // Default accuracy if no data
        }
        
        // Calculate daily production averages and standard deviation
        $dailyProductions = array_column($productionData, 'daily_production');
        $mean = array_sum($dailyProductions) / count($dailyProductions);
        $variance = $this->calculateVariance($dailyProductions);
        $stdDev = sqrt($variance);
        
        // Safety check: Prevent division by zero or invalid calculations
        if ($stdDev <= 0 || is_nan($stdDev) || is_infinite($stdDev)) {
            $stdDev = 1; // Default to 1 if calculation fails
        }
        
        // Define anomaly threshold (2 standard deviations from mean)
        $threshold = 2 * $stdDev;
        
        // Count actual anomalies (days with production outside threshold)
        $actualAnomalies = 0;
        foreach ($dailyProductions as $production) {
            if (abs($production - $mean) > $threshold) {
                $actualAnomalies++;
            }
        }
        
        // Calculate anomaly rate
        $anomalyRate = count($dailyProductions) > 0 ? ($actualAnomalies / count($dailyProductions)) * 100 : 0;
        
        // Model accuracy based on reasonable anomaly detection (not too many false positives)
        // Ideal anomaly rate should be between 5-15% for production data
        if ($anomalyRate >= 5 && $anomalyRate <= 15) {
            $accuracy = 90; // High accuracy for reasonable anomaly rate
        } elseif ($anomalyRate > 15) {
            $accuracy = max(60, 90 - ($anomalyRate - 15)); // Decreasing accuracy for too many anomalies
        } else {
            $accuracy = max(70, 90 - (5 - $anomalyRate)); // Decreasing accuracy for too few anomalies
        }
        
        return round($accuracy);
    }
    
    /**
     * Stok bilgilerini getir
     */
    private function getStockInfo()
    {
        return [
            'total_stocks' => Stock::count(),
            'total_quantity' => DB::select('
                SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
                FROM stocks
                LEFT JOIN barcodes ON stocks.id = barcodes.stock_id
                LEFT JOIN quantities ON barcodes.quantity_id = quantities.id
                WHERE barcodes.deleted_at IS NULL
            ')[0]->total_quantity ?? 0,
            'average_stock_age' => DB::select('
                SELECT AVG(days_old) as avg_days_old
                FROM (
                    SELECT DATEDIFF(NOW(), MAX(barcodes.created_at)) as days_old
                    FROM stocks
                    LEFT JOIN barcodes ON stocks.id = barcodes.stock_id
                    WHERE barcodes.deleted_at IS NULL
                    GROUP BY stocks.id
                ) as stock_ages
            ')[0]->avg_days_old ?? 0,
            'critical_stock_count' => DB::select('
                SELECT COUNT(*) as count
                FROM stocks
                LEFT JOIN barcodes ON stocks.id = barcodes.stock_id
                WHERE barcodes.created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)
                AND barcodes.deleted_at IS NULL
            ')[0]->count ?? 0,
            'warning_stock_count' => DB::select('
                SELECT COUNT(*) as count
                FROM stocks
                LEFT JOIN barcodes ON stocks.id = barcodes.stock_id
                WHERE barcodes.created_at < DATE_SUB(NOW(), INTERVAL 15 DAY)
                AND barcodes.deleted_at IS NULL
            ')[0]->count ?? 0,
            'info_stock_count' => DB::select('
                SELECT COUNT(*) as count
                FROM stocks
                LEFT JOIN barcodes ON stocks.id = barcodes.stock_id
                WHERE barcodes.created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)
                AND barcodes.deleted_at IS NULL
            ')[0]->count ?? 0
        ];
    }

    /**
     * Depo bilgilerini getir
     */
    private function getWarehouseInfo()
    {
        return [
            'total_warehouses' => Warehouse::count(),
            'total_barcodes' => DB::select('
                SELECT COUNT(DISTINCT barcodes.id) as total_barcodes
                FROM barcodes
                LEFT JOIN quantities ON barcodes.quantity_id = quantities.id
                WHERE barcodes.deleted_at IS NULL
            ')[0]->total_barcodes ?? 0,
            'total_quantity' => DB::select('
                SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
                FROM warehouses
                LEFT JOIN barcodes ON warehouses.id = barcodes.warehouse_id
                LEFT JOIN quantities ON barcodes.quantity_id = quantities.id
                WHERE barcodes.deleted_at IS NULL
            ')[0]->total_quantity ?? 0,
            'average_warehouse_age' => DB::select('
                SELECT AVG(days_old) as avg_days_old
                FROM (
                    SELECT DATEDIFF(NOW(), MAX(barcodes.created_at)) as days_old
                    FROM warehouses
                    LEFT JOIN barcodes ON warehouses.id = barcodes.warehouse_id
                    WHERE barcodes.deleted_at IS NULL
                    GROUP BY warehouses.id
                ) as warehouse_ages
            ')[0]->avg_days_old ?? 0,
            'critical_warehouse_count' => DB::select('
                SELECT COUNT(*) as count
                FROM warehouses
                LEFT JOIN barcodes ON warehouses.id = barcodes.warehouse_id
                WHERE barcodes.created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)
                AND barcodes.deleted_at IS NULL
            ')[0]->count ?? 0,
            'warning_warehouse_count' => DB::select('
                SELECT COUNT(*) as count
                FROM warehouses
                LEFT JOIN barcodes ON warehouses.id = barcodes.warehouse_id
                WHERE barcodes.created_at < DATE_SUB(NOW(), INTERVAL 15 DAY)
                AND barcodes.deleted_at IS NULL
            ')[0]->count ?? 0,
            'info_warehouse_count' => DB::select('
                SELECT COUNT(*) as count
                FROM warehouses
                LEFT JOIN barcodes ON warehouses.id = barcodes.warehouse_id
                WHERE barcodes.created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)
                AND barcodes.deleted_at IS NULL
            ')[0]->count ?? 0
        ];
    }

    /**
     * Fırın bilgilerini getir
     */
    private function getKilnInfo()
    {
        return [
            'total_kilns' => Kiln::count(),
            'total_barcodes' => DB::select('
                SELECT COUNT(DISTINCT barcodes.id) as total_barcodes
                FROM barcodes
                LEFT JOIN quantities ON barcodes.quantity_id = quantities.id
                WHERE barcodes.deleted_at IS NULL
            ')[0]->total_barcodes ?? 0,
            'total_quantity' => DB::select('
                SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
                FROM kilns
                LEFT JOIN barcodes ON kilns.id = barcodes.kiln_id
                LEFT JOIN quantities ON barcodes.quantity_id = quantities.id
                WHERE barcodes.deleted_at IS NULL
            ')[0]->total_quantity ?? 0,
            'average_kiln_age' => DB::select('
                SELECT AVG(days_old) as avg_days_old
                FROM (
                    SELECT DATEDIFF(NOW(), MAX(barcodes.created_at)) as days_old
                    FROM kilns
                    LEFT JOIN barcodes ON kilns.id = barcodes.kiln_id
                    WHERE barcodes.deleted_at IS NULL
                    GROUP BY kilns.id
                ) as kiln_ages
            ')[0]->avg_days_old ?? 0,
            'critical_kiln_count' => DB::select('
                SELECT COUNT(*) as count
                FROM kilns
                LEFT JOIN barcodes ON kilns.id = barcodes.kiln_id
                WHERE barcodes.created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)
                AND barcodes.deleted_at IS NULL
            ')[0]->count ?? 0,
            'warning_kiln_count' => DB::select('
                SELECT COUNT(*) as count
                FROM kilns
                LEFT JOIN barcodes ON kilns.id = barcodes.kiln_id
                WHERE barcodes.created_at < DATE_SUB(NOW(), INTERVAL 15 DAY)
                AND barcodes.deleted_at IS NULL
            ')[0]->count ?? 0,
            'info_kiln_count' => DB::select('
                SELECT COUNT(*) as count
                FROM kilns
                LEFT JOIN barcodes ON kilns.id = barcodes.kiln_id
                WHERE barcodes.created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)
                AND barcodes.deleted_at IS NULL
            ')[0]->count ?? 0
        ];
    }

    /**
     * Stok yaşı analizi
     */
    private function getStockAgeAnalysis()
    {
        $currentDate = Carbon::now();
        
        // Tüm barkodları yaşlarına göre analiz et
        $stockAgeData = DB::select('
            SELECT 
                barcodes.id,
                barcodes.id as barcode,
                barcodes.status,
                barcodes.created_at,
                barcodes.updated_at,
                barcodes.lab_at,
                barcodes.warehouse_transferred_at as shipment_at,
                stocks.name as stock_name,
                stocks.code as stock_code,
                quantities.quantity,
                companies.name as company_name,
                warehouses.name as warehouse_name,
                kilns.name as kiln_name,
                DATEDIFF(?, barcodes.created_at) as days_old,
                DATEDIFF(?, COALESCE(barcodes.lab_at, barcodes.created_at)) as days_since_lab,
                DATEDIFF(?, COALESCE(barcodes.warehouse_transferred_at, barcodes.created_at)) as days_since_shipment,
                DATEDIFF(?, barcodes.updated_at) as days_since_update
            FROM barcodes
            LEFT JOIN stocks ON barcodes.stock_id = stocks.id
            LEFT JOIN quantities ON barcodes.quantity_id = quantities.id
            LEFT JOIN companies ON barcodes.company_id = companies.id
            LEFT JOIN warehouses ON barcodes.warehouse_id = warehouses.id
            LEFT JOIN kilns ON barcodes.kiln_id = kilns.id
            WHERE barcodes.deleted_at IS NULL
            AND barcodes.status NOT IN (?, ?, ?, ?)
            ORDER BY days_old DESC, quantities.quantity DESC
        ', [
            $currentDate,
            $currentDate,
            $currentDate,
            $currentDate,
            Barcode::STATUS_CUSTOMER_TRANSFER,
            Barcode::STATUS_DELIVERED,
            Barcode::STATUS_REJECTED,
            Barcode::STATUS_CORRECTED
        ]);
        
        // Yaş kategorilerine göre grupla
        $ageCategories = [
            'critical' => ['min' => 30, 'max' => null, 'label' => 'Kritik (30+ gün)', 'color' => 'danger'],
            'warning' => ['min' => 15, 'max' => 29, 'label' => 'Uyarı (15-29 gün)', 'color' => 'warning'],
            'attention' => ['min' => 7, 'max' => 14, 'label' => 'Dikkat (7-14 gün)', 'color' => 'info'],
            'normal' => ['min' => 0, 'max' => 6, 'label' => 'Normal (0-6 gün)', 'color' => 'success']
        ];
        
        $categorizedStock = [];
        $summary = [
            'total_barcodes' => 0,
            'total_quantity' => 0,
            'critical_count' => 0,
            'critical_quantity' => 0,
            'warning_count' => 0,
            'warning_quantity' => 0,
            'attention_count' => 0,
            'attention_quantity' => 0,
            'normal_count' => 0,
            'normal_quantity' => 0
        ];
        
        foreach ($stockAgeData as $stock) {
            $summary['total_barcodes']++;
            $summary['total_quantity'] += $stock->quantity ?? 0;
            
            foreach ($ageCategories as $category => $criteria) {
                if (($criteria['min'] === null || $stock->days_old >= $criteria['min']) && 
                    ($criteria['max'] === null || $stock->days_old <= $criteria['max'])) {
                    
                    if (!isset($categorizedStock[$category])) {
                        $categorizedStock[$category] = [];
                    }
                    
                    $categorizedStock[$category][] = $stock;
                    
                    // Summary güncelle
                    $summary[$category . '_count']++;
                    $summary[$category . '_quantity'] += $stock->quantity ?? 0;
                    break;
                }
            }
        }
        
        // En eski 20 barkodu al
        $oldestBarcodes = array_slice($stockAgeData, 0, 20);
        
        // Durum bazında analiz
        $statusAnalysis = [];
        foreach ($stockAgeData as $stock) {
            $status = $stock->status;
            if (!isset($statusAnalysis[$status])) {
                $statusAnalysis[$status] = [
                    'count' => 0,
                    'quantity' => 0,
                    'avg_age' => 0,
                    'oldest_age' => 0
                ];
            }
            
            $statusAnalysis[$status]['count']++;
            $statusAnalysis[$status]['quantity'] += $stock->quantity ?? 0;
            $statusAnalysis[$status]['avg_age'] += $stock->days_old;
            $statusAnalysis[$status]['oldest_age'] = max($statusAnalysis[$status]['oldest_age'], $stock->days_old);
        }
        
        // Ortalama yaşları hesapla
        foreach ($statusAnalysis as $status => $data) {
            if ($data['count'] > 0) {
                $statusAnalysis[$status]['avg_age'] = round($data['avg_age'] / $data['count'], 1);
            }
        }
        
        // Ürün bazında analiz
        $productAnalysis = [];
        foreach ($stockAgeData as $stock) {
            $productKey = $stock->stock_name . ' (' . $stock->stock_code . ')';
            if (!isset($productAnalysis[$productKey])) {
                $productAnalysis[$productKey] = [
                    'stock_name' => $stock->stock_name,
                    'stock_code' => $stock->stock_code,
                    'count' => 0,
                    'quantity' => 0,
                    'avg_age' => 0,
                    'oldest_age' => 0,
                    'critical_count' => 0,
                    'warning_count' => 0
                ];
            }
            
            $productAnalysis[$productKey]['count']++;
            $productAnalysis[$productKey]['quantity'] += $stock->quantity ?? 0;
            $productAnalysis[$productKey]['avg_age'] += $stock->days_old;
            $productAnalysis[$productKey]['oldest_age'] = max($productAnalysis[$productKey]['oldest_age'], $stock->days_old);
            
            if ($stock->days_old >= 30) {
                $productAnalysis[$productKey]['critical_count']++;
            } elseif ($stock->days_old >= 15) {
                $productAnalysis[$productKey]['warning_count']++;
            }
        }
        
        // Ürün ortalamalarını hesapla
        foreach ($productAnalysis as $productKey => $data) {
            if ($data['count'] > 0) {
                $productAnalysis[$productKey]['avg_age'] = round($data['avg_age'] / $data['count'], 1);
            }
        }
        
        // Kritik ürünleri sırala
        uasort($productAnalysis, function($a, $b) {
            return ($b['critical_count'] + $b['warning_count']) - ($a['critical_count'] + $a['warning_count']);
        });
        
        return [
            'summary' => $summary,
            'categorized_stock' => $categorizedStock,
            'oldest_barcodes' => $oldestBarcodes,
            'status_analysis' => $statusAnalysis,
            'product_analysis' => array_slice($productAnalysis, 0, 20), // En kritik 20 ürün
            'age_categories' => $ageCategories,
            'current_date' => $currentDate->format('d.m.Y H:i')
        ];
    }

    /**
     * Red sebepleri analizi
     */
    private function getRejectionReasonsAnalysis($date, $period = 'daily', $periodInfo = null)
    {
        $startDate = $date->copy()->startOfDay();
        $endDate = $date->copy()->endOfDay();

        // Günlük red sebepleri analizi
        $dailyRejectionReasons = Barcode::with(['rejectionReasons', 'quantity', 'stock', 'kiln'])
            ->whereBetween('lab_at', [$startDate, $endDate])
            ->where('status', Barcode::STATUS_REJECTED)
            ->get()
            ->flatMap(function ($barcode) {
                return $barcode->rejectionReasons->map(function ($reason) use ($barcode) {
                    return [
                        'reason_name' => $reason->name,
                        'reason_code' => $reason->code,
                        'kg' => $barcode->quantity->quantity ?? 0,
                        'stock_name' => $barcode->stock->name ?? 'Bilinmiyor',
                        'kiln_name' => $barcode->kiln->name ?? 'Bilinmiyor',
                        'lab_at' => $barcode->lab_at
                    ];
                });
            })
            ->groupBy('reason_name')
            ->map(function ($items) {
                return [
                    'count' => $items->count(),
                    'total_kg' => $items->sum('kg'),
                    'stocks' => $items->pluck('stock_name')->unique()->values(),
                    'kilns' => $items->pluck('kiln_name')->unique()->values(),
                    'avg_kg_per_rejection' => $items->avg('kg')
                ];
            })
            ->sortByDesc('count');

        // Son 7 günlük trend
        $weeklyTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $trendDate = $date->copy()->subDays($i);
            $trendStart = $trendDate->copy()->startOfDay();
            $trendEnd = $trendDate->copy()->endOfDay();
            
            $dailyCount = Barcode::whereBetween('lab_at', [$trendStart, $trendEnd])
                ->where('status', Barcode::STATUS_REJECTED)
                ->count();
            
            $weeklyTrend[] = [
                'date' => $trendDate->format('d.m'),
                'count' => $dailyCount
            ];
        }

        // En kritik red sebepleri (son 30 gün)
        $criticalReasons = Barcode::with(['rejectionReasons', 'quantity'])
            ->whereBetween('lab_at', [$date->copy()->subDays(30), $endDate])
            ->where('status', Barcode::STATUS_REJECTED)
            ->get()
            ->flatMap(function ($barcode) {
                return $barcode->rejectionReasons->map(function ($reason) use ($barcode) {
                    return [
                        'reason_name' => $reason->name,
                        'kg' => $barcode->quantity->quantity ?? 0
                    ];
                });
            })
            ->groupBy('reason_name')
            ->map(function ($items) {
                return [
                    'count' => $items->count(),
                    'total_kg' => $items->sum('kg'),
                    'trend' => 'stable' // Basit trend analizi
                ];
            })
            ->sortByDesc('count')
            ->take(5);

        return [
            'daily_reasons' => $dailyRejectionReasons,
            'weekly_trend' => $weeklyTrend,
            'critical_reasons' => $criticalReasons,
            'total_rejected_today' => Barcode::whereBetween('lab_at', [$startDate, $endDate])
                ->where('status', Barcode::STATUS_REJECTED)
                ->count(),
            'total_rejected_kg_today' => Barcode::with(['quantity'])
                ->whereBetween('lab_at', [$startDate, $endDate])
                ->where('status', Barcode::STATUS_REJECTED)
                ->get()
                ->sum('quantity.quantity')
        ];
    }
}
