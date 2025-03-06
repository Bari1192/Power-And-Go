<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('persons')->onDelete('cascade');
            $table->foreignId('sub_id')->constrained('subscriptions')->onDelete('cascade');
            $table->integer('account_balance')->default(0);
            $table->string('password_2_4', 2);
            $table->boolean('plant_tree')->default(0);
            $table->boolean('vip_discount')->default(0);
            $table->date('bonus_min_exp')->nullable();
            $table->unsignedInteger('bonus_minutes')->default(0);
            $table->unsignedInteger('driving_minutes')->nullable();
            $table->unsignedInteger('contributions')->nullable();

            $table->string('user_name', 45)->unique();
            $table->string('password', 255);
            $table->rememberToken();

            $table->timestamps();
        });
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('user_name')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
