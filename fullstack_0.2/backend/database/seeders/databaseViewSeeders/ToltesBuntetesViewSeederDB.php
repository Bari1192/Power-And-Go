<?php

namespace Database\Seeders\databaseViewSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToltesBuntetesViewSeederDB extends Seeder
{
    public function run(): void
    {
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
