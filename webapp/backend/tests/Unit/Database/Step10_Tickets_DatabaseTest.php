<?php

namespace Tests\Unit\Database;

use App\Models\Ticket;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Step10_Tickets_DatabaseTest extends TestCase
{

    public function test_tickets_table_exists_in_database()
    {
        $this->assertTrue(
            Schema::hasTable('tickets'),
            'Az `tickets` tábla nem jött létre az adatbázisban.'
        );
    }

    public function test_tickets_table_is_not_empty_after_seeding()
    {
        $this->assertGreaterThan(
            0,
            Ticket::count(),
            'Az `tickets` tábla Seedelés után sem tartalmaz adatrekordot.'
        );
    }

    public function test_tickets_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumns('tickets', [
            'id',
            'car_id',
            'status_id',
            'description',
            'created_at',
            'updated_at',
        ]));
    }

    public function test_tickets_table_has_correct_columns_and_types()
    {
        $columns = [
            'id' => 'bigint',
            'car_id' => 'bigint',
            'status_id' => 'bigint',
            'description' => 'varchar',
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
        ];

        foreach ($columns as $column => $type) {
            $this->assertTrue(
                Schema::hasColumn('tickets', $column),
                "A {$column} oszlop nem jött létre az `tickets` táblában."
            );

            $this->assertEquals(
                $type,
                Schema::getColumnType('tickets', $column),
                "A {$column} mező típusa nem egyezik az elvárt (migráció) típussal."
            );
        }
    }
    public function test_tickets_table_foreign_keys()
    {
        $this->assertTrue(
            Schema::hasColumn('tickets', 'car_id'),
            'Az `tickets` táblában nem található a `car_id` idegen kulcs.'
        );
        $this->assertTrue(
            Schema::hasColumn('tickets', 'status_id'),
            'Az `tickets` táblában nem található a `status_id` idegen kulcs.'
        );
    }

    public function test_tickets_table_seeding_random_data()
    {
        $tickets = Ticket::all();
        $this->assertGreaterThan(0, $tickets->count(), 'Nincsenek rekordok a `tickets` táblában.');

        foreach ($tickets as $ticket) {
            $this->assertNotNull($ticket->car_id, 'A `car_id` mező értéke hiányzik.');
            $this->assertNotNull($ticket->status_id, 'A `status_id` mező értéke hiányzik.');
            $this->assertNotNull($ticket->description, 'A `description` mező értéke hiányzik.');
            $this->assertLessThanOrEqual(255, strlen($ticket->description), 'A `description` mező hossza meghaladja a 255 karaktert.');
        }
    }
}
