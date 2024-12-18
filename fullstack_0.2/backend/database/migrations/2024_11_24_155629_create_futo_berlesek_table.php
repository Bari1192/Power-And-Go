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
            $table->unsignedBigInteger('kategoria'); 
            $table->unsignedBigInteger('szemely_azon');
            
            $table->date('berles_kezd_datum');
            $table->time('berles_kezd_ido');

            // FK kapcsolatok
            $table->foreign('auto_azon')->references('id')->on('cars')->onDelete('cascade');
            $table->foreign('szemely_azon')->references('id')->on('persons')->onDelete('cascade');
            $table->foreign('kategoria')->references('id')->on('categories')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('futo_berlesek');
    }
};