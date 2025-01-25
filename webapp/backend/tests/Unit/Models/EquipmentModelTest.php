<?php

namespace Tests\Unit\Models;

use App\Models\Equipment;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EquipmentModelTest extends TestCase
{
    public function test_equipments_table_has_created()
    {
        $this->assertTrue(
            Schema::hasTable('equipments'),
            'Az `equipments` tábla nem jött létre az adatbázisban.'
        );
    }

    public function test_equipment_seeder_created_records_into_equipments_table()
    {

        $this->assertGreaterThan(
            0,
            Equipment::count(),
            'Az `equipments` tábla Seedelés után sem tartalmaz adatrekordot.'
        );
    }
    public function test_prices_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumns('equipments', [
            "id",
            "reversing_camera",
            "lane_keep_assist",
            "adaptive_cruise_control",
            "parking_sensors",
            "multifunction_wheel",
        ]));
    }
    public function test_equipment_factory_created_valid_data_key_value_pairs()
    {
        $equipment = Equipment::factory()->create();

        $this->assertIsBool($equipment->reversing_camera);
        $this->assertIsBool($equipment->lane_keep_assist);
        $this->assertIsBool($equipment->adaptive_cruise_control);
        $this->assertIsBool($equipment->parking_sensors);
        $this->assertIsBool($equipment->multifunction_wheel);
    }
}
