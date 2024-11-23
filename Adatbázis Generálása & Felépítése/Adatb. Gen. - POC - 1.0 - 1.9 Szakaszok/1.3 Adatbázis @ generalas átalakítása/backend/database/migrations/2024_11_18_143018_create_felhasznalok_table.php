<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('felhasznalok', function (Blueprint $table) {
            $table->id('felh_id');
            $table->unsignedBigInteger('szemely_id_FK');
            $table->integer('felh_egyenleg')->default(0);
            $table->string('jelszo_2_4', 2);
            $table->string('felh_nev')->unique();
            $table->enum('elofiz_kat', ['Power', 'Power-Plus', 'Power-Premium', 'Power-VIP']);
            $table->timestamps();

            $table->foreign('szemely_id_FK')->references('szemely_id')->on('szemelyek')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('felhasznalok');
    }
};
