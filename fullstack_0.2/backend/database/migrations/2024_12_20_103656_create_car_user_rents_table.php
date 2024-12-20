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
            $table->unsignedBigInteger('kategoria');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('car_id');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->foreign('kategoria')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('persons')->onDelete('cascade');

            // Specifikus mezők
            $table->float('nyitas_szaz', 2)->default(100.00);
            $table->float('nyitas_kw', 1)->default(18.0);
            $table->float('zaras_szaz', 2)->nullable();
            $table->float('zaras_kw', 1)->nullable();

            $table->date('berles_kezd_datum');
            $table->time('berles_kezd_ido');
            $table->date('berles_veg_datum')->nullable();
            $table->time('berles_veg_ido')->nullable();
            $table->integer('megtett_tavolsag')->nullable();
            $table->dateTime('parkolas_kezd')->nullable()->default(null);
            $table->dateTime('parkolas_veg')->nullable()->default(null);
            $table->integer('parkolasi_perc')->nullable();
            $table->integer('vezetesi_perc')->nullable();
            $table->integer('berles_osszeg')->nullable();

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
