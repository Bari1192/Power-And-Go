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
                VIEW most_rented_cars AS
       SELECT 
             COUNT(`car_user_rents`.`car_id`) AS `rented_times`,
            `fleets`.`manufacturer`,
            `fleets`.`carmodel`
       FROM 
           `car_user_rents`
       JOIN 
           `cars` 
       ON 
           `car_user_rents`.`car_id` = `cars`.`id`
       JOIN
            `fleets`
       ON 
            `cars`.`flotta_azon`=`fleets`.`id`
       WHERE `car_user_rents`.`rentstatus`= '2'

       GROUP BY 
            `fleets`.`manufacturer`,
            `fleets`.`carmodel`
        ORDER BY `rented_times` DESC
        LIMIT 10"
            );
        }
    }
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS most_rented_cars");
    }
};
