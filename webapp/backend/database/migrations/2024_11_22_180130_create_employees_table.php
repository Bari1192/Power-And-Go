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
            $table->foreignId('person_id')->constrained('persons','id')->onDelete('cascade'); 
            $table->string('field',128);
            $table->string('role',45);
            $table->string('position',45);
            $table->enum('salary_type',['fix','hourly']);
            $table->integer('salary'); 
            $table->date('hire_date')->default(now());
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
