<?php

namespace App\Actions\Barcode;

use App\Models\Barcode;
use App\Models\BarcodeHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateBarcodeAction
{
    /**
     * Barkod bilgilerini günceller ve ilişkili iş lojiklerini yürütür.
     *
     * @param Barcode $barcode
     * @param array $data
     * @param array|null $rejectionReasons
     * @return Barcode
     * @throws ValidationException
     */
    public function execute(Barcode $barcode, array $data, ?array $rejectionReasons = null): Barcode
    {
        return DB::transaction(function () use ($barcode, $data, $rejectionReasons) {
            
            // 1. Statü Değişikliği ve Yan Etkileri
            if (isset($data['status']) && (int) $barcode->status !== (int) $data['status']) {
                $newStatus = (int) $data['status'];

                // Durum geçiş kontrolü
                if (!$barcode->canTransitionTo($newStatus)) {
                    $this->throwValidationError('status', "Geçersiz durum geçişi.");
                }

                // İş kuralları (Firma/Depo zorunluluğu)
                $this->validateStatusRequirements($newStatus, $data);

                // Zaman damgaları ve bayraklar
                $data = $this->prepareStatusAttributes($barcode, $newStatus, $data);
            }

            // 2. Modeli Güncelle
            $barcode->update($data);

            // 3. Red Sebeplerini İşle
            $this->handleRejectionReasons($barcode, $data['status'] ?? null, $rejectionReasons);

            // 4. Tarihçe Kaydı
            BarcodeHistory::create([
                'barcode_id' => $barcode->id,
                'status' => $barcode->status,
                'user_id' => auth()->id(),
                'description' => Barcode::EVENT_UPDATED,
                'changes' => $barcode->getChanges(),
            ]);

            return $barcode;
        });
    }

    /**
     * Statü bazlı zorunlulukları kontrol eder.
     */
    private function validateStatusRequirements(int $newStatus, array $data): void
    {
        if (in_array($newStatus, [Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED])) {
            if (empty($data['company_id'])) {
                $this->throwValidationError('company_id', 'Firma seçimi zorunludur.');
            }
        } else {
            if (empty($data['warehouse_id'])) {
                $this->throwValidationError('warehouse_id', 'Depo seçimi zorunludur.');
            }
        }
    }

    /**
     * Statüye göre ek öznitelikleri hazırlar.
     */
    private function prepareStatusAttributes(Barcode $barcode, int $newStatus, array $data): array
    {
        // Müşteri Transfer/Teslim Edildi: Depo temizle, Firma bazlı temizlik gerekebilir?
        if (in_array($newStatus, [Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED])) {
            $data['warehouse_id'] = null;
        } else {
            $data['company_id'] = null;
        }

        // Laboratuvar işlemleri
        if (in_array($newStatus, [Barcode::STATUS_CONTROL_REPEAT, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_REJECTED])) {
            $data['lab_at'] = now();
            $data['lab_by'] = auth()->id();

            if ($newStatus === Barcode::STATUS_PRE_APPROVED && (int) $barcode->status === Barcode::STATUS_DELIVERED) {
                $data['is_returned'] = true;
                $data['returned_at'] = now();
                $data['returned_by'] = auth()->id();
            }
        }

        // Sevk onayı
        if ($newStatus === Barcode::STATUS_SHIPMENT_APPROVED) {
            $data['warehouse_transferred_at'] = now();
            $data['warehouse_transferred_by'] = auth()->id();
        }

        // Müşteri transfer
        if ($newStatus === Barcode::STATUS_CUSTOMER_TRANSFER) {
            $data['company_transferred_at'] = now();
            if ((int) $barcode->status === Barcode::STATUS_REJECTED) {
                $data['is_exceptionally_approved'] = true;
            }
        }

        // Teslim edildi
        if ($newStatus === Barcode::STATUS_DELIVERED) {
            $data['delivered_at'] = now();
            $data['delivered_by'] = auth()->id();
            if ((int) $barcode->status === Barcode::STATUS_REJECTED) {
                $data['is_exceptionally_approved'] = true;
            }
        }

        return $data;
    }

    /**
     * Red sebeplerini yönetir.
     */
    private function handleRejectionReasons(Barcode $barcode, ?int $status, ?array $rejectionReasons): void
    {
        if ($status === Barcode::STATUS_REJECTED) {
            if (!empty($rejectionReasons)) {
                $barcode->rejectionReasons()->sync($rejectionReasons);
            } else {
                $barcode->rejectionReasons()->detach();
            }
        } else {
            // Red durumu değilse: İstisnai onaylı veya iade edilmiş ürünlerin sebeplerini koru, diğerlerini sil
            if (!$barcode->is_exceptionally_approved && !$barcode->is_returned) {
                $barcode->rejectionReasons()->detach();
            }
        }
    }

    /**
     * Validation hatası fırlatır.
     */
    private function throwValidationError(string $key, string $message): void
    {
        throw ValidationException::withMessages([$key => [$message]]);
    }
}
