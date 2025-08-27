<?php

namespace App\Observers;

use App\Models\Barcode;
use App\Services\StockCalculationService;

class BarcodeObserver
{
    protected $stockCalculationService;

    public function __construct(StockCalculationService $stockCalculationService)
    {
        $this->stockCalculationService = $stockCalculationService;
    }

    /**
     * Handle the Barcode "created" event.
     */
    public function created(Barcode $barcode): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Barcode "updated" event.
     */
    public function updated(Barcode $barcode): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Barcode "deleted" event.
     */
    public function deleted(Barcode $barcode): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Barcode "restored" event.
     */
    public function restored(Barcode $barcode): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Barcode "force deleted" event.
     */
    public function forceDeleted(Barcode $barcode): void
    {
        $this->clearCache();
    }

    /**
     * Cache'i temizle
     */
    private function clearCache(): void
    {
        try {
            $this->stockCalculationService->clearCache();
        } catch (\Exception $e) {
            // Log error but don't break the main operation
            \Log::error('Cache temizleme hatası: ' . $e->getMessage());
        }
    }
} 