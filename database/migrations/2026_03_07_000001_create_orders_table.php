<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['frit', 'granilya']);
            $table->string('company_name');
            $table->string('product_code')->nullable()->comment('Frit kodu veya Granilya boyutu');
            $table->decimal('quantity_kg', 10, 2)->comment('İstenilen miktar (KG)');
            $table->text('notes')->nullable();
            $table->enum('status', ['open', 'fulfilled', 'partial', 'cancelled'])->default('open');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
