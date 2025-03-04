<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bonus_minutes_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->unsignedInteger('amount');
            $table->enum('source', ['admin', 'system', 'plant_tree', 'user']);
            $table->enum('type', ['credit', 'debit']);
            $table->string('reason', 255)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('bonus_minutes_transactions');
    }
};
