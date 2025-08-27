<?php

namespace App\Http\Middleware;

use App\Models\Barcode;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DataIntegrityCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Sadece admin kullanıcılar için data integrity uyarısı göster
        if (auth()->check() && auth()->user()->hasPermission(1)) { // Admin permission
            $this->checkDataIntegrity();
        }

        return $next($request);
    }

    /**
     * Data integrity kontrolü yap ve uyarı göster
     */
    private function checkDataIntegrity()
    {
        // Cache'de son kontrol zamanını kontrol et (1 saat)
        $lastCheck = Cache::get('data_integrity_last_check');
        if ($lastCheck && now()->diffInHours($lastCheck) < 1) {
            return;
        }

        $issues = $this->getDataIntegrityIssues();
        
        if (!empty($issues)) {
            // Session'a uyarı ekle
            session()->flash('data_integrity_warning', [
                'message' => 'Veri bütünlüğü sorunları tespit edildi!',
                'issues' => $issues,
                'count' => count($issues)
            ]);
        }

        // Son kontrol zamanını cache'e kaydet
        Cache::put('data_integrity_last_check', now(), 3600);
    }

    /**
     * Data integrity sorunlarını kontrol et
     */
    private function getDataIntegrityIssues()
    {
        $issues = [];
        
        // Kritik sorunları kontrol et
        $orphanedBarcodes = Barcode::whereDoesntHave('stock')
            ->orWhereDoesntHave('kiln')
            ->orWhereDoesntHave('quantity')
            ->count();
            
        if ($orphanedBarcodes > 0) {
            $issues[] = "{$orphanedBarcodes} adet orphaned barkod";
        }
        
        $invalidStatusBarcodes = Barcode::whereNotIn('status', [
            Barcode::STATUS_WAITING,
            Barcode::STATUS_ACCEPTED,
            Barcode::STATUS_REJECTED,
            Barcode::STATUS_IN_WAREHOUSE,
            Barcode::STATUS_ON_DELIVERY,
            Barcode::STATUS_DELIVERED,
            Barcode::STATUS_ON_DELIVERY_IN_WAREHOUSE,
            Barcode::STATUS_MERGED
        ])->count();
        
        if ($invalidStatusBarcodes > 0) {
            $issues[] = "{$invalidStatusBarcodes} adet geçersiz durum değeri";
        }
        
        return $issues;
    }
} 