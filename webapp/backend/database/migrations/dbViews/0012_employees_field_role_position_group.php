<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('employees')) {
            DB::statement(
                "CREATE OR REPLACE VIEW field_role_position_group AS
                SELECT 
                    `field`,
                    `role`,
                    `position`,
                     COUNT(*) AS employees_number
                FROM `employees`
                GROUP BY
                    `position`,
                    `field`,
                    `role`
                ORDER BY 
                    `field`
                ASC;"
            );
        }
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS billsCsoportositva");
    }
};
