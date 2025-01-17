<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('person_password',8);
            $table->string('id_card')->unique();
            $table->string('driving_license')->unique()->nullable(); 
            $table->date('license_start_date')->nullable(); 
            $table->date('license_end_date')->nullable(); 
            $table->string('firstname');
            $table->string('lastname');
            $table->date('birth_date');
            $table->string('phone');
            $table->string('email')->collation('utf8mb4_unicode_ci');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};
