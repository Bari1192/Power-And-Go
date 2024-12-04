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
            $table->enum('szamla_tipus', ['berles', 'baleset', 'karokozas', 'magatartas']);
            $table->unsignedBigInteger('felh_id');
            $table->foreign('felh_id')
                ->references('felh_id')
                ->on('felhasznalok')
                ->onDelete('cascade');
            $table->unsignedBigInteger('szemely_id');
            $table->foreign('szemely_id')
                ->references('szemely_id')
                ->on('szemelyek')
                ->onDelete('cascade');
            $table->date('berles_kezd_datum');
            $table->time('berles_kezd_ido');
            $table->date('berles_veg_datum');
            $table->time('berles_veg_ido');
            $table->integer('megtett_tavolsag');
            $table->integer('parkolasi_perc');
            $table->integer('vezetesi_perc');
            $table->integer('berles_osszeg');
            $table->timestamp('szamla_kelt')->useCurrent();
            $table->enum('szamla_status', ['active', 'pending', 'archiv'])->default('pending');
            $table->timestamps();
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
