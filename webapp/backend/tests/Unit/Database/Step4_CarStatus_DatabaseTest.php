<?php

namespace Tests\Unit\Database;

use App\Models\CarStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Step4_CarStatus_DatabaseTest extends TestCase
{
    use DatabaseTransactions;
    public function test_carstatus_table_exists_in_database()
    {
        $this->assertTrue(
            Schema::hasTable('carstatus'),
            'Az `carstatus` tábla nem jött létre az adatbázisban.'
        );
    }
    public function test_carstatus_table_is_not_empty_after_seeding()
    {
        $this->assertGreaterThan(
            0,
            CarStatus::count(),
            'Az `carstatus` tábla Seedelés után sem tartalmaz adatrekordot.'
        );
    }
    public function test_carstatus_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumns('carstatus', [
            'id',
            'status_name',
            'status_descrip',
            'created_at',
            'updated_at',
        ]));
    }
    public function test_carstatus_table_has_correct_columns_and_types()
    {
        $columns = [
            'id' => 'bigint',
            'status_name' => 'varchar',
            'status_descrip' => 'varchar',
        ];
        foreach ($columns as $column => $type) {
            $this->assertTrue(
                Schema::hasColumn('carstatus', $column),
                "A {$column} oszlop nem jött lére az `carstatus` táblában."
            );

            $this->assertEquals(
                $type,
                Schema::getColumnType('carstatus', $column),
                "A {$column} mező típusa nem egyezik az elvárt (migráció) típussal."
            );
        }
    }
    public function test_carstatus_columns_allows_maximum_and_minimum_migration_lenght()
    {
        $dataMaxLenght = [
            'status_name' => fake()->regexify('[a-z]{50}'),
            'status_descrip' => fake()->regexify('[a-z]{255}'),
        ];
        $dataMinLenght = [
            'status_name' => fake()->regexify('[a-z]{8}'),
            'status_descrip' => fake()->regexify('[a-z]{3}'),
        ];

        $response = $this->postJson('/api/carstatus', $dataMaxLenght);
        $response->assertStatus(201);
        $this->assertDatabaseHas('carstatus', [
            "status_name" => $dataMaxLenght['status_name'],
            "status_descrip" => $dataMaxLenght['status_descrip'],
        ]);

        $response = $this->postJson('/api/carstatus', $dataMinLenght);
        $response->assertStatus(201);
        $this->assertDatabaseHas('carstatus', [
            "status_name" => $dataMinLenght['status_name'],
            "status_descrip" => $dataMinLenght['status_descrip'],
        ]);
    }
    ## Negatív teszt ##
    public function test_carstatus_columns_cannot_allows_out_of_maximum_and_minimum_migration_lenght_and_format()
    {
        $failedMaxData = [
            'status_name' => fake()->regexify('[a-z]{51}'),
            'status_descrip' => fake()->regexify('[a-z]{256}'),
        ];
        $response = $this->postJson('/api/carstatus', $failedMaxData);
        $response->assertStatus(422);

        $response->assertJsonValidationErrors(
            [
                'status_name',
                'status_descrip',
            ]
        );

        $failedMinData = [
            'status_name' => fake()->regexify('[a-z]{7}'),
        ];
        $response = $this->postJson('/api/carstatus', $failedMinData);
        $response->assertStatus(422);

        $response->assertJsonValidationErrors(
            [
                'status_name',
            ]
        );
    }
}
