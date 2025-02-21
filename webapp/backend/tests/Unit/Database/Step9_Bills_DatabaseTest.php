<?php

namespace Tests\Unit\Database;

use App\Models\Bill;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Step9_Bills_DatabaseTest extends TestCase
{
    use DatabaseTransactions;
    public function test_bills_table_exists_in_database()
    {
        $this->assertTrue(
            Schema::hasTable('bills'),
            'Az `bills` tábla nem jött létre az adatbázisban.'
        );
    }

    public function test_bills_table_is_not_empty_after_seeding()
    {
        $this->assertGreaterThan(
            0,
            Bill::count(),
            'Az `bills` tábla seedelés után sem tartalmaz adatrekordot.'
        );
    }

    public function test_bills_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumns('bills', [
            'id',
            'bill_type',
            'user_id',
            'person_id',
            'car_id',
            'total_cost',
            'distance',
            'parking_minutes',
            'driving_minutes',
            'rent_start',
            'rent_close',
            'invoice_date',
            'invoice_status',
        ]));
    }
    public function test_bills_table_has_correct_columns_and_types()
    {
        $columns = [
            'id' => 'bigint',
            'bill_type' => 'enum',
            'user_id' => 'bigint',
            'person_id' => 'bigint',
            'car_id' => 'bigint',
            'total_cost' => 'int',
            'distance' => 'int',
            'parking_minutes' => 'int',
            'driving_minutes' => 'int',
            'rent_start' => 'datetime',
            'rent_close' => 'datetime',
            'invoice_date' => 'timestamp',
            'invoice_status' => 'enum',
        ];

        foreach ($columns as $column => $type) {
            $this->assertTrue(
                Schema::hasColumn('bills', $column),
                "A {$column} oszlop nem jött létre a `bills` táblában."
            );

            $this->assertEquals(
                $type,
                Schema::getColumnType('bills', $column),
                "A {$column} mező típusa nem egyezik az elvárt (migráció) típussal."
            );
        }
    }
}
