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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('recipient_name')->nullable()->after('status_shipping');
            $table->string('phone')->nullable()->after('recipient_name');
            $table->text('address_details')->nullable()->after('phone');
            $table->string('courier')->nullable()->after('address_details');
            $table->integer('shipping_cost')->default(0)->after('courier');
            $table->string('resi')->nullable()->after('shipping_cost');
            $table->timestamp('shipped_at')->nullable()->after('resi');
            
            // Perbaiki Enum status_shipping agar memiliki opsi 'processing' dan 'completed' jika belum ada
            // (Kita biarkan enum karena enum susah diubah lewat Schema::table di SQLite/MySQL lama, kita bisa mengubah tipe kolom jadi string saja agar aman)
        });
        
        // Ubah enum jadi string agar lebih dinamis statusnya
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status_shipping')->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'recipient_name',
                'phone',
                'address_details',
                'courier',
                'shipping_cost',
                'resi',
                'shipped_at'
            ]);
        });
    }
};
