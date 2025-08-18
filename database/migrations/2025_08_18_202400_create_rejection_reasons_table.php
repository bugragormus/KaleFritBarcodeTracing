<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rejection_reasons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default rejection reasons
        $reasons = [
            ['name' => 'Beyaz', 'code' => 'beyaz', 'description' => 'Beyaz renk hatası'],
            ['name' => 'Gri', 'code' => 'gri', 'description' => 'Gri renk hatası'],
            ['name' => 'Sütlü', 'code' => 'sutlu', 'description' => 'Sütlü görünüm hatası'],
            ['name' => 'Şeffaf', 'code' => 'seffaf', 'description' => 'Şeffaflık hatası'],
            ['name' => 'Mat', 'code' => 'mat', 'description' => 'Mat görünüm hatası'],
            ['name' => 'Parlak', 'code' => 'parlak', 'description' => 'Parlaklık hatası'],
            ['name' => 'Sarı', 'code' => 'sari', 'description' => 'Sarı renk hatası'],
            ['name' => 'Mavi', 'code' => 'mavi', 'description' => 'Mavi renk hatası'],
            ['name' => 'Yeşil', 'code' => 'yesil', 'description' => 'Yeşil renk hatası'],
            ['name' => 'Kırmızı', 'code' => 'kirmizi', 'description' => 'Kırmızı renk hatası'],
            ['name' => 'Isı', 'code' => 'isi', 'description' => 'Isı ile ilgili hata'],
            ['name' => 'Genleşme', 'code' => 'genlesme', 'description' => 'Genleşme hatası'],
            ['name' => 'ARGE', 'code' => 'arge', 'description' => 'ARGE çalışması'],
            ['name' => 'Diğer', 'code' => 'diger', 'description' => 'Diğer sebepler'],
        ];

        foreach ($reasons as $reason) {
            DB::table('rejection_reasons')->insert($reason);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rejection_reasons');
    }
};
