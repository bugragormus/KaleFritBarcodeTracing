<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateBarcodeStatusesToNewWorkflow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Eski durumları yeni durumlara çevir
        // STATUS_ACCEPTED (2) -> STATUS_PRE_APPROVED (3)
        DB::table('barcodes')
            ->where('status', 2)
            ->update(['status' => 3]);

        // STATUS_IN_WAREHOUSE (4) -> STATUS_SHIPMENT_APPROVED (4) - aynı kalıyor
        // STATUS_ON_DELIVERY (5) -> STATUS_CUSTOMER_TRANSFER (6)
        DB::table('barcodes')
            ->where('status', 5)
            ->update(['status' => 6]);

        // STATUS_DELIVERED (6) -> STATUS_DELIVERED (7)
        DB::table('barcodes')
            ->where('status', 6)
            ->update(['status' => 7]);

        // STATUS_ON_DELIVERY_IN_WAREHOUSE (7) -> STATUS_CUSTOMER_TRANSFER (6)
        DB::table('barcodes')
            ->where('status', 7)
            ->update(['status' => 6]);

        // Transfer status alanını temizle - artık kullanılmıyor
        DB::table('barcodes')->update(['transfer_status' => null]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Geri alma işlemleri
        // STATUS_PRE_APPROVED (3) -> STATUS_ACCEPTED (2)
        DB::table('barcodes')
            ->where('status', 3)
            ->update(['status' => 2]);

        // STATUS_CUSTOMER_TRANSFER (6) -> STATUS_ON_DELIVERY (5)
        DB::table('barcodes')
            ->where('status', 6)
            ->update(['status' => 5]);

        // STATUS_DELIVERED (7) -> STATUS_DELIVERED (6)
        DB::table('barcodes')
            ->where('status', 7)
            ->update(['status' => 6]);

        // Transfer status alanını geri al - artık kullanılmıyor
        DB::table('barcodes')->update(['transfer_status' => null]);
    }
}
