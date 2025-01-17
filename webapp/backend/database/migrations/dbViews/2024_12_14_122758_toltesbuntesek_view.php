<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        if (Schema::hasTable('bills') && Schema::hasTable('car_user_rents')) {
            DB::statement(
                "CREATE OR REPLACE VIEW toltes_buntetesek AS
       SELECT 
           `cars`.`id` AS `car_id`,
           `cars`.`plate` AS `plate`,
           `cars`.`power_percent` AS `toltes`,
           `cars`.`status` AS `allapot`
       FROM 
           `cars`
       JOIN 
           `bills` 
       ON 
           `cars`.`id` = `bills`.`car_id`
       WHERE 
           `bills`.`bill_type` = 'charging_penalty'
       ORDER BY `cars`.`id` ASC"
            );
        }
    }
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS toltes_buntetesek");
    }
};
