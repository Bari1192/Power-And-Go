<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('flotta_tipusok', function (Blueprint $table) {
            $table->id('flotta_id');
            $table->string('gyarto',30);
            $table->string('tipus',30);
            $table->integer('hatotav');
            $table->integer('teljesitmeny');
            $table->integer('vegsebesseg');
            $table->string('gumimeret',30);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('flotta_tipusok');
    }
};
