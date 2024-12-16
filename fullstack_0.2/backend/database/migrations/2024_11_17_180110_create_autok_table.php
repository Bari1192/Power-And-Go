<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('autok', function (Blueprint $table) {
            $table->id('autok_id');
            $table->string('rendszam', 10)->unique();

            $table->float('toltes_szazalek', 2)->default(100.0);
            $table->float('toltes_kw', 1)->default(18.0);
            $table->float('becsult_hatotav', 1)->default(130);

            $table->foreignId('status')->constrained('carstatus','id')->onDelete('cascade');
            $table->foreignId('kategoria_besorolas_fk')->constrained('kategoriak', 'kat_id')->onDelete('cascade');
            $table->foreignId('felsz_id_fk')->nullable()->constrained('felszereltsegek', 'felsz_id')->onDelete('set null');

            $table->unsignedBigInteger('flotta_id_fk');
            $table->foreign('flotta_id_fk')->references('flotta_id')->on('flotta_tipusok')->onDelete('cascade');
            $table->integer('km_ora_allas');
            $table->year('gyartasi_ev');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('autok');
    }
};
