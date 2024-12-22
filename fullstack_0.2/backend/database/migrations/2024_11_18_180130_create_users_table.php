<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('szemely_id')->constrained('persons')->onDelete('cascade');
            $table->foreignId('elofiz_id')->constrained('subscriptions')->onDelete('cascade');
            $table->integer('felh_egyenleg')->default(0);
            $table->string('jelszo_2_4', 2);
            $table->string('felh_nev', 20)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
