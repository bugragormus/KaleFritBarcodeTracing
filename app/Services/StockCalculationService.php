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
            return DB::select("
                SELECT 
                    stocks.id,
                    stocks.name,
                    stocks.code
                FROM stocks
                ORDER BY stocks.name
            ");
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
            $whereConditions = [];
            $params = [
                Barcode::STATUS_WAITING,
                Barcode::STATUS_CONTROL_REPEAT,
                Barcode::STATUS_PRE_APPROVED,
                Barcode::STATUS_REJECTED,
                Barcode::STATUS_SHIPMENT_APPROVED,
                Barcode::STATUS_CUSTOMER_TRANSFER,
                Barcode::STATUS_DELIVERED,
                Barcode::STATUS_MERGED
            ];
            
            // Tarih filtreleri ekle
            if ($startDate) {
                $whereConditions[] = 'barcodes.created_at >= ?';
                $params[] = $startDate;
            }
            if ($endDate) {
                $whereConditions[] = 'barcodes.created_at <= ?';
                $params[] = $endDate . ' 23:59:59';
            }
            
            $whereClause = '';
            if (!empty($whereConditions)) {
                $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
            }
            
            return DB::select("
                SELECT 
                    stocks.id,
                    stocks.name,
                    stocks.code,
                    COALESCE(SUM(CASE WHEN barcodes.status = ? AND barcodes.deleted_at IS NULL THEN COALESCE(quantities.quantity, 0) ELSE 0 END), 0) as waiting_quantity,
                    COALESCE(SUM(CASE WHEN barcodes.status = ? AND barcodes.deleted_at IS NULL THEN COALESCE(quantities.quantity, 0) ELSE 0 END), 0) as control_repeat_quantity,
                    COALESCE(SUM(CASE WHEN barcodes.status = ? AND barcodes.deleted_at IS NULL THEN COALESCE(quantities.quantity, 0) ELSE 0 END), 0) as accepted_quantity,
                    COALESCE(SUM(CASE WHEN barcodes.status = ? AND barcodes.deleted_at IS NULL THEN COALESCE(quantities.quantity, 0) ELSE 0 END), 0) as rejected_quantity,
                    COALESCE(SUM(CASE WHEN barcodes.status = ? AND barcodes.deleted_at IS NULL THEN COALESCE(quantities.quantity, 0) ELSE 0 END), 0) as in_warehouse_quantity,
                    COALESCE(SUM(CASE WHEN barcodes.status = ? AND barcodes.deleted_at IS NULL THEN COALESCE(quantities.quantity, 0) ELSE 0 END), 0) as on_delivery_in_warehouse_quantity,
                    COALESCE(SUM(CASE WHEN barcodes.status = ? AND barcodes.deleted_at IS NULL THEN COALESCE(quantities.quantity, 0) ELSE 0 END), 0) as delivered_quantity,
                    COALESCE(SUM(CASE WHEN barcodes.status = ? AND barcodes.deleted_at IS NULL THEN COALESCE(quantities.quantity, 0) ELSE 0 END), 0) as merged_quantity
                FROM stocks
                LEFT JOIN barcodes ON stocks.id = barcodes.stock_id AND barcodes.deleted_at IS NULL
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                {$whereClause}
                GROUP BY stocks.id, stocks.name, stocks.code
                ORDER BY stocks.name
            ", $params);
        });
    }

    /**
     * Depo stok durumlarını hesapla (Cache ile)
     */
    public function calculateWarehouseStockStatuses($warehouseId)
    {
        return Cache::remember("warehouse_stock_statuses_{$warehouseId}", 300, function () use ($warehouseId) {
            return DB::select('
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
                Barcode::STATUS_WAITING,           // Beklemede
                Barcode::STATUS_CONTROL_REPEAT,    // Kontrol Tekrarı
                Barcode::STATUS_PRE_APPROVED,      // Ön Onaylı
                Barcode::STATUS_SHIPMENT_APPROVED, // Sevk Onaylı
                Barcode::STATUS_REJECTED,          // Reddedildi
                $warehouseId
            ]);
        });
    }

    /**
     * Depo stok detaylarını hesapla (Cache ile) - Durum bazında ayrıştırma
     */
    public function calculateWarehouseStockDetails($warehouseId)
    {
        return Cache::remember("warehouse_stock_details_{$warehouseId}", 300, function () use ($warehouseId) {
            return DB::select('
                SELECT 
                    barcodes.warehouse_id,
                    stocks.id,
                    stocks.name,
                    COALESCE(SUM(CASE 
                        WHEN barcodes.status = ? AND barcodes.deleted_at IS NULL 
                        THEN COALESCE(quantities.quantity, 0) 
                        ELSE 0 
                    END), 0) as waiting_quantity,
                    COALESCE(SUM(CASE 
                        WHEN barcodes.status = ? AND barcodes.deleted_at IS NULL 
                        THEN COALESCE(quantities.quantity, 0) 
                        ELSE 0 
                    END), 0) as control_repeat_quantity,
                    COALESCE(SUM(CASE 
                        WHEN barcodes.status = ? AND barcodes.deleted_at IS NULL 
                        THEN COALESCE(quantities.quantity, 0) 
                        ELSE 0 
                    END), 0) as pre_approved_quantity,
                    COALESCE(SUM(CASE 
                        WHEN barcodes.status = ? AND barcodes.deleted_at IS NULL 
                        THEN COALESCE(quantities.quantity, 0) 
                        ELSE 0 
                    END), 0) as shipment_approved_quantity,
                    COALESCE(SUM(CASE 
                        WHEN barcodes.status = ? AND barcodes.deleted_at IS NULL 
                        THEN COALESCE(quantities.quantity, 0) 
                        ELSE 0 
                    END), 0) as rejected_quantity,
                    COALESCE(SUM(CASE 
                        WHEN barcodes.status IN (?, ?, ?, ?, ?) AND barcodes.deleted_at IS NULL 
                        THEN COALESCE(quantities.quantity, 0) 
                        ELSE 0 
                    END), 0) as total_quantity
                FROM barcodes
                LEFT JOIN stocks ON barcodes.stock_id = stocks.id
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                WHERE barcodes.warehouse_id = ? AND barcodes.deleted_at IS NULL
                GROUP BY barcodes.warehouse_id, stocks.id, stocks.name
                ORDER BY stocks.name
            ', [
                Barcode::STATUS_WAITING,           // Beklemede
                Barcode::STATUS_CONTROL_REPEAT,    // Kontrol Tekrarı
                Barcode::STATUS_PRE_APPROVED,      // Ön Onaylı
                Barcode::STATUS_SHIPMENT_APPROVED, // Sevk Onaylı
                Barcode::STATUS_REJECTED,          // Reddedildi
                Barcode::STATUS_WAITING,           // Toplam için tekrar
                Barcode::STATUS_CONTROL_REPEAT,    // Toplam için tekrar
                Barcode::STATUS_PRE_APPROVED,      // Toplam için tekrar
                Barcode::STATUS_SHIPMENT_APPROVED, // Toplam için tekrar
                Barcode::STATUS_REJECTED,          // Toplam için tekrar
                $warehouseId
            ]);
        });
    }

    /**
     * Stok toplam miktarını hesapla (Cache ile)
     */
    public function calculateTotalStockQuantity($stockId)
    {
        return Cache::remember("total_stock_quantity_{$stockId}", 300, function () use ($stockId) {
            return DB::select('
                SELECT 
                    COALESCE(SUM(COALESCE(quantities.quantity, 0)), 0) as total_quantity
                FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                WHERE barcodes.stock_id = ? AND barcodes.deleted_at IS NULL
            ', [$stockId])[0]->total_quantity ?? 0;
        });
    }

    /**
     * Stok durumuna göre miktar hesapla (Cache ile)
     */
    public function calculateStockQuantityByStatus($stockId, $status)
    {
        return Cache::remember("stock_quantity_status_{$stockId}_{$status}", 300, function () use ($stockId, $status) {
            return DB::select('
                SELECT 
                    COALESCE(SUM(COALESCE(quantities.quantity, 0)), 0) as quantity
                FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                WHERE barcodes.stock_id = ? AND barcodes.status = ? AND barcodes.deleted_at IS NULL
            ', [$stockId, $status])[0]->quantity ?? 0;
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
            $whereConditions = ['stocks.id = ?'];
            $params = [$stockId];
            
            // Tarih filtreleri ekle
            if ($startDate) {
                $whereConditions[] = 'barcodes.created_at >= ?';
                $params[] = $startDate;
            }
            if ($endDate) {
                $whereConditions[] = 'barcodes.created_at <= ?';
                $params[] = $endDate . ' 23:59:59';
            }
            
            $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
            
            return DB::select("
                SELECT 
                    stocks.id,
                    stocks.name,
                    stocks.code,
                    COUNT(DISTINCT barcodes.id) as total_barcodes,
                    COUNT(DISTINCT barcodes.kiln_id) as total_kilns,
                    COUNT(DISTINCT barcodes.company_id) as total_companies,
                    COALESCE(SUM(quantities.quantity), 0) as total_quantity,
                    COALESCE(SUM(CASE WHEN barcodes.status = ? THEN quantities.quantity ELSE 0 END), 0) as waiting_quantity,
                    COALESCE(SUM(CASE WHEN barcodes.status = ? THEN quantities.quantity ELSE 0 END), 0) as control_repeat_quantity,
                    COALESCE(SUM(CASE WHEN barcodes.status = ? THEN quantities.quantity ELSE 0 END), 0) as pre_approved_quantity,
                    COALESCE(SUM(CASE WHEN barcodes.status = ? THEN quantities.quantity ELSE 0 END), 0) as shipment_approved_quantity,
                    COALESCE(SUM(CASE WHEN barcodes.status = ? THEN quantities.quantity ELSE 0 END), 0) as customer_transfer_quantity,
                    COALESCE(SUM(CASE WHEN barcodes.status = ? THEN quantities.quantity ELSE 0 END), 0) as delivered_quantity,
                    COALESCE(SUM(CASE WHEN barcodes.status = ? THEN quantities.quantity ELSE 0 END), 0) as rejected_quantity,
                    COALESCE(SUM(CASE WHEN barcodes.status = ? THEN quantities.quantity ELSE 0 END), 0) as merged_quantity,
                    MIN(barcodes.created_at) as first_production_date,
                    MAX(barcodes.created_at) as last_production_date
                FROM stocks
                LEFT JOIN barcodes ON stocks.id = barcodes.stock_id AND barcodes.deleted_at IS NULL
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                {$whereClause}
                GROUP BY stocks.id, stocks.name, stocks.code
            ", array_merge([
                Barcode::STATUS_WAITING,
                Barcode::STATUS_CONTROL_REPEAT,
                Barcode::STATUS_PRE_APPROVED,
                Barcode::STATUS_SHIPMENT_APPROVED,
                Barcode::STATUS_CUSTOMER_TRANSFER,
                Barcode::STATUS_DELIVERED,
                Barcode::STATUS_REJECTED,
                Barcode::STATUS_MERGED
            ], $params))[0] ?? null;
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
            $whereConditions = ['barcodes.stock_id = ?', 'barcodes.deleted_at IS NULL'];
            $params = [$stockId];
            
            // Tarih filtreleri ekle
            if ($startDate) {
                $whereConditions[] = 'barcodes.created_at >= ?';
                $params[] = $startDate;
            } else {
                // Varsayılan olarak son 30 gün
                $whereConditions[] = 'barcodes.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)';
            }
            
            if ($endDate) {
                $whereConditions[] = 'barcodes.created_at <= ?';
                $params[] = $endDate . ' 23:59:59';
            }
            
            $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
            
            return DB::select("
                SELECT 
                    DATE(barcodes.created_at) as date,
                    COUNT(barcodes.id) as barcode_count,
                    COALESCE(SUM(quantities.quantity), 0) as total_quantity
                FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                {$whereClause}
                GROUP BY DATE(barcodes.created_at)
                ORDER BY date
            ", $params);
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
            $whereConditions = ['barcodes.stock_id = ?', 'barcodes.deleted_at IS NULL'];
            $params = [$stockId];
            
            // Tarih filtreleri ekle
            if ($startDate) {
                $whereConditions[] = 'barcodes.created_at >= ?';
                $params[] = $startDate;
            }
            if ($endDate) {
                $whereConditions[] = 'barcodes.created_at <= ?';
                $params[] = $endDate . ' 23:59:59';
            }
            
            $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
            
            return DB::select("
                SELECT 
                    barcodes.status,
                    COUNT(barcodes.id) as count,
                    COALESCE(SUM(quantities.quantity), 0) as total_quantity
                FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                {$whereClause}
                GROUP BY barcodes.status
                ORDER BY barcodes.status
            ", $params);
        });
    }

    /**
     * Fırın bazında üretim (tarih filtresi ile)
     */
    public function getProductionByKiln($stockId, $perPage = 10, $startDate = null, $endDate = null)
    {
        $offset = (request('page', 1) - 1) * $perPage;
        
        $whereConditions = ['barcodes.stock_id = ?', 'barcodes.deleted_at IS NULL'];
        $params = [$stockId];
        
        // Tarih filtreleri ekle
        if ($startDate) {
            $whereConditions[] = 'barcodes.created_at >= ?';
            $params[] = $startDate;
        }
        if ($endDate) {
            $whereConditions[] = 'barcodes.created_at <= ?';
            $params[] = $endDate . ' 23:59:59';
        }
        
        $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
        
        $data = DB::select("
            SELECT 
                kilns.name as kiln_name,
                COUNT(barcodes.id) as barcode_count,
                COALESCE(SUM(quantities.quantity), 0) as total_quantity,
                AVG(quantities.quantity) as avg_quantity
            FROM barcodes
            LEFT JOIN kilns ON kilns.id = barcodes.kiln_id
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            {$whereClause}
            GROUP BY kilns.id, kilns.name
            ORDER BY total_quantity DESC
            LIMIT ? OFFSET ?
        ", array_merge($params, [$perPage, $offset]));
        
        $total = DB::select("
            SELECT COUNT(DISTINCT kilns.id) as total
            FROM barcodes
            LEFT JOIN kilns ON kilns.id = barcodes.kiln_id
            {$whereClause}
        ", $params)[0]->total;
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => request('page', 1),
            'last_page' => ceil($total / $perPage)
        ];
    }

    /**
     * Müşteri bazında satış (tarih filtresi ile)
     */
    public function getSalesByCompany($stockId, $perPage = 10, $startDate = null, $endDate = null)
    {
        $offset = (request('page', 1) - 1) * $perPage;
        
        $whereConditions = ['barcodes.stock_id = ?', 'barcodes.deleted_at IS NULL'];
        $params = [$stockId];
        
        // Tarih filtreleri ekle
        if ($startDate) {
            $whereConditions[] = 'barcodes.created_at >= ?';
            $params[] = $startDate;
        }
        if ($endDate) {
            $whereConditions[] = 'barcodes.created_at <= ?';
            $params[] = $endDate . ' 23:59:59';
        }
        
        $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
        
        $data = DB::select("
            SELECT 
                companies.name as company_name,
                COUNT(barcodes.id) as barcode_count,
                COALESCE(SUM(quantities.quantity), 0) as total_quantity,
                MIN(barcodes.created_at) as first_sale_date,
                MAX(barcodes.created_at) as last_sale_date
            FROM barcodes
            LEFT JOIN companies ON companies.id = barcodes.company_id
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            {$whereClause}
            GROUP BY companies.id, companies.name
            ORDER BY total_quantity DESC
            LIMIT ? OFFSET ?
        ", array_merge($params, [$perPage, $offset]));
        
        $total = DB::select("
            SELECT COUNT(DISTINCT companies.id) as total
            FROM barcodes
            LEFT JOIN companies ON companies.id = barcodes.company_id
            {$whereClause}
        ", $params)[0]->total;
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => request('page', 1),
            'last_page' => ceil($total / $perPage)
        ];
    }

    /**
     * Aylık trend (tarih filtresi ile)
     */
    public function getMonthlyTrend($stockId, $perPage = 10, $startDate = null, $endDate = null)
    {
        $offset = (request('page', 1) - 1) * $perPage;
        
        $whereConditions = ['barcodes.stock_id = ?', 'barcodes.deleted_at IS NULL'];
        $params = [$stockId];
        
        // Tarih filtreleri ekle
        if ($startDate) {
            $whereConditions[] = 'barcodes.created_at >= ?';
            $params[] = $startDate;
        }
        if ($endDate) {
            $whereConditions[] = 'barcodes.created_at <= ?';
            $params[] = $endDate . ' 23:59:59';
        }
        
        $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
        
        $data = DB::select("
            SELECT 
                MONTH(barcodes.created_at) as month,
                YEAR(barcodes.created_at) as year,
                COUNT(barcodes.id) as barcode_count,
                COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            {$whereClause}
            GROUP BY MONTH(barcodes.created_at), YEAR(barcodes.created_at)
            ORDER BY year DESC, month DESC
            LIMIT ? OFFSET ?
        ", array_merge($params, [$perPage, $offset]));
        
        $total = DB::select("
            SELECT COUNT(DISTINCT CONCAT(YEAR(barcodes.created_at), '-', MONTH(barcodes.created_at))) as total
            FROM barcodes
            {$whereClause}
        ", $params)[0]->total;
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => request('page', 1),
            'last_page' => ceil($total / $perPage)
        ];
    }
} 