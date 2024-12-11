<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('szemelyek', function (Blueprint $table) {
            $table->id('szemely_id');
            $table->string('szemely_jelszo', 4);
            $table->string('szig_szam')->unique();
            $table->string('jogos_szam')->unique()->nullable(); 
            $table->date('jogos_erv_kezdete')->nullable(); 
            $table->date('jogos_erv_vege')->nullable(); 
            $table->string('v_nev');
            $table->string('k_nev');
            $table->date('szul_datum');
            $table->string('telefon');
            $table->string('email')->collation('utf8mb4_unicode_ci');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('szemelyek');
    }
};
