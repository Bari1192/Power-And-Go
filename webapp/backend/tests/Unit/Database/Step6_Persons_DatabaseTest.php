<?php

namespace Tests\Unit\Database;

use App\Models\Person;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Step6_Persons_DatabaseTest extends TestCase
{
    public function test_persons_table_exists_in_database()
    {
        $this->assertTrue(
            Schema::hasTable('persons'),
            'Az `persons` tábla nem jött létre az adatbázisban.'
        );
    }
    public function test_persons_table_is_not_empty_after_seeding()
    {
        $this->assertGreaterThan(
            0,
            Person::count(),
            'Az `persons` tábla Seedelés után sem tartalmaz adatrekordot.'
        );
    }
    public function test_persons_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumns('persons', [
            'id',
            'person_password',
            'id_card',
            'driving_license',
            'license_start_date',
            'license_end_date',
            'firstname',
            'lastname',
            'birth_date',
            'phone',
            'email',
        ]));
    }
    public function test_persons_table_has_correct_columns_and_types()
    {
        $columns = [
            'id' => 'bigint',
            'person_password' => 'varchar',
            'id_card' => 'varchar',
            'driving_license' => 'varchar',
            'license_start_date' => 'date',
            'license_end_date' => 'date',
            'firstname' => 'varchar',
            'lastname' => 'varchar',
            'birth_date' => 'date',
            'phone' => 'varchar',
            'email' => 'varchar',
        ];
        foreach ($columns as $column => $type) {
            $this->assertTrue(
                Schema::hasColumn('persons', $column),
                "A {$column} oszlop nem jött lére az `persons` táblában."
            );

            $this->assertEquals(
                $type,
                Schema::getColumnType('persons', $column),
                "A {$column} mező típusa nem egyezik az elvárt (migráció) típussal."
            );
        }
    }
}
