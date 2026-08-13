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
        Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users');
    $table->string('order_type'); // delivery or pickup
    $table->text('special_note')->nullable();
    $table->decimal('subtotal', 10, 2);
    $table->decimal('extra_total', 10, 2)->default(0);
    $table->decimal('delivery_fee', 10, 2)->default(0);
    $table->decimal('total_amount', 10, 2);
    $table->string('status')->default('pending'); 
    $table->string('delivery_address')->nullable();
    $table->decimal('latitude', 10, 8)->nullable();
    $table->decimal('longitude', 11, 8)->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
