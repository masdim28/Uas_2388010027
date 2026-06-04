<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('cart_items', function (Blueprint $table) {
        $table->foreignId('product_variant_id')->nullable()->constrained()->onDelete('cascade');
    });
    Schema::table('order_items', function (Blueprint $table) {
        $table->foreignId('product_variant_id')->nullable()->constrained();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
};
