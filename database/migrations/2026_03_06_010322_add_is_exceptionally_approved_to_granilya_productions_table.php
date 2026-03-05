<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsExceptionallyApprovedToGranilyaProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('granilya_productions', function (Blueprint $table) {
            $table->boolean('is_exceptionally_approved')->default(false)->after('status');
        });
        
        // Convert any existing STATUS_EXCEPTIONAL (12) to STATUS_SHIPMENT_APPROVED (4) and set flag to true
        \DB::table('granilya_productions')
            ->where('status', 12)
            ->update([
                'status' => 4,
                'is_exceptionally_approved' => 1
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('granilya_productions', function (Blueprint $table) {
            $table->dropColumn('is_exceptionally_approved');
        });
    }
}
