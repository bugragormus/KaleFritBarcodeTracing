<?php

namespace App\Http\Controllers\Granilya;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barcode;
use App\Models\GranilyaProduction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the Granilya application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Frit tarafından Granilya'ya aktarılan hammaddeleri getir
        $query = DB::table('barcodes')
            ->select(
                'stocks.id as stock_id',
                'stocks.name as stock_name',
                'barcodes.load_number',
                DB::raw('SUM(quantities.quantity) as total_quantity')
            )
            ->join('stocks', 'barcodes.stock_id', '=', 'stocks.id')
            ->join('quantities', 'barcodes.quantity_id', '=', 'quantities.id')
            ->where('barcodes.status', Barcode::STATUS_TRANSFERRED_TO_GRANILYA)
            ->whereNull('barcodes.deleted_at');

        if ($startDate && $endDate) {
            $query->whereBetween('barcodes.updated_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $rawMaterialStocks = $query->groupBy('stocks.id', 'stocks.name', 'barcodes.load_number')
            ->orderBy('stocks.name')
            ->orderBy('barcodes.load_number')
            ->get();
            
        // Üretim kullanımlarını (granilya_productions tablosu) gruplu olarak al
        $productionStats = DB::table('granilya_productions')
            ->select(
                'stock_id',
                'load_number',
                DB::raw('SUM(used_quantity) as total_used'),
                DB::raw('SUM(sieve_residue_quantity) as total_sieve_residue')
            )
            ->where('is_correction', false)
            ->whereNull('deleted_at')
            ->groupBy('stock_id', 'load_number')
            ->get()
            ->keyBy(function ($item) {
                return $item->stock_id . '_' . $item->load_number;
            });

        $kpiTotalStock = 0;
        // Verileri birleştir ve kalan miktarı hesapla
        foreach ($rawMaterialStocks as $stock) {
            $key = $stock->stock_id . '_' . $stock->load_number;
            $stat = $productionStats->get($key);
            
            $stock->used_quantity = $stat ? $stat->total_used : 0;
            $stock->sieve_residue_quantity = $stat ? $stat->total_sieve_residue : 0;
            $stock->remaining_quantity = $stock->total_quantity - $stock->used_quantity - $stock->sieve_residue_quantity;
            $kpiTotalStock += $stock->remaining_quantity;
        }

        // --- KPI Hesaplamaları ---
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Günlük Üretim
        $kpiDailyProduction = GranilyaProduction::whereDate('created_at', $today)
            ->sum('used_quantity');

        // Satış Hacmi (Aylık) - STATUS_SHIPPED
        $kpiMonthlySales = GranilyaProduction::where('status', GranilyaProduction::STATUS_SHIPPED)
            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
            ->sum('used_quantity');

        // Bekleyen Analiz
        $kpiPendingAnalysis = GranilyaProduction::whereIn('status', [
            GranilyaProduction::STATUS_WAITING, 
            GranilyaProduction::STATUS_PRE_APPROVED
        ])->count();

        // Onaylı Ürünler
        $kpiApproved = GranilyaProduction::whereIn('status', [
            GranilyaProduction::STATUS_SHIPMENT_APPROVED,
            GranilyaProduction::STATUS_CUSTOMER_TRANSFER
        ])->count();

        // Reddedilen Ürünler
        $kpiRejected = GranilyaProduction::where('status', GranilyaProduction::STATUS_REJECTED)
            ->count();

        return view('granilya.dashboard', compact(
            'rawMaterialStocks', 
            'kpiTotalStock', 
            'kpiDailyProduction', 
            'kpiMonthlySales', 
            'kpiPendingAnalysis', 
            'kpiApproved', 
            'kpiRejected',
            'startDate',
            'endDate'
        ));
    }

    public function exportRawMaterials(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = DB::table('barcodes')
            ->select(
                'stocks.name as stock_name',
                'barcodes.load_number',
                DB::raw('SUM(quantities.quantity) as total_quantity')
            )
            ->join('stocks', 'barcodes.stock_id', '=', 'stocks.id')
            ->join('quantities', 'barcodes.quantity_id', '=', 'quantities.id')
            ->where('barcodes.status', Barcode::STATUS_TRANSFERRED_TO_GRANILYA)
            ->whereNull('barcodes.deleted_at');

        if ($startDate && $endDate) {
            $query->whereBetween('barcodes.updated_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $rawMaterialStocks = $query->groupBy('stocks.id', 'stocks.name', 'barcodes.load_number')
            ->orderBy('stocks.name')
            ->orderBy('barcodes.load_number')
            ->get();

        $productionStats = DB::table('granilya_productions')
            ->select(
                'stock_id',
                'load_number',
                DB::raw('SUM(used_quantity) as total_used'),
                DB::raw('SUM(sieve_residue_quantity) as total_sieve_residue')
            )
            ->where('is_correction', false)
            ->whereNull('deleted_at')
            ->groupBy('stock_id', 'load_number')
            ->get()
            ->keyBy(function ($item) {
                // We need stock_id to map, but the first query group by stocks.id which we didn't select for export.
                // Let's fix the query select below.
                return ''; 
            });
            
        // Correcting the query above to include stock_id for internal calculation
        $exportDataQuery = DB::table('barcodes')
            ->select(
                'stocks.id as stock_id',
                'stocks.name as stock_name',
                'barcodes.load_number',
                DB::raw('SUM(quantities.quantity) as total_quantity')
            )
            ->join('stocks', 'barcodes.stock_id', '=', 'stocks.id')
            ->join('quantities', 'barcodes.quantity_id', '=', 'quantities.id')
            ->where('barcodes.status', Barcode::STATUS_TRANSFERRED_TO_GRANILYA)
            ->whereNull('barcodes.deleted_at');

        if ($startDate && $endDate) {
            $exportDataQuery->whereBetween('barcodes.updated_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $rawMaterialStocks = $exportDataQuery->groupBy('stocks.id', 'stocks.name', 'barcodes.load_number')
            ->orderBy('stocks.name')
            ->orderBy('barcodes.load_number')
            ->get();

        $filename = "frit_hammadde_aktarim_raporu_" . date('Ymd_His') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($rawMaterialStocks, $productionStats) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM for excel
            
            fputcsv($file, [
                'Hammadde (Frit) Adı',
                'Şarj No',
                'Toplam Gelen (KG)',
                'Üretimde Kullanılan (KG)',
                'Elek Altı (KG)',
                'Kalan Stok (KG)'
            ]);

            foreach ($rawMaterialStocks as $row) {
                $stat = $productionStats->get($row->stock_id . '_' . $row->load_number);
                $used = $stat ? $stat->total_used : 0;
                $sieve = $stat ? $stat->total_sieve_residue : 0;
                $remaining = $row->total_quantity - $used - $sieve;

                fputcsv($file, [
                    $row->stock_name,
                    $row->load_number,
                    number_format($row->total_quantity, 2, '.', ''),
                    number_format($used, 2, '.', ''),
                    number_format($sieve, 2, '.', ''),
                    number_format($remaining, 2, '.', '')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
