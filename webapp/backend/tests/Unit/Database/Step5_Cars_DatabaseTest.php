<?php

namespace Tests\Unit\Database;

use App\Models\Car;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Step5_Cars_DatabaseTest extends TestCase
{
    public function test_cars_table_exists_in_database()
    {
        $this->assertTrue(
            Schema::hasTable('cars'),
            'Az `cars` tábla nem jött létre az adatbázisban.'
        );
    }
    public function test_cars_table_is_not_empty_after_seeding()
    {
        $this->assertGreaterThan(
            0,
            Car::count(),
            'Az `cars` tábla Seedelés után sem tartalmaz adatrekordot.'
        );
    }
    public function test_cars_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumns('cars', [
            'id',
            'plate',
            'power_percent',
            'estimated_range',
            'status',
            'category_id',
            'equipment_class',
            'fleet_id',
            'odometer',
            'manufacturing_year',
        ]));
    }
    public function test_cars_table_has_correct_columns_and_types()
    {
        $columns = [
            'id' => 'bigint',
            'plate' => 'varchar',
            'power_percent' => 'float',
            'estimated_range' => 'float',
            'status' => 'bigint',
            'category_id' => 'bigint',
            'equipment_class' => 'bigint',
            'fleet_id' => 'bigint',
            'odometer' => 'int',
            'manufacturing_year' => 'year',
        ];
        foreach ($columns as $column => $type) {
            $this->assertTrue(
                Schema::hasColumn('cars', $column),
                "A {$column} oszlop nem jött lére az `cars` táblában."
            );

            $this->assertEquals(
                $type,
                Schema::getColumnType('cars', $column),
                "A {$column} mező típusa nem egyezik az elvárt (migráció) típussal."
            );
        }
    }
}
