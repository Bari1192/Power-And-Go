<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('szemely_id');
            $table->foreign('szemely_id')->references('id')->on('persons')->onDelete('cascade');
            $table->integer('felh_egyenleg')->default(0);
            $table->char('jelszo_2_4', 2);
            $table->string('felh_nev', 20)->unique();

            $table->unsignedBigInteger('elofiz_id');
            $table->foreign('elofiz_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
