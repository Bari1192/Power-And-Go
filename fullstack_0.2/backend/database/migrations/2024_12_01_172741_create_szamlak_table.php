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
        Schema::create('szamlak', function (Blueprint $table) {
            $table->id('szamla_id');
            $table->enum('szamla_tipus', ['berles', 'baleset', 'karokozas', 'toltes_buntetes']);
            $table->unsignedBigInteger('felh_id');
            $table->unsignedBigInteger('szemely_id');
            $table->unsignedBigInteger('auto_azon');

            $table->foreign('felh_id')->references('felh_id')->on('felhasznalok')->onDelete('cascade');
            $table->foreign('szemely_id')->references('szemely_id')->on('szemelyek')->onDelete('cascade');
            $table->foreign('auto_azon')->references('id')->on('cars')->onDelete('cascade');

            $table->integer('osszeg');
            $table->integer('megtett_tavolsag');
            $table->integer('parkolasi_perc');
            $table->integer('vezetesi_perc');
            $table->date('berles_kezd_datum');
            $table->time('berles_kezd_ido');
            $table->date('berles_veg_datum');
            $table->time('berles_veg_ido');
            $table->timestamp('szamla_kelt')->useCurrent();
            $table->enum('szamla_status', ['active', 'pending', 'archiv'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('szamlak');
    }
};
