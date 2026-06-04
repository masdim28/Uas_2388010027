<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->integer('price')->default(0)->after('stock');
            $table->integer('weight')->default(500)->after('price');
            $table->string('status')->default('ready')->after('weight');
            $table->string('image')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['price', 'weight', 'status', 'image']);
        });
    }
};
