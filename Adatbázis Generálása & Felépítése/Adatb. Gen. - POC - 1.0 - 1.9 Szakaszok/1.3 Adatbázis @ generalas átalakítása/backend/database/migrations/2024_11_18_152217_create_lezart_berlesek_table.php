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
        Schema::create('lezart_berlesek', function (Blueprint $table) {
            $table->id('lezart_berles_id');
            $table->string('rendszam_fk', 20);
            $table->integer('kat_besorolas_fk')->default(5);
            $table->string('felh_nev_fk');
            $table->date('berles_kezd_datum');
            $table->time('berles_kezd_ido');
            $table->date('berles_veg_datum');
            $table->time('berles_veg_ido');
            $table->integer('megtett_tavolsag');

            # FK KULCSOK
            $table->foreign('rendszam_fk')->references('rendszam')->on('autok')->onDelete('cascade');
            $table->foreign('felh_nev_fk')->references('felh_nev')->on('felhasznalok')->onDelete('cascade');

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('lezart_berlesek');
    }
};
