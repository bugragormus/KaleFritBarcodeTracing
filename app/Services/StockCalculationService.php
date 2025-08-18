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
        Cache::forget('stock_statuses');
        
        // Warehouse cache'lerini temizle
        $warehouses = DB::table('warehouses')->pluck('id');
        foreach ($warehouses as $warehouseId) {
            Cache::forget("warehouse_stock_statuses_{$warehouseId}");
            Cache::forget("warehouse_stock_details_{$warehouseId}");
        }
        
        // Stock cache'lerini temizle
        $stocks = DB::table('stocks')->pluck('id');
        foreach ($stocks as $stockId) {
            Cache::forget("total_stock_quantity_{$stockId}");
            Cache::forget("stock_details_{$stockId}");
            Cache::forget("production_chart_{$stockId}");
            Cache::forget("barcodes_by_status_{$stockId}");
            Cache::forget("production_by_kiln_{$stockId}");
            Cache::forget("sales_by_company_{$stockId}");
            Cache::forget("monthly_trend_{$stockId}");
            foreach (Barcode::STATUSES as $status => $statusName) {
                Cache::forget("stock_quantity_status_{$stockId}_{$status}");
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
     * Stok detaylarını getir
     */
    public function getStockDetails($stockId)
    {
        return Cache::remember("stock_details_{$stockId}", 300, function () use ($stockId) {
            return DB::select('
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
                WHERE stocks.id = ?
                GROUP BY stocks.id, stocks.name, stocks.code
            ', [
                Barcode::STATUS_WAITING,
                Barcode::STATUS_CONTROL_REPEAT,
                Barcode::STATUS_PRE_APPROVED,
                Barcode::STATUS_SHIPMENT_APPROVED,
                Barcode::STATUS_CUSTOMER_TRANSFER,
                Barcode::STATUS_DELIVERED,
                Barcode::STATUS_REJECTED,
                Barcode::STATUS_MERGED,
                $stockId
            ])[0] ?? null;
        });
    }

    /**
     * Üretim grafiği için veri getir
     */
    public function getProductionChartData($stockId)
    {
        return Cache::remember("production_chart_{$stockId}", 300, function () use ($stockId) {
            return DB::select('
                SELECT 
                    DATE(barcodes.created_at) as date,
                    COUNT(barcodes.id) as barcode_count,
                    COALESCE(SUM(quantities.quantity), 0) as total_quantity
                FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                WHERE barcodes.stock_id = ? 
                AND barcodes.deleted_at IS NULL
                AND barcodes.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY DATE(barcodes.created_at)
                ORDER BY date
            ', [$stockId]);
        });
    }

    /**
     * Durum bazında barkod listesi
     */
    public function getBarcodesByStatus($stockId)
    {
        return Cache::remember("barcodes_by_status_{$stockId}", 300, function () use ($stockId) {
            return DB::select('
                SELECT 
                    barcodes.status,
                    COUNT(barcodes.id) as count,
                    COALESCE(SUM(quantities.quantity), 0) as total_quantity
                FROM barcodes
                LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
                WHERE barcodes.stock_id = ? AND barcodes.deleted_at IS NULL
                GROUP BY barcodes.status
                ORDER BY barcodes.status
            ', [$stockId]);
        });
    }

    /**
     * Fırın bazında üretim
     */
    public function getProductionByKiln($stockId, $perPage = 10)
    {
        $offset = (request('page', 1) - 1) * $perPage;
        
        $data = DB::select('
            SELECT 
                kilns.name as kiln_name,
                COUNT(barcodes.id) as barcode_count,
                COALESCE(SUM(quantities.quantity), 0) as total_quantity,
                AVG(quantities.quantity) as avg_quantity
            FROM barcodes
            LEFT JOIN kilns ON kilns.id = barcodes.kiln_id
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.stock_id = ? AND barcodes.deleted_at IS NULL
            GROUP BY kilns.id, kilns.name
            ORDER BY total_quantity DESC
            LIMIT ? OFFSET ?
        ', [$stockId, $perPage, $offset]);
        
        $total = DB::select('
            SELECT COUNT(DISTINCT kilns.id) as total
            FROM barcodes
            LEFT JOIN kilns ON kilns.id = barcodes.kiln_id
            WHERE barcodes.stock_id = ? AND barcodes.deleted_at IS NULL
        ', [$stockId])[0]->total;
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => request('page', 1),
            'last_page' => ceil($total / $perPage)
        ];
    }

    /**
     * Müşteri bazında satış
     */
    public function getSalesByCompany($stockId, $perPage = 10)
    {
        $offset = (request('page', 1) - 1) * $perPage;
        
        $data = DB::select('
            SELECT 
                companies.name as company_name,
                COUNT(barcodes.id) as barcode_count,
                COALESCE(SUM(quantities.quantity), 0) as total_quantity,
                MIN(barcodes.company_transferred_at) as first_sale_date,
                MAX(barcodes.company_transferred_at) as last_sale_date
            FROM barcodes
            LEFT JOIN companies ON companies.id = barcodes.company_id
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.stock_id = ? 
            AND barcodes.deleted_at IS NULL
            AND barcodes.company_id IS NOT NULL
            GROUP BY companies.id, companies.name
            ORDER BY total_quantity DESC
            LIMIT ? OFFSET ?
        ', [$stockId, $perPage, $offset]);
        
        $total = DB::select('
            SELECT COUNT(DISTINCT companies.id) as total
            FROM barcodes
            LEFT JOIN companies ON companies.id = barcodes.company_id
            WHERE barcodes.stock_id = ? 
            AND barcodes.deleted_at IS NULL
            AND barcodes.company_id IS NOT NULL
        ', [$stockId])[0]->total;
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => request('page', 1),
            'last_page' => ceil($total / $perPage)
        ];
    }

    /**
     * Aylık üretim trendi
     */
    public function getMonthlyTrend($stockId, $perPage = 10)
    {
        $offset = (request('page', 1) - 1) * $perPage;
        
        $data = DB::select('
            SELECT 
                YEAR(barcodes.created_at) as year,
                MONTH(barcodes.created_at) as month,
                COUNT(barcodes.id) as barcode_count,
                COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.stock_id = ? AND barcodes.deleted_at IS NULL
            GROUP BY YEAR(barcodes.created_at), MONTH(barcodes.created_at)
            ORDER BY year DESC, month DESC
            LIMIT ? OFFSET ?
        ', [$stockId, $perPage, $offset]);
        
        $total = DB::select('
            SELECT COUNT(DISTINCT CONCAT(YEAR(barcodes.created_at), "-", MONTH(barcodes.created_at))) as total
            FROM barcodes
            WHERE barcodes.stock_id = ? AND barcodes.deleted_at IS NULL
        ', [$stockId])[0]->total;
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => request('page', 1),
            'last_page' => ceil($total / $perPage)
        ];
    }
} 