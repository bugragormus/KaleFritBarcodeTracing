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

        // Stok bazlı şarj numaralarını JS için grupla
        $stockLoadNumbers = [];
        $uniqueStocks = [];
        
        foreach ($transferredBarcodes as $item) {
            $stockId = $item->stock_id;
            
            if (!isset($uniqueStocks[$stockId])) {
                $uniqueStocks[$stockId] = [
                    'id' => $stockId,
                    'name' => $item->stock_name,
                    'code' => $item->stock_code
                ];
            }
            
            if (!empty($item->load_number) && $item->load_number !== '-') {
                if (!isset($stockLoadNumbers[$stockId])) {
                    $stockLoadNumbers[$stockId] = [];
                }
                if (!in_array($item->load_number, $stockLoadNumbers[$stockId])) {
                    $stockLoadNumbers[$stockId][] = $item->load_number;
                }
            }
        }

        // Diğer tanımlamalar
        $sizes = \App\Models\GranilyaSize::orderBy('name')->get();
        $crushers = \App\Models\GranilyaCrusher::orderBy('name')->get();
        $quantities = \App\Models\GranilyaQuantity::orderBy('quantity')->get();
        $companies = \App\Models\GranilyaCompany::orderBy('name')->get();

        return view('granilya.production.create', compact(
            'uniqueStocks',
            'stockLoadNumbers',
            'sizes',
            'crushers',
            'quantities',
            'companies'
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
        return view('granilya.placeholder', ['title' => 'Barkod Sorgu']);
    }
}
