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
        Schema::create('dailyrentals', function (Blueprint $table) {
            $table->id();
            # Autókategórián belüli konkrét autó (pl. 1-es, 2-es, 3-as, 4-es, 5-ös)
            $table->unsignedBigInteger('prices_id');
            $table->foreign('prices_id')->references('id')->on('prices')->onDelete('cascade');
            $table->unsignedBigInteger('category_class');
            $table->foreign('category_class')->references('id')->on('categories')->onDelete('cascade');

            $table->integer('days');
            $table->integer('price');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('dailyrentals');
    }
};
