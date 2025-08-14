<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCorrectionFieldsToBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barcodes', function (Blueprint $table) {
            // Düzeltme faaliyeti alanları
            $table->boolean('is_correction')->default(false)->after('is_merged');
            $table->foreignId('correction_source_barcode_id')->nullable()->constrained('barcodes')->after('is_correction');
            $table->integer('correction_quantity')->nullable()->after('correction_source_barcode_id');
            $table->text('correction_note')->nullable()->after('correction_quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barcodes', function (Blueprint $table) {
            $table->dropForeign(['correction_source_barcode_id']);
            $table->dropColumn([
                'is_correction',
                'correction_source_barcode_id',
                'correction_quantity',
                'correction_note'
            ]);
        });
    }
}
