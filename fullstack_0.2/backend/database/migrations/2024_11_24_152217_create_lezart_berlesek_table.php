<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lezart_berlesek', function (Blueprint $table) {
            $table->id('lezart_berles_id');
            $table->unsignedBigInteger('auto_azonosito'); // Autók ID FK
            $table->unsignedBigInteger('auto_kategoria'); // Autók kategória
            $table->unsignedBigInteger('szemely_id_fk'); // FK helyes elnevezése

            $table->date('berles_kezd_datum');
            $table->time('berles_kezd_ido');
            $table->date('berles_veg_datum');
            $table->time('berles_veg_ido');
            $table->integer('megtett_tavolsag');

            // FK kapcsolatok
            $table->foreign('auto_azonosito')->references('autok_id')->on('autok')->onDelete('cascade');
            $table->foreign('auto_kategoria')->references('kat_id')->on('kategoriak')->onDelete('cascade');
            $table->foreign('szemely_id_fk')->references('szemely_id')->on('felhasznalok')->onDelete('cascade');

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('lezart_berlesek');
    }
};
