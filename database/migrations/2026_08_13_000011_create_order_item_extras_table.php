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
       Schema::create('order_item_extras', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_item_id')->constrained('order_items')->onDelete('cascade');
    $table->foreignId('extra_id')->constrained('extras');
    $table->integer('quantity');
    $table->decimal('price', 10, 2);
    $table->decimal('subtotal', 10, 2);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_extras');
    }
};
