<?php

namespace Tests\Unit\Database;

use App\Models\Car;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Step5_Cars_DatabaseTest extends TestCase
{
    use DatabaseTransactions;
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
            'manufactured',
        ]));
    }
    public function test_cars_table_has_correct_values_in_columns()
    { # Ha létezik hibás adat, akkor true értékre fut!

        $invalidPowerPercent = DB::table('cars')
            ->where('power_percent', '<', 0)
            ->orWhere('power_percent', '>', 100)
            ->exists();
        $invalidPowerKiloWatt = DB::table('cars')
            ->where('power_kw', '<', 0)
            ->orWhere('power_kw', '>', 100)
            ->exists();

        $highestEstimatedRange = DB::table('fleets')->max('driving_range');
        $invalidEstimatedRange = DB::table('cars')
            ->where('estimated_range', '<', 0)
            ->orWhere('estimated_range', '>', $highestEstimatedRange)
            ->exists();

        $this->assertFalse($invalidPowerPercent, "Találtunk HIBÁS (0% alatti) VAGY (100% feletti) értéket a power_percent oszlopban!");
        $this->assertFalse($invalidPowerKiloWatt, "Találtunk HIBÁS (0 kw) alatti VAGY (100 kw feletti) értéket a power_kw oszlopban!");
        $this->assertFalse($invalidEstimatedRange, "Találtunk HIBÁS " . $highestEstimatedRange . "megtehető km távú értéket az [estimated_range] oszlopban!");
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
            'manufactured' => 'year',
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
