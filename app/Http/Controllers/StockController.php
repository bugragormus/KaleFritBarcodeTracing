<?php

namespace App\Http\Controllers;

use App\Exports\StockExport;
use App\Exports\StockDetailExport;
use App\Http\Requests\Stock\StockStoreRequest;
use App\Http\Requests\Stock\StockUpdateRequest;
use App\Models\Barcode;
use App\Models\Stock;
use App\Services\StockCalculationService;
use Carbon\Carbon;
use Excel;

class StockController extends Controller
{
    protected $stockCalculationService;

    public function __construct(StockCalculationService $stockCalculationService)
    {
        $this->stockCalculationService = $stockCalculationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $stocks = $this->stockCalculationService->calculateStockStatuses();

        // Array'i Collection'a çevir
        $stocks = collect($stocks);

        // Her stok için detaylı istatistikler
        foreach ($stocks as $stock) {
            // Toplam üretim miktarı
            $stock->total_production = $stock->waiting_quantity + $stock->accepted_quantity + $stock->rejected_quantity + 
                                     $stock->in_warehouse_quantity + $stock->on_delivery_in_warehouse_quantity + $stock->delivered_quantity;
            
            // Red oranı
            $totalQuantity = $stock->total_production;
            $stock->rejection_rate = $totalQuantity > 0 ? round(($stock->rejected_quantity / $totalQuantity) * 100, 2) : 0;
            
            // Teslim oranı
            $stock->delivery_rate = $totalQuantity > 0 ? round(($stock->delivered_quantity / $totalQuantity) * 100, 2) : 0;
            
            // Stokta kalan miktar
            $stock->remaining_stock = $stock->waiting_quantity + $stock->accepted_quantity + $stock->in_warehouse_quantity + 
                                    $stock->on_delivery_in_warehouse_quantity;
            
            // Son üretim tarihi (barkod tablosundan)
            $lastBarcode = Barcode::where('stock_id', $stock->id)->latest('created_at')->first();
            $stock->last_production_date = $lastBarcode ? $lastBarcode->created_at : null;
            
            // Aktiflik durumu (son 90 gün içinde üretim yapılıp yapılmadığı)
            $recentProduction = Barcode::where('stock_id', $stock->id)
                ->where('created_at', '>=', now()->subDays(90))
                ->count();
            $stock->is_active = $recentProduction > 0;
        }

        return view('admin.stock.index', compact('stocks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.stock.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Stock\StockStoreRequest $request
     * @return string
     */
    public function store(StockStoreRequest $request)
    {
        $data = $request->validated();

        $stock = Stock::create($data);

        if (!$stock) {
            toastr()->error('Stok girişi yapılamadı.');
        }

        // Cache temizleme
        $this->stockCalculationService->clearCache();
        
        toastr()->success('Stok girişi başarılı.');

        return redirect()->route('stock.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stock = Stock::findOrFail($id);
        
        // Stok detaylarını hesapla
        $stockDetails = $this->stockCalculationService->getStockDetails($id);
        
        // Son 30 günlük üretim grafiği için veri
        $productionData = $this->stockCalculationService->getProductionChartData($id);
        
        // Durum bazında barkod listesi
        $barcodesByStatus = $this->stockCalculationService->getBarcodesByStatus($id);
        
        // Fırın bazında üretim
        $productionByKiln = $this->stockCalculationService->getProductionByKiln($id);
        
        // Müşteri bazında satış
        $salesByCompany = $this->stockCalculationService->getSalesByCompany($id);
        
        // Aylık üretim trendi
        $monthlyTrend = $this->stockCalculationService->getMonthlyTrend($id);
        
        return view('admin.stock.show', compact(
            'stock', 
            'stockDetails', 
            'productionData', 
            'barcodesByStatus',
            'productionByKiln',
            'salesByCompany',
            'monthlyTrend'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $stock = Stock::findOrFail($id);

        return view('admin.stock.edit', compact('stock'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Stock\StockUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(StockUpdateRequest $request, $id)
    {
        $data = $request->validated();

        $stock = Stock::findOrFail($id);

        $stock->update($data);

        if (!$stock) {
            toastr()->error('Stok güncellenemedi.');
        }

        // Cache temizleme
        $this->stockCalculationService->clearCache();
        
        toastr()->success('Stok başarıyla güncellendi.');

        return redirect()->route('stock.index');
    }

    /**
     * Export stock details as PDF
     */
    public function exportPdf($id)
    {
        // PDF özelliği geçici olarak devre dışı
        toastr()->warning('PDF özelliği şu anda kullanılamıyor. Excel formatını kullanabilirsiniz.');
        return redirect()->route('stock.show', ['stok' => $id]);
    }

    /**
     * Export stock details as Excel
     */
    public function exportExcel($id)
    {
        $stock = Stock::findOrFail($id);
        
        // Stok detaylarını hesapla
        $stockDetails = $this->stockCalculationService->getStockDetails($id);
        $productionData = $this->stockCalculationService->getProductionChartData($id);
        $barcodesByStatus = $this->stockCalculationService->getBarcodesByStatus($id);
        $productionByKiln = $this->stockCalculationService->getProductionByKiln($id);
        $salesByCompany = $this->stockCalculationService->getSalesByCompany($id);
        $monthlyTrend = $this->stockCalculationService->getMonthlyTrend($id);
        
        return Excel::download(new \App\Exports\StockDetailExport(
            $stock, 
            $stockDetails, 
            $productionData, 
            $barcodesByStatus,
            $productionByKiln,
            $salesByCompany,
            $monthlyTrend
        ), "stok-raporu-{$stock->code}-" . date('Y-m-d') . ".xlsx");
    }

    /**
     * Print stock details
     */
    public function print($id)
    {
        $stock = Stock::findOrFail($id);
        
        // Stok detaylarını hesapla
        $stockDetails = $this->stockCalculationService->getStockDetails($id);
        $productionData = $this->stockCalculationService->getProductionChartData($id);
        $barcodesByStatus = $this->stockCalculationService->getBarcodesByStatus($id);
        $productionByKiln = $this->stockCalculationService->getProductionByKiln($id);
        $salesByCompany = $this->stockCalculationService->getSalesByCompany($id);
        $monthlyTrend = $this->stockCalculationService->getMonthlyTrend($id);
        
        return view('admin.stock.print', compact(
            'stock', 
            'stockDetails', 
            'productionData', 
            'barcodesByStatus',
            'productionByKiln',
            'salesByCompany',
            'monthlyTrend'
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);

        $stock->delete();

        if (!$stock) {
            toastr()->error('Stok girişi silinemedi.');
        }

        // Cache temizleme
        $this->stockCalculationService->clearCache();

        return response()->json(['message' => 'Stok girişi silindi!']);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadExcel()
    {
        $stocks = $this->stockCalculationService->calculateStockStatuses();

        return Excel::download(new StockExport(collect($stocks)), 'stoklar-'.Carbon::now()->format('d-m-Y H:i').'.xlsx');
    }
}
