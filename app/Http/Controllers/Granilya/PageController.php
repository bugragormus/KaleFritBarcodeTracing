<?php

namespace App\Http\Controllers\Granilya;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
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

    public function production()
    {
        // Frit'ten Granilya'ya aktarılmış stoklar ve şarj numaraları (Kullanılabilir Hammaddeler)
        $transferredBarcodes = \App\Models\Barcode::select(
                'stocks.id as stock_id',
                'stocks.name as stock_name',
                'stocks.code as stock_code',
                'barcodes.load_number'
            )
            ->join('stocks', 'barcodes.stock_id', '=', 'stocks.id')
            ->where('barcodes.status', \App\Models\Barcode::STATUS_TRANSFERRED_TO_GRANILYA)
            ->whereNull('barcodes.deleted_at')
            ->groupBy('stocks.id', 'stocks.name', 'stocks.code', 'barcodes.load_number')
            ->orderBy('stocks.name')
            ->orderBy('barcodes.load_number')
            ->get();

        // Stok bazlı şarj numaralarını JS için grupla ve stok kalanını doğrula
        $stockLoadNumbers = [];
        $uniqueStocks = [];
        
        foreach ($transferredBarcodes as $item) {
            $stockId = $item->stock_id;
            $loadNumber = $item->load_number;

            // Kalan stoğu hesapla
            $totalImported = \App\Models\Barcode::where('stock_id', $stockId)
                ->where('load_number', $loadNumber)
                ->where('status', \App\Models\Barcode::STATUS_TRANSFERRED_TO_GRANILYA)
                ->leftJoin('quantities', 'barcodes.quantity_id', '=', 'quantities.id')
                ->sum('quantities.quantity');

            $previouslyUsed = \App\Models\GranilyaProduction::where('stock_id', $stockId)
                ->where('load_number', $loadNumber)
                ->where('is_correction', false)
                ->sum('used_quantity');

            $previouslySieved = \App\Models\GranilyaProduction::where('stock_id', $stockId)
                ->where('load_number', $loadNumber)
                ->sum('sieve_residue_quantity');

            $remainingStock = $totalImported - $previouslyUsed - $previouslySieved;

            // Sadece içinde yeterli miktar kalanları (Float hatalarına karşı 0.01'den büyük) listele
            if ($remainingStock > 0.01) {
                if (!isset($uniqueStocks[$stockId])) {
                    $uniqueStocks[$stockId] = [
                        'id' => $stockId,
                        'name' => $item->stock_name,
                        'code' => $item->stock_code
                    ];
                }
                
                if (!empty($loadNumber) && $loadNumber !== '-') {
                    if (!isset($stockLoadNumbers[$stockId])) {
                        $stockLoadNumbers[$stockId] = [];
                    }
                    if (!in_array($loadNumber, $stockLoadNumbers[$stockId])) {
                        $stockLoadNumbers[$stockId][] = $loadNumber;
                    }
                }
            }
        }

        // Diğer tanımlamalar
        $sizes = \App\Models\GranilyaSize::orderBy('name')->get();
        $crushers = \App\Models\GranilyaCrusher::orderBy('name')->get();
        $quantities = \App\Models\GranilyaQuantity::orderBy('quantity')->get();
        $companies = \App\Models\GranilyaCompany::orderBy('name')->get();

        // Sadece reddedilen Granilya üretimlerini getir (Düzeltme faaliyeti için)
        $rejectedProductions = \App\Models\GranilyaProduction::with(['stock', 'size'])
            ->where('status', \App\Models\GranilyaProduction::STATUS_REJECTED)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('granilya.production.create', compact(
            'uniqueStocks',
            'stockLoadNumbers',
            'sizes',
            'crushers',
            'quantities',
            'companies',
            'rejectedProductions'
        ));
    }

    public function stock()
    {
        return view('granilya.placeholder', ['title' => 'Stok Durumu']);
    }

    public function laboratory()
    {
        return view('granilya.placeholder', ['title' => 'Laboratuvar']);
    }

    public function report()
    {
        return view('granilya.placeholder', ['title' => 'Üretim Raporu']);
    }

    public function sales()
    {
        return view('granilya.placeholder', ['title' => 'Satış Ekranı']);
    }

    public function barcode()
    {
        return view('granilya.stock.query', ['title' => 'Palet Sorgula']);
    }
}
