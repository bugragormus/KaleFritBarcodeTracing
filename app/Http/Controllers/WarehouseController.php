<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\CompanyStoreRequest;
use App\Http\Requests\Company\CompanyUpdateRequest;
use App\Http\Requests\Warehouse\WarehouseStoreRequest;
use App\Http\Requests\Warehouse\WarehouseUpdateRequest;
use App\Models\Permission;
use App\Models\Warehouse;
use App\Services\StockCalculationService;

class WarehouseController extends Controller
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
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        $warehouses = Warehouse::withCount([
            'barcodes as in_warehouse_barcodes' => function($query) {
                $query->whereNotIn('status', [
                    \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER,
                    \App\Models\Barcode::STATUS_DELIVERED,
                    \App\Models\Barcode::STATUS_MERGED
                ]);
            },
            'barcodes as waiting_count' => function($query) {
                $query->where('status', \App\Models\Barcode::STATUS_WAITING);
            },
            'barcodes as pre_approved_count' => function($query) {
                $query->where('status', \App\Models\Barcode::STATUS_PRE_APPROVED);
            },
            'barcodes as shipment_approved_count' => function($query) {
                $query->where('status', \App\Models\Barcode::STATUS_SHIPMENT_APPROVED);
            },
            'barcodes as rejected_count' => function($query) {
                $query->where('status', \App\Models\Barcode::STATUS_REJECTED);
            }
        ])->get();

        // Her depo için detaylı istatistikler
        foreach ($warehouses as $warehouse) {
            // Depoda bulunan stok miktarı (müşteri transfer, teslim edildi ve birleştirildi hariç)
            $warehouse->current_stock = $warehouse->barcodes()
                ->whereNotIn('status', [
                    \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER,
                    \App\Models\Barcode::STATUS_DELIVERED,
                    \App\Models\Barcode::STATUS_MERGED
                ])
                ->sum('quantity_id');
            
            // Depoda bulunan barkod sayısı
            $warehouse->current_barcodes = $warehouse->in_warehouse_barcodes;
            
            // Son işlem tarihi (depoda bulunan barkodlardan)
            $lastBarcode = $warehouse->barcodes()
                ->whereNotIn('status', [
                    \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER,
                    \App\Models\Barcode::STATUS_DELIVERED,
                    \App\Models\Barcode::STATUS_MERGED
                ])
                ->latest('created_at')
                ->first();
            $warehouse->last_activity_date = $lastBarcode ? $lastBarcode->created_at : null;
            
            // Aktiflik durumu (son 90 gün içinde depoya giriş yapılıp yapılmadığı)
            $recentActivity = $warehouse->barcodes()
                ->whereNotIn('status', [
                    \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER,
                    \App\Models\Barcode::STATUS_DELIVERED,
                    \App\Models\Barcode::STATUS_MERGED
                ])
                ->where('created_at', '>=', now()->subDays(90))
                ->count();
            $warehouse->is_active = $recentActivity > 0;
            
            // Red oranı
            $totalBarcodes = $warehouse->current_barcodes;
            $rejectedBarcodes = $warehouse->rejected_count;
            $warehouse->rejection_rate = $totalBarcodes > 0 ? 
                round(($rejectedBarcodes / $totalBarcodes) * 100, 2) : 0;
            
            // Sevk onayı oranı
            $shipmentApprovedBarcodes = $warehouse->shipment_approved_count;
            $warehouse->shipment_approval_rate = $totalBarcodes > 0 ? 
                round(($shipmentApprovedBarcodes / $totalBarcodes) * 100, 2) : 0;
        }

        return view('admin.warehouse.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        return view('admin.warehouse.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Warehouse\WarehouseStoreRequest $request
     * @return string
     */
    public function store(WarehouseStoreRequest $request)
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        $data = $request->validated();

        $warehouse = Warehouse::create($data);

        if (!$warehouse) {
            toastr()->error('Depo oluşturulamadı.');
        }

        toastr()->success('Depo başarıyla oluşturuldu.');

        return redirect()->route('warehouse.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        $warehouse = Warehouse::findOrFail($id);

        return view('admin.warehouse.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Warehouse\WarehouseUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(WarehouseUpdateRequest $request, $id)
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        $data = $request->validated();

        $warehouse = Warehouse::findOrFail($id);

        $warehouse->update($data);

        if (!$warehouse) {
            toastr()->error('Depo düzenlenemedi.');
        }

        toastr()->success('Depo başarıyla düzenlendi.');

        return redirect()->route('warehouse.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        $warehouse = Warehouse::findOrFail($id);

        $warehouse->delete();

        if (!$warehouse) {
            toastr()->error('Depo silinemedi.');
        }

        return response()->json(['message' => 'Depo silindi!']);
    }

    public function stockQuantity($id)
    {
        // Debug: Mevcut verileri kontrol et
        $debugInfo = $this->getDebugInfo($id);
        
        // Cache'i temizle
        $this->stockCalculationService->clearCache();
        
        $stockQuantities = $this->stockCalculationService->calculateWarehouseStockStatuses($id);
        $stockDetails = $this->stockCalculationService->calculateWarehouseStockDetails($id);

        return view('admin.warehouse.stock-quantity', compact('stockQuantities', 'stockDetails', 'debugInfo'));
    }

    /**
     * Debug bilgilerini topla
     */
    private function getDebugInfo($warehouseId)
    {
        $debug = [];
        
        // 1. Depo bilgisi
        $debug['warehouse'] = \App\Models\Warehouse::find($warehouseId);
        
        // 2. Bu depoya ait tüm barkodlar
        $debug['all_barcodes'] = \App\Models\Barcode::where('warehouse_id', $warehouseId)
            ->whereNull('deleted_at')
            ->count();
            
        // 3. Depo stoğunda sayılan durumlardaki barkodlar (Beklemede, Kontrol Tekrarı, Ön Onaylı, Sevk Onaylı, Reddedildi)
        $debug['in_warehouse_barcodes'] = \App\Models\Barcode::where('warehouse_id', $warehouseId)
            ->whereIn('status', [
                \App\Models\Barcode::STATUS_WAITING,
                \App\Models\Barcode::STATUS_CONTROL_REPEAT,
                \App\Models\Barcode::STATUS_PRE_APPROVED,
                \App\Models\Barcode::STATUS_SHIPMENT_APPROVED,
                \App\Models\Barcode::STATUS_REJECTED
            ])
            ->whereNull('deleted_at')
            ->count();
            
        // 4. Tüm durumların dağılımı
        $debug['status_distribution'] = \App\Models\Barcode::where('warehouse_id', $warehouseId)
            ->whereNull('deleted_at')
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
            
        // 4.1. Transfer durumlarının dağılımı
        $debug['transfer_status_distribution'] = \App\Models\Barcode::where('warehouse_id', $warehouseId)
            ->whereNull('deleted_at')
            ->selectRaw('transfer_status, COUNT(*) as count')
            ->groupBy('transfer_status')
            ->get();
            
        // 5. Null quantity_id olan barkodlar
        $debug['null_quantity_barcodes'] = \App\Models\Barcode::where('warehouse_id', $warehouseId)
            ->whereNull('quantity_id')
            ->whereNull('deleted_at')
            ->count();
            
        // 6. Orphaned barkodlar (stock_id veya quantity_id null)
        $debug['orphaned_barcodes'] = \App\Models\Barcode::where('warehouse_id', $warehouseId)
            ->whereNull('deleted_at')
            ->where(function($query) {
                $query->whereNull('stock_id')
                      ->orWhereNull('quantity_id');
            })
            ->count();
            
        // 7. Örnek barkod verileri (ilk 5 tane)
        $debug['sample_barcodes'] = \App\Models\Barcode::with(['stock', 'quantity'])
            ->where('warehouse_id', $warehouseId)
            ->whereNull('deleted_at')
            ->limit(5)
            ->get(['id', 'stock_id', 'quantity_id', 'status', 'warehouse_id']);
            
        // 8. Raw SQL sonucu
        $debug['raw_sql_result'] = \DB::select('
            SELECT 
                barcodes.warehouse_id,
                stocks.id,
                stocks.name,
                COALESCE(SUM(CASE 
                    WHEN barcodes.status IN (?, ?, ?, ?, ?) 
                    AND barcodes.deleted_at IS NULL 
                    THEN COALESCE(quantities.quantity, 0) 
                    ELSE 0 
                END), 0) as quantity
            FROM barcodes
            LEFT JOIN stocks ON barcodes.stock_id = stocks.id
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.warehouse_id = ? AND barcodes.deleted_at IS NULL
            GROUP BY barcodes.warehouse_id, stocks.id, stocks.name
            ORDER BY stocks.name
        ', [
            \App\Models\Barcode::STATUS_WAITING,           // Beklemede
            \App\Models\Barcode::STATUS_CONTROL_REPEAT,    // Kontrol Tekrarı
            \App\Models\Barcode::STATUS_PRE_APPROVED,      // Ön Onaylı
            \App\Models\Barcode::STATUS_SHIPMENT_APPROVED, // Sevk Onaylı
            \App\Models\Barcode::STATUS_REJECTED,          // Reddedildi
            $warehouseId
        ]);
        
        // 9. Cache durumu
        $debug['cache_status'] = [
            'warehouse_cache_exists' => \Cache::has("warehouse_stock_statuses_{$warehouseId}"),
            'stock_statuses_cache_exists' => \Cache::has('stock_statuses')
        ];
        
        return $debug;
    }

    /**
     * Cache'i temizle
     */
    public function clearCache($id)
    {
        try {
            $this->stockCalculationService->clearCache();
            return response()->json(['success' => true, 'message' => 'Cache başarıyla temizlendi']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Cache temizlenirken hata: ' . $e->getMessage()]);
        }
    }

    /**
     * Veri düzeltme
     */
    public function fixData($id)
    {
        try {
            \DB::beginTransaction();
            
            $fixedCount = 0;
            
            // 1. Null quantity_id olan barkodları düzelt
            $nullQuantityBarcodes = \App\Models\Barcode::where('warehouse_id', $id)
                ->whereNull('quantity_id')
                ->whereNull('deleted_at')
                ->get();
                
            foreach ($nullQuantityBarcodes as $barcode) {
                // Eğer stock_id varsa, o stock için varsayılan quantity oluştur
                if ($barcode->stock_id) {
                    $quantity = \App\Models\Quantity::create([
                        'quantity' => 1000, // Varsayılan miktar
                        'stock_id' => $barcode->stock_id,
                        'created_by' => auth()->id()
                    ]);
                    
                    $barcode->update(['quantity_id' => $quantity->id]);
                    $fixedCount++;
                }
            }
            
            // 2. "Ön Onaylı" durumundaki barkodları "Sevk Onaylı" durumuna çevir
            $preApprovedBarcodes = \App\Models\Barcode::where('warehouse_id', $id)
                ->where('status', \App\Models\Barcode::STATUS_PRE_APPROVED)
                ->whereNull('deleted_at') 
                ->get();
                
            foreach ($preApprovedBarcodes as $barcode) {
                $barcode->update([
                    'status' => \App\Models\Barcode::STATUS_SHIPMENT_APPROVED,
                    'warehouse_transferred_at' => now(),
                    'warehouse_transferred_by' => auth()->id()
                ]);
                $fixedCount++;
            }
            
            // 3. "Beklemede" durumundaki barkodları "Ön Onaylı" durumuna çevir (opsiyonel)
            $waitingBarcodes = \App\Models\Barcode::where('warehouse_id', $id)
                ->where('status', \App\Models\Barcode::STATUS_WAITING)
                ->whereNull('deleted_at')
                ->get();
                
            foreach ($waitingBarcodes as $barcode) {
                $barcode->update([
                    'status' => \App\Models\Barcode::STATUS_PRE_APPROVED,
                    'lab_at' => now(),
                    'lab_by' => auth()->id()
                ]);
                $fixedCount++;
            }
            
            \DB::commit();
            
            // Cache'i temizle
            $this->stockCalculationService->clearCache();
            
            return response()->json([
                'success' => true, 
                'message' => "{$fixedCount} adet barkod düzeltildi ve cache temizlendi"
            ]);
            
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false, 
                'message' => 'Veri düzeltilirken hata: ' . $e->getMessage()
            ]);
        }
    }
}
