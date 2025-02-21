<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // if (Schema::hasTable('bills') && Schema::hasTable('car_user_rents')) {
        //     DB::statement(
        //         "CREATE OR REPLACE VIEW bills_stats_view AS
        //         SELECT 
        //             `powerandgo`.`bills`.`bill_type` AS `carmodel`,
        //             COUNT(`powerandgo`.`bills`.`id`) AS `darabszam`,
        //             (SELECT COUNT(0) FROM `powerandgo`.`cars` WHERE `powerandgo`.`cars`.`status` = 7) AS `status_7`,
        //         FROM 
        //             `powerandgo`.`bills`
        //         GROUP BY 
        //             `powerandgo`.`bills`.`bill_type`
        //         ORDER BY 
        //             `darabszam` DESC;"
        //     );
        // }
    }
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS billsEsAutoStatus");
    }
};
