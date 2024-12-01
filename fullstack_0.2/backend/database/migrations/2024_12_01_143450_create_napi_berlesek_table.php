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
        Schema::create('napi_berlesek', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('arazas_id');
            # Autókategórián belüli konkrét autó (pl. 1-es, 2-es, 3-as, 4-es, 5-ös)
            $table->unsignedBigInteger('auto_tipus');
            $table->foreign('auto_tipus')
                ->references('kat_id') // Mire hivatkozik
                ->on('kategoriak')    // Melyik táblában
                ->onDelete('cascade'); // Törlési szabály
            $table->integer('napok');
            $table->integer('ar');
            $table->timestamps();

            $table->foreign('arazas_id')->references('id')->on('arazasok')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('napi_berlesek');
    }
};
