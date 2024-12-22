<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('car_user_rents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars')->onDelete('cascade');
            $table->foreignId('kategoria')->constrained('categories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('persons')->onDelete('cascade');

            // Specifikus mezők
            $table->float('nyitas_szaz', 2)->nullable();
            $table->float('nyitas_kw', 1)->nullable();
            $table->float('zaras_szaz', 2)->nullable();
            $table->float('zaras_kw', 1)->nullable();

            $table->date('berles_kezd_datum')->nullable();
            $table->time('berles_kezd_ido')->nullable();
            $table->date('berles_veg_datum')->nullable();
            $table->time('berles_veg_ido')->nullable();
            $table->integer('megtett_tavolsag')->nullable();
            $table->dateTime('parkolas_kezd')->nullable()->default(null);
            $table->dateTime('parkolas_veg')->nullable()->default(null);
            $table->integer('parkolasi_perc')->nullable();
            $table->integer('vezetesi_perc')->nullable();
            $table->integer('berles_osszeg')->nullable();
            $table->timestamp('szamla_kelt')->now();

            // Státusz: folyamatban (0) vagy lezárt (1)
            $table->boolean('rentstatus')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_user_rents');
    }
};
