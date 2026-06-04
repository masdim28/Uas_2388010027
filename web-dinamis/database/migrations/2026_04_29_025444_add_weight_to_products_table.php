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
        Schema::table('products', function (Blueprint $table) {
            // Kita tambahkan 'after' agar kolomnya berjejer rapi di sebelah kolom harga
            $table->integer('weight')->default(500)->after('price'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Hapus kolom weight jika migrasi dibatalkan
            $table->dropColumn('weight');
        });
    }
};