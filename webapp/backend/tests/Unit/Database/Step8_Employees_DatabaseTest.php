<?php

namespace Tests\Unit\Database;

use App\Models\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Step8_Employees_DatabaseTest extends TestCase
{
    use DatabaseTransactions;
    public function test_employees_table_exists_in_database()
    {
        $this->assertTrue(
            Schema::hasTable('employees'),
            'Az `employees` tábla nem jött létre az adatbázisban.'
        );
    }
    public function test_employees_table_is_not_empty_after_seeding()
    {
        $this->assertGreaterThan(
            0,
            Employee::count(),
            'Az `employees` tábla seedelés után sem tartalmaz adatrekordot.'
        );
    }
    public function test_employees_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumns('employees', [
            'id',
            'person_id',
            'field',
            'role',
            'position',
            'salary_type',
            'salary',
            'hire_date',
        ]));
    }
    public function test_employees_table_has_correct_columns_and_types()
    {
        $columns = [
            'id' => 'bigint',
            'person_id' => 'bigint',
            'field' => 'varchar',
            'role' => 'varchar',
            'position' => 'varchar',
            'salary_type' => 'enum',
            'salary' => 'int',
            'hire_date' => 'date',
        ];

        foreach ($columns as $column => $type) {
            $this->assertTrue(Schema::hasColumn('employees', $column), "A {$column} oszlop nem jött létre az `employees` táblában.");
            $this->assertEquals($type, Schema::getColumnType('employees', $column), "A {$column} mező típusa nem egyezik az elvárt típussal.");
        }
    }
}
