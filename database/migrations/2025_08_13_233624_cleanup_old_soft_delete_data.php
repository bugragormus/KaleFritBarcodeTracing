<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CleanupOldSoftDeleteData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Eski soft delete verilerini temizle (1 yıldan eski)
        $oneYearAgo = now()->subYear()->format('Y-m-d H:i:s');
        
        // Barcodes tablosundaki eski deleted_at verilerini temizle
        if (Schema::hasColumn('barcodes', 'deleted_at')) {
            DB::statement("DELETE FROM barcodes WHERE deleted_at < ?", [$oneYearAgo]);
        }
        
        // Barcode_histories tablosundaki eski deleted_at verilerini temizle (6 aydan eski)
        $sixMonthsAgo = now()->subMonths(6)->format('Y-m-d H:i:s');
        if (Schema::hasColumn('barcode_histories', 'deleted_at')) {
            DB::statement("DELETE FROM barcode_histories WHERE deleted_at < ?", [$sixMonthsAgo]);
        }
        
        // Users tablosundaki eski deleted_at verilerini temizle (1 yıldan eski)
        if (Schema::hasColumn('users', 'deleted_at')) {
            DB::statement("DELETE FROM users WHERE deleted_at < ?", [$oneYearAgo]);
        }
        
        echo "Eski soft delete verileri temizlendi.\n";
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Bu migration geri alınamaz, veri kaybı olur
        echo "Bu migration geri alınamaz.\n";
    }
}
