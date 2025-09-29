<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDynamicStockQuantitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dynamic_stock_quantities', function (Blueprint $table) {
            $table->id();
            $table->decimal('quantity_1', 10, 2)->default(0)->comment('İlk KG alanı');
            $table->decimal('quantity_2', 10, 2)->default(0)->comment('İkinci KG alanı');
            $table->decimal('total_quantity', 10, 2)->default(0)->comment('Toplam KG');
            $table->string('description_1')->nullable()->comment('İlk alan açıklaması');
            $table->string('description_2')->nullable()->comment('İkinci alan açıklaması');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dynamic_stock_quantities');
    }
}
