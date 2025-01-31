<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('car_user_rent_parkings')) {
            DB::statement(
                "CREATE OR REPLACE VIEW most_parking_in_one_rent AS 
                    SELECT 
                fleets.carmodel AS model,
                CONCAT(fleets.motor_power,' ','kWh') AS power,
                CONCAT(COUNT(car_user_rent_parkings.rent_id), ' times') AS parkings,
                car_user_rent_parkings.rent_id
                    FROM
                car_user_rent_parkings
                    JOIN car_user_rents ON car_user_rent_parkings.rent_id = car_user_rents.id
                    JOIN cars ON car_user_rents.car_id = cars.id
                    JOIN fleets ON cars.fleet_id = fleets.id  
                    GROUP BY
                car_user_rent_parkings.rent_id, fleets.carmodel, fleets.motor_power
                    ORDER BY
                parkings DESC;"
            );
        }
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS most_parking_in_one_rent");
    }
};
