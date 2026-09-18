<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_accounts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Telegram's own numeric user ID — this is what initData signs and proves
            $table->unsignedBigInteger('telegram_user_id')->unique();

            // Optional, for support/debugging only — never used for auth
            $table->string('telegram_username')->nullable();
            $table->string('telegram_first_name')->nullable();

            $table->timestamp('linked_at')->nullable();
            $table->timestamp('last_login_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_accounts');
    }
};