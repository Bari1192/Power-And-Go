<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_user_rent_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rent_id')->constrained('car_user_rents','id')->onDelete('cascade');
            $table->dateTime('charging_start_date');
            $table->dateTime('charging_end_date');
            $table->integer('charging_time');
            
            $table->float('start_percent', 2)->nullable();
            $table->float('end_percent', 2)->nullable();
            $table->float('start_kw', 1)->nullable();
            $table->float('end_kw', 1)->nullable();

            $table->float('charged_kw')->nullable();
            $table->integer('credits')->nullable();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('car_user_rent_charges');
    }
};
