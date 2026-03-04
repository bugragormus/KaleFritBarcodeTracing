<?php

namespace App\Http\Controllers\Granilya;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barcode;
use Illuminate\Support\Facades\DB;

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
    public function index()
    {
        // Frit tarafından Granilya'ya aktarılan hammaddeleri getir
        $rawMaterialStocks = DB::table('barcodes')
            ->select(
                'stocks.id as stock_id',
                'stocks.name as stock_name',
                'barcodes.load_number',
                DB::raw('SUM(quantities.quantity) as total_quantity')
            )
            ->join('stocks', 'barcodes.stock_id', '=', 'stocks.id')
            ->join('quantities', 'barcodes.quantity_id', '=', 'quantities.id')
            ->where('barcodes.status', Barcode::STATUS_TRANSFERRED_TO_GRANILYA)
            ->whereNull('barcodes.deleted_at')
            ->groupBy('stocks.id', 'stocks.name', 'barcodes.load_number')
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
            ->whereNull('deleted_at')
            ->groupBy('stock_id', 'load_number')
            ->get()
            ->keyBy(function ($item) {
                return $item->stock_id . '_' . $item->load_number;
            });

        // Verileri birleştir ve kalan miktarı hesapla
        foreach ($rawMaterialStocks as $stock) {
            $key = $stock->stock_id . '_' . $stock->load_number;
            $stat = $productionStats->get($key);
            
            $stock->used_quantity = $stat ? $stat->total_used : 0;
            $stock->sieve_residue_quantity = $stat ? $stat->total_sieve_residue : 0;
            $stock->remaining_quantity = $stock->total_quantity - $stock->used_quantity - $stock->sieve_residue_quantity;
        }

        return view('granilya.dashboard', compact('rawMaterialStocks'));
    }
}
