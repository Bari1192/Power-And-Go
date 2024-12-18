<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('kat_besorolas')->unique();
            $table->unsignedTinyInteger('teljesitmeny');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
