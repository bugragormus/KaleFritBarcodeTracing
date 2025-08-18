<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barcode_rejection_reason', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barcode_id')->constrained('barcodes')->onDelete('cascade');
            $table->foreignId('rejection_reason_id')->constrained('rejection_reasons')->onDelete('cascade');
            $table->timestamps();
            
            // Ensure unique combinations
            $table->unique(['barcode_id', 'rejection_reason_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barcode_rejection_reason');
    }
};
