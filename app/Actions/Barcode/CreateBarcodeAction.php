<?php

namespace App\Actions\Barcode;

use App\Models\Barcode;
use App\Models\BarcodeHistory;
use App\Models\Kiln;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateBarcodeAction
{
    /**
     * Yeni barkodlar oluşturur (Normal üretim + Düzeltme faaliyeti).
     *
     * @param array $data
     * @param array|null $correctionBarcodes
     * @param array|null $correctionQuantities
     * @return array
     */
    public function execute(array $data, ?array $correctionBarcodes = null, ?array $correctionQuantities = null): array
    {
        return DB::transaction(function () use ($data, $correctionBarcodes, $correctionQuantities) {
            $kiln = Kiln::findOrFail($data['kiln_id']);
            $barcodeIds = [];
            $totalCorrectionQuantity = 0;
            $startLoadNumber = (int) $data['load_number'];
            $normalQuantity = (int) ($data['quantity'] ?? 0);
            $correctionLoadNumberCounter = $startLoadNumber + $normalQuantity;

            // 1. Düzeltme Faaliyeti İşleme
            if (!empty($correctionBarcodes) && is_array($correctionBarcodes)) {
                foreach ($correctionBarcodes as $index => $sourceBarcodeId) {
                    if (!empty($sourceBarcodeId) && !empty($correctionQuantities[$index])) {
                        $sourceBarcode = Barcode::findOrFail($sourceBarcodeId);
                        $correctionQuantity = (int) $correctionQuantities[$index];
                        
                        $correctionBarcode = Barcode::create([
                            'stock_id' => $data['stock_id'],
                            'kiln_id' => $data['kiln_id'],
                            'party_number' => $data['party_number'],
                            'load_number' => $correctionLoadNumberCounter,
                            'quantity_id' => $sourceBarcode->quantity_id,
                            'warehouse_id' => $data['warehouse_id'],
                            'created_by' => auth()->id(),
                            'status' => Barcode::STATUS_WAITING,
                            'is_correction' => true,
                            'correction_source_barcode_id' => $sourceBarcodeId,
                            'correction_quantity' => $correctionQuantity,
                            'correction_note' => "Düzeltme faaliyeti: {$sourceBarcode->stock->name} stoğundan {$sourceBarcode->load_number} şarjından {$correctionQuantity} KG",
                            'note' => $data['note'] ?? ''
                        ]);

                        $barcodeIds[] = $correctionBarcode->id;
                        $totalCorrectionQuantity += $correctionQuantity;
                        $correctionLoadNumberCounter++;

                        $this->createHistory($correctionBarcode, Barcode::EVENT_CREATED);

                        // Kaynak barkodu güncelle
                        $mainStock = Stock::find($data['stock_id']);
                        $stockName = $mainStock ? $mainStock->name : 'Bilinmeyen Stok';
                        
                        $sourceBarcode->update([
                            'status' => Barcode::STATUS_CORRECTED,
                            'note' => ($sourceBarcode->note ? $sourceBarcode->note . ' | ' : '') . 
                                     "Düzeltme faaliyetinde kullanıldı: {$correctionBarcode->load_number} şarjı ({$stockName} stoğunda)"
                        ]);

                        $this->createHistory($sourceBarcode, 'Düzeltme faaliyetinde kullanıldı', [
                            'old_status' => Barcode::STATUS_REJECTED,
                            'new_status' => Barcode::STATUS_CORRECTED,
                            'correction_quantity' => $correctionQuantity,
                            'correction_barcode_id' => $correctionBarcode->id,
                            'note' => 'Quantity bilgisi korundu, sadece status değiştirildi'
                        ]);
                    }
                }
            }

            // 2. Normal Üretim Barkodlarını Oluştur
            for ($i = 0; $i < $normalQuantity; $i++) {
                $barcode = Barcode::create(array_merge($data, [
                    'load_number' => $startLoadNumber + $i,
                    'is_correction' => false,
                    'status' => Barcode::STATUS_WAITING,
                    'created_by' => auth()->id()
                ]));

                $barcodeIds[] = $barcode->id;
                $this->createHistory($barcode, Barcode::EVENT_CREATED);
            }

            // 3. Fırın Şarj Numarasını Güncelle
            $maxLoadNumber = max(
                $startLoadNumber + $normalQuantity - 1,
                $correctionLoadNumberCounter - 1
            );
            $kiln->update(['load_number' => $maxLoadNumber]);

            return [
                'barcode_ids' => $barcodeIds,
                'normal_quantity' => $normalQuantity,
                'total_correction_quantity' => $totalCorrectionQuantity,
                'start_load_number' => $startLoadNumber,
                'correction_load_number_end' => $correctionLoadNumberCounter - 1
            ];
        });
    }

    /**
     * Tarihçe kaydı oluşturur.
     */
    private function createHistory(Barcode $barcode, string $description, ?array $changes = null): void
    {
        try {
            BarcodeHistory::create([
                'barcode_id' => $barcode->id,
                'status' => $barcode->status ?? Barcode::STATUS_WAITING,
                'user_id' => auth()->id(),
                'description' => $description,
                'changes' => $changes
            ]);
        } catch (\Exception $e) {
            Log::error("BarcodeHistory creation error: {$e->getMessage()}");
        }
    }
}
