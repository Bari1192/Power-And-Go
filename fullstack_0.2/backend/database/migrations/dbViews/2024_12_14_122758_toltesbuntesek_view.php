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

        if (Schema::hasTable('szamlak') && Schema::hasTable('lezart_berlesek')) {
            DB::statement(
                "CREATE OR REPLACE VIEW toltes_buntetes_autok AS
       SELECT 
           `powerandgo`.`cars`.`id` AS `id`,
           `powerandgo`.`cars`.`rendszam` AS `rendszam`,
           `powerandgo`.`cars`.`toltes_szaz` AS `toltes_szaz`,
           `powerandgo`.`cars`.`status` AS `status`
       FROM 
           `powerandgo`.`cars`
       JOIN 
           `powerandgo`.`szamlak` 
       ON 
           (`powerandgo`.`cars`.`id` = `powerandgo`.`szamlak`.`auto_azon`)
       WHERE 
           (`powerandgo`.`szamlak`.`szamla_tipus` = 'toltes_buntetes')
       ORDER BY `powerandgo`.`cars`.`id` ASC"
            );
        }
    }
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS toltes_buntetes_autok");

    }
};
