<?php

namespace Tests\Unit\Database;

use App\Models\Fleet;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Step1_Fleets_DatabaseTest extends TestCase
{
    use DatabaseTransactions;
    public function test_fleets_table_exists_in_database()
    {
        $this->assertTrue(
            Schema::hasTable('fleets'),
            'Az `fleets` tábla nem jött létre az adatbázisban.'
        );
    }
    public function test_fleets_table_is_not_empty_after_seeding()
    {
        $this->assertGreaterThan(
            0,
            Fleet::count(),
            'Az `fleets` tábla Seedelés után sem tartalmaz adatrekordot.'
        );
    }
    public function test_fleets_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumns('fleets', [
            'id',
            'manufacturer',
            'carmodel',
            'driving_range',
            'motor_power',
            'top_speed',
            'tire_size',

        ]));
    }
    public function test_fleets_table_has_correct_columns_and_types()
    {
        $columns = [
            'id' => 'bigint',
            'manufacturer' => 'varchar',
            'carmodel' => 'varchar',
            'driving_range' => 'int',
            'motor_power' => 'int',
            'top_speed' => 'int',
            'tire_size' => 'varchar',
        ];
        foreach ($columns as $column => $type) {
            $this->assertTrue(
                Schema::hasColumn('fleets', $column),
                "A {$column} oszlop nem jött lére az `fleets` táblában."
            );

            $this->assertEquals(
                $type,
                Schema::getColumnType('fleets', $column),
                "A {$column} mező típusa nem egyezik az elvárt (migráció) típussal."
            );
        }
    }
    public function test_fleets_columns_allows_maximum_and_minimum_migration_lenght()
    {
        $dataMaxLenght = [
            'manufacturer' => str_repeat('L', 30),
            'carmodel' => str_repeat('C', 30),
            'driving_range' => 1000,
            'motor_power' => 500,
            'top_speed' => 300,
            'tire_size' => '165|65-R99',
        ];
        $dataMinLenght = [
            'manufacturer' => str_repeat('L', 2),
            'carmodel' => str_repeat('C', 2),
            'driving_range' => 125,
            'motor_power' => 18,
            'top_speed' => 130,
            'tire_size' => '165|65-R10',
        ];

        $response = $this->postJson('/api/fleets', $dataMaxLenght);
        $response->assertStatus(201);
        $this->assertDatabaseHas('fleets', [
            "manufacturer" => $dataMaxLenght['manufacturer'],
            "carmodel" => $dataMaxLenght['carmodel'],
            "driving_range" => $dataMaxLenght['driving_range'],
            "motor_power" => $dataMaxLenght['motor_power'],
            "top_speed" => $dataMaxLenght['top_speed'],
            "tire_size" => $dataMaxLenght['tire_size'],
        ]);

        $response = $this->postJson('/api/fleets', $dataMinLenght);
        $response->assertStatus(201);
        $this->assertDatabaseHas('fleets', [
            "manufacturer" => $dataMinLenght['manufacturer'],
            "carmodel" => $dataMinLenght['carmodel'],
            "driving_range" => $dataMinLenght['driving_range'],
            "motor_power" => $dataMinLenght['motor_power'],
            "top_speed" => $dataMinLenght['top_speed'],
            "tire_size" => $dataMinLenght['tire_size'],
        ]);
    }
    public function test_fleets_columns_cannot_allows_out_of_maximum_and_minimum_migration_lenght()
    {
        $failedData = [
            'manufacturer' => str_repeat('L', 31),
            'carmodel' => str_repeat('C', 31),
            'driving_range' => 1001,
            'motor_power' => 501,
            'top_speed' => 301,
            'tire_size' => fake()->numberBetween(100, 300) . '|' . fake()->numberBetween(65, 100) . '-' . fake()->numberBetween(15, 20) . str_repeat('X', 1),
        ];
        $response = $this->postJson('/api/fleets', $failedData);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(
            [
                'manufacturer',
                'carmodel',
                'driving_range',
                'motor_power',
                'top_speed',
                'tire_size',
            ]
        );
    }
}
