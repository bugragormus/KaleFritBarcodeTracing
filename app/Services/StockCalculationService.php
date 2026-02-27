<?php

namespace App\Services;

use App\Models\Barcode;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class StockCalculationService
{
    /**
     * Tüm stokları getir (barkod'u olmayanlar dahil)
     */
    public function getAllStocks()
    {
        return Cache::remember('all_stocks', 300, function () {
            return Stock::query()
                ->select('id', 'name', 'code')
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Stok durumlarını hesapla (Cache ile)
     */
    public function calculateStockStatuses($startDate = null, $endDate = null)
    {
        // Cache key'i tarih filtrelerine göre oluştur
        $cacheKey = 'stock_statuses';
        if ($startDate) {
            $cacheKey .= '_from_' . $startDate;
        }
        if ($endDate) {
            $cacheKey .= '_to_' . $endDate;
        }
        
        return Cache::remember($cacheKey, 300, function () use ($startDate, $endDate) {
            $query = Stock::query()
                ->select('stocks.id', 'stocks.name', 'stocks.code')
                ->leftJoin('barcodes', function($join) {
                    $join->on('stocks.id', '=', 'barcodes.stock_id')
                        ->whereNull('barcodes.deleted_at');
                })
                ->leftJoin('quantities', 'quantities.id', '=', 'barcodes.quantity_id');

            $statusSum = function($status) {
                return "COALESCE(SUM(CASE WHEN barcodes.status = {$status} AND barcodes.deleted_at IS NULL THEN COALESCE(quantities.quantity, 0) ELSE 0 END), 0)";
            };

            $query->selectRaw("{$statusSum(Barcode::STATUS_WAITING)} as waiting_quantity")
                ->selectRaw("{$statusSum(Barcode::STATUS_CONTROL_REPEAT)} as control_repeat_quantity")
                ->selectRaw("{$statusSum(Barcode::STATUS_PRE_APPROVED)} as pre_approved_quantity")
                ->selectRaw("{$statusSum(Barcode::STATUS_REJECTED)} as rejected_quantity")
                ->selectRaw("{$statusSum(Barcode::STATUS_SHIPMENT_APPROVED)} as shipment_approved_quantity")
                ->selectRaw("{$statusSum(Barcode::STATUS_CUSTOMER_TRANSFER)} as customer_transfer_quantity")
                ->selectRaw("{$statusSum(Barcode::STATUS_DELIVERED)} as delivered_quantity")
                ->selectRaw("{$statusSum(Barcode::STATUS_MERGED)} as merged_quantity");

            if ($startDate) {
                $query->where('barcodes.created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->where('barcodes.created_at', '<=', $endDate . ' 23:59:59');
            }

            return $query->groupBy('stocks.id', 'stocks.name', 'stocks.code')
                ->orderBy('stocks.name')
                ->get()
                ->toArray();
        });
    }

    /**
     * Depo stok durumlarını hesapla (Cache ile)
     */
    public function calculateWarehouseStockStatuses($warehouseId)
    {
        return Cache::remember("warehouse_stock_statuses_{$warehouseId}", 300, function () use ($warehouseId) {
            return DB::table('barcodes')
                ->select([
                    'barcodes.warehouse_id',
                    'stocks.id',
                    'stocks.name',
                    DB::raw('COALESCE(SUM(CASE 
                        WHEN barcodes.status IN (' . implode(',', [
                            Barcode::STATUS_WAITING,
                            Barcode::STATUS_CONTROL_REPEAT,
                            Barcode::STATUS_PRE_APPROVED,
                            Barcode::STATUS_SHIPMENT_APPROVED,
                            Barcode::STATUS_REJECTED
                        ]) . ') 
                        AND barcodes.deleted_at IS NULL 
                        THEN COALESCE(quantities.quantity, 0) 
                        ELSE 0 
                    END), 0) as quantity')
                ])
                ->leftJoin('stocks', 'barcodes.stock_id', '=', 'stocks.id')
                ->leftJoin('quantities', 'quantities.id', '=', 'barcodes.quantity_id')
                ->where('barcodes.warehouse_id', $warehouseId)
                ->whereNull('barcodes.deleted_at')
                ->groupBy('barcodes.warehouse_id', 'stocks.id', 'stocks.name')
                ->orderBy('stocks.name')
                ->get()
                ->toArray();
        });
    }

    /**
     * Depo stok detaylarını hesapla (Cache ile) - Durum bazında ayrıştırma
     */
    public function calculateWarehouseStockDetails($warehouseId)
    {
        return Cache::remember("warehouse_stock_details_{$warehouseId}", 300, function () use ($warehouseId) {
            $statusSum = function($status) {
                return "COALESCE(SUM(CASE WHEN barcodes.status = {$status} AND barcodes.deleted_at IS NULL THEN COALESCE(quantities.quantity, 0) ELSE 0 END), 0)";
            };

            $totalStatusIn = implode(',', [
                Barcode::STATUS_WAITING,
                Barcode::STATUS_CONTROL_REPEAT,
                Barcode::STATUS_PRE_APPROVED,
                Barcode::STATUS_SHIPMENT_APPROVED,
                Barcode::STATUS_REJECTED
            ]);

            return DB::table('barcodes')
                ->select([
                    'barcodes.warehouse_id',
                    'stocks.id',
                    'stocks.name',
                    DB::raw("{$statusSum(Barcode::STATUS_WAITING)} as waiting_quantity"),
                    DB::raw("{$statusSum(Barcode::STATUS_CONTROL_REPEAT)} as control_repeat_quantity"),
                    DB::raw("{$statusSum(Barcode::STATUS_PRE_APPROVED)} as pre_approved_quantity"),
                    DB::raw("{$statusSum(Barcode::STATUS_SHIPMENT_APPROVED)} as shipment_approved_quantity"),
                    DB::raw("{$statusSum(Barcode::STATUS_REJECTED)} as rejected_quantity"),
                    DB::raw("COALESCE(SUM(CASE 
                        WHEN barcodes.status IN ({$totalStatusIn}) AND barcodes.deleted_at IS NULL 
                        THEN COALESCE(quantities.quantity, 0) 
                        ELSE 0 
                    END), 0) as total_quantity")
                ])
                ->leftJoin('stocks', 'barcodes.stock_id', '=', 'stocks.id')
                ->leftJoin('quantities', 'quantities.id', '=', 'barcodes.quantity_id')
                ->where('barcodes.warehouse_id', $warehouseId)
                ->whereNull('barcodes.deleted_at')
                ->groupBy('barcodes.warehouse_id', 'stocks.id', 'stocks.name')
                ->orderBy('stocks.name')
                ->get()
                ->toArray();
        });
    }

    /**
     * Stok toplam miktarını hesapla (Cache ile)
     */
    public function calculateTotalStockQuantity($stockId)
    {
        return Cache::remember("total_stock_quantity_{$stockId}", 300, function () use ($stockId) {
            return Barcode::query()
                ->leftJoin('quantities', 'quantities.id', '=', 'barcodes.quantity_id')
                ->where('barcodes.stock_id', $stockId)
                ->whereNull('barcodes.deleted_at')
                ->sum(DB::raw('COALESCE(quantities.quantity, 0)')) ?: 0;
        });
    }

    /**
     * Stok durumuna göre miktar hesapla (Cache ile)
     */
    public function calculateStockQuantityByStatus($stockId, $status)
    {
        return Cache::remember("stock_quantity_status_{$stockId}_{$status}", 300, function () use ($stockId, $status) {
            return Barcode::query()
                ->leftJoin('quantities', 'quantities.id', '=', 'barcodes.quantity_id')
                ->where('barcodes.stock_id', $stockId)
                ->where('barcodes.status', $status)
                ->whereNull('barcodes.deleted_at')
                ->sum(DB::raw('COALESCE(quantities.quantity, 0)')) ?: 0;
        });
    }

    /**
     * Cache'i temizle
     */
    public function clearCache()
    {
        // Ana cache'leri temizle
        Cache::forget('stock_statuses');
        Cache::forget('all_stocks');
        
        // Warehouse cache'lerini temizle
        $warehouses = DB::table('warehouses')->pluck('id');
        foreach ($warehouses as $warehouseId) {
            Cache::forget("warehouse_stock_statuses_{$warehouseId}");
            Cache::forget("warehouse_stock_details_{$warehouseId}");
        }
        
        // Stock cache'lerini temizle (tarih filtreli olanlar dahil)
        $stocks = DB::table('stocks')->pluck('id');
        foreach ($stocks as $stockId) {
            // Temel cache'ler
            Cache::forget("total_stock_quantity_{$stockId}");
            Cache::forget("stock_details_{$stockId}");
            Cache::forget("production_chart_{$stockId}");
            Cache::forget("barcodes_by_status_{$stockId}");
            Cache::forget("production_by_kiln_{$stockId}");
            Cache::forget("sales_by_company_{$stockId}");
            Cache::forget("monthly_trend_{$stockId}");
            
            // Tarih filtreli cache'ler için pattern matching kullan
            $pattern = "stock_details_{$stockId}_*";
            $keys = Cache::get('cache_keys', []);
            foreach ($keys as $key) {
                if (preg_match("/^stock_details_{$stockId}_(from|to)_/", $key)) {
                    Cache::forget($key);
                }
                if (preg_match("/^production_chart_{$stockId}_(from|to)_/", $key)) {
                    Cache::forget($key);
                }
                if (preg_match("/^barcodes_by_status_{$stockId}_(from|to)_/", $key)) {
                    Cache::forget($key);
                }
                if (preg_match("/^production_by_kiln_{$stockId}_(from|to)_/", $key)) {
                    Cache::forget($key);
                }
                if (preg_match("/^sales_by_company_{$stockId}_(from|to)_/", $key)) {
                    Cache::forget($key);
                }
                if (preg_match("/^monthly_trend_{$stockId}_(from|to)_/", $key)) {
                    Cache::forget($key);
                }
            }
            
            // Status bazlı cache'ler
            foreach (Barcode::STATUSES as $status => $statusName) {
                Cache::forget("stock_quantity_status_{$stockId}_{$status}");
            }
        }
        
        // Stock statuses cache'lerini temizle (tarih filtreli olanlar dahil)
        $pattern = "stock_statuses_*";
        $keys = Cache::get('cache_keys', []);
        foreach ($keys as $key) {
            if (preg_match("/^stock_statuses_(from|to)_/", $key)) {
                Cache::forget($key);
            }
        }
    }

    /**
     * Veri bütünlüğü kontrolü
     */
    public function validateDataIntegrity()
    {
        $issues = [];
        
        // Null quantity kontrolü
        $nullQuantities = DB::table('barcodes')
            ->whereNull('quantity_id')
            ->whereNull('deleted_at')
            ->count();
            
        if ($nullQuantities > 0) {
            $issues[] = "{$nullQuantities} adet barkod için miktar bilgisi eksik";
        }
        
        // Orphaned barcodes kontrolü
        $orphanedBarcodes = DB::table('barcodes')
            ->leftJoin('stocks', 'stocks.id', '=', 'barcodes.stock_id')
            ->leftJoin('quantities', 'quantities.id', '=', 'barcodes.quantity_id')
            ->whereNull('stocks.id')
            ->orWhereNull('quantities.id')
            ->whereNull('barcodes.deleted_at')
            ->count();
            
        if ($orphanedBarcodes > 0) {
            $issues[] = "{$orphanedBarcodes} adet orphaned barkod tespit edildi";
        }
        
        return $issues;
    }

    /**
     * Stok detaylarını getir (tarih filtresi ile)
     */
    public function getStockDetails($stockId, $startDate = null, $endDate = null)
    {
        // Cache key'i tarih filtrelerine göre oluştur
        $cacheKey = "stock_details_{$stockId}";
        if ($startDate) {
            $cacheKey .= '_from_' . $startDate;
        }
        if ($endDate) {
            $cacheKey .= '_to_' . $endDate;
        }
        
        return Cache::remember($cacheKey, 300, function () use ($stockId, $startDate, $endDate) {
            $statusSum = function($status) {
                return "COALESCE(SUM(CASE WHEN barcodes.status = {$status} THEN quantities.quantity ELSE 0 END), 0)";
            };

            $query = Stock::query()
                ->select([
                    'stocks.id',
                    'stocks.name',
                    'stocks.code',
                    DB::raw('COUNT(DISTINCT barcodes.id) as total_barcodes'),
                    DB::raw('COUNT(DISTINCT barcodes.kiln_id) as total_kilns'),
                    DB::raw('COUNT(DISTINCT barcodes.company_id) as total_companies'),
                    DB::raw('COALESCE(SUM(quantities.quantity), 0) as total_quantity'),
                    DB::raw("{$statusSum(Barcode::STATUS_WAITING)} as waiting_quantity"),
                    DB::raw("{$statusSum(Barcode::STATUS_CONTROL_REPEAT)} as control_repeat_quantity"),
                    DB::raw("{$statusSum(Barcode::STATUS_PRE_APPROVED)} as pre_approved_quantity"),
                    DB::raw("{$statusSum(Barcode::STATUS_SHIPMENT_APPROVED)} as shipment_approved_quantity"),
                    DB::raw("{$statusSum(Barcode::STATUS_CUSTOMER_TRANSFER)} as customer_transfer_quantity"),
                    DB::raw("{$statusSum(Barcode::STATUS_DELIVERED)} as delivered_quantity"),
                    DB::raw("{$statusSum(Barcode::STATUS_REJECTED)} as rejected_quantity"),
                    DB::raw("{$statusSum(Barcode::STATUS_MERGED)} as merged_quantity"),
                    DB::raw('MIN(barcodes.created_at) as first_production_date'),
                    DB::raw('MAX(barcodes.created_at) as last_production_date')
                ])
                ->leftJoin('barcodes', function($join) {
                    $join->on('stocks.id', '=', 'barcodes.stock_id')
                        ->whereNull('barcodes.deleted_at');
                })
                ->leftJoin('quantities', 'quantities.id', '=', 'barcodes.quantity_id')
                ->where('stocks.id', $stockId);

            if ($startDate) {
                $query->where('barcodes.created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->where('barcodes.created_at', '<=', $endDate . ' 23:59:59');
            }

            return $query->groupBy('stocks.id', 'stocks.name', 'stocks.code')
                ->first();
        });
    }

    /**
     * Üretim grafiği için veri getir (tarih filtresi ile)
     */
    public function getProductionChartData($stockId, $startDate = null, $endDate = null)
    {
        // Cache key'i tarih filtrelerine göre oluştur
        $cacheKey = "production_chart_{$stockId}";
        if ($startDate) {
            $cacheKey .= '_from_' . $startDate;
        }
        if ($endDate) {
            $cacheKey .= '_to_' . $endDate;
        }
        
        return Cache::remember($cacheKey, 300, function () use ($stockId, $startDate, $endDate) {
            $query = DB::table('barcodes')
                ->select([
                    DB::raw('DATE(barcodes.created_at) as date'),
                    DB::raw('COUNT(barcodes.id) as barcode_count'),
                    DB::raw('COALESCE(SUM(quantities.quantity), 0) as total_quantity')
                ])
                ->leftJoin('quantities', 'quantities.id', '=', 'barcodes.quantity_id')
                ->where('barcodes.stock_id', $stockId)
                ->whereNull('barcodes.deleted_at');

            // Tarih filtreleri ekle
            if ($startDate) {
                $query->where('barcodes.created_at', '>=', $startDate);
            } else {
                // Varsayılan olarak son 30 gün
                $query->where('barcodes.created_at', '>=', now()->subDays(30));
            }
            
            if ($endDate) {
                $query->where('barcodes.created_at', '<=', $endDate . ' 23:59:59');
            }

            return $query->groupBy(DB::raw('DATE(barcodes.created_at)'))
                ->orderBy('date')
                ->get()
                ->toArray();
        });
    }

    /**
     * Durum bazında barkod listesi (tarih filtresi ile)
     */
    public function getBarcodesByStatus($stockId, $startDate = null, $endDate = null)
    {
        // Cache key'i tarih filtrelerine göre oluştur
        $cacheKey = "barcodes_by_status_{$stockId}";
        if ($startDate) {
            $cacheKey .= '_from_' . $startDate;
        }
        if ($endDate) {
            $cacheKey .= '_to_' . $endDate;
        }
        
        return Cache::remember($cacheKey, 300, function () use ($stockId, $startDate, $endDate) {
            $query = DB::table('barcodes')
                ->select([
                    'barcodes.status',
                    DB::raw('COUNT(barcodes.id) as count'),
                    DB::raw('COALESCE(SUM(quantities.quantity), 0) as total_quantity')
                ])
                ->leftJoin('quantities', 'quantities.id', '=', 'barcodes.quantity_id')
                ->where('barcodes.stock_id', $stockId)
                ->whereNull('barcodes.deleted_at');

            if ($startDate) {
                $query->where('barcodes.created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->where('barcodes.created_at', '<=', $endDate . ' 23:59:59');
            }

            return $query->groupBy('barcodes.status')
                ->orderBy('barcodes.status')
                ->get()
                ->toArray();
        });
    }

    /**
     * Fırın bazında üretim (tarih filtresi ile)
     */
    public function getProductionByKiln($stockId, $perPage = 10, $startDate = null, $endDate = null)
    {
        $currentPage = request('page', 1);
        
        $query = DB::table('barcodes')
            ->leftJoin('kilns', 'kilns.id', '=', 'barcodes.kiln_id')
            ->leftJoin('quantities', 'quantities.id', '=', 'barcodes.quantity_id')
            ->where('barcodes.stock_id', $stockId)
            ->whereNull('barcodes.deleted_at');

        if ($startDate) {
            $query->where('barcodes.created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('barcodes.created_at', '<=', $endDate . ' 23:59:59');
        }

        $total = $query->clone()->distinct()->count('kilns.id');
        
        $data = $query->select([
                'kilns.name as kiln_name',
                DB::raw('COUNT(barcodes.id) as barcode_count'),
                DB::raw('COALESCE(SUM(quantities.quantity), 0) as total_quantity'),
                DB::raw('AVG(quantities.quantity) as avg_quantity')
            ])
            ->groupBy('kilns.id', 'kilns.name')
            ->orderBy('total_quantity', 'DESC')
            ->limit($perPage)
            ->offset(($currentPage - 1) * $perPage)
            ->get()
            ->toArray();
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'last_page' => ceil($total / $perPage)
        ];
    }

    /**
     * Müşteri bazında satış (tarih filtresi ile)
     */
    public function getSalesByCompany($stockId, $perPage = 10, $startDate = null, $endDate = null)
    {
        $currentPage = request('page', 1);
        
        $query = DB::table('barcodes')
            ->leftJoin('companies', 'companies.id', '=', 'barcodes.company_id')
            ->leftJoin('quantities', 'quantities.id', '=', 'barcodes.quantity_id')
            ->where('barcodes.stock_id', $stockId)
            ->whereNull('barcodes.deleted_at');

        if ($startDate) {
            $query->where('barcodes.created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('barcodes.created_at', '<=', $endDate . ' 23:59:59');
        }

        $total = $query->clone()->distinct()->count('companies.id');
        
        $data = $query->select([
                'companies.name as company_name',
                DB::raw('COUNT(barcodes.id) as barcode_count'),
                DB::raw('COALESCE(SUM(quantities.quantity), 0) as total_quantity'),
                DB::raw('MIN(barcodes.created_at) as first_sale_date'),
                DB::raw('MAX(barcodes.created_at) as last_sale_date')
            ])
            ->groupBy('companies.id', 'companies.name')
            ->orderBy('total_quantity', 'DESC')
            ->limit($perPage)
            ->offset(($currentPage - 1) * $perPage)
            ->get()
            ->toArray();
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'last_page' => ceil($total / $perPage)
        ];
    }

    /**
     * Aylık trend (tarih filtresi ile)
     */
    public function getMonthlyTrend($stockId, $perPage = 10, $startDate = null, $endDate = null)
    {
        $currentPage = request('page', 1);
        
        $query = DB::table('barcodes')
            ->leftJoin('quantities', 'quantities.id', '=', 'barcodes.quantity_id')
            ->where('barcodes.stock_id', $stockId)
            ->whereNull('barcodes.deleted_at');

        if ($startDate) {
            $query->where('barcodes.created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('barcodes.created_at', '<=', $endDate . ' 23:59:59');
        }

        $total = $query->clone()->distinct()->count(DB::raw("CONCAT(YEAR(barcodes.created_at), '-', MONTH(barcodes.created_at))"));
        
        $data = $query->select([
                DB::raw('MONTH(barcodes.created_at) as month'),
                DB::raw('YEAR(barcodes.created_at) as year'),
                DB::raw('COUNT(barcodes.id) as barcode_count'),
                DB::raw('COALESCE(SUM(quantities.quantity), 0) as total_quantity')
            ])
            ->groupBy(DB::raw('MONTH(barcodes.created_at)'), DB::raw('YEAR(barcodes.created_at)'))
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->limit($perPage)
            ->offset(($currentPage - 1) * $perPage)
            ->get()
            ->toArray();
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'last_page' => ceil($total / $perPage)
        ];
    }
} 