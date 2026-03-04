<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGranilyaProductionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('granilya_production_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_id');
            $table->integer('status');
            $table->unsignedBigInteger('user_id');
            $table->string('description')->nullable();
            $table->json('changes')->nullable();
            $table->timestamps();

            $table->foreign('production_id')->references('id')->on('granilya_productions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('granilya_production_histories');
    }
}
