<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->boolean('tolatokamera')->default(0);
            $table->boolean('savtarto')->default(0);
            $table->boolean('tempomat')->default(0);
            $table->boolean('tolatoradar')->default(0);
            $table->boolean('multif_kormany')->default(0);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
