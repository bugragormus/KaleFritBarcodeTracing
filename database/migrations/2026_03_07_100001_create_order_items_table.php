<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            // Frit için: stocks.code — Granilya için: stocks.code (hangi fritten üretildi)
            $table->string('stock_code')->nullable()->comment('Frit stok kodu');
            // Granilya için: granilya_sizes.name — Frit için null
            $table->string('granilya_size')->nullable()->comment('Granilya boyut adı');
            $table->decimal('quantity_kg', 12, 3)->comment('İstenen miktar (KG)');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
