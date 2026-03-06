<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTriggerProductionIdToGranilyaProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('granilya_productions', function (Blueprint $table) {
            $table->unsignedBigInteger('trigger_production_id')->nullable()->after('correction_source_id');
            $table->foreign('trigger_production_id')->references('id')->on('granilya_productions')->onDelete('set null');
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
            $table->dropForeign(['trigger_production_id']);
            $table->dropColumn('trigger_production_id');
        });
    }
}
