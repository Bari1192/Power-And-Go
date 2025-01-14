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
            $table->foreignId('szemely_azon')->constrained('persons','id')->onDelete('cascade'); 
            $table->string('terulet',128);
            $table->string('munkakor',45);
            $table->string('beosztas',45);
            $table->enum('munkaber_tipus',['fix','oradij']);
            $table->integer('fizetes'); 
            $table->date('belepes_datum');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
