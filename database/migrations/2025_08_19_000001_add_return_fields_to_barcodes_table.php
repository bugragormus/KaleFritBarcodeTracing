<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barcodes', function (Blueprint $table) {
            $table->boolean('is_returned')->default(false)->after('is_exceptionally_approved');
            $table->timestamp('returned_at')->nullable()->after('delivered_at');
            $table->unsignedBigInteger('returned_by')->nullable()->after('returned_at');

            $table->foreign('returned_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('barcodes', function (Blueprint $table) {
            if (Schema::hasColumn('barcodes', 'returned_by')) {
                $table->dropForeign(['returned_by']);
                $table->dropColumn('returned_by');
            }
            if (Schema::hasColumn('barcodes', 'returned_at')) {
                $table->dropColumn('returned_at');
            }
            if (Schema::hasColumn('barcodes', 'is_returned')) {
                $table->dropColumn('is_returned');
            }
        });
    }
};


