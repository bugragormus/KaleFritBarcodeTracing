<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ImproveDataIntegrity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Barcodes tablosu iyileştirmeleri
        Schema::table('barcodes', function (Blueprint $table) {
            // Unique constraint for kiln_id + load_number combination
            $table->unique(['kiln_id', 'load_number'], 'unique_kiln_load_number');
            
            // Indexes for better performance
            $table->index(['status', 'created_at'], 'idx_status_created');
            $table->index(['warehouse_id', 'status'], 'idx_warehouse_status');
            $table->index(['company_id', 'status'], 'idx_company_status');
            $table->index(['lab_at'], 'idx_lab_at');
            $table->index(['created_at'], 'idx_created_at');
            
            // Check constraints for data validation
            $table->check('status >= 0 AND status <= 7');
            $table->check('party_number > 0');
            $table->check('load_number > 0');
            
            // Foreign key constraints with proper cascade
            $table->foreign('merged_barcode_id')
                  ->references('id')
                  ->on('barcodes')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });

        // Users tablosu iyileştirmeleri
        Schema::table('users', function (Blueprint $table) {
            // Unique constraint for registration_number
            $table->unique('registration_number', 'unique_registration_number');
            
            // Index for email searches
            $table->index('email', 'idx_email');
            
            // Check constraints
            $table->check('LENGTH(name) >= 2');
            $table->check('LENGTH(phone) >= 10');
        });

        // Stocks tablosu iyileştirmeleri
        Schema::table('stocks', function (Blueprint $table) {
            // Unique constraint for stock code
            $table->unique('code', 'unique_stock_code');
            
            // Index for name searches
            $table->index('name', 'idx_stock_name');
            
            // Check constraints
            $table->check('LENGTH(name) >= 2');
            $table->check('LENGTH(code) >= 2');
        });

        // Warehouses tablosu iyileştirmeleri
        Schema::table('warehouses', function (Blueprint $table) {
            // Unique constraint for warehouse name
            $table->unique('name', 'unique_warehouse_name');
            
            // Check constraints
            $table->check('LENGTH(name) >= 2');
        });

        // Kilns tablosu iyileştirmeleri
        Schema::table('kilns', function (Blueprint $table) {
            // Unique constraint for kiln name
            $table->unique('name', 'unique_kiln_name');
            
            // Check constraints
            $table->check('LENGTH(name) >= 2');
            $table->check('load_number >= 0');
        });

        // Companies tablosu iyileştirmeleri
        Schema::table('companies', function (Blueprint $table) {
            // Check constraints
            $table->check('LENGTH(name) >= 2');
            $table->check('LENGTH(address) >= 10');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Barcodes tablosu geri alma
        Schema::table('barcodes', function (Blueprint $table) {
            $table->dropUnique('unique_kiln_load_number');
            $table->dropIndex('idx_status_created');
            $table->dropIndex('idx_warehouse_status');
            $table->dropIndex('idx_company_status');
            $table->dropIndex('idx_lab_at');
            $table->dropIndex('idx_created_at');
            $table->dropForeign(['merged_barcode_id']);
        });

        // Users tablosu geri alma
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('unique_registration_number');
            $table->dropIndex('idx_email');
        });

        // Stocks tablosu geri alma
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropUnique('unique_stock_code');
            $table->dropIndex('idx_stock_name');
        });

        // Warehouses tablosu geri alma
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropUnique('unique_warehouse_name');
        });

        // Kilns tablosu geri alma
        Schema::table('kilns', function (Blueprint $table) {
            $table->dropUnique('unique_kiln_name');
        });
    }
}
