<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        if (Schema::hasTable('car_user_rents')) {
            DB::statement(
                "CREATE OR REPLACE 
                VIEW user_rents_db AS
       SELECT 
            COUNT(`car_user_rents`.`user_id`) AS `rents_number`,
           `user_id` AS `person_id`,
           `persons`.`v_nev` AS `first_name`,
           `persons`.`k_nev` AS `last_name`
       FROM 
           `car_user_rents`
       JOIN 
           `persons` 
       ON 
           `car_user_rents`.`user_id` = `persons`.`id`
        GROUP BY 
            `car_user_rents`.`user_id`, `persons`.`v_nev`, `persons`.`k_nev`
       ORDER BY `rents_number` DESC"
            );
        }
    }
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS car_user_rents");
    }
};
