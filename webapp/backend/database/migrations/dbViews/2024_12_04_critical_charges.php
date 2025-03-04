<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        if (Schema::hasTable('bills') && Schema::hasTable('fleets') && Schema::hasTable('cars') && Schema::hasTable('carstatus') && Schema::hasTable('car_user_rents')) {
            DB::statement(
                "CREATE OR REPLACE VIEW critical_charges AS
                SELECT 
                    DISTINCT(c.plate) AS rendszam,
                    CONCAT(f.manufacturer, ' ', f.carmodel) AS tipus,
                    CONCAT(f.motor_power,' ','kw') AS kW,
                    CONCAT(c.power_percent,' ','%') AS power,
                    CONCAT(cur.start_percent,' ','%')  AS startpercent,
                    CONCAT((cur.driving_minutes + cur.parking_minutes),' ','perc') AS berles_ido,
                    CONCAT(cur.end_percent,' ','%') AS endpercent,
                    cur.id AS rent_azon,
                    cur.user_id AS user_azon,
                    cur.rent_start AS berles_kezdete,
                    cur.rent_close AS berles_vege
                FROM cars c
                INNER JOIN fleets f ON c.fleet_id = f.id
                INNER JOIN car_user_rents cur ON c.id = cur.car_id
                INNER JOIN bills b ON c.id = b.car_id
                WHERE c.status = 7
                AND b.bill_type = 'charging_penalty'
                ORDER BY cur.rent_close DESC;"
            );
        }
    }
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS critical_charges");
    }
};
