<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLabTestsToGranilyaProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('granilya_productions', function (Blueprint $table) {
            $table->enum('sieve_test_result', ['Bekliyor', 'Onay', 'Red'])->default('Bekliyor')->after('is_sieve_residue');
            $table->enum('sieve_reject_reason', ['Dirilik', 'Tozama'])->nullable()->after('sieve_test_result');
            $table->enum('surface_test_result', ['Bekliyor', 'Onay', 'Red'])->default('Bekliyor')->after('sieve_reject_reason');
            $table->enum('surface_reject_reason', ['Renk', 'Parlaklık'])->nullable()->after('surface_test_result');
            $table->enum('arge_test_result', ['Bekliyor', 'Onay', 'Red'])->default('Bekliyor')->after('surface_reject_reason');
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
            $table->dropColumn([
                'sieve_test_result',
                'sieve_reject_reason',
                'surface_test_result',
                'surface_reject_reason',
                'arge_test_result'
            ]);
        });
    }
}
