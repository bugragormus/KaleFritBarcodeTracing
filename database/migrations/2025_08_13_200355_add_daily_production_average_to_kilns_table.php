<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDailyProductionAverageToKilnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kilns', function (Blueprint $table) {
            $table->decimal('daily_production_average', 10, 2)->default(0)->after('load_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kilns', function (Blueprint $table) {
            $table->dropColumn('daily_production_average');
        });
    }
}
