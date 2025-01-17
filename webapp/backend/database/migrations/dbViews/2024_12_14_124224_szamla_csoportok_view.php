<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bills') && Schema::hasTable('car_user_rents')) {
            DB::statement(
                "CREATE OR REPLACE VIEW SzamlakCsoportositva AS
                SELECT
                    `bills`.`szamla_tipus` AS tipus,
                    COUNT(*) AS darabszam
                FROM 
                    `bills`
                GROUP BY 
                    `szamla_tipus`
                ORDER BY 
                    darabszam DESC"
            );
        }
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS SzamlakCsoportositva");
    }
};
