<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flotta_tipusok', function (Blueprint $table) {
            $table->id('flotta_id');
            $table->string('gyarto');
            $table->string('tipus');
            $table->integer('teljesitmeny');
            $table->integer('vegsebesseg');
            $table->string('gumimeret');
            $table->integer('hatotav');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('flotta_tipusok');
    }
};
