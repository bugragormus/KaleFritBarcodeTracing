<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarcodeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barcode_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barcode_id')->constrained('barcodes');
            $table->unsignedTinyInteger('status');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->unsignedTinyInteger('description');
            $table->jsonb('changes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barcode_histories');
    }
}
