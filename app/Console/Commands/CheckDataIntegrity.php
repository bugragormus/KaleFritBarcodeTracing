<?php

namespace App\Console\Commands;

use App\Models\Barcode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckDataIntegrity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:integrity-check {--fix : Fix issues automatically}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check data integrity and optionally fix issues';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Veri bütünlüğü kontrolü başlatılıyor...');
        
        $issues = $this->checkDataIntegrity();
        
        if (empty($issues)) {
            $this->info('✅ Veri bütünlüğü kontrolü tamamlandı. Hiçbir sorun bulunamadı!');
            return 0;
        }
        
        $this->warn('⚠️  ' . count($issues) . ' adet veri bütünlüğü sorunu bulundu:');
        
        foreach ($issues as $issue) {
            $this->error('  • ' . $issue);
        }
        
        if ($this->option('fix')) {
            $this->info('🔧 Sorunlar otomatik olarak düzeltiliyor...');
            $this->fixIssues($issues);
            $this->info('✅ Düzeltme işlemi tamamlandı!');
        } else {
            $this->info('💡 Sorunları otomatik düzeltmek için --fix parametresini kullanın.');
        }
        
        return 0;
    }
    
    /**
     * Veri bütünlüğü kontrollerini yap
     */
    private function checkDataIntegrity()
    {
        $issues = [];
        
        // 1. Orphaned barcodes
        $orphanedBarcodes = Barcode::whereDoesntHave('stock')
            ->orWhereDoesntHave('kiln')
            ->orWhereDoesntHave('quantity')
            ->count();
            
        if ($orphanedBarcodes > 0) {
            $issues[] = "{$orphanedBarcodes} adet orphaned barkod";
        }
        
        // 2. Geçersiz durum değerleri
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
        
        // 3. Tutarsız tarih değerleri
        $inconsistentDates = Barcode::where('lab_at', '<', 'created_at')
            ->orWhere('warehouse_transferred_at', '<', 'lab_at')
            ->orWhere('delivered_at', '<', 'warehouse_transferred_at')
            ->count();
            
        if ($inconsistentDates > 0) {
            $issues[] = "{$inconsistentDates} adet tutarsız tarih değeri";
        }
        
        // 4. Boş zorunlu alanlar
        $emptyRequiredFields = Barcode::whereNull('stock_id')
            ->orWhereNull('kiln_id')
            ->orWhereNull('quantity_id')
            ->count();
            
        if ($emptyRequiredFields > 0) {
            $issues[] = "{$emptyRequiredFields} adet boş zorunlu alan";
        }
        
        // 5. Duplicate load numbers
        $duplicateLoadNumbers = DB::select('
            SELECT COUNT(*) as count FROM (
                SELECT kiln_id, load_number, COUNT(*) as cnt
                FROM barcodes 
                WHERE load_number IS NOT NULL 
                GROUP BY kiln_id, load_number 
                HAVING cnt > 1
            ) as duplicates
        ')[0]->count ?? 0;
        
        if ($duplicateLoadNumbers > 0) {
            $issues[] = "{$duplicateLoadNumbers} adet duplicate şarj numarası";
        }
        
        return $issues;
    }
    
    /**
     * Sorunları otomatik düzelt
     */
    private function fixIssues($issues)
    {
        // 1. Orphaned barcodes'ları sil
        $orphanedBarcodes = Barcode::whereDoesntHave('stock')
            ->orWhereDoesntHave('kiln')
            ->orWhereDoesntHave('quantity');
            
        $orphanedCount = $orphanedBarcodes->count();
        if ($orphanedCount > 0) {
            $orphanedBarcodes->delete();
            $this->info("  ✅ {$orphanedCount} adet orphaned barkod silindi");
        }
        
        // 2. Geçersiz durum değerlerini düzelt
        $invalidStatusBarcodes = Barcode::whereNotIn('status', [
            Barcode::STATUS_WAITING,
            Barcode::STATUS_ACCEPTED,
            Barcode::STATUS_REJECTED,
            Barcode::STATUS_IN_WAREHOUSE,
            Barcode::STATUS_ON_DELIVERY,
            Barcode::STATUS_DELIVERED,
            Barcode::STATUS_ON_DELIVERY_IN_WAREHOUSE,
            Barcode::STATUS_MERGED
        ]);
        
        $invalidCount = $invalidStatusBarcodes->count();
        if ($invalidCount > 0) {
            $invalidStatusBarcodes->update(['status' => Barcode::STATUS_WAITING]);
            $this->info("  ✅ {$invalidCount} adet geçersiz durum değeri düzeltildi");
        }
        
        // 3. Tutarsız tarih değerlerini düzelt
        $inconsistentDates = Barcode::where('lab_at', '<', 'created_at')
            ->orWhere('warehouse_transferred_at', '<', 'lab_at')
            ->orWhere('delivered_at', '<', 'warehouse_transferred_at');
            
        $inconsistentCount = $inconsistentDates->count();
        if ($inconsistentCount > 0) {
            $inconsistentDates->update([
                'lab_at' => null,
                'warehouse_transferred_at' => null,
                'delivered_at' => null
            ]);
            $this->info("  ✅ {$inconsistentCount} adet tutarsız tarih değeri düzeltildi");
        }
        
        // 4. Boş zorunlu alanları kontrol et (silme işlemi yapılmaz, sadece uyarı)
        $emptyRequiredFields = Barcode::whereNull('stock_id')
            ->orWhereNull('kiln_id')
            ->orWhereNull('quantity_id');
            
        $emptyCount = $emptyRequiredFields->count();
        if ($emptyCount > 0) {
            $this->warn("  ⚠️  {$emptyCount} adet boş zorunlu alan bulundu (manuel kontrol gerekli)");
        }
        
        // 5. Duplicate load numbers'ları düzelt
        $duplicates = DB::select('
            SELECT id, kiln_id, load_number
            FROM barcodes 
            WHERE load_number IS NOT NULL 
            AND id NOT IN (
                SELECT MIN(id) 
                FROM barcodes 
                WHERE load_number IS NOT NULL 
                GROUP BY kiln_id, load_number
            )
        ');
        
        $duplicateCount = count($duplicates);
        if ($duplicateCount > 0) {
            $duplicateIds = collect($duplicates)->pluck('id')->toArray();
            Barcode::whereIn('id', $duplicateIds)->delete();
            $this->info("  ✅ {$duplicateCount} adet duplicate şarj numarası düzeltildi");
        }
    }
} 