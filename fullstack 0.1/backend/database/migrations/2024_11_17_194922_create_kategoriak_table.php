<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategoriak', function (Blueprint $table) {
            $table->id('kat_id');
            $table->string('rendszam', 20)->unique();
            $table->integer('kat_besorolas');
            $table->foreign('rendszam')->references('rendszam')->on('autok')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('kategoriak');
    }
};
