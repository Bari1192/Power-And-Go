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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->enum('szamla_tipus', ['berles', 'baleset', 'karokozas', 'toltes_buntetes']);
            $table->foreignId('felh_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('szemely_id')->constrained('persons')->onDelete('cascade');
            $table->foreignId('car_id')->constrained('cars')->onDelete('cascade');

            $table->integer('osszeg');
            $table->integer('megtett_tavolsag')->nullable();
            $table->integer('parkolasi_perc')->nullable();
            $table->integer('vezetesi_perc')->nullable();
            $table->date('berles_kezd_datum')->nullable();
            $table->time('berles_kezd_ido')->nullable();
            $table->date('berles_veg_datum')->nullable();
            $table->time('berles_veg_ido')->nullable();
            $table->timestamp('szamla_kelt')->useCurrent();
            $table->enum('szamla_status', ['active', 'pending', 'archiv'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
