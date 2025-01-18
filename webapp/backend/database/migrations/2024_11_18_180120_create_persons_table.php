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
            $table->string('id_card',8)->unique();
            $table->string('driving_license',8)->unique()->nullable(); 
            $table->date('license_start_date')->nullable(); 
            $table->date('license_end_date')->nullable(); 
            $table->string('firstname',50);
            $table->string('lastname',25);
            $table->date('birth_date');
            $table->string('phone',15);
            $table->string('email',80)->collation('utf8mb4_unicode_ci');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};
