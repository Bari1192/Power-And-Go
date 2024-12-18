<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('szemely_azon');
            $table->foreign('szemely_azon')->references('id')->on('persons')->onDelete('cascade'); 
            $table->string('terulet');
            $table->string('munkakor');
            $table->string('beosztas');
            $table->string('munkaido');
            $table->integer('fizetes_ossz'); 
            $table->date('belepes_datum');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
