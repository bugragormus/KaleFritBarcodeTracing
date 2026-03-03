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
        $rawMaterialStocks = Barcode::select(
                'stocks.name as stock_name',
                DB::raw('SUM(quantities.quantity) as total_quantity'),
                DB::raw('COUNT(barcodes.id) as barcode_count')
            )
            ->join('stocks', 'barcodes.stock_id', '=', 'stocks.id')
            ->join('quantities', 'barcodes.quantity_id', '=', 'quantities.id')
            ->where('barcodes.status', Barcode::STATUS_TRANSFERRED_TO_GRANILYA)
            ->whereNull('barcodes.deleted_at')
            ->groupBy('stocks.id', 'stocks.name')
            ->orderBy('stocks.name')
            ->get();

        return view('granilya.dashboard', compact('rawMaterialStocks'));
    }
}
