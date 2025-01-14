<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->boolean('reversing_camera')->default(0);
            $table->boolean('lane_keep_assist')->default(0);
            $table->boolean('adaptive_cruise_control')->default(0);
            $table->boolean('parking_sensors')->default(0);
            $table->boolean('multifunction_wheel')->default(0);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
