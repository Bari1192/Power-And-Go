<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('rendszam', 10)->unique();

            $table->float('toltes_szaz', 2)->default(100.0);
            $table->float('toltes_kw', 1)->default(18.0);
            $table->float('becs_tav', 1)->default(130);

            $table->foreignId('status')->constrained('carstatus','id')->onDelete('cascade');
            $table->foreignId('kategoria')->constrained('categories', 'id')->onDelete('cascade');
            $table->foreignId('felszereltseg')->nullable()->constrained('equipments', 'id')->onDelete('set null');
            $table->foreignId('flotta_azon')->constrained('fleets','id')->onDelete('cascade');

            $table->integer('kilometerora');
            $table->year('gyartasi_ev');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
