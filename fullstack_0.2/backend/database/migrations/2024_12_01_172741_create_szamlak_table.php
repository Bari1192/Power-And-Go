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
            $table->id();
            $table->unsignedBigInteger('felhasznalo_id');
            $table->unsignedBigInteger('lezart_berles_id');
            $table->integer('vegosszeg');
            $table->string('szamla_statusz')->default('Függőben');
            $table->timestamps();

            $table->foreign('felhasznalo_id')->references('felh_id')->on('felhasznalok')->onDelete('cascade');
            $table->foreign('lezart_berles_id')->references('lezart_berles_id')->on('lezart_berlesek')->onDelete('cascade');
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
