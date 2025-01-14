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
        Schema::create('carstatus', function (Blueprint $table) {
            $table->id();
            $table->string('status_name',50);
            $table->string('status_descrip',255);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('carstatus');
    }
};
