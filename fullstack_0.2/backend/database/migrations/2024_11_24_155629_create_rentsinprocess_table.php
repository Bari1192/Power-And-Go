<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentsinprocess', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id');
            $table->unsignedBigInteger('kategoria');
            $table->unsignedBigInteger('szemely_azon');

            $table->date('berles_kezd_datum');
            $table->time('berles_kezd_ido');
            $table->float('nyitas_szaz', 2);
            $table->float('nyitas_kw', 1);

            // FK kapcsolatok
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->foreign('szemely_azon')->references('id')->on('persons')->onDelete('cascade');
            $table->foreign('kategoria')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rentsinprocess');
    }
};
