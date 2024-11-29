<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('autok', function (Blueprint $table) {
            $table->id('autok_id');
            $table->unsignedBigInteger('flotta_id');
            $table->string('rendszam')->unique();
            $table->year('gyartasi_ev');
            $table->integer('km_ora_allas');
            $table->foreign('flotta_id')->references('flotta_id')->on('flotta_tipusok');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('autok');
    }
};
