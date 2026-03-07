<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrdersRemoveProductFields extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // company_name → company_id (FK'ya geçiş için önce nullable ekle)
            $table->unsignedBigInteger('company_id')->nullable()->after('type');
            $table->string('company_type')->nullable()->after('company_id')
                  ->comment('frit | granilya — hangi firma tablosuna referans');
        });

        // product_code ve quantity_kg varsa kaldır
        if (Schema::hasColumn('orders', 'product_code')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('product_code');
            });
        }
        if (Schema::hasColumn('orders', 'quantity_kg')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('quantity_kg');
            });
        }
        // company_name varsa kaldır
        if (Schema::hasColumn('orders', 'company_name')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('company_name');
            });
        }
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['company_id', 'company_type']);
            $table->string('company_name')->nullable();
            $table->string('product_code')->nullable();
            $table->decimal('quantity_kg', 12, 3)->nullable();
        });
    }
}
