<?php

namespace Tests\Unit\Database;

use App\Models\Dailyrental;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Step13_DailyRentals_DatabaseTest extends TestCase
{
    public function test_dailyrentals_table_exists_in_database()
    {
        $this->assertTrue(
            Schema::hasTable('dailyrentals'),
            'Az `dailyrentals` tábla nem jött létre az adatbázisban.'
        );
    }
    public function test_dailyrentals_table_is_not_empty_after_seeding()
    {
        $this->assertGreaterThan(
            0,
            Dailyrental::count(),
            'Az `dailyrentals` tábla Seedelés után sem tartalmaz adatrekordot.'
        );
    }
    public function test_dailyrentals_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumns('dailyrentals', [
            "id",
            'prices_id',
            'category_class',
        ]));
    }
    public function test_dailyrentals_table_has_correct_columns_and_types()
    {
        $columns = [
            "id" => 'bigint',
            'prices_id' => 'bigint',
            'category_class' => 'bigint',
        ];
        foreach ($columns as $column => $type) {
            $this->assertTrue(
                Schema::hasColumn('dailyrentals', $column),
                "A {$column} oszlop nem jött lére az `dailyrentals` táblában."
            );

            $this->assertEquals(
                $type,
                Schema::getColumnType('dailyrentals', $column),
                "A {$column} mező típusa nem egyezik az elvárt (migráció) típussal."
            );
        }
    }

   
}
