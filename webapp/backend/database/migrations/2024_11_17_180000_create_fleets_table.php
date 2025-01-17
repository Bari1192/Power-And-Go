<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();
            $table->string('manufacturer',30);
            $table->string('carmodel',30);
            $table->integer('driving_range');
            $table->integer('motor_power');
            $table->integer('top_speed');
            $table->string('tire_size',30);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('fleets');
    }
};
