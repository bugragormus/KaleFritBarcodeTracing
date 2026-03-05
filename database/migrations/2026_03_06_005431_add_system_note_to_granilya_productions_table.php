<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSystemNoteToGranilyaProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('granilya_productions', function (Blueprint $table) {
            $table->text('system_note')->nullable()->after('general_note');
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
            $table->dropColumn('system_note');
        });
    }
}
