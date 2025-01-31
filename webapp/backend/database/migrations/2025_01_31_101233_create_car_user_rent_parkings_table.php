<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_user_rent_parkings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rent_id')->constrained('car_user_rents','id')->onDelete('cascade');
            $table->dateTime('parking_start');
            $table->dateTime('parking_end')->nullable();
            $table->integer('parking_minutes')->nullable();
            $table->integer('parking_cost')->nullable();;
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('car_user_rent_parkings');
    }
};
