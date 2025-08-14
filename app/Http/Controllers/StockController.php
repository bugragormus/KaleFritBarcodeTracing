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
        // Tarih filtreleme parametreleri
        $startDate = request('start_date');
        $endDate = request('end_date');
        $period = request('period');
        
        // Period parametresine göre varsayılan tarihleri ayarla
        if (!$startDate && !$endDate && $period) {
            switch ($period) {
                case 'monthly':
                    $startDate = now()->startOfMonth()->format('Y-m-d');
                    $endDate = now()->endOfMonth()->format('Y-m-d');
                    break;
                case 'quarterly':
                    $startDate = now()->startOfQuarter()->format('Y-m-d');
                    $endDate = now()->endOfQuarter()->format('Y-m-d');
                    break;
                case 'yearly':
                    $startDate = now()->startOfYear()->format('Y-m-d');
                    $endDate = now()->endOfYear()->format('Y-m-d');
                    break;
                case 'all':
                    // Tüm zamanlar için tarih filtresi yok
                    break;
                default:
                    // Günlük - bugün
                    $startDate = now()->format('Y-m-d');
                    $endDate = now()->format('Y-m-d');
                    break;
            }
        } elseif (!$startDate && !$endDate) {
            // Varsayılan olarak bugün
            $startDate = now()->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
        }

        $stocks = $this->stockCalculationService->calculateStockStatuses();

        // Array'i Collection'a çevir
        $stocks = collect($stocks);

        // Her stok için detaylı istatistikler
        foreach ($stocks as $stock) {
            // Barkod sorgusu oluştur
            $barcodesQuery = Barcode::where('stock_id', $stock->id);
            if ($startDate) { $barcodesQuery->where('created_at', '>=', $startDate); }
            if ($endDate) { $barcodesQuery->where('created_at', '<=', $endDate . ' 23:59:59'); }
            
            // Tarih filtrelenmiş miktarlar
            $filteredBarcodes = $barcodesQuery->with('quantity')->get();
            
            // Toplam üretim miktarı (filtrelenmiş)
            $stock->total_production = $filteredBarcodes->sum(function($barcode) {
                return $barcode->quantity ? $barcode->quantity->quantity : 0;
            });
            
            // Durum bazında miktarlar (filtrelenmiş)
            $stock->waiting_quantity = $filteredBarcodes->where('status', Barcode::STATUS_WAITING)->sum(function($barcode) {
                return $barcode->quantity ? $barcode->quantity->quantity : 0;
            });
            $stock->control_repeat_quantity = $filteredBarcodes->where('status', Barcode::STATUS_CONTROL_REPEAT)->sum(function($barcode) {
                return $barcode->quantity ? $barcode->quantity->quantity : 0;
            });
            $stock->accepted_quantity = $filteredBarcodes->where('status', Barcode::STATUS_PRE_APPROVED)->sum(function($barcode) {
                return $barcode->quantity ? $barcode->quantity->quantity : 0;
            });
            $stock->rejected_quantity = $filteredBarcodes->where('status', Barcode::STATUS_REJECTED)->sum(function($barcode) {
                return $barcode->quantity ? $barcode->quantity->quantity : 0;
            });
            $stock->in_warehouse_quantity = $filteredBarcodes->where('status', Barcode::STATUS_SHIPMENT_APPROVED)->sum(function($barcode) {
                return $barcode->quantity ? $barcode->quantity->quantity : 0;
            });
            $stock->on_delivery_in_warehouse_quantity = $filteredBarcodes->where('status', Barcode::STATUS_CUSTOMER_TRANSFER)->sum(function($barcode) {
                return $barcode->quantity ? $barcode->quantity->quantity : 0;
            });
            $stock->delivered_quantity = $filteredBarcodes->where('status', Barcode::STATUS_DELIVERED)->sum(function($barcode) {
                return $barcode->quantity ? $barcode->quantity->quantity : 0;
            });
            $stock->merged_quantity = $filteredBarcodes->where('status', Barcode::STATUS_MERGED)->sum(function($barcode) {
                return $barcode->quantity ? $barcode->quantity->quantity : 0;
            });
            
            // Red oranı
            $totalQuantity = $stock->total_production;
            $stock->rejection_rate = $totalQuantity > 0 ? round(($stock->rejected_quantity / $totalQuantity) * 100, 2) : 0;
            
            // Teslim oranı
            $stock->delivery_rate = $totalQuantity > 0 ? round(($stock->delivered_quantity / $totalQuantity) * 100, 2) : 0;
            
            // Stokta kalan miktar
            $stock->remaining_stock = $stock->waiting_quantity + $stock->control_repeat_quantity + $stock->accepted_quantity + 
                                    $stock->in_warehouse_quantity + $stock->on_delivery_in_warehouse_quantity;
            
            // Son üretim tarihi (filtrelenmiş)
            $lastBarcode = $barcodesQuery->latest('created_at')->first();
            $stock->last_production_date = $lastBarcode ? $lastBarcode->created_at : null;
            
            // Aktiflik durumu (filtrelenmiş)
            $recentProduction = $barcodesQuery
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
        try {
            $stock = Stock::findOrFail($id);

            // Check if stock can be deleted
            if ($this->canStockBeDeleted($stock)) {
                $stock->delete();
                
                // Cache temizleme
                $this->stockCalculationService->clearCache();
                
                return response()->json(['success' => true, 'message' => 'Stok başarıyla silindi!']);
            } else {
                return response()->json([
                    'success' => false, 
                    'message' => 'Bu stok silinemez çünkü sistemde aktif olarak kullanılmaktadır. İlişkili kayıtlar silinene kadar bu stok silinemez.'
                ], 422);
            }
        } catch (\Exception $e) {
            \Log::error('Stock deletion error: ' . $e->getMessage());
            
            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Bu stok silinemez çünkü sistemde aktif olarak kullanılmaktadır. İlişkili kayıtlar silinene kadar bu stok silinemez.'
                ], 422);
            }
            
            return response()->json([
                'success' => false, 
                'message' => 'Stok silinirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if stock can be safely deleted
     */
    private function canStockBeDeleted($stock)
    {
        // Check if stock has any related barcodes
        return !$stock->barcodes()->exists();
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
