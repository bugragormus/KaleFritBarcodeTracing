<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGranilyaProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('granilya_productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained('stocks')->onDelete('cascade');
            $table->string('load_number');
            $table->foreignId('size_id')->constrained('granilya_sizes')->onDelete('cascade');
            $table->foreignId('crusher_id')->constrained('granilya_crushers')->onDelete('cascade');
            $table->foreignId('quantity_id')->nullable()->constrained('granilya_quantities')->onDelete('set null');
            $table->decimal('custom_quantity', 10, 2)->nullable();
            $table->decimal('used_quantity', 10, 2)->default(0);
            $table->foreignId('company_id')->constrained('granilya_companies')->onDelete('cascade');
            $table->string('pallet_number');
            $table->boolean('is_sieve_residue')->default(false);
            $table->decimal('sieve_residue_quantity', 10, 2)->default(0);
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
        Schema::dropIfExists('granilya_productions');
    }
}
