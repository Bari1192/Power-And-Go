<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elofizetesek', function (Blueprint $table) {
            $table->id('elofiz_id');
            $table->string('elofiz_nev');
            $table->integer('havi_dij');
            $table->integer('eves_dij');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('elofizetesek');
    }
};
