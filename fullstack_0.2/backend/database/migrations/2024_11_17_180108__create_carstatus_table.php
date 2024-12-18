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
            $table->enum('status_name',['Szabad','Foglalva','Bérlés alatt','Szervízre vár','Tisztításra vár','Kritikus töltés']);
            $table->string('status_descrip',255);
            $table->timestamp('created')->useCurrent();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('carstatus');
    }
};
