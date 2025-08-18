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

        // Önce tüm stokları al
        $allStocks = $this->stockCalculationService->getAllStocks();
        $allStocks = collect($allStocks);
        
        // Tarih filtrelenmiş verileri al
        $filteredStocks = $this->stockCalculationService->calculateStockStatuses($startDate, $endDate);
        $filteredStocks = collect($filteredStocks);
        
        // Tüm stokları birleştir ve tarih filtrelenmiş verileri ekle
        $stocks = $allStocks->map(function ($stock) use ($filteredStocks) {
            $filteredStock = $filteredStocks->firstWhere('id', $stock->id);
            
            if ($filteredStock) {
                // Tarih filtrelenmiş veri varsa onu kullan
                $stock->waiting_quantity = $filteredStock->waiting_quantity;
                $stock->control_repeat_quantity = $filteredStock->control_repeat_quantity;
                $stock->accepted_quantity = $filteredStock->accepted_quantity;
                $stock->rejected_quantity = $filteredStock->rejected_quantity;
                $stock->in_warehouse_quantity = $filteredStock->in_warehouse_quantity;
                $stock->on_delivery_in_warehouse_quantity = $filteredStock->on_delivery_in_warehouse_quantity;
                $stock->delivered_quantity = $filteredStock->delivered_quantity;
                $stock->merged_quantity = $filteredStock->merged_quantity;
            } else {
                // Tarih filtrelenmiş veri yoksa 0 olarak ayarla
                $stock->waiting_quantity = 0;
                $stock->control_repeat_quantity = 0;
                $stock->accepted_quantity = 0;
                $stock->rejected_quantity = 0;
                $stock->in_warehouse_quantity = 0;
                $stock->on_delivery_in_warehouse_quantity = 0;
                $stock->delivered_quantity = 0;
                $stock->merged_quantity = 0;
            }
            
            return $stock;
        });

        // Ürün koduna göre arama
        $searchCode = request('code');
        if ($searchCode) {
            $stocks = $stocks->filter(function ($stock) use ($searchCode) {
                return stripos($stock->code ?? '', $searchCode) !== false;
            })->values();
        }

        // Her stok için ek hesaplamalar (tarih filtrelenmiş veriler üzerinden)
        foreach ($stocks as $stock) {
            // Toplam üretim miktarı
            $stock->total_production = $stock->waiting_quantity + $stock->control_repeat_quantity + 
                                     $stock->accepted_quantity + $stock->rejected_quantity + 
                                     $stock->in_warehouse_quantity + $stock->on_delivery_in_warehouse_quantity + 
                                     $stock->delivered_quantity + $stock->merged_quantity;
            
            // Red oranı
            $totalQuantity = $stock->total_production;
            $stock->rejection_rate = $totalQuantity > 0 ? round(($stock->rejected_quantity / $totalQuantity) * 100, 2) : 0;
            
            // Teslim oranı
            $stock->delivery_rate = $totalQuantity > 0 ? round(($stock->delivered_quantity / $totalQuantity) * 100, 2) : 0;
            
            // Stokta kalan miktar
            $stock->remaining_stock = $stock->waiting_quantity + $stock->control_repeat_quantity + 
                                    $stock->accepted_quantity + $stock->in_warehouse_quantity + 
                                    $stock->on_delivery_in_warehouse_quantity;
            
            // Aktiflik durumu (son 90 günde üretim var mı?)
            $stock->is_active = ($stock->total_production > 0);
            
            // Son üretim tarihi (tarih filtrelenmiş veriler üzerinden)
            if ($stock->total_production > 0) {
                $lastBarcode = \App\Models\Barcode::where('stock_id', $stock->id)
                    ->when($startDate, function($query) use ($startDate) {
                        return $query->where('created_at', '>=', $startDate);
                    })
                    ->when($endDate, function($query) use ($endDate) {
                        return $query->where('created_at', '<=', $endDate . ' 23:59:59');
                    })
                    ->latest('created_at')
                    ->first();
                $stock->last_production_date = $lastBarcode ? $lastBarcode->created_at : null;
            } else {
                $stock->last_production_date = null;
            }
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
        
        // Tarih filtreleme parametreleri (index metoduyla aynı)
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
        
        // Stok detaylarını hesapla (tarih filtresi ile)
        $stockDetails = $this->stockCalculationService->getStockDetails($id);
        $productionData = $this->stockCalculationService->getProductionChartData($id);
        $barcodesByStatus = $this->stockCalculationService->getBarcodesByStatus($id);
        $productionByKiln = $this->stockCalculationService->getProductionByKiln($id);
        $salesByCompany = $this->stockCalculationService->getSalesByCompany($id);
        $monthlyTrend = $this->stockCalculationService->getMonthlyTrend($id);
        
        // Dosya adına tarih bilgisi ekle
        $fileName = "stok-raporu-{$stock->code}";
        if ($startDate && $endDate) {
            if ($startDate === $endDate) {
                $fileName .= '-' . \Carbon\Carbon::parse($startDate)->format('d-m-Y');
            } else {
                $fileName .= '-' . \Carbon\Carbon::parse($startDate)->format('d-m-Y') . '-to-' . \Carbon\Carbon::parse($endDate)->format('d-m-Y');
            }
        } elseif ($period) {
            $periodNames = [
                'monthly' => 'aylik',
                'quarterly' => '3-aylik', 
                'yearly' => 'yillik',
                'all' => 'tum-zamanlar'
            ];
            $fileName .= '-' . ($periodNames[$period] ?? 'gunluk');
        } else {
            $fileName .= '-' . date('d-m-Y');
        }
        $fileName .= '.xlsx';
        
        return Excel::download(new \App\Exports\StockDetailExport(
            $stock, 
            $stockDetails, 
            $productionData, 
            $barcodesByStatus,
            $productionByKiln,
            $salesByCompany,
            $monthlyTrend
        ), $fileName);
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
        // Tarih filtreleme parametreleri (index metoduyla aynı)
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

        $stocks = $this->stockCalculationService->calculateStockStatuses($startDate, $endDate);

        // Array'i Collection'a çevir
        $stocks = collect($stocks);

        // Her stok için ek hesaplamalar (tarih filtrelenmiş veriler üzerinden)
        foreach ($stocks as $stock) {
            // Toplam üretim miktarı
            $stock->total_production = $stock->waiting_quantity + $stock->control_repeat_quantity + 
                                     $stock->accepted_quantity + $stock->rejected_quantity + 
                                     $stock->in_warehouse_quantity + $stock->on_delivery_in_warehouse_quantity + 
                                     $stock->delivered_quantity + $stock->merged_quantity;
            
            // Red oranı
            $totalQuantity = $stock->total_production;
            $stock->rejection_rate = $totalQuantity > 0 ? round(($stock->rejected_quantity / $totalQuantity) * 100, 2) : 0;
            
            // Teslim oranı
            $stock->delivery_rate = $totalQuantity > 0 ? round(($stock->delivered_quantity / $totalQuantity) * 100, 2) : 0;
            
            // Stokta kalan miktar
            $stock->remaining_stock = $stock->waiting_quantity + $stock->control_repeat_quantity + 
                                    $stock->accepted_quantity + $stock->in_warehouse_quantity + 
                                    $stock->on_delivery_in_warehouse_quantity;
            
            // Aktiflik durumu (son 90 günde üretim var mı?)
            $stock->is_active = ($stock->total_production > 0);
            
            // Son üretim tarihi (tarih filtrelenmiş veriler üzerinden)
            if ($stock->total_production > 0) {
                $lastBarcode = \App\Models\Barcode::where('stock_id', $stock->id)
                    ->when($startDate, function($query) use ($startDate) {
                        return $query->where('created_at', '>=', $startDate);
                    })
                    ->when($endDate, function($query) use ($endDate) {
                        return $query->where('created_at', '<=', $endDate . ' 23:59:59');
                    })
                    ->latest('created_at')
                    ->first();
                $stock->last_production_date = $lastBarcode ? $lastBarcode->created_at : null;
            } else {
                $stock->last_production_date = null;
            }
        }

        // Dosya adına tarih bilgisi ekle
        $fileName = 'stoklar';
        if ($startDate && $endDate) {
            if ($startDate === $endDate) {
                $fileName .= '-' . \Carbon\Carbon::parse($startDate)->format('d-m-Y');
            } else {
                $fileName .= '-' . \Carbon\Carbon::parse($startDate)->format('d-m-Y') . '-to-' . \Carbon\Carbon::parse($endDate)->format('d-m-Y');
            }
        } elseif ($period) {
            $periodNames = [
                'monthly' => 'aylik',
                'quarterly' => '3-aylik', 
                'yearly' => 'yillik',
                'all' => 'tum-zamanlar'
            ];
            $fileName .= '-' . ($periodNames[$period] ?? 'gunluk');
        } else {
            $fileName .= '-' . \Carbon\Carbon::now()->format('d-m-Y');
        }
        $fileName .= '.xlsx';

        return Excel::download(new StockExport(collect($stocks)), $fileName);
    }
}
