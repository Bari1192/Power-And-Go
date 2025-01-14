<?php

namespace Tests\Unit;

use App\Models\Equipment;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EquipmentDatabaseTest extends TestCase
{
    public function equipments_table_exists_in_database()
    {
        $this->assertTrue(Schema::hasTable('equipments'), 
        'Az `Equipments` tábla nem jött létre az adatbázisban.');
    }
    public function equipments_table_has_correct_columns_and_types()
    {
        $columns = [
            'reversing_camera' => 'boolean',
            'lane_keep_assist' => 'boolean',
            'adaptive_cruise_control' => 'boolean',
            'parking_sensors' => 'boolean',
            'multifunction_wheel' => 'boolean',
        ]; 
        foreach ($columns as $column => $type) {
            $this->assertTrue(
                Schema::hasColumn('equipments', $column),
                "A {$column} oszlop nem jött lére az `equipments` táblában."
            );

            $this->assertEquals(
                $type,
                Schema::getColumnType('equipments', $column),
                "A {$column} mező típusa nem egyezik az elvárt (migráció) típussal."
            );
        }
    }

    public function test_equipments_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumns('equipments', [
            'id',
            'reversing_camera',
            'lane_keep_assist',
            'adaptive_cruise_control',
            'parking_sensors',
            'multifunction_wheel'
        ]));
    }
    public function equipments_table_is_not_empty_after_seeding()
    {
        $this->assertGreaterThan(
            0,
            Equipment::count(),
            'Az `equipments` tábla Seedelés után sem tartalmaz adatrekordot.'
        );
    }
}
