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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom baru untuk kebutuhan Checkout & WA
            $table->string('phone')->nullable()->after('email');   // Untuk kirim WA otomatis
            $table->text('address')->nullable()->after('phone');    // Untuk alamat pengiriman
            $table->string('gender')->nullable()->after('address'); // Untuk data jenis kelamin
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn(['phone', 'address', 'gender']);
        });
    }
};