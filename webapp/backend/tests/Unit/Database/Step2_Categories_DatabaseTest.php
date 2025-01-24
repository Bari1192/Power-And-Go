<?php

namespace Tests\Unit\Database;

use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Step2_Categories_DatabaseTest extends TestCase
{
    public function test_categories_table_exists_in_database()
    {
        $this->assertTrue(
            Schema::hasTable('categories'),
            'Az `categories` tábla nem jött létre az adatbázisban.'
        );
    }
    public function test_categories_table_is_not_empty_after_seeding()
    {
        $this->assertGreaterThan(
            0,
            Category::count(),
            'Az `categories` tábla Seedelés után sem tartalmaz adatrekordot.'
        );
    }
    public function test_categories_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumns('categories', [
            'id',
            'category_class',
            'motor_power',
        ]));
    }
    public function test_categories_table_has_correct_columns_and_types()
    {
        $columns = [
            'id' => 'bigint',
            'category_class' => 'tinyint',
            'motor_power' => 'int',
        ];
        foreach ($columns as $column => $type) {
            $this->assertTrue(
                Schema::hasColumn('categories', $column),
                "A {$column} oszlop nem jött lére az `categories` táblában."
            );

            $this->assertEquals(
                $type,
                Schema::getColumnType('categories', $column),
                "A {$column} mező típusa nem egyezik az elvárt (migráció) típussal."
            );
        }
    }
    public function test_categories_columns_allows_maximum_and_minimum_migration_lenght()
    {
        $dataMaxLenght = [
            'category_class' => 20,
            'motor_power' => 500,
        ];
        $dataMinLenght = [
            'category_class' => 1,
            'motor_power' => 18,
        ];

        $response = $this->postJson('/api/categories', $dataMaxLenght);
        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', [
            "motor_power" => $dataMaxLenght['motor_power'],
            "category_class" => $dataMaxLenght['category_class'],
        ]);

        $response = $this->postJson('/api/categories', $dataMinLenght);
        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', [
            "category_class" => $dataMinLenght['category_class'],
            "motor_power" => $dataMinLenght['motor_power'],
        ]);
    }
    ## Negatív teszt ##
    public function test_categories_columns_cannot_allows_out_of_maximum_and_minimum_migration_lenght_and_format()
    {
        $failedData = [
            'category_class' => 501,
            'motor_power' => 501,
        ];
        $response = $this->postJson('/api/categories', $failedData);
        $response->assertStatus(422);

        $response->assertJsonValidationErrors(
            [
                'category_class',
                'motor_power',
            ]
        );
    }
}
