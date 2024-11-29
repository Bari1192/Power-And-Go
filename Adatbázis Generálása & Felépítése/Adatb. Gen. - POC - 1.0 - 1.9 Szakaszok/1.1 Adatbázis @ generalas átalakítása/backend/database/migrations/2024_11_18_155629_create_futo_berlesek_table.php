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
        Schema::create('futo_berlesek', function (Blueprint $table) {
            $table->id('futo_id');
            $table->string('rendszam')->unique();
            $table->integer('kat_besorolas');
            $table->string('felh_nev')->unique();
            $table->date('berles_kezd_datum');
            $table->time('berles_kezd_ido');
    
            $table->foreign('rendszam')->references('rendszam')->on('autok')->onDelete('cascade');
            $table->foreign('felh_nev')->references('felh_nev')->on('felhasznalok')->onDelete('cascade');
    
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('futo_berlesek');
    }
};
