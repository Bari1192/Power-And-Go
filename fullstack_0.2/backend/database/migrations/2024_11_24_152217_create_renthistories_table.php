<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('renthistories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id');
            $table->unsignedBigInteger('kategoria');
            $table->unsignedBigInteger('szemely_azon');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->foreign('kategoria')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('szemely_azon')->references('id')->on('persons')->onDelete('cascade');
            
            $table->float('nyitas_szaz',2);
            $table->float('nyitas_kw',1);
            $table->float('zaras_szaz',2);
            $table->float('zaras_kw',1);
            
            $table->date('berles_kezd_datum');
            $table->time('berles_kezd_ido');
            $table->date('berles_veg_datum');
            $table->time('berles_veg_ido');
            $table->integer('megtett_tavolsag')->nullable();
            $table->dateTime('parkolas_kezd')->nullable()->default(null);
            $table->dateTime('parkolas_veg')->nullable()->default(null);
            $table->integer('parkolasi_perc')->nullable();
            $table->integer('vezetesi_perc');
            $table->integer('berles_osszeg')->nullable();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('renthistories');
    }
};
