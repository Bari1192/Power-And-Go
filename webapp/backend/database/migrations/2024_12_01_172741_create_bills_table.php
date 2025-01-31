<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->enum('bill_type', ['rental', 'accident', 'damage', 'charging_penalty']);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('person_id')->constrained('persons')->onDelete('cascade');
            $table->foreignId('car_id')->constrained('cars')->onDelete('cascade');

            $table->integer('total_cost');
            $table->integer('distance')->nullable();
            $table->integer('parking_minutes')->nullable();
            $table->integer('driving_minutes')->nullable();
            $table->datetime('rent_start')->nullable();
            $table->datetime('rent_close')->nullable();
            $table->timestamp('invoice_date')->useCurrent();
            $table->enum('invoice_status', ['active', 'pending', 'archiv'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
