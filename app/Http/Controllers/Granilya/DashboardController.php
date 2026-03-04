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
                'barcodes.load_number'
            )
            ->join('stocks', 'barcodes.stock_id', '=', 'stocks.id')
            ->where('barcodes.status', Barcode::STATUS_TRANSFERRED_TO_GRANILYA)
            ->whereNull('barcodes.deleted_at')
            ->groupBy('stocks.id', 'stocks.name', 'barcodes.load_number')
            ->orderBy('stocks.name')
            ->orderBy('barcodes.load_number')
            ->get();

        return view('granilya.dashboard', compact('rawMaterialStocks'));
    }
}
