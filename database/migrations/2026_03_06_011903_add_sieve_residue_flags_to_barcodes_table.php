<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSieveResidueFlagsToBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barcodes', function (Blueprint $table) {
            $table->boolean('is_sieve_residue')->default(false)->after('is_merged');
            $table->boolean('has_sieve_residue')->default(false)->after('is_sieve_residue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barcodes', function (Blueprint $table) {
            $table->dropColumn(['is_sieve_residue', 'has_sieve_residue']);
        });
    }
}
