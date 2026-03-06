<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCorrectionFieldsToGranilyaProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('granilya_productions', function (Blueprint $table) {
            $table->boolean('is_correction')->default(false)->after('sieve_residue_quantity');
            $table->unsignedBigInteger('correction_source_id')->nullable()->after('is_correction');
            
            $table->foreign('correction_source_id')
                  ->references('id')
                  ->on('granilya_productions')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('granilya_productions', function (Blueprint $table) {
            $table->dropForeign(['correction_source_id']);
            $table->dropColumn(['is_correction', 'correction_source_id']);
        });
    }
}
