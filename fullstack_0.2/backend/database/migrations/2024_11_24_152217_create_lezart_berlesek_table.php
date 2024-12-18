<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lezart_berlesek', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auto_azon');
            $table->unsignedBigInteger('auto_kat');
            $table->unsignedBigInteger('szemely_azon');
            $table->foreign('auto_azon')->references('id')->on('cars')->onDelete('cascade');
            $table->foreign('auto_kat')->references('kat_id')->on('kategoriak')->onDelete('cascade');
            $table->foreign('szemely_azon')->references('szemely_id')->on('szemelyek')->onDelete('cascade');
            
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
            $table->dateTime('parkolas_veg')->nullable();
            $table->integer('parkolasi_perc')->nullable();
            $table->integer('vezetesi_perc');
            $table->integer('berles_osszeg')->nullable();
            $table->timestamps();
            
            // Kapcsolatok
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('lezart_berlesek');
    }
};
