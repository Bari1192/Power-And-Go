<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_user_rents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('persons')->onDelete('cascade');

            // Specifikus mezők
            $table->float('start_percent', 2)->nullable();
            $table->float('start_kw', 1)->nullable();
            $table->float('end_percent', 2)->nullable();
            $table->float('end_kw', 1)->nullable();

            $table->dateTime('rent_start')->nullable();
            $table->dateTime('rent_close')->nullable();
            $table->integer('distance')->nullable();
            $table->integer('parking_minutes')->nullable();
            $table->integer('driving_minutes')->nullable();
            $table->integer('rental_cost')->nullable();
            $table->timestamp('invoice_date')->now();

            $table->unsignedTinyInteger('rentstatus')->default(1);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('car_user_rents');
    }
};
