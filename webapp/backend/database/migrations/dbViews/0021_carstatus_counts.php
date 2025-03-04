<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cars') && Schema::hasTable('carstatus') && Schema::hasTable('car_user_rents') && Schema::hasTable('bills')) {
            DB::statement(
                "CREATE OR REPLACE VIEW carstatus_counts AS
                SELECT 
                    CASE 
                        WHEN SUM(CASE WHEN b.bill_type = 'charging_penalty' THEN 1 ELSE 0 END) > 0 
                        THEN CONCAT(cs.status_name, ' (', SUM(CASE WHEN b.bill_type = 'charging_penalty' THEN 1 ELSE 0 END), ' büntetés)')
                        ELSE cs.status_name
                    END AS status_name,
                    c.status AS status_num, 
                    COUNT(DISTINCT c.id) AS status_db
                FROM cars c
                INNER JOIN carstatus cs ON c.status = cs.id
                LEFT JOIN bills b ON c.id = b.car_id
                GROUP BY c.status, cs.status_name

                UNION ALL
                SELECT
                    'Összes bérlés',
                    0 AS status_num,
                    COUNT(*) AS status_db
                FROM car_user_rents
                WHERE rentstatus = 2;"
            );
        }
    }
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS carstatus_counts");
    }
};
