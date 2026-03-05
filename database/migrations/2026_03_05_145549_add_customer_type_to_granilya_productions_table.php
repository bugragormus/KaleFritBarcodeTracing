<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerTypeToGranilyaProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('granilya_productions', function (Blueprint $table) {
            $table->enum('customer_type', ['İç Müşteri', 'Dış Müşteri'])->default('İç Müşteri')->after('company_id');
        });

        // Make company_id nullable
        DB::statement('ALTER TABLE granilya_productions MODIFY company_id BIGINT UNSIGNED NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert company_id to not null (if needed, though this might fail if data exists, so keeping it nullable in down is sometimes safer, but let's be technically correct)
        // DB::statement('ALTER TABLE granilya_productions MODIFY company_id BIGINT UNSIGNED NOT NULL;');

        Schema::table('granilya_productions', function (Blueprint $table) {
            $table->dropColumn('customer_type');
        });
    }
}
