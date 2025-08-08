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
        // Genel istatistikler
        $generalStats = $this->getGeneralStats();
        
        // Son 7 günlük üretim
        $weeklyProduction = $this->getWeeklyProduction();
        
        // En çok üretilen stoklar
        $topStocks = $this->getTopStocks();
        
        // En aktif müşteriler
        $topCustomers = $this->getTopCustomers();
        
        // Fırın performansı
        $kilnPerformance = $this->getKilnPerformance();
        
        // Durum dağılımı
        $statusDistribution = $this->getStatusDistribution();
        
        // Aylık trend
        $monthlyTrend = $this->getMonthlyTrend();
        
        return view('admin.dashboard', compact(
            'generalStats',
            'weeklyProduction',
            'topStocks',
            'topCustomers',
            'kilnPerformance',
            'statusDistribution',
            'monthlyTrend'
        ));
    }

    private function getGeneralStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        return [
            'total_stocks' => Stock::count(),
            'total_barcodes' => Barcode::count(),
            'total_companies' => Company::count(),
            'total_warehouses' => Warehouse::count(),
            'total_kilns' => Kiln::count(),
            'today_production' => Barcode::whereDate('created_at', $today)->count(),
            'month_production' => Barcode::whereMonth('created_at', $thisMonth->month)
                ->whereYear('created_at', $thisMonth->year)
                ->count(),
            'total_delivered' => Barcode::where('status', Barcode::STATUS_DELIVERED)->count(),
            'total_rejected' => Barcode::where('status', Barcode::STATUS_REJECTED)->count(),
        ];
    }

    private function getWeeklyProduction()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        return DB::select('
            SELECT 
                DATE(barcodes.created_at) as date,
                COUNT(*) as barcode_count,
                COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id AND quantities.deleted_at IS NULL
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.deleted_at IS NULL
            GROUP BY DATE(barcodes.created_at)
            ORDER BY date
        ', [$startDate, $endDate]);
    }

    private function getTopStocks()
    {
        return DB::select('
            SELECT 
                stocks.name,
                stocks.code,
                COUNT(barcodes.id) as barcode_count,
                COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM stocks
            LEFT JOIN barcodes ON stocks.id = barcodes.stock_id AND barcodes.deleted_at IS NULL
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id AND quantities.deleted_at IS NULL
            WHERE stocks.deleted_at IS NULL
            GROUP BY stocks.id, stocks.name, stocks.code
            ORDER BY total_quantity DESC
            LIMIT 10
        ');
    }

    private function getTopCustomers()
    {
        return DB::select('
            SELECT 
                companies.name,
                COUNT(barcodes.id) as barcode_count,
                COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM companies
            LEFT JOIN barcodes ON companies.id = barcodes.company_id AND barcodes.deleted_at IS NULL
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id AND quantities.deleted_at IS NULL
            WHERE companies.deleted_at IS NULL
            AND barcodes.company_id IS NOT NULL
            GROUP BY companies.id, companies.name
            ORDER BY total_quantity DESC
            LIMIT 10
        ');
    }

    private function getKilnPerformance()
    {
        return DB::select('
            SELECT 
                kilns.name,
                COUNT(barcodes.id) as barcode_count,
                COALESCE(SUM(quantities.quantity), 0) as total_quantity,
                AVG(quantities.quantity) as avg_quantity
            FROM kilns
            LEFT JOIN barcodes ON kilns.id = barcodes.kiln_id AND barcodes.deleted_at IS NULL
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id AND quantities.deleted_at IS NULL
            WHERE kilns.deleted_at IS NULL
            GROUP BY kilns.id, kilns.name
            ORDER BY total_quantity DESC
        ');
    }

    private function getStatusDistribution()
    {
        return DB::select('
            SELECT 
                barcodes.status,
                COUNT(barcodes.id) as count,
                COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id AND quantities.deleted_at IS NULL
            WHERE barcodes.deleted_at IS NULL
            GROUP BY barcodes.status
            ORDER BY barcodes.status
        ');
    }

    private function getMonthlyTrend()
    {
        return DB::select('
            SELECT 
                YEAR(barcodes.created_at) as year,
                MONTH(barcodes.created_at) as month,
                COUNT(*) as barcode_count,
                COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id AND quantities.deleted_at IS NULL
            WHERE barcodes.deleted_at IS NULL
            AND barcodes.created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY YEAR(barcodes.created_at), MONTH(barcodes.created_at)
            ORDER BY year DESC, month DESC
            LIMIT 12
        ');
    }
}
