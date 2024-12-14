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
           `powerandgo`.`autok`.`autok_id` AS `autok_id`,
           `powerandgo`.`autok`.`rendszam` AS `rendszam`,
           `powerandgo`.`autok`.`toltes_szazalek` AS `toltes_szazalek`,
           `powerandgo`.`autok`.`status` AS `status`
       FROM 
           `powerandgo`.`autok`
       JOIN 
           `powerandgo`.`szamlak` 
       ON 
           (`powerandgo`.`autok`.`autok_id` = `powerandgo`.`szamlak`.`auto_azon`)
       WHERE 
           (`powerandgo`.`szamlak`.`szamla_tipus` = 'toltes_buntetes')
       ORDER BY `powerandgo`.`autok`.`autok_id` ASC"
            );
        }
    }
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS toltes_buntetes_autok");

    }
};
