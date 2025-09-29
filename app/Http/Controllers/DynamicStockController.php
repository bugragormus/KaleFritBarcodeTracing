<?php

namespace App\Http\Controllers;

use App\Models\DynamicStockQuantity;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DynamicStockController extends Controller
{
    /**
     * Dinamik stok miktarlarını getir
     */
    public function index(): JsonResponse
    {
        try {
            $dynamicStock = DynamicStockQuantity::getInstance();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'quantity_1' => $dynamicStock->quantity_1,
                    'quantity_2' => $dynamicStock->quantity_2,
                    'total_quantity' => $dynamicStock->total_quantity,
                    'description_1' => $dynamicStock->description_1,
                    'description_2' => $dynamicStock->description_2
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dinamik stok verileri alınamadı: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dinamik stok miktarlarını güncelle
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'quantity_1' => 'required|numeric|min:0',
                'quantity_2' => 'required|numeric|min:0',
                'description_1' => 'nullable|string|max:255',
                'description_2' => 'nullable|string|max:255'
            ]);

            $dynamicStock = DynamicStockQuantity::getInstance();
            
            $dynamicStock->update([
                'quantity_1' => $request->quantity_1,
                'quantity_2' => $request->quantity_2,
                'description_1' => $request->description_1 ?? $dynamicStock->description_1,
                'description_2' => $request->description_2 ?? $dynamicStock->description_2
            ]);

            // Toplam miktarı hesapla ve güncelle
            $dynamicStock->calculateTotal()->save();

            return response()->json([
                'success' => true,
                'message' => 'Dinamik stok miktarları başarıyla güncellendi',
                'data' => [
                    'quantity_1' => $dynamicStock->quantity_1,
                    'quantity_2' => $dynamicStock->quantity_2,
                    'total_quantity' => $dynamicStock->total_quantity,
                    'description_1' => $dynamicStock->description_1,
                    'description_2' => $dynamicStock->description_2
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz veri: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dinamik stok güncellenirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toplam dinamik stok miktarını getir (sadece toplam değer)
     */
    public function getTotal(): JsonResponse
    {
        try {
            $dynamicStock = DynamicStockQuantity::getInstance();
            
            return response()->json([
                'success' => true,
                'total_quantity' => $dynamicStock->total_quantity
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'total_quantity' => 0,
                'message' => 'Dinamik stok toplamı alınamadı: ' . $e->getMessage()
            ], 500);
        }
    }
}