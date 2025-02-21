<?php

namespace Tests\Unit\Database;

use App\Models\Price;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Step12_Prices_DatabaseTest extends TestCase
{
    use DatabaseTransactions;
    public function test_prices_table_exists_in_database()
    {
        $this->assertTrue(
            Schema::hasTable('prices'),
            'Az `prices` tábla nem jött létre az adatbázisban.'
        );
    }
    public function test_prices_table_is_not_empty_after_seeding()
    {
        $this->assertGreaterThan(
            0,
            Price::count(),
            'Az `prices` tábla Seedelés után sem tartalmaz adatrekordot.'
        );
    }
    public function test_prices_table_has_correct_columns_and_types()
    {
        $columns = [
            "id" => 'bigint',
            "sub_id" => 'bigint',
            "category_class" => 'bigint',
            "rental_start" => 'int',
            "driving_minutes" => 'int',
            "discounted_driving" => 'int',
            "parking_minutes" => 'int',
            "reserv_minutes" => 'int',
            "disc_parking_minutes" => 'int',
            "daily_fee" => 'int',
            "daily_km_limit" => 'int',
            "km_fee" => 'int',
            "airport_out_fee" => 'int',
            "airport_in_fee" => 'int',
            "airport_out_terminal_fee" => 'int',
            "airport_in_terminal_fee" => 'int',
            "zone_opening_fee" => 'int',
            "zone_closing_fee" => 'int',
            "three_hour_fee" => 'int',
            "six_hour_fee" => 'int',
            "twelve_hour_fee" => 'int',
            "weekend_daily_fee" => 'int',
        ];
        foreach ($columns as $column => $type) {
            $this->assertTrue(
                Schema::hasColumn('prices', $column),
                "A {$column} oszlop nem jött lére az `prices` táblában."
            );

            $this->assertEquals(
                $type,
                Schema::getColumnType('prices', $column),
                "A {$column} mező típusa nem egyezik az elvárt (migráció) típussal."
            );
        }
    }

    public function test_prices_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumns('prices', [
            "id",
            "sub_id",
            "category_class",
            "rental_start",
            "driving_minutes",
            "discounted_driving",
            "parking_minutes",
            "reserv_minutes",
            "disc_parking_minutes",
            "daily_fee",
            "daily_km_limit",
            "km_fee",
            "airport_out_fee",
            "airport_in_fee",
            "airport_out_terminal_fee",
            "airport_in_terminal_fee",
            "zone_opening_fee",
            "zone_closing_fee",
            "three_hour_fee",
            "six_hour_fee",
            "twelve_hour_fee",
            "weekend_daily_fee",
        ]));
    }
}
