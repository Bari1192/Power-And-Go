<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('plate', 10)->unique();

            $table->float('power_percent', 2)->default(100.0);
            $table->float('power_kw', 1)->default(18.0);
            $table->float('estimated_range', 1)->default(130);

            $table->foreignId('status')->default(8)->constrained('carstatus', 'id')->onDelete('cascade');
            $table->foreignId('category_id')->default(1)->constrained('categories', 'id')->onDelete('cascade');
            $table->foreignId('equipment_class')->default(1)->constrained('equipments', 'id')->onDelete('cascade');
            $table->foreignId('fleet_id')->default(1)->constrained('fleets', 'id')->onDelete('cascade');

            $table->integer('odometer')->default(0);
            $table->year('manufactured')->default(Carbon::now()->year);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
