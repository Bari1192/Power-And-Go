<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        if (Schema::hasTable('bills') && Schema::hasTable('renthistories')) {
            DB::statement(
                "CREATE OR REPLACE VIEW toltes_buntetes_autok AS
       SELECT 
           `cars`.`id` AS `id`,
           `cars`.`rendszam` AS `rendszam`,
           `cars`.`toltes_szaz` AS `toltes_szaz`,
           `cars`.`status` AS `status`
       FROM 
           `cars`
       JOIN 
           `bills` 
       ON 
           `cars`.`id` = `bills`.`auto_azon`
       WHERE 
           `bills`.`szamla_tipus` = 'toltes_buntetes'
       ORDER BY `cars`.`id` ASC"
            );
        }
    }
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS toltes_buntetes_autok");

    }
};
