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
            $table->enum('elofiz_nev',['Power','Power-Plus','Power-Premium','Power-VIP']);
            $table->integer('havi_dij')->nullable();
            $table->integer('eves_dij')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('elofizetesek');
    }
};
