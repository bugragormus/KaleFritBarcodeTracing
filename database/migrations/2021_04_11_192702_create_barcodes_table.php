<?php

use App\Models\Barcode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barcodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained('stocks');
            $table->foreignId('kiln_id')->constrained('kilns');
            $table->foreignId('quantity_id')->constrained('quantities');
            $table->unsignedInteger('party_number')->nullable();
            $table->unsignedInteger('load_number')->nullable()->comment('Şarj numarası');
            $table->unsignedInteger('rejected_load_number')->nullable()->comment('Rejected şarj numarası');
            $table->unsignedTinyInteger('status')->default(Barcode::STATUS_WAITING);
            $table->unsignedTinyInteger('transfer_status')->nullable();
            $table->timestamp('lab_at')->nullable();
            $table->foreignId('lab_by')->nullable()->constrained('users');
            $table->timestamp('warehouse_transferred_at')->nullable();
            $table->foreignId('warehouse_transferred_by')->nullable()->constrained('users');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses');
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->timestamp('company_transferred_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamp('delivered_at')->nullable();
            $table->foreignId('delivered_by')->nullable()->constrained('users');
            $table->string('note')->nullable();
            $table->string('lab_note')->nullable();
            $table->foreignId('merged_barcode_id')->nullable()->constrained('barcodes');
            $table->foreignId('old_barcode_id')->nullable()->constrained('barcodes');
            $table->boolean('is_merged')->nullable();
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
        Schema::dropIfExists('barcodes');
    }
}
