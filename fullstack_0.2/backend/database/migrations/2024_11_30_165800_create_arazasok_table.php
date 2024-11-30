<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arazasok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('elofiz_azon')->constrained('elofizetesek', 'elofiz_id')->onDelete('cascade');
            $table->integer('auto_besorolas')->constrained('kategoriak', 'kat_id')->onDelete('cascade');
            $table->integer('berles_ind');
            $table->integer('vez_perc');
            $table->integer('kedv_vez')->nullable();                    # Kedvezményes vezetés (percdíj, 6:00 - 9:00) - opcionális
            $table->integer('parkolas_perc');                           # Parkolás (percdíj)
            $table->integer('foglalasi_perc');                          # Foglalás (percdíj, 20 perc után)
            $table->integer('kedv_parkolas_perc')->nullable();          # Kedvezményes parkolás (percdíj)
            $table->integer('napidij');                                 # Napidíj
            $table->integer('km_dij');                                  # Ingyenesen (125) megtehető km-en felüli útdíj
            $table->integer('repter_felar')->nullable();                # Reptéri felár transzferrel (reptérre/reptérről)
            $table->integer('repter_felar_terminal')->nullable();       # Reptéri felár terminálnál (reptérre/reptérről)
            $table->integer('zona_felar')->nullable();                  # Külső zóna felár (indítás/zárás)
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('arazasok');
    }
};
