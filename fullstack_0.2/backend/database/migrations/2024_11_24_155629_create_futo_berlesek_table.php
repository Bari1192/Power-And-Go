<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('futo_berlesek', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auto_azon'); 
            $table->integer('kat_besorolas'); 
            $table->unsignedBigInteger('szemely_id');
            
            $table->date('berles_kezd_datum');
            $table->time('berles_kezd_ido');

            // FK kapcsolatok
            $table->foreign('auto_azon')->references('id')->on('cars')->onDelete('cascade');
            $table->foreign('szemely_id')->references('szemely_id')->on('felhasznalok')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('futo_berlesek');
    }
};