<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryFieldsToGranilyaProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('granilya_productions', function (Blueprint $table) {
            $table->foreignId('delivery_company_id')->nullable()->after('company_id')->constrained('granilya_companies')->onDelete('set null');
            $table->timestamp('delivered_at')->nullable()->after('updated_at');
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
            $table->dropForeign(['delivery_company_id']);
            $table->dropColumn(['delivery_company_id', 'delivered_at']);
        });
    }
}
