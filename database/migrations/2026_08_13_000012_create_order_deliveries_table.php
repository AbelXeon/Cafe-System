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
       Schema::create('order_deliveries', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained('orders');
    $table->foreignId('delivery_user_id')->nullable()->constrained('users');
    $table->string('status')->default('pending');
    $table->timestamp('assigned_at')->nullable();
    $table->timestamp('accepted_at')->nullable();
    $table->timestamp('picked_up_at')->nullable();
    $table->timestamp('delivered_at')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_deliveries');
    }
};
