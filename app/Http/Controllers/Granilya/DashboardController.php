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
        $calcStartDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth()->startOfDay();
        $calcEndDate   = $endDate   ? Carbon::parse($endDate)->endOfDay()     : Carbon::now()->endOfDay();
        $today = Carbon::today();
        $filterActive = ($startDate || $endDate);

        // 1. Üretim Toplamı — Seçilen dönemde üretilen toplam KG (is_correction = false)
        if ($filterActive) {
            $kpiDailyProduction = GranilyaProduction::where('is_correction', false)
                ->whereBetween('created_at', [$calcStartDate, $calcEndDate])
                ->sum('used_quantity');
        } else {
            $kpiDailyProduction = GranilyaProduction::where('is_correction', false)
                ->whereDate('created_at', $today)
                ->sum('used_quantity');
        }

        // 2. Satış Hacmi — SADECE Teslim Edildi (STATUS_DELIVERED)
        $kpiMonthlySales = GranilyaProduction::where('status', GranilyaProduction::STATUS_DELIVERED)
            ->whereBetween('updated_at', [$calcStartDate, $calcEndDate])
            ->sum('used_quantity');

        // 3. Sevke Hazır Stok — Sevk Onaylı tüm paletlerin KG (is_correction filtresi YOK: düzeltme paletleri de hazır olabilir)
        $kpiReadyStock = GranilyaProduction::where('status', GranilyaProduction::STATUS_SHIPMENT_APPROVED)
            ->sum('used_quantity');

        // 4. Analiz Bekleyenler — Anlık, KG cinsinden (is_correction filtresi YOK)
        $kpiPendingAnalysis = GranilyaProduction::whereIn('status', [
                GranilyaProduction::STATUS_WAITING,
                GranilyaProduction::STATUS_PRE_APPROVED,
            ])
            ->sum('used_quantity');

        // 5. Onaylı Paletler — Dönemde sevk onayı almış palet sayısı (is_correction filtresi YOK)
        $kpiApproved = GranilyaProduction::where('status', GranilyaProduction::STATUS_SHIPMENT_APPROVED)
            ->whereBetween('updated_at', [$calcStartDate, $calcEndDate])
            ->count();

        // 6. Reddedilen — STATUS_REJECTED + STATUS_CORRECTED (düzeltmeye gitmiş red'ler) toplamı KG — Anlık
        $kpiRejected = GranilyaProduction::whereIn('status', [
                GranilyaProduction::STATUS_REJECTED,
                GranilyaProduction::STATUS_CORRECTED,
            ])
            ->sum('used_quantity');

        // 7. Dönem etiketi
        $periodLabel = $filterActive
            ? Carbon::parse($calcStartDate)->format('d.m.Y') . ' – ' . Carbon::parse($calcEndDate)->format('d.m.Y')
            : 'Bugün / Bu Ay';


        return view('granilya.dashboard', compact(
            'rawMaterialStocks', 
            'kpiTotalStock', 
            'kpiDailyProduction', 
            'kpiMonthlySales',
            'kpiReadyStock',
            'kpiPendingAnalysis', 
            'kpiApproved', 
            'kpiRejected',
            'periodLabel',
            'filterActive',
            'startDate',
            'endDate'
        ));
    }

    public function exportRawMaterials(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

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

        $processedData = [];
        foreach ($rawMaterialStocks as $row) {
            $stat = $productionStats->get($row->stock_id . '_' . $row->load_number);
            $used = $stat ? $stat->total_used : 0;
            $sieve = $stat ? $stat->total_sieve_residue : 0;
            $remaining = $row->total_quantity - $used - $sieve;

            $processedData[] = [
                'stock_name' => $row->stock_name,
                'load_number' => $row->load_number,
                'total_quantity' => $row->total_quantity,
                'used_quantity' => $used,
                'sieve_residue_quantity' => $sieve,
                'remaining_quantity' => $remaining
            ];
        }

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\Granilya\RawMaterialTransferExport($processedData, $startDate, $endDate),
            'frit_hammadde_aktarim_raporu_' . date('Ymd_His') . '.xlsx'
        );
    }
}
