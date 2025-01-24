<?php

namespace Tests\Unit\Database;

use App\Models\Subscription;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Step3_Subscription_DatabaseTest extends TestCase
{

    public function test_subscriptions_table_exists_in_database()
    {
        $this->assertTrue(
            Schema::hasTable('subscriptions'),
            'Az `subscriptions` tábla nem jött létre az adatbázisban.'
        );
    }
    public function test_subscriptions_table_is_not_empty_after_seeding()
    {
        $this->assertGreaterThan(
            0,
            Subscription::count(),
            'Az `subscriptions` tábla Seedelés után sem tartalmaz adatrekordot.'
        );
    }
    public function test_subscriptions_table_has_correct_columns()
    {

        $this->assertTrue(Schema::hasColumns('subscriptions', [
            'id',
            'sub_name',
            'sub_monthly',
            'sub_annual',
            'created_at',
            'updated_at',
        ]));
    }
    public function test_subscriptions_table_has_correct_columns_and_types()
    {
        $columns = [
            'id' => 'bigint',
            'sub_name' => 'varchar',
            'sub_monthly' => 'int',
            'sub_annual' => 'int',
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
        ];
        foreach ($columns as $column => $type) {
            $this->assertTrue(
                Schema::hasColumn('subscriptions', $column),
                "A {$column} oszlop nem jött lére az `subscriptions` táblában."
            );

            $this->assertEquals(
                $type,
                Schema::getColumnType('subscriptions', $column),
                "A {$column} mező típusa nem egyezik az elvárt (migráció) típussal."
            );
        }
    }
    public function test_subscriptions_columns_allows_maximum_and_minimum_migration_lenght()
    {
        $dataMaxLenght = [
            'sub_name' => fake()->regexify('[A-Za-z]{50}'),
            'sub_monthly'=>10000,
            'sub_annual'=>100000,
        ];
        $dataMinLenght = [
            'sub_name' => fake()->regexify('[A-Za-z]{5}'),
            'sub_monthly' => 0,
            'sub_annual' => 0,
        ];

        $response = $this->postJson('/api/subscriptions', $dataMaxLenght);
        $response->assertStatus(201);
        $this->assertDatabaseHas('subscriptions', [
            "sub_name" => $dataMaxLenght['sub_name'],
            "sub_monthly" => $dataMaxLenght['sub_monthly'],
            "sub_annual" => $dataMaxLenght['sub_annual'],
        ]);

        $response = $this->postJson('/api/subscriptions', $dataMinLenght);
        $response->assertStatus(201);
        $this->assertDatabaseHas('subscriptions', [
            "sub_name" => $dataMinLenght['sub_name'],
            "sub_monthly" => $dataMinLenght['sub_monthly'],
            "sub_annual" => $dataMinLenght['sub_annual'],
        ]);
    }
    ## Negatív tesztek ##
    public function test_subscriptions_name_field_invalid_validation()
    {
        $failedData = ['sub_name' => str_repeat('a', 51)];
        $response = $this->postJson('/api/subscriptions', $failedData);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['sub_name']);
    }

    public function test_subscriptions_monthly_field_validation()
    {
        $failedData = ['sub_monthly' => 10001];
        $response = $this->postJson('/api/subscriptions', $failedData);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['sub_monthly']);
    }

    public function test_subscriptions_annual_field_validation()
    {
        $failedData = ['sub_annual' => 100001];
        $response = $this->postJson('/api/subscriptions', $failedData);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['sub_annual']);
    }
}
